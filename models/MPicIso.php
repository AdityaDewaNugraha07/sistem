<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_pic_iso".
 *
 * @property integer $pic_iso_id
 * @property integer $departement_id
 * @property integer $pegawai_id
 * @property string $kategori_dokumen
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MDepartement $departement
 * @property MPegawai $pegawai
 * @property TDokumenDistribusi[] $tDokumenDistribusis
 */
class MPicIso extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_pic_iso';
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
            [['departement_id', 'pegawai_id', 'created_by', 'updated_by'], 'integer'],
            [['departement_id', 'pegawai_id', 'kategori_dokumen'], 'required'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at', 'kategori_dokumen'], 'safe'],
            // [['kategori_dokumen'], 'string'],
            // [['kategori_dokumen'], 'each', 'rule' => ['string']],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pic_iso_id' => 'Pic Iso',
                'departement_id' => 'Departement',
                'pegawai_id' => 'Pegawai',
                'kategori_dokumen' => 'Kategori Dokumen',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTDokumenDistribusis()
    {
        return $this->hasMany(TDokumenDistribusi::className(), ['pic_iso_id' => 'pic_iso_id']);
    }
} 