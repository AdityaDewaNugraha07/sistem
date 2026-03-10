<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "map_dkg_pmr_pengajuan_pembelianlog".
 *
 * @property integer $map_dkg_id
 * @property integer $dkg_id
 * @property integer $pmr_id
 * @property integer $pengajuan_pembelianlog_id
 *
 * @property TDkg $dkg
 * @property TPengajuanPembelianlog $pengajuanPembelianlog
 * @property TPmr $pmr
 */
class MapDkgPmrPengajuanPembelianlog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map_dkg_pmr_pengajuan_pembelianlog';
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
            [['dkg_id', 'pmr_id', 'pengajuan_pembelianlog_id'], 'integer'],
            [['dkg_id'], 'exist', 'skipOnError' => true, 'targetClass' => TDkg::className(), 'targetAttribute' => ['dkg_id' => 'dkg_id']],
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
                'map_dkg_id' => 'Map Dkg',
                'dkg_id' => 'Dkg',
                'pmr_id' => 'Pmr',
                'pengajuan_pembelianlog_id' => 'Pengajuan Pembelianlog',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDkg()
    {
        return $this->hasOne(TDkg::className(), ['dkg_id' => 'dkg_id']);
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
