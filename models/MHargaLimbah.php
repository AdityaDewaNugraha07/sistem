<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_harga_limbah".
 *
 * @property integer $harga_id
 * @property integer $limbah_id
 * @property integer $harga_enduser
 * @property string $harga_tanggal_penetapan
 * @property string $harga_keterangan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $kode
 *
 * @property MBrgLimbah $limbah
 */
class MHargaLimbah extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $limbah_kelompok,$limbah_kode,$limbah_nama,$limbah_satuan_jual,$limbah_satuan_muat;
    public static function tableName()
    {
        return 'm_harga_limbah';
    }
    
    public function behaviors(){
		return [\app\components\DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['limbah_id', 'harga_enduser', 'harga_tanggal_penetapan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['limbah_id', 'created_by', 'updated_by'], 'integer'],
            [['harga_tanggal_penetapan', 'harga_enduser', 'created_at', 'updated_at'], 'safe'],
            [['harga_keterangan', 'kode', 'status_approval', 'approve_reason', 'reject_reason'], 'string'],
            [['active'], 'boolean'],
            [['limbah_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgLimbah::className(), 'targetAttribute' => ['limbah_id' => 'limbah_id']],
            [['kode'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'harga_id' => Yii::t('app', 'Harga'),
                'limbah_id' => Yii::t('app', 'Limbah'),
                'harga_enduser' => Yii::t('app', 'Harga'),
                'harga_tanggal_penetapan' => Yii::t('app', 'Tanggal Penetapan'),
                'harga_keterangan' => Yii::t('app', 'Keterangan'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'kode' => Yii::t('app', 'Kode'),
                'status_approval' => Yii::t('app', 'Status Approval'),
                'approve_reason' => Yii::t('app', 'Approve Reason'),
                'reject_reason' => Yii::t('app', 'Reject Reason'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLimbah()
    {
        return $this->hasOne(MBrgLimbah::class, ['limbah_id' => 'limbah_id']);
    }
    
    public static function getOptionListTanggal()
    {
        $mod = self::find()->where(['active'=>true])->orderBy('harga_tanggal_penetapan DESC')->all();
        $return = \yii\helpers\ArrayHelper::map($mod, 'harga_tanggal_penetapan', 'harga_tanggal_penetapan');
        foreach($return as $i => $ret){
            $return[$i] = Yii::t('app', 'Price List Tanggal : ').\app\components\DeltaFormatter::formatDateTimeForUser($ret);
        }
        return $return;
    }

    public static function getHargaCurrentEndUser($limbah_id) 
    {
        $harga      = self::find()
                    ->andWhere(['limbah_id' => $limbah_id])
                    ->andWhere(['status_approval' => 'APPROVED'])
                    ->andWhere(['<=', 'harga_tanggal_penetapan', date('Y-m-d')])
                    ->orderBy(['harga_tanggal_penetapan' => SORT_DESC])
                    ->one();
        return $harga['harga_enduser'];
    }

    public static function getTanggalBerlaku()
    {
        return self::find()
        ->andWhere(['status_approval' => 'APPROVED'])
        ->andWhere(['<=', 'harga_tanggal_penetapan', date('Y-m-d')])
        ->orderBy(['harga_tanggal_penetapan' => SORT_DESC])
        ->max('harga_tanggal_penetapan');
    }
}
