<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_terima_logalam_pabrik".
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
 * @property double $cacat_panjang
 * @property double $cacat_gb
 * @property double $cacat_gr
 * @property double $volume
 * @property string $keterangan
 * @property integer $lampiran
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property double $diameter_rata
 * @property integer $pengajuan_pembelianlog_id
 * @property string $no_produksi
 * @property integer $terima_logalam_pabrik_id
 * @property string $tanggal_terimapabrik
 * @property integer $pic_terimapabrik
 * @property string $created_atpabrik
 * @property integer $created_bypabrik
 * @property string $kode_terimapabrik
 * @property MPegawai $picTerima
 * @property TTerimaLogalamDetail $terimaLogalamDetail
 * @property TTerimaLogalam $terimaLogalam
 * @property TTerimaLogalamPabrik[] $tTerimaLogalamPabriks
 * @property ViewTerimaLogalamPabrik[] $ViewTerimaLogalamPabrik
 */

class ViewTerimaLogalamPabrik extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_terima_logalam_pabrik';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['terima_logalam_detail_id', 'terima_logalam_id', 'kayu_id', 'lampiran', 'created_by', 'updated_by', 'pengajuan_pembelianlog_id', 'terima_logalam_pabrik_id', 'pic_terimapabrik', 'created_bypabrik'], 'integer'],
            [['panjang', 'diameter_ujung1', 'diameter_ujung2', 'diameter_pangkal1', 'diameter_pangkal2', 'cacat_panjang', 'cacat_gb', 'cacat_gr', 'volume', 'diameter_rata'], 'number'],
            [['keterangan'], 'string'],
            [['created_at', 'updated_at', 'tanggal_terimapabrik', 'created_atpabrik'], 'safe'],
            [['no_barcode', 'no_produksi'], 'string', 'max' => 50],
            [['no_lap', 'no_grade', 'no_btg'], 'string', 'max' => 100],
            [['kode_potong'], 'string', 'max' => 4],
            [['kode_terimapabrik'], 'string', 'max' => 25],
        ];
    }
    public function attributeLabels()
    {
        return [
            'terima_logalam_detail_id' => 'Terima Logalam Detail ID',
            'terima_logalam_id' => 'Terima Logalam ID',
            'no_barcode' => 'No Barcode',
            'no_lap' => 'No Lap',
            'no_grade' => 'No Grade',
            'no_btg' => 'No Btg',
            'kayu_id' => 'Kayu ID',
            'panjang' => 'Panjang',
            'kode_potong' => 'Kode Potong',
            'diameter_ujung1' => 'Diameter Ujung1',
            'diameter_ujung2' => 'Diameter Ujung2',
            'diameter_pangkal1' => 'Diameter Pangkal1',
            'diameter_pangkal2' => 'Diameter Pangkal2',
            'cacat_panjang' => 'Cacat Panjang',
            'cacat_gb' => 'Cacat Gb',
            'cacat_gr' => 'Cacat Gr',
            'volume' => 'Volume',
            'keterangan' => 'Keterangan',
            'lampiran' => 'Lampiran',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'diameter_rata' => 'Diameter Rata',
            'pengajuan_pembelianlog_id' => 'Pengajuan Pembelianlog ID',
            'no_produksi' => 'No Produksi',
            'terima_logalam_pabrik_id' => 'Terima Logalam Pabrik ID',
            'tanggal_terimapabrik' => 'Tanggal Terimapabrik',
            'pic_terimapabrik' => 'Pic Terimapabrik',
            'created_atpabrik' => 'Created Atpabrik',
            'created_bypabrik' => 'Created Bypabrik',
            'kode_terimapabrik' => 'Kode Terimapabrik',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerimaLogalam()
    {
        return $this->hasOne(TTerimaLogalam::className(), ['terima_logalam_id' => 'terima_logalam_id']);
    }
    public function getTTerimaLogalamPabriks()
    {
        return $this->hasMany(TTerimaLogalamPabrik::className(), ['terima_logalam_detail_id' => 'terima_logalam_detail_id']);
    }
}
