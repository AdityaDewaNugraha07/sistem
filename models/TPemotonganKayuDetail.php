<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pemotongan_kayu_detail".
 *
 * @property integer $pemotongan_kayu_detail_id
 * @property integer $pemotongan_kayu_id
 * @property integer $kayu_id
 * @property string $no_barcode
 * @property double $panjang
 * @property double $reduksi
 * @property double $volume
 * @property integer $jumlah_potong
 * @property string $hasil_pemotongan
 *
 * @property MKayu $kayu
 */
class TPemotonganKayuDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $no_barcode_baru, $panjang_baru, $volume_baru;
    public static function tableName()
    {
        return 't_pemotongan_kayu_detail';
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
            [['pemotongan_kayu_id', 'kayu_id', 'reduksi', 'no_barcode', 'jumlah_potong', 'hasil_pemotongan'], 'required'],
            [['pemotongan_kayu_id', 'kayu_id', 'jumlah_potong'], 'integer'],
            [['panjang', 'volume'], 'number'],
            [['hasil_pemotongan'], 'string'],
            [['no_barcode'], 'string', 'max' => 50],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pemotongan_kayu_detail_id' => 'Pemotongan Kayu Detail',
                'pemotongan_kayu_id' => 'Pemotongan Kayu',
                'kayu_id' => 'Kayu',
                'no_barcode' => 'No Barcode',
                'panjang' => 'Panjang',
                'reduksi' => 'Reduksi',
                'volume' => 'Volume',
                'jumlah_potong' => 'Jumlah Potong',
                'hasil_pemotongan' => 'Hasil Pemotongan',
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
