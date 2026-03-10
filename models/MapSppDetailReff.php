<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "map_spp_detail_reff".
 *
 * @property integer $sppd_id
 * @property string $reff_no
 * @property integer $reff_detail_id
 * @property integer $terima_bhpd_id
 *
 * @property TSppDetail $sppd
 * @property TTerimaBhpDetail $terimaBhpd
 */
class MapSppDetailReff extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map_spp_detail_reff';
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
            [['sppd_id', 'reff_no', 'reff_detail_id'], 'required'],
            [['sppd_id', 'reff_detail_id', 'terima_bhpd_id'], 'integer'],
            [['reff_no'], 'string', 'max' => 50],
            [['sppd_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSppDetail::className(), 'targetAttribute' => ['sppd_id' => 'sppd_id']],
            [['terima_bhpd_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaBhpDetail::className(), 'targetAttribute' => ['terima_bhpd_id' => 'terima_bhpd_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'sppd_id' => Yii::t('app', 'Sppd'),
                'reff_no' => Yii::t('app', 'Reff No'),
                'reff_detail_id' => Yii::t('app', 'Reff Detail'),
                'terima_bhpd_id' => Yii::t('app', 'Terima Bhpd'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSppd()
    {
        return $this->hasOne(TSppDetail::className(), ['sppd_id' => 'sppd_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerimaBhpd()
    {
        return $this->hasOne(TTerimaBhpDetail::className(), ['terima_bhpd_id' => 'terima_bhpd_id']);
    }
	
	public static function simpanMapping($params){
		$modMap = new \app\models\MapSppDetailReff();
		$modMap->sppd_id = $params['sppd_id'];
		$modMap->reff_no = $params['reff_no'];
		$modMap->reff_detail_id = $params['reff_detail_id'];
		if($modMap->validate()){
			$return = $modMap->save();
		}else{
			$return = false;
		}
		return $return;
	}
}
