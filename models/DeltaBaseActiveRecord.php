<?php

namespace app\models;

use Yii;

/**
 * Author Arie Satriananta <ariesatriananta@yahoo.com>
 */
class DeltaBaseActiveRecord extends \yii\db\ActiveRecord
{
	public function behaviors(){
		$enable_items = null;
		$return = [];
		
		if($enable_items != null){
			foreach($enable_items as $item){
				if($item === 'created_at'){
					$return[$item] = [	'class' => \yii\behaviors\TimestampBehavior::className(),
										'attributes' => [
											\yii\db\BaseActiveRecord::EVENT_BEFORE_VALIDATE => ['created_at']
										],
										'value' => \app\components\DeltaFormatter::formatDateTimeForDb(date('Y-m-d H:i:s')),
									 ];
				}
				if($item === 'created_updated_at'){
					$return[$item] = [	'class' => \yii\behaviors\TimestampBehavior::className(),
										'attributes' => [
											\yii\db\BaseActiveRecord::EVENT_BEFORE_VALIDATE => ['created_at', 'updated_at'],
											\yii\db\BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
										],
										'value' => \app\components\DeltaFormatter::formatDateTimeForDb(date('Y-m-d H:i:s')),
									 ];
				}
				if($item === 'created_by'){
					$return[$item] = [	'class' => \yii\behaviors\BlameableBehavior::className(),
										'attributes' => [
											\yii\db\BaseActiveRecord::EVENT_BEFORE_VALIDATE => ['created_by']
										],   
									 ];
				}
				if($item === 'created_updated_by'){
					$return[$item] = [	'class' => \yii\behaviors\BlameableBehavior::className(),
										'attributes' => [
											\yii\db\BaseActiveRecord::EVENT_BEFORE_VALIDATE => ['created_by', 'updated_by'],
											\yii\db\BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by'
										],   
									 ];
				}
			}
		}
		
		return $return;
	}
}
