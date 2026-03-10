<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "map_spp_detail_spo_detail".
 *
 * @property integer $sppd_id
 * @property integer $spod_id
 *
 * @property TSpoDetail $spod
 * @property TSppDetail $sppd
 */
class MapSppDetailSpoDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map_spp_detail_spo_detail';
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
            [['sppd_id', 'spod_id'], 'required'],
            [['sppd_id', 'spod_id'], 'integer'],
            [['spod_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpoDetail::className(), 'targetAttribute' => ['spod_id' => 'spod_id']],
            [['sppd_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSppDetail::className(), 'targetAttribute' => ['sppd_id' => 'sppd_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'sppd_id' => Yii::t('app', 'Sppd'),
                'spod_id' => Yii::t('app', 'Spod'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpod()
    {
        return $this->hasOne(TSpoDetail::className(), ['spod_id' => 'spod_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSppd()
    {
        return $this->hasOne(TSppDetail::className(), ['sppd_id' => 'sppd_id']);
    }
}
