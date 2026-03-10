<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_bank".
 *
 * @property integer $bank_id
 * @property string $nama
 * @property string $nomor
 * @property string $atasnama
 * @property string $othername
 * @property integer $sequence_number
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MBank extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_bank';
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
            [['nama', 'nomor', 'atasnama', 'othername', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['sequence_number', 'created_by', 'updated_by'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama'], 'string', 'max' => 100],
            [['nomor', 'atasnama', 'othername'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'bank_id' => 'Bank',
                'nama' => 'Nama',
                'nomor' => 'Nomor',
                'atasnama' => 'Atasnama',
                'othername' => 'Othername',
                'sequence_number' => 'Sequence Number',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    public static function getOptionList()
    {
        return yii\helpers\ArrayHelper::map(self::findAll([
            'active' => true, 
        ]), 'bank_id', function($model) {
            return $model->othername . ' - ' . $model->nama;
        });
    }
} 