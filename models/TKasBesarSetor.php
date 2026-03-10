<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_kas_besar_setor".
 *
 * @property integer $kas_besar_setor_id
 * @property integer $kas_besar_id
 * @property string $kode
 * @property string $reff_no_bank
 * @property string $reff_no_dokangkut
 * @property string $tanggal
 * @property double $nominal
 * @property string $deskripsi
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property TCancelTransaksi $cancelTransaksi
 * @property TKasBesar $kasBesar
 */
class TKasBesarSetor extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_kas_besar_setor';
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
            [['kas_besar_id', 'kode', 'reff_no_bank', 'reff_no_dokangkut', 'tanggal', 'nominal', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['kas_besar_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['deskripsi'], 'string'],
            [['kode', 'reff_no_bank', 'reff_no_dokangkut'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 30],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['kas_besar_id'], 'exist', 'skipOnError' => true, 'targetClass' => TKasBesar::className(), 'targetAttribute' => ['kas_besar_id' => 'kas_besar_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'kas_besar_setor_id' => Yii::t('app', 'Kas Besar Setor'),
                'kas_besar_id' => Yii::t('app', 'Kas Besar'),
                'kode' => Yii::t('app', 'Kode'),
                'reff_no_bank' => Yii::t('app', 'Reff. No BCA'),
                'reff_no_dokangkut' => Yii::t('app', 'No. Seri Dok Angkut'),
                'tanggal' => Yii::t('app', 'Tanggal Setor'),
                'nominal' => Yii::t('app', 'Nominal Setor'),
                'deskripsi' => Yii::t('app', 'Deskripsi'),
                'status' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
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
    public function getKasBesar()
    {
        return $this->hasOne(TKasBesar::className(), ['kas_besar_id' => 'kas_besar_id']);
    }
}
