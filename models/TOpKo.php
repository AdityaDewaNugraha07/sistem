<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "t_op_ko".
 *
 * @property integer $op_ko_id
 * @property string $jenis_produk
 * @property string $kode
 * @property string $tanggal
 * @property string $pp_no
 * @property integer $sales_id
 * @property string $syarat_jual
 * @property string $sistem_bayar
 * @property string $cara_bayar
 * @property string $cara_bayar_reff
 * @property string $tanggal_kirim
 * @property integer $cust_id
 * @property string $alamat_bongkar
 * @property string $cust_alamat
 * @property integer $disetujui
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $provinsi_bongkar
 * @property integer $po_ko_id
 *
 * @property MCustomer $cust
 * @property MPegawai $disetujui0
 * @property MSales $sales
 * @property TCancelTransaksi $cancelTransaksi
 * @property TTempobayarKo[] $tTempobayarKos
 * @property mixed|string|null $tanggal_po
 */
class TOpKo extends ActiveRecord
{
    //    public $customer;
    public $customer,$cust_an_nama,$cust_pr_nama,$cust_an_alamat,$tgl_awal,$tgl_akhir;    
    public $tarik_data, $produk_nama, $kode_po_ko;
    public static function tableName()
    {
        return 't_op_ko';
    }
    
    public function behaviors(){
		return [DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenis_produk', 'kode', 'tanggal', 'pp_no', 'sales_id', 'syarat_jual', 'sistem_bayar', 'cara_bayar', 'tanggal_kirim', 'cust_id', 'disetujui', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'tanggal_kirim', 'created_at', 'updated_at'], 'safe'],
            [['sales_id', 'cust_id', 'disetujui', 'cancel_transaksi_id', 'created_by', 'updated_by', 'po_ko_id'], 'integer'],
            [['alamat_bongkar', 'cust_alamat'], 'string'],
            [['jenis_produk', 'kode', 'pp_no', 'syarat_jual', 'sistem_bayar', 'cara_bayar', 'cara_bayar_reff', 'status', 'provinsi_bongkar'], 'string', 'max' => 50],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['disetujui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disetujui' => 'pegawai_id']],
            [['sales_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSales::className(), 'targetAttribute' => ['sales_id' => 'sales_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['terima_logalam_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaLogalam::className(), 'targetAttribute' => ['terima_logalam_id' => 'terima_logalam_id']],
            [['po_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPoKo::className(), 'targetAttribute' => ['po_ko_id' => 'po_ko_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'op_ko_id' => Yii::t('app', 'Op Ko'),
                'jenis_produk' => Yii::t('app', 'Jenis Produk'),
                'kode' => Yii::t('app', 'Kode OP'),
                'tanggal' => Yii::t('app', 'Tanggal OP'),
                'pp_no' => Yii::t('app', 'No. Berkas PP'),
                'sales_id' => Yii::t('app', 'Sales'),
                'syarat_jual' => Yii::t('app', 'Syarat Jual'),
                'sistem_bayar' => Yii::t('app', 'Sistem Bayar'),
                'cara_bayar' => Yii::t('app', 'Cara Bayar'),
                'cara_bayar_reff' => Yii::t('app', 'Cara Bayar Reff'),
                'tanggal_kirim' => Yii::t('app', 'Tanggal Kirim'),
                'cust_id' => Yii::t('app', 'Customer'),
                'alamat_bongkar' => Yii::t('app', 'Alamat Bongkar'),
                'disetujui' => Yii::t('app', 'Disetujui'),
                'status' => Yii::t('app', 'Status'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'provinsi_bongkar' => Yii::t('app', 'Provinsi Bongkar'),
                'cust_alamat'        => Yii::t('app', 'Customer Address')
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'cust_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDisetujui0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'disetujui']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSales()
    {
        return $this->hasOne(MSales::className(), ['sales_id' => 'sales_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTTempobayarKos()
    {
        return $this->hasMany(TTempobayarKo::className(), ['op_ko_id' => 'op_ko_id']);
    }

    public function searchLaporan() {
        $query = self::find();
      
        /*$query->select(['op_ko_id','kode','tanggal','cust_an_nama', 'alamat_bongkar', 'status', 
                        '(select status from t_approval where reff_no = t_op_ko.kode and level = 1) as status_level_1',			
                        '(select status from t_approval where reff_no = t_op_ko.kode and level = 2) as status_level_2',			
                        '(select updated_at from t_approval where reff_no = t_op_ko.kode and level = 1) as tgl_approve_1', 
                        '(select updated_at from t_approval where reff_no = t_op_ko.kode and level = 2) as tgl_approve_2','t_op_ko.updated_at','t_op_ko.updated_by','jenis_produk']);*/
        $query->select(['op_ko_id','kode','cust_an_nama','jenis_produk','tanggal','alamat_bongkar','status','t_op_ko.updated_at']);
     
        $query->join('JOIN', 'm_customer','m_customer.cust_id = t_op_ko.cust_id');
       // $query->join('JOIN', 'view_user','view_user.user_id = t_op_ko.updated_by');        
        $query->groupBy('op_ko_id, kode, tanggal, cust_an_nama, alamat_bongkar');
        $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']) : self::tableName().'.tanggal desc' );
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
            $query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");//t_op_ko.status<>'' and 
        }
        
        if(!empty($this->status)){
            if($this->status == 'ALL'){
//                $query->andWhere("status = '".$this->status."' ");
            }else{
                $query->andWhere("status <>'' ");
            }
        }
        if(!empty($this->jenis_produk)){
            $query->andWhere("jenis_produk = '".$this->jenis_produk."' ");
        }
        if(!empty($this->cust_id)){
            $query->andWhere("t_op_ko.cust_id = '".$this->cust_id."' ");
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
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
            array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");//t_op_ko.status<>'' and 
        }
        if(!empty($this->status)){
            if($this->status == 'ALL'){
//                array_push($param['where'],"status = '".$this->status."'");                
            }else{
                array_push($param['where'],"status <>''");
            }
            
        }
        if(!empty($this->jenis_produk)){
            array_push($param['where'],"jenis_produk = '".$this->jenis_produk."'");
        }
        if(!empty($this->cust_id)){
            array_push($param['where'],"t_op_ko.cust_id = '".$this->cust_id."'");
        }
        return $param;
    }

    public function searchLaporanX() {
        $query = self::find();
      
        $query->select(['op_ko_id',                                                                                                 //0
                        'kode',                                                                                                     //1
                        'tanggal',                                                                                                  //2
                        'cust_an_nama',                                                                                             //3
                        'alamat_bongkar',                                                                                           //4
                        'status',                                                                                                   //5
                        '(select status from t_approval where reff_no = t_op_ko.kode and level = 1) as status_level_1',			    //6
                        '(select status from t_approval where reff_no = t_op_ko.kode and level = 2) as status_level_2',			    //7
                        '(select updated_at from t_approval where reff_no = t_op_ko.kode and level = 1) as tgl_approve_1',          //8
                        '(select updated_at from t_approval where reff_no = t_op_ko.kode and level = 2) as tgl_approve_2',          //9
                        't_op_ko.updated_at',                                                                                       //10
                        't_op_ko.updated_by',                                                                                       //11
                        'jenis_produk',                                                                                             //12
                        '(select status from t_approval where reff_no = t_op_ko.kode and level = 3) as status_level_3',			    //13
                        '(select updated_at from t_approval where reff_no = t_op_ko.kode and level = 3) as tgl_approve_3',          //14
                        't_op_ko.created_at',
                        ]);       
     
        $query->join('JOIN', 'm_customer','m_customer.cust_id = t_op_ko.cust_id');
       // $query->join('JOIN', 'view_user','view_user.user_id = t_op_ko.updated_by');        
        $query->groupBy('op_ko_id, kode, tanggal, cust_an_nama, alamat_bongkar');
        $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']) : self::tableName().'.tanggal desc' );
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
            $query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");//t_op_ko.status<>'' and 
        }
        
        if(!empty($this->status)){
            if($this->status == 'ALL'){
//                $query->andWhere("status = '".$this->status."' ");
            }else{
                $query->andWhere("status <>'' ");
            }
        }
        if(!empty($this->jenis_produk)){
            $query->andWhere("jenis_produk = '".$this->jenis_produk."' ");
        }
        if(!empty($this->cust_id)){
            $query->andWhere("t_op_ko.cust_id = '".$this->cust_id."' ");
        }
        return $query;
    }

    public function searchLaporanDtX() {
        $searchLaporan = $this->searchLaporanX();
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
            array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");//t_op_ko.status<>'' and 
        }
        if(!empty($this->status)){
            if($this->status == 'ALL'){
//                array_push($param['where'],"status = '".$this->status."'");                
            }else{
                array_push($param['where'],"status <>''");
            }
            
        }
        if(!empty($this->jenis_produk)){
            array_push($param['where'],"jenis_produk = '".$this->jenis_produk."'");
        }
        if(!empty($this->cust_id)){
            array_push($param['where'],"t_op_ko.cust_id = '".$this->cust_id."'");
        }
        return $param;
    }    

    public static function getOptionListLogPelabuhan($cust_id){ 
        $query = "  SELECT DISTINCT t_terima_logalam.* from t_terima_logalam 
                    -- JOIN m_customer ON t_terima_logalam.lokasi_tujuan = m_customer.cust_an_nama
                    JOIN m_customer ON split_part(t_terima_logalam.lokasi_tujuan, ' - ', 1) = m_customer.cust_an_nama
                    JOIN t_terima_logalam_detail ON t_terima_logalam_detail.terima_logalam_id = t_terima_logalam.terima_logalam_id
                    WHERE t_terima_logalam.peruntukan = 'Trading' AND NOT EXISTS (select terima_logalam_id from t_op_ko where 
                    t_terima_logalam.terima_logalam_id = t_op_ko.terima_logalam_id) AND tanggal >= '2025-04-28'"; // pembatasan tanggal berdasarkan tgl pertama di PO
        if($cust_id != null){
            $query .= " AND m_customer.cust_id = {$cust_id}";
        }
        $res = Yii::$app->db->createCommand($query)->queryAll();

        // NOT EXISTS (select no_barcode from t_spm_log where no_barcode = t_terima_logalam_detail.no_barcode)
        $return = [];
        foreach($res as $i =>$val){
            $return[$val['terima_logalam_id']] = $val['kode'];
        }
        return $return;
    }

    /*public function searchLaporanPenerimaanJasaKD() {
        $query = self::find();
        $query->select(
            [
				't_op_ko.op_ko_id',																			//0	
				't_op_ko.kode',																				//1
				't_op_ko.jenis_produk',																		//2		
				't_op_ko.tanggal',																			//3
				'm_sales.sales_nm',																			//4
				't_op_ko.sistem_bayar',																		//5		
				't_op_ko.tanggal_kirim',																	//6		
				'm_customer.cust_an_nama',																	//7
				'm_customer.cust_pr_nama',																	//8
				't_op_ko.cancel_transaksi_id',																//9			
				'MAX(t_spm_ko.spm_ko_id) AS spm_ko_id',														//10			
				't_op_ko.status as xxx',																	//11
				'(select nota_penjualan_id
					from t_nota_penjualan 
					where t_nota_penjualan.op_ko_id = t_op_ko.op_ko_id
					limit 1 ) as yyy'														                //12
			]
        );
     
        $query->join('JOIN', 'm_sales', 'm_sales.sales_id = t_op_ko.sales_id');
        $query->join('JOIN', 'm_customer','m_customer.cust_id = t_op_ko.cust_id');
        $query->join('LEFT JOIN', 't_spm_ko','t_spm_ko.op_ko_id = t_op_ko.op_ko_id');
        $query->groupBy('t_op_ko.op_ko_id, t_op_ko.kode, t_op_ko.jenis_produk, t_op_ko.tanggal, m_sales.sales_nm, t_op_ko.sistem_bayar,
						t_op_ko.tanggal_kirim, m_customer.cust_an_nama, m_customer.cust_pr_nama, t_op_ko.cancel_transaksi_id, xxx, yyy');
        $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']) : self::tableName().'.tanggal desc' );
        $query->andWhere("t_op_ko.cancel_transaksi_id IS NULL and t_op_ko.jenis_produk = 'JasaKD'");
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
            $query->andWhere("t_op_ko.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
        }
        if(!empty($this->kode)){
            $query->andWhere("t_op_ko.kode = '".$this->kode."'");
        }
        if(!empty($this->cust_an_nama)){
            $query->andWhere("m_customer.cust_an_nama = '".$this->cust_an_nama."' ");
        }
        return $query;
    }*/

    public function searchLaporanPenerimaanJasaKD() {
        $query = self::find();
        $query->select(
            [
				't_op_ko.op_ko_id',																			//0	
				't_op_ko.kode',																				//1
				't_op_ko.tanggal',																			//2
				'm_sales.sales_nm',																			//3
				't_op_ko.tanggal_kirim',																	//4		
				'm_customer.cust_an_nama',																	//5
				'm_customer.cust_pr_nama',																	//6
                't_terima_jasa.tanggal as tgl_terima',                                                      //7
                't_terima_jasa.nopol',                                                                      //8
                't_terima_jasa.nomor_palet',                                                                //9
                'm_produk_jasa.nama',                                                                       //10
                "CONCAT(t_terima_jasa.t, ' ', t_terima_jasa.t_satuan, ' x ', 
                        t_terima_jasa.l , ' ', t_terima_jasa.l_satuan, ' x ', 
                        t_terima_jasa.p, ' ', t_terima_jasa.p_satuan) as dimensi",                          //11
                't_terima_jasa.qty_kecil',                                                                  //12
                't_terima_jasa.kubikasi',                                                                   //13
                't_terima_jasa.qty_kecil_actual',                                                           //14
                't_terima_jasa.kubikasi_actual',                                                            //15
                't_terima_jasa.keterangan',                                                                 //16
                't_spm_ko.status',
                'MAX(t_spm_ko.kode) as kode_spm',
                'MAX(t_spm_ko.tanggal) as tgl_spm'
			]
        );
     
        $query->join('JOIN', 'm_sales', 'm_sales.sales_id = t_op_ko.sales_id');
        $query->join('JOIN', 'm_customer','m_customer.cust_id = t_op_ko.cust_id');
        $query->join('LEFT JOIN', 't_spm_ko','t_spm_ko.op_ko_id = t_op_ko.op_ko_id');
        $query->join('LEFT JOIN', 't_terima_jasa', 't_terima_jasa.op_ko_id = t_op_ko.op_ko_id'); 
        $query->join('INNER JOIN', 'm_produk_jasa', 't_terima_jasa.produk_jasa_id = m_produk_jasa.produk_jasa_id');
        $query->join('LEFT JOIN', "t_spm_ko_detail", "t_spm_ko_detail.spm_ko_id = t_spm_ko.spm_ko_id 
                        AND t_terima_jasa.nomor_palet = ANY(string_to_array(REPLACE(t_spm_ko_detail.keterangan, '''', ''), ','))");

        $query->groupBy('t_op_ko.op_ko_id, t_op_ko.kode, t_op_ko.jenis_produk, t_op_ko.tanggal, m_sales.sales_nm, t_op_ko.sistem_bayar, t_op_ko.tanggal_kirim, 
                        m_customer.cust_an_nama, m_customer.cust_pr_nama, t_op_ko.cancel_transaksi_id,t_terima_jasa.tanggal,t_terima_jasa.nopol,
                        t_terima_jasa.nomor_palet, t_terima_jasa.t,t_terima_jasa.t_satuan,t_terima_jasa.l,t_terima_jasa.l_satuan,t_terima_jasa.p,
                        t_terima_jasa.p_satuan, t_terima_jasa.qty_kecil,t_terima_jasa.kubikasi,t_terima_jasa.keterangan,t_terima_jasa.qty_kecil_actual,
                        t_terima_jasa.kubikasi_actual,m_produk_jasa.nama,t_spm_ko.status');
        $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']) : self::tableName().'.tanggal desc' );
        $query->andWhere("t_op_ko.cancel_transaksi_id IS NULL and t_op_ko.jenis_produk = 'JasaKD'");
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
            $query->andWhere("t_op_ko.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
        }
        if(!empty($this->kode)){
            $query->andWhere("t_op_ko.kode = '".$this->kode."'");
        }
        if(!empty($this->cust_an_nama)){
            $query->andWhere("m_customer.cust_an_nama = '".$this->cust_an_nama."' ");
        }
        return $query;
    }

    public function searchLaporanPenerimaanJasaKDDt() {
        $searchLaporan = $this->searchLaporanPenerimaanJasaKD();
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
        $param['where'] = ["t_op_ko.cancel_transaksi_id IS NULL and t_op_ko.jenis_produk = 'JasaKD'"];
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
            array_push($param['where'],"t_op_ko.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' "); 
        }
        if(!empty($this->kode)){
            array_push($param['where'],"t_op_ko.kode = '".$this->kode."'");
        }
        if(!empty($this->cust_an_nama)){
            array_push($param['where'],"m_customer.cust_an_nama = '".$this->cust_an_nama."'");
        }
        return $param;
    }  

}
