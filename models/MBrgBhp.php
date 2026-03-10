<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_brg_bhp".
 *
 * @property integer $bhp_id
 * @property string $bhp_kode
 * @property string $bhp_group
 * @property string $bhp_nm
 * @property string $bhp_grade
 * @property string $bhp_satuan
 * @property string $bhp_gbr
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property double $current_stock
 * @property string $bhp_kelompok
 * @property double $bhp_harga
 * @property double $bhp_ppn
 * @property double $bhp_harga_pokok
 * @property boolean $bhp_include_ppn
 * @property string $acct_no
 * @property double $pbbkb
 * @property string $other
 *
 * @property TAdjustmentstock[] $tAdjustmentstocks
 * @property TBpbDetail[] $tBpbDetails
 * @property TMutasiGudanglogistikDetail[] $tMutasiGudanglogistikDetails
 * @property TSpbDetail[] $tSpbDetails
 * @property TSplDetail[] $tSplDetails
 * @property TSpoDetail[] $tSpoDetails
 * @property TSppDetail[] $tSppDetails
 * @property TTerimaBhpDetail[] $tTerimaBhpDetails
 */ 
class MBrgBhp extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file1;
    public static function tableName()
    {
        return 'm_brg_bhp';
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
            [['bhp_kode', 'bhp_group', 'bhp_nm', 'bhp_satuan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['active', 'bhp_include_ppn'], 'boolean'],
            [['created_at', 'updated_at','current_stock'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['bhp_harga', 'bhp_ppn', 'bhp_harga_pokok', 'pbbkb'], 'number'],
            [['bhp_kode'], 'string', 'max' => 100],
            [['bhp_nm'], 'string', 'max' => 200],
            [['bhp_group', 'bhp_grade', 'bhp_satuan', 'bhp_gbr', 'bhp_kelompok'], 'string', 'max' => 50],
            [['acct_no'], 'string', 'max' => 10],
            [['bhp_kode'], 'unique'],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'bhp_id' => Yii::t('app', 'Bhp'),
			'bhp_kode' => Yii::t('app', 'Kode'),
			'bhp_group' => Yii::t('app', 'Kelompok'),
			'bhp_nm' => Yii::t('app', 'Nama'),
			'bhp_grade' => Yii::t('app', 'Grade'),
			'bhp_satuan' => Yii::t('app', 'Satuan'),
			'bhp_gbr' => Yii::t('app', 'Gambar'),
			'active' => Yii::t('app', 'Status'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'bhp_harga'=>'Harga',
			'current_stock' => Yii::t('app', 'Current Stock'),
			'bhp_kelompok' => Yii::t('app', 'Kelompok Barang'),
			'bhp_ppn' => Yii::t('app', 'PPN'),
			'bhp_harga_pokok' => Yii::t('app', 'Harga Pokok'),
			'bhp_include_ppn' => Yii::t('app', 'Include PPN'),
			'acct_no' => Yii::t('app', 'Acct No'),
			'pbbkb' => Yii::t('app', 'Pbbkb'),
			'other' => Yii::t('app', 'Other'),
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getTAdjustmentstocks()
    {
        return $this->hasMany(TAdjustmentstock::className(), ['bhp_id' => 'bhp_id']);
    } 
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getTBpbDetails()
    {
        return $this->hasMany(TBpbDetail::className(), ['bhp_id' => 'bhp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpbDetails()
    {
        return $this->hasMany(TSpbDetail::className(), ['bhp_id' => 'bhp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSplDetails()
    {
        return $this->hasMany(TSplDetail::className(), ['bhp_id' => 'bhp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpoDetails()
    {
        return $this->hasMany(TSpoDetail::className(), ['bhp_id' => 'bhp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSppDetails()
    {
        return $this->hasMany(TSppDetail::className(), ['bhp_id' => 'bhp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaBhpDetails()
    {
        return $this->hasMany(TTerimaBhpDetail::className(), ['bhp_id' => 'bhp_id']);
    } 
    
    public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('bhp_nm ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'bhp_id', 'bhp_nm');
    }
	
    public static function getOne($bhp_id)
    {
		return static::findOne($bhp_id);
    }
	
	public static function updateHargaBhp($bhp_id,$harga_pokok,$ppn,$harga,$include_ppn){
        $model = MBrgBhp::findOne($bhp_id);
        $model->bhp_ppn = $ppn;
        $model->bhp_harga_pokok = $harga_pokok;
		$model->bhp_harga = $harga;
		$model->bhp_include_ppn = $include_ppn;
        if($model->validate()){
            if($model->save()){
                return true;
            }else{
                return false;
            }
        }
    }
	
	public function getBhp_nm(){
		$array = explode("/", $this->bhp_nm);
		if(count($array)==2){
			$this->bhp_nm = $array[1];
		}
		if(count($array)==3){
			$this->bhp_nm = $array[1].'/'.$array[2];
		}
		if(count($array)==4){
			$this->bhp_nm = $array[1].'/'.$array[2].'/'.$array[3];
		}
		if(count($array)==5){
			$this->bhp_nm = $array[1].'/'.$array[2].'/'.$array[3].'/'.$array[4];
		}
		if(count($array)==6){
			$this->bhp_nm = $array[1].'/'.$array[2].'/'.$array[3].'/'.$array[4].'/'.$array[5];
		}
		return $this->bhp_nm;
	}
	
	
	public function searchLaporan() {
		$query = self::find();
		$query->select('bhp_id, bhp_kode, bhp_group, bhp_nm, active ');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC' );
		if(!empty($this->bhp_group)){
			$query->andWhere("bhp_group ILIKE '%".$this->bhp_group."%'");
		}
		if(!empty($this->bhp_nm)){
			$query->andWhere("bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->active)){
			if($this->active == "active"){
				$query->andWhere("active IS TRUE");
			}else{
				$query->andWhere("active IS FALSE");
			}
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
		if(!empty($this->bhp_group)){
			array_push($param['where'],"bhp_group ILIKE '%".$this->bhp_group."%'");
		}
		if(!empty($this->bhp_nm)){
			array_push($param['where'],"bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->active)){
			if($this->active == "active"){
				array_push($param['where'],"active IS TRUE");
			}else{
				array_push($param['where'],"active IS FALSE");
			}
		}
		return $param;
	}
}
