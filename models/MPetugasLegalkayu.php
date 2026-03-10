<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_petugas_legalkayu".
 *
 * @property integer $petugas_legalkayu_id
 * @property integer $pegawai_id
 * @property string $noreg
 * @property string $jenis
 * @property string $periode_awal
 * @property string $periode_akhir
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MPegawai $pegawai
 */
class MPetugasLegalkayu extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $pegawai_nama;
    public static function tableName()
    {
        return 'm_petugas_legalkayu';
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
            [['pegawai_id', 'noreg', 'jenis', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['pegawai_id', 'created_by', 'updated_by'], 'integer'],
            [['periode_awal', 'periode_akhir', 'created_at', 'updated_at'], 'safe'],
            [['active'], 'boolean'],
            [['noreg', 'jenis'], 'string', 'max' => 100],
            [['noreg'], 'unique'],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'petugas_legalkayu_id' => Yii::t('app', 'Petugas Legalkayu'),
                'pegawai_id' => Yii::t('app', 'Pegawai'),
                'noreg' => Yii::t('app', 'Noreg'),
                'jenis' => Yii::t('app', 'Jenis'),
                'periode_awal' => Yii::t('app', 'Periode Awal'),
                'periode_akhir' => Yii::t('app', 'Periode Akhir'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawai_id']);
    }
	
	public static function getOptionList($jenis)
    {
		$ret = [];
		if(!empty($jenis)){
			$res = self::find()->join("JOIN", "m_pegawai", "m_pegawai.pegawai_id = m_petugas_legalkayu.pegawai_id")->where(['m_petugas_legalkayu.active'=>true,'jenis'=>$jenis])->orderBy('pegawai_nama ASC')->all();
		}else{
			$res = self::find()->join("JOIN", "m_pegawai", "m_pegawai.pegawai_id = m_petugas_legalkayu.pegawai_id")->where(['m_petugas_legalkayu.active'=>true])->orderBy('pegawai_nama ASC')->all();
		}
		if(count($res)){
			foreach($res as $i => $r){
				$ret[$r->petugas_legalkayu_id] = $r->pegawai->pegawai_nama." - ".$r->noreg;
			}
		}
        return $ret;
    }
}
