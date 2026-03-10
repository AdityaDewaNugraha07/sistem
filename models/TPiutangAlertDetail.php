<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_piutang_alert_detail".
 *
 * @property integer $piutang_alert_detail_id
 * @property integer $piutang_alert_id
 * @property string $termin
 * @property integer $termin_batas
 * @property double $termin_tagihan
 * @property double $termin_terbayar
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $termin_duedate
 */
class TPiutangAlertDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $sisa_bayar,$potongan,$sisa_bayar_baru;
    public static function tableName()
    {
        return 't_piutang_alert_detail';
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
            [['piutang_alert_id', 'termin_batas', 'created_by', 'updated_by'], 'integer'],
            [['termin', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['termin_tagihan', 'termin_terbayar'], 'number'],
            [['created_at', 'updated_at', 'termin_duedate'], 'safe'],
            [['termin'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'piutang_alert_detail_id' => 'Piutang Alert Detail',
                'piutang_alert_id' => 'Piutang Alert',
                'termin' => 'Termin',
                'termin_batas' => 'Termin Batas',
                'termin_tagihan' => 'Termin Tagihan',
                'termin_terbayar' => 'Termin Terbayar',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'termin_duedate' => 'Termin Duedate',
        ];
    }
}
