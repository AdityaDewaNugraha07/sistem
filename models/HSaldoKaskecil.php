<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_saldo_kaskecil".
 *
 * @property integer $saldo_kaskecil_id
 * @property string $reff_no
 * @property string $tanggal
 * @property string $deskripsi
 * @property double $debit
 * @property double $kredit
 * @property double $saldo
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status
 */
class HSaldoKaskecil extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_saldo_kaskecil';
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
            [['reff_no', 'tanggal', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['deskripsi'], 'string'],
            [['debit', 'kredit', 'saldo'], 'number'],
            [['created_by', 'updated_by'], 'integer'],
            [['reff_no', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'saldo_kaskecil_id' => Yii::t('app', 'Saldo Kaskecil'),
                'reff_no' => Yii::t('app', 'Reff No'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'deskripsi' => Yii::t('app', 'Deskripsi'),
                'debit' => Yii::t('app', 'Debit'),
                'kredit' => Yii::t('app', 'Kredit'),
                'saldo' => Yii::t('app', 'Saldo'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'status' => Yii::t('app', 'Status'),
        ];
    }
	
	public static function getSaldoAwal($tgl) {
		$time = new \DateTime($tgl);
		$tgl = $time->modify('-1 day')->format('Y-m-d');
		return self::getSaldoAkhir($tgl);
	}
	
	public static function getSaldoAkhir($tgl=null) {
		if(!empty($tgl)){
			if(strlen($tgl) <= 10){
				$tgl = date('Y-m-d', strtotime($tgl));
				$tgl = $tgl." 23:59:59";
			}
			$sql = "SELECT sum(debit)-sum(kredit) AS saldo FROM h_saldo_kaskecil WHERE tanggal <= '".$tgl."'" ;
		}else{
			$sql = "SELECT sum(debit)-sum(kredit) AS saldo FROM h_saldo_kaskecil";
		}
		$mod = \Yii::$app->db->createCommand($sql)->queryOne();
		return $mod['saldo'];
	}
}
