<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_penerbit_bl".
 *
 * @property integer $penerbit_bl_id
 * @property string $kode
 * @property string $nama
 * @property string $alamat
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MPenerbitBl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_penerbit_bl';
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
            [['kode', 'nama', 'alamat', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['alamat'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['kode'], 'string', 'max' => 20],
            [['nama'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'penerbit_bl_id' => 'Penerbit Bl',
                'kode' => 'Kode',
                'nama' => 'Nama',
                'alamat' => 'Alamat',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }
	
	public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('nama ASC')->all();
		$ret = [];
		foreach($res as $i => $pbl){
			$ret[$pbl['penerbit_bl_id']] = $pbl['nama']." - ".$pbl['alamat'];
			
		}
        return $ret;
    }
}
