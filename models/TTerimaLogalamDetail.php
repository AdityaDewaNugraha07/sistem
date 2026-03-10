<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_logalam_detail".
 *
 * @property integer $terima_logalam_detail_id
 * @property integer $terima_logalam_id
 * @property string $no_barcode
 * @property string $no_lap
 * @property string $no_grade
 * @property string $no_btg
 * @property integer $kayu_id
 * @property double $panjang
 * @property string $kode_potong
 * @property double $diameter_ujung1
 * @property double $diameter_ujung2
 * @property double $diameter_pangkal1
 * @property double $diameter_pangkal2
 * @property double $diameter_rata
 * @property double $cacat_panjang
 * @property double $cacat_gb
 * @property double $cacat_gr
 * @property double $volume
 * @property string $keterangan
 * @property string $no_produksi
 * @property boolean $fsc
 *
 * @property TTerimaLogalam $terimaLogalam
 * @property TTerimaLogalamPabrik[] $tTerimaLogalamPabriks
 */
class TTerimaLogalamDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_terima_logalam_detail';
    }

    public function behaviors()
    {
        return [\app\components\DeltaGeneralBehavior::className()];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['terima_logalam_id', 'pengajuan_pembelianlog_id', 'kayu_id'], 'integer'],
            [['no_grade', 'no_btg', 'kayu_id', 'no_produksi'], 'required'],
            [['panjang', 'diameter_ujung1', 'diameter_ujung2', 'diameter_pangkal1', 'diameter_pangkal2', 'cacat_panjang', 'cacat_gb', 'cacat_gr', 'volume'], 'number'],
            [['diameter_rata'], 'number'],
            [['keterangan'], 'string'],
            [['no_barcode'], 'string', 'max' => 50],
            [['no_lap', 'no_grade', 'no_btg'], 'string', 'max' => 100],
            [['kode_potong'], 'string', 'max' => 4],
            [['terima_logalam_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaLogalam::className(), 'targetAttribute' => ['terima_logalam_id' => 'terima_logalam_id']],
            [['no_lap', 'no_barcode'], 'unique'],
            [['fsc'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'terima_logalam_detail_id' => 'Terima Logalam Detail',
            'terima_logalam_id' => 'Terima Logalam',
            'pengajuan_pembelianlog_id' => 'Pengajuan Pembelian',
            'no_barcode' => 'No Barcode',
            'no_lap' => 'No Lap',
            'no_grade' => 'No Grade',
            'no_btg' => 'No Btg',
            'kayu_id' => 'Kayu',
            'panjang' => 'Panjang',
            'kode_potong' => 'Kode Potong',
            'diameter_ujung1' => 'Diameter Ujung1',
            'diameter_ujung2' => 'Diameter Ujung2',
            'diameter_pangkal1' => 'Diameter Pangkal1',
            'diameter_pangkal2' => 'Diameter Pangkal2',
            'diameter_rata' => 'Diameter Rata',
            'cacat_panjang' => 'Cacat Panjang',
            'cacat_gb' => 'Cacat Gb',
            'cacat_gr' => 'Cacat Gr',
            'volume' => 'Volume',
            'keterangan' => 'Keterangan',
            'no_produksi' => 'Nomor Produksi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerimaLogalam()
    {
        return $this->hasOne(TTerimaLogalam::className(), ['terima_logalam_id' => 'terima_logalam_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaLogalamPabriks()
    {
        return $this->hasMany(TTerimaLogalamPabrik::className(), ['terima_logalam_detail_id' => 'terima_logalam_detail_id']);
    }
}
