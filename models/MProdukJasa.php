<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_produk_jasa".
 *
 * @property integer $produk_jasa_id
 * @property string $jenis
 * @property string $kode
 * @property string $nama
 * @property string $satuan
 * @property string $keterangan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MProdukJasa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_produk_jasa';
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
            [['jenis', 'kode', 'nama', 'satuan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['keterangan'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['jenis', 'kode'], 'string', 'max' => 50],
            [['nama'], 'string', 'max' => 200],
            [['satuan'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'produk_jasa_id' => 'Produk Jasa',
                'jenis' => 'Jenis',
                'kode' => 'Kode',
                'nama' => 'Nama',
                'satuan' => 'Satuan Harga',
                'keterangan' => 'Keterangan',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }
}
