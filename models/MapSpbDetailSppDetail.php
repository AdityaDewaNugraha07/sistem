<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "map_spb_detail_spp_detail".
 *
 * @property integer $spbd_id
 * @property integer $sppd_id
 *
 * @property TSpbDetail $spbd
 * @property TSppDetail $sppd
 */
class MapSpbDetailSppDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map_spb_detail_spp_detail';
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
            [['spbd_id', 'sppd_id'], 'required'],
            [['spbd_id', 'sppd_id'], 'integer'],
            [['spbd_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpbDetail::className(), 'targetAttribute' => ['spbd_id' => 'spbd_id']],
            [['sppd_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSppDetail::className(), 'targetAttribute' => ['sppd_id' => 'sppd_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spbd_id' => Yii::t('app', 'Spbd'),
                'sppd_id' => Yii::t('app', 'Sppd'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpbd()
    {
        return $this->hasOne(TSpbDetail::className(), ['spbd_id' => 'spbd_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSppd()
    {
        return $this->hasOne(TSppDetail::className(), ['sppd_id' => 'sppd_id']);
    }
}
