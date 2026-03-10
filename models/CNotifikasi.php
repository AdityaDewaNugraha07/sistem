<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "c_notifikasi".
 *
 * @property integer $notifikasi_id
 * @property string $jenis_notifikasi
 * @property string $nama_notifikasi
 * @property string $reff_penerima
 * @property integer $reff_id_penerima
 * @property string $status
 * @property string $keterangan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property HNotifikasi[] $hNotifikasis
 */
class CNotifikasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const SENDER_EMAIL = 'it.ciptana@gmail.com';
    const SENDER_EMAIL_ALIAS = 'CIS NOTIFIKASI';
    const SUBJECT_EMAIL = 'APPROVAL REQUEST';
    public static function tableName()
    {
        return 'c_notifikasi';
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
            [['jenis_notifikasi', 'nama_notifikasi', 'reff_penerima', 'reff_id_penerima', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['reff_id_penerima', 'created_by', 'updated_by'], 'integer'],
            [['keterangan'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['jenis_notifikasi', 'nama_notifikasi', 'reff_penerima', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'notifikasi_id' => 'Notifikasi',
                'jenis_notifikasi' => 'Jenis Notifikasi',
                'nama_notifikasi' => 'Nama Notifikasi',
                'reff_penerima' => 'Reff Penerima',
                'reff_id_penerima' => 'Reff',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHNotifikasis()
    {
        return $this->hasMany(HNotifikasi::className(), ['c_notifikasi_id' => 'notifikasi_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReffPenerima()
    {
        if($this->reff_penerima == "m_pegawai"){
            return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'reff_id_penerima']);
        }else{
            return false;
        }
    }

    public static function getPenerima($notif_name){
        $return = [];
        switch ($notif_name){
            case "APPROVAL PMR":
            $return["to"]=[]; $return["cc"]=[];
            $modTo = self::find()->where(['nama_notifikasi'=>$notif_name,'keterangan'=>'TO','active'=>true])->all();
            if(!empty($modTo)){
                foreach($modTo as $i => $to){
                    $modPegawai = MPegawai::findOne($to['reff_id_penerima']);
                    if(!empty($modPegawai->email)){
                        $return["to"][$to->notifikasi_id] = $modPegawai->email;
                    }
                }
            }
            $modCc = self::find()->where(['nama_notifikasi'=>$notif_name,'keterangan'=>'CC','active'=>true])->all();
            if(!empty($modCc)){
                foreach($modCc as $i => $cc){
                    $modPegawai = MPegawai::findOne($cc['reff_id_penerima']);
                    if(!empty($modPegawai->email)){
                        $return["cc"][$cc->notifikasi_id] = $modPegawai->email;
                    }
                }
            }
            break;
        }
        return $return;
    }
}
