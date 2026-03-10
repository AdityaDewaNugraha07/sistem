<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_persediaan_bhp_sub".
 *
 * @property integer $persediaan_bhp_sub_id
 * @property integer $bhp_id
 * @property string $waktu_transaksi
 * @property double $qty_in
 * @property double $qty_out
 * @property string $keterangan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $reff_no
 * @property integer $reff_detail_id
 * @property string $tgl_transaksi
 * @property string $deskripsi
 */
class HPersediaanBhpSub extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $bhp_group,$per_tanggal,$bhp_nm,$current,$available;
	public $tgl_awal,$tgl_akhir,$total_qty;
    public static function tableName()
    {
        return 'h_persediaan_bhp_sub';
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
            [['bhp_id', 'created_by', 'updated_by', 'reff_detail_id'], 'integer'],
            [['waktu_transaksi', 'created_at', 'updated_at', 'tgl_transaksi'], 'safe'],
            [['qty_in', 'qty_out'], 'number'],
            [['keterangan', 'deskripsi'], 'string'],
            [['active'], 'boolean'],
            [['reff_no'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'persediaan_bhp_sub_id' => 'Persediaan Bhp Sub ID',
            'bhp_id' => 'Bhp ID',
            'waktu_transaksi' => 'Waktu Transaksi',
            'qty_in' => 'Qty In',
            'qty_out' => 'Qty Out',
            'keterangan' => 'Keterangan',
            'active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'reff_no' => 'Reff No',
            'reff_detail_id' => 'Reff Detail ID',
            'tgl_transaksi' => 'Tgl Transaksi',
            'deskripsi' => 'Deskripsi',
        ];
    }

    public function updateStokPersediaan($modParams,$reff_no,$reff_detail_id,$tgl_transaksi){
        $model = new HPersediaanBhp();
        $model->bhp_id = $modParams->bhp_id;
        $model->waktu_transaksi = date('Y-m-d H:i:s');
        $model->qty_in = $modParams->qty_in;
        $model->qty_out = $modParams->qty_out;
        $model->keterangan = isset($modParams->keterangan)?$modParams->keterangan:"";
        $model->reff_no = !empty($reff_no)?$reff_no:"";
        $model->reff_detail_id = !empty($reff_detail_id)?$reff_detail_id:"";
        $model->tgl_transaksi = $tgl_transaksi;
        if($model->validate()){
            if($model->save()){
                return true;
            }else{
                return false;
            }
        }
    }
     public static function getCurrentStockPerItem($reff_detail_id){
		$sql = "SELECT 
                    concat(h_persediaan_bhp_sub.bhp_id, '-', h_persediaan_bhp_sub.reff_detail_id) AS itemnumber,bhp_id,reff_detail_id, 
					SUM(qty_in - qty_out) AS jumlah
				FROM h_persediaan_bhp_sub 
				WHERE reff_detail_id = $reff_detail_id
				GROUP BY 1,2,3";
		$mod = Yii::$app->db->createCommand($sql)->queryOne();
		return $mod;
	}
}
