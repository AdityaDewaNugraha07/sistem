<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spk_sawmill".
 *
 * @property integer $spk_sawmill_id
 * @property string $kode
 * @property integer $refisi_ke
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string $pemenuhan_po
 * @property string $peruntukan
 * @property string $line_sawmill
 * @property boolean $status_spk
 * @property string $status_spk_close
 * @property string $keterangan
 * @property string $approval_status
 * @property string $approve_reason
 * @property string $reject_reason
 * @property integer $prepared_by
 * @property integer $approved_by1
 * @property integer $approved_by2
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $kayu_id
 * @property string $produk_sawmill
 *
 * @property TBandsaw[] $tBandsaws
 * @property TBrakedown[] $tBrakedowns
 */
class TSpkSawmill extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal, $tgl_akhir;
    public static function tableName()
    {
        return 't_spk_sawmill';
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
            [['kode', 'tanggal_mulai', 'tanggal_selesai', 'peruntukan', 'line_sawmill', 'prepared_by', 'approved_by1', 'approved_by2', 'created_at', 'created_by', 'updated_at', 'updated_by', 'kayu_id', 'produk_sawmill'], 'required'],
            [['refisi_ke', 'prepared_by', 'approved_by1', 'approved_by2', 'cancel_transaksi_id', 'created_by', 'updated_by', 'kayu_id'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai', 'created_at', 'updated_at'], 'safe'],
            [['status_spk'], 'boolean'],
            [['status_spk_close', 'keterangan', 'approve_reason', 'reject_reason'], 'string'],
            [['kode', 'pemenuhan_po'], 'string', 'max' => 50],
            [['peruntukan'], 'string', 'max' => 20],
            [['line_sawmill'], 'string', 'max' => 2],
            [['approval_status'], 'string', 'max' => 15],
            [['produk_sawmill'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spk_sawmill_id' => 'Spk Sawmill',
                'kode' => 'Kode',
                'refisi_ke' => 'Refisi Ke',
                'tanggal_mulai' => 'Tanggal Mulai',
                'tanggal_selesai' => 'Tanggal Selesai',
                'pemenuhan_po' => 'Pemenuhan Po',
                'peruntukan' => 'Peruntukan',
                'line_sawmill' => 'Line Sawmill',
                'status_spk' => 'Status Spk',
                'status_spk_close' => 'Status Spk Close',
                'keterangan' => 'Keterangan',
                'approval_status' => 'Approval Status',
                'approve_reason' => 'Approve Reason',
                'reject_reason' => 'Reject Reason',
                'prepared_by' => 'Prepared By',
                'approved_by1' => 'Approved By1',
                'approved_by2' => 'Approved By2',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'kayu_id' => 'Kayu',
                'produk_sawmill' => 'Produk Sawmill',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTBandsaws()
    {
        return $this->hasMany(TBandsaw::className(), ['spk_sawmill_id' => 'spk_sawmill_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTBrakedowns()
    {
        return $this->hasMany(TBrakedown::className(), ['spk_sawmill_id' => 'spk_sawmill_id']);
    }

    public static function getOptionList()
    {
        $res = self::find()->orderBy('spk_sawmill_id DESC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'kode', 'kode');
    }

    public function searchLaporan() {
		$query = self::find();
		$query->select('t_spk_sawmill.spk_sawmill_id, 
                        kode, 
                        refisi_ke, 
                        tanggal_mulai, 
                        tanggal_selesai, 
                        pemenuhan_po, 
                        peruntukan, 
                        line_sawmill, 
                        status_spk, 
                        m_kayu.kayu_nama, 
                        t_spk_sawmill.produk_sawmill, 
                        produk_t, 
                        produk_l, 
                        produk_p, 
                        kategori_ukuran, 
                        approval_status
                    ');
        $query->join('JOIN', 't_spk_sawmill_detail', "t_spk_sawmill_detail.spk_sawmill_id = t_spk_sawmill.spk_sawmill_id");
        $query->join('JOIN', 'm_kayu', "m_kayu.kayu_id = t_spk_sawmill.kayu_id");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):"" );
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal_mulai BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
        if(!empty($this->peruntukan)) {
			$query->andWhere("peruntukan = '".$this->peruntukan."'");
		}
        if(!empty($this->line_sawmill)) {
			$query->andWhere("line_sawmill = '".$this->line_sawmill."'");
		}
        if(!empty($this->produk_sawmill)) {
			$query->andWhere("t_spk_sawmill.produk_sawmill = '".$this->produk_sawmill."'");
		}
        if(!empty($this->kode)){
            if (is_array($this->kode)) {
                if (isset($this->kode)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->kode as $k) {
                        $subq.="t_spk_sawmill.kode = '".$k."' ";
                        if ($cn < count($this->kode)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("t_spk_sawmill.kode = '".$this->kode."'");
            }            
        }
        if(!empty($this->kayu_id)){
            if (is_array($this->kayu_id)) {
                if (isset($this->kayu_id)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->kayu_id as $k) {
                        $subq.="t_spk_sawmill.kayu_id = '".$k."' ";
                        if ($cn < count($this->kayu_id)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("t_spk_sawmill.kayu_id = '".$this->kayu_id."'");
            }            
        }
		return $query;
	}

    public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= 't_spk_sawmill.spk_sawmill_id';//self::primaryKey()[0];
		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
		}		
		if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}
		if(!empty($searchLaporan->join)){
			foreach($searchLaporan->join as $join){
				$param['join'][] = $join[0].' '.$join[1]." ON ".$join[2];
			}
		}
		$param['where'] = [];
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal_mulai BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
        if (!empty($this->peruntukan)){
			array_push($param['where'],"peruntukan = '".$this->peruntukan."'");
		}
        if (!empty($this->line_sawmill)){
			array_push($param['where'],"line_sawmill = '".$this->line_sawmill."'");
		}
        if (!empty($this->produk_sawmill)){
			array_push($param['where'],"t_spk_sawmill.produk_sawmill = '".$this->produk_sawmill."'");
		}
        if(!empty($this->kode)){
            if (is_array($this->kode)) {
                if (isset($this->kode)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->kode as $k) {
                        $subq.="t_spk_sawmill.kode = '".$k."'";
                        if ($cn < count($this->kode)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"t_spk_sawmill.kode = '".$this->kode."'");
            }     
        }
        if(!empty($this->kayu_id)){
            if (is_array($this->kayu_id)) {
                if (isset($this->kayu_id)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->kayu_id as $k) {
                        $subq.="t_spk_sawmill.kayu_id = '".$k."'";
                        if ($cn < count($this->kayu_id)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"t_spk_sawmill.kayu_id = '".$this->kayu_id."'");
            }     
        }
		return $param;
	}

    public function searchLaporanMonitoring() {
		$query = self::find();
		$query->select('t_spk_sawmill.kode as kode_spk, 
                        kayu_nama, 
                        t_bandsaw_detail.produk_t, 
                        t_bandsaw_detail.produk_l, 
                        t_bandsaw_detail.produk_p, 
                        sum(qty) as qty
                    ');
        $query->join('JOIN', 't_spk_sawmill_detail', 't_spk_sawmill_detail.spk_sawmill_id = t_spk_sawmill.spk_sawmill_id');
        $query->join('LEFT JOIN', 't_bandsaw', 't_bandsaw.spk_sawmill_id = t_spk_sawmill.spk_sawmill_id ');
        $query->join('LEFT JOIN', 't_bandsaw_detail', 't_bandsaw_detail.bandsaw_id = t_bandsaw.bandsaw_id 
                        AND t_bandsaw_detail.produk_t = t_spk_sawmill_detail.produk_t 
	                    AND t_bandsaw_detail.produk_p = t_spk_sawmill_detail.produk_p');
        $query->join('JOIN', 'm_kayu', "m_kayu.kayu_id = t_spk_sawmill.kayu_id");
        $query->groupBy('t_spk_sawmill.kode, kayu_nama, t_bandsaw_detail.produk_t, t_bandsaw_detail.produk_l, t_bandsaw_detail.produk_p');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):
                        "t_spk_sawmill.kode, t_bandsaw_detail.produk_t, t_bandsaw_detail.produk_l, t_bandsaw_detail.produk_p" );
        $query->andWhere("t_spk_sawmill.cancel_transaksi_id is null and status_spk is TRUE");
        if(!empty($this->kode)){
            if (is_array($this->kode)) {
                if (isset($this->kode)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->kode as $k) {
                        $subq.="t_spk_sawmill.kode = '".$k."' ";
                        if ($cn < count($this->kode)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("t_spk_sawmill.kode = '".$this->kode."'");
            }            
        }
		return $query;
	}

    public function searchLaporanMonitoringDt() {
		$searchLaporan = $this->searchLaporanMonitoring();
		$param['table']= self::tableName();
		$param['pk']= 't_spk_sawmill.spk_sawmill_id';//self::primaryKey()[0];
		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
		}		
		if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}
		if(!empty($searchLaporan->join)){
			foreach($searchLaporan->join as $join){
				$param['join'][] = $join[0].' '.$join[1]." ON ".$join[2];
			}
		}
		$param['where'] = ["t_spk_sawmill.cancel_transaksi_id IS NULL and status_spk is TRUE"];
        if(!empty($this->kode)){
            if (is_array($this->kode)) {
                if (isset($this->kode)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->kode as $k) {
                        $subq.="t_spk_sawmill.kode = '".$k."'";
                        if ($cn < count($this->kode)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"t_spk_sawmill.kode = '".$this->kode."'");
            }     
        }
		return $param;
	}
} 