<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_acct_setup".
 *
 * @property integer $acct_setup_id
 * @property string $acct_setup_nm
 * @property string $acct_group
 * @property string $acct_tipe
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $acct_key
 *
 * @property MAcctAuto[] $mAcctAutos
 */
class MAcctSetup extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_acct_setup';
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
            [['acct_setup_nm', 'acct_group', 'acct_tipe'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['acct_key'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'acct_setup_id' => Yii::t('app', 'Acct Setup'),
                'acct_setup_nm' => Yii::t('app', 'Acct Setup Nm'),
                'acct_group' => Yii::t('app', 'Acct Group'),
                'acct_tipe' => Yii::t('app', 'Acct Tipe'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'acct_key' => Yii::t('app', 'Acct Key'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMAcctAutos()
    {
        return $this->hasMany(MAcctAuto::className(), ['acct_setup_id' => 'acct_setup_id']);
    }
	
	public static function findByAcctKeyDefaultValue($acct_key,$par2=null){
		if($par2){
			if( (strtoupper($acct_key) != 'ATK') && (strtoupper($acct_key) != 'CETAKAN') && (strtoupper($acct_key) != 'RUMAH TANGGA') ){
				$acct_key = "BOP";
			}
			$mod = self::find()->where("acct_key->>'m_default_value' = '$acct_key' AND acct_key->>'tipe' = '$par2'")->one();
		}else{
			$mod = self::find()->where("acct_key->>'m_default_value' = '$acct_key'")->one();
		}
		
		return $mod;
	}
}
