<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_biaya_grader_detail".
 *
 * @property integer $biaya_grader_detail_id
 * @property integer $biaya_grader_id
 * @property integer $graderlog_id
 * @property string $tipe_dinas
 * @property integer $wilayah_dinas_id
 * @property string $tujuan_dinas
 * @property string $periode_awal
 * @property string $periode_akhir
 * @property string $grader_norek
 * @property string $grader_bank
 * @property double $biaya_grader_detail_jml
 *
 * @property MGraderlog $graderlog
 * @property MWilayahDinas $wilayahDinas
 * @property TBiayaGrader $biayaGrader
 */
class TBiayaGraderDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $tgl_awal,$tgl_akhir,$biaya_grader_kode,$graderlog_nm,$wilayah_dinas_nama,$status;
    public static function tableName()
    {
        return 't_biaya_grader_detail';
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
            [['biaya_grader_id', 'graderlog_id', 'tipe_dinas', 'wilayah_dinas_id', 'grader_norek', 'grader_bank'], 'required'],
            [['biaya_grader_id', 'graderlog_id', 'wilayah_dinas_id'], 'integer'],
            [['tujuan_dinas'], 'string'],
            [['periode_awal', 'periode_akhir'], 'safe'],
            [['biaya_grader_detail_jml'], 'number'],
            [['tipe_dinas'], 'string', 'max' => 25],
            [['grader_norek', 'grader_bank'], 'string', 'max' => 50],
            [['graderlog_id'], 'exist', 'skipOnError' => true, 'targetClass' => MGraderlog::className(), 'targetAttribute' => ['graderlog_id' => 'graderlog_id']],
            [['wilayah_dinas_id'], 'exist', 'skipOnError' => true, 'targetClass' => MWilayahDinas::className(), 'targetAttribute' => ['wilayah_dinas_id' => 'wilayah_dinas_id']],
            [['biaya_grader_id'], 'exist', 'skipOnError' => true, 'targetClass' => TBiayaGrader::className(), 'targetAttribute' => ['biaya_grader_id' => 'biaya_grader_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'biaya_grader_detail_id' => Yii::t('app', 'Biaya Grader Detail'),
                'biaya_grader_id' => Yii::t('app', 'Pengajuan Biaya Grader'),
                'graderlog_id' => Yii::t('app', 'Nama Grader'),
                'tipe_dinas' => Yii::t('app', 'Tipe Dinas'),
                'wilayah_dinas_id' => Yii::t('app', 'Wilayah Dinas'),
                'tujuan_dinas' => Yii::t('app', 'Tempat Tujuan'),
                'periode_awal' => Yii::t('app', 'Periode Awal'),
                'periode_akhir' => Yii::t('app', 'Periode Akhir'),
                'grader_norek' => Yii::t('app', 'No. Rek'),
                'grader_bank' => Yii::t('app', 'Nama Bank'),
                'biaya_grader_detail_jml' => Yii::t('app', 'Biaya (Rp)'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGraderlog()
    {
        return $this->hasOne(MGraderlog::className(), ['graderlog_id' => 'graderlog_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWilayahDinas()
    {
        return $this->hasOne(MWilayahDinas::className(), ['wilayah_dinas_id' => 'wilayah_dinas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBiayaGrader()
    {
        return $this->hasOne(TBiayaGrader::className(), ['biaya_grader_id' => 'biaya_grader_id']);
    }
	
	public function searchLaporan(){
		$biayagrader = TBiayaGrader::tableName();
		$grader = MGraderlog::tableName();
		$wildinas = MWilayahDinas::tableName();
		$query = self::find();
		$query->select('biaya_grader_detail_id, biaya_grader_kode, graderlog_nm, tipe_dinas, wilayah_dinas_nama, periode_awal, periode_akhir, biaya_grader_detail_jml, status');
		$query->join('JOIN', $biayagrader,$biayagrader.'.biaya_grader_id = '.self::tableName().'.biaya_grader_id');
		$query->join('JOIN', $grader,$grader.'.graderlog_id = '.self::tableName().'.graderlog_id');
		$query->join('JOIN', $wildinas,$wildinas.'.wilayah_dinas_id = '.self::tableName().'.wilayah_dinas_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			'biaya_grader_kode DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("periode_awal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->biaya_grader_kode)){
			$query->andWhere("biaya_grader_kode ILIKE '%".$this->biaya_grader_kode."%'");
		}
		if(!empty($this->tipe_dinas)){
			$query->andWhere("tipe_dinas ILIKE '%".$this->tipe_dinas."%'");
		}
		if(!empty($this->graderlog_id)){
			$query->andWhere("graderlog_id  = ".$this->graderlog_id);
		}
		if(!empty($this->wilayah_dinas_id)){
			$query->andWhere("wilayah_dinas_id  = ".$this->wilayah_dinas_id);
		}
		if(!empty($this->status)){
			$query->andWhere("status = '".$this->status."'");
		}
		return $query;
	}
	
	public function searchLaporanDt(){
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
		$wildinas = MWilayahDinas::tableName();
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
			array_push($param['where'],"periode_awal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->biaya_grader_kode)){
			array_push($param['where'],"biaya_grader_kode ILIKE '%".$this->biaya_grader_kode."%'");
		}
		if(!empty($this->tipe_dinas)){
			array_push($param['where'],"tipe_dinas ILIKE '%".$this->tipe_dinas."%'");
		}
		if(!empty($this->graderlog_id)){
			array_push($param['where'],$param['table'].".graderlog_id = ".$this->graderlog_id);
		}
		if(!empty($this->wilayah_dinas_id)){
			array_push($param['where'],$wildinas.".wilayah_dinas_id = ".$this->wilayah_dinas_id);
		}
		if(!empty($this->status)){
			array_push($param['where'],"status = '".$this->status."'");
		}
		
		return $param;
	}
}
