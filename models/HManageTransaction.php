<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_manage_transaction".
 *
 * @property integer $manage_transaction_id
 * @property string $type
 * @property string $table_name
 * @property string $contents_old
 * @property string $contents_new
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $create_identity
 */
class HManageTransaction extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_manage_transaction';
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
            [['type', 'table_name', 'contents_old', 'created_at', 'created_by', 'updated_at', 'updated_by', 'create_identity'], 'required'],
            [['contents_old', 'contents_new', 'create_identity'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['type', 'table_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'manage_transaction_id' => Yii::t('app', 'Manage Transaction'),
                'type' => Yii::t('app', 'Type'),
                'table_name' => Yii::t('app', 'Table Name'),
                'contents_old' => Yii::t('app', 'Contents Old'),
                'contents_new' => Yii::t('app', 'Contents New'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'create_identity' => Yii::t('app', 'Create'),
        ];
    }
}
