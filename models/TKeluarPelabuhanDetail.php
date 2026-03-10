<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_keluar_pelabuhan_detail".
 *
 * @property integer $keluar_pelabuhan_detail_id
 * @property integer $keluar_pelabuhan_id
 * @property string $no_grade
 * @property string $no_barcode
 * @property string $no_btg
 * @property string $no_lap
 * @property integer $kayu_id
 * @property double $panjang
 * @property double $diameter
 * @property double $volume
 * @property string $kondisi
 * @property string $asal_kayu
 * @property string $keterangan
 *
 * @property MKayu $kayu
 */
class TKeluarPelabuhanDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_keluar_pelabuhan_detail';
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
            [['keluar_pelabuhan_id', 'no_grade', 'no_barcode', 'no_btg', 'kayu_id'], 'required'],
            [['keluar_pelabuhan_id', 'kayu_id'], 'integer'],
            [['panjang', 'diameter', 'volume'], 'number'],
            [['keterangan'], 'string'],
            [['no_grade', 'no_barcode', 'no_btg', 'no_lap', 'kondisi', 'asal_kayu'], 'string', 'max' => 50],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'keluar_pelabuhan_detail_id' => 'Keluar Pelabuhan Detail',
                'keluar_pelabuhan_id' => 'Keluar Pelabuhan',
                'no_grade' => 'No Grade',
                'no_barcode' => 'No Barcode',
                'no_btg' => 'No Btg',
                'no_lap' => 'No Cardpad',
                'kayu_id' => 'Kayu',
                'panjang' => 'Panjang',
                'diameter' => 'Diameter',
                'volume' => 'Volume',
                'kondisi' => 'Kondisi',
                'asal_kayu' => 'Asal Kayu',
                'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKayu()
    {
        return $this->hasOne(MKayu::className(), ['kayu_id' => 'kayu_id']);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeluarPelabuhan()
    {
        return $this->hasOne(MKayu::className(), ['keluar_pelabuhan_id' => 'keluar_pelabuhan_id']);
    }
}
