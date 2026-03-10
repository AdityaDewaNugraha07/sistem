<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_asuransi_detail".
 *
 * @property integer $asuransi_detail_id
 * @property integer $asuransi_id
 * @property string $jenis
 * @property string $tipe
 * @property double $harga
 * @property double $kubikasi
 * @property double $total
 */
class TAsuransiDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_asuransi_detail';
    }
    
    public $jenisX;

    public function behaviors(){
		return [\app\components\DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asuransi_detail_id', 'asuransi_id'], 'integer'],
            [['jenis', 'tipe'], 'string'],
            [['harga', 'kubikasi', 'total'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'asuransi_detail_id' => 'Asuransi Detail',
                'asuransi_id' => 'Asuransi',
                'jenis' => 'Jenis',
                'tipe' => 'Tipe',
                'harga' => 'Harga',
                'kubikasi' => 'Kubikasi',
        ];
    }
}
