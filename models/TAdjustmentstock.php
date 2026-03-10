<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_adjustmentstock".
 *
 * @property integer $adjustmentstock_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $bhp_id
 * @property double $qty_in
 * @property double $qty_out
 * @property double $qty_fisik
 * @property double $qty_selisih
 * @property double $rp_selisih
 * @property double $saldo_sebelum_adjust
 * @property double $saldo_setelah_adjust
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status
 * @property integer $cancel_transaksi_id
 *
 * @property MBrgBhp $bhp
 * @property TCancelTransaksi $cancelTransaksi
 */
class TAdjustmentstock extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $bhp_nm;
    public static function tableName()
    {
        return 't_adjustmentstock';
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
            [['kode', 'tanggal', 'bhp_id', 'qty_in', 'qty_out', 'qty_fisik', 'qty_selisih', 'rp_selisih', 'saldo_sebelum_adjust', 'saldo_setelah_adjust', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['bhp_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['qty_in', 'qty_out', 'qty_fisik', 'qty_selisih', 'rp_selisih', 'saldo_sebelum_adjust', 'saldo_setelah_adjust'], 'number'],
            [['keterangan'], 'string'],
            [['kode', 'status'], 'string', 'max' => 50],
            [['bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgBhp::className(), 'targetAttribute' => ['bhp_id' => 'bhp_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'adjustmentstock_id' => Yii::t('app', 'Adjustmentstock'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'bhp_id' => Yii::t('app', 'Bhp'),
                'qty_in' => Yii::t('app', 'Qty In'),
                'qty_out' => Yii::t('app', 'Qty Out'),
                'qty_fisik' => Yii::t('app', 'Qty Fisik'),
                'qty_selisih' => Yii::t('app', 'Qty Selisih'),
                'rp_selisih' => Yii::t('app', 'Rp Selisih'),
                'saldo_sebelum_adjust' => Yii::t('app', 'Saldo Sebelum Adjust'),
                'saldo_setelah_adjust' => Yii::t('app', 'Saldo Setelah Adjust'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'status' => Yii::t('app', 'Status'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBhp()
    {
        return $this->hasOne(MBrgBhp::className(), ['bhp_id' => 'bhp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }
} 