<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_logalam".
 *
 * @property integer $terima_logalam_id
 * @property string $area_pembelian
 * @property integer $spk_shipping_id
 * @property integer $pengajuan_pembelianlog_id
 * @property string $tanggal
 * @property string $kode
 * @property string $no_truk
 * @property string $no_dokumen
 * @property string $keterangan
 * @property string $peruntukan
 * @property string $lokasi_tujuan
 * @property string $alamat_tujuan
 * @property integer $pic_ukur
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cetak
 *
 * @property MPegawai $picUkur
 * @property TTerimaLogalamDetail[] $tTerimaLogalamDetails
 */
class TTerimaLogalam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal, $tgl_akhir;
    public static function tableName()
    {
        return 't_terima_logalam';
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
            [['area_pembelian', 'tanggal', 'kode', 'no_truk', 'no_dokumen', 'peruntukan', 'lokasi_tujuan', 'pic_ukur', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['spk_shipping_id', 'pengajuan_pembelianlog_id', 'pic_ukur', 'cancel_transaksi_id', 'created_by', 'updated_by', 'cetak'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['keterangan', 'alamat_tujuan'], 'string'],
            [['area_pembelian', 'kode'], 'string', 'max' => 25],
            [['no_truk'], 'string', 'max' => 12],
            [['no_dokumen', 'peruntukan'], 'string', 'max' => 20],
            [['lokasi_tujuan'], 'string', 'max' => 100],
            [['pic_ukur'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pic_ukur' => 'pegawai_id']],
            [['no_dokumen'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'terima_logalam_id' => 'Terima Logalam',
            'area_pembelian' => 'Area Pembelian',
            'spk_shipping_id' => 'Spk Shipping',
            'pengajuan_pembelianlog_id' => 'Pengajuan Pembelianlog',
            'tanggal' => 'Tanggal',
            'kode' => 'Kode',
            'no_truk' => 'No Truk',
            'no_dokumen' => 'No Dokumen',
            'keterangan' => 'Keterangan',
            'peruntukan' => 'Peruntukan',
            'lokasi_tujuan' => 'Ditujukan',
            'pic_ukur' => 'Pic Ukur',
            'cancel_transaksi_id' => 'Cancel Transaksi',
            'created_at' => 'Create Time',
            'created_by' => 'Created By',
            'updated_at' => 'Last Update Time',
            'updated_by' => 'Last Updated By',
            'cetak' => 'Cetak',
            'alamat_tujuan' => 'Alamat Tujuan',
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPicUkur()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pic_ukur']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaLogalamDetails()
    {
        return $this->hasMany(TTerimaLogalamDetail::className(), ['terima_logalam_id' => 'terima_logalam_id']);
    }

    public static function getOptionLokTujuan()
    {
        $res = Yii::$app->db->createCommand("
                    SELECT lokasi_tujuan FROM t_terima_logalam WHERE peruntukan = 'Trading' GROUP BY lokasi_tujuan ORDER BY lokasi_tujuan
                    ")->queryAll();
        return yii\helpers\ArrayHelper::map($res, 'lokasi_tujuan', 'lokasi_tujuan');
    }
}