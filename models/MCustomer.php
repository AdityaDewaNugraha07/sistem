<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use app\components\DeltaGeneralBehavior;
/**
 * This is the model class for table "m_customer".
 * @note Rewrite by Edi on 2022-02-10
 * @property integer $cust_id
 * @property integer $kode_customer 
 * @property string $cust_kode
 * @property double $cust_max_plafond
 * @property string $cust_no_npwp
 * @property string $cust_file_npwp
 * @property string $cust_file_ktp
 * @property string $cust_file_photo
 * @property string $cust_an_nik
 * @property string $cust_an_jk
 * @property string $cust_an_nohp
 * @property string $cust_an_agama
 * @property string $cust_an_email
 * @property string $cust_pr_nama
 * @property string $cust_pr_direktur
 * @property string $cust_pr_alamat
 * @property integer $kota_id
 * @property string $cust_pr_phone
 * @property string $cust_pr_fax
 * @property string $cust_pr_email
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status_approval
 * @property string $by_dirut
 * @property string $approve_reason
 * @property string $reject_reason
 * @property bool|mixed|null $active
 * @property mixed|string|null $cust_tipe_penjualan
 * @property false|mixed|string|null $cust_tanggal_join
 * @property false|mixed|string|null $cust_an_tgllahir
 * @property int|mixed|null $cust_is_pkp
 * @property int|mixed|null $by_kadiv
 * @property mixed|null $cust_an_nama
 * @property mixed|null $cust_an_alamat
 */
class MCustomer extends ActiveRecord
{

    public $file1, $file2, $file3, $cust_an_nama2,$cust_an_alamat2, $kadiv_name, $dirut_name, $customer_jenis, $cust_max_plafond_lama;
    private static $filter_jenis_produk   = ['Plywood', 'Lamineboard', 'Platform'];

    public static function tableName()
    {
        return 'm_customer';
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
            [['cust_kode', 'cust_tipe_penjualan', 'cust_tanggal_join', 'cust_is_pkp', 'cust_max_plafond', 'cust_no_npwp', 'cust_an_nama', 'cust_an_nik', 'cust_an_jk', 'cust_an_tgllahir', 'cust_an_nohp', 'cust_an_agama', 'cust_an_alamat', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['cust_tanggal_join', 'cust_an_tgllahir', 'created_at', 'updated_at'], 'safe'],
            [['cust_is_pkp', 'active'], 'boolean'],
            [['kota_id', 'created_by', 'updated_by', 'by_kadiv', 'by_dirut'], 'integer'],
            [['cust_file_npwp', 'cust_file_ktp', 'cust_file_photo', 'cust_an_alamat', 'cust_pr_alamat','cust_max_plafond'], 'string'],
            [['cust_kode', 'cust_tipe_penjualan', 'cust_no_npwp', 'cust_an_nik', 'cust_an_jk', 'cust_an_nohp', 'cust_an_agama', 'cust_pr_phone', 'cust_pr_fax'], 'string', 'max' => 30],
            [['cust_an_nama', 'cust_an_email', 'cust_pr_nama', 'cust_pr_direktur', 'cust_pr_email','contact_person'], 'string', 'max' => 100],
            [['cust_an_email'], 'email'],
            [['file1'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['file2'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['file3'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['status_approval'], 'string', 'max' => 20],
            [['kode_customer'], 'string', 'max' => 50],
            [['approve_reason', 'reject_reason'], 'string'],
        ];
    }
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cust_id' => Yii::t('app', 'Customer'),
            'cust_kode' => Yii::t('app', 'Customer Kode'),
            'cust_tipe_penjualan' => Yii::t('app', 'Jenis Customer'),
            'cust_tanggal_join' => Yii::t('app', 'Tanggal Join'),
            'cust_is_pkp' => Yii::t('app', 'PKP'),
            'cust_max_plafond' => Yii::t('app', 'Max Plafond (Rp)'),
            'cust_no_npwp' => Yii::t('app', 'No. Npwp'),
            'cust_file_npwp' => Yii::t('app', 'File Npwp'),
            'cust_file_ktp' => Yii::t('app', 'File Ktp'),
            'cust_file_photo' => Yii::t('app', 'Photo'),
            'cust_an_nama' => Yii::t('app', 'Atas Nama'),
            'cust_an_nik' => Yii::t('app', 'Nik'),
            'cust_an_jk' => Yii::t('app', 'Jenis Kelamin'),
            'cust_an_tgllahir' => Yii::t('app', 'Tanggal Lahir'),
            'cust_an_nohp' => Yii::t('app', 'HP / Telp'),
            'cust_an_agama' => Yii::t('app', 'Agama'),
            'cust_an_alamat' => Yii::t('app', 'Alamat'),
            'cust_an_email' => Yii::t('app', 'Email'),
            'cust_pr_nama' => Yii::t('app', 'Nama Perusahaan'),
            'cust_pr_direktur' => Yii::t('app', 'Nama Direktur'),
            'cust_pr_alamat' => Yii::t('app', 'Alamat Perusahaan'),
            'kota_id' => Yii::t('app', 'Kota Perusahaan'),
            'cust_pr_phone' => Yii::t('app', 'Telp Perusahaan'),
            'cust_pr_fax' => Yii::t('app', 'Fax'),
            'cust_pr_email' => Yii::t('app', 'Email Perusahaan'),
            'active' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
            'status_approval' => Yii::t('app', 'Status Approval'),
            'contact_person' => Yii::t('app', 'Contact Person'),
        ];
    }
    
    /**
     * @return ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'cust_id']);
    } 
	
	public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('cust_an_nama ASC')->all();
		$ret = [];
		foreach($res as $cust){
			$ret[$cust['cust_id']] = $cust['cust_an_nama'] . " " . (!empty($cust['cust_pr_nama']) ? "- " . $cust['cust_pr_nama'] : "");
			
		}
        return $ret;
    }
	public static function getOptionListExport()
    {
        $res = self::find()->where(['active'=>true,'cust_tipe_penjualan' =>'export'])->orderBy('cust_an_nama ASC')->all();
		$ret = [];
		foreach($res as $cust){
			$ret[$cust['cust_id']] = $cust['cust_an_nama'];
			
		}
        return $ret;
    }

    /**
     * @throws Exception
     */
    public static function getSisaPlafon($cust_id){
        $sql = "SELECT
                    m_customer.cust_id,
                    (
                        m_customer.cust_max_plafond - (
                            COALESCE (
                                SUM ( t_nota_penjualan.total_bayar ) - COALESCE ( ( SELECT SUM ( bayar ) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id ), 0 ),
                                0 
                            ) 
                        ) 
                    ) AS sisa_plafon 
                FROM
                    m_customer
                    LEFT JOIN t_nota_penjualan ON t_nota_penjualan.cust_id = m_customer.cust_id 
                WHERE
                    t_nota_penjualan.cancel_transaksi_id IS NULL 
                    AND m_customer.cust_id = $cust_id 
                GROUP BY
                    m_customer.cust_id,
                    m_customer.cust_max_plafond
                ";

		return Yii::$app->db->createCommand($sql)->queryOne()['sisa_plafon'];
	}

    /**
     * @throws Exception
     */
    public static function getOPAktif($cust_id){
		$ret = 0;
		$sql = "SELECT
                    t_op_ko_detail.*,
                    t_op_ko.jenis_produk 
                FROM
                    t_op_ko
                    JOIN t_op_ko_detail ON t_op_ko_detail.op_ko_id = t_op_ko.op_ko_id
                    LEFT JOIN t_nota_penjualan ON t_nota_penjualan.op_ko_id = t_op_ko.op_ko_id 
                WHERE
                    t_op_ko.cancel_transaksi_id IS NULL 
                    AND t_op_ko.cust_id = $cust_id 
                    AND nota_penjualan_id IS NULL 
                    AND t_op_ko.sistem_bayar = 'Tempo'
                ";

		$mod = Yii::$app->db->createCommand($sql)->queryAll();

        if(!empty($mod)) foreach ($mod as $op) {
            $ret += $op['harga_jual'] * ( in_array($op['jenis_produk'], self::$filter_jenis_produk) ? $op['qty_kecil'] : $op['kubikasi']);
        }
		return $ret;
	}

    /**
     * @throws Exception
     */
    public static function getSisaPiutang($cust_id){
		$sql = "SELECT
                (
                    COALESCE (
                        SUM ( t_nota_penjualan.total_bayar ) - COALESCE ( ( SELECT SUM ( bayar ) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id ), 0 ),
                        0 
                    ) 
                ) AS sisa_piutang 
            FROM
                m_customer
                LEFT JOIN t_nota_penjualan ON t_nota_penjualan.cust_id = m_customer.cust_id 
            WHERE
                t_nota_penjualan.cancel_transaksi_id IS NULL 
                AND m_customer.cust_id = $cust_id 
            GROUP BY
                m_customer.cust_id,
                m_customer.cust_max_plafond
	        ";

		return Yii::$app->db->createCommand($sql)->queryOne()['sisa_piutang'];
	}
	
	public static function getShipmentTo($cust_id)
    {
		$mod = self::findOne($cust_id);
        return $mod->cust_an_nama.". ".$mod->cust_an_alamat;
    }

    /**
     * @throws Exception
     */
    public static function getOptionListInvoiceLokal()
    {
        $sql = "SELECT
                    m_customer.cust_id,
                    m_customer.cust_an_nama 
                FROM
                    t_nota_penjualan
                    JOIN m_customer ON m_customer.cust_id = t_nota_penjualan.cust_id 
                WHERE
                    jenis_produk IN ( 'JasaKD', 'JasaGesek', 'JasaMoulding' ) 
                GROUP BY
                    1,2
                ";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $ret = [];
        if(count($res)>0) foreach($res as $cust){
            $ret[$cust['cust_id']] = $cust['cust_an_nama'];
        }
        return $ret;
    }

    public static function getOptionListNama()
    {
        return ArrayHelper::map(self::findAll([
            'active' => true, 
            'status_approval' => 'APPROVED'
        ]), 'cust_an_nama', 'cust_an_nama');
    }

    public static function getOptionListCustPO(){
        $res = Yii::$app->db->createCommand("
                    SELECT t_po_ko.kode as kode, m_customer.cust_an_nama, m_customer.cust_pr_nama FROM m_customer
                    LEFT JOIN t_po_ko ON t_po_ko.cust_id = m_customer.cust_id
                    WHERE m_customer.status_approval = 'APPROVED' AND active = true AND t_po_ko.status_approval = 'APPROVED' 
                    AND t_po_ko.status_po = true
                    ORDER BY m_customer.cust_id ASC
                    ")->queryAll();
        $ret = [];
        if(count($res)>0){
            foreach($res as $cust){
                $customer = $cust['cust_pr_nama']?$cust['cust_pr_nama'] . ' - ' .$cust['kode']:$cust['cust_an_nama'] . ' - ' .$cust['kode'];
                $ret[$customer] = $customer;
            }
        }
        return $ret;
    }
}
