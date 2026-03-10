<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "c_site_config".
 *
 * @property integer $site_config_id
 * @property string $config_name
 * @property integer $company_profile_id
 * @property boolean $notifikasi
 * @property boolean $screenlock
 *
 * @property CCompanyProfile $companyProfile
 */
class CSiteConfig extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_site_config';
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
            [['config_name', 'company_profile_id', 'notifikasi', 'screenlock'], 'required'],
            [['company_profile_id'], 'integer'],
            [['notifikasi', 'screenlock'], 'boolean'],
            [['config_name'], 'string', 'max' => 200],
            [['company_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => CCompanyProfile::className(), 'targetAttribute' => ['company_profile_id' => 'company_profile_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'site_config_id' => Yii::t('app', 'Site Config'),
                'config_name' => Yii::t('app', 'Config Name'),
                'company_profile_id' => Yii::t('app', 'Company Profile'),
                'notifikasi' => Yii::t('app', 'Notifikasi'),
                'screenlock' => Yii::t('app', 'Screenlock'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyProfile()
    {
        return $this->hasOne(CCompanyProfile::className(), ['company_profile_id' => 'company_profile_id']);
    }
}
