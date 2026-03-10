<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_departement".
 *
 * @property integer $departement_id
 * @property string $departement_nama
 * @property string $departement_other
 * @property integer $departement_kadep
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property TSpb[] $tSpbs
 */
class MDepartement extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_departement';
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
            [['departement_nama', 'departement_other', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['departement_kadep', 'created_by', 'updated_by'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['departement_nama', 'departement_other'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'departement_id' => Yii::t('app', 'Departement'),
			'departement_nama' => Yii::t('app', 'Nama Departement'),
			'departement_other' => Yii::t('app', 'Nama Lain'),
			'departement_kadep' => Yii::t('app', 'Departement Kadep'),
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
    public function getTSpbs()
    {
        return $this->hasMany(TSpb::className(), ['departement_id' => 'departement_id']);
    }
    
    public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('created_at ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'departement_id', 'departement_nama');
    }
    public static function getOptionList2()
    {
        $res = self::find()->where(['active'=>true,'departement_id'=>Yii::$app->user->identity->pegawai->departement_id])->orderBy('created_at ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'departement_id', 'departement_nama');
    }
    public static function getOptionListOne()
    {
        $deptID=Yii::$app->user->identity->pegawai->departement_id;
        if( ($deptID == '112') || ($deptID == '107') || ($deptID == '113') || ($deptID == '117') || ($deptID == '119')){
            $res = self::find()->where(['active'=>true])->orderBy('created_at ASC')->all();
            return \yii\helpers\ArrayHelper::map($res, 'departement_id', 'departement_nama');            
        }else{
            $res = self::find()->where(['departement_id'=>Yii::$app->user->identity->pegawai->departement_id])->all();
            return \yii\helpers\ArrayHelper::map($res, 'departement_id', 'departement_nama');
        }
    }
}
