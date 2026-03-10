<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_deposit_suplier".
 *
 * @property integer $deposit_suplier_id
 * @property string $tipe
 * @property string $tanggal
 * @property integer $suplier_id
 * @property string $reff_no
 * @property double $nominal_in
 * @property double $nominal_out
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MSuplier $suplier
 */
class HDepositSuplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_deposit_suplier';
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
            [['tipe', 'tanggal', 'suplier_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['suplier_id', 'created_by', 'updated_by'], 'integer'],
            [['nominal_in', 'nominal_out'], 'number'],
            [['keterangan'], 'string'],
            [['tipe', 'reff_no'], 'string', 'max' => 50],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'deposit_suplier_id' => 'Deposit Suplier',
                'tipe' => 'Tipe',
                'tanggal' => 'Tanggal',
                'suplier_id' => 'Suplier',
                'reff_no' => 'Reff No',
                'nominal_in' => 'Nominal In',
                'nominal_out' => 'Nominal Out',
                'keterangan' => 'Keterangan',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuplier()
    {
        return $this->hasOne(MSuplier::className(), ['suplier_id' => 'suplier_id']);
    }
}
