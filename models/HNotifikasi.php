<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_notifikasi".
 *
 * @property integer $notifikasi_id
 * @property integer $c_notifikasi_id
 * @property string $title
 * @property string $description
 * @property boolean $is_read
 * @property string $status
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property CNotifikasi $cNotifikasi
 */
class HNotifikasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_notifikasi';
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
            [['c_notifikasi_id', 'created_by', 'updated_by'], 'integer'],
            [['title', 'description', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['description'], 'string'],
            [['is_read', 'active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 50],
            [['c_notifikasi_id'], 'exist', 'skipOnError' => true, 'targetClass' => CNotifikasi::className(), 'targetAttribute' => ['c_notifikasi_id' => 'notifikasi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'notifikasi_id' => 'Notifikasi',
                'c_notifikasi_id' => 'C Notifikasi',
                'title' => 'Title',
                'description' => 'Description',
                'is_read' => 'Is Read',
                'status' => 'Status',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCNotifikasi()
    {
        return $this->hasOne(CNotifikasi::className(), ['notifikasi_id' => 'c_notifikasi_id']);
    }
    
    public static function createNotifikasi($par){
        $model = new HNotifikasi();
        $model->attributes = $par;
		if($model->validate()){
			if($model->save()){
				return true;
			} else {
				return false;
			}
		}else{
			return false;
		}
    }
}
