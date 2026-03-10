<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_stockopname_agenda".
 *
 * @property integer $stockopname_agenda_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $penanggungjawab
 * @property integer $by_kadivacc
 * @property integer $by_kanitgud
 * @property integer $by_kadivmkt
 * @property string $status
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property TStockopname[] $tStockopnames
 * @property MPegawai $penanggungjawab0
 * @property MPegawai $byKadivacc
 * @property MPegawai $byKanitgud
 * @property MPegawai $byKadivmkt
 * @property TStockopnameHasil[] $tStockopnameHasils
 * @property TStockopnamePeserta[] $tStockopnamePesertas
 */
class TStockopnameAgenda extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $penanggungjawab_display,$by_kadivacc_display,$by_kanitgud_display,$by_kadivmkt_display;
    public static function tableName()
    {
        return 't_stockopname_agenda';
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
            [['kode', 'tanggal', 'penanggungjawab', 'by_kadivacc', 'by_kanitgud', 'by_kadivmkt', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['penanggungjawab', 'by_kadivacc', 'by_kanitgud', 'by_kadivmkt', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['keterangan'], 'string'],
            [['kode', 'status'], 'string', 'max' => 50],
            [['penanggungjawab'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['penanggungjawab' => 'pegawai_id']],
            [['by_kadivacc'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['by_kadivacc' => 'pegawai_id']],
            [['by_kanitgud'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['by_kanitgud' => 'pegawai_id']],
            [['by_kadivmkt'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['by_kadivmkt' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'stockopname_agenda_id' => 'Stockopname Agenda',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'penanggungjawab' => 'Penanggungjawab',
                'by_kadivacc' => 'By Kadivacc',
                'by_kanitgud' => 'By Kanitgud',
                'by_kadivmkt' => 'By Kadivmkt',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTStockopnames()
    {
        return $this->hasMany(TStockopname::className(), ['stockopname_agenda_id' => 'stockopname_agenda_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenanggungjawab0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'penanggungjawab']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getByKadivacc()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'by_kadivacc']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getByKanitgud()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'by_kanitgud']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getByKadivmkt()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'by_kadivmkt']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTStockopnameHasils()
    {
        return $this->hasMany(TStockopnameHasil::className(), ['stockopname_agenda_id' => 'stockopname_agenda_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTStockopnamePesertas()
    {
        return $this->hasMany(TStockopnamePeserta::className(), ['stockopname_agenda_id' => 'stockopname_agenda_id']);
    }
    
    public static function getOptionListScan()
    {
        $ret = [];
        $res = self::find()->where("cancel_transaksi_id IS NULL")->orderBy('created_at DESC')->all();
        if(count($res)>0){
            foreach($res as $i => $mod){
                $ret[$mod->stockopname_agenda_id] = $mod->kode." - ".$mod->status;
            }
        }
        return $ret;
    }
}
