<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_closing_kasir".
 *
 * @property integer $closing_kasir_id
 * @property string $kode
 * @property string $tipe
 * @property string $tanggal
 * @property double $debit
 * @property double $kredit
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 */
class TClosingKasir extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_closing_kasir';
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
            [['kode', 'tipe', 'tanggal', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['debit', 'kredit'], 'number'],
            [['created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['kode'], 'string', 'max' => 50],
            [['tipe'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'closing_kasir_id' => Yii::t('app', 'Closing Kasir'),
                'kode' => Yii::t('app', 'Kode'),
                'tipe' => Yii::t('app', 'Tipe'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'debit' => Yii::t('app', 'Debit'),
                'kredit' => Yii::t('app', 'Kredit'),
                'status' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
        ];
    }
}
