<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_incoming_dkb".
 *
 * @property integer $incoming_dkb_id
 * @property integer $loglist_id
 * @property integer $kode_partai
 * @property string $no_grade
 * @property string $no_barcode
 * @property string $no_btg
 * @property string $no_lap
 * @property integer $kayu_id
 * @property double $panjang
 * @property double $diameter
 * @property double $volume
 * @property string $kondisi
 * @property integer $pot
 * @property string $asal_kayu
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MKayu $kayu
 * @property TIncomingPelabuhan $incomingPelabuhan
 */
class TIncomingDkb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $nomor_kontrak,$lokasi_muat,$total_loglist,$total_batang,$total_m3,$pihak1_perusahaan,$lokasi_bongkar;
    public static function tableName()
    {
        return 't_incoming_dkb';
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
            [['loglist_id', 'kode_partai', 'no_grade', 'no_barcode', 'no_btg', 'kayu_id', 'created_at', 'created_by', 'updated_at', 'updated_by','lokasi_bongkar'], 'required'],
            [['loglist_id', 'kayu_id', 'pot', 'created_by', 'updated_by'], 'integer'],
            [['panjang', 'diameter', 'volume'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['no_barcode'], 'unique'],
            [['kode_partai', 'no_grade', 'no_barcode', 'no_btg', 'no_lap', 'kondisi', 'asal_kayu', 'status'], 'string', 'max' => 50],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
            [['loglist_id'], 'exist', 'skipOnError' => true, 'targetClass' => TLoglist::className(), 'targetAttribute' => ['loglist_id' => 'loglist_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'incoming_dkb_id' => 'Incoming Dkb',
                'loglist_id' => 'Loglist',
                'kode_partai' => 'Kode Partai',
                'no_grade' => 'No Grade',
                'no_barcode' => 'No Barcode',
                'no_btg' => 'No Btg',
                'no_lap' => 'No Lap',
                'kayu_id' => 'Kayu',
                'panjang' => 'Panjang',
                'diameter' => 'Diameter',
                'volume' => 'Volume',
                'kondisi' => 'Kondisi',
                'pot' => 'Pot',
                'asal_kayu' => 'Asal Kayu',
                'status' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
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
    public function getLoglist()
    {
        return $this->hasOne(TLoglist::className(), ['loglist_id' => 'loglist_id']);
    }
}
