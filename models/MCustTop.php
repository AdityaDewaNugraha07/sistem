<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_cust_top".
 *
 * @property integer $custtop_id
 * @property integer $cust_id
 * @property string $custtop_jns
 * @property integer $custtop_top
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MCustTop extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_cust_top';
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
            [['cust_id', 'custtop_jns', 'custtop_top', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['cust_id', 'custtop_top', 'created_by', 'updated_by'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['custtop_jns'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'custtop_id' => Yii::t('app', 'Custtop'),
            'cust_id' => Yii::t('app', 'Cust'),
            'custtop_jns' => Yii::t('app', 'Custtop Jns'),
            'custtop_top' => Yii::t('app', 'Custtop Top'),
            'active' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }
}
