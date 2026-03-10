<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_stock_produk".
 *
 * @property integer $produk_id
 */
class ViewStockProduk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_stock_produk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['produk_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'produk_id' => 'Produk ID',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
    */
}
