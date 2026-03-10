<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_uangtunai".
 *
 * @property integer $uangtunai_id
 * @property string $tanggal
 * @property double $nominal
 * @property double $qty
 * @property double $subtotal
 * @property boolean $closing
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $tipe
 *
 * @property TCancelTransaksi $cancelTransaksi
 */ 
class TUangtunai extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_uangtunai';
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
            [['tanggal', 'nominal', 'qty', 'subtotal', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['nominal', 'qty', 'subtotal'], 'number'],
            [['closing'], 'boolean'],
            [['created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['status', 'tipe'], 'string', 'max' => 50],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'uangtunai_id' => Yii::t('app', 'Uangtunai'),
			'tanggal' => Yii::t('app', 'Tanggal'),
			'nominal' => Yii::t('app', 'Nominal'),
			'qty' => Yii::t('app', 'Qty'),
			'subtotal' => Yii::t('app', 'Subtotal'),
			'closing' => Yii::t('app', 'Closing'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'status' => Yii::t('app', 'Status'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'tipe' => Yii::t('app', 'Tipe'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }
}
