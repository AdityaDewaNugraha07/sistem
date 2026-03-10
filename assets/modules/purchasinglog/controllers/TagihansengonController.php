<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class TagihansengonController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TTagihanSengon();
        $model->kode = 'AUTO GENERATE';
        $model->tanggal = date("d/m/Y");
        $model->total_pcs = 0;
        $model->total_m3 = 0;
        $model->total_bayar = 0;
        
		if(isset($_GET['tagihan_sengon_id'])){
            $model = \app\models\TTagihanSengon::findOne($_GET['tagihan_sengon_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            if($model->bayar_langsung == true){
                $model->kode_po = $model->posengon->kode;
            }else{
                $model->kode_po = $model->posengon->kode;
                $model->kode_terima = $model->terimaSengon->kode;
            }
            $model->suplier_nm = $model->suplier->suplier_nm;
            $model->suplier_almt = $model->suplier->suplier_almt;
        }
        
		if( Yii::$app->request->post('TTagihanSengon') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_tagihan_sengon
                $success_2 = false; // h_saldo_suplier
                $model->load(\Yii::$app->request->post());
                if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeTagihanSengon();
					$model->tanggal = date('d/m/Y');
				}
                $diameter_harga = [];
                foreach($_POST['TTagihanSengon'] as $i => $post){
                    if(is_array($post)){
                        $diameter_harga[] = $post;
                    }
                }
                $model->diameter_harga = \yii\helpers\Json::encode($diameter_harga);
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        
                        // START INSERT OUT h_saldo_suplier
                        if(isset($_GET['edit'])){
                            \app\models\HSaldoSuplier::deleteAll("reff_no = '".$model->kode."'");
                        }
                        $modSaldo = new \app\models\HSaldoSuplier();
                        $modSaldo->tipe = "LS";
                        $modSaldo->tanggal = $model->tanggal;
                        $modSaldo->suplier_id = $model->suplier_id;
                        $modSaldo->reff_no = $model->kode;
                        $modSaldo->deskripsi = "TAGIHAN LOG SENGON ".$model->kode;
                        $modSaldo->nominal_in = 0;
                        $modSaldo->nominal_out = $model->total_bayar;
                        $modSaldo->active = true;
                        if($modSaldo->validate()){
                            if($modSaldo->save()){
                                $success_2 = true;
                            }
                        }
                        // END INSERT OUT  h_saldo_suplier
                    }
                }
//                echo "<pre>1 = ";
//                print_r($success_1);
//                echo "<pre>2 = ";
//                print_r($success_2);
//                exit;
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE));
                    return $this->redirect(['index','success'=>1,'tagihan_sengon_id'=>$model->tagihan_sengon_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		
		return $this->render('index',['model'=>$model]);
	}
    
    public function actionSetHeader(){
        if(\Yii::$app->request->isAjax){
			$kode_po = Yii::$app->request->post('kode_po');
			$kode_terima = Yii::$app->request->post('kode_terima');
            $data = [];
            if(!empty($kode_po)){
                $modPo = \app\models\TPosengon::findOne(['kode'=>$kode_po]);
                $modPo->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($modPo->tanggal);
                $data['po'] = $modPo->attributes;
            }
            if(!empty($kode_terima)){
                $modTerima = \app\models\TTerimaSengon::findOne(['kode'=>$kode_terima]);
                $modTerima->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($modTerima->tanggal);
                $data['terima'] = $modTerima->attributes;
                $modPo = \app\models\TPosengon::findOne($modTerima->posengon_id);
                $modPo->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($modPo->tanggal);
                $data['po'] = $modTerima->attributes;
            }
            $modSuplier = \app\models\MSuplier::findOne($modPo->suplier_id);
            $data['suplier'] = $modSuplier->attributes;
            
			return $this->asJson($data);
		}
    }
    
    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TTagihanSengon();
            $model->diameter_awal = 0;
            $model->diameter_akhir = 0;
            $model->pcs = 0;
            $model->m3 = 0;
            $model->harga = 0;
            $model->subtotal = 0;
            $model->pph = 0;
            $model->bayar = 0;
            $data['html'] = $this->renderPartial('_item',['model'=>$model]);
            return $this->asJson($data);
        }
    }
    
    public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $tagihan_sengon_id = Yii::$app->request->post('id'); 
            $edit = Yii::$app->request->post('edit'); 
            $data = []; $data['html'] = '';
            if(!empty($tagihan_sengon_id)){
                $model = \app\models\TTagihanSengon::findOne($tagihan_sengon_id);
                if(!empty($model)){
                    $diameter_harga = \yii\helpers\Json::decode($model->diameter_harga);
                    if(count($diameter_harga)>0){
                        foreach($diameter_harga as $i => $dia){
                            $model->attributes = $dia;
                            $model->panjang = $dia['panjang'];
                            $model->wilayah = $dia['wilayah'];
                            $model->diameter_awal = $dia['diameter_awal'];
                            $model->diameter_akhir = $dia['diameter_akhir'];
                            $model->pcs = $dia['pcs'];
                            $model->m3 = $dia['m3'];
                            $model->harga = number_format($dia['harga']);
                            $data['html'] .= $this->renderPartial('_item',['model'=>$model,'edit'=>$edit]);
                        }
                    }
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
            $pick = \Yii::$app->request->get('pick');
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TTagihanSengon::tableName();
				$param['pk']= $param['table'].".".\app\models\TTagihanSengon::primaryKey()[0];
				$param['column'] = [$param['table'].'.tagihan_sengon_id',
                                    $param['table'].'.kode',
                                    $param['table'].'.tanggal',
                                    't_posengon.kode AS kode_po',
                                    't_terima_sengon.kode AS kode_terima',
                                    'm_suplier.suplier_nm',
                                    'm_suplier.suplier_almt',
                                    $param['table'].'.reff_no',
                                    $param['table'].'.bayar_langsung',
                                    $param['table'].'.total_pcs',
                                    $param['table'].'.total_m3',
                                    $param['table'].'.total_bayar',
                                    '(SELECT reff_no2 FROM t_open_voucher WHERE reff_no2 = t_tagihan_sengon.kode) AS kode_tagihan_sengon',
                                    $param['table'].'.suplier_id',
                                    $param['table'].'.posengon_id',
                                    $param['table'].'.terima_sengon_id',
									];
				$param['join']= ['JOIN t_posengon ON t_posengon.posengon_id = '.$param['table'].'.posengon_id 
								  LEFT JOIN t_terima_sengon ON t_terima_sengon.terima_sengon_id = '.$param['table'].'.terima_sengon_id
                                  JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id
                                '];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave',['pick'=>$pick]);
        }
    }
}
