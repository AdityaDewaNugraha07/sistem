<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_afkir_sengon_detail".
 *
 * @property integer $afkir_sengon_detail_id
 * @property integer $afkir_sengon_id
 * @property integer $terima_sengon_id
 * @property double $diameter
 * @property double $panjang
 * @property double $qty_pcs
 * @property double $qty_m3
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property TAfkirSengon $afkirSengon
 * @property TTerimaSengon $terimaSengon
 */
class TAfkirSengonDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_afkir_sengon_detail';
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
            [['afkir_sengon_id', 'terima_sengon_id', 'diameter', 'panjang', 'qty_pcs', 'qty_m3', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['afkir_sengon_id', 'terima_sengon_id', 'created_by', 'updated_by'], 'integer'],
            [['diameter', 'panjang', 'qty_pcs', 'qty_m3'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['afkir_sengon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TAfkirSengon::className(), 'targetAttribute' => ['afkir_sengon_id' => 'afkir_sengon_id']],
            [['terima_sengon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaSengon::className(), 'targetAttribute' => ['terima_sengon_id' => 'terima_sengon_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'afkir_sengon_detail_id' => 'Afkir Sengon Detail',
                'afkir_sengon_id' => 'Afkir Sengon',
                'terima_sengon_id' => 'Terima Sengon',
                'diameter' => 'Diameter',
                'panjang' => 'Panjang',
                'qty_pcs' => 'Qty Pcs',
                'qty_m3' => 'Qty M3',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAfkirSengon()
    {
        return $this->hasOne(TAfkirSengon::className(), ['afkir_sengon_id' => 'afkir_sengon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerimaSengon()
    {
        return $this->hasOne(TTerimaSengon::className(), ['terima_sengon_id' => 'terima_sengon_id']);
    }
}
