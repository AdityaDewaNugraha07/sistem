<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "m_video_training".
 *
 * @property integer $video_training_id
 * @property string $tgl_awal
 * @property string $tgl_akhir
 * @property string $judul
 * @property string $deskripsi
 * @property string $video
 * @property string $peserta
 * @property string $evaluasi_peserta
 * @property string $evaluasi_atasan
 */
class TVideoTraining extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_video_training';
    }
    
    public function behaviors(){
		return [DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tgl_awal', 'tgl_akhir', 'video'], 'required'],
            [['deskripsi', 'peserta', 'evaluasi_peserta', 'evaluasi_atasan'], 'string'],
            [['judul'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'video_training_id' => 'Video Training',
                'tgl_awal' => 'Tanggal Mulai',
                'tgl_akhir' => 'Tanggal Berakhir',
                'judul' => 'Judul',
                'deskripsi' => 'Deskripsi',
                'video' => 'Video',
                'peserta' => 'Peserta',
                'evaluasi_peserta' => 'Evaluasi Peserta',
                'evaluasi_atasan' => 'Evaluasi Atasan',
        ];
    }

//    public function getVideoTrainingPeserta()
//    {
//        return $this->hasMany(TVideoTrainingPeserta::className(), ['video_training_id' => 'video_training_id']);
//    }

    public function getPeserta()
    {
        return $this->hasOne(TVideoTrainingPeserta::className(), ['video_training_id' => 'video_training_id']);
    }
}
