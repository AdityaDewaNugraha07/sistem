<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "map_penawaran_bhp".
 *
 * @property integer $map_penawaran_bhp_id
 * @property integer $penawaran_bhp_id
 * @property integer $sppd_id
 * @property integer $spod_id
 * @property integer $spld_id
 * @property double $qty
 * @property double $harga
 *
 * @property TPenawaranBhp $penawaranBhp
 * @property TSplDetail $spld
 * @property TSpoDetail $spod
 * @property TSppDetail $sppd
 */
class MapPenawaranBhp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map_penawaran_bhp';
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
            [['penawaran_bhp_id'], 'required'],
            [['penawaran_bhp_id', 'sppd_id', 'spod_id', 'spld_id'], 'integer'],
            [['qty', 'harga'], 'number'],
            [['penawaran_bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPenawaranBhp::className(), 'targetAttribute' => ['penawaran_bhp_id' => 'penawaran_bhp_id']],
            [['spld_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSplDetail::className(), 'targetAttribute' => ['spld_id' => 'spld_id']],
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
                'map_penawaran_bhp_id' => 'Map Penawaran Bhp',
                'penawaran_bhp_id' => 'Penawaran Bhp',
                'sppd_id' => 'Sppd',
                'spod_id' => 'Spod',
                'spld_id' => 'Spld',
                'qty' => 'Qty',
                'harga' => 'Harga',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenawaranBhp()
    {
        return $this->hasOne(TPenawaranBhp::className(), ['penawaran_bhp_id' => 'penawaran_bhp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpld()
    {
        return $this->hasOne(TSplDetail::className(), ['spld_id' => 'spld_id']);
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
