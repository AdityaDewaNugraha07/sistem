<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_kas_dinasgrader".
 *
 * @property integer $kas_dinasgrader_id
 * @property string $tanggal
 * @property integer $graderlog_id
 * @property string $graderlog_nm
 * @property string $reff_no
 * @property double $nominal_in
 * @property double $nominal_out
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MGraderlog $graderlog
 */
class HKasDinasgrader extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_kas_dinasgrader';
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
            [['tanggal', 'graderlog_id', 'graderlog_nm', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['graderlog_id', 'created_by', 'updated_by'], 'integer'],
            [['nominal_in', 'nominal_out'], 'number'],
            [['keterangan'], 'string'],
            [['graderlog_nm', 'reff_no'], 'string', 'max' => 50],
            [['graderlog_id'], 'exist', 'skipOnError' => true, 'targetClass' => MGraderlog::className(), 'targetAttribute' => ['graderlog_id' => 'graderlog_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'kas_dinasgrader_id' => Yii::t('app', 'Kas Dinasgrader'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'graderlog_id' => Yii::t('app', 'Graderlog'),
                'graderlog_nm' => Yii::t('app', 'Graderlog Nm'),
                'reff_no' => Yii::t('app', 'Reff No'),
                'nominal_in' => Yii::t('app', 'Nominal In'),
                'nominal_out' => Yii::t('app', 'Nominal Out'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGraderlog()
    {
        return $this->hasOne(MGraderlog::className(), ['graderlog_id' => 'graderlog_id']);
    }
	
	public function updateSaldoKas($modParams){
        $model = new HKasDinasgrader();
        $model->graderlog_id = $modParams->graderlog_id;
        $model->graderlog_nm = $modParams->graderlog->graderlog_nm;
        $model->tanggal = date('Y-m-d');
        $model->nominal_in = $modParams->nominal_in;
        $model->nominal_out = $modParams->nominal_out;
        $model->keterangan = isset($modParams->keterangan)?$modParams->keterangan:"";
        $model->reff_no = isset($modParams->reff_no)?$modParams->reff_no:"";
        if($model->validate()){
            if($model->save()){
                return true;
            }else{
                return false;
            }
        }
    }
	
	 public static function getSaldoKas($graderlog_id){
        $sql = "SELECT sum(nominal_in)-sum(nominal_out) AS saldo FROM h_kas_dinasgrader WHERE graderlog_id = ".$graderlog_id." GROUP BY graderlog_id";
		$mod = \Yii::$app->db->createCommand($sql)->queryOne();
		if(!empty($mod)){
			return $mod['saldo'];
		}else{
			return 0;
		}
        
    }
}
