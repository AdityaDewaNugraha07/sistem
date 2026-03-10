<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_open_voucher_detail".
 *
 * @property integer $open_voucher_detail_id
 * @property integer $open_voucher_id
 * @property string $deskripsi
 * @property double $nominal
 * @property double $ppn
 * @property double $pph
 * @property double $subtotal
 * @property string $keterangan
 * @property string $reff_no
 *
 * @property TOpenVoucher $openVoucher
 */
class TOpenVoucherDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_open_voucher_detail';
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
            [['open_voucher_id', 'deskripsi'], 'required'],
            [['open_voucher_id'], 'integer'],
            [['deskripsi', 'keterangan', 'reff_no'], 'string'],
            [['nominal', 'ppn', 'pph', 'subtotal'], 'number'],
            [['open_voucher_id'], 'exist', 'skipOnError' => true, 'targetClass' => TOpenVoucher::className(), 'targetAttribute' => ['open_voucher_id' => 'open_voucher_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'open_voucher_detail_id' => 'Open Voucher Detail',
                'open_voucher_id' => 'Open Voucher',
                'deskripsi' => 'Deskripsi',
                'nominal' => 'Nominal',
                'ppn' => 'Ppn',
                'pph' => 'Pph',
                'subtotal' => 'Subtotal',
                'keterangan' => 'Keterangan',
                'reff_no' => 'Reff No',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVoucher()
    {
        return $this->hasOne(TOpenVoucher::className(), ['open_voucher_id' => 'open_voucher_id']);
    }
}
