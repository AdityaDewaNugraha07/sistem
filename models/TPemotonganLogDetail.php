<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pemotongan_log_detail".
 *
 * @property integer $pemotongan_log_detail_id
 * @property integer $pemotongan_log_id
 * @property integer $kayu_id
 * @property string $no_barcode
 * @property double $panjang
 * @property double $diameter
 * @property string $reduksi
 * @property double $volume
 * @property integer $jumlah_potong
 * @property boolean $potong
 *
 * @property MKayu $kayu
 */
class TPemotonganLogDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $no_barcode_lap;
    public static function tableName()
    {
        return 't_pemotongan_log_detail';
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
            [['pemotongan_log_id', 'kayu_id', 'no_barcode', 'reduksi', 'jumlah_potong'], 'required'],
            [['pemotongan_log_id', 'kayu_id', 'jumlah_potong'], 'integer'],
            [['panjang', 'diameter', 'volume'], 'number'],
            [['potong'], 'boolean'],
            [['no_barcode', 'reduksi'], 'string', 'max' => 50],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pemotongan_log_detail_id' => 'Pemotongan Log Detail',
                'pemotongan_log_id' => 'Pemotongan Log',
                'kayu_id' => 'Kayu',
                'no_barcode' => 'No Barcode',
                'panjang' => 'Panjang',
                'diameter' => 'Diameter',
                'reduksi' => 'Reduksi',
                'volume' => 'Volume',
                'jumlah_potong' => 'Jumlah Potong',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKayu()
    {
        return $this->hasOne(MKayu::className(), ['kayu_id' => 'kayu_id']);
    }
} 