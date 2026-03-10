<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_video_training_peserta".
 *
 * @property integer $video_training_peserta_id
 * @property integer $video_training_id
 * @property integer $pegawai_id
 * @property string $tipe
 */
class TVideoTrainingPeserta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_video_training_peserta';
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
            [['video_training_id', 'pegawai_id'], 'required'],
            [['video_training_id', 'pegawai_id'], 'integer'],
            [['tipe'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'video_training_peserta_id' => 'Video Training Peserta',
                'video_training_id' => 'Video Training',
                'pegawai_id' => 'Pegawai',
                'tipe' => 'Tipe',
        ];
    }
}
