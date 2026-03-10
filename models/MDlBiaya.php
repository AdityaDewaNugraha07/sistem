<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_dl_biaya".
 *
 * @property integer $dl_biaya_id
 * @property integer $jabatan_id
 * @property integer $dl_rencana_id
 * @property string $wilayah
 * @property string $satuan
 * @property double $biaya
 * @property string $transportasi
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MDlRencana $dlRencana
 * @property MJabatan $jabatan
 */
class MDlBiaya extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_dl_biaya';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jabatan_id', 'dl_rencana_id', 'created_by', 'updated_by'], 'integer'],
            [['wilayah', 'satuan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['biaya'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['wilayah', 'satuan', 'transportasi'], 'string', 'max' => 50],
            [['dl_rencana_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDlRencana::className(), 'targetAttribute' => ['dl_rencana_id' => 'dl_rencana_id']],
            [['jabatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MJabatan::className(), 'targetAttribute' => ['jabatan_id' => 'jabatan_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dl_biaya_id' => 'Dl Biaya ID',
            'jabatan_id' => 'Jabatan ID',
            'dl_rencana_id' => 'Dl Rencana ID',
            'wilayah' => 'Wilayah',
            'satuan' => 'Satuan',
            'biaya' => 'Biaya',
            'transportasi' => 'Transportasi',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDlRencana()
    {
        return $this->hasOne(MDlRencana::className(), ['dl_rencana_id' => 'dl_rencana_id']);
    }
    public function getJabatan()
    {
        return $this->hasOne(MJabatan::className(), ['jabatan_id' => 'jabatan_id']);
    }
}
