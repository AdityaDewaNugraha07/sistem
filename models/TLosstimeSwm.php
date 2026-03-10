<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_losstime_swm".
 *
 * @property integer $losstime_swm_id
 * @property string $kode
 * @property integer $spk_sawmill_id
 * @property string $tanggal
 * @property string $line_sawmill
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property TSpkSawmill $spkSawmill
 */
class TLosstimeSwm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal, $tgl_akhir, $kategori_losstime;
    public static function tableName()
    {
        return 't_losstime_swm';
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
            [['kode', 'spk_sawmill_id', 'tanggal', 'line_sawmill', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['spk_sawmill_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['kode'], 'string', 'max' => 50],
            [['line_sawmill'], 'string', 'max' => 2],
            [['spk_sawmill_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpkSawmill::className(), 'targetAttribute' => ['spk_sawmill_id' => 'spk_sawmill_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'losstime_swm_id' => 'Losstime Swm',
                'kode' => 'Kode',
                'spk_sawmill_id' => 'Spk Sawmill',
                'tanggal' => 'Tanggal',
                'line_sawmill' => 'Line Sawmill',
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
		$query->select('t_losstime_swm.losstime_swm_id, 
                        t_losstime_swm.kode, 
                        t_spk_sawmill.kode as kode_spk, 
                        t_losstime_swm.tanggal, 
                        t_losstime_swm.line_sawmill, 
                        nomor_bandsaw, 
                        kategori_losstime, 
                        losstime_start, 
                        losstime_end, 
                        t_losstime_swm_detail.keterangan
                    ');
        $query->join('JOIN', 't_losstime_swm_detail', "t_losstime_swm_detail.losstime_swm_id = t_losstime_swm.losstime_swm_id");
        $query->join('JOIN', 't_spk_sawmill', "t_spk_sawmill.spk_sawmill_id = t_losstime_swm.spk_sawmill_id");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):"" );
        $query->andWhere("t_losstime_swm.cancel_transaksi_id IS NULL");
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
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
        if(!empty($this->kategori_losstime)){
            $query->andWhere("kategori_losstime = '".$this->kategori_losstime."'");
        }
		return $query;
	}

    public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= 't_losstime_swm.losstime_swm_id';//self::primaryKey()[0];
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
		$param['where'] = ["t_losstime_swm.cancel_transaksi_id IS NULL"];
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
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
        if(!empty($this->kategori_losstime)){
            array_push($param['where'], "kategori_losstime = '".$this->kategori_losstime."'");
        }
		return $param;
	}

    public function searchLaporanRekap() {
		$query = self::find();
		$query->select('t_spk_sawmill.kode as kode_spk, 
                        nomor_bandsaw, 
                        kategori_losstime, 
                        SUM(EXTRACT(EPOCH FROM (losstime_end - losstime_start)) / 60) AS losstime
                    ');
        $query->join('JOIN', 't_losstime_swm_detail', "t_losstime_swm_detail.losstime_swm_id = t_losstime_swm.losstime_swm_id");
        $query->join('JOIN', 't_spk_sawmill', "t_spk_sawmill.spk_sawmill_id = t_losstime_swm.spk_sawmill_id");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):"" );
        $query->groupBy("t_spk_sawmill.kode, nomor_bandsaw, kategori_losstime");
        $query->andWhere("t_losstime_swm.cancel_transaksi_id IS NULL");
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
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
        if(!empty($this->kategori_losstime)){
            $query->andWhere("kategori_losstime = '".$this->kategori_losstime."'");
        }
		return $query;
	}

    public function searchLaporanRekapDt() {
		$searchLaporan = $this->searchLaporanRekap();
		$param['table']= self::tableName();
		$param['pk']= 't_losstime_swm.losstime_swm_id';//self::primaryKey()[0];
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
		$param['where'] = ["t_losstime_swm.cancel_transaksi_id IS NULL"];
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
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
        if(!empty($this->kategori_losstime)){
            array_push($param['where'], "kategori_losstime = '".$this->kategori_losstime."'");
        }
		return $param;
	}
} 