<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_rekap_penerimaan_bhpsub".
 *
 * @property integer $departement_id
 * @property string $periode_bulan
 * @property string $target_plan
 * @property string $target_peruntukan
 * @property double $penerimaan
 */
class ViewRekapPenerimaanBhpsub extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_rekap_penerimaan_bhpsub';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['departement_id'], 'integer'],
            [['periode_bulan'], 'string'],
            [['penerimaan'], 'number'],
            [['target_plan', 'target_peruntukan'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'departement_id' => 'Departement ID',
            'periode_bulan' => 'Periode Bulan',
            'target_plan' => 'Target Plan',
            'target_peruntukan' => 'Target Peruntukan',
            'penerimaan' => 'Penerimaan',
        ];
    }
}
