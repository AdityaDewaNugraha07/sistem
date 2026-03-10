<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spb".
 *
 * @property integer $spb_id
 * @property integer $departement_id
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
 * @property string $approve_date
 * @property integer $spb_mengetahui
 * @property string $approve_status
 * @property string $reason_ditolak
 * @property string $reason_approval
 *
 * @property TBpb[] $tBpbs
 * @property MDepartement $departement
 * @property MPegawai $spbDiminta
 * @property MPegawai $spbDisetujui
 * @property MPegawai $spbMengetahui
 * @property TSpbDetail[] $tSpbDetails
 */ 
class TSpb extends \app\models\DeltaBaseActiveRecord
{
	const SCENARIO_SPB_BARU = 'scenarioSpbBaru';
    /**
     * @inheritdoc
     */
	public $tgl_awal,$tgl_akhir;
    public static function tableName()
    {
        return 't_spb';
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
            [['departement_id', 'spb_jenis', 'spb_tipe', 'spb_kode', 'spb_diminta', 'spb_disetujui', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['departement_id', 'spb_diminta', 'spb_disetujui', 'created_by', 'updated_by', 'spb_mengetahui'], 'integer'],
            [['spb_tanggal', 'created_at', 'updated_at', 'approve_date'], 'safe'],
            [['spb_keterangan', 'reason_ditolak', 'reason_approval'], 'string'],
            [['spb_jenis', 'spb_tipe', 'approve_status'], 'string', 'max' => 50],
            [['spb_kode', 'spb_nomor', 'spb_status'], 'string', 'max' => 30],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
            [['spb_diminta'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['spb_diminta' => 'pegawai_id']],
            [['spb_disetujui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['spb_disetujui' => 'pegawai_id']],
			[['spb_mengetahui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['spb_mengetahui' => 'pegawai_id']],
			[['spb_mengetahui'], 'required', 'on' => self::SCENARIO_SPB_BARU],
        ]; 
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spb_id' => Yii::t('app', 'Spb'),
                'departement_id' => Yii::t('app', 'Dept. Pemesan'),
                'spb_jenis' => Yii::t('app', 'Jenis Permintaan'),
                'spb_tipe' => Yii::t('app', 'Prioritas'),
                'spb_kode' => Yii::t('app', 'Kode SPB'),
                'spb_nomor' => Yii::t('app', 'No. SPB'),
                'spb_tanggal' => Yii::t('app', 'Tanggal Pesan'),
                'spb_diminta' => Yii::t('app', 'Diminta Oleh'),
                'spb_disetujui' => Yii::t('app', 'Menyetujui'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'spb_keterangan' => Yii::t('app', 'Catatan Khusus'),
                'spb_status' => Yii::t('app', 'Status'),
				'approve_date' => Yii::t('app', 'Approve Date'),
                'spb_mengetahui' => Yii::t('app', 'Mengetahui'),
				'approve_status' => Yii::t('app', 'Approve Status'),
                'reason_ditolak' => Yii::t('app', 'Alasan Ditolak')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartement()
    {
        return $this->hasOne(MDepartement::className(), ['departement_id' => 'departement_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpbDiminta()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'spb_diminta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpbDisetujui()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'spb_disetujui']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getSpbMengetahui()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'spb_mengetahui']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpbDetails()
    {
        return $this->hasMany(TSpbDetail::className(), ['spb_id' => 'spb_id']);
    } 
    
    public static function getOptionList()
    {
        $res = self::find()->where("(spb_status = 'BELUM DIPROSES' OR spb_status = 'SEDANG DIPROSES')")->orderBy('spb_tanggal DESC')->all();
        $return = \yii\helpers\ArrayHelper::map($res, 'spb_id', 'spb_kode');
        return $return;
    }
    public static function getOptionListDepartement()
    {
		$res = self::find()
			->where("((t_spb.spb_status = 'BELUM DIPROSES' OR t_spb.spb_status = 'SEDANG DIPROSES') OR spbd_id NOT IN ( SELECT spbd_id FROM map_spb_detail_spp_detail ))
						AND t_spb.approve_status = '". TApproval::STATUS_APPROVED."' ")
			->select(['departement_id'])
			->groupBy(['departement_id'])
			->join("JOIN","t_spb_detail", "t_spb.spb_id=t_spb_detail.spb_id")
			->all();
        $return = [];
        if(count($res)>0){
            foreach($res as $i => $val){
                $return[$val['departement_id']] = $val->departement->departement_nama;
            }
        }
        return $return;
    }
	public static function getOptionListSpbMutasi()
    {
		$return = [];
        $res = self::find()->where("mutation_req = TRUE AND spb_status != 'TERPENUHI' AND approve_status = '". TApproval::STATUS_APPROVED."' ")->orderBy('spb_tanggal DESC')->all();
		foreach($res as $i => $det){
			$return[$det->spb_id] = $det->spb_kode.' - '.$det->departement->departement_nama;
		}
        return $return;
    }
	
	public function searchLaporan() {
		$dept = MDepartement::tableName();
		$peg = MPegawai::tableName();
		$query = self::find();
		$query->select(self::tableName().'.spb_id,  spb_kode, spb_nomor, spb_tanggal, spb_tipe, departement_nama, pegawai_nama, spb_status, approve_status');
		$query->join('JOIN', $dept,$dept.'.departement_id = '.self::tableName().'.departement_id');
		$query->join('JOIN', $peg,$peg.'.pegawai_id = '.self::tableName().'.spb_diminta');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.spb_id DESC' );
//		if(Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER){
//			$query->andWhere("approve_status = '".\app\models\TApproval::STATUS_APPROVED."'");
//		}
                if(!empty($this->approve_status)) {
			$query->andWhere("approve_status = '".$this->approve_status."'");
		}
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("spb_tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->spb_kode)){
			$query->andWhere("spb_kode ILIKE '%".$this->spb_kode."%'");
		}
		if(!empty($this->spb_status)){
			$query->andWhere("spb_status ILIKE '%".$this->spb_status."%'");
		}
		if(!empty($this->approve_status)){
			$query->andWhere("approve_status ILIKE '%".$this->approve_status."%'");
		}
		if(!empty($this->departement_id)){
			$query->andWhere($dept.".departement_id = ".$this->departement_id);
		}
		if(!empty($this->spb_diminta)){
			$query->andWhere("spb_diminta = ".$this->spb_diminta);
		}
		return $query;
	}
	
	public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$dept = MDepartement::tableName();
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
//		if(Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER){
//			array_push($param['where'],"approve_status = '".\app\models\TApproval::STATUS_APPROVED."'");
//		}
                if(!empty($this->approve_status)) {
                        array_push($param['where'],"approve_status = '".$this->approve_status."'");
		}
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"spb_tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->spb_kode)){
			array_push($param['where'],"spb_kode ILIKE '%".$this->spb_kode."%'");
		}
		if(!empty($this->spb_status)){
			array_push($param['where'],"spb_status ILIKE '%".$this->spb_status."%'");
		}
		if(!empty($this->approve_status)){
			array_push($param['where'],"approve_status ILIKE '%".$this->approve_status."%'");
		}
		if(!empty($this->departement_id)){
			array_push($param['where'],$dept.".departement_id = '".$this->departement_id."'");
		}
		if(!empty($this->spb_diminta)){
			array_push($param['where'],"spb_diminta = '".$this->spb_diminta."'");
		}
		return $param;
	}
}
