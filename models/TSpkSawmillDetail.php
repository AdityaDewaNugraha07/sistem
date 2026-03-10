<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spk_sawmill_detail".
 *
 * @property integer $spk_sawmill_detail_id
 * @property integer $spk_sawmill_id
 * @property integer $kayu_id
 * @property string $produk_sawmill
 * @property double $produk_t
 * @property double $produk_l
 * @property double $produk_p
 * @property string $kategori_ukuran
 *
 * @property MKayu $kayu
 */
class TSpkSawmillDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $size, $panjang;
    public static function tableName()
    {
        return 't_spk_sawmill_detail';
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
            [['spk_sawmill_id', 'kayu_id', 'produk_sawmill', 'produk_t', 'produk_l', 'produk_p', 'kategori_ukuran'], 'required'],
            [['spk_sawmill_id', 'kayu_id'], 'integer'],
            [['produk_t', 'produk_l', 'produk_p'], 'number'],
            [['produk_sawmill'], 'string', 'max' => 25],
            [['kategori_ukuran'], 'string', 'max' => 20],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spk_sawmill_detail_id' => 'Spk Sawmill Detail',
                'spk_sawmill_id' => 'Spk Sawmill',
                'kayu_id' => 'Kayu',
                'produk_sawmill' => 'Produk Sawmill',
                'produk_t' => 'Produk T',
                'produk_l' => 'Produk L',
                'produk_p' => 'Produk P',
                'kategori_ukuran' => 'Kategori Ukuran',
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