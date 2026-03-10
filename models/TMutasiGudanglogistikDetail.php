<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_mutasi_gudanglogistik_detail".
 *
 * @property integer $mutasi_gudanglogistikd_id
 * @property integer $mutasi_gudanglogistik_id
 * @property integer $bhp_id
 * @property double $qty
 * @property string $satuan
 * @property string $keterangan
 *
 * @property MapSpbDetailMutasiGudanglogistikDetail[] $mapSpbDetailMutasiGudanglogistikDetails
 * @property MBrgBhp $bhp
 */
class TMutasiGudanglogistikDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $bhp_nm,$qty_in,$qty_out,$qty_termutasi,$qty_spb,$spbd_id,$spbd_qty;
    public static function tableName()
    {
        return 't_mutasi_gudanglogistik_detail';
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
            [['mutasi_gudanglogistik_id', 'bhp_id', 'qty', 'satuan'], 'required'],
            [['mutasi_gudanglogistik_id', 'bhp_id'], 'integer'],
            [['qty'], 'number'],
            [['keterangan'], 'string'],
            [['satuan'], 'string', 'max' => 50],
            [['bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgBhp::className(), 'targetAttribute' => ['bhp_id' => 'bhp_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'mutasi_gudanglogistikd_id' => Yii::t('app', 'Mutasi Gudanglogistikd'),
                'mutasi_gudanglogistik_id' => Yii::t('app', 'Mutasi Gudanglogistik'),
                'bhp_id' => Yii::t('app', 'Bhp'),
                'qty' => Yii::t('app', 'Qty'),
                'satuan' => Yii::t('app', 'Satuan'),
                'keterangan' => Yii::t('app', 'Keterangan'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapSpbDetailMutasiGudanglogistikDetails()
    {
        return $this->hasMany(MapSpbDetailMutasiGudanglogistikDetail::className(), ['mutasi_gudanglogistikd_id' => 'mutasi_gudanglogistikd_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBhp()
    {
        return $this->hasOne(MBrgBhp::className(), ['bhp_id' => 'bhp_id']);
    }
}
