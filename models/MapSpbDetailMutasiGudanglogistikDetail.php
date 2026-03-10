<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "map_spb_detail_mutasi_gudanglogistik_detail".
 *
 * @property integer $spbd_id
 * @property double $spbd_qty
 * @property integer $mutasi_gudanglogistikd_id
 * @property double $mutasi_gudanglogistikd_qty
 *
 * @property TMutasiGudanglogistikDetail $mutasiGudanglogistikd
 * @property TSpbDetail $spbd
 */
class MapSpbDetailMutasiGudanglogistikDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map_spb_detail_mutasi_gudanglogistik_detail';
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
            [['spbd_id', 'spbd_qty', 'mutasi_gudanglogistikd_id', 'mutasi_gudanglogistikd_qty'], 'required'],
            [['spbd_id', 'mutasi_gudanglogistikd_id'], 'integer'],
            [['spbd_qty', 'mutasi_gudanglogistikd_qty'], 'number'],
            [['mutasi_gudanglogistikd_id'], 'exist', 'skipOnError' => true, 'targetClass' => TMutasiGudanglogistikDetail::className(), 'targetAttribute' => ['mutasi_gudanglogistikd_id' => 'mutasi_gudanglogistikd_id']],
            [['spbd_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpbDetail::className(), 'targetAttribute' => ['spbd_id' => 'spbd_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spbd_id' => Yii::t('app', 'Spbd'),
                'spbd_qty' => Yii::t('app', 'Spbd Qty'),
                'mutasi_gudanglogistikd_id' => Yii::t('app', 'Mutasi Gudanglogistikd'),
                'mutasi_gudanglogistikd_qty' => Yii::t('app', 'Mutasi Gudanglogistikd Qty'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMutasiGudanglogistikd()
    {
        return $this->hasOne(TMutasiGudanglogistikDetail::className(), ['mutasi_gudanglogistikd_id' => 'mutasi_gudanglogistikd_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpbd()
    {
        return $this->hasOne(TSpbDetail::className(), ['spbd_id' => 'spbd_id']);
    }
}
