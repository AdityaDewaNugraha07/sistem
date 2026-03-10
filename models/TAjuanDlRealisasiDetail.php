<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_ajuan_dl_realisasi_detail".
 *
 * @property integer $ajuan_dl_realisasi_detail_id
 * @property integer $ajuan_dl_realisasi_id
 * @property integer $dl_realisasi_id
 * @property double $biaya
 * @property boolean $persetujuan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MDlRealisasi $dlRealisasi
 * @property TAjuanDlRealisasi $ajuanDlRealisasi
 */
class TAjuanDlRealisasiDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_ajuan_dl_realisasi_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ajuan_dl_realisasi_id', 'dl_realisasi_id', 'created_by', 'updated_by'], 'integer'],
            [['biaya'], 'number'],
            [['persetujuan'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['dl_realisasi_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDlRealisasi::className(), 'targetAttribute' => ['dl_realisasi_id' => 'dl_realisasi_id']],
            [['ajuan_dl_realisasi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TAjuanDlRealisasi::className(), 'targetAttribute' => ['ajuan_dl_realisasi_id' => 'ajuan_dl_realisasi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ajuan_dl_realisasi_detail_id' => 'Ajuan Dl Realisasi Detail ID',
            'ajuan_dl_realisasi_id' => 'Ajuan Dl Realisasi ID',
            'dl_realisasi_id' => 'Dl Realisasi ID',
            'biaya' => 'Biaya',
            'persetujuan' => 'Persetujuan',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDlRealisasi()
    {
        return $this->hasOne(MDlRealisasi::className(), ['dl_realisasi_id' => 'dl_realisasi_id']);
    }
    public function getAjuanDlRealisasi()
    {
        return $this->hasOne(TAjuanDlRealisasi::className(), ['ajuan_dl_realisasi_id' => 'ajuan_dl_realisasi_id']);
    }
}
