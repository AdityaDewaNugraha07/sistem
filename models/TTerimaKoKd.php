<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_ko_kd".
 *
 * @property integer $tbko_kd_id
 * @property integer $tbko_id
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
 * @property TTerimaKo $tbko
 */
class TTerimaKoKd extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $kapasitas_kubikasi_display;
    public static function tableName()
    {
        return 't_terima_ko_kd';
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
            [['tbko_id', 'p_satuan', 'l_satuan', 't_satuan'], 'required'],
            [['tbko_id'], 'integer'],
            [['qty', 'p', 'l', 't', 'kapasitas_kubikasi'], 'number'],
            [['keterangan'], 'string'],
            [['qty_satuan', 'p_satuan', 'l_satuan', 't_satuan'], 'string', 'max' => 50],
            [['tbko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaKo::className(), 'targetAttribute' => ['tbko_id' => 'tbko_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'tbko_kd_id' => Yii::t('app', 'Tbko Kd'),
                'tbko_id' => Yii::t('app', 'Tbko'),
                'qty' => Yii::t('app', 'Qty'),
                'qty_satuan' => Yii::t('app', 'Qty Satuan'),
                'p' => Yii::t('app', 'P'),
                'p_satuan' => Yii::t('app', 'P Satuan'),
                'l' => Yii::t('app', 'L'),
                'l_satuan' => Yii::t('app', 'L Satuan'),
                't' => Yii::t('app', 'T'),
                't_satuan' => Yii::t('app', 'T Satuan'),
                'kapasitas_kubikasi' => Yii::t('app', 'Kapasitas Kubikasi'),
                'keterangan' => Yii::t('app', 'Keterangan'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTbko()
    {
        return $this->hasOne(TTerimaKo::className(), ['tbko_id' => 'tbko_id']);
    }
}
