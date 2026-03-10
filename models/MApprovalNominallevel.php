<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_approval_nominallevel".
 *
 * @property integer $approval_nominallevel_id
 * @property integer $pegawai_id
 * @property string $nominal
 * @property string $type
 * @property integer $level
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MPegawai $pegawai
 */
class MApprovalNominallevel extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_approval_nominallevel';
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
            [['pegawai_id', 'nominal', 'type', 'level', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['pegawai_id', 'level', 'created_by', 'updated_by'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['nominal', 'type'], 'string', 'max' => 50],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'approval_nominallevel_id' => Yii::t('app', 'Approval Nominallevel'),
                'pegawai_id' => Yii::t('app', 'Pegawai'),
                'nominal' => Yii::t('app', 'Nominal'),
                'type' => Yii::t('app', 'Type'),
                'level' => Yii::t('app', 'Level'),
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
}
