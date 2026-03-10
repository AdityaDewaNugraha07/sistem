<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_loglist_detail".
 *
 * @property integer $loglist_detail_id
 * @property integer $loglist_id
 * @property string $nomor_grd
 * @property string $nomor_produksi
 * @property string $nomor_batang
 * @property integer $kayu_id
 * @property double $panjang
 * @property double $diameter_ujung
 * @property double $diameter_pangkal
 * @property double $diameter_rata
 * @property double $cacat_panjang
 * @property string $cacat_gb
 * @property string $cacat_gr
 * @property string $volume_range
 * @property double $volume_value
 * @property boolean $is_freshcut
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property double $diameter_ujung1
 * @property double $diameter_ujung2
 * @property double $diameter_pangkal1
 * @property double $diameter_pangkal2
 *
 * @property MKayu $kayu
 * @property TLoglist $loglist
 */
class TLoglistDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $tgl_awal,$tgl_akhir,$graderlog_nm,$tipe_dinas,$wilayah_dinas_nama,$kayu_nama;
	public $pihak1_perusahaan, $log_kontrak_id;
    public static function tableName()
    {
        return 't_loglist_detail';
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
            [['loglist_id', 'nomor_grd', 'nomor_produksi', 'nomor_batang', 'kayu_id', 'panjang', 'diameter_ujung', 'diameter_pangkal', 'diameter_rata', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['loglist_id', 'kayu_id', 'created_by', 'updated_by'], 'integer'],
            [['panjang', 'diameter_ujung', 'diameter_pangkal', 'diameter_rata', 'cacat_panjang', 'volume_value','diameter_ujung1','diameter_ujung2','diameter_pangkal1','diameter_pangkal2'], 'number'],
            [['is_freshcut'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['nomor_grd', 'nomor_produksi', 'nomor_batang', 'cacat_gb', 'cacat_gr', 'volume_range'], 'string', 'max' => 50],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
            [['loglist_id'], 'exist', 'skipOnError' => true, 'targetClass' => TLoglist::className(), 'targetAttribute' => ['loglist_id' => 'loglist_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'loglist_detail_id' => Yii::t('app', 'Loglist Detail'),
                'loglist_id' => Yii::t('app', 'Loglist'),
                'nomor_grd' => Yii::t('app', 'Nomor Grd'),
                'nomor_produksi' => Yii::t('app', 'Nomor Produksi'),
                'nomor_batang' => Yii::t('app', 'Nomor Batang'),
                'kayu_id' => Yii::t('app', 'Kayu'),
                'panjang' => Yii::t('app', 'Panjang'),
                'diameter_ujung' => Yii::t('app', 'Diameter Ujung'),
                'diameter_pangkal' => Yii::t('app', 'Diameter Pangkal'),
                'diameter_rata' => Yii::t('app', 'Diameter Rata'),
                'cacat_panjang' => Yii::t('app', 'Cacat Panjang'),
                'cacat_gb' => Yii::t('app', 'Cacat Gb'),
                'cacat_gr' => Yii::t('app', 'Cacat Gr'),
                'volume_range' => Yii::t('app', 'Volume Range'),
                'volume_value' => Yii::t('app', 'Volume Value'),
                'is_freshcut' => Yii::t('app', 'Is Freshcut'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'diameter_ujung1' => Yii::t('app', 'Diameter Ujung 1'),
                'diameter_ujung2' => Yii::t('app', 'Diameter Ujung 2'),
                'diameter_pangkal1' => Yii::t('app', 'Diameter Pangkal 1'),
                'diameter_pangkal2' => Yii::t('app', 'Diameter Pangkal 2'),
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
    public function getLoglist()
    {
        return $this->hasOne(TLoglist::className(), ['loglist_id' => 'loglist_id']);
    }
	
    public function getKayuByLoglist($loglist_id){
		$sql = "SELECT ". self::tableName().".kayu_id, m_kayu.kayu_nama FROM ". self::tableName()." JOIN m_kayu ON m_kayu.kayu_id = ". self::tableName().".kayu_id WHERE loglist_id = ".$loglist_id." GROUP BY ". self::tableName().".kayu_id, m_kayu.kayu_nama";
		$model = \Yii::$app->db->createCommand($sql)->queryAll();
		$ret = '';
		foreach( $model as $i => $detail ){
			$ret .= $detail['kayu_nama'];
			if( count($model) != $i+1 ){
				$ret .= ", ";
			}
		}
        return $ret;
    }
	
	public static function getTotalByLoglistId($loglist_id){
		$sqltotal = "SELECT * FROM ".self::tableName()." WHERE loglist_id = ".$loglist_id;
		$restotal = \Yii::$app->db->createCommand($sqltotal)->queryAll();
		$totalm3 = 0;
		foreach($restotal as $iv => $log){
			$totalm3 += $log['volume_value'];
		}
		$ret['jmlbtg'] = count($restotal);
		$ret['totalm3'] = $totalm3;
		return $ret;
	}
	
    public function getRekapPerRange($loglist_id){
		$sqlrangegroup = "SELECT volume_range FROM ". self::tableName()." WHERE loglist_id = ".$loglist_id." GROUP BY volume_range ORDER BY volume_range ASC";
		$resrangegroup = \Yii::$app->db->createCommand($sqlrangegroup)->queryAll();
		$sql = "SELECT ". self::tableName().".kayu_id, m_kayu.kayu_nama 
				FROM ". self::tableName()." JOIN m_kayu ON m_kayu.kayu_id = ". self::tableName().".kayu_id 
				WHERE loglist_id = ".$loglist_id." 
				GROUP BY ". self::tableName().".kayu_id, m_kayu.kayu_nama ";
		$res = \Yii::$app->db->createCommand($sql)->queryAll();
		
		$ret = '';
		$ret .= '<table class="table table-striped table-bordered table-advance table-hover">';
		$ret .=		'<thead>';
		$ret .=		'<tr>';
		$ret .=			'<th rowspan="2" style="text-align: center; vertical-align: middle; border: 1px solid #bdbdbd; padding: 2px;">Jenis Kayu</th>';
					foreach($resrangegroup as $i => $range){
		$ret .=			'<th colspan="2" style="text-align: center; vertical-align: middle; border: 1px solid #bdbdbd; padding: 2px;">'.$range['volume_range'].'</th>';
					}
		$ret .=		'</tr>';
		$ret .=		'<tr>';
					foreach($resrangegroup as $i => $range){
		$ret .=			'<th style="width:55px; text-align: center; vertical-align: middle; border: 1px solid #bdbdbd; padding: 2px;">Bt</th>';
		$ret .=			'<th style="width:55px; text-align: center; vertical-align: middle; border: 1px solid #bdbdbd; padding: 2px;">m<sup>3</sup></th>';
					}
		$ret .=		'</tr>';
		$ret .=		'</thead>';
		$ret .=		'<tbody>';
					foreach($res as $ii => $kayu){
		$ret .=		'<tr>';
		$ret .=			'<td style="vertical-align: middle;">'.$kayu['kayu_nama'].'</td>';
						foreach($resrangegroup as $iii => $range){
							$sqlcontent = "SELECT kayu_id, volume_range, COUNT(loglist_detail_id) AS totalbatang, SUM(volume_value) AS m3  FROM t_loglist_detail
											WHERE loglist_id = ".$loglist_id." AND volume_range = '".$range['volume_range']."' AND kayu_id = ".$kayu['kayu_id']." GROUP BY t_loglist_detail.kayu_id, volume_range 
											ORDER BY volume_range ASC";
							$rescontent = \Yii::$app->db->createCommand($sqlcontent)->queryOne();
							if(!empty($rescontent)){
		$ret .=					'<td style="vertical-align: middle; text-align:center;">'.((!empty($rescontent['totalbatang']))?\app\components\DeltaFormatter::formatNumberForUser($rescontent['totalbatang']):"-").'</td>';
		$ret .=					'<td style="vertical-align: middle; text-align:center;">'.((!empty($rescontent['m3']))?\app\components\DeltaFormatter::formatNumberForUser($rescontent['m3']):"-").'</td>';	
							}else{
		$ret .=					'<td><center> - </center></td><td><center> - </center></td>';					
							}
						}
		$ret .=		'</tr>';
					}
		$ret .=		'</tbody>';
		$ret .= '</table>';
		$ret .= '<tr>
					<td style="text-align: left; width: 25%;"><b>Total Batang</b></td>
					<td style="width: 5%;">:</td>
					<td><b>'.self::getTotalByLoglistId($loglist_id)['jmlbtg'].'</b></td>
				</tr>';
		
		$ret .= '<tr>
					<td style="text-align: left; width: 25%;"><b>Total m<sup>3</sup></b></td>
					<td style="width: 5%;">:</td>
					<td><b>'. \app\components\DeltaFormatter::formatNumberForUser(self::getTotalByLoglistId($loglist_id)['totalm3']).'</b></td>
				</tr>';
        return $ret;
    }
	
	public function searchLaporan(){
		$loglist = TLoglist::tableName();
		$kayu = MKayu::tableName();
		$query = self::find();
		$query->select('nomor_grd, nomor_produksi, nomor_batang, kayu_nama, panjang, diameter_ujung, diameter_pangkal, diameter_rata, cacat_panjang, cacat_gb, cacat_gr, volume_range, volume_value, is_freshcut');
		$query->join('JOIN', $kayu,$kayu.'.kayu_id = '.self::tableName().'.kayu_id');
		$query->join('JOIN', $loglist,$loglist.'.loglist_id = '.self::tableName().'.loglist_id');
		$query->join('JOIN', 't_log_kontrak', 't_log_kontrak.log_kontrak_id = t_loglist.log_kontrak_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("t_loglist.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->nomor_produksi)){
			$query->andWhere("nomor_produksi ILIKE '%".$this->nomor_produksi."%'");
		}
		if(!empty($this->nomor_batang)){
			$query->andWhere("nomor_batang ILIKE '%".$this->nomor_batang."%'");
		}
		if(!empty($this->kayu_id)){
			$query->andWhere("t_loglist_detail.kayu_id  = ".$this->kayu_id);
		}
		if(!empty($this->volume_range)){
			$query->andWhere("volume_range  = '".$this->volume_range."'");
		}
		if(!empty($this->is_freshcut)){
			$query->andWhere("is_freshcut = '".$this->is_freshcut."'");
		}
		if(!empty($this->pihak1_perusahaan)){
			$query->andWhere("pihak1_perusahaan ilike '%".$this->pihak1_perusahaan."%'");
		}
		if(!empty($this->log_kontrak_id)){
			$query->andWhere("t_log_kontrak.log_kontrak_id = ".$this->log_kontrak_id);
		}
		return $query;
	}
	
	public function searchLaporanDt(){
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
		$loglist = TLoglist::tableName();
		$kayu = MKayu::tableName();
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
			array_push($param['where'],"t_loglist.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->nomor_produksi)){
			array_push($param['where'],"nomor_produksi ILIKE '%".$this->nomor_produksi."%'");
		}
		if(!empty($this->nomor_batang)){
			array_push($param['where'],"nomor_batang ILIKE '%".$this->nomor_batang."%'");
		}
		if(!empty($this->kayu_id)){
			array_push($param['where'],$param['table'].".kayu_id = ".$this->kayu_id);
		}
		if(!empty($this->volume_range)){
			array_push($param['where'],"volume_range = '".$this->volume_range."'");
		}
		if(!empty($this->is_freshcut)){
			array_push($param['where'],"is_freshcut = '".$this->is_freshcut."'");
		}
		if(!empty($this->pihak1_perusahaan)){
			array_push($param['where'],"pihak1_perusahaan ilike '%".$this->pihak1_perusahaan."%'");
		}
		if(!empty($this->log_kontrak_id)){
			array_push($param['where'],"t_log_kontrak.log_kontrak_id = ".$this->log_kontrak_id);
		}
		
		return $param;
	}
}
