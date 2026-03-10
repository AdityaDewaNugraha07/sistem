<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_op_export".
 *
 * @property integer $op_export_id
 * @property string $nomor_kontrak
 * @property string $tanggal
 * @property string $jenis_produk
 * @property integer $cust_id
 * @property string $shipper
 * @property string $shipment_to
 * @property string $port_of_loading
 * @property string $vessel
 * @property string $departure_estimated_date
 * @property string $final_destination
 * @property string $arrival_estimated_date
 * @property string $static_product_code
 * @property string $goods_description
 * @property string $harvesting_area
 * @property string $hs_code
 * @property string $term_of_price
 * @property string $origin
 * @property string $svlk_no
 * @property string $vlegal_no
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MCustomer $cust
 * @property TCancelTransaksi $cancelTransaksi
 * @property TPackinglist[] $tPackinglists
 * @property mixed|string|null $kode
 * @property mixed|null $notify_party
 * @property mixed|null $mother_vessel
 */
class TOpExport extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public  $cust_an_nama, 
            $applicant, 
            $detail_description, 
            $detail_size, 
            $detail_vehicle_type, 
            $detail_vehicle_qty, 
            $detail_vehicle_size,
            $detail_volume, 
            $detail_price, 
            $detail_subtotal, 
            $detail_lot_code, 
            $shipment_time,
            $tgl_awal, 
            $tgl_akhir, 
            $year, 
            $jns_produk;

    public static function tableName()
    {
        return 't_op_export';
    }

    public function behaviors()
    {
        return [\app\components\DeltaGeneralBehavior::class];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nomor_kontrak', 'kode', 'tanggal', 'jenis_produk', 'cust_id', 'shipper', 'shipment_to', 'port_of_loading', 'static_product_code', 'goods_description', 'payment_method', 'term_of_price', 'origin', 'svlk_no', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'departure_estimated_date', 'arrival_estimated_date', 'created_at', 'updated_at'], 'safe'],
            [['cust_id', 'notify_party', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['shipper', 'shipment_to', 'port_of_loading', 'vessel', 'mother_vessel', 'final_destination', 'static_product_code', 'goods_description', 'harvesting_area', 'term_of_price', 'origin', 'svlk_no', 'vlegal_no', 'detail_order', 'detail_qty'], 'string'],
            [['nomor_kontrak', 'hs_code', 'status', 'kode'], 'string', 'max' => 50],
            [['jenis_produk'], 'string', 'max' => 200],
            [['kode'], 'unique'],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::class, 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['notify_party'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::class, 'targetAttribute' => ['notify_party' => 'cust_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::class, 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'op_export_id' => Yii::t('app', 'Op Export'),
            'kode' => Yii::t('app', 'Order No.'),
            'nomor_kontrak' => Yii::t('app', 'Nomor Kontrak'),
            'tanggal' => Yii::t('app', 'Tanggal'),
            'jenis_produk' => Yii::t('app', 'Jenis Produk'),
            'cust_id' => Yii::t('app', 'Cust'),
            'shipper' => Yii::t('app', 'Shipper'),
            'shipment_to' => Yii::t('app', 'Shipment To'),
            'port_of_loading' => Yii::t('app', 'Port of Loading'),
            'vessel' => Yii::t('app', 'Vessel'),
            'mother_vessel' => Yii::t('app', 'Mother Vessel'),
            'departure_estimated_date' => Yii::t('app', 'Dept Estimated'),
            'final_destination' => Yii::t('app', 'Final Destination'),
            'arrival_estimated_date' => Yii::t('app', 'Arrival Estimated'),
            'static_product_code' => Yii::t('app', 'Static Product Code'),
            'goods_description' => Yii::t('app', 'Goods Description'),
            'harvesting_area' => Yii::t('app', 'Harvesting Area'),
            'hs_code' => Yii::t('app', 'Hs Code'),
            'payment_method' => Yii::t('app', 'Payment Method'),
            'term_of_price' => Yii::t('app', 'Term of Price'),
            'origin' => Yii::t('app', 'Origin'),
            'svlk_no' => Yii::t('app', 'SVLK No.'),
            'vlegal_no' => Yii::t('app', 'V-Legal No.'),
            'status' => Yii::t('app', 'Status'),
            'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(MCustomer::class, ['cust_id' => 'cust_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifyParty()
    {
        return $this->hasOne(MCustomer::class, ['cust_id' => 'notify_party']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::class, ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTPackinglists()
    {
        return $this->hasMany(TPackinglist::class, ['op_export_id' => 'op_export_id']);
    }

    public static function getOptionList()
    {
        //        $res = self::find()->where("cancel_transaksi_id IS NULL")->orderBy('created_at DESC')->all();
        $res = Yii::$app->db->createCommand("SELECT t_op_export.* FROM t_op_export 
											LEFT JOIN t_packinglist ON t_packinglist.op_export_id = t_op_export.op_export_id
											WHERE t_op_export.cancel_transaksi_id IS NULL AND t_packinglist.cancel_transaksi_id IS NULL
											ORDER BY t_op_export.created_at DESC")->queryAll();
        $ret = [];
        foreach ($res as $i => $asd) {
            $ret[$asd['op_export_id']] = $asd['kode'] . " - " . $asd['nomor_kontrak'];
        }
        return $ret;
    }
}
