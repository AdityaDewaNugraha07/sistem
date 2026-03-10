<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "m_kayu".
 *
 * @property integer $kayu_id
 * @property string $group_kayu
 * @property string $kayu_nama
 * @property string $kayu_othername
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property mixed|null $nama_ilmiah
 */
class MKayu extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_kayu';
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
            [['group_kayu', 'kayu_nama', 'kayu_othername', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['group_kayu', 'kayu_nama', 'kayu_othername'], 'string', 'max' => 100],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'kayu_id' => Yii::t('app', 'Kayu'),
                'group_kayu' => Yii::t('app', 'Kelompok Kayu'),
                'kayu_nama' => Yii::t('app', 'Nama Kayu'),
                'kayu_othername' => Yii::t('app', 'Nama Lain'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }
	
	public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('kayu_nama ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'kayu_id', 'kayu_nama');
    }
	
    public static function getOptionListN()
    {
        $res = self::find()->where(['active'=>true])->orderBy('kayu_nama ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'kayu_nama', 'kayu_nama');
    }
    
	public static function getOptionListPlusGroup()
    {
		$ret = [];
        $res = self::find()->where(['active'=>true])->orderBy('group_kayu ASC, kayu_nama ASC')->all();
		if(count($res)>0){
			foreach($res as $i => $r){
				$ret[$r['kayu_id']] = $r->group_kayu." - ".$r->kayu_nama;
			}
		}
        return $ret;
    }
	public static function getOptionListGroupKayu()
    {
		$ret = [];
        $res = self::find()->select(['group_kayu'])->distinct()->where(['active'=>true])->orderBy('group_kayu ASC')->all();
		if(count($res)>0){
			foreach($res as $i => $r){
				$ret[$r['group_kayu']] = $r->group_kayu;
			}
		}
        return $ret;
    }    
	public static function getOptionListNamaKayu()
    {
		$ret = [];
        $res = self::find()->where(['active'=>true])->orderBy('kayu_nama ASC')->all();
		if(count($res)>0){
			foreach($res as $i => $r){
				$ret[$r['kayu_id']] = $r->kayu_nama;
			}
		}
        return $ret;
    }

    public static function getOptionListNamaIlmiah()
    {
        return ArrayHelper::map(array_map(static function ($value) {
            return [
                'kayu_id' => $value->kayu_id,
                'kayu_nama' => $value->nama_ilmiah
                    ? $value->kayu_nama . ' (' . $value->nama_ilmiah . ')'
                    : $value->kayu_nama
            ];
        }, self::findAll(['active' => true])), 'kayu_id', 'kayu_nama');
    }

    public static function getOptionListIlimiahKayu()
    {
		$ret = [];
        $res = self::find()->where(['active'=>true])->orderBy('group_kayu ASC, kayu_nama ASC')->all();
		if(count($res)>0){
			foreach($res as $i => $r){
				$ret[$r['kayu_id']] = $r->kayu_nama . ' - ' .$r->group_kayu;
			}
		}
        return $ret;
    }
}
