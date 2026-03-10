<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_acct_rekening".
 *
 * @property integer $acct_id
 * @property string $acct_no
 * @property string $acct_tipe
 * @property string $acct_nm
 * @property boolean $acct_flag
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MAcctAuto[] $mAcctAutos
 */
class MAcctRekening extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_acct_rekening';
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
            [['acct_no', 'acct_tipe', 'acct_nm', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['acct_flag', 'active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['acct_no'], 'string', 'max' => 10],
            [['acct_tipe'], 'string', 'max' => 50],
            [['acct_nm'], 'string', 'max' => 225],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'acct_id' => Yii::t('app', 'Acct'),
                'acct_no' => Yii::t('app', 'Acct No'),
                'acct_tipe' => Yii::t('app', 'Acct Tipe'),
                'acct_nm' => Yii::t('app', 'Acct Nm'),
                'acct_flag' => Yii::t('app', 'Acct Flag'),
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
    public function getMAcctAutos()
    {
        return $this->hasMany(MAcctAuto::className(), ['acct_id' => 'acct_id']);
    }
	
	public static function getByPk($acct_id){
		return self::findOne($acct_id);
	}
	
	public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true,'acct_flag'=>TRUE])->orderBy('acct_no ASC')->all();
		$return = [];
		foreach($res as $i => $val){
			$return[$val['acct_id']] = $val['acct_no'].' '.$val['acct_nm'];
		}
        return $return;
    }
	
	public static function getOptionListBank()
    {
        $res = self::find()->where(['active'=>true,'acct_flag'=>TRUE])->andWhere("acct_no ILIKE '1-2%'")->orderBy('acct_no ASC')->all();
		$return = [];
		foreach($res as $i => $val){
			$return[$val['acct_id']] = $val['acct_no'].' '.$val['acct_nm'];
		}
        return $return;
    }
}
