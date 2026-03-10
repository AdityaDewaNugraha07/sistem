<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_po_ko_detail".
 *
 * @property integer $po_ko_detail_id
 * @property integer $po_ko_id
 * @property string $produk_alias
 * @property double $qty_besar
 * @property string $satuan_besar
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 * @property double $harga
 * @property string $keterangan
 * @property integer $produk_id
 * @property double $komposisi
 * @property string $diameter_alias
 * @property string $produk_id_alias
 * @property boolean $alias
 * @property string $range_diameter
 * @property boolean $fsc
 *
 * @property TPoKo $poKo
 */
class TPoKoDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $subtotal;
    public static function tableName()
    {
        return 't_po_ko_detail';
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
            [['po_ko_id', 'produk_alias', 'diameter_alias', 'kubikasi', 'komposisi', 'harga'], 'required'],
            [['po_ko_id', 'produk_id'], 'integer'],
            [['qty_besar', 'qty_kecil', 'kubikasi', 'harga', 'komposisi'], 'number'],
            [['keterangan'], 'string'],
            [['alias', 'fsc'], 'boolean'],
            [['produk_alias'], 'string', 'max' => 150],
            [['satuan_besar', 'satuan_kecil', 'diameter_alias'], 'string', 'max' => 50],
            [['produk_id_alias'], 'string', 'max' => 100],
            [['range_diameter'], 'string', 'max' => 10],
            [['po_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPoKo::className(), 'targetAttribute' => ['po_ko_id' => 'po_ko_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'po_ko_detail_id' => 'Po Ko Detail',
                'po_ko_id' => 'Po Ko',
                'produk_alias' => 'Produk Alias',
                'qty_besar' => 'Qty Besar',
                'satuan_besar' => 'Satuan Besar',
                'qty_kecil' => 'Qty Kecil',
                'satuan_kecil' => 'Satuan Kecil',
                'kubikasi' => 'Kubikasi',
                'harga' => 'Harga',
                'keterangan' => 'Keterangan',
                'produk_id' => 'Produk',
                'komposisi' => 'Komposisi',
                'diameter_alias' => 'Diameter Alias',
                'produk_id_alias' => 'Produk',
                'alias' => 'Alias',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoKo()
    {
        return $this->hasOne(TPoKo::className(), ['po_ko_id' => 'po_ko_id']);
    }

    public function getLog()
    {
        return $this->hasOne(MBrgLog::className(), ['log_id' => 'produk_id']);
    }
} 