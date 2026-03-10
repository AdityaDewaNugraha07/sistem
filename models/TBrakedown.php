<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_brakedown".
 *
 * @property integer $brakedown_id
 * @property string $kode
 * @property integer $spk_sawmill_id
 * @property integer $kayu_id
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
 * @property MKayu $kayu
 * @property TSpkSawmill $spkSawmill
 */
class TBrakedown extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kode_spk, $tgl_awal, $tgl_akhir;
    public static function tableName()
    {
        return 't_brakedown';
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
            [['kode', 'spk_sawmill_id', 'kayu_id', 'tanggal', 'line_sawmill', 'prepared_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['spk_sawmill_id', 'kayu_id', 'prepared_by', 'approved_by1', 'approved_by2', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['approve_reason', 'reject_reason'], 'string'],
            [['kode'], 'string', 'max' => 50],
            [['line_sawmill'], 'string', 'max' => 2],
            [['approval_status'], 'string', 'max' => 15],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
            [['spk_sawmill_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpkSawmill::className(), 'targetAttribute' => ['spk_sawmill_id' => 'spk_sawmill_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'brakedown_id' => 'Brakedown',
                'kode' => 'Kode',
                'spk_sawmill_id' => 'Spk Sawmill',
                'kayu_id' => 'Kayu',
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
    public function getKayu()
    {
        return $this->hasOne(MKayu::className(), ['kayu_id' => 'kayu_id']);
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
		$query->select('t_brakedown.brakedown_id, 
                        t_brakedown.kode, 
                        kayu_nama, 
                        t_spk_sawmill.kode as kode_spk, 
                        tanggal, 
                        t_brakedown.line_sawmill, 
                        no_barcode_baru, 
                        no_lap_baru, 
                        grading_rule,
                        panjang_baru, 
                        diameter_ujung1_baru, 
                        diameter_ujung2_baru, 
                        diameter_pangkal1_baru, 
                        diameter_pangkal2_baru, 
                        cacat_pjg_baru, 
                        cacat_gb_baru,
                        cacat_gr_baru, 
                        volume_baru
                    ');
        $query->join('JOIN', 't_brakedown_detail', "t_brakedown_detail.brakedown_id = t_brakedown.brakedown_id");
        $query->join('JOIN', 't_spk_sawmill', "t_spk_sawmill.spk_sawmill_id = t_brakedown.spk_sawmill_id");
        $query->join('JOIN', 'm_kayu', "m_kayu.kayu_id = t_brakedown.kayu_id");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):"" );
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
        if(!empty($this->line_sawmill)) {
			$query->andWhere("t_brakedown.line_sawmill = '".$this->line_sawmill."'");
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
                        $subq.="t_brakedown.kayu_id = '".$k."' ";
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
                $query->andWhere("t_brakedown.kayu_id = '".$this->kayu_id."'");
            }            
        }
		return $query;
	}

    public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= 't_brakedown.brakedown_id';//self::primaryKey()[0];
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
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
        if (!empty($this->line_sawmill)){
			array_push($param['where'],"t_brakedown.line_sawmill = '".$this->line_sawmill."'");
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
                        $subq.="t_brakedown.kayu_id = '".$k."'";
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
                array_push($param['where'],"t_brakedown.kayu_id = '".$this->kayu_id."'");
            }     
        }
		return $param;
	}
} 