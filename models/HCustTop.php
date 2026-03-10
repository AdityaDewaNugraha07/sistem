<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "h_cust_top".
 *
 * @property integer $hcusttop_id
 * @property integer $cust_id
 * @property string $custtop_jns
 * @property integer $custtop_top
 * @property string $created_at
 * @property integer $created_by
 *
 * @property MCustTop $custtop
 * @property MCustomer $cust
 * @property int|mixed|string|null $kode_customer
 */
class HCustTop extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_cust_top';
    }
    
    public function behaviors(){
		return [DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'custtop_jns', 'custtop_top', 'created_at', 'created_by'], 'required'],
            [['cust_id', 'custtop_top', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['custtop_jns'], 'string', 'max' => 100],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'hcusttop_id' => 'Hcusttop',
                'cust_id' => 'Cust',
                'custtop_jns' => 'Custtop Jns',
                'custtop_top' => 'Custtop Top',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'cust_id']);
    }

    public function scopes()
    {
        return [
            'by_custtop_jns' => ['order' => 'custtop_jns']
        ];
    }
}
