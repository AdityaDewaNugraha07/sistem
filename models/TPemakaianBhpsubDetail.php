<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pemakaian_bhpsub_detail".
 *
 * @property integer $pemakaian_bhpsub_detail_id
 * @property integer $pemakaian_bhpsub_id
 * @property integer $terima_bhp_sub_id
 * @property integer $bhp_id
 * @property double $qty
 * @property double $harga_peritem
 * @property integer $dept_peruntukan
 * @property integer $asset_peruntukan
 * @property string $reff_no
 * @property string $keterangan
 * @property integer $bhp_id
 * @property integer $cancel_transaksi_id
 *
 * @property MDepartement $deptPeruntukan
 * @property TCancelTransaksi $cancelTransaksi
 * @property TPemakaianBhpsub $pemakaianBhpsub
 * @property TTerimaBhpSub $terimaBhpSub
 */
class TPemakaianBhpsubDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    // public $bhp_id;
    public static function tableName()
    {
        return 't_pemakaian_bhpsub_detail';
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
            [['pemakaian_bhpsub_id', 'terima_bhp_sub_id', 'dept_peruntukan'], 'required'],
            [['pemakaian_bhpsub_id', 'terima_bhp_sub_id', 'dept_peruntukan', 'asset_peruntukan', 'bhp_id'], 'integer'],
            [['qty', 'harga_peritem'], 'number'],
            [['keterangan'], 'string'],
            [['reff_no'], 'string', 'max' => 50],
            [['dept_peruntukan'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['dept_peruntukan' => 'departement_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['pemakaian_bhpsub_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPemakaianBhpsub::className(), 'targetAttribute' => ['pemakaian_bhpsub_id' => 'pemakaian_bhpsub_id']],
            [['terima_bhp_sub_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaBhpSub::className(), 'targetAttribute' => ['terima_bhp_sub_id' => 'terima_bhp_sub_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pemakaian_bhpsub_detail_id' => 'Pemakaian Bhpsub Detail ID',
            'pemakaian_bhpsub_id' => 'Pemakaian Bhpsub ID',
            'terima_bhp_sub_id' => 'Terima Bhp Sub ID',
            'bhp_id' => 'BHP ID',
            'qty' => 'Qty',
            'harga_peritem' => 'Harga Peritem',
            'dept_peruntukan' => 'Dept Peruntukan',
            'asset_peruntukan' => 'Asset Peruntukan',
            'reff_no' => 'Reff No',
            'keterangan' => 'Keterangan',
            'bhp_id' => 'Bhp ID',
            'cancel_transaksi_id' => 'Cancel Transaksi ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeptPeruntukan()
    {
        return $this->hasOne(MDepartement::className(), ['departement_id' => 'dept_peruntukan']);
    }
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }
    public function getPemakaianBhpsub()
    {
        return $this->hasOne(TPemakaianBhpsub::className(), ['pemakaian_bhpsub_id' => 'pemakaian_bhpsub_id']);
    }
    public function getTerimaBhpSub()
    {
        return $this->hasOne(TTerimaBhpSub::className(), ['terima_bhp_sub_id' => 'terima_bhp_sub_id']);
    }
    public function getBhpId()
    {
        return $this->hasOne(MBrgBhp::className(), ['bhp_id' => 'bhp_id']);
    }
    public function getAsset()
    {
        return $this->hasOne(MInventaris::className(), ['inventaris_id' => 'asset_peruntukan']);
    }
    public function getItemStocksub()
    {
        return $this->hasOne(ViewStockItemsub::className(), ['reff_detail_id' => 'terima_bhp_sub_id']);
    }
}