<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_dl_realisasi".
 *
 * @property integer $dl_realisasi_id
 * @property string $dl_realisasi
 * @property integer $dl_realisasi_asc
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $realisasi_days
 * @property integer $realisasi_rms
 *
 * @property TAjuanDlRealisasiDetail[] $tAjuanDlRealisasiDetails
 */
class MDlRealisasi extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_dl_realisasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dl_realisasi', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['dl_realisasi_asc', 'created_by', 'updated_by', 'realisasi_days', 'realisasi_rms'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['dl_realisasi'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dl_realisasi_id' => 'Dl Realisasi ID',
            'dl_realisasi' => 'Dl Realisasi',
            'dl_realisasi_asc' => 'Dl Realisasi Asc',
            'active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'realisasi_days' => 'Realisasi Days',
            'realisasi_rms' => 'Realisasi Rms',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTAjuanDlRealisasiDetails()
    {
        return $this->hasMany(TAjuanDlRealisasiDetail::className(), ['dl_realisasi_id' => 'dl_realisasi_id']);
    }
    
}
