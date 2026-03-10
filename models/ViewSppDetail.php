<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_spp_detail".
 *
 * @property integer $spp_id
 * @property integer $departement_id
 * @property string $spp_kode
 * @property string $spp_nomor
 * @property string $spp_tanggal
 * @property string $spp_tanggal_dibutuhkan
 * @property integer $spp_disetujui
 * @property string $spp_tanggal_disetujui
 * @property string $spp_status
 * @property string $spp_catatan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $sppd_id
 * @property integer $bhp_id
 * @property double $sppd_qty
 * @property string $sppd_ket
 * @property integer $suplier_id
 */
class ViewSppDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_spp_detail';
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
            [['spp_id', 'departement_id', 'spp_disetujui', 'created_by', 'updated_by', 'sppd_id', 'bhp_id', 'suplier_id'], 'integer'],
            [['spp_tanggal', 'spp_tanggal_dibutuhkan', 'spp_tanggal_disetujui', 'created_at', 'updated_at'], 'safe'],
            [['spp_catatan', 'sppd_ket'], 'string'],
            [['sppd_qty'], 'number'],
            [['spp_kode', 'spp_nomor', 'spp_status'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spp_id' => Yii::t('app', 'Spp'),
                'departement_id' => Yii::t('app', 'Departement'),
                'spp_kode' => Yii::t('app', 'Spp Kode'),
                'spp_nomor' => Yii::t('app', 'Spp Nomor'),
                'spp_tanggal' => Yii::t('app', 'Spp Tanggal'),
                'spp_tanggal_dibutuhkan' => Yii::t('app', 'Spp Tanggal Dibutuhkan'),
                'spp_disetujui' => Yii::t('app', 'Spp Disetujui'),
                'spp_tanggal_disetujui' => Yii::t('app', 'Spp Tanggal Disetujui'),
                'spp_status' => Yii::t('app', 'Spp Status'),
                'spp_catatan' => Yii::t('app', 'Spp Catatan'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'sppd_id' => Yii::t('app', 'Sppd'),
                'bhp_id' => Yii::t('app', 'Bhp'),
                'sppd_qty' => Yii::t('app', 'Sppd Qty'),
                'sppd_ket' => Yii::t('app', 'Sppd Ket'),
                'suplier_id' => Yii::t('app', 'Suplier'),
        ];
    }
}
