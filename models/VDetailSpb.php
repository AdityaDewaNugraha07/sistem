<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_detail_spb".
 *
 * @property integer $spb_id
 * @property integer $departement_id
 * @property string $departement_nama
 * @property string $spb_jenis
 * @property string $spb_tipe
 * @property string $spb_kode
 * @property string $spb_nomor
 * @property string $spb_tanggal
 * @property integer $spb_diminta
 * @property integer $spb_disetujui
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $spb_keterangan
 * @property string $spb_status
 * @property integer $spbd_id
 * @property integer $bhp_id
 * @property string $bhp_kode
 * @property string $bhp_group
 * @property string $bhp_nm
 * @property string $bhp_grade
 * @property string $bhp_satuan
 * @property integer $spbd_jml
 * @property string $spbd_tgl_dipakai
 * @property string $spbd_ket
 * @property integer $spbd_jml_terpenuhi
 */
class VDetailSpb extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $pesan, $terpenuhi;
    public static function tableName()
    {
        return 'v_detail_spb';
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
            [['spb_id', 'departement_id', 'spb_diminta', 'spb_disetujui', 'created_by', 'updated_by', 'spbd_id', 'bhp_id', 'spbd_jml', 'spbd_jml_terpenuhi'], 'integer'],
            [['spb_tanggal', 'created_at', 'updated_at', 'spbd_tgl_dipakai'], 'safe'],
            [['spb_keterangan', 'spbd_ket'], 'string'],
            [['departement_nama', 'spb_jenis', 'spb_tipe', 'bhp_group', 'bhp_nm', 'bhp_grade', 'bhp_satuan'], 'string', 'max' => 50],
            [['spb_kode', 'spb_nomor', 'spb_status'], 'string', 'max' => 30],
            [['bhp_kode'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spb_id' => Yii::t('app', 'Spb'),
                'departement_id' => Yii::t('app', 'Departement'),
                'departement_nama' => Yii::t('app', 'Departement Nama'),
                'spb_jenis' => Yii::t('app', 'Spb Jenis'),
                'spb_tipe' => Yii::t('app', 'Spb Tipe'),
                'spb_kode' => Yii::t('app', 'Spb Kode'),
                'spb_nomor' => Yii::t('app', 'Spb Nomor'),
                'spb_tanggal' => Yii::t('app', 'Spb Tanggal'),
                'spb_diminta' => Yii::t('app', 'Spb Diminta'),
                'spb_disetujui' => Yii::t('app', 'Spb Disetujui'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'spb_keterangan' => Yii::t('app', 'Spb Keterangan'),
                'spb_status' => Yii::t('app', 'Spb Status'),
                'spbd_id' => Yii::t('app', 'Spbd'),
                'bhp_id' => Yii::t('app', 'Bhp'),
                'bhp_kode' => Yii::t('app', 'Bhp Kode'),
                'bhp_group' => Yii::t('app', 'Bhp Group'),
                'bhp_nm' => Yii::t('app', 'Bhp Nm'),
                'bhp_grade' => Yii::t('app', 'Bhp Grade'),
                'bhp_satuan' => Yii::t('app', 'Bhp Satuan'),
                'spbd_jml' => Yii::t('app', 'Spbd Jml'),
                'spbd_tgl_dipakai' => Yii::t('app', 'Spbd Tgl Dipakai'),
                'spbd_ket' => Yii::t('app', 'Spbd Ket'),
                'spbd_jml_terpenuhi' => Yii::t('app', 'Spbd Jml Terpenuhi'),
        ];
    }
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getBhp()
    {
        return $this->hasOne(MBrgBhp::className(), ['bhp_id' => 'bhp_id']);
    }
	
	
	public static function getRekapSpbBelumTerlayani($departement_id,$bhp_id){
		$model = self::find()
			->select("v_detail_spb.departement_id, v_detail_spb.departement_nama, v_detail_spb.bhp_id, v_detail_spb.bhp_nm, SUM(spbd_jml) as pesan, SUM(spbd_jml_terpenuhi) as terpenuhi")
			->where("v_detail_spb.departement_id = '".$departement_id."' AND v_detail_spb.spb_status NOT IN ('TERPENUHI','DITOLAK') AND ((v_detail_spb.spbd_jml - v_detail_spb.spbd_jml_terpenuhi) <> 0) AND v_detail_spb.bhp_id = ".$bhp_id)
			->groupBy("v_detail_spb.departement_id, v_detail_spb.departement_nama, v_detail_spb.bhp_id, v_detail_spb.bhp_nm")
			->one();
		return $model;
	}
}
