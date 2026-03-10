<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_alias".
 *
 * @property integer $alias_id
 * @property string $reff_no
 * @property integer $reff_detail_id
 * @property string $alias_name
 * @property string $value_original
 * @property string $value_alias
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property TCancelTransaksi $cancelTransaksi
 */
class TAlias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_alias';
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
            [['reff_no', 'alias_name', 'value_original', 'value_alias', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['reff_detail_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['value_original', 'value_alias'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['reff_no', 'alias_name'], 'string', 'max' => 50],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'alias_id' => 'Alias',
                'reff_no' => 'Reff No',
                'reff_detail_id' => 'Reff Detail',
                'alias_name' => 'Alias Name',
                'value_original' => 'Value Original',
                'value_alias' => 'Value Alias',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }
} 