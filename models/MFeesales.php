<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_feesales".
 *
 * @property integer $feesales_id
 * @property string $feesales_level_sales
 * @property string $feesales_jenis_produk
 * @property string $feesales_destinasi_penjualan
 * @property integer $feesales_tempo_pembayaran
 * @property double $feesales_fee
 * @property string $feesales_satuan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MFeesales extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $jenis_log;
    public static function tableName()
    {
        return 'm_feesales';
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
            [['feesales_level_sales', 'feesales_jenis_produk', 'feesales_destinasi_penjualan', 'feesales_tempo_pembayaran', 'feesales_fee', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['feesales_tempo_pembayaran', 'created_by', 'updated_by'], 'integer'],
            [['feesales_fee'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['feesales_level_sales', 'feesales_jenis_produk', 'feesales_destinasi_penjualan', 'feesales_satuan'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'feesales_id' => Yii::t('app', 'Feesales'),
                'feesales_level_sales' => Yii::t('app', 'Level'),
                'feesales_jenis_produk' => Yii::t('app', 'Jenis Produk'),
                'feesales_destinasi_penjualan' => Yii::t('app', 'Destinasi Penjualan'),
                'feesales_tempo_pembayaran' => Yii::t('app', 'Tempo Pembayaran'),
                'feesales_fee' => Yii::t('app', 'Fee (Rp)'),
                'feesales_satuan' => Yii::t('app', 'Satuan Fee'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }
}
