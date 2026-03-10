<?php

namespace app\modules\logistik\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PenerimaanspbController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
//	public function actionIndex(){
//        if(\Yii::$app->request->get('dt')=='table-penerimaan'){
//			$param['table']= \app\models\TSpb::tableName();
//			$param['pk']= \app\models\TSpb::primaryKey()[0];
//			$param['column'] = ['spb_id','spb_kode','spb_nomor',['col_name'=>'spb_tanggal','formatter'=>'formatDateForUser2'],'spb_tipe','departement_nama','spb_status'];
//            $param['join']= ['JOIN m_departement ON m_departement.departement_id = '.$param['table'].'.departement_id'];
//			if(Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER){
//				$param['where'] = "approve_status = '".\app\models\TApproval::STATUS_APPROVED."'";
//			}
//			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
//		}
//        
//		return $this->render('index');
//	}
	
	public function actionIndex(){
        $model = new \app\models\TSpb();
		$model->tgl_awal = date('d/m/Y',strtotime("-3 day"));
		$model->tgl_akhir = date('d/m/Y');
        if(\Yii::$app->request->get('dt')=='table-penerimaan'){
			if((\Yii::$app->request->get('laporan_params')) !== null){ 
				$form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params);
				$model->attributes = $form_params['TSpb'];
				$model->tgl_awal = $form_params['TSpb']['tgl_awal'];
				$model->tgl_akhir = $form_params['TSpb']['tgl_akhir'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchLaporanDt() ));
		}
		return $this->render('index',['model'=>$model]);
	}
    
    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpb::findOne($id);
            $modDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$id])->all();
			return $this->renderAjax('info',['model'=>$model,'modDetail'=>$modDetail]);
		}
	}
    
    public function actionTolakSpb(){
        if(\Yii::$app->request->isAjax){
            $spb_id = Yii::$app->request->post('spb_id');
			$model = \app\models\TSpb::findOne($spb_id);
			$model->spb_status = 'DITOLAK';
            if($model->validate()){
                if($model->save()){
                    $data = true;
                }else{
                    $data = false;
                }
            }else{
                $data = false;
            }
            return $this->asJson($data);
		}
    }
	
	public function actionSetDropdownPegawai(){
        if(\Yii::$app->request->isAjax){
			$dept_id = Yii::$app->request->post('dept_id');
            $mod = [];
			if(!empty($dept_id)){
				$mod = \app\models\MPegawai::find()->where(['active'=>true,'departement_id'=>$dept_id])->orderBy(['pegawai_nama'=>SORT_ASC])->all();
			}else{
				$mod = \app\models\MPegawai::find()->where(['active'=>true,''])->orderBy(['pegawai_nama'=>SORT_ASC])->all();
			}
			$arraymap = \yii\helpers\ArrayHelper::map($mod, 'pegawai_id', 'pegawai_nama');
			$html = \yii\bootstrap\Html::tag('option','All',['value'=>'']);
			foreach($arraymap as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	
	public function actionPermintaanMutasi($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpb::findOne($id);
			$pesan = "Yakin akan melakukan <b>Permintaan Mutasi Gudang Logistik</b> pada SPB : <b>".$model->spb_kode."</b> ini?";
            if( Yii::$app->request->post('updaterecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					if(!empty($model)){
						$model->mutation_req = TRUE;
						if($model->validate()){
							if($model->save()){
								$success_1 = true;
							}
						}
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['message'] = "SPB Telah Diajukan Mutasi";
						$data['callback'] = '$( "#close-btn-globalconfirm" ).click(); info("'.$id.'");';
					} else {
						$transaction->rollback();
						$data['status'] = false;
						(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');
					}
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('@views/apps/partial/_globalConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'PermintaanMutasi']);
		}
	}
}
