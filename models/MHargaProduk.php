<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_harga_produk".
 *
 * @property integer $harga_id
 * @property integer $produk_id
 * @property integer $harga_distributor
 * @property integer $harga_agent
 * @property integer $harga_enduser
 * @property integer $harga_hpp
 * @property string $harga_tanggal_penetapan
 * @property string $harga_keterangan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MHargaProduk extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $jenis_produk;
    public static function tableName()
    {
        return 'm_harga_produk';
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
            [['produk_id', 'harga_enduser', 'harga_hpp', 'harga_tanggal_penetapan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['produk_id', 'created_by', 'updated_by'], 'integer'],
            [['harga_tanggal_penetapan', 'created_at', 'updated_at'], 'safe'],
            [['kode', 'status_approval', 'approve_reason', 'reject_reason'], 'string'],
            [['harga_keterangan','harga_distributor', 'harga_agent', 'harga_enduser', 'harga_hpp'], 'number'],
            [['kode'], 'string', 'max' => 30],
            [['status_harga'], 'string', 'max' => 20],
            [['active','status_harga'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'harga_id' => Yii::t('app', 'Harga'),
                'produk_id' => Yii::t('app', 'Produk'),
                'harga_distributor' => Yii::t('app', 'Harga Distributor (Rp)'),
                'harga_agent' => Yii::t('app', 'Harga Agent (Rp)'),
                'harga_enduser' => Yii::t('app', 'Harga End User (Rp)'),
                'harga_hpp' => Yii::t('app', 'HPP (Rp)'),
                'harga_tanggal_penetapan' => Yii::t('app', 'Tanggal Penetapan'),
                'harga_keterangan' => Yii::t('app', 'Keterangan'),
                'active' => Yii::t('app', 'Status'),
                'status_harga' => Yii::t('app', 'Status Harga'),                
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'kode' => Yii::t('app', 'Kode'),
                'status_approval' => Yii::t('app', 'Status Approval'),
                'approve_reason' => Yii::t('app', 'Approve Reason'),
                'reject_reason' => Yii::t('app', 'Reject Reason'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduk()
    {
        return $this->hasOne(MBrgProduk::className(), ['produk_id' => 'produk_id']);
    } 
    
    public static function getOptionListTanggal()
    {
        $mod = self::find()->where(['active'=>true])->orderBy('harga_tanggal_penetapan DESC')->all();
        $return = \yii\helpers\ArrayHelper::map($mod, 'harga_tanggal_penetapan', 'harga_tanggal_penetapan');
        foreach($return as $i => $ret){
            $return[$i] = Yii::t('app', 'Price List Tanggal : ').\app\components\DeltaFormatter::formatDateTimeForUser($ret);
        }
        return $return;
    }
    
	public static function getHargaCurrentEndUser($produk_id,$tipe_harga,$kode){
        $sql_tanggal = "select max(harga_tanggal_penetapan) ".
                            "   from m_harga_produk ". 
                            "   where produk_id = ".$produk_id." ". 
                            //"   and status_harga is true ".
                            "   and harga_tanggal_penetapan <= '".date('Y-m-d')."' ".
                            "   and status_approval = 'APPROVED' ".
                            "   ";
		$lastDate = \Yii::$app->db->createCommand($sql_tanggal)->queryOne()['max'];
        
        $sql = "select * ". 
                    "   from m_harga_produk ". 
                    "   where produk_id = ".$produk_id." ".
                    //"   and status_harga is true ". 
                    "   and harga_tanggal_penetapan = '".$lastDate."' ". 
                    "   and kode = '".$kode."'".
                    "   ";
        $harga = !empty($lastDate) ? \Yii::$app->db->createCommand($sql)->queryOne() : '0';
		$return = !empty($harga) ? $harga[$tipe_harga] : '0';
		return $return;
	}

}
