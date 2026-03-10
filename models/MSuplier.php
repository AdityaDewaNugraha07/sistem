<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_suplier".
 *
 * @property integer $suplier_id
 * @property string $suplier_nm
 * @property string $suplier_nm_company
 * @property string $suplier_almt
 * @property string $suplier_phone
 * @property string $suplier_norekening
 * @property string $suplier_bank
 * @property string $suplier_an_rekening
 * @property string $suplier_email
 * @property string $suplier_npwp
 * @property string $suplier_ket
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $type
 * @property string $suplier_phone2
 * @property string $suplier_up
 * @property string $fax
 *
 * @property TDpBhp[] $tDpBhps
 * @property TLogKontrak[] $tLogKontraks
 * @property TPosengon[] $tPosengons
 * @property TSplDetail[] $tSplDetails
 * @property TSpo[] $tSpos
 * @property TSppDetail[] $tSppDetails
 * @property TTerimaBhp[] $tTerimaBhps
 * @property TTerimaBhpDetail[] $tTerimaBhpDetails
 * @property TVoucherPengeluaran[] $tVoucherPengeluarans
 */ 
class MSuplier extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $tgl_awal,$tgl_akhir, $periode;
    public static function tableName()
    {
        return 'm_suplier';
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
            [['suplier_nm', 'suplier_almt', 'created_at', 'created_by', 'updated_at', 'updated_by', 'type'], 'required'],
            [['suplier_ket', 'suplier_nik'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['suplier_nm', 'suplier_nm_company', 'suplier_norekening', 'suplier_bank', 'suplier_an_rekening', 'suplier_email', 'suplier_npwp'], 'string', 'max' => 50],
            [['suplier_almt'], 'string', 'max' => 150],
            [['suplier_phone', 'suplier_phone2', 'fax'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 20],
            [['suplier_up'], 'string', 'max' => 200],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'suplier_id' => Yii::t('app', 'Suplier'),
            'suplier_nm' => Yii::t('app', 'Nama'),
            'suplier_nm_company' => Yii::t('app', 'Perusahaan'),
            'suplier_almt' => Yii::t('app', 'Alamat'),
            'suplier_phone' => Yii::t('app', 'Phone'),
            'suplier_norekening' => Yii::t('app', 'No. Rekening'),
            'suplier_bank' => Yii::t('app', 'Bank'),
            'suplier_an_rekening' => Yii::t('app', 'An. Rekening'),
            'suplier_email' => Yii::t('app', 'Email'),
            'suplier_npwp' => Yii::t('app', 'No. NPWP'),
            'suplier_ket' => Yii::t('app', 'Keterangan'),
            'active' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
			'type' => Yii::t('app', 'Tipe Supplier'),
			'fax' => Yii::t('app', 'Fax'),
			'suplier_nik' => Yii::t('app', 'NIK'),
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getTDpBhps()
    {
        return $this->hasMany(TDpBhp::className(), ['suplier_id' => 'suplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTLogKontraks()
    {
        return $this->hasMany(TLogKontrak::className(), ['suplier_id' => 'suplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTPosengons()
    {
        return $this->hasMany(TPosengon::className(), ['suplier_id' => 'suplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSplDetails()
    {
        return $this->hasMany(TSplDetail::className(), ['suplier_id' => 'suplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpos()
    {
        return $this->hasMany(TSpo::className(), ['suplier_id' => 'suplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSppDetails()
    {
        return $this->hasMany(TSppDetail::className(), ['suplier_id' => 'suplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaBhps()
    {
        return $this->hasMany(TTerimaBhp::className(), ['suplier_id' => 'suplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaBhpDetails()
    {
        return $this->hasMany(TTerimaBhpDetail::className(), ['suplier_id' => 'suplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTVoucherPengeluarans()
    {
        return $this->hasMany(TVoucherPengeluaran::className(), ['suplier_id' => 'suplier_id']);
    } 
	
	public static function getOptionList($tipe=null)
    {
		$ret = array();
		if($tipe){
            //$res = self::find()->where(['active'=>true,'type'=>$tipe])->orderBy('suplier_nm ASC')->all();
            if ($tipe == "LS") {
//                $res = self::find()->where(['active'=>true,'type'=>$tipe])->orWhere(['and',['type'=>'LJ']])->orderBy('suplier_nm ASC')->all();  peubahan tanggal 2022-10-20
                $res = self::find()->where(['in', 'type', ['LS', 'LJ']])->andWhere(['active' => true])->orderBy('suplier_nm')->all();
            } else {
                $res = self::find()->where(['active'=>true,'type'=>$tipe])->orderBy('suplier_nm ASC')->all();
            }
		}else{
			$res = self::find()->where(['active'=>true])->orderBy('suplier_nm ASC')->all();
		}
		if(count($res)>0){
			foreach($res as $i => $data){
				$text = ( !empty($data->suplier_nm)?$data->suplier_nm:""). (!empty($data->suplier_nm_company)?', '.$data->suplier_nm_company:'');
				if( strlen($text) > 50 ){
					$text = substr($text, 0,50);
					$text .= $text.'...';
				}
				$ret[$data->suplier_id] = $text;
			}
		}
        return $ret;
    }
    
    public static function getOptionListSuplier($id=null)
    {
		$ret = array();
		if($id){
			$res = self::find()->where(['active'=>true,'suplier_id'=>$id])->orderBy('suplier_nm ASC')->all();
		}else{
			$res = self::find()->where(['active'=>true])->orderBy('suplier_nm ASC')->all();
		}
		if(count($res)>0){
			foreach($res as $i => $data){
				$text = ( !empty($data->suplier_nm)?$data->suplier_nm:""). (!empty($data->suplier_almt)?', '.$data->suplier_almt:'');
				if( strlen($text) > 50 ){
					// $text = substr($text, 0,50);
					// $text .= $text.'...';
					$text = substr($text, 0, 50).'...'; 
				}
				$ret[$data->suplier_id] = $text;
			}
		}
        return $ret;
    }
    
	public static function getOptionListPo($tipe=null)
    {
		$ret = array();
		if($tipe){
			$res = self::find()->where(['active'=>true,'type'=>$tipe])->orderBy('suplier_nm ASC')->all();
		}else{
			$res = self::find()->where(['active'=>true])->orderBy('suplier_nm ASC')->all();
		}
		if(count($res)>0){
			foreach($res as $i => $data){
				$text = ( !empty($data->suplier_nm)?$data->suplier_nm:""). (!empty($data->suplier_almt)?', '.$data->suplier_almt:'');
				if( strlen($text) > 50 ){
					// $text = substr($text, 0,50);
					// $text .= $text.'...';
					$text = substr($text, 0, 50).'...'; 
				}
				$ret[$data->suplier_id] = $text;
			}
		}
        return $ret;
    }
    
    public static function getOptionListPo2($tipe=null)
    {
    		$ret = array();
		if($tipe){
			$res = self::find()->where(['active'=>true]) ->andWhere("type IN($tipe)")->orderBy('suplier_nm ASC')->all();
                    
		}else{
			$res = self::find()->where(['active'=>true])->orderBy('suplier_nm ASC')->all();
		}
		if(count($res)>0){
			foreach($res as $i => $data){
				$text = ( !empty($data->suplier_nm)?$data->suplier_nm:""). (!empty($data->suplier_almt)?', '.$data->suplier_almt:'');
				if( strlen($text) > 50 ){
					// $text = substr($text, 0,50);
					// $text .= $text.'...';
					$text = substr($text, 0, 50).'...'; 
				}
				$ret[$data->suplier_id] = $text;
			}
		}
        return $ret;
    }
    
	public static function getOptionList2()
    {
        $res = self::find()->where(['active'=>true])->orderBy('suplier_nm ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'suplier_id', 'suplier_nm_company');
    }
	public static function getOptionListBHP()
    {      
		$res = self::find()->where(['active'=>true,'type'=>'BHP'])->andWhere("suplier_nm NOT ILIKE '%SPL%'")->orderBy('suplier_nm ASC')->all();
		$ret = [];
		if(count($res) > 0){
			foreach($res as $i => $data){
				$text = $data->suplier_nm. (!empty($data->suplier_almt) ? ', '.$data->suplier_almt : '');
				if(strlen($text) > 50){
					$text = substr($text, 0, 50).'...'; // Mengganti $text .= $text.'...';
				}
				$ret[$data->suplier_id] = $text;
			}
		}
        return $ret;
    }
    
    public static function getOptionListBB()
    {
        $res = self::find()->where(['active'=>true])->andWhere("type IN ('LS','LA','LJ')")->andWhere("exists (select penerima_reff_id from view_tagihan_bahan_baku where view_tagihan_bahan_baku.penerima_reff_id=m_suplier.suplier_id  group by 1 having sum(totaltagihan - dibayar)>0)")->orderBy('suplier_nm ASC')->all();
		if(count($res)>0){
			foreach($res as $i => $data){
				$text = $data->suplier_nm. (!empty($data->suplier_almt)?', Alamat : '.$data->suplier_almt:'');
				if( strlen($text) > 50 ){
					// $text = substr($text, 0,50);
					// $text .= $text.'...';
					$text = substr($text, 0, 50).'...'; 
				}
				$ret[$data->suplier_id] = $text;
			}
		}
        return $ret;
    }
	public static function getOptionListTypeSuplier()
    {
		if(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER){
			$paramAndWhere =" and type IN('BHP','LA','LS','LJ')";
		}elseif( (Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_PCH)){
			$paramAndWhere =" and type IN('BHP','LS','LJ')";
		}elseif( (Yii::$app->user->identity->pegawai->departement_id== \app\components\Params::DEPARTEMENT_ID_PURCH_LOG)){
			$paramAndWhere =" and type IN('LA')";
		}else{
			$paramAndWhere =" and type IN('BHP')";
		}
		$res = Yii::$app->db->createCommand("select 
													type as typejenis,
													case
														when type = 'BHP' then 'Bahan Pembantu'
														when type = 'LA' then 'Log Alam'
														when type = 'LS' then 'Log Sengon'
														when type = 'LJ' then 'Log Jabon'
													end as tipe_suplier 
											from m_suplier 
											where active='t' $paramAndWhere group by 1 order by 1 asc")->queryAll();
		$ret = [];
		foreach($res as $i => $asd){
			$ret[$asd['typejenis']] = $asd['tipe_suplier'];
		}
        return $ret;
    }
	public static function getOptionListType()
    {
		$res = Yii::$app->db->createCommand("select 
													type as typejenis,
													case
														when type = 'BHP' then 'Bahan Pembantu'
														when type = 'LA' then 'Log Alam'
														when type = 'LS' then 'Log Sengon'
														when type = 'LJ' then 'Log Jabon'
													end as tipe_suplier  
											from m_suplier where active='t' and type <>'LA' group by 1 order by 1 asc")->queryAll();
		$ret = [];
		foreach($res as $i => $asd){
			$ret[$asd['typejenis']] = $asd['tipe_suplier'];
		}
        return $ret;
    }
	public function searchLaporan() {
		$query = self::find();
		$query->select('suplier_id, suplier_nm, suplier_nm_company, suplier_almt, type, active, suplier_ket, suplier_phone, created_at ');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("created_at::date BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->type)){
			$query->andWhere("type ILIKE '%".$this->type."%'");
		}
		if(!empty($this->suplier_nm)){
			$query->andWhere("suplier_nm ILIKE '%".$this->suplier_nm."%'");
		}
		if(!empty($this->suplier_nm_company)){
			$query->andWhere("suplier_nm_company ILIKE '%".$this->suplier_nm_company."%'");
		}
		if(!empty($this->suplier_almt)){
			$query->andWhere("suplier_almt ILIKE '%".$this->suplier_almt."%'");
		}
		if(!empty($this->suplier_phone)){
			$query->andWhere("suplier_phone ILIKE '%".$this->suplier_phone."%'");
		}
		if(!empty($this->suplier_ket)){
			$query->andWhere("suplier_ket ILIKE '%".$this->suplier_ket."%'");
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
		$param['where'] = [];
		
		// if(\Yii::$app->controller->id == 'supplierlog'){
        //     if(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER){
        //         array_push($param['where'],"type IN('BHP','LA','LS','LJ')");
        //     }elseif( (Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_PCH) ){
        //         array_push($param['where'],"type IN('BHP','LS','LJ')");
        //     }elseif( (Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_PURCH_LOG) ){
        //         array_push($param['where'],"type IN('LA')");
        //     }
		// }else{
		// 	array_push($param['where'],"type IN('BHP')");
		// }
		if(!empty($this->suplier_nm)){
			array_push($param['where'],"suplier_nm ILIKE '%".$this->suplier_nm."%'");
		}
		if(!empty($this->suplier_nm_company)){
			array_push($param['where'],"suplier_nm_company ILIKE '%".$this->suplier_nm_company."%'");
		}
		if(!empty($this->type)){
			array_push($param['where'],"type ILIKE '%".$this->type."%'");
		}
		if(!empty($this->suplier_almt)){
			array_push($param['where'],"suplier_almt ILIKE '%".$this->suplier_almt."%'");
		}
		if(!empty($this->suplier_phone)){
			array_push($param['where'],"suplier_phone ILIKE '%".$this->suplier_phone."%'");
		}
		if(!empty($this->suplier_ket)){
			array_push($param['where'],"suplier_ket ILIKE '%".$this->suplier_ket."%'");
		}
		return $param;
	}

	public function searchLaporanDtSuplier() {
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
		$param['where'] = [];
		
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"created_at::date BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->suplier_nm)){
			array_push($param['where'],"suplier_nm ILIKE '%".$this->suplier_nm."%'");
		}
		if(!empty($this->suplier_nm_company)){
			array_push($param['where'],"suplier_nm_company ILIKE '%".$this->suplier_nm_company."%'");
		}
		if(!empty($this->suplier_almt)){
			array_push($param['where'],"suplier_almt ILIKE '%".$this->suplier_almt."%'");
		}
		if(!empty($this->suplier_phone)){
			array_push($param['where'],"suplier_phone ILIKE '%".$this->suplier_phone."%'");
		}
		if(!empty($this->suplier_ket)){
			array_push($param['where'],"suplier_ket ILIKE '%".$this->suplier_ket."%'");
		}
		if(!empty($this->type)){
			array_push($param['where'],"type = '".$this->type."'");
		}
		return $param;
	}
	
}	
