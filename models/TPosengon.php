<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_posengon".
 *
 * @property integer $posengon_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $posengon_rencana_id
 * @property integer $suplier_id
 * @property string $nama_barang
 * @property string $panjang
 * @property integer $kuota
 * @property string $diameter_harga
 * @property string $periode_pengiriman_awal
 * @property string $periode_pengiriman_akhir
 * @property string $cara_bayar
 * @property string $rekening_bank
 * @property string $spesifikasi_log
 * @property string $disetujui_supplier
 * @property integer $disetujui_cwm
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MPegawai $disetujuiCwm
 * @property MSuplier $suplier
 * @property TPosengonRencana $posengonRencana
 * @property TTagihanSengon[] $tTagihanSengons
 * @property TTerimaSengon[] $tTerimaSengons
 */
class TPosengon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $qty_m3,$wilayah,$diameter_awal,$diameter_akhir,$harga;
    public static function tableName()
    {
        return 't_posengon';
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
            [['kode', 'tanggal', 'posengon_rencana_id', 'suplier_id', 'nama_barang', 'panjang', 'kuota', 'diameter_harga', 'periode_pengiriman_awal', 'periode_pengiriman_akhir', 'cara_bayar', 'spesifikasi_log', 'disetujui_supplier', 'disetujui_cwm', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'periode_pengiriman_awal', 'periode_pengiriman_akhir', 'created_at', 'updated_at'], 'safe'],
            [['posengon_rencana_id', 'suplier_id', 'disetujui_cwm', 'created_by', 'updated_by'], 'integer'],
            [['diameter_harga', 'cara_bayar', 'rekening_bank', 'spesifikasi_log','kuota','panjang'], 'string'],
            [['kode', 'status'], 'string', 'max' => 30],
            [['nama_barang', 'disetujui_supplier'], 'string', 'max' => 100],
            [['disetujui_cwm'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disetujui_cwm' => 'pegawai_id']],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['posengon_rencana_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPosengonRencana::className(), 'targetAttribute' => ['posengon_rencana_id' => 'posengon_rencana_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'posengon_id' => 'Posengon',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'posengon_rencana_id' => 'Posengon Rencana',
                'suplier_id' => 'Suplier',
                'nama_barang' => 'Nama Barang',
                'panjang' => 'Panjang',
                'kuota' => 'Kuota',
                'diameter_harga' => 'Diameter Harga',
                'periode_pengiriman_awal' => 'Periode Pengiriman Awal',
                'periode_pengiriman_akhir' => 'Periode Pengiriman Akhir',
                'cara_bayar' => 'Cara Bayar',
                'rekening_bank' => 'Rekening Bank',
                'spesifikasi_log' => 'Spesifikasi Log',
                'disetujui_supplier' => 'Disetujui Supplier',
                'disetujui_cwm' => 'Disetujui Cwm',
                'status' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisetujuiCwm()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'disetujui_cwm']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuplier()
    {
        return $this->hasOne(MSuplier::className(), ['suplier_id' => 'suplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosengonRencana()
    {
        return $this->hasOne(TPosengonRencana::className(), ['posengon_rencana_id' => 'posengon_rencana_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTagihanSengons()
    {
        return $this->hasMany(TTagihanSengon::className(), ['posengon_id' => 'posengon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaSengons()
    {
        return $this->hasMany(TTerimaSengon::className(), ['posengon_id' => 'posengon_id']);
    }
    
    public static function getOptionList()
    {
        $res = self::find()->orderBy('posengon_id DESC')->all();
		$ret = [];
		foreach($res as $i => $po){
			$ret[$po->posengon_id] = $po->kode.' - '.$po->suplier->suplier_nm;
		}
        return $ret;
    }
}
