<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_log_bayar_muat".
 *
 * @property integer $log_bayar_muat_id
 * @property integer $log_kontrak_id
 * @property integer $pengajuan_pembelianlog_id
 * @property integer $loglist_id
 * @property string $kode
 * @property string $tanggal
 * @property double $harga_m3
 * @property double $total_volume
 * @property double $total_bayar
 * @property double $total_dp
 * @property double $total_harga
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $reff_no
 *
 * @property TLogKontrak $logKontrak
 */
class TLogBayarMuat extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $nomor_kontrak, $tongkang, $kode_po, $kode_keputusan,$loglist_kode;
    public static function tableName()
    {
        return 't_log_bayar_muat';
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
            [['log_kontrak_id', 'pengajuan_pembelianlog_id', 'loglist_id', 'kode', 'reff_no', 'tanggal', 'created_at', 'created_by', 'updated_at', 'updated_by', 'total_volume', 'harga_m3', 'total_bayar', 'total_dp', 'total_harga'], 'required'],
            [['log_kontrak_id', 'pengajuan_pembelianlog_id', 'loglist_id', 'created_by', 'updated_by'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['keterangan'], 'string'],
            [['kode','reff_no'], 'string', 'max' => 50],
            [['log_kontrak_id'], 'exist', 'skipOnError' => true, 'targetClass' => TLogKontrak::className(), 'targetAttribute' => ['log_kontrak_id' => 'log_kontrak_id']],
            [['pengajuan_pembelianlog_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPengajuanPembelianlog::className(), 'targetAttribute' => ['pengajuan_pembelianlog_id' => 'pengajuan_pembelianlog_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'log_bayar_muat_id' => Yii::t('app', 'Log Bayar Muat'),
                'log_kontrak_id' => Yii::t('app', 'Log Kontrak'),
                'pengajuan_pembelianlog_id' => Yii::t('app', 'Keputusan Pembelian'),
                'loglist_id' => Yii::t('app', 'Log List'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'harga_m3' => Yii::t('app', 'Harga M3'),
                'total_volume' => Yii::t('app', 'Total Volume'),
                'total_bayar' => Yii::t('app', 'Total Bayar'),
                'total_dp' => Yii::t('app', 'Total Dp'),
                'total_harga' => Yii::t('app', 'Total Harga'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
				'reff_no' => Yii::t('app', 'Reff No'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogKontrak()
    {
        return $this->hasOne(TLogKontrak::className(), ['log_kontrak_id' => 'log_kontrak_id']);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoglist()
    {
        return $this->hasOne(TLoglist::className(), ['loglist_id' => 'loglist_id']);
    } 
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherPengeluaran()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengaluaran_id' => 'voucher_pengaluaran_id']);
    } 
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanPembelianlog()
    {
        return $this->hasOne(TPengajuanPembelianlog::className(), ['pengajuan_pembelianlog_id' => 'pengajuan_pembelianlog_id']);
    } 
}
