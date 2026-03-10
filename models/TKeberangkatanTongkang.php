<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_keberangkatan_tongkang".
 *
 * @property integer $keberangkatan_tongkang_id
 * @property string $kode
 * @property string $nama
 * @property string $eta
 * @property double $total_loglist
 * @property double $total_batang
 * @property double $total_m3
 * @property string $keterangan
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class TKeberangkatanTongkang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_keberangkatan_tongkang';
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
            [['kode', 'nama', 'eta', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['eta', 'created_at', 'updated_at'], 'safe'],
            [['total_loglist', 'total_batang', 'total_m3'], 'number'],
            [['keterangan'], 'string'],
            [['cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['kode'], 'string', 'max' => 25],
            [['nama'], 'string', 'max' => 200],
            [['kode'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'keberangkatan_tongkang_id' => 'Keberangkatan Tongkang',
                'kode' => 'Kode',
                'nama' => 'Nama',
                'eta' => 'Eta',
                'total_loglist' => 'Total Loglist',
                'total_batang' => 'Total Batang',
                'total_m3' => 'Total M3',
                'keterangan' => 'Keterangan',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }
	
	public static function getOptionListIncoming()
    {
        $res = self::find()->where("cancel_transaksi_id IS NULL AND keberangkatan_tongkang_id NOT IN ( SELECT keberangkatan_tongkang_id FROM t_incoming_pelabuhan WHERE cancel_transaksi_id IS NULL )")
							->orderBy('created_at DESC')->all();
		$return = [];
		foreach($res as $i => $val){
			$return[$val['keberangkatan_tongkang_id']] = $val['kode'].' - '.$val['nama'];
		}
        return $return;
    }
}
