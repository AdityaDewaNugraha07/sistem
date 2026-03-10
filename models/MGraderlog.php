<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_graderlog".
 *
 * @property integer $graderlog_id
 * @property string $graderlog_nm
 * @property string $graderlog_almt
 * @property string $graderlog_phone
 * @property string $graderlog_norek_bank
 * @property string $graderlog_bank
 * @property string $graderlog_email
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $graderlog_kode
 */
class MGraderlog extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_graderlog';
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
            [['graderlog_nm', 'graderlog_almt', 'graderlog_phone', 'graderlog_norek_bank', 'graderlog_bank', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['graderlog_almt'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['graderlog_nm', 'graderlog_norek_bank', 'graderlog_bank', 'graderlog_email', 'graderlog_kode'], 'string', 'max' => 50],
            [['graderlog_phone'], 'string', 'max' => 20],
            [['type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'graderlog_id' => Yii::t('app', 'Graderlog'),
                'graderlog_nm' => Yii::t('app', 'Nama Grader'),
                'graderlog_almt' => Yii::t('app', 'Alamat'),
                'graderlog_phone' => Yii::t('app', 'Phone'),
                'graderlog_norek_bank' => Yii::t('app', 'No. Rek'),
                'graderlog_bank' => Yii::t('app', 'Nama Bank'),
                'graderlog_email' => Yii::t('app', 'Email'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
				'graderlog_kode' => Yii::t('app', 'Kode Grader'),
        ];
    }
	
	public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('graderlog_nm ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'graderlog_id', 'graderlog_nm');
    }
	
	public static function getOptionList2($selected_items)
    {
		if(!empty($selected_items)){
			if(is_array($selected_items)){
				$selected_items = implode(', ', $selected_items);
			}
		}else{
			$selected_items = '';
		}
		$query = "
			SELECT * FROM m_graderlog
			WHERE m_graderlog.active IS TRUE
				".(($selected_items!='')?'AND graderlog_id NOT IN ('.$selected_items.')':'')." 
			ORDER BY m_graderlog.graderlog_nm ASC
		";
		$mod = Yii::$app->db->createCommand($query)->queryAll();
		$arraymap = \yii\helpers\ArrayHelper::map($mod, 'graderlog_id', 'graderlog_nm');
		return $arraymap;
    }

    public static function getOptionList3($selected_items,$type)
    {
		if(!empty($selected_items)){
			if(is_array($selected_items)){
				$selected_items = implode(', ', $selected_items);
			}
		}else{
			$selected_items = '';
		}
		$query = "
			SELECT * FROM m_graderlog
			WHERE m_graderlog.active IS TRUE
            ".(($selected_items!='')?'AND graderlog_id NOT IN ('.$selected_items.')':'')." 
            AND m_graderlog.type = '".$type."'
			ORDER BY m_graderlog.graderlog_nm ASC
		";
		$mod = Yii::$app->db->createCommand($query)->queryAll();
        $arraymap = \yii\helpers\ArrayHelper::map($mod, 'graderlog_id', 'graderlog_nm');
		return $arraymap;
    }
}
