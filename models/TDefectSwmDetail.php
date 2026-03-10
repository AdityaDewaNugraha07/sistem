<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_defect_swm_detail".
 *
 * @property integer $defect_swm_detail_id
 * @property integer $defect_swm_id
 * @property double $produk_t
 * @property double $produk_l
 * @property double $produk_p
 * @property string $kategori_defect
 * @property integer $qty
 * @property string $keterangan
 */
class TDefectSwmDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_defect_swm_detail';
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
            [['defect_swm_id', 'produk_t', 'produk_l', 'produk_p', 'kategori_defect', 'qty'], 'required'],
            [['defect_swm_id', 'qty'], 'integer'],
            [['produk_t', 'produk_l', 'produk_p'], 'number'],
            [['keterangan'], 'string'],
            [['kategori_defect'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'defect_swm_detail_id' => 'Defect Swm Detail',
                'defect_swm_id' => 'Defect Swm',
                'produk_t' => 'Produk T',
                'produk_l' => 'Produk L',
                'produk_p' => 'Produk P',
                'kategori_defect' => 'Kategori Defect',
                'qty' => 'Qty',
                'keterangan' => 'Keterangan',
        ];
    }
} 