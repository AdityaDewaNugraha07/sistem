<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_departement".
 *
 * @property integer $departement_id
 * @property string $departement_nama
 * @property string $departement_other
 * @property integer $departement_kadep
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $case_divisi
 */
class ViewDepartement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_departement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['departement_id', 'departement_kadep', 'created_by', 'updated_by'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['case_divisi'], 'string'],
            [['departement_nama', 'departement_other'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'departement_id' => 'Departement ID',
            'departement_nama' => 'Departement Nama',
            'departement_other' => 'Departement Other',
            'departement_kadep' => 'Departement Kadep',
            'active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'case_divisi' => 'Case Divisi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpbs()
    {
        return $this->hasMany(TSpb::className(), ['departement_id' => 'departement_id']);
    }
    
    public static function getOptionList()
    {
        $res = self::find()->where(['not', ['case_divisi' => '']])->orderBy('departement_nama ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'departement_id', 'departement_nama');
    }
}
