<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_bandsaw".
 *
 * @property integer $bandsaw_id
 * @property string $kode
 * @property integer $spk_sawmill_id
 * @property string $tanggal
 * @property string $line_sawmill
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
 *
 * @property TSpkSawmill $spkSawmill
 */
class TBandsaw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kode_spk, $nomor_bandsaw, $tgl_awal, $tgl_akhir, $kayu_id;
    public static function tableName()
    {
        return 't_bandsaw';
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
            [['kode', 'spk_sawmill_id', 'tanggal', 'line_sawmill', 'prepared_by','created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['spk_sawmill_id', 'prepared_by', 'approved_by1', 'approved_by2', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['approve_reason', 'reject_reason'], 'string'],
            [['kode'], 'string', 'max' => 50],
            [['line_sawmill'], 'string', 'max' => 2],
            [['approval_status'], 'string', 'max' => 15],
            [['spk_sawmill_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpkSawmill::className(), 'targetAttribute' => ['spk_sawmill_id' => 'spk_sawmill_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'bandsaw_id' => 'Bandsaw',
                'kode' => 'Kode',
                'spk_sawmill_id' => 'Spk Sawmill',
                'tanggal' => 'Tanggal',
                'line_sawmill' => 'Line Sawmill',
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpkSawmill()
    {
        return $this->hasOne(TSpkSawmill::className(), ['spk_sawmill_id' => 'spk_sawmill_id']);
    }

    public function searchLaporan() {
		$query = self::find();
		$query->select('t_bandsaw.bandsaw_id, 
                        t_bandsaw.kode, 
                        t_spk_sawmill.kode as kode_spk, 
                        tanggal, 
                        t_bandsaw.line_sawmill, 
                        kayu_nama, 
                        nomor_bandsaw,
                        produk_t, 
                        produk_l, 
                        produk_p, 
                        qty
                    ');
        $query->join('JOIN', 't_bandsaw_detail', "t_bandsaw_detail.bandsaw_id = t_bandsaw.bandsaw_id");
        $query->join('JOIN', 't_spk_sawmill', "t_spk_sawmill.spk_sawmill_id = t_bandsaw.spk_sawmill_id");
        $query->join('JOIN', 'm_kayu', "m_kayu.kayu_id = t_bandsaw_detail.kayu_id");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):"" );
        $query->andWhere("qty > 0");
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
        if(!empty($this->nomor_bandsaw)) {
			$query->andWhere("nomor_bandsaw = '".$this->nomor_bandsaw."'");
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
                        $subq.="t_bandsaw_detail.kayu_id = '".$k."' ";
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
                $query->andWhere("t_bandsaw_detail.kayu_id = '".$this->kayu_id."'");
            }            
        }
		return $query;
	}

    public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= 't_bandsaw.bandsaw_id';//self::primaryKey()[0];
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
		$param['where'] = ["qty > 0"];
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
        if (!empty($this->nomor_bandsaw)){
			array_push($param['where'],"nomor_bandsaw = '".$this->nomor_bandsaw."'");
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
                        $subq.="t_bandsaw_detail.kayu_id = '".$k."'";
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
                array_push($param['where'],"t_bandsaw_detail.kayu_id = '".$this->kayu_id."'");
            }     
        }
		return $param;
	}

    public function searchLaporanRekap() {
		$query = self::find();
		$query->select('t_spk_sawmill.kode as kode_spk, 
                        kayu_nama, 
                        nomor_bandsaw,
                        produk_t, 
                        produk_l, 
                        produk_p, 
                        sum(qty) as qty
                    ');
        $query->join('JOIN', 't_bandsaw_detail', "t_bandsaw_detail.bandsaw_id = t_bandsaw.bandsaw_id");
        $query->join('JOIN', 't_spk_sawmill', "t_spk_sawmill.spk_sawmill_id = t_bandsaw.spk_sawmill_id");
        $query->join('JOIN', 'm_kayu', "m_kayu.kayu_id = t_bandsaw_detail.kayu_id");
        $query->groupBy('t_spk_sawmill.kode, kayu_nama, nomor_bandsaw, produk_t, produk_l, produk_p');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):
                        "t_spk_sawmill.kode DESC, produk_t ASC, produk_l ASC, produk_p ASC" );
        $query->andWhere("qty > 0");
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
        if(!empty($this->nomor_bandsaw)) {
			$query->andWhere("nomor_bandsaw = '".$this->nomor_bandsaw."'");
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
                        $subq.="t_bandsaw_detail.kayu_id = '".$k."' ";
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
                $query->andWhere("t_bandsaw_detail.kayu_id = '".$this->kayu_id."'");
            }            
        }
		return $query;
	}

    public function searchLaporanRekapDt() {
		$searchLaporan = $this->searchLaporanRekap();
		$param['table']= self::tableName();
		$param['pk']= 't_bandsaw.bandsaw_id';//self::primaryKey()[0];
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
		$param['where'] = ["qty > 0"];
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
        if (!empty($this->nomor_bandsaw)){
			array_push($param['where'],"nomor_bandsaw = '".$this->nomor_bandsaw."'");
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
                        $subq.="t_bandsaw_detail.kayu_id = '".$k."'";
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
                array_push($param['where'],"t_bandsaw_detail.kayu_id = '".$this->kayu_id."'");
            }     
        }
		return $param;
	}
} 