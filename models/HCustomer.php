<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "h_customer".
 *
 * @property integer $hcust_id
 * @property integer $cust_id
 * @property string $kode_customer
 * @property double $cust_max_plafond_lama
 * @property double $cust_max_plafond
 * @property integer $by_kadiv
 * @property integer $by_dirut
 * @property string $approve_reason
 * @property string $reject_reason
 * @property string $status_approval
 * @property string $created_at
 * @property integer $created_by
 *
 * @property MCustomer $cust
 * @property mixed|string|null $cust_alamat
 */
class HCustomer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_customer';
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
            [['cust_id', 'kode_customer', 'created_at', 'created_by'], 'required'],
            [['cust_id', 'by_kadiv', 'by_dirut', 'created_by'], 'integer'],
            [['cust_max_plafond_lama', 'cust_max_plafond'], 'number'],
            [['approve_reason', 'reject_reason'], 'string'],
            [['created_at'], 'safe'],
            [['kode_customer'], 'string', 'max' => 30],
            [['status_approval'], 'string', 'max' => 20],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'hcust_id' => 'Hcust',
                'cust_id' => 'Cust',
                'kode_customer' => 'Kode Customer',
                'cust_max_plafond_lama' => 'Cust Max Plafond Lama',
                'cust_max_plafond' => 'Cust Max Plafond',                
                'by_kadiv' => 'By Kadiv',
                'by_dirut' => 'By Dirut',
                'approve_reason' => 'Approve Reason',
                'reject_reason' => 'Reject Reason',
                'status_approval' => 'Status Approval',
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
}
