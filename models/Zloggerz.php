<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zloggerz".
 *
 * @property integer $id
 * @property integer $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 */
class Zloggerz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zloggerz';
    }

    public static function primaryKey()
    {
        return ["id"];
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
            [['log_time'], 'safe'],
            [['id', 'level'], 'integer'],
            [['category', 'prefix', 'message'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'id' => 'ID',
                'level' => 'Level',
                'category' => 'Category',
                'log_time' => 'Log Time',
                'prefix' => 'Prefix',
                'message' => 'Message',
        ];
    }
}
