<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_pengumuman".
 *
 * @property integer $pengumuman_id
 * @property string $tipe
 * @property string $judul
 * @property string $deskripsi
 * @property integer $seq
 * @property boolean $judul_pulsate
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MPengumuman extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_pengumuman';
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
            [['tipe', 'judul', 'deskripsi', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['deskripsi'], 'string'],
            [['seq', 'created_by', 'updated_by'], 'integer'],
            [['judul_pulsate', 'active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['tipe'], 'string', 'max' => 50],
            [['judul'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengumuman_id' => Yii::t('app', 'Pengumuman'),
                'tipe' => Yii::t('app', 'Tipe'),
                'judul' => Yii::t('app', 'Judul'),
                'deskripsi' => Yii::t('app', 'Deskripsi'),
                'seq' => Yii::t('app', 'Seq'),
                'judul_pulsate' => Yii::t('app', 'Judul Pulsate'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }
}
