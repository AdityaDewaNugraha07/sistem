<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pengembalian_log_detail".
 *
 * @property integer $pengembalian_log_detail_id
 * @property integer $pengembalian_log_id
 * @property integer $kayu_id
 * @property string $no_barcode
 * @property string $alasan_pengembalian
 * @property boolean $status_penerimaan
 * @property string $tanggal_penerimaan
 * @property integer $penerima
 * @property string $catatan_penerimaan
 *
 * @property MKayu $kayu
 * @property MPegawai $penerima0
 */
class TPengembalianLogDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_pengembalian_log_detail';
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
            [['pengembalian_log_id', 'kayu_id', 'no_barcode'], 'required'],
            [['pengembalian_log_id', 'kayu_id', 'penerima'], 'integer'],
            [['alasan_pengembalian', 'catatan_penerimaan'], 'string'],
            [['status_penerimaan'], 'boolean'],
            [['tanggal_penerimaan'], 'safe'],
            [['no_barcode'], 'string', 'max' => 50],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
            [['penerima'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['penerima' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengembalian_log_detail_id' => 'Pengembalian Log Detail',
                'pengembalian_log_id' => 'Pengembalian Log',
                'kayu_id' => 'Kayu',
                'no_barcode' => 'No Barcode',
                'alasan_pengembalian' => 'Alasan Pengembalian',
                'status_penerimaan' => 'Status Penerimaan',
                'tanggal_penerimaan' => 'Tanggal Penerimaan',
                'penerima' => 'Penerima',
                'catatan_penerimaan' => 'Catatan Penerimaan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKayu()
    {
        return $this->hasOne(MKayu::className(), ['kayu_id' => 'kayu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenerima0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'penerima']);
    }
} 