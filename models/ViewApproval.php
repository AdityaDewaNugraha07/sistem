<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_approval".
 *
 * @property integer $approval_id
 * @property integer $assigned_to
 * @property string $assigned_nama
 * @property integer $approved_by
 * @property string $approved_by_nama
 * @property string $reff_no
 * @property string $tanggal_berkas
 * @property string $tanggal_approve
 * @property integer $level
 * @property string $status
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $keterangan
 * @property integer $parameter1
 */
class ViewApproval extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal,$tgl_akhir,$contain;
    public static function tableName()
    {
        return 'view_approval';
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
            [['approval_id', 'assigned_to', 'approved_by', 'level', 'created_by', 'updated_by'], 'integer'],
            [['tanggal_berkas', 'tanggal_approve', 'created_at', 'updated_at'], 'safe'],
            [['active'], 'boolean'],
            [['keterangan','parameter1'], 'string'],
            [['assigned_nama', 'approved_by_nama'], 'string', 'max' => 100],
            [['reff_no', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'approval_id' => Yii::t('app', 'Approval'),
                'assigned_to' => Yii::t('app', 'Assigned To'),
                'assigned_nama' => Yii::t('app', 'Assigned Nama'),
                'approved_by' => Yii::t('app', 'Approved By'),
                'approved_by_nama' => Yii::t('app', 'Approved By Nama'),
                'reff_no' => Yii::t('app', 'Reff No'),
                'tanggal_berkas' => Yii::t('app', 'Tanggal Berkas'),
                'tanggal_approve' => Yii::t('app', 'Tanggal Approve'),
                'level' => Yii::t('app', 'Level'),
                'status' => Yii::t('app', 'Status'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'parameter1' => Yii::t('app', 'Keterangan'),
        ];
    }
    
    public function searchSPBConfirmed() {
		$query = self::find();
        $query->select(['approval_id','reff_no','spb_nomor','tanggal_berkas', 'assigned_nama', 'approved_by_nama', 
                        '(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT m_brg_bhp.bhp_nm, t_spb_detail.spbd_jml, m_brg_bhp.bhp_satuan FROM t_spb_detail 
                            JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_spb_detail.bhp_id WHERE t_spb_detail.spb_id = t_spb.spb_id ) t) AS detail',
                        'view_approval.status', 'view_approval.created_at', 'view_approval.created_at']);
        $query->join('LEFT JOIN', 't_spb','view_approval.reff_no = t_spb.spb_kode');
        $query->andWhere("reff_no ILIKE '%SPB%' AND view_approval.status != 'Not Confirmed'");
        if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
            $query->andWhere("assigned_to = ".Yii::$app->user->identity->pegawai_id." ");
        }
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal_berkas BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->contain)){
			$query->andWhere("((SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT m_brg_bhp.bhp_nm, t_spb_detail.spbd_jml, m_brg_bhp.bhp_satuan FROM t_spb_detail 
                            JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_spb_detail.bhp_id WHERE t_spb_detail.spb_id = t_spb.spb_id ) t)::text) ILIKE '%".$this->contain."%' ");
		}
		return $query;
	}
	
	public function searchSPBConfirmedDt() {
		$searchLaporan = $this->searchSPBConfirmed();
		$param['table']= self::tableName();
		$param['pk']= "approval_id";
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
		$param['where'] = ["reff_no ILIKE '%SPB%' AND view_approval.status != 'Not Confirmed'"];
        if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
            array_push($param['where'],"assigned_to = ".Yii::$app->user->identity->pegawai_id." ");
        }
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal_berkas BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->contain)){
			array_push($param['where'],"((SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT m_brg_bhp.bhp_nm, t_spb_detail.spbd_jml, m_brg_bhp.bhp_satuan FROM t_spb_detail 
                                        JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_spb_detail.bhp_id WHERE t_spb_detail.spb_id = t_spb.spb_id ) t)::text) ILIKE '%".$this->contain."%'");
		}
		return $param;
	}
}
