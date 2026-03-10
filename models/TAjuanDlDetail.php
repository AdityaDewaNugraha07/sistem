<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_ajuan_dl_detail".
 *
 * @property integer $ajuan_dl_detail_id
 * @property integer $ajuan_dl_id
 * @property integer $dl_rencana_id
 * @property double $biaya
 *
 * @property MDlRencana $dlRencana
 * @property TAjuanDl $ajuanDl
 */
class TAjuanDlDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_ajuan_dl_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ajuan_dl_id', 'dl_rencana_id'], 'integer'],
            [['biaya'], 'number'],
            [['dl_rencana_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDlRencana::className(), 'targetAttribute' => ['dl_rencana_id' => 'dl_rencana_id']],
            [['ajuan_dl_id'], 'exist', 'skipOnError' => true, 'targetClass' => TAjuanDl::className(), 'targetAttribute' => ['ajuan_dl_id' => 'ajuan_dl_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ajuan_dl_detail_id' => 'Ajuan Dl Detail ID',
            'ajuan_dl_id' => 'Ajuan Dl ID',
            'dl_rencana_id' => 'Dl Rencana ID',
            'biaya' => 'Biaya',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDlRencana()
    {
        return $this->hasOne(MDlRencana::className(), ['dl_rencana_id' => 'dl_rencana_id']);
    }
    public function getAjuanDl()
    {
        return $this->hasOne(TAjuanDl::className(), ['ajuan_dl_id' => 'ajuan_dl_id']);
    }
}
