<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_mutasi_gudanglogistik".
 *
 * @property integer $mutasi_gudanglogistik_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $pegawai_mutasi
 * @property string $status
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property integer $departement_id
 * @property integer $spb_id
 *
 * @property MDepartement $departement
 * @property MPegawai $pegawaiMutasi
 * @property TCancelTransaksi $cancelTransaksi
 * @property TSpb $spb
 */
class TMutasiGudanglogistik extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $spb_kode,$tgl_awal,$tgl_akhir,$bhp_nm,$departement_nama,$satuan,$qty;
    public static function tableName()
    {
        return 't_mutasi_gudanglogistik';
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
            [['kode', 'tanggal', 'pegawai_mutasi', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'departement_id', 'spb_id'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['pegawai_mutasi', 'created_by', 'updated_by', 'cancel_transaksi_id', 'departement_id', 'spb_id'], 'integer'],
            [['keterangan'], 'string'],
            [['kode', 'status'], 'string', 'max' => 50],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
            [['pegawai_mutasi'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pegawai_mutasi' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['spb_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpb::className(), 'targetAttribute' => ['spb_id' => 'spb_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'mutasi_gudanglogistik_id' => Yii::t('app', 'Mutasi Gudanglogistik'),
                'kode' => Yii::t('app', 'Kode Mutasi'),
                'tanggal' => Yii::t('app', 'Tanggal Mutasi'),
                'pegawai_mutasi' => Yii::t('app', 'Pegawai Mutasi'),
                'status' => Yii::t('app', 'Status'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
                'departement_id' => Yii::t('app', 'Dept Pemesan'),
                'spb_id' => Yii::t('app', 'Kode SPB'),
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
    public function getPegawaiMutasi()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawai_mutasi']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpb()
    {
        return $this->hasOne(TSpb::className(), ['spb_id' => 'spb_id']);
    }
	
	public function searchLaporan() {
		$bhp = MBrgBhp::tableName();
		$detail = TMutasiGudanglogistikDetail::tableName();
		$dept = MDepartement::tableName();
		$spb = TSpb::tableName();
		$query = self::find();
		$query->select(self::tableName().'.mutasi_gudanglogistik_id, departement_nama, kode, '.$spb.'.spb_kode, tanggal, bhp_nm, qty, satuan, '.$detail.'.keterangan');
		$query->join('JOIN', $detail,$detail.'.mutasi_gudanglogistik_id = '.self::tableName().'.mutasi_gudanglogistik_id');
		$query->join('JOIN', $bhp,$bhp.'.bhp_id = '.$detail.'.bhp_id');
		$query->join('JOIN', $spb,$spb.'.spb_id = '.self::tableName().'.spb_id');
		$query->join('JOIN', $dept,$dept.'.departement_id = '.self::tableName().'.departement_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.tanggal DESC, kode DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bhp_nm)){
			$query->andWhere("bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->kode)){
			$query->andWhere("kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->departement_id)){
			$query->andWhere($dept.".departement_id = ".$this->departement_id);
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
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bhp_nm)){
			array_push($param['where'],"bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->kode)){
			array_push($param['where'],"kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->departement_id)){
			array_push($param['where'],$dept.".departement_id = ".$this->departement_id);
		}
		return $param;
	}
	
}
