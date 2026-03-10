<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_penerima_voucher".
 *
 * @property integer $penerima_voucher_id
 * @property string $kode
 * @property string $nama_penerima
 * @property string $nama_perusahaan
 * @property string $penerima_alamat
 * @property string $phone
 * @property string $phone2
 * @property string $fax
 * @property string $contact_person
 * @property string $rekening_bank
 * @property string $rekening_an
 * @property string $rekening_no
 * @property string $email
 * @property string $npwp
 * @property string $keterangan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $nik
 */
class MPenerimaVoucher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_penerima_voucher';
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
            [['kode', 'nama_penerima', 'penerima_alamat', 'phone', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['penerima_alamat', 'keterangan'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['kode', 'phone', 'phone2', 'rekening_bank', 'rekening_an', 'rekening_no', 'email', 'npwp', 'nik'], 'string', 'max' => 50],
            [['nama_penerima', 'nama_perusahaan', 'fax', 'contact_person'], 'string', 'max' => 100],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'penerima_voucher_id' => 'Penerima Voucher',
                'kode' => 'Kode Penerima',
                'nama_penerima' => 'Nama Penerima',
                'nama_perusahaan' => 'Nama Perusahaan',
                'penerima_alamat' => 'Alamat Penerima',
                'phone' => 'Phone',
                'phone2' => 'Phone2',
                'fax' => 'Fax',
                'contact_person' => 'Contact Person',
                'rekening_bank' => 'Rekening Bank',
                'rekening_an' => 'Rekening An',
                'rekening_no' => 'Rekening No',
                'email' => 'Email',
                'npwp' => 'Npwp',
                'keterangan' => 'Keterangan',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'nik' => 'NIK',
        ];
    }
    
    public static function getOptionList()
    {
        $mod = self::find()->where(['active'=>true])->orderBy('created_at DESC')->all();
		$res = [];
		if(count($mod)>0){
			foreach($mod as $i => $data){
				$res[$data->penerima_voucher_id] = $data->nama_penerima.(!empty($data->nama_perusahaan)?' ('.$data->nama_perusahaan.')':"");
			}
		}
        return $res;
    }
}
