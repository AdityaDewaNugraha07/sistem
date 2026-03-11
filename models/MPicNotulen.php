<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_pic_notulen".
 *
 * @property integer $pic_notulen_id
 * @property integer $departement_id
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MDepartement $departement
 */
class MPicNotulen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_pic_notulen';
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
            [['departement_id', 'created_by', 'updated_by'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pic_notulen_id' => 'Pic Notulen',
                'departement_id' => 'Departement',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartement()
    {
        return $this->hasOne(MDepartement::className(), ['departement_id' => 'departement_id']);
    }

    public function getPegawais()
    {
        // Asumsi model pegawainya bernama MPPegawai
        return $this->hasMany(MPegawai::className(), ['pic_notulen_id' => 'pic_notulen_id']);
    }

    // Relasi ke tabel perantara m_pic_notulen_pegawai
    public function getPicNotulenPegawais()
    {
        return $this->hasMany(MPegawai::className(), ['pic_notulen_id' => 'pic_notulen_id']);
    }

}
