<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pengajuan_drp".
 *
 * @property integer $pengajuan_drp_id
 * @property string $kode
 * @property string $kategori
 * @property string $tanggal
 * @property integer $cancel_transaksi_id
 * @property string $keterangan
 * @property string $status_approve
 * @property string $reason_approval
 * @property string $reason_rejected
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class TPengajuanDrp extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal,$tgl_akhir, $total_jml;
	public $approver_1, $approver_2, $approver_3;
    public static function tableName()
    {
        return 't_pengajuan_drp';
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
            [['kode', 'tanggal', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['keterangan', 'reason_approval', 'reason_rejected'], 'string'],
            [['kode'], 'string', 'max' => 50],
            [['status_approve'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengajuan_drp_id' => 'Pengajuan Drp',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal Rencana Pembayaran',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'keterangan' => 'Keterangan',
                'status_approve' => 'Status Approve',
                'reason_approval' => 'Reason Approval',
                'reason_rejected' => 'Reason Rejected',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    public function searchLaporan() {
		$query = self::find();
		$query->select(self::tableName().".pengajuan_drp_id,
						".self::tableName().".kode, 
						".self::tableName().".tanggal, 
						t_pengajuan_drp.keterangan,					
						CASE WHEN status_approve = 'APPROVED' THEN 
								(SELECT SUM(vp2.total_nominal)
									FROM t_voucher_pengeluaran vp2
									JOIN t_pengajuan_drp_detail pdd2 ON pdd2.voucher_pengeluaran_id = vp2.voucher_pengeluaran_id
									WHERE pdd2.pengajuan_drp_id = t_pengajuan_drp.pengajuan_drp_id
									AND pdd2.status_pengajuan = 'Disetujui')
							ELSE
								(SELECT SUM(vp2.total_nominal) FROM t_voucher_pengeluaran vp2
									JOIN t_pengajuan_drp_detail pdd2 ON pdd2.voucher_pengeluaran_id = vp2.voucher_pengeluaran_id
									WHERE pdd2.pengajuan_drp_id = t_pengajuan_drp.pengajuan_drp_id)
						END as total_jml,	
						".self::tableName().".cancel_transaksi_id,			
						".self::tableName().".status_approve,
						");
        $query->join('LEFT JOIN', 't_pengajuan_drp_detail','t_pengajuan_drp_detail.pengajuan_drp_id = '.self::tableName().'.pengajuan_drp_id');
		$query->join('LEFT JOIN', 't_voucher_pengeluaran','t_voucher_pengeluaran.voucher_pengeluaran_id = t_pengajuan_drp_detail.voucher_pengeluaran_id');
        // $query->join('LEFT JOIN', 't_open_voucher','t_open_voucher.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id');
        $query->groupBy('t_pengajuan_drp.pengajuan_drp_id'); //, t_open_voucher.tipe
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC, t_pengajuan_drp.pengajuan_drp_id ASC' );
        $query->where(self::tableName().".status_approve in ('APPROVED', 'Not Confirmed') AND ".self::tableName().".cancel_transaksi_id IS NULL");
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere(self::tableName().".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->status_approve)){
			$query->andWhere(self::tableName().".status_approve = '".$this->status_approve."'");
		}
		return $query;
	}

    public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
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
		$param['where'] = [self::tableName().".status_approve in ('APPROVED', 'Not Confirmed') AND ".self::tableName().".cancel_transaksi_id IS NULL"];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],self::tableName().".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->status_approve)){
			array_push($param['where'],self::tableName().".status_approve = '".$this->status_approve."'");
		}
		return $param;
	}

	public static function getOptionList()
    {
        $res = self::find()->orderBy('pengajuan_drp_id DESC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'kode', 'kode');
    }
} 