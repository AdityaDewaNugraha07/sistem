<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "map_permintaan_keputusan_logalam".
 *
 * @property integer $permintaan_keputusan_logalam_id
 * @property integer $pmr_id
 * @property integer $pengajuan_pembelianlog_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property TPengajuanPembelianlog $pengajuanPembelianlog
 * @property TPmr $pmr
 */
class MapPermintaanKeputusanLogalam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map_permintaan_keputusan_logalam';
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
            [['pmr_id', 'pengajuan_pembelianlog_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['pmr_id', 'pengajuan_pembelianlog_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['pengajuan_pembelianlog_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPengajuanPembelianlog::className(), 'targetAttribute' => ['pengajuan_pembelianlog_id' => 'pengajuan_pembelianlog_id']],
            [['pmr_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPmr::className(), 'targetAttribute' => ['pmr_id' => 'pmr_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'permintaan_keputusan_logalam_id' => 'Permintaan Keputusan Logalam',
                'pmr_id' => 'Pmr',
                'pengajuan_pembelianlog_id' => 'Pengajuan Pembelianlog',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanPembelianlog()
    {
        return $this->hasOne(TPengajuanPembelianlog::className(), ['pengajuan_pembelianlog_id' => 'pengajuan_pembelianlog_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPmr()
    {
        return $this->hasOne(TPmr::className(), ['pmr_id' => 'pmr_id']);
    }
}
