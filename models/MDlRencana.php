<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_dl_rencana".
 *
 * @property integer $dl_rencana_id
 * @property string $dl_rencana
 * @property integer $dl_rencana_asc
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $dl_days
 * @property integer $dl_rms
 *
 * @property MDlBiaya[] $mDlBiayas
 * @property TAjuanDlDetail[] $tAjuanDlDetails
 */
class MDlRencana extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_dl_rencana';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dl_rencana', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['dl_rencana_asc', 'created_by', 'updated_by', 'dl_days', 'dl_rms'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['dl_rencana'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dl_rencana_id' => 'Dl Rencana ID',
            'dl_rencana' => 'Dl Rencana',
            'dl_rencana_asc' => 'Dl Rencana Asc',
            'active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'dl_days' => 'Dl Days',
            'dl_rms' => 'Dl Rms',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMDlBiayas()
    {
        return $this->hasMany(MDlBiaya::className(), ['dl_rencana_id' => 'dl_rencana_id']);
    }

    public function getTAjuanDlDetails()
    {
        return $this->hasMany(TAjuanDlDetail::className(), ['dl_rencana_id' => 'dl_rencana_id']);
    }
}
