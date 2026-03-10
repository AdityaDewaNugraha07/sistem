<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_monitoring_pembelianlog_detail".
 *
 * @property integer $monitoring_pembelianlog_detail_id
 * @property integer $monitoring_pembelianlog_id
 * @property integer $kayu_id
 * @property string $kondisi_global
 * @property double $btg
 * @property double $m3
 * @property double $gr
 * @property double $pecah
 * @property double $cm
 * @property string $keterangan
 *
 * @property MKayu $kayu
 * @property TMonitoringPembelianlog $monitoringPembelianlog
 */
class TMonitoringPembelianlogDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $totalbtg,$totalm3,$totalgr,$totalpecah,$totalcm;
    public static function tableName()
    {
        return 't_monitoring_pembelianlog_detail';
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
            [['monitoring_pembelianlog_id', 'kayu_id', 'kondisi_global', 'btg', 'm3', 'gr', 'pecah', 'cm'], 'required'],
            [['monitoring_pembelianlog_id', 'kayu_id'], 'integer'],
            [['btg', 'm3', 'gr', 'pecah', 'cm'], 'number'],
            [['keterangan'], 'string'],
            [['kondisi_global'], 'string', 'max' => 200],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
            [['monitoring_pembelianlog_id'], 'exist', 'skipOnError' => true, 'targetClass' => TMonitoringPembelianlog::className(), 'targetAttribute' => ['monitoring_pembelianlog_id' => 'monitoring_pembelianlog_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'monitoring_pembelianlog_detail_id' => 'Monitoring Pembelianlog Detail',
                'monitoring_pembelianlog_id' => 'Monitoring Pembelianlog',
                'kayu_id' => 'Kayu',
                'kondisi_global' => 'Kondisi Global',
                'btg' => 'Btg',
                'm3' => 'M3',
                'gr' => 'Gr',
                'pecah' => 'Pecah',
                'cm' => 'Cm',
                'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKayu()
    {
        return $this->hasOne(MKayu::className(), ['kayu_id' => 'kayu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonitoringPembelianlog()
    {
        return $this->hasOne(TMonitoringPembelianlog::className(), ['monitoring_pembelianlog_id' => 'monitoring_pembelianlog_id']);
    }
}
