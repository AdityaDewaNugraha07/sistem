<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_realisasidinas_grader".
 *
 * @property integer $realisasidinas_grader_id
 * @property string $kode
 * @property string $tanggal
 * @property string $periode_awal
 * @property string $periode_akhir
 * @property string $status
 * @property string $keterangan
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
 * @property TCancelTransaksi $cancelTransaksi
 * @property TDkg $dkg
 */
class TRealisasidinasGrader extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $graderlog_nm,$nominal_in,$nominal_out,$reff_no;
    public static function tableName()
    {
        return 't_realisasidinas_grader';
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
            [['kode', 'tanggal', 'periode_awal', 'periode_akhir', 'graderlog_id', 'dkg_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'periode_awal', 'periode_akhir', 'created_at', 'updated_at','total_realisasi','saldo_awal', 'saldo_akhir'], 'safe'],
            [['keterangan'], 'string'],
            [[], 'number'],
            [['graderlog_id', 'dkg_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['kode', 'status'], 'string', 'max' => 50],
            [['graderlog_id'], 'exist', 'skipOnError' => true, 'targetClass' => MGraderlog::className(), 'targetAttribute' => ['graderlog_id' => 'graderlog_id']],
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
                'realisasidinas_grader_id' => Yii::t('app', 'Realisasidinas Grader'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'periode_awal' => Yii::t('app', 'Periode Awal'),
                'periode_akhir' => Yii::t('app', 'Periode Akhir'),
                'status' => Yii::t('app', 'Status'),
                'keterangan' => Yii::t('app', 'Keterangan'),
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
