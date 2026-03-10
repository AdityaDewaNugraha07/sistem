<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_inventaris".
 *
 * @property integer $inventaris_id
 * @property string $kode
 * @property integer $inventaris_group_id
 * @property string $group_asset
 * @property string $group_kode
 * @property string $inventaris_group_nama
 * @property string $inventaris_group_kode
 * @property string $inventaris_nama
 * @property string $spesifikasi
 * @property string $keterangan
 * @property string $tgl_perolehan
 * @property integer $peruntukan_dept
 * @property string $departement_nama
 * @property integer $peruntukan_pegawai
 * @property string $pegawai_nama
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $asset_group_id
 * @property string $lokasi
 * @property integer $inventaris_group_pic
 * @property integer $inventaris_pic_dept
 * @property string $dept_pic
 * @property integer $penghapusan
 * @property string $file_qrcode
 * @property string $ip_add
 * @property string $inventaris_merk
 * @property string $inventaris_type
 * @property string $inventaris_nomor
 * @property string $satuan_pengukuran
 * @property string $capacity
 * @property double $accuracy
 * @property double $nilai
 * @property boolean $verifikasi_alat_ukur
 * @property double $label_panjang
 * @property double $label_lebar
 */
class ViewInventaris extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_inventaris';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inventaris_id', 'inventaris_group_id', 'peruntukan_dept', 'peruntukan_pegawai', 'created_by', 'updated_by', 'asset_group_id', 'inventaris_group_pic', 'inventaris_pic_dept', 'penghapusan'], 'integer'],
            [['spesifikasi', 'keterangan', 'lokasi'], 'string'],
            [['tgl_perolehan', 'created_at', 'updated_at'], 'safe'],
            [['active', 'verifikasi_alat_ukur'], 'boolean'],
            [['accuracy', 'nilai', 'label_panjang', 'label_lebar'], 'number'],
            [['kode', 'departement_nama', 'dept_pic'], 'string', 'max' => 50],
            [['group_asset', 'group_kode', 'inventaris_group_nama', 'pegawai_nama', 'inventaris_merk', 'inventaris_type', 'inventaris_nomor'], 'string', 'max' => 100],
            [['inventaris_group_kode'], 'string', 'max' => 10],
            [['inventaris_nama'], 'string', 'max' => 250],
            [['file_qrcode', 'capacity'], 'string', 'max' => 25],
            [['ip_add'], 'string', 'max' => 15],
            [['satuan_pengukuran'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inventaris_id' => 'Inventaris ID',
            'kode' => 'Kode',
            'inventaris_group_id' => 'Inventaris Group ID',
            'group_asset' => 'Group Asset',
            'group_kode' => 'Group Kode',
            'inventaris_group_nama' => 'Inventaris Group Nama',
            'inventaris_group_kode' => 'Inventaris Group Kode',
            'inventaris_nama' => 'Inventaris Nama',
            'spesifikasi' => 'Spesifikasi',
            'keterangan' => 'Keterangan',
            'tgl_perolehan' => 'Tgl Perolehan',
            'peruntukan_dept' => 'Peruntukan Dept',
            'departement_nama' => 'Departement Nama',
            'peruntukan_pegawai' => 'Peruntukan Pegawai',
            'pegawai_nama' => 'Pegawai Nama',
            'active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'asset_group_id' => 'Asset Group ID',
            'lokasi' => 'Lokasi',
            'inventaris_group_pic' => 'Inventaris Group Pic',
            'inventaris_pic_dept' => 'Inventaris Pic Dept',
            'dept_pic' => 'Dept Pic',
            'penghapusan' => 'Penghapusan',
            'file_qrcode' => 'File Qrcode',
            'ip_add' => 'Ip Add',
            'inventaris_merk' => 'Inventaris Merk',
            'inventaris_type' => 'Inventaris Type',
            'inventaris_nomor' => 'Inventaris Nomor',
            'satuan_pengukuran' => 'Satuan Pengukuran',
            'capacity' => 'Capacity',
            'accuracy' => 'Accuracy',
            'nilai' => 'Nilai',
            'verifikasi_alat_ukur' => 'Verifikasi Alat Ukur',
            'label_panjang' => 'Label Panjang',
            'label_lebar' => 'Label Lebar',
        ];
    }
}
