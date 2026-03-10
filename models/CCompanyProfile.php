<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "c_company_profile".
 *
 * @property integer $company_profile_id
 * @property string $name
 * @property integer $director
 * @property integer $year_since
 * @property string $type
 * @property string $logo_pic
 * @property string $site_host
 * @property string $db_host
 * @property string $db_name
 * @property string $db_username
 * @property string $db_password
 * @property double $screenlock_timeout
 * @property string $alamat
 * @property string $phone
 * @property string $email
 */
class CCompanyProfile extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_company_profile';
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
            [['name', 'db_host', 'db_name', 'db_username', 'db_password'], 'required'],
            [['director', 'year_since'], 'integer'],
            [['logo_pic', 'alamat', 'phone'], 'string'],
            [['screenlock_timeout'], 'number'],
            [['name', 'type', 'site_host', 'db_host', 'db_name', 'db_username', 'db_password'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'company_profile_id' => Yii::t('app', 'Company Profile'),
                'name' => Yii::t('app', 'Name'),
                'director' => Yii::t('app', 'Director'),
                'year_since' => Yii::t('app', 'Year Since'),
                'type' => Yii::t('app', 'Type'),
                'logo_pic' => Yii::t('app', 'Logo Pic'),
                'site_host' => Yii::t('app', 'Site Host'),
                'db_host' => Yii::t('app', 'Db Host'),
                'db_name' => Yii::t('app', 'Db Name'),
                'db_username' => Yii::t('app', 'Db Username'),
                'db_password' => Yii::t('app', 'Db Password'),
                'screenlock_timeout' => Yii::t('app', 'Screenlock Timeout'),
                'alamat' => Yii::t('app', 'Alamat'),
                'phone' => Yii::t('app', 'Phone'),
                'email' => Yii::t('app', 'Email'),
        ];
    }
	
	public static function Name(){
		$modCompany = self::findOne( \app\components\Params::DEFAULT_COMPANY_PROFILE );
		return $modCompany->name;
	}
}
