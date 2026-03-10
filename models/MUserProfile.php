<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_user_profile".
 *
 * @property integer $user_profile_id
 * @property string $fullname
 * @property string $email
 * @property string $language
 * @property string $theme_colors
 * @property string $avatar
 * @property integer $employee_id
 * @property string $bg
 *
 * @property MUser[] $mUsers
 */
class MUserProfile extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname'], 'required'],
            [['employee_id'], 'integer'],
            [['fullname', 'email', 'avatar', 'bg'], 'string', 'max' => 200],
            [['language', 'theme_colors'], 'string', 'max' => 50],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_profile_id' => Yii::t('app', 'User Profile ID'),
            'fullname' => Yii::t('app', 'Nama Lengkap'),
            'email' => Yii::t('app', 'Email'),
            'language' => Yii::t('app', 'Language'),
            'theme_colors' => Yii::t('app', 'Theme Colors'),
            'avatar' => Yii::t('app', 'Avatar'),
            'employee_id' => Yii::t('app', 'Employee ID'),
            'bg' => Yii::t('app', 'Bg'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMUsers()
    {
        return $this->hasMany(MUser::className(), ['user_profile_id' => 'user_profile_id']);
    }
}
