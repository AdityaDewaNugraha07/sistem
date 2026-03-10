<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_packinglist".
 *
 * @property integer $packinglist_id
 * @property string $nomor
 * @property string $tanggal
 * @property integer $op_export_id
 * @property integer $cust_id
 * @property double $total_container
 * @property double $total_bundles
 * @property double $total_pcs
 * @property double $total_volume
 * @property double $total_gross_weight
 * @property double $total_nett_weight
 * @property integer $disetujui
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $notes
 * @property integer $diperiksa
 *
 * @property MCustomer $cust
 * @property MPegawai $disetujui0
 * @property TCancelTransaksi $cancelTransaksi
 * @property TOpExport $opExport
 * @property TPackinglistContainer[] $tPackinglistContainers
 * @property int|mixed|null $revisi_ke
 * @property mixed|string|null $kode
 * @property int|mixed|null $mengetahui
 * @property mixed|null $jenis_produk
 * @property mixed|string|null $shipper
 * @property mixed|null $notify_party
 * @property mixed|string|null $port_of_loading
 * @property mixed|string|null $vessel
 * @property mixed|null $mother_vessel
 * @property mixed|string|null $etd
 * @property mixed|string|null $final_destination
 * @property mixed|string|null $eta
 * @property mixed|string|null $hs_code
 * @property mixed|string|null $origin
 * @property mixed|string|null $svlk_no
 * @property mixed|string|null $vlegal_no
 * @property mixed|string|null $static_product_code
 * @property mixed|string|null $goods_description
 * @property mixed|string|null $harvesting_area
 * @property mixed|null $bundle_partition
 * @property mixed|string|null $status_approval
 * @property mixed|string|null $reason_approval
 */
class TPackinglist extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $applicant_display, $notify_display;
    public $si_shipper, $si_consignee, $si_notify, $si_gd_product, $si_gd_sizegrade, $si_gd_total, $si_gdrepeater, $si_gd_ket, $si_instruction;
    const SCENARIO_PACKINGLIST_EXIM = 'scenarioPackinglistExim';

    public static function tableName()
    {
        return 't_packinglist';
    }

    public function behaviors()
    {
        return [\app\components\DeltaGeneralBehavior::className()];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'tanggal', 'op_export_id', 'cust_id', 'jenis_produk', 'total_container', 'total_bundles', 'total_pcs', 'total_volume', 'total_gross_weight', 'total_nett_weight', 'disetujui', 'mengetahui', 'created_at', 'created_by', 'updated_at', 'updated_by', 'revisi_ke'], 'required'],
            [['tanggal', 'tanggal_packinglistexim', 'created_at', 'updated_at', 'bundle_partition', 'etd', 'eta'], 'safe'],
            [['bundle_partition'], 'boolean'],
            [['op_export_id', 'cust_id', 'disetujui', 'disetujui_finance', 'cancel_transaksi_id', 'created_by', 'updated_by', 'revisi_ke', 'notify_party'], 'integer'],
//            [['total_container', 'total_bundles', 'total_pcs', 'total_volume', 'total_gross_weight', 'total_nett_weight'], 'number'],
            [['kode', 'nomor'], 'string', 'max' => 25],
            [['status', 'jenis_produk', 'hs_code'], 'string', 'max' => 50],
            [['shipper', 'port_of_loading', 'vessel', 'mother_vessel', 'final_destination', 'static_product_code', 'goods_description', 'harvesting_area', 'origin', 'svlk_no', 'vlegal_no', 'notes'], 'string'],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['disetujui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disetujui' => 'pegawai_id']],
            [['disetujui_finance'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disetujui_finance' => 'pegawai_id']],
            [['mengetahui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['mengetahui' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['op_export_id'], 'exist', 'skipOnError' => true, 'targetClass' => TOpExport::className(), 'targetAttribute' => ['op_export_id' => 'op_export_id']],
//			[['nomor','tanggal_packinglistexim','disetujui_finance'], 'required', 'on' => self::SCENARIO_PACKINGLIST_EXIM],
            [['nomor'], 'required', 'on' => self::SCENARIO_PACKINGLIST_EXIM],
            [['diperiksa'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'packinglist_id' => Yii::t('app', 'Packinglist'),
            'kode' => Yii::t('app', 'Kode Packing List'),
            'nomor' => Yii::t('app', 'No. Packing List'),
            'tanggal' => Yii::t('app', 'Tanggal'),
            'tanggal_packinglistexim' => Yii::t('app', 'Tanggal'),
            'op_export_id' => Yii::t('app', 'Op Export'),
            'cust_id' => Yii::t('app', 'Cust'),
            'bundle_partition' => Yii::t('app', 'Buncle Partition'),
            'total_container' => Yii::t('app', 'Number of Container'),
            'total_bundles' => Yii::t('app', 'Total Bundles'),
            'total_pcs' => Yii::t('app', 'Total Pcs'),
            'total_volume' => Yii::t('app', 'Total Volume'),
            'total_gross_weight' => Yii::t('app', 'Total Gross Weight'),
            'total_nett_weight' => Yii::t('app', 'Total Nett Weight'),
            'disetujui' => Yii::t('app', 'Disetujui Oleh'),
            'disetujui_finance' => Yii::t('app', 'Disetujui Finance'),
            'mengetahui' => Yii::t('app', 'Diketahui Oleh'),
            'status' => Yii::t('app', 'Status'),
            'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
            'jenis_produk' => Yii::t('app', 'Jenis Produk'),
            'final_destination' => Yii::t('app', 'Final Destination'),
            'notes' => Yii::t('app', 'Notes'),
            'diperiksa' => Yii::t('app', 'Diperiksa'),
            'status_approval' => Yii::t('app', 'Status Approval'),
            'reason_approval' => Yii::t('app', 'Reason Approval')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'cust_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifyParty()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'notify_party']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisetujui0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'disetujui']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisetujuiFinance0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'disetujui_finance']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMengetahui0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'mengetahui']);
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
    public function getOpExport()
    {
        return $this->hasOne(TOpExport::className(), ['op_export_id' => 'op_export_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTPackinglistContainers()
    {
        return $this->hasMany(TPackinglistContainer::className(), ['packinglist_id' => 'packinglist_id']);
    }


    public static function getOptionListInvoiceBaru($status_inv)
    {
//        $res = \Yii::$app->db->createCommand("SELECT t_packinglist.*, nomor_kontrak FROM t_packinglist
//											JOIN m_customer ON m_customer.cust_id = t_packinglist.cust_id
//											JOIN t_op_export ON t_op_export.op_export_id = t_packinglist.op_export_id
//											LEFT JOIN t_invoice ON t_invoice.packinglist_id = t_packinglist.packinglist_id
//											WHERE t_packinglist.cancel_transaksi_id IS NULL ".( ($status_inv=="FINAL")?"AND t_packinglist.status = 'FINAL'":"" )." AND invoice_id IS NULL
//											ORDER BY t_packinglist.packinglist_id DESC")->queryAll();
        $res = \Yii::$app->db->createCommand("SELECT t_packinglist.*, nomor_kontrak FROM t_packinglist
											JOIN m_customer ON m_customer.cust_id = t_packinglist.cust_id
											JOIN t_op_export ON t_op_export.op_export_id = t_packinglist.op_export_id
											LEFT JOIN t_invoice ON t_invoice.packinglist_id = t_packinglist.packinglist_id
											WHERE t_packinglist.cancel_transaksi_id IS NULL " . (($status_inv == "FINAL") ? "AND t_packinglist.status = 'FINAL'" : "") . " AND invoice_id IS NULL
											ORDER BY t_packinglist.packinglist_id DESC")->queryAll();
        $return = [];
        foreach ($res as $i => $val) {
            $return[$val['packinglist_id']] = $val['nomor'] . " - " . $val['nomor_kontrak'];
        }
        return $return;
    }

    public static function getOptionListPackinglistExim()
    {
        $res = \Yii::$app->db->createCommand("SELECT packinglist_id,t_packinglist.kode, t_op_export.nomor_kontrak, t_packinglist.nomor FROM t_packinglist
											JOIN t_op_export ON t_op_export.op_export_id = t_packinglist.op_export_id
											JOIN t_approval ON t_approval.reff_no = t_packinglist.kode AND t_packinglist.mengetahui = t_approval.assigned_to
											WHERE t_packinglist.cancel_transaksi_id IS NULL AND t_approval.status = '" . TApproval::STATUS_APPROVED . "'
											GROUP BY 1,2,3
											ORDER BY 1 DESC")->queryAll();
        $return = [];
        foreach ($res as $i => $val) {
            if (!empty($val['nomor'])) {
                $nomor = substr($val['nomor'], 0, 5);
            } else {
                $nomor = "xxxxx";
            }
//			$return[$val['packinglist_id']] = $val['kode']."_".$nomor."_(".$val['nomor_kontrak'].")";
            $return[$val['packinglist_id']] = $val['kode'];
        }
        return $return;
    }
}
