<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_losstime_swm_detail".
 *
 * @property integer $losstime_swm_detail_id
 * @property integer $losstime_swm_id
 * @property string $nomor_bandsaw
 * @property string $kategori_losstime
 * @property string $losstime_start
 * @property string $losstime_end
 * @property string $keterangan
 */
class TLosstimeSwmDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_losstime_swm_detail';
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
            [['losstime_swm_id', 'nomor_bandsaw', 'kategori_losstime', 'losstime_start', 'losstime_end'], 'required'],
            [['losstime_swm_id'], 'integer'],
            [['losstime_start', 'losstime_end'], 'safe'],
            [['keterangan'], 'string'],
            [['nomor_bandsaw'], 'string', 'max' => 2],
            [['kategori_losstime'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'losstime_swm_detail_id' => 'Losstime Swm Detail',
                'losstime_swm_id' => 'Losstime Swm',
                'nomor_bandsaw' => 'Nomor Bandsaw',
                'kategori_losstime' => 'Kategori Losstime',
                'losstime_start' => 'Losstime Start',
                'losstime_end' => 'Losstime End',
                'keterangan' => 'Keterangan',
        ];
    }
} 