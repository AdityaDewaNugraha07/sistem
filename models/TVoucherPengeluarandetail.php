<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_voucher_pengeluarandetail".
 *
 * @property integer $voucher_detail_id
 * @property integer $voucher_pengeluaran_id
 * @property integer $acct_id
 * @property string $keterangan
 * @property integer $jumlah
 *
 * @property TVoucherPengeluaran $voucherPengeluaran
 */
class TVoucherPengeluarandetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_voucher_pengeluarandetail';
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
            [['voucher_pengeluaran_id', 'keterangan', 'jumlah'], 'required'],
            [['voucher_pengeluaran_id', 'acct_id'], 'integer'],
            [['jumlah'], 'number'],
            [['keterangan'], 'string'],
            [['voucher_pengeluaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => TVoucherPengeluaran::className(), 'targetAttribute' => ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'voucher_detail_id' => Yii::t('app', 'Voucher Detail'),
                'voucher_pengeluaran_id' => Yii::t('app', 'Voucher Pengeluaran'),
                'acct_id' => Yii::t('app', 'Acct'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'jumlah' => Yii::t('app', 'Jumlah'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherPengeluaran()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
    }
}
