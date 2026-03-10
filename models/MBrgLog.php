<?php

namespace app\models;

use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "m_brg_log".
 *
 * @property integer $log_id
 * @property string $log_kode
 * @property integer $kayu_id
 * @property string $log_kelompok
 * @property string $log_nama
 * @property string $log_satuan_jual
 * @property string $log_keterangan
 * @property string $log_gambar
 * @property double $log_harga_enduser
 * @property integer $seq
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $range_awal
 * @property integer $range_akhir
 * @property boolean $fsc
 *
 * @property MKayu $kayu
 * @property MHargaLog[] $mHargaLogs
 */
class MBrgLog extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file1, $kayu_nama;
    public static function tableName()
    {
        return 'm_brg_log';
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
            [['kayu_id', 'log_kelompok', 'log_satuan_jual', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['kayu_id', 'seq', 'range_awal', 'range_akhir', 'created_by', 'updated_by'], 'integer'],
            // [['range_awal', 'range_akhir'], 'integer', 'min' => 1, 'message' => ''],
            [['log_keterangan', 'log_gambar'], 'string'],
            [['log_harga_enduser'], 'number'],
            [['active', 'fsc'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['log_kode', 'log_kelompok'], 'string', 'max' => 50],
            [['log_nama'], 'string', 'max' => 200],
            [['log_satuan_jual'], 'string', 'max' => 10],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'log_id' => 'Log',
                'log_kode' => 'Log Kode',
                'kayu_id' => 'Log Kayu',
                'log_kelompok' => 'Log Kelompok',
                'log_nama' => 'Log Nama',
                'log_satuan_jual' => 'Log Satuan Jual',
                'log_keterangan' => 'Log Keterangan',
                'log_gambar' => 'Log Gambar',
                'log_harga_enduser' => 'Log Harga Enduser',
                'seq' => 'Seq',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'range_awal' => 'Range Awal',
                'range_akhir' => 'Range Akhir',
                'fsc' => 'Status FSC',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHargaLog()
    {
        return $this->hasMany(MHargaLog::className(), ['log_id' => 'log_id']);
    }
}