<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "map_terimamutasi_hasilrepacking".
 *
 * @property integer $terimamutasi_hasilrepacking_id
 * @property integer $pengajuan_repacking_id
 * @property integer $mutasi_keluar_id
 * @property integer $terima_mutasi_id
 * @property integer $hasil_repacking_id
 * @property integer $nomor_produksi_lama
 * @property integer $nomor_produksi_baru
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property THasilRepacking $hasilRepacking
 * @property TMutasiKeluar $mutasiKeluar
 * @property TPengajuanRepacking $pengajuanRepacking
 * @property TTerimaMutasi $terimaMutasi
 */
class MapTerimamutasiHasilrepacking extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $qty_m3,$qty_kecil;
    public static function tableName()
    {
        return 'map_terimamutasi_hasilrepacking';
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
            [['pengajuan_repacking_id', 'mutasi_keluar_id', 'terima_mutasi_id', 'hasil_repacking_id', 'nomor_produksi_lama', 'nomor_produksi_baru', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['pengajuan_repacking_id', 'mutasi_keluar_id', 'terima_mutasi_id', 'hasil_repacking_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nomor_produksi_lama', 'nomor_produksi_baru'], 'string', 'max' => 50],
            [['hasil_repacking_id'], 'exist', 'skipOnError' => true, 'targetClass' => THasilRepacking::className(), 'targetAttribute' => ['hasil_repacking_id' => 'hasil_repacking_id']],
            [['mutasi_keluar_id'], 'exist', 'skipOnError' => true, 'targetClass' => TMutasiKeluar::className(), 'targetAttribute' => ['mutasi_keluar_id' => 'mutasi_keluar_id']],
            [['pengajuan_repacking_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPengajuanRepacking::className(), 'targetAttribute' => ['pengajuan_repacking_id' => 'pengajuan_repacking_id']],
            [['terima_mutasi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaMutasi::className(), 'targetAttribute' => ['terima_mutasi_id' => 'terima_mutasi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'terimamutasi_hasilrepacking_id' => 'Terimamutasi Hasilrepacking',
                'pengajuan_repacking_id' => 'Pengajuan Repacking',
                'mutasi_keluar_id' => 'Mutasi Keluar',
                'terima_mutasi_id' => 'Terima Mutasi',
                'hasil_repacking_id' => 'Hasil Repacking',
                'nomor_produksi_lama' => 'Nomor Produksi Lama',
                'nomor_produksi_baru' => 'Nomor Produksi Baru',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHasilRepacking()
    {
        return $this->hasOne(THasilRepacking::className(), ['hasil_repacking_id' => 'hasil_repacking_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMutasiKeluar()
    {
        return $this->hasOne(TMutasiKeluar::className(), ['mutasi_keluar_id' => 'mutasi_keluar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanRepacking()
    {
        return $this->hasOne(TPengajuanRepacking::className(), ['pengajuan_repacking_id' => 'pengajuan_repacking_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerimaMutasi()
    {
        return $this->hasOne(TTerimaMutasi::className(), ['terima_mutasi_id' => 'terima_mutasi_id']);
    }
}
