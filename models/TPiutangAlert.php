<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_piutang_alert".
 *
 * @property integer $piutang_alert_id
 * @property string $piutang_alert_tgl
 * @property integer $piutang_jenis
 * @property integer $piutang_nota
 * @property string $piutang_nomor_nota
 * @property string $tgl_nota
 * @property integer $customer_id
 * @property double $tagihan_jml
 * @property integer $tempo_bayar
 * @property double $dp_terbayar
 * @property string $piutang_ket
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $kode
 * @property boolean $status_approve
 * @property integer $disetujui
 * @property integer $mengetahui
 * @property mixed|null $cust
 */
class TPiutangAlert extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $potongan,$sisa_bayar_baru;
    public static function tableName()
    {
        return 't_piutang_alert';
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
            [['piutang_alert_tgl', 'tgl_nota', 'created_at', 'updated_at'], 'safe'],
            [['piutang_jenis', 'piutang_nota', 'customer_id', 'tempo_bayar', 'created_by', 'updated_by', 'disetujui', 'mengetahui'], 'integer'],
            [['piutang_nomor_nota', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tagihan_jml', 'dp_terbayar'], 'number'],
            [['piutang_ket'], 'string'],
            [['status_approve'], 'boolean'],
            [['piutang_nomor_nota'], 'string', 'max' => 50],
            [['kode'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'piutang_alert_id' => 'Piutang Alert',
                'piutang_alert_tgl' => 'Piutang Alert Tgl',
                'piutang_jenis' => 'Piutang Jenis',
                'piutang_nota' => 'Piutang Nota',
                'piutang_nomor_nota' => 'Piutang Nomor Nota',
                'tgl_nota' => 'Tgl Nota',
                'customer_id' => 'Customer',
                'tagihan_jml' => 'Tagihan Jml',
                'tempo_bayar' => 'Tempo Bayar',
                'dp_terbayar' => 'Dp Terbayar',
                'piutang_ket' => 'Piutang Ket',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'kode' => 'Kode',
                'status_approve' => 'Status Approve',
                'disetujui' => 'Disetujui',
                'mengetahui' => 'Mengetahui',
        ];
    }
    
    public function getCust()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'customer_id']);
    }
    
    function getDisetujui0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'disetujui']);
    }

    public function getMengetahui()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'mengetahui']);
    }
}