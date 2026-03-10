<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_brakedown_detail".
 *
 * @property integer $brakedown_detail_id
 * @property integer $brakedown_id
 * @property string $no_barcode_baru
 * @property string $no_lap_baru
 * @property string $grading_rule
 * @property double $panjang_baru
 * @property double $diameter_ujung1_baru
 * @property double $diameter_ujung2_baru
 * @property double $diameter_pangkal1_baru
 * @property double $diameter_pangkal2_baru
 * @property double $cacat_pjg_baru
 * @property double $cacat_gb_baru
 * @property double $cacat_gr_baru
 * @property double $volume_baru
 */
class TBrakedownDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_brakedown_detail';
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
            [['brakedown_id', 'no_barcode_baru'], 'required'],
            [['brakedown_id'], 'integer'],
            [['panjang_baru', 'diameter_ujung1_baru', 'diameter_ujung2_baru', 'diameter_pangkal1_baru', 'diameter_pangkal2_baru', 'cacat_pjg_baru', 'cacat_gb_baru', 'cacat_gr_baru', 'volume_baru'], 'number'],
            [['no_barcode_baru', 'no_lap_baru'], 'string', 'max' => 50],
            [['grading_rule'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'brakedown_detail_id' => 'Brakedown Detail',
                'brakedown_id' => 'Brakedown',
                'no_barcode_baru' => 'No Barcode Baru',
                'no_lap_baru' => 'No Lap Baru',
                'grading_rule' => 'Grading Rule',
                'panjang_baru' => 'Panjang Baru',
                'diameter_ujung1_baru' => 'Diameter Ujung1 Baru',
                'diameter_ujung2_baru' => 'Diameter Ujung2 Baru',
                'diameter_pangkal1_baru' => 'Diameter Pangkal1 Baru',
                'diameter_pangkal2_baru' => 'Diameter Pangkal2 Baru',
                'cacat_pjg_baru' => 'Cacat Pjg Baru',
                'cacat_gb_baru' => 'Cacat Gb Baru',
                'cacat_gr_baru' => 'Cacat Gr Baru',
                'volume_baru' => 'Volume Baru',
        ];
    }
} 