<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_surat_pengantar_detail".
 *
 * @property integer $surat_pengantar_detail_id
 * @property integer $surat_pengantar_id
 * @property integer $produk_id
 * @property double $qty_besar
 * @property string $satuan_besar
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 * @property string $keterangan
 * @property integer $spm_log_id
 *
 * @property MBrgProduk $produk
 * @property TSuratPengantar $suratPengantar
 * @property MBrgLog $log
 */
class TSuratPengantarDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_surat_pengantar_detail';
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
            [['surat_pengantar_id', 'produk_id', 'qty_besar', 'satuan_besar', 'qty_kecil', 'satuan_kecil', 'kubikasi'], 'required'],
            [['surat_pengantar_id', 'produk_id', 'spm_log_id'], 'integer'],
            [['qty_besar', 'qty_kecil', 'kubikasi'], 'number'],
            [['keterangan'], 'string'],
            [['satuan_besar', 'satuan_kecil'], 'string', 'max' => 50],
//            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['surat_pengantar_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSuratPengantar::className(), 'targetAttribute' => ['surat_pengantar_id' => 'surat_pengantar_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'surat_pengantar_detail_id' => Yii::t('app', 'Surat Pengantar Detail'),
                'surat_pengantar_id' => Yii::t('app', 'Surat Pengantar'),
                'produk_id' => Yii::t('app', 'Produk'),
                'qty_besar' => Yii::t('app', 'Qty Besar'),
                'satuan_besar' => Yii::t('app', 'Satuan Besar'),
                'qty_kecil' => Yii::t('app', 'Qty Kecil'),
                'satuan_kecil' => Yii::t('app', 'Satuan Kecil'),
                'kubikasi' => Yii::t('app', 'Kubikasi'),
                'keterangan' => Yii::t('app', 'Keterangan'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduk()
    {
        return $this->hasOne(MBrgProduk::className(), ['produk_id' => 'produk_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLimbah()
    {
        return $this->hasOne(MBrgLimbah::className(), ['limbah_id' => 'produk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuratPengantar()
    {
        return $this->hasOne(TSuratPengantar::className(), ['surat_pengantar_id' => 'surat_pengantar_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdukJasa()
    {
        return $this->hasOne(MProdukJasa::className(), ['produk_jasa_id' => 'produk_id']);
    }

    public function getLog()
    {
        return $this->hasOne(MBrgLog::className(), ['log_id' => 'produk_id']);
    }
}
