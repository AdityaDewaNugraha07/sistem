<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_log_bayar_dp".
 *
 * @property integer $log_bayar_dp_id
 * @property integer $log_kontrak_id
 * @property string $kode
 * @property string $tanggal
 * @property double $total_dp
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status
 * @property integer $voucher_pengeluaran_id
 *
 * @property TLogKontrak $logKontrak
 */ 
class TLogBayarDp extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $tgl_awal,$tgl_akhir,$nomor,$suplier_id,$suplier_nm_company,$biaya_grader_detail_jml,$nomor_kontrak;
    public static function tableName()
    {
        return 't_log_bayar_dp';
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
            [['log_kontrak_id', 'kode', 'tanggal', 'created_at', 'created_by', 'updated_at', 'updated_by','total_dp'], 'required'],
            [['log_kontrak_id', 'created_by', 'updated_by', 'voucher_pengeluaran_id'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
//            [['total_dp'], 'number'],
            [['keterangan'], 'string'],
            [['kode', 'status'], 'string', 'max' => 50],
            [['log_kontrak_id'], 'exist', 'skipOnError' => true, 'targetClass' => TLogKontrak::className(), 'targetAttribute' => ['log_kontrak_id' => 'log_kontrak_id']],
        ]; 
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'log_bayar_dp_id' => Yii::t('app', 'Log Bayar Dp'),
                'log_kontrak_id' => Yii::t('app', 'Nomor Kontrak'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'total_dp' => Yii::t('app', 'Jumlah Dp (Rp)'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
				'status' => Yii::t('app', 'Status'),
				'voucher_pengeluaran_id' => Yii::t('app', 'Voucher Pengeluaran'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogKontrak()
    {
        return $this->hasOne(TLogKontrak::className(), ['log_kontrak_id' => 'log_kontrak_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherPengeluaran()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
    } 
	
	public function searchLaporan(){
		$kontrak = TLogKontrak::tableName();
		$suplier = MSuplier::tableName();
		$query = self::find();
		$query->select('log_bayar_dp_id, '.self::tableName().'.kode, nomor, suplier_nm_company, '.self::tableName().'.tanggal, '.self::tableName().'.keterangan, total_dp, '.self::tableName().'.status');
		$query->join('JOIN', $kontrak,$kontrak.'.log_kontrak_id = '.self::tableName().'.log_kontrak_id');
		$query->join('JOIN', $suplier,$suplier.'.suplier_id = '.$kontrak.'.suplier_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere(self::tableName().".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kode)){
			$query->andWhere(self::tableName().".kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->nomor)){
			$query->andWhere("nomor ILIKE '%".$this->nomor."%'");
		}
		if(!empty($this->suplier_id)){
			$query->andWhere($kontrak.".suplier_id  = ".$this->suplier_id);
		}
		if(!empty($this->status)){
			$query->andWhere(self::tableName().".status = '".$this->status."'");
		}
		return $query;
	}
	
	public function searchLaporanDt(){
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
		$kontrak = TLogKontrak::tableName();
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
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],self::tableName().".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kode)){
			array_push($param['where'],self::tableName().".kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->nomor)){
			array_push($param['where'],"nomor ILIKE '%".$this->nomor."%'");
		}
		if(!empty($this->suplier_id)){
			array_push($param['where'],$kontrak.".suplier_id = ".$this->suplier_id);
		}
		if(!empty($this->status)){
			array_push($param['where'],self::tableName().".status = '".$this->status."'");
		}
		return $param;
	}
}
