<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "map_trackingpembelian_checklist".
 *
 * @property string $reff_no
 * @property integer $bhp_id
 * @property boolean $checked
 */
class MapTrackingpembelianChecklist extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map_trackingpembelian_checklist';
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
            [['bhp_id'], 'integer'],
            [['checked'], 'boolean'],
            [['reff_no'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'reff_no' => Yii::t('app', 'Reff No'),
                'bhp_id' => Yii::t('app', 'Bhp'),
                'checked' => Yii::t('app', 'Checked'),
        ];
    }
	
	public static function checkTrack($reff_no){
		$ret = false;
		$mod = self::find()->where(['reff_no'=>$reff_no])->all();
		if(count($mod)>0){
			foreach($mod as $i => $abc){
				if($abc->checked === TRUE){
					$ret = TRUE;
				}
			}
		}
		return $ret;
	}
}
