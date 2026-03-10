<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\TApproval;
use app\components\DeltaGeneralBehavior;

/**
 * This is the model class for table "m_mtrg_setup".
 *
 * @property integer $mtrg_setup_id
 * @property string $tanggal
 * @property string $kategori_proses
 * @property string $jenis_proses
 * @property string $jenis_kayu
 * @property string $grade
 * @property double $plan_harian
 * @property string $satuan_harian
 * @property double $jumlah_aktual
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $sequence
 */
class MMtrgSetup extends ActiveRecord
{
    public $jumlah_aktual_m3, $jumlah_bulanan_m3;

    const
        INPUT           = 'INPUT',
        OUTPUT          = 'OUTPUT';
    const
        KATEGORI_DRYING         = 'DRYING',
        KATEGORI_ROTARY         = 'ROTARY SENGON',
        KATEGORI_CORE_BUILDER   = 'CORE BUILDER',
        KATEGORI_PLYTECH        = 'PLYTECH',
        KATEGORI_REPAIR         = 'REPAIR',
        KATEGORI_SETTING        = 'SETTING';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_mtrg_setup';
    }
    
    public function behaviors(){
		return [DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tanggal', 'kategori_proses', 'jenis_proses', 'jenis_kayu', 'created_at', 'created_by', 'updated_at', 'updated_by', 'plan_harian'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['plan_harian', 'jumlah_aktual', 'sequence'], 'number'],
            [['active'], 'boolean'],
            [['created_by', 'updated_by'], 'integer'],
            [['kategori_proses', 'jenis_proses', 'jenis_kayu', 'grade', 'satuan_harian'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'mtrg_setup_id' => Yii::t('app', 'Mtrg Setup'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'kategori_proses' => Yii::t('app', 'Kategori Proses'),
                'jenis_proses' => Yii::t('app', 'Jenis Proses'),
                'jenis_kayu' => Yii::t('app', 'Jenis Kayu'),
                'grade' => Yii::t('app', 'Grade'),
                'plan_harian' => Yii::t('app', 'Plan Harian'),
                'satuan_harian' => Yii::t('app', 'Satuan Harian'),
                'jumlah_aktual' => Yii::t('app', 'Jumlah Aktual'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'sequence' => Yii::t('app', 'Sequence'),
        ];
    }

    /**
     * @param int $sub
     * @param null $set_date
     * @return string
     */
    public static function getActiveDate($sub = 0, $set_date = null)
    {
        if($set_date !== null) {
            $date           = explode(' ', $set_date)[0];
            $current_date   = $set_date;
        }else {
            $date           = date('Y-m-d');
            $current_date   = date('Y-m-d H:i:s');
        }

        if($current_date <= $date . ' 09:00:00' && $current_date >= $date . ' 00:00:00') {
            --$sub;
        }

        return $sub === 0 ? $date : date_create($date)
                                        ->add(date_interval_create_from_date_string("$sub days"))
                                        ->format('Y-m-d');
    }

    public static function getFirstLastDate($tanggal = null)
    {
        if($tanggal !== null) {
            $date       = explode('-', $tanggal);
            $month      = $date[1];
            $year       = (int)$date[1] === 12 ? (int)$date[0] + 1 : $date[0];
            $next_month = (int)$date[1] === 12 ? 1 : (int)$date[1] + 1;
            $next_month = $next_month < 10 ? "0$next_month" : $next_month;
            return [date("Y-$month-01 09:00:00"), date("$year-$next_month-01 08:59:59")];
        }

        return [date('Y-m-01 09:00:00'), date('Y-m-01 08:59:59', strtotime('first day of +1 month'))];
    }

    /**
     * @param $grade
     * @param $jenis_proses
     * @param $jenis_kayu
     * @param $kategori_proses
     * @param null $tanggal
     * @return ActiveQuery
     */
    public static function getSummary($grade, $jenis_proses, $jenis_kayu, $kategori_proses, $tanggal = null)
    {
        $clause = self::getFirstLastDate($tanggal);
        $params = [
            'jenis_proses' => $jenis_proses,
            'jenis_kayu' => $jenis_kayu,
            'kategori_proses' => $kategori_proses
        ];

        if($grade !== '*') {
            $params['grade'] = $grade;
        }
        return self::find()->where($params)->andWhere(['between', 'tanggal', $clause[0], $clause[1]]);
    }

    /**
     * @param $kategori_proses
     * @param $tanggal
     * @return int
     * @throws Exception
     */
    public static function getHariKerja($kategori_proses, $tanggal = null)
    {
        $clause = self::getFirstLastDate($tanggal);
        $sql = "SELECT DISTINCT(tanggal) FROM m_mtrg_setup WHERE kategori_proses = '". $kategori_proses ."' AND tanggal BETWEEN '$clause[0]' AND '$clause[1]'";
        return Yii::$app->db->createCommand($sql)->query()->count();
    }

    public static function getActiveShift($kategori_proses)
    {
        $io = TMtrgInOut::find()
            ->where(['kategori_proses' => $kategori_proses, 'status_approval' => TApproval::STATUS_APPROVED])
            ->orderBy(['tanggal_produksi' => SORT_DESC])
            ->one();

        return $io ? $io->shift : '-';
    }

    public static function getNotConfirmed($kategori_proses)
    {
        $data1 = 0;
        if($kategori_proses === MMtrgSetup::KATEGORI_ROTARY) {
            $data1 = TMtrgRotary::find()->where(['status_approval' => TApproval::STATUS_NOT_CONFIRMATED])->count();
        }
        $data2 = TMtrgInOut::find()->where(['status_approval' => TApproval::STATUS_NOT_CONFIRMATED, 'kategori_proses' => $kategori_proses])->count();
        return $data1 + $data2;
    }
}
