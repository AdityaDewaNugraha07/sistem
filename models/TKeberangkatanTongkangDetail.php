<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_keberangkatan_tongkang_detail".
 *
 * @property integer $keberangkatan_tongkang_detail_id
 * @property integer $keberangkatan_tongkang_id
 * @property integer $loglist_id
 * @property string $lokasi_muat
 * @property string $tanggal_muat
 * @property double $qty_batang
 * @property double $qty_m3
 * @property string $keterangan
 *
 * @property TLoglist $loglist
 */
class TKeberangkatanTongkangDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_keberangkatan_tongkang_detail';
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
            [['keberangkatan_tongkang_id', 'loglist_id', 'lokasi_muat', 'tanggal_muat'], 'required'],
            [['keberangkatan_tongkang_id', 'loglist_id'], 'integer'],
            [['tanggal_muat'], 'safe'],
            [['qty_batang', 'qty_m3'], 'number'],
            [['keterangan'], 'string'],
            [['lokasi_muat'], 'string', 'max' => 200],
            [['loglist_id'], 'exist', 'skipOnError' => true, 'targetClass' => TLoglist::className(), 'targetAttribute' => ['loglist_id' => 'loglist_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'keberangkatan_tongkang_detail_id' => 'Keberangkatan Tongkang Detail',
                'keberangkatan_tongkang_id' => 'Keberangkatan Tongkang',
                'loglist_id' => 'Loglist',
                'lokasi_muat' => 'Lokasi Muat',
                'tanggal_muat' => 'Tanggal Muat',
                'qty_batang' => 'Qty Batang',
                'qty_m3' => 'Qty M3',
                'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoglist()
    {
        return $this->hasOne(TLoglist::className(), ['loglist_id' => 'loglist_id']);
    }
}
