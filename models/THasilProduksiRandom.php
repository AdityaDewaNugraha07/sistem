<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_hasil_produksi_random".
 *
 * @property integer $hasil_produksi_random_id
 * @property integer $hasil_produksi_id
 * @property double $qty
 * @property string $qty_satuan
 * @property double $p
 * @property string $p_satuan
 * @property double $l
 * @property string $l_satuan
 * @property double $t
 * @property string $t_satuan
 * @property double $kapasitas_kubikasi
 * @property string $keterangan
 *
 * @property THasilProduksi $hasilProduksi
 */
class THasilProduksiRandom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kapasitas_kubikasi_display;
    public static function tableName()
    {
        return 't_hasil_produksi_random';
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
            [['hasil_produksi_id', 'p_satuan', 'l_satuan', 't_satuan'], 'required'],
            [['hasil_produksi_id'], 'integer'],
            [['qty', 'p', 'l', 't', 'kapasitas_kubikasi'], 'number'],
            [['keterangan'], 'string'],
            [['qty_satuan', 'p_satuan', 'l_satuan', 't_satuan'], 'string', 'max' => 50],
            [['hasil_produksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => THasilProduksi::className(), 'targetAttribute' => ['hasil_produksi_id' => 'hasil_produksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'hasil_produksi_random_id' => 'Hasil Produksi Random',
                'hasil_produksi_id' => 'Hasil Produksi',
                'qty' => 'Qty',
                'qty_satuan' => 'Qty Satuan',
                'p' => 'P',
                'p_satuan' => 'P Satuan',
                'l' => 'L',
                'l_satuan' => 'L Satuan',
                't' => 'T',
                't_satuan' => 'T Satuan',
                'kapasitas_kubikasi' => 'Kapasitas Kubikasi',
                'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHasilProduksi()
    {
        return $this->hasOne(THasilProduksi::className(), ['hasil_produksi_id' => 'hasil_produksi_id']);
    }
}
