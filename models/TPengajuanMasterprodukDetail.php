<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pengajuan_masterproduk_detail".
 *
 * @property integer $pengajuan_masterproduk_detail_id
 * @property integer $pengajuan_masterproduk_id
 * @property string $produk_kode
 * @property string $produk_group
 * @property string $produk_nama
 * @property string $produk_dimensi
 * @property double $produk_p
 * @property string $produk_p_satuan
 * @property double $produk_l
 * @property string $produk_l_satuan
 * @property double $produk_t
 * @property string $produk_t_satuan
 * @property string $produk_satuan_besar
 * @property string $produk_satuan_kecil
 * @property double $produk_qty_satuan_kecil
 * @property string $produk_gbr
 * @property integer $produk_harga_distributor
 * @property integer $produk_harga_agent
 * @property integer $produk_harga_enduser
 * @property integer $produk_harga_hpp
 * @property double $produk_stock
 * @property double $kapasitas_kubikasi
 * @property string $jenis_kayu
 * @property string $grade
 * @property string $glue
 * @property string $profil_kayu
 * @property string $kondisi_kayu
 * @property integer $kayu_id
 * @property string $diameter_range
 * @property string $warna_kayu
 *
 * @property MKayu $kayu
 */
class TPengajuanMasterprodukDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_pengajuan_masterproduk_detail';
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
            [['pengajuan_masterproduk_id', 'produk_kode', 'produk_group', 'produk_nama', 'produk_dimensi', 'produk_p', 'produk_p_satuan', 'produk_l', 'produk_l_satuan', 'produk_t', 'produk_t_satuan', 'produk_qty_satuan_kecil'], 'required'],
            [['pengajuan_masterproduk_id', 'produk_harga_distributor', 'produk_harga_agent', 'produk_harga_enduser', 'produk_harga_hpp', 'kayu_id'], 'integer'],
            [['produk_qty_satuan_kecil', 'produk_stock', 'kapasitas_kubikasi'], 'number'],
            [['produk_gbr'], 'string'],
            [['produk_kode', 'jenis_kayu', 'grade', 'glue', 'profil_kayu', 'kondisi_kayu', 'diameter_range'], 'string', 'max' => 100],
            [['produk_group', 'produk_dimensi', 'produk_p_satuan', 'produk_l_satuan', 'produk_t_satuan', 'produk_satuan_besar', 'produk_satuan_kecil'], 'string', 'max' => 50],
            [['produk_nama'], 'string', 'max' => 200],
            [['warna_kayu'], 'string', 'max' => 20],
            [['produk_kode', 'produk_nama'], 'unique'],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengajuan_masterproduk_detail_id' => 'Pengajuan Masterproduk Detail',
                'pengajuan_masterproduk_id' => 'Pengajuan Masterproduk',
                'produk_kode' => 'Produk Kode',
                'produk_group' => 'Produk Group',
                'produk_nama' => 'Produk Nama',
                'produk_dimensi' => 'Produk Dimensi',
                'produk_p' => 'Panjang',
                'produk_p_satuan' => 'Produk P Satuan',
                'produk_l' => 'Lebar',
                'produk_l_satuan' => 'Produk L Satuan',
                'produk_t' => 'Tinggi / Tebal',
                'produk_t_satuan' => 'Produk T Satuan',
                'produk_satuan_besar' => 'Produk Satuan Besar',
                'produk_satuan_kecil' => 'Produk Satuan Kecil',
                'produk_qty_satuan_kecil' => 'Produk Qty Satuan Kecil',
                'produk_gbr' => 'Gambar',
                'produk_harga_distributor' => 'Produk Harga Distributor',
                'produk_harga_agent' => 'Produk Harga Agent',
                'produk_harga_enduser' => 'Produk Harga Enduser',
                'produk_harga_hpp' => 'Produk Harga Hpp',
                'produk_stock' => 'Produk Stock',
                'kapasitas_kubikasi' => 'Kapasitas Kubikasi',
                'jenis_kayu' => 'Jenis Kayu',
                'grade' => 'Grade',
                'glue' => 'Glue',
                'profil_kayu' => 'Profil Kayu',
                'kondisi_kayu' => 'Kondisi Kayu',
                'kayu_id' => 'Kayu',
                'diameter_range' => 'Diameter Range',
                'warna_kayu' => 'Warna Kayu',
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