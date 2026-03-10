<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_biaya_grader".
 *
 * @property integer $biaya_grader_id
 * @property string $biaya_grader_kode
 * @property string $biaya_grader_tgl
 * @property string $biaya_grader_ket
 * @property double $biaya_grader_jml
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status
 *
 * @property TBiayaGraderDetail[] $tBiayaGraderDetails
 */
class TBiayaGrader extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_biaya_grader';
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
            [['biaya_grader_kode', 'biaya_grader_tgl', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['biaya_grader_tgl', 'created_at', 'updated_at'], 'safe'],
            [['biaya_grader_ket'], 'string'],
            [['biaya_grader_jml'], 'number'],
            [['created_by', 'updated_by'], 'integer'],
            [['biaya_grader_kode'], 'string', 'max' => 25],
            [['status'], 'string', 'max' => 50],
        ];
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'biaya_grader_id' => Yii::t('app', 'Pengajuan Biaya Grader'),
                'biaya_grader_kode' => Yii::t('app', 'Kode Pengajuan'),
                'biaya_grader_tgl' => Yii::t('app', 'Tanggal Pengajuan'),
                'biaya_grader_ket' => Yii::t('app', 'Keterangan'),
                'biaya_grader_jml' => Yii::t('app', 'Total Biaya (Rp)'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTBiayaGraderDetails()
    {
        return $this->hasMany(TBiayaGraderDetail::className(), ['biaya_grader_id' => 'biaya_grader_id']);
    }
}
