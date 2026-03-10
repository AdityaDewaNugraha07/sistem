<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_harga_log".
 *
 * @property integer $harga_id
 * @property integer $log_id
 * @property integer $harga_enduser
 * @property string $harga_tanggal_penetapan
 * @property string $harga_keterangan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property boolean $status_harga
 * @property string $kode
 * @property string $status_approval
 * @property string $approve_reason
 * @property string $reject_reason
 *
 * @property MBrgLog $log
 */
class MHargaLog extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $log_kelompok,$log_kode,$log_nama,$log_satuan_jual;
    public static function tableName()
    {
        return 'm_harga_log';
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
            [['log_id', 'harga_enduser', 'harga_tanggal_penetapan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['log_id', 'harga_enduser', 'created_by', 'updated_by'], 'integer'],
            [['harga_tanggal_penetapan', 'created_at', 'updated_at'], 'safe'],
            [['harga_keterangan', 'approve_reason', 'reject_reason'], 'string'],
            [['active', 'status_harga'], 'boolean'],
            [['kode'], 'string', 'max' => 30],
            [['status_approval'], 'string', 'max' => 20],
            [['log_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgLog::className(), 'targetAttribute' => ['log_id' => 'log_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'harga_id' => 'Harga',
                'log_id' => 'Log',
                'harga_enduser' => 'Harga Enduser',
                'harga_tanggal_penetapan' => 'Harga Tanggal Penetapan',
                'harga_keterangan' => 'Harga Keterangan',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'status_harga' => 'Status Harga',
                'kode' => 'Kode',
                'status_approval' => 'Status Approval',
                'approve_reason' => 'Approve Reason',
                'reject_reason' => 'Reject Reason',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLog()
    {
        return $this->hasOne(MBrgLog::className(), ['log_id' => 'log_id']);
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

    public static function getHargaCurrentEndUser($log_id) 
    {
        $harga      = self::find()
                    ->andWhere(['log_id' => $log_id])
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