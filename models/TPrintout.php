<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_printout".
 *
 * @property integer $printout_id
 * @property string $reff_no
 * @property string $reff_no2
 * @property string $parameter1
 * @property string $parameter2
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class TPrintout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_printout';
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
            [['reff_no', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['parameter1', 'parameter2'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['reff_no', 'reff_no2'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'printout_id' => 'Printout',
                'reff_no' => 'Reff No',
                'reff_no2' => 'Reff No2',
                'parameter1' => 'Parameter1',
                'parameter2' => 'Parameter2',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getJumlahPrintByReffNo($reff_no2,$param1=null)
    {
        $sql = "SELECT COUNT(printout_id) FROM t_printout WHERE reff_no2 = '{$reff_no2}'".(!empty($param1)?" AND parameter1='".$param1."'":"");
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        return (!empty($res['count']))?$res['count']:0;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function createPrintout($params)
    {
        $model = new TPrintout();
        $model->attributes = $params;
        if($model->validate()){
            $model->save();
        }
    }
}
