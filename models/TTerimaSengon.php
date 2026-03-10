<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_sengon".
 *
 * @property integer $terima_sengon_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $posengon_id
 * @property integer $suplier_id
 * @property string $lokasi_muat
 * @property string $asal_kayu
 * @property string $nopol
 * @property double $total_notaangkut_pcs
 * @property double $total_notaangkut_m3
 * @property double $total_terima_pcs
 * @property double $total_terima_m3
 * @property string $status
 * @property integer $diperiksa_tally
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MPegawai $diperiksaTally
 * @property MSuplier $suplier
 * @property TPosengon $posengon
 * @property TTerimaSengonDetail[] $tTerimaSengonDetails
 */
class TTerimaSengon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file,$posengon_kode,$suplier_nm;
    public static function tableName()
    {
        return 't_terima_sengon';
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
            [['kode', 'tanggal', 'posengon_id', 'suplier_id', 'lokasi_muat', 'asal_kayu', 'nopol', 'status', 'diperiksa_tally', 'created_at', 'created_by', 'updated_at', 'updated_by','file'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['posengon_id', 'suplier_id', 'diperiksa_tally', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['total_notaangkut_pcs', 'total_notaangkut_m3', 'total_terima_pcs', 'total_terima_m3'], 'number'],
            [['kode'], 'string', 'max' => 50],
            [['lokasi_muat', 'asal_kayu'], 'string', 'max' => 200],
            [['nopol', 'status'], 'string', 'max' => 20],
            [['diperiksa_tally'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['diperiksa_tally' => 'pegawai_id']],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['posengon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPosengon::className(), 'targetAttribute' => ['posengon_id' => 'posengon_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'terima_sengon_id' => 'Terima Sengon',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'posengon_id' => 'Posengon',
                'suplier_id' => 'Suplier',
                'lokasi_muat' => 'Lokasi Muat',
                'asal_kayu' => 'Asal Kayu',
                'nopol' => 'Nopol',
                'total_notaangkut_pcs' => 'Total Notaangkut Pcs',
                'total_notaangkut_m3' => 'Total Notaangkut M3',
                'total_terima_pcs' => 'Total Terima Pcs',
                'total_terima_m3' => 'Total Terima M3',
                'status' => 'Status',
                'diperiksa_tally' => 'Diperiksa Tally',
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
    public function getDiperiksaTally()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'diperiksa_tally']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuplier()
    {
        return $this->hasOne(MSuplier::className(), ['suplier_id' => 'suplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosengon()
    {
        return $this->hasOne(TPosengon::className(), ['posengon_id' => 'posengon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaSengonDetails()
    {
        return $this->hasMany(TTerimaSengonDetail::className(), ['terima_sengon_id' => 'terima_sengon_id']);
    }
    
    public static function getOptionList()
    {
        $ret = [];
        $res = self::find()->where("cancel_transaksi_id IS NULL")->orderBy('created_at DESC')->all();
        if(count($res)>0){
			foreach($res as $i => $data){
				$text = ( !empty($data->kode)?$data->kode:""). (!empty($data->suplier->suplier_nm)?', '.$data->suplier->suplier_nm:'');
				if( strlen($text) > 50 ){
					$text = substr($text, 0,50);
					$text .= $text.'...';
				}
				$ret[$data->terima_sengon_id] = $text;
			}
		}
        return $ret;
    }
}
