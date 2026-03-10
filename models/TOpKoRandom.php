<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_op_ko_random".
 *
 * @property integer $op_ko_random_id
 * @property integer $op_ko_detail_id
 * @property integer $produk_id
 * @property string $nomor_produksi
 * @property double $p
 * @property string $p_satuan
 * @property double $l
 * @property string $l_satuan
 * @property double $t
 * @property string $t_satuan
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 *
 * @property MBrgProduk $produk
 * @property TOpKoDetail $opKoDetail
 */
class TOpKoRandom extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $qty_kecil_realisasi,$qty_besar,$qty_besar_realisasi,$kubikasi_realisasi,$satuan_kecil_realisasi,$satuan_besar_realisasi;
    public static function tableName()
    {
        return 't_op_ko_random';
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
            [['op_ko_detail_id', 'produk_id', 'nomor_produksi', 'p_satuan', 'l_satuan', 't_satuan', 'qty_kecil', 'satuan_kecil', 'kubikasi'], 'required'],
            [['op_ko_detail_id', 'produk_id'], 'integer'],
            [['p', 'l', 't', 'qty_kecil', 'kubikasi'], 'number'],
            [['nomor_produksi', 'p_satuan', 'l_satuan', 't_satuan', 'satuan_kecil'], 'string', 'max' => 50],
            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['op_ko_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => TOpKoDetail::className(), 'targetAttribute' => ['op_ko_detail_id' => 'op_ko_detail_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'op_ko_random_id' => Yii::t('app', 'Op Ko Random'),
                'op_ko_detail_id' => Yii::t('app', 'Op Ko Detail'),
                'produk_id' => Yii::t('app', 'Produk'),
                'nomor_produksi' => Yii::t('app', 'Nomor Produksi'),
                'p' => Yii::t('app', 'P'),
                'p_satuan' => Yii::t('app', 'P Satuan'),
                'l' => Yii::t('app', 'L'),
                'l_satuan' => Yii::t('app', 'L Satuan'),
                't' => Yii::t('app', 'T'),
                't_satuan' => Yii::t('app', 'T Satuan'),
                'qty_kecil' => Yii::t('app', 'Qty Kecil'),
                'satuan_kecil' => Yii::t('app', 'Satuan Kecil'),
                'kubikasi' => Yii::t('app', 'Kubikasi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduk()
    {
        return $this->hasOne(MBrgProduk::className(), ['produk_id' => 'produk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpKoDetail()
    {
        return $this->hasOne(TOpKoDetail::className(), ['op_ko_detail_id' => 'op_ko_detail_id']);
    }
}
