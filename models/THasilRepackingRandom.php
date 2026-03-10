<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_hasil_repacking_random".
 *
 * @property integer $hasil_repacking_random_id
 * @property integer $hasil_repacking_id
 * @property double $qty
 * @property string $qty_satuan
 * @property double $p
 * @property string $p_satuan
 * @property double $l
 * @property string $l_satuan
 * @property double $t
 * @property string $t_satuan
 * @property double $kapasitas_kubikasi
 * @property string $keterangan
 *
 * @property THasilRepacking $hasilRepacking
 */
class THasilRepackingRandom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kapasitas_kubikasi_display;
    public static function tableName()
    {
        return 't_hasil_repacking_random';
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
            [['hasil_repacking_id', 'p_satuan', 'l_satuan', 't_satuan'], 'required'],
            [['hasil_repacking_id'], 'integer'],
            [['qty', 'p', 'l', 't', 'kapasitas_kubikasi'], 'number'],
            [['keterangan'], 'string'],
            [['qty_satuan', 'p_satuan', 'l_satuan', 't_satuan'], 'string', 'max' => 50],
            [['hasil_repacking_id'], 'exist', 'skipOnError' => true, 'targetClass' => THasilRepacking::className(), 'targetAttribute' => ['hasil_repacking_id' => 'hasil_repacking_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'hasil_repacking_random_id' => 'Hasil Repacking Random',
                'hasil_repacking_id' => 'Hasil Repacking',
                'qty' => 'Qty',
                'qty_satuan' => 'Qty Satuan',
                'p' => 'P',
                'p_satuan' => 'P Satuan',
                'l' => 'L',
                'l_satuan' => 'L Satuan',
                't' => 'T',
                't_satuan' => 'T Satuan',
                'kapasitas_kubikasi' => 'Kapasitas Kubikasi',
                'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHasilRepacking()
    {
        return $this->hasOne(THasilRepacking::className(), ['hasil_repacking_id' => 'hasil_repacking_id']);
    }
}
