<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_acct_jurnal".
 *
 * @property integer $jurnal_id
 * @property integer $acct_id
 * @property string $acct_no
 * @property string $kode
 * @property string $reff_no
 * @property string $tanggal
 * @property string $memo
 * @property double $debet
 * @property double $kredit
 * @property string $status_posting
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MAcctRekening $acct
 */ 
class TAcctJurnal extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $acct_nm,$tgl_awal,$tgl_akhir,$bhp_nm;
	public $totalkredit,$totaldebet;
    public static function tableName()
    {
        return 't_acct_jurnal';
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
            [['acct_id', 'acct_no', 'kode', 'reff_no', 'tanggal', 'status_posting', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['acct_id', 'created_by', 'updated_by'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['memo'], 'string'],
            [['debet', 'kredit'], 'number'],
            [['active'], 'boolean'],
            [['acct_no'], 'string', 'max' => 10],
            [['kode'], 'string', 'max' => 20],
            [['reff_no', 'status_posting'], 'string', 'max' => 50],
            [['acct_id'], 'exist', 'skipOnError' => true, 'targetClass' => MAcctRekening::className(), 'targetAttribute' => ['acct_id' => 'acct_id']],
        ]; 
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'jurnal_id' => Yii::t('app', 'Jurnal'),
			'acct_id' => Yii::t('app', 'Acct'),
			'acct_no' => Yii::t('app', 'Acct No'),
			'kode' => Yii::t('app', 'Kode'),
			'reff_no' => Yii::t('app', 'Reff No'),
			'tanggal' => Yii::t('app', 'Tanggal'),
			'memo' => Yii::t('app', 'Memo'),
			'debet' => Yii::t('app', 'Debet'),
			'kredit' => Yii::t('app', 'Kredit'),
			'status_posting' => Yii::t('app', 'Status Posting'),
			'active' => Yii::t('app', 'Status'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
        ]; 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcct()
    {
        return $this->hasOne(MAcctRekening::className(), ['acct_id' => 'acct_id']);
    }
	
	public function autoInsertJurnal(){
		$this->tanggal = date('Y-m-d');
		$this->status_posting = \app\components\Params::ACCT_JURNAL_STATUS_UNPOSTED;
		$this->acct_no = \app\models\MAcctRekening::getByPk($this->acct_id)->acct_no;
		if($this->validate()){
			if($this->save()){
				return true;
			} else {
				return false;
			}
		}else{
			return false;
		}
    }
	
	public function searchLaporan() {
		$query = self::find();
		$rek = MAcctRekening::tableName();
		$query->select("jurnal_id, kode, ".self::tableName().".acct_no, tanggal, acct_nm, memo, debet, kredit");
		$query->join('JOIN', $rek,$rek.'.acct_id = '.self::tableName().'.acct_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):" ".self::tableName().".tanggal DESC, jurnal_id DESC" );
//		$query->andWhere([self::tableName().'.active'=>TRUE]);
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		return $query;
	}
	
	public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= self::primaryKey()[0];
		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
		}
		if(!empty($searchLaporan->join)){
			foreach($searchLaporan->join as $join){
				$param['join'][] = $join[0].' '.$join[1]." ON ".$join[2];
			}
		}
		if(!empty($searchLaporan->orderBy)){
			foreach($searchLaporan->orderBy as $i_order => $order){
				$param['order'][] = $i_order." ".(($order == 3)?"DESC":"ASC");
			}
		}
		$param['where'] = [];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		
		return $param;
	}
	
}
