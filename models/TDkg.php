<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_dkg".
 *
 * @property integer $dkg_id
 * @property string $kode
 * @property string $tipe
 * @property string $tanggal
 * @property integer $graderlog_id
 * @property integer $wilayah_dinas_id
 * @property string $tujuan
 * @property string $keterangan
 * @property integer $loglist_id
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property double $saldo_akhir_dinas
 * @property double $saldo_akhir_makan
 * @property double $selesai_dinas_at
 *
 * @property TAjuandinasGrader[] $tAjuandinasGraders
 * @property TAjuanmakanGrader[] $tAjuanmakanGraders
 * @property MGraderlog $graderlog
 * @property MWilayahDinas $wilayahDinas
 * @property TCancelTransaksi $cancelTransaksi
 * @property TLoglist $loglist
 * @property TRealisasidinasGrader[] $tRealisasidinasGraders
 * @property TRealisasimakanGrader[] $tRealisasimakanGraders
 */ 
class TDkg extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $graderlog_nm,$wilayah_dinas_nama;
	const AKTIF_DINAS = "AKTIF DINAS";
	const NON_AKTIF_DINAS = "NON-AKTIF DINAS";
    public static function tableName()
    {
        return 't_dkg';
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
            [['kode', 'tipe', 'tanggal', 'graderlog_id', 'wilayah_dinas_id'], 'required'],
            [['tanggal', 'created_at', 'updated_at', 'selesai_dinas_at'], 'safe'],
            [['graderlog_id', 'wilayah_dinas_id', 'loglist_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['tujuan', 'keterangan'], 'string'],
            [['saldo_akhir_dinas','saldo_akhir_makan'], 'number'],
            [['kode', 'status'], 'string', 'max' => 50],
            [['tipe'], 'string', 'max' => 25],
            [['graderlog_id'], 'exist', 'skipOnError' => true, 'targetClass' => MGraderlog::className(), 'targetAttribute' => ['graderlog_id' => 'graderlog_id']],
            [['wilayah_dinas_id'], 'exist', 'skipOnError' => true, 'targetClass' => MWilayahDinas::className(), 'targetAttribute' => ['wilayah_dinas_id' => 'wilayah_dinas_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['loglist_id'], 'exist', 'skipOnError' => true, 'targetClass' => TLoglist::className(), 'targetAttribute' => ['loglist_id' => 'loglist_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'dkg_id' => Yii::t('app', 'Dkg'),
			'kode' => Yii::t('app', 'Kode'),
			'tipe' => Yii::t('app', 'Tipe Dinas'),
			'tanggal' => Yii::t('app', 'Tanggal'),
			'graderlog_id' => Yii::t('app', 'Grader'),
			'wilayah_dinas_id' => Yii::t('app', 'Wilayah Dinas'),
			'tujuan' => Yii::t('app', 'Tujuan'),
			'keterangan' => Yii::t('app', 'Keterangan'),
			'loglist_id' => Yii::t('app', 'Loglist'),
			'status' => Yii::t('app', 'Status'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'saldo_akhir_dinas' => Yii::t('app', 'Saldo Akhir Dinas'),
			'saldo_akhir_makan' => Yii::t('app', 'Saldo Akhir Makan'),
			'selesai_dinas_at' => Yii::t('app', 'Selesai Dinas At'),
		]; 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGraderlog()
    {
        return $this->hasOne(MGraderlog::className(), ['graderlog_id' => 'graderlog_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWilayahDinas()
    {
        return $this->hasOne(MWilayahDinas::className(), ['wilayah_dinas_id' => 'wilayah_dinas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoglist()
    {
        return $this->hasOne(TLoglist::className(), ['loglist_id' => 'loglist_id']);
    } 
	
	public static function getOptionListGraderEdit($dkg_id)
    {
        $res = self::find()->where("status = '".self::AKTIF_DINAS."' OR dkg_id = {$dkg_id}")->orderBy(['created_at'=>SORT_DESC])->all();
		$ret = [];
		if(count($res)){
			foreach($res as $i => $dkg){
				$ret[$dkg->dkg_id] = $dkg->graderlog->graderlog_nm;
			}
		}
        return $ret;
    }
    
	public static function getOptionListGrader()
    {
        $res = self::find()->where("status = '".self::AKTIF_DINAS."'")->orderBy(['created_at'=>SORT_DESC])->all();
		$ret = [];
		if(count($res)){
			foreach($res as $i => $dkg){
				$ret[$dkg->dkg_id] = $dkg->graderlog->graderlog_nm;
			}
		}
        return $ret;
    }
	
	public static function getOptionListDkg()
    {
        $res = self::find()->where("status = '".self::AKTIF_DINAS."'")->orderBy(['created_at'=>SORT_DESC])->all();
		$ret = [];
		if(count($res)){
			foreach($res as $i => $dkg){
				$ret[$dkg->dkg_id] = $dkg->kode;
			}
		}
        return $ret;
    }
}
