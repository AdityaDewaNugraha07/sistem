<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pengajuan_pembelianlog_detail".
 *
 * @property integer $pengajuan_pembelianlog_detail_id
 * @property integer $pengajuan_pembelianlog_id
 * @property string $tipe
 * @property integer $kayu_id
 * @property string $diameter_cm
 * @property integer $qty_batang
 * @property double $qty_m3
 * @property double $harga
 * @property double $keterangan
 *
 * @property MKayu $kayu
 */
class TPengajuanPembelianlogDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_pengajuan_pembelianlog_detail';
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
            [['pengajuan_pembelianlog_id', 'tipe', 'kayu_id', 'diameter_cm', 'qty_batang', 'qty_m3', 'harga'], 'required'],
            [['pengajuan_pembelianlog_id', 'kayu_id', 'qty_batang'], 'integer'],
            [['qty_m3', 'harga'], 'number'],
			[['keterangan'], 'string'],
            [['tipe'], 'string', 'max' => 25],
            [['diameter_cm'], 'string', 'max' => 50],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengajuan_pembelianlog_detail_id' => 'Pengajuan Pembelianlog Detail',
                'pengajuan_pembelianlog_id' => 'Pengajuan Pembelianlog',
                'tipe' => 'Tipe',
                'kayu_id' => 'Kayu',
                'diameter_cm' => 'Diameter Cm',
                'qty_batang' => 'Qty Batang',
                'qty_m3' => 'Qty M3',
                'harga' => 'Harga',
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
}
