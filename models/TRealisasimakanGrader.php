<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_realisasimakan_grader".
 *
 * @property integer $realisasimakan_grader_id
 * @property string $kode
 * @property string $tanggal
 * @property string $periode_awal
 * @property string $periode_akhir
 * @property string $status
 * @property string $keterangan
 * @property integer $wilayah_dinas_id
 * @property integer $wilayah_dinas_makan
 * @property integer $wilayah_dinas_pulsa
 * @property integer $qty_hari
 * @property double $saldo_awal
 * @property double $total_realisasi
 * @property double $saldo_akhir
 * @property integer $graderlog_id
 * @property integer $dkg_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MGraderlog $graderlog
 * @property MWilayahDinas $wilayahDinas
 * @property TCancelTransaksi $cancelTransaksi
 * @property TDkg $dkg
 */
class TRealisasimakanGrader extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $graderlog_nm,$reff_no,$nominal_in,$nominal_out,$wilayah_dinas_nama,$tempat_tujuan;
    public static function tableName()
    {
        return 't_realisasimakan_grader';
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
            [['kode', 'tanggal', 'periode_awal', 'periode_akhir', 'wilayah_dinas_id', 'wilayah_dinas_makan', 'wilayah_dinas_pulsa', 'graderlog_id', 'dkg_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'periode_awal', 'periode_akhir', 'created_at', 'updated_at', 'total_realisasi','saldo_awal', 'saldo_akhir', 'wilayah_dinas_makan', 'wilayah_dinas_pulsa',], 'safe'],
            [['keterangan'], 'string'],
            [['wilayah_dinas_id', 'qty_hari', 'graderlog_id', 'dkg_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [[], 'number'],
            [['kode', 'status'], 'string', 'max' => 50],
            [['graderlog_id'], 'exist', 'skipOnError' => true, 'targetClass' => MGraderlog::className(), 'targetAttribute' => ['graderlog_id' => 'graderlog_id']],
            [['wilayah_dinas_id'], 'exist', 'skipOnError' => true, 'targetClass' => MWilayahDinas::className(), 'targetAttribute' => ['wilayah_dinas_id' => 'wilayah_dinas_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['dkg_id'], 'exist', 'skipOnError' => true, 'targetClass' => TDkg::className(), 'targetAttribute' => ['dkg_id' => 'dkg_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'realisasimakan_grader_id' => Yii::t('app', 'Realisasimakan Grader'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'periode_awal' => Yii::t('app', 'Periode Awal'),
                'periode_akhir' => Yii::t('app', 'Periode Akhir'),
                'status' => Yii::t('app', 'Status'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'wilayah_dinas_id' => Yii::t('app', 'Wilayah Dinas'),
                'wilayah_dinas_makan' => Yii::t('app', 'Wilayah Dinas Makan'),
                'wilayah_dinas_pulsa' => Yii::t('app', 'Wilayah Dinas Pulsa'),
                'qty_hari' => Yii::t('app', 'Qty Hari'),
                'saldo_awal' => Yii::t('app', 'Saldo Awal'),
                'total_realisasi' => Yii::t('app', 'Total Realisasi'),
                'saldo_akhir' => Yii::t('app', 'Saldo Akhir'),
                'graderlog_id' => Yii::t('app', 'Graderlog'),
                'dkg_id' => Yii::t('app', 'Dkg'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGraderlog()
    {
        return $this->hasOne(MGraderlog::className(), ['graderlog_id' => 'graderlog_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWilayahDinas()
    {
        return $this->hasOne(MWilayahDinas::className(), ['wilayah_dinas_id' => 'wilayah_dinas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDkg()
    {
        return $this->hasOne(TDkg::className(), ['dkg_id' => 'dkg_id']);
    }
}
