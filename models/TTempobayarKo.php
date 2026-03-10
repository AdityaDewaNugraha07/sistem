<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_tempobayar_ko".
 *
 * @property integer $tempobayar_ko_id
 * @property string $kode
 * @property integer $op_ko_id
 * @property string $jenis_produk
 * @property double $maks_top_hari
 * @property double $top_hari
 * @property double $maks_plafon
 * @property double $op_aktif
 * @property double $sisa_piutang
 * @property double $sisa_plafon
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property TCancelTransaksi $cancelTransaksi
 * @property TOpKo $opKo
 */ 
class TTempobayarKo extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $top_master;
    public static function tableName()
    {
        return 't_tempobayar_ko';
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
            [['kode', 'op_ko_id', 'jenis_produk', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['op_ko_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['maks_top_hari', 'top_hari', 'maks_plafon', 'op_aktif', 'sisa_piutang', 'sisa_plafon'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode', 'jenis_produk'], 'string', 'max' => 50],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['op_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TOpKo::className(), 'targetAttribute' => ['op_ko_id' => 'op_ko_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'tempobayar_ko_id' => Yii::t('app', 'Tempobayar Ko'),
                'kode' => Yii::t('app', 'Kode'),
                'op_ko_id' => Yii::t('app', 'Op Ko'),
                'jenis_produk' => Yii::t('app', 'Jenis Produk'),
				'maks_top_hari' => Yii::t('app', 'Maks Top Hari'),
                'top_hari' => Yii::t('app', 'Top Hari'),
                'maks_plafon' => Yii::t('app', 'Maks Plafon'),
				'op_aktif' => Yii::t('app', 'Op Aktif'),
                'sisa_piutang' => Yii::t('app', 'Sisa Piutang'),
                'sisa_plafon' => Yii::t('app', 'Sisa Plafon'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
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
    public function getOpKo()
    {
        return $this->hasOne(TOpKo::className(), ['op_ko_id' => 'op_ko_id']);
    }
}
