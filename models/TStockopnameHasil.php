<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_stockopname_hasil".
 *
 * @property integer $stockopname_hasil_id
 * @property integer $stockopname_agenda_id
 * @property string $kode
 * @property string $tanggal
 * @property string $jenis_produk
 * @property double $total_fisik_palet
 * @property double $total_fisik_m3
 * @property double $total_fisik_rp
 * @property double $total_system_palet
 * @property double $total_system_m3
 * @property double $total_system_rp
 * @property double $fisik_yes_system_yes_palet
 * @property double $fisik_yes_system_yes_m3
 * @property double $fisik_yes_system_no_palet
 * @property double $fisik_yes_system_no_m3
 * @property double $fisik_no_system_yes_palet
 * @property double $fisik_no_system_yes_m3
 * @property string $status
 * @property string $keterangan
 * @property integer $by_prepared
 * @property integer $by_gmopr
 * @property integer $by_dirut
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MPegawai $byPrepared
 * @property MPegawai $byGmopr
 * @property MPegawai $byDirut
 * @property TStockopnameAgenda $stockopnameAgenda
 */
class TStockopnameHasil extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $lanjut_adjustment,$by_gmopr_display,$by_dirut_display;
    public static function tableName()
    {
        return 't_stockopname_hasil';
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
            [['stockopname_agenda_id', 'kode', 'tanggal', 'created_at', 'created_by', 'updated_at', 'updated_by' ,'jenis_produk'], 'required'],
            [['stockopname_agenda_id', 'by_prepared', 'by_gmopr', 'by_dirut', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['total_fisik_palet', 'total_fisik_m3', 'total_fisik_rp', 'total_system_palet', 'total_system_m3', 'total_system_rp', 'total_undefined_palet', 'total_undefined_m3', 'total_undefined_rp', 'fisik_yes_system_yes_palet', 'fisik_yes_system_yes_m3', 'fisik_yes_system_yes_rp', 'fisik_yes_system_no_palet', 'fisik_yes_system_no_m3', 'fisik_yes_system_no_rp', 'fisik_no_system_yes_palet', 'fisik_no_system_yes_m3', 'fisik_no_system_yes_rp'], 'number'],
            [['keterangan','undefined_reff','jenis_produk'], 'string'],
            [['kode', 'status'], 'string', 'max' => 50],
            [['by_prepared'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['by_prepared' => 'pegawai_id']],
            [['by_gmopr'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['by_gmopr' => 'pegawai_id']],
            [['by_dirut'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['by_dirut' => 'pegawai_id']],
            [['stockopname_agenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => TStockopnameAgenda::className(), 'targetAttribute' => ['stockopname_agenda_id' => 'stockopname_agenda_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'stockopname_hasil_id' => 'Stockopname Hasil',
                'stockopname_agenda_id' => 'Stockopname Agenda',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'total_fisik_palet' => 'Total Fisik Palet',
                'total_fisik_m3' => 'Total Fisik M3',
                'total_fisik_rp' => 'Total Fisik Rp',
                'total_system_palet' => 'Total System Palet',
                'total_system_m3' => 'Total System M3',
                'total_system_rp' => 'Total System Rp',
                'fisik_yes_system_yes_palet' => 'Fisik Yes System Yes Palet',
                'fisik_yes_system_yes_m3' => 'Fisik Yes System Yes M3',
                'fisik_yes_system_no_palet' => 'Fisik Yes System No Palet',
                'fisik_yes_system_no_m3' => 'Fisik Yes System No M3',
                'fisik_no_system_yes_palet' => 'Fisik No System Yes Palet',
                'fisik_no_system_yes_m3' => 'Fisik No System Yes M3',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'by_prepared' => 'By Prepared',
                'by_gmopr' => 'By Gmopr',
                'by_dirut' => 'By Dirut',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'jenis_produk' => 'Jenis Produk',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getByPrepared()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'by_prepared']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getByGmopr()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'by_gmopr']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getByDirut()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'by_dirut']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockopnameAgenda()
    {
        return $this->hasOne(TStockopnameAgenda::className(), ['stockopname_agenda_id' => 'stockopname_agenda_id']);
    }
}
