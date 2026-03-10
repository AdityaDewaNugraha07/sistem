<?php
namespace app\components;

use yii\db\ActiveRecord;
use yii\base\Behavior;

/**
* Behavior Model
* @author Arie Satriananta <ariesatriananta@yahoo.com>
*/
class DeltaGeneralBehavior extends Behavior
{
    public $createdAtAttribute = 'created_at';
	public $updatedAtAttribute = 'updated_at';
	public $value;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function beforeValidate($event)
    {
		// Nilai sementara. Agar berhasil di validate terlebih dahulu
		($this->owner->hasAttribute('created_at') ? $this->owner->created_at = $this->owner->created_at = '0000-00-00 00:00:00':'');
		($this->owner->hasAttribute('created_by') ? $this->owner->created_by = $this->owner->created_by = 0:'');
		($this->owner->hasAttribute('updated_at') ? $this->owner->updated_at = $this->owner->updated_at = '0000-00-00 00:00:00':'');
		($this->owner->hasAttribute('updated_by') ? $this->owner->updated_by = $this->owner->updated_by = 0:'');
    }
	
	public function beforeInsert($event)
    {
		($this->owner->hasAttribute('created_at') ? $this->owner->created_at = DeltaFormatter::formatDateTimeForDb(date('Y-m-d H:i:s')):'');
		($this->owner->hasAttribute('created_by') ? $this->owner->created_by = \Yii::$app->user->id:'');
		($this->owner->hasAttribute('updated_at') ? $this->owner->updated_at = DeltaFormatter::formatDateTimeForDb(date('Y-m-d H:i:s')):'');
		($this->owner->hasAttribute('updated_by') ? $this->owner->updated_by = \Yii::$app->user->id:'');
		($this->owner->hasAttribute('create_identity') ? $this->owner->create_identity = $this->clientIdentity():'');
    }
	
	public function beforeUpdate($event)
    {
		($this->owner->hasAttribute('created_at') ? $this->owner->created_at = $this->owner->oldAttributes['created_at']:'');
		($this->owner->hasAttribute('created_by') ? $this->owner->created_by = $this->owner->oldAttributes['created_by']:'');
		($this->owner->hasAttribute('updated_at') ? $this->owner->updated_at = DeltaFormatter::formatDateTimeForDb(date('Y-m-d H:i:s')):'');
		($this->owner->hasAttribute('updated_by') ? $this->owner->updated_by = \Yii::$app->user->id:'');
	}
	
	public function afterDelete($event)
    {
		$model = new \app\models\HManageTransaction();
		$model->type = "DELETE";
		$model->table_name = $this->owner->tableName();
		$model->contents_old = \yii\helpers\Json::encode($this->owner->attributes);
		$model->create_identity = $this->clientIdentity();
		if($model->validate()){
			if($model->save()){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public static function clientIdentity(){
		$client_array = ['server'=>\Yii::$app->request->serverName."::".\Yii::$app->request->serverPort,'ip'=>\Yii::$app->request->userIP,'agent'=>\Yii::$app->request->userAgent];
		return \yii\helpers\Json::encode($client_array);
	}
}

?>