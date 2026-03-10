<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_packinglist_container".
 *
 * @property integer $packinglist_container_id
 * @property integer $packinglist_id
 * @property string $seal_no
 * @property string $order_kode
 * @property string $container_kode
 * @property double $container_no
 * @property double $bundles_no
 * @property string $partition_kode
 * @property string $grade
 * @property string $jenis_kayu
 * @property string $glue
 * @property string $profil_kayu
 * @property string $kondisi_kayu
 * @property double $width
 * @property string $width_unit
 * @property double $length
 * @property string $length_unit
 * @property double $thick
 * @property string $thick_unit
 * @property double $pcs
 * @property double $volume
 * @property double $gross_weight
 * @property double $nett_weight
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $container_size
 *
 * @property TCancelTransaksi $cancelTransaksi
 * @property TPackinglist $packinglist
 */ 
class TPackinglistContainer extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	
	public $thick_rdm,$width_rdm,$length_rdm,$volume_display;
	public $qty_besar,$qty_kecil_realisasi,$qty_besar_realisasi,$pcs_realisasi,$volume_realisasi,$kubikasi_realisasi,$produk_id;
    public static function tableName()
    {
        return 't_packinglist_container';
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
            [['packinglist_id', 'container_no', 'bundles_no', 'width', 'width_unit', 'length', 'length_unit', 'thick', 'thick_unit', 'pcs', 'volume', 'gross_weight', 'nett_weight', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['packinglist_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['container_no', 'bundles_no', 'width', 'length', 'thick', 'pcs', 'volume', 'gross_weight', 'nett_weight'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['seal_no', 'order_kode', 'container_kode', 'partition_kode', 'width_unit', 'length_unit', 'thick_unit', 'status','container_size', 'lot_code'], 'string', 'max' => 50],
            [['grade', 'jenis_kayu', 'glue', 'profil_kayu', 'kondisi_kayu'], 'string', 'max' => 100],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['packinglist_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPackinglist::className(), 'targetAttribute' => ['packinglist_id' => 'packinglist_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'packinglist_container_id' => Yii::t('app', 'Packinglist Container'),
                'packinglist_id' => Yii::t('app', 'Packinglist'),
                'seal_no' => Yii::t('app', 'Seal No'),
                'order_kode' => Yii::t('app', 'Order Kode'),
                'container_kode' => Yii::t('app', 'Container Kode'),
                'container_no' => Yii::t('app', 'Container No'),
                'bundles_no' => Yii::t('app', 'Bundles No'),
                'partition_kode' => Yii::t('app', 'Partition Code'),
				'grade' => Yii::t('app', 'Grade'),
                'jenis_kayu' => Yii::t('app', 'Jenis Kayu'),
                'glue' => Yii::t('app', 'Glue'),
                'profil_kayu' => Yii::t('app', 'Profil Kayu'),
                'kondisi_kayu' => Yii::t('app', 'Kondisi Kayu'),
                'width' => Yii::t('app', 'Width'),
                'width_unit' => Yii::t('app', 'Width Unit'),
                'length' => Yii::t('app', 'Length'),
                'length_unit' => Yii::t('app', 'Length Unit'),
                'thick' => Yii::t('app', 'Thick'),
                'thick_unit' => Yii::t('app', 'Thick Unit'),
                'pcs' => Yii::t('app', 'Pcs'),
                'volume' => Yii::t('app', 'Volume'),
                'gross_weight' => Yii::t('app', 'Gross Weight'),
                'nett_weight' => Yii::t('app', 'Nett Weight'),
                'status' => Yii::t('app', 'Status'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
				'container_size' => Yii::t('app', 'Container Size'),
				'lot_code' => Yii::t('app', 'Lot Code'),
        ];
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
    public function getPackinglist()
    {
        return $this->hasOne(TPackinglist::className(), ['packinglist_id' => 'packinglist_id']);
    }
	
	public static function getOptionListSPM()
    {
		$return = [];
        $mod = Yii::$app->db->createCommand("
			SELECT t_packinglist_container.packinglist_id, t_packinglist.nomor, t_packinglist_container.container_no FROM t_packinglist_container
			JOIN t_packinglist ON t_packinglist.packinglist_id = t_packinglist_container.packinglist_id
			LEFT JOIN t_spm_ko ON t_spm_ko.packinglist_id = t_packinglist.packinglist_id
			WHERE t_packinglist.cancel_transaksi_id IS NULL AND t_packinglist.status = 'FINAL'
			GROUP BY 1,2,3
			ORDER BY t_packinglist_container.packinglist_id DESC, container_no DESC
			")->queryAll();
        foreach($mod as $i => $val){
            $return[$val['packinglist_id']."-".$val['container_no']] = $val['nomor']." - Cont. ".$val['container_no'];
        }
        return $return;
    }
}
