<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_target_penjualan_sales".
 *
 * @property integer $target_penjualan_sales_id
 * @property integer $target_penjualan_id
 * @property string $type_penjualan
 * @property string $target_jenis_produk
 * @property double $target_jml
 * @property string $target_jml_satuan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $target_seq
 * @property string $target_periode
 * @property double $target_range
 * @property double $target_range_prosentase
 * @property integer $sales_id
 *
 * @property MSales $sales
 * @property TTargetPenjualan $targetPenjualan
 */
class TTargetPenjualanSales extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_target_penjualan_sales';
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
            [['target_penjualan_id', 'created_by', 'updated_by', 'target_seq', 'sales_id'], 'integer'],
            [['type_penjualan', 'target_jenis_produk', 'target_jml_satuan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['target_jml', 'target_range', 'target_range_prosentase'], 'number'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['type_penjualan'], 'string', 'max' => 10],
            [['target_jenis_produk'], 'string', 'max' => 50],
            [['target_jml_satuan'], 'string', 'max' => 20],
            [['target_periode'], 'string', 'max' => 8],
            [['sales_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSales::className(), 'targetAttribute' => ['sales_id' => 'sales_id']],
            [['target_penjualan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTargetPenjualan::className(), 'targetAttribute' => ['target_penjualan_id' => 'target_penjualan_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'target_penjualan_sales_id' => 'Target Penjualan Sales',
                'target_penjualan_id' => 'Target Penjualan',
                'type_penjualan' => 'Type Penjualan',
                'target_jenis_produk' => 'Target Jenis Produk',
                'target_jml' => 'Target Jml',
                'target_jml_satuan' => 'Target Jml Satuan',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'target_seq' => 'Target Seq',
                'target_periode' => 'Target Periode',
                'target_range' => 'Target Range',
                'target_range_prosentase' => 'Target Range Prosentase',
                'sales_id' => 'Sales',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasOne(MSales::className(), ['sales_id' => 'sales_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTargetPenjualan()
    {
        return $this->hasOne(TTargetPenjualan::className(), ['target_penjualan_id' => 'target_penjualan_id']);
    }
}
