<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_persediaan_dkb".
 *
 * @property integer $persediaan_dkb_id
 * @property integer $kayu_id
 * @property string $no_grade
 * @property string $no_barcode
 * @property string $no_btg
 * @property string $no_lap
 * @property string $status
 * @property string $reff_no
 * @property string $lokasi
 * @property double $dok_diameter
 * @property double $dok_panjang
 * @property string $dok_reduksi
 * @property double $dok_volume
 * @property integer $pot
 * @property string $keterangan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MKayu $kayu
 */
class HPersediaanDkb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_persediaan_dkb';
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
            [['kayu_id', 'no_grade', 'no_barcode', 'no_btg', 'status', 'reff_no', 'lokasi', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['kayu_id', 'pot', 'created_by', 'updated_by'], 'integer'],
            [['dok_diameter', 'dok_panjang', 'dok_volume'], 'number'],
            [['keterangan'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['no_grade', 'no_barcode', 'no_btg', 'no_lap', 'status', 'reff_no', 'lokasi', 'dok_reduksi'], 'string', 'max' => 50],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'persediaan_dkb_id' => 'Persediaan Dkb',
                'kayu_id' => 'Kayu',
                'no_grade' => 'No Grade',
                'no_barcode' => 'No Barcode',
                'no_btg' => 'No Btg',
                'no_lap' => 'No Lap',
                'status' => 'Status',
                'reff_no' => 'Reff No',
                'lokasi' => 'Lokasi',
                'dok_diameter' => 'Dok Diameter',
                'dok_panjang' => 'Dok Panjang',
                'dok_reduksi' => 'Dok Reduksi',
                'dok_volume' => 'Dok Volume',
                'pot' => 'Pot',
                'keterangan' => 'Keterangan',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKayu()
    {
        return $this->hasOne(MKayu::className(), ['kayu_id' => 'kayu_id']);
    }
	
	public static function checkPersediaanOut($barcode){
		$check = \app\models\HPersediaanDkb::find()->where("no_barcode='{$barcode}' AND status != 'IN'")->all();
		if(count($check)>0){
			return true;
		}else{
			return false;
		}
	}
	
	public static function updateStokPersediaan($model){
        if($model->validate()){
            if($model->save()){
                return true;
            }else{
                return false;
            }
		}
    }
	
	public static function getCurrentStockPerBatang($no_barcode){
		$sql = "SELECT * FROM h_persediaan_dkb WHERE no_barcode = '{$no_barcode}' AND status = 'IN' ORDER BY persediaan_dkb_id DESC LIMIT 1";
		$mod = self::find()->where(['no_barcode'=>$no_barcode,'status'=>'IN'])->limit("1")->orderBy("persediaan_dkb_id DESC")->one();
		return $mod;
	}
}
