<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_tagihan_sengon".
 *
 * @property integer $tagihan_sengon_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $suplier_id
 * @property integer $posengon_id
 * @property integer $terima_sengon_id
 * @property string $reff_no
 * @property string $reff_no2
 * @property boolean $bayar_langsung
 * @property string $cara_bayar
 * @property string $suplier_norekening
 * @property string $suplier_bank
 * @property string $suplier_an_rekening
 * @property string $suplier_npwp
 * @property string $diameter_harga
 * @property double $total_pcs
 * @property double $total_m3
 * @property double $total_bayar
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MSuplier $suplier
 * @property TPosengon $posengon
 * @property TTerimaSengon $terimaSengon
 */
class TTagihanSengon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $suplier_nm,$suplier_almt,$kode_po,$kode_terima,$panjang,$wilayah,$diameter_awal,$diameter_akhir,$pcs,$m3,$harga,$subtotal,$pph,$bayar;
    public static function tableName()
    {
        return 't_tagihan_sengon';
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
            [['kode', 'tanggal', 'suplier_id', 'posengon_id', 'reff_no', 'cara_bayar', 'diameter_harga', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['suplier_id', 'posengon_id', 'terima_sengon_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['bayar_langsung'], 'boolean'],
            [['diameter_harga'], 'string'],
            [['total_pcs', 'total_m3', 'total_bayar'], 'number'],
            [['kode', 'cara_bayar', 'status'], 'string', 'max' => 30],
            [['reff_no', 'reff_no2', 'suplier_norekening', 'suplier_bank', 'suplier_an_rekening', 'suplier_npwp'], 'string', 'max' => 50],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['posengon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPosengon::className(), 'targetAttribute' => ['posengon_id' => 'posengon_id']],
            [['terima_sengon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaSengon::className(), 'targetAttribute' => ['terima_sengon_id' => 'terima_sengon_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'tagihan_sengon_id' => 'Tagihan Sengon',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'suplier_id' => 'Suplier',
                'posengon_id' => 'Posengon',
                'terima_sengon_id' => 'Terima Sengon',
                'reff_no' => 'Reff No',
                'reff_no2' => 'Reff No2',
                'bayar_langsung' => 'Bayar Langsung',
                'cara_bayar' => 'Cara Bayar',
                'suplier_norekening' => 'Suplier Norekening',
                'suplier_bank' => 'Suplier Bank',
                'suplier_an_rekening' => 'Suplier An Rekening',
                'suplier_npwp' => 'Suplier NPWP',
                'diameter_harga' => 'Diameter Harga',
                'total_pcs' => 'Total Pcs',
                'total_m3' => 'Total M3',
                'total_bayar' => 'Total Bayar',
                'status' => 'Status',
                'cancel_transaksi_id' => 'Cancel Transaksi',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosengon()
    {
        return $this->hasOne(TPosengon::className(), ['posengon_id' => 'posengon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerimaSengon()
    {
        return $this->hasOne(TTerimaSengon::className(), ['terima_sengon_id' => 'terima_sengon_id']);
    }
}
