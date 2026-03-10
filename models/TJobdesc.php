<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_jobdesc".
 *
 * @property integer $jobdesc_id
 * @property integer $pegawai_id
 * @property string $nama_file
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TJobdesc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    // tambahan variable untuk upload file
    public $file;

    public static function tableName()
    {
        return 't_jobdesc';
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
            [['pegawai_id', 'nama_file', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['pegawai_id'], 'integer'],
            [['nama_file'], 'string'],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'jobdesc_id' => 'Jobdesc',
                'pegawai_id' => 'Pegawai',
                'nama_file' => 'Nama File',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @inheritdoc
     * @return TJobdescQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TJobdescQuery(get_called_class());
    }

    public function getPegawai()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawai_id']);
    }

    /*public function getFile()
    {
        return $this->hasOne(TFile::className(), ['file' => 'file']);
    }*/
}
