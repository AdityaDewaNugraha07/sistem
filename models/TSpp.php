<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spp".
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
 * @property integer $cancel_transaksi_id
 *
 * @property MDepartement $departement
 * @property MPegawai $sppDisetujui
 * @property TCancelTransaksi $cancelTransaksi
 * @property TSppDetail[] $tSppDetails
 */ 
class TSpp extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_spp';
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
            [['departement_id', 'spp_kode', 'spp_tanggal', 'spp_tanggal_dibutuhkan', 'spp_disetujui', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['departement_id', 'spp_disetujui', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['spp_tanggal', 'spp_tanggal_dibutuhkan', 'spp_tanggal_disetujui', 'created_at', 'updated_at'], 'safe'],
            [['spp_catatan'], 'string'],
            [['spp_kode', 'spp_nomor', 'spp_status'], 'string', 'max' => 30],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
            [['spp_disetujui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['spp_disetujui' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ]; 
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spp_id' => Yii::t('app', 'SPP'),
                'departement_id' => Yii::t('app', 'Departement'),
                'spp_kode' => Yii::t('app', 'Kode SPP'),
                'spp_nomor' => Yii::t('app', 'No. SPP'),
                'spp_tanggal' => Yii::t('app', 'Tanggal Permintaan'),
				'spp_tanggal_dibutuhkan' => Yii::t('app', 'Tanggal Dibutuhkan'),
                'spp_disetujui' => Yii::t('app', 'Disetujui Oleh'),
                'spp_tanggal_disetujui' => Yii::t('app', 'Tanggal Disetujui'),
                'spp_status' => Yii::t('app', 'Status'),
				'spp_catatan' => Yii::t('app', 'Catatan'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
				'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartement()
    {
        return $this->hasOne(MDepartement::className(), ['departement_id' => 'departement_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSppDisetujui()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'spp_disetujui']);
    }
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSppDetails()
    {
        return $this->hasMany(TSppDetail::className(), ['spp_id' => 'spp_id']);
    }
	
	public function getStatusSPP($id){
		$status = '';
		$totalspp = 0;
		$totalterima = 0;
		$modDetail = TSppDetail::find()->where(['spp_id'=>$id])->all();
		if(count($modDetail)>0){
			foreach($modDetail as $i => $detail){
				$totalspp += $detail->sppd_qty;
				$modmap = MapSppDetailReff::find()->where(['sppd_id'=>$detail->sppd_id])->all();
				foreach($modmap as $ii => $map){
					if(!empty($map->terima_bhpd_id)){
						$totalterima += $map->terimaBhpd->terimabhpd_qty;
					}
				}
			}
		}
		if($totalterima == 0){
			$status = '<span class="label label-info">TO-DO</span>';
		}else{
			if($totalterima < $totalspp){
				$status = '<span class="label label-default">PARTIALLY</span>';
			}else{
				$status = '<span class="label label-success">COMPLETE</span>';
			}
		}
		return $status;
	}
}
