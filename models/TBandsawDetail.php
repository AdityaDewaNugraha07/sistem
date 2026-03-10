<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_bandsaw_detail".
 *
 * @property integer $bandsaw_detail_id
 * @property integer $bandsaw_id
 * @property integer $kayu_id
 * @property string $nomor_bandsaw
 * @property double $produk_t
 * @property double $produk_l
 * @property double $produk_p
 * @property integer $qty
 *
 * @property MKayu $kayu
 */
class TBandsawDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $size, $panjang, $jml, $qty2;
    public static function tableName()
    {
        return 't_bandsaw_detail';
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
            [['bandsaw_id', 'kayu_id', 'nomor_bandsaw', 'produk_t', 'produk_l', 'produk_p',  'qty'], 'required'],
            [['bandsaw_id', 'kayu_id', 'qty'], 'integer'],
            [['produk_t', 'produk_l', 'produk_p'], 'number'],
            [['nomor_bandsaw'], 'string', 'max' => 2],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'bandsaw_detail_id' => 'Bandsaw Detail',
                'bandsaw_id' => 'Bandsaw',
                'kayu_id' => 'Kayu',
                'nomor_bandsaw' => 'Nomor Bandsaw',
                'produk_t' => 'Produk T',
                'produk_l' => 'Produk L',
                'produk_p' => 'Produk P',
                'qty' => 'Qty',
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