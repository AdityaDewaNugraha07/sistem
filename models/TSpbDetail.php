<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spb_detail".
 *
 * @property integer $spbd_id
 * @property integer $spb_id
 * @property integer $bhp_id
 * @property integer $spbd_jml
 * @property string $spbd_tgl_dipakai
 * @property string $spbd_ket
 * @property integer $spbd_jml_terpenuhi
 * @property string $spbd_satuan
 *
 * @property MapSpbDetailSppDetail[] $mapSpbDetailSppDetails
 * @property MBrgBhp $bhp
 * @property TSpb $spb
 */ 
class TSpbDetail extends \app\models\DeltaBaseActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public $bhp_nama,$spbd_qty;
    public static function tableName()
    {
        return 't_spb_detail';
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
            [['spb_id', 'bhp_id', 'spbd_jml'], 'required'],
            [['spb_id', 'bhp_id'], 'integer'],
            [['spbd_tgl_dipakai', 'spbd_jml_terpenuhi'], 'safe'],
            [['spbd_ket'], 'string'],
            [['spbd_satuan'], 'string', 'max' => 20],
            [['bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgBhp::className(), 'targetAttribute' => ['bhp_id' => 'bhp_id']],
            [['spb_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpb::className(), 'targetAttribute' => ['spb_id' => 'spb_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spbd_id' => Yii::t('app', 'Spbd'),
                'spb_id' => Yii::t('app', 'Spb'),
                'bhp_id' => Yii::t('app', 'Bhp'),
                'spbd_jml' => Yii::t('app', 'Spbd Jml'),
                'spbd_tgl_dipakai' => Yii::t('app', 'Spbd Tgl Dipakai'),
                'spbd_ket' => Yii::t('app', 'Spbd Ket'),
				'spbd_jml_terpenuhi' => Yii::t('app', 'Spbd Jml Terpenuhi'),
                'spbd_satuan' => Yii::t('app', 'Spbd Satuan'),
        ];
    }
    
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getMapSpbDetailSppDetails()
    {
        return $this->hasMany(MapSpbDetailSppDetail::className(), ['spbd_id' => 'spbd_id']);
    } 
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBhp()
    {
        return $this->hasOne(MBrgBhp::className(), ['bhp_id' => 'bhp_id']);
    } 
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getSpb()
    {
        return $this->hasOne(TSpb::className(), ['spb_id' => 'spb_id']);
    } 
	
	
	public static function getDetailItemSpb($spb_id,$bhp_id){
		if( (!empty($spb_id)) && (!empty($bhp_id))){
			$modDetailItemSpb = TSpbDetail::find()->where(['spb_id'=>$spb_id,'bhp_id'=>$bhp_id])->one();
		}
		if(count($modDetailItemSpb)>0){
			return $modDetailItemSpb;
		}
		return false;
	}
	
	public static function getOptionListSatuan()
    {
        $res = MBrgBhp::find()->select('bhp_satuan')->groupBy('bhp_satuan')->where("active IS TRUE AND bhp_satuan != ''")->all();
        return \yii\helpers\ArrayHelper::map($res, 'bhp_satuan', 'bhp_satuan');
    }
}
