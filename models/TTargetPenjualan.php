<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_target_penjualan".
 *
 * @property integer $target_penjualan_id
 * @property string $type_penjualan
 * @property string $target_jenis_produk
 * @property double $target_jml
 * @property string $target_jml_satuan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $target_seq
 * @property string $target_periode
 * @property double $target_range
 * @property double $target_range_prosentase
 *
 * @property TTargetPenjualanSales[] $tTargetPenjualanSales
 */
class TTargetPenjualan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal,$tgl_akhir,$tahun_awal,$tahun_akhir,$periode ;
    public static function tableName()
    {
        return 't_target_penjualan';
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
            [['type_penjualan', 'target_jenis_produk', 'target_jml_satuan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['target_jml', 'target_range', 'target_range_prosentase'], 'number'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'target_seq'], 'integer'],
            [['type_penjualan'], 'string', 'max' => 10],
            [['target_jenis_produk'], 'string', 'max' => 50],
            [['target_jml_satuan'], 'string', 'max' => 20],
            [['target_periode'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'target_penjualan_id' => 'Target Penjualan',
                'type_penjualan' => 'Type Penjualan',
                'target_jenis_produk' => 'Target Jenis Produk',
                'target_jml' => 'Target Jml',
                'target_jml_satuan' => 'Target Jml Satuan',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'target_seq' => 'Target Seq',
                'target_periode' => 'Target Periode',
                'target_range' => 'Target Range',
                'target_range_prosentase' => 'Target Range Prosentase',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTargetPenjualanSales()
    {
        return $this->hasMany(TTargetPenjualanSales::className(), ['target_penjualan_id' => 'target_penjualan_id']);
    }
    public static function getOptionListPeriodeTahun()
    {
        $res = Yii::$app->db->createCommand("select substring(target_periode,1,4)::numeric as periode_tahun from t_target_penjualan group by 1 order by 1 asc")->queryAll();
		$ret = [];
		foreach($res as $i => $asd){
			$ret[$asd['periode_tahun']] = $asd['periode_tahun'];
		}
        return $ret;
    }
    
}
