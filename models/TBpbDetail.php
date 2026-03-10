<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_bpb_detail".
 *
 * @property integer $bpbd_id
 * @property integer $bpb_id
 * @property integer $bhp_id
 * @property double $bpbd_jml
 * @property string $bpbd_ket
 * @property integer $cancel_transaksi_id
 *
 * @property MBrgBhp $bhp
 * @property TBpb $bpb
 * @property TCancelTransaksi $cancelTransaksi
 */ 
class TBpbDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $qty_kebutuhan, $current_stock, $bhp_nama, $satuan, $jml_terpenuhi;
    public $qty_in,$qty_out,$keterangan;
	public $bhp_nm,$bhp_group,$tgl_awal,$tgl_akhir,$bhp_satuan,$departement_nama,$bpb_kode,$bpb_tanggal,$bpb_tgl_keluar,$departement_id;
	public $bhp_kode;
    public static function tableName()
    {
        return 't_bpb_detail';
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
            [['bpb_id', 'bhp_id', 'bpbd_jml'], 'required'],
            [['bpb_id', 'bhp_id', 'cancel_transaksi_id'], 'integer'],
            [['bpbd_jml'], 'number'],
            [['bpbd_ket'], 'string'],
            [['bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgBhp::className(), 'targetAttribute' => ['bhp_id' => 'bhp_id']],
            [['bpb_id'], 'exist', 'skipOnError' => true, 'targetClass' => TBpb::className(), 'targetAttribute' => ['bpb_id' => 'bpb_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'bpbd_id' => Yii::t('app', 'Bpbd'),
                'bpb_id' => Yii::t('app', 'Bpb'),
                'bhp_id' => Yii::t('app', 'Bhp'),
                'bpbd_jml' => Yii::t('app', 'Bpbd Jml'),
                'bpbd_ket' => Yii::t('app', 'Bpbd Ket'),
				'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBhp()
    {
        return $this->hasOne(MBrgBhp::className(), ['bhp_id' => 'bhp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBpb()
    {
        return $this->hasOne(TBpb::className(), ['bpb_id' => 'bpb_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    } 
	
	public function searchLaporan(){
		$bhp = MBrgBhp::tableName();
		$bpb = TBpb::tableName();
		$dept = MDepartement::tableName();
		$query = self::find();
		$query->select(self::tableName().'.bpbd_id,  bpb_kode, bpb_tgl_keluar, bhp_kode, bhp_nm, bpbd_jml, bhp_satuan, departement_nama, '.self::tableName().'.bpbd_ket');
		$query->join('JOIN', $bpb,$bpb.'.bpb_id = '.self::tableName().'.bpb_id');
		$query->join('JOIN', $bhp,$bhp.'.bhp_id = '.self::tableName().'.bhp_id');
		$query->join('JOIN', $dept,$dept.'.departement_id = '.$bpb.'.departement_id');
		$query->where(self::tableName().'.cancel_transaksi_id IS NULL');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			$bpb.'.bpb_tgl_keluar DESC, departement_nama' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("bpb_tgl_keluar BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bpb_kode)){
			$query->andWhere("bpb_kode  ILIKE '%".$this->bpb_kode."%'");
		}
		if(!empty($this->bhp_nm)){
			$query->andWhere("bhp_nm  ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->bhp_group)){
			$query->andWhere("bhp_group  ILIKE '%".$this->bhp_group."%'");
		}
		if(!empty($this->departement_id)){
			$query->andWhere($bpb.".departement_id  = ".$this->departement_id);
		}
		return $query;
	}
	
	public function searchLaporanDt(){
		$searchLaporan = $this->searchLaporan();
		$bpb = TBpb::tableName();
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
		$param['where'] = [self::tableName().'.cancel_transaksi_id IS NULL'];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"bpb_tgl_keluar BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bpb_kode)){
			array_push($param['where'],"bpb_kode = '".$this->bpb_kode."'");
		}
		if(!empty($this->bhp_group)){
			array_push($param['where'],"bhp_group = '".$this->bhp_group."'");
		}
		if(!empty($this->bhp_nm)){
			array_push($param['where'],"bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->departement_id)){
			array_push($param['where'],$bpb.".departement_id = ".$this->departement_id);
		}
		
		return $param;
	}
}
