<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_mutasi_sengon".
 *
 * @property integer $mutasi_sengon_id
 * @property string $kode
 * @property string $tanggal
 * @property string $jenis_mutasi
 * @property string $dari
 * @property string $ke
 * @property double $panjang
 * @property double $diameter
 * @property double $pcs
 * @property double $m3
 * @property string $keterangan
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property string $jenis_log
 */
class TMutasiSengon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal,$tgl_akhir;
    public static function tableName()
    {
        return 't_mutasi_sengon';
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
            [['kode', 'tanggal', 'jenis_mutasi', 'dari', 'ke', 'diameter', 'panjang', 'pcs', 'm3', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at', 'diameter', 'pcs', 'm3'], 'safe'],
            [['panjang'], 'number'],
            [['keterangan'], 'string'],
            [['created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['kode', 'dari', 'ke', 'status','jenis_mutasi','jenis_log'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'mutasi_sengon_id' => 'Mutasi Sengon',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'jenis_mutasi' => 'Jenis Mutasi',
                'dari' => 'Dari',
                'ke' => 'Ke',
                'diameter' => 'Diameter',
                'pcs' => 'Pcs',
                'm3' => 'M3',
                'keterangan' => 'Keterangan',
                'status' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'jenis_log' => 'Jenis Log',
        ];
    }
}
