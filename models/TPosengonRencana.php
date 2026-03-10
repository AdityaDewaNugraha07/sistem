<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_posengon_rencana".
 *
 * @property integer $posengon_rencana_id
 * @property string $kode
 * @property string $tanggal
 * @property string $tanggal_pengiriman_awal
 * @property string $tanggal_pengiriman_akhir
 * @property integer $menyetujui
 * @property integer $mengetahui
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MPegawai $mengetahui0
 */
class TPosengonRencana extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $menyetujui_display,$kode_permintaan,$pmr_id,$status_approval;
    public static function tableName()
    {
        return 't_posengon_rencana';
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
            [['kode', 'tanggal', 'tanggal_pengiriman_awal', 'tanggal_pengiriman_akhir', 'menyetujui', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'tanggal_pengiriman_awal', 'tanggal_pengiriman_akhir', 'created_at', 'updated_at'], 'safe'],
            [['menyetujui', 'mengetahui', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['kode', 'status'], 'string', 'max' => 50],
            [['mengetahui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['mengetahui' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'posengon_rencana_id' => 'Posengon Rencana',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'tanggal_pengiriman_awal' => 'Tanggal Pengiriman Awal',
                'tanggal_pengiriman_akhir' => 'Tanggal Pengiriman Akhir',
                'menyetujui' => 'Menyetujui',
                'mengetahui' => 'Mengetahui',
                'status' => 'Status',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMengetahui0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'mengetahui']);
    }
    
    public static function getOptionList()
    {
        $res = self::find()->where("cancel_transaksi_id IS NULL")->orderBy('posengon_rencana_id DESC')->all();
        $ret['AUTO GENERATE'] = "RENCANA BARU (AUTO GENERATE)";
        if(count($res)>0){
            foreach($res as $i => $r){
                $ret[$r->posengon_rencana_id] = $r->kode." (".\app\components\DeltaFormatter::formatDateTimeForUser2($r->tanggal_pengiriman_awal)."-".\app\components\DeltaFormatter::formatDateTimeForUser2($r->tanggal_pengiriman_akhir).")";
            }
        }
        return $ret;
    }
    
    public function getPembuat()
    {
        $ret = "";
        $user = MUser::findOne($this->created_by);
        $pegawai = MPegawai::findOne($user->pegawai_id);
        return $pegawai->pegawai_nama;
    }
}
