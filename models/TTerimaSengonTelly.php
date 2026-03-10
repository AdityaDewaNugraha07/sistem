<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_sengon_telly".
 *
 * @property integer $terima_sengon_telly_id
 * @property integer $terima_sengon_detail_id
 * @property integer $no_urut
 * @property string $kode_truck
 * @property integer $batang_ke
 * @property string $jenis
 * @property integer $diameter
 * @property integer $panjang
 * @property integer $pcs
 * @property double $m3
 *
 * @property TTerimaSengonDetail $terimaSengonDetail
 */
class TTerimaSengonTelly extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_terima_sengon_telly';
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
            [['terima_sengon_detail_id', 'no_urut', 'diameter', 'panjang', 'm3'], 'required'],
            [['terima_sengon_detail_id', 'no_urut', 'batang_ke', 'diameter', 'panjang', 'pcs'], 'integer'],
            [['m3'], 'number'],
            [['kode_truck', 'jenis'], 'string', 'max' => 20],
            [['terima_sengon_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaSengonDetail::className(), 'targetAttribute' => ['terima_sengon_detail_id' => 'terima_sengon_detail_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'terima_sengon_telly_id' => Yii::t('app', 'Terima Sengon Telly'),
                'terima_sengon_detail_id' => Yii::t('app', 'Terima Sengon Detail'),
                'no_urut' => Yii::t('app', 'No Urut'),
                'kode_truck' => Yii::t('app', 'Kode Truck'),
                'batang_ke' => Yii::t('app', 'Batang Ke'),
                'jenis' => Yii::t('app', 'Jenis'),
                'diameter' => Yii::t('app', 'Diameter'),
                'panjang' => Yii::t('app', 'Panjang'),
                'pcs' => Yii::t('app', 'Pcs'),
                'm3' => Yii::t('app', 'M3'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerimaSengonDetail()
    {
        return $this->hasOne(TTerimaSengonDetail::className(), ['terima_sengon_detail_id' => 'terima_sengon_detail_id']);
    }
}
