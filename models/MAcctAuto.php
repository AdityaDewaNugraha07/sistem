<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_acct_auto".
 *
 * @property integer $acct_auto_id
 * @property integer $acct_setup_id
 * @property string $acct_no
 * @property string $acct_status
 * @property integer $acct_id
 *
 * @property MAcctRekening $acct
 * @property MAcctSetup $acctSetup
 */
class MAcctAuto extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_acct_auto';
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
            [['acct_setup_id', 'acct_no', 'acct_status'], 'required'],
            [['acct_setup_id', 'acct_id'], 'integer'],
            [['acct_no'], 'string', 'max' => 10],
            [['acct_status'], 'string', 'max' => 15],
            [['acct_id'], 'exist', 'skipOnError' => true, 'targetClass' => MAcctRekening::className(), 'targetAttribute' => ['acct_id' => 'acct_id']],
            [['acct_setup_id'], 'exist', 'skipOnError' => true, 'targetClass' => MAcctSetup::className(), 'targetAttribute' => ['acct_setup_id' => 'acct_setup_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'acct_auto_id' => Yii::t('app', 'Acct Auto'),
                'acct_setup_id' => Yii::t('app', 'Acct Setup'),
                'acct_no' => Yii::t('app', 'Acct No'),
                'acct_status' => Yii::t('app', 'Acct Status'),
                'acct_id' => Yii::t('app', 'Acct'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcct()
    {
        return $this->hasOne(MAcctRekening::className(), ['acct_id' => 'acct_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcctSetup()
    {
        return $this->hasOne(MAcctSetup::className(), ['acct_setup_id' => 'acct_setup_id']);
    }
}
