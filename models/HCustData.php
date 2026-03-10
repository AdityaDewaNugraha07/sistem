<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "m_customer".
 *
 * @property integer $kode_customer
 * @property string $cust_kode
 * @property string $cust_tipe_penjualan
 * @property string $cust_tanggal_join
 * @property boolean $cust_is_pkp
 * @property double $cust_max_plafond
 * @property string $cust_no_npwp
 * @property string $cust_file_npwp
 * @property string $cust_file_ktp
 * @property string $cust_file_photo
 * @property string $cust_an_nama
 * @property string $cust_an_nik
 * @property string $cust_an_jk
 * @property string $cust_an_tgllahir
 * @property string $cust_an_nohp
 * @property string $cust_an_agama
 * @property string $cust_an_alamat
 * @property string $cust_an_email
 * @property string $cust_pr_nama
 * @property string $cust_pr_direktur
 * @property string $cust_pr_alamat
 * @property integer $kota_id
 * @property string $cust_pr_phone
 * @property string $cust_pr_fax
 * @property string $cust_pr_email
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status_approval
 * @property string $by_kadiv
 * @property string $by_dirut
 * @property string $approve_reason
 * @property string $reject_reason 
 * @property array|mixed|null $cust_id
 */

class HCustData extends ActiveRecord
{

    public $file1, $file2, $file3;

    public static function tableName()
    {
        return 'h_cust_data';
    }
    
    public function behaviors(){
		return [DeltaGeneralBehavior::className()];
	}

    public function rules()
    {
        return [
            [['cust_kode', 'cust_tipe_penjualan', 'cust_tanggal_join', 'cust_is_pkp', 'cust_max_plafond', 'cust_no_npwp', 'cust_an_nama', 'cust_an_nik', 'cust_an_jk', 'cust_an_tgllahir', 'cust_an_nohp', 'cust_an_agama', 'cust_an_alamat', 'created_at', 'created_by', 'updated_at', 'updated_by', 'cust_id'], 'required'],
            [['cust_tanggal_join', 'cust_an_tgllahir', 'created_at', 'updated_at'], 'safe'],
            [['cust_is_pkp', 'active'], 'boolean'],
            [['kota_id', 'created_by', 'updated_by', 'by_kadiv', 'by_dirut', 'cust_id'], 'integer'],
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


    public function attributeLabels()
    {
        return [
            'cust_id_id' => Yii::t('app', 'Customer'),
            'cust_id' => Yii::t('app', 'Customer History'),
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
}
