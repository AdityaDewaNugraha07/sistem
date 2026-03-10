<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_kas_bon".
 *
 * @property integer $kas_bon_id
 * @property integer $kas_kecil_id
 * @property string $kode
 * @property string $tanggal
 * @property string $penerima
 * @property string $deskripsi
 * @property double $nominal
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $tipe
 * @property string $status_bon
 * @property integer $bkk_id
 * @property boolean $terimakasbon_kk
 * @property integer $gkk_id
 *
 * @property HBonsementara[] $hBonsementaras
 * @property TBkk $bkk
 * @property TCancelTransaksi $cancelTransaksi
 * @property TGkk $gkk
 * @property TKasKecil $kasKecil
 * @property TPpk[] $tPpks
 */ 

class TKasBon extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $is_kasbonkaskecil,$tanggal_kasbon,$tgl_awal,$tgl_akhir;
    public static function tableName()
    {
        return 't_kas_bon';
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
            [['kas_kecil_id', 'created_by', 'updated_by', 'cancel_transaksi_id', 'bkk_id', 'gkk_id'], 'integer'],
            [['kode', 'tanggal', 'penerima', 'deskripsi', 'created_at', 'created_by', 'updated_at', 'updated_by','nominal'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['deskripsi'], 'string'],
            [['terimakasbon_kk'], 'boolean'],
            [['kode', 'status', 'tipe', 'status_bon'], 'string', 'max' => 50],
            [['penerima'], 'string', 'max' => 200],
            [['kode'], 'unique'],
            [['bkk_id'], 'exist', 'skipOnError' => true, 'targetClass' => TBkk::className(), 'targetAttribute' => ['bkk_id' => 'bkk_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['gkk_id'], 'exist', 'skipOnError' => true, 'targetClass' => TGkk::className(), 'targetAttribute' => ['gkk_id' => 'gkk_id']],
            [['kas_kecil_id'], 'exist', 'skipOnError' => true, 'targetClass' => TKasKecil::className(), 'targetAttribute' => ['kas_kecil_id' => 'kas_kecil_id']],
        ]; 
   }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'kas_bon_id' => Yii::t('app', 'Kas Bon'),
			'kas_kecil_id' => Yii::t('app', 'Kas Kecil'),
			'kode' => Yii::t('app', 'Kode'),
			'tanggal' => Yii::t('app', 'Tanggal'),
			'penerima' => Yii::t('app', 'Penerima'),
			'deskripsi' => Yii::t('app', 'Deskripsi'),
			'nominal' => Yii::t('app', 'Nominal'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'status' => Yii::t('app', 'Status'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'tipe' => Yii::t('app', 'Tipe'),
			'status_bon' => Yii::t('app', 'Status Bon'),
			'bkk_id' => Yii::t('app', 'Bkk'),
			'terimakasbon_kk' => Yii::t('app', 'Terimakasbon Kk'),
			'gkk_id' => Yii::t('app', 'Gkk'),
        ]; 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHBonsementaras()
    {
        return $this->hasMany(HBonsementara::className(), ['kas_bon_id' => 'kas_bon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBkk()
    {
        return $this->hasOne(TBkk::className(), ['bkk_id' => 'bkk_id']);
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
    public function getGkk()
    {
        return $this->hasOne(TGkk::className(), ['gkk_id' => 'gkk_id']);
    } 

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKasKecil()
    {
        return $this->hasOne(TKasKecil::className(), ['kas_kecil_id' => 'kas_kecil_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTPpks()
    {
        return $this->hasMany(TPpk::className(), ['kas_bon_id' => 'kas_bon_id']);
    } 
	
	public static function kasbonGantung(){
		return \app\models\TKasBon::find()->where("kas_kecil_id IS NULL AND tipe = 'KK' AND (status_bon != 'PAID' OR status_bon IS NULL)")->orderBy(['tanggal'=>SORT_ASC,'kas_bon_id'=>SORT_ASC])->all();
	}
	public static function kasbonGantungKB(){
		return \app\models\TKasBon::find()->where("kas_kecil_id IS NULL AND tipe = 'KB' AND (status_bon != 'PAID' OR status_bon IS NULL)")->orderBy(['tanggal'=>SORT_ASC,'kas_bon_id'=>SORT_ASC])->all();
	}
	public static function kasbonGantungKBKeKK($pertgl=null){
		if(!empty($pertgl)){
			$return = \app\models\TKasBon::find()
					->where("kas_kecil_id IS NULL AND tipe = 'KB' AND (status_bon != 'PAID' OR status_bon IS NULL) AND status='KASBON KASBESAR KE KASKECIL' AND tanggal <= '".$pertgl."'")
					->orderBy(['tanggal'=>SORT_ASC,'kas_bon_id'=>SORT_ASC])
					->all();
		}else{
			$return = \app\models\TKasBon::find()
					->where("kas_kecil_id IS NULL AND tipe = 'KB' AND (status_bon != 'PAID' OR status_bon IS NULL) AND status='KASBON KASBESAR KE KASKECIL'")
					->orderBy(['tanggal'=>SORT_ASC,'kas_bon_id'=>SORT_ASC])
					->all();
		}
		return $return;
	}
	
	public function searchLaporan($tipe) {
		$query = self::find();
		$query->select(self::tableName().'.kas_bon_id, t_kas_bon.kode, t_kas_bon.tanggal, t_kas_bon.penerima, t_kas_bon.deskripsi, t_kas_bon.nominal, t_kas_kecil.tanggal AS tanggal_real');
		$query->join('LEFT JOIN', "t_gkk","t_gkk.gkk_id = t_kas_bon.gkk_id");
		$query->join('LEFT JOIN', "t_voucher_pengeluaran","t_voucher_pengeluaran.voucher_pengeluaran_id = t_gkk.voucher_pengeluaran_id");
		$query->join('LEFT JOIN', "t_kas_kecil","t_kas_kecil.kas_kecil_id = t_kas_bon.kas_kecil_id");
		if($tipe=="gantung"){
			$query->andWhere("t_kas_bon.kas_kecil_id IS NULL AND t_kas_bon.tipe = 'KK' AND (status_bon != 'PAID' OR status_bon IS NULL)");
		}else if($tipe=="realisasi"){
			$query->andWhere("(t_kas_bon.kas_kecil_id IS NOT NULL OR status_bon = 'PAID' AND t_kas_bon.tipe = 'KK')");
		}
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.tanggal DESC, t_kas_bon.kas_bon_id DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("t_kas_bon.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kode)){
			$query->andWhere("t_kas_bon.kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->penerima)){
			$query->andWhere("t_kas_bon.penerima ILIKE '%".$this->penerima."%'");
		}
		if(!empty($this->deskripsi)){
			$query->andWhere("t_kas_bon.deskripsi ILIKE '%".$this->deskripsi."%'");
		}
		return $query;
	}
	
	public function searchLaporanDt($tipe) {
		$searchLaporan = $this->searchLaporan($tipe);
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
		if(!empty($searchLaporan->groupBy)){
			$param['column'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}	
		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
		}
		if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}
		if(!empty($searchLaporan->orderBy)){
			foreach($searchLaporan->orderBy as $i_order => $order){
				$param['order'][] = $i_order." ".(($order == 3)?"DESC":"ASC");
			}
		}
		if(!empty($searchLaporan->join)){
			foreach($searchLaporan->join as $join){
				$param['join'][] = $join[0].' '.$join[1]." ON ".$join[2];
			}
		}
		$param['where'] = [];
		if($tipe=="gantung"){
			array_push($param['where'],"t_kas_bon.kas_kecil_id IS NULL AND t_kas_bon.tipe = 'KK' AND (status_bon != 'PAID' OR status_bon IS NULL)");
		}else if($tipe=="realisasi"){
			array_push($param['where'],"(t_kas_bon.kas_kecil_id IS NOT NULL OR status_bon = 'PAID' AND t_kas_bon.tipe = 'KK')");
		}
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"t_kas_bon.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kode)){
			array_push($param['where'],"t_kas_bon.kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->penerima)){
			array_push($param['where'],"t_kas_bon.penerima ILIKE '%".$this->penerima."%'");
		}
		if(!empty($this->deskripsi)){
			array_push($param['where'],"t_kas_bon.deskripsi ILIKE '%".$this->deskripsi."%'");
		}
		return $param;
	}
}
