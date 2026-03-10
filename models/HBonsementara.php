<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_bonsementara".
 *
 * @property integer $bonsementara_id
 * @property string $tanggal
 * @property integer $kas_bon_id
 * @property string $kode
 * @property string $penerima
 * @property string $deskripsi
 * @property double $nominal
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $tipe
 * @property string $tanggal_kasbon
 *
 * @property TCancelTransaksi $cancelTransaksi
 * @property TKasBon $kasBon
 */ 
class HBonsementara extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_bonsementara';
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
            [['tanggal', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at', 'tanggal_kasbon'], 'safe'],
            [['kas_bon_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['deskripsi'], 'string'],
            [['nominal'], 'number'],
            [['kode', 'status', 'tipe'], 'string', 'max' => 50],
            [['penerima'], 'string', 'max' => 200],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['kas_bon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TKasBon::className(), 'targetAttribute' => ['kas_bon_id' => 'kas_bon_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'bonsementara_id' => Yii::t('app', 'Bonsementara'),
			'tanggal' => Yii::t('app', 'Tanggal'),
			'kas_bon_id' => Yii::t('app', 'Kas Bon'),
			'kode' => Yii::t('app', 'Kode'),
			'penerima' => Yii::t('app', 'Penerima'),
			'deskripsi' => Yii::t('app', 'Deskripsi'),
			'nominal' => Yii::t('app', 'Nominal'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'status' => Yii::t('app', 'Status'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'tipe' => Yii::t('app', 'Tipe'),
			'tanggal_kasbon' => Yii::t('app', 'Tanggal Kasbon'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKasBon()
    {
        return $this->hasOne(TKasBon::className(), ['kas_bon_id' => 'kas_bon_id']);
    }
}
