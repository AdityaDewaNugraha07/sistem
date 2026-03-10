<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_stockopname_hasil_detail".
 *
 * @property integer $stockopname_hasil_detail_id
 * @property integer $stockopname_hasil_id
 * @property string $jenis_produk
 * @property double $total_fisik_palet
 * @property double $total_fisik_m3
 * @property double $total_fisik_rp
 * @property double $total_system_palet
 * @property double $total_system_m3
 * @property double $total_system_rp
 * @property double $total_undefined_palet
 * @property double $total_undefined_m3
 * @property double $total_undefined_rp
 * @property double $fisik_yes_system_yes_palet
 * @property double $fisik_yes_system_yes_m3
 * @property double $fisik_yes_system_yes_rp
 * @property double $fisik_yes_system_no_palet
 * @property double $fisik_yes_system_no_m3
 * @property double $fisik_yes_system_no_rp
 * @property double $fisik_no_system_yes_palet
 * @property double $fisik_no_system_yes_m3
 * @property double $fisik_no_system_yes_rp
 * @property string $keterangan
 *
 * @property TStockopnameHasil $stockopnameHasil
 */
class TStockopnameHasilDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_stockopname_hasil_detail';
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
            [['stockopname_hasil_id', 'jenis_produk'], 'required'],
            [['stockopname_hasil_id'], 'integer'],
            [['total_fisik_palet', 'total_fisik_m3', 'total_fisik_rp', 'total_system_palet', 'total_system_m3', 'total_system_rp', 'total_undefined_palet', 'total_undefined_m3', 'total_undefined_rp', 'fisik_yes_system_yes_palet', 'fisik_yes_system_yes_m3', 'fisik_yes_system_yes_rp', 'fisik_yes_system_no_palet', 'fisik_yes_system_no_m3', 'fisik_yes_system_no_rp', 'fisik_no_system_yes_palet', 'fisik_no_system_yes_m3', 'fisik_no_system_yes_rp'], 'number'],
            [['keterangan'], 'string'],
            [['jenis_produk'], 'string', 'max' => 50],
            [['stockopname_hasil_id'], 'exist', 'skipOnError' => true, 'targetClass' => TStockopnameHasil::className(), 'targetAttribute' => ['stockopname_hasil_id' => 'stockopname_hasil_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'stockopname_hasil_detail_id' => 'Stockopname Hasil Detail',
                'stockopname_hasil_id' => 'Stockopname Hasil',
                'jenis_produk' => 'Jenis Produk',
                'total_fisik_palet' => 'Total Fisik Palet',
                'total_fisik_m3' => 'Total Fisik M3',
                'total_fisik_rp' => 'Total Fisik Rp',
                'total_system_palet' => 'Total System Palet',
                'total_system_m3' => 'Total System M3',
                'total_system_rp' => 'Total System Rp',
                'total_undefined_palet' => 'Total Undefined Palet',
                'total_undefined_m3' => 'Total Undefined M3',
                'total_undefined_rp' => 'Total Undefined Rp',
                'fisik_yes_system_yes_palet' => 'Fisik Yes System Yes Palet',
                'fisik_yes_system_yes_m3' => 'Fisik Yes System Yes M3',
                'fisik_yes_system_yes_rp' => 'Fisik Yes System Yes Rp',
                'fisik_yes_system_no_palet' => 'Fisik Yes System No Palet',
                'fisik_yes_system_no_m3' => 'Fisik Yes System No M3',
                'fisik_yes_system_no_rp' => 'Fisik Yes System No Rp',
                'fisik_no_system_yes_palet' => 'Fisik No System Yes Palet',
                'fisik_no_system_yes_m3' => 'Fisik No System Yes M3',
                'fisik_no_system_yes_rp' => 'Fisik No System Yes Rp',
                'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockopnameHasil()
    {
        return $this->hasOne(TStockopnameHasil::className(), ['stockopname_hasil_id' => 'stockopname_hasil_id']);
    }
}
