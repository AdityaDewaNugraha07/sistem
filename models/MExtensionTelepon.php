<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_extension_telepon".
 *
 * @property integer $extension_telepon_id
 * @property string $ext_kode
 * @property string $bagian
 * @property string $nama
 * @property integer $pegawai_id
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MExtensionTelepon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_extension_telepon';
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
            [['ext_kode', 'bagian', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['pegawai_id', 'created_by', 'updated_by'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['ext_kode', 'bagian', 'nama'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'extension_telepon_id' => 'Extension Telepon',
                'ext_kode' => 'Ext Kode',
                'bagian' => 'Bagian',
                'nama' => 'Nama',
                'pegawai_id' => 'Pegawai',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }
}
