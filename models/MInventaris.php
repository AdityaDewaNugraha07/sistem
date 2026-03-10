<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_inventaris".
 *
 * @property integer $inventaris_id
 * @property integer $inventaris_group_id
 * @property string $kode
 * @property string $inventaris_nama
 * @property string $tgl_perolehan
 * @property integer $peruntukan_dept
 * @property integer $peruntukan_pegawai
 * @property string $spesifikasi
 * @property string $keterangan
 * @property string $lokasi
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
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
 * @property double $label_panjang
 * @property double $label_lebar
 *
 * @property MDepartement $peruntukanDept
 * @property MInventarisGroup $inventarisGroup
 * @property MInventarisSetup[] $mInventarisSetups
 * @property TInventarisMutasi[] $tInventarisMutasis
 * @property TInventarisPerawatan[] $tInventarisPerawatans
 * @property TInventarisVerifikasi[] $tInventarisVerifikasis
 */
class MInventaris extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_inventaris';
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
            [['inventaris_group_id', 'peruntukan_dept', 'peruntukan_pegawai', 'created_by', 'updated_by', 'penghapusan'], 'integer'],
            [['kode', 'inventaris_nama'], 'required'],
            [['tgl_perolehan', 'created_at', 'updated_at'], 'safe'],
            [['spesifikasi', 'keterangan', 'lokasi'], 'string'],
            [['active'], 'boolean'],
            [['accuracy', 'nilai', 'label_panjang', 'label_lebar'], 'number'],
            [['kode'], 'string', 'max' => 50],
            [['inventaris_nama'], 'string', 'max' => 250],
            [['file_qrcode', 'capacity'], 'string', 'max' => 25],
            [['ip_add'], 'string', 'max' => 15],
            [['inventaris_merk', 'inventaris_type', 'inventaris_nomor'], 'string', 'max' => 100],
            [['satuan_pengukuran'], 'string', 'max' => 20],
            [['peruntukan_dept'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['peruntukan_dept' => 'departement_id']],
            [['inventaris_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => MInventarisGroup::className(), 'targetAttribute' => ['inventaris_group_id' => 'inventaris_group_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inventaris_id' => 'Inventaris ID',
            'inventaris_group_id' => 'Inventaris Group ID',
            'kode' => 'Kode',
            'inventaris_nama' => 'Inventaris Nama',
            'tgl_perolehan' => 'Tgl Perolehan',
            'peruntukan_dept' => 'Peruntukan Dept',
            'peruntukan_pegawai' => 'Peruntukan Pegawai',
            'spesifikasi' => 'Spesifikasi',
            'keterangan' => 'Keterangan',
            'lokasi' => 'Lokasi',
            'active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
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
            'label_panjang' => 'Label Panjang',
            'label_lebar' => 'Label Lebar',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeruntukanDept()
    {
        return $this->hasOne(MDepartement::className(), ['departement_id' => 'peruntukan_dept']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventarisGroup()
    {
        return $this->hasOne(MInventarisGroup::className(), ['inventaris_group_id' => 'inventaris_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMInventarisSetups()
    {
        return $this->hasMany(MInventarisSetup::className(), ['inventaris_id' => 'inventaris_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTInventarisMutasis()
    {
        return $this->hasMany(TInventarisMutasi::className(), ['inventaris_id' => 'inventaris_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTInventarisPerawatans()
    {
        return $this->hasMany(TInventarisPerawatan::className(), ['inventaris_id' => 'inventaris_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTInventarisVerifikasis()
    {
        return $this->hasMany(TInventarisVerifikasi::className(), ['inventaris_id' => 'inventaris_id']);
    }
}
