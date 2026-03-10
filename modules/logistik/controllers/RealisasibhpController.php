<?php

namespace app\modules\logistik\controllers;
use app\models\MBrgBhp;
use app\models\TPemakaianBhpsub;
use app\models\TPemakaianBhpsubDetail;
use app\models\ViewStockItemsub;
use app\models\HPersediaanBhpSub;
use Yii;
use app\controllers\DeltaBaseController;
use app\models\TTerimaBhpSub;
use yii\db\Exception;
use yii\web\Response;
class RealisasibhpController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        
        $model = new TPemakaianBhpsub();
        $model->departement_id = Yii::$app->user->identity->pegawai->departement_id;
        $model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        if(isset($_GET['pemakaian_bhpsub_id'])){
            $model = TPemakaianBhpsub::findOne($_GET['pemakaian_bhpsub_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modDetail = TPemakaianBhpsubDetail::find()->where(['pemakaian_bhpsub_id'=>$model->pemakaian_bhpsub_id])->all();
        }
        
        if( Yii::$app->request->post('TPemakaianBhpsub')){
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $success_1 = false; // t_pemakaian_bhpsub
                $success_2 = true; // t_pemakaian_bhpsub_detail
                $success_3 = true; // h_persediaan_bhp_sub
                $model->load(Yii::$app->request->post());      
                if(!isset($_GET['edit'])){
                    // exec ini jika proses save
                    $model->kode = \app\components\DeltaGenerator::kodeRealisasiPemakaianbhp();  
					// exec ini jika proses save
                }
                if($model->validate()){             
                    if ($model->save()) {                        
                        $success_1 = true;
                        
                        if(isset($_POST['TPemakaianBhpsubDetail']) && count($_POST['TPemakaianBhpsubDetail']) > 0 ){ 
                            
                            foreach($_POST['TPemakaianBhpsubDetail'] as $i => $detail){
                                
                                $modDetail = new TPemakaianBhpsubDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->pemakaian_bhpsub_id = $model->pemakaian_bhpsub_id;
                                
                                if($modDetail->validate()){
                                    if($modDetail->save()){ 
                                        $success_2 &= true;
                                        // Start Proses Update Stock
                                        $modPersediaan = new HPersediaanBhpSub();
                                        $modPersediaan->bhp_id = $modDetail->bhp_id;
                                        $modPersediaan->waktu_transaksi = date('Y-m-d H:i:s');
                                        $modPersediaan->qty_in = 0;
                                        $modPersediaan->qty_out = $modDetail->qty;
                                        $modPersediaan->keterangan = isset($modDetail->keterangan)?$modDetail->keterangan:"";
                                        $modPersediaan->reff_no = !empty($model->kode)?$model->kode:"";
                                        $modPersediaan->reff_detail_id = !empty($modDetail->terima_bhp_sub_id)?$modDetail->terima_bhp_sub_id:"";
                                        $modPersediaan->tgl_transaksi = $model->tanggal ;
                                        
                                        if($modPersediaan->validate()){
                                            if($modPersediaan->save()){
                                                $success_3 &= true;
                                            }else{
                                                $success_3 = false;
                                            }
                                        }                                        
                                    }else{
                                        $success_2 = false;
                                    }
                                }else{
                                    $success_2 = false;
                                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                                }                                
                            }
                        }else{
                            $success_2 = false;
                            Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                        }
                    }
                }
				// echo "<pre>1";
                // print_r($success_1);
                // echo "<pre>2";
                // print_r($success_2);
                // echo "<pre>3";                
                // print_r($success_3);
                // exit;

                
                if ($success_1  && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'pemakaian_bhpsub_id'=>$model->pemakaian_bhpsub_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
                
            } catch ( yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		return $this->render('index',['model'=>$model]);
	}
    public function actionAddItem(){
        if(Yii::$app->request->isAjax){
            $modDetail = new TPemakaianBhpsubDetail();
            $modBhp = new MBrgBhp();
            $modTerimaBhpsub = new TTerimaBhpSub();
            $modStock = new ViewStockItemsub();
            $modDetail->qty = 0;
            $data['item'] = $this->renderPartial('_addItem',['modDetail'=>$modDetail,'modBhp'=>$modBhp,'modStock'=>$modStock,'modTerimaBhpsub'=>$modTerimaBhpsub]);
            return $this->asJson($data);
        }
    }
    public function actionItemInStock($disableAction=null,$tr_seq=null){
		if(Yii::$app->request->isAjax){
			if(Yii::$app->request->get('dt')=='table-produk'){
                $DepartementID = Yii::$app->user->identity->pegawai->departement_id;
				$param['table']= ViewStockItemsub::tableName();
				// $param['pk']= \app\models\ViewStockItemsub::primaryKey()[0];
				$param['pk']= "itemnumber";
				$param['column'] = ["itemnumber",                                        
                                        "reff_detail_id",
                                        $param['table'].".bhp_id",
                                        "bhp_nm",
                                        "target_plan",
                                        "target_peruntukan",
                                        "jumlah"
                                    ];    
                $param['join']= ['JOIN m_brg_bhp ON m_brg_bhp.bhp_id = '.$param['table'].'.bhp_id
								  JOIN t_terima_bhp_sub ON t_terima_bhp_sub.terima_bhp_sub_id = '.$param['table'].'.reff_detail_id
								'];
                $param['where'] = 't_terima_bhp_sub.departement_id = '.$DepartementID;	
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('itemInStock',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq]);
		}
	}
    public function actionPickItem(){
		if(Yii::$app->request->isAjax){
			$reff_detail_id = Yii::$app->request->post('reff_detail_id');
            // $bhp_id = \Yii::$app->request->post('bhp_id');
			$data = [];
			if(!empty($reff_detail_id)){
                // $model = ViewStockItemsub::findOne(['reff_detail_id'=>$reff_detail_id]);
                $sql = "SELECT 
                            view_stock_itemsub.itemnumber,
                            view_stock_itemsub.reff_detail_id,
                            view_stock_itemsub.bhp_id,
                            m_brg_bhp.bhp_nm,
                            view_stock_itemsub.jumlah
                        FROM view_stock_itemsub
                        JOIN m_brg_bhp on m_brg_bhp.bhp_id = view_stock_itemsub.bhp_id 
                        WHERE view_stock_itemsub.reff_detail_id = $reff_detail_id";
		        $data = Yii::$app->db->createCommand($sql)->queryOne();
                // $data = (!empty($model))? $model->attributes:null;                
			}
			return $this->asJson($data);
		}
    }
    function actionSetInventaris(){
		if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $dept_peruntukan = Yii::$app->request->post('dept_peruntukan');
			$data = \app\models\ViewInventaris::findAll(['peruntukan_dept'=>$dept_peruntukan]);
            return $data;
        }
    }

    function actionSetItem(){
		if(Yii::$app->request->isAjax){
            // $bhp_id = Yii::$app->request->post('bhp_id');
            $terima_bhp_sub_id = Yii::$app->request->post('terima_bhp_sub_id');
            if(!empty($terima_bhp_sub_id)) {
                // $data['bhp'] = MBrgBhp::findOne(['bhp_id'=>$bhp_id]);
                $sql = "SELECT 
                            t_terima_bhp_sub.bhp_id,
                            m_brg_bhp.bhp_nm,
                            m_brg_bhp.bhp_satuan,
                            t_terima_bhp_sub.harga_peritem,
                            t_terima_bhp_sub.target_plan,
                            t_terima_bhp_sub.target_peruntukan
                        FROM t_terima_bhp_sub
                        JOIN m_brg_bhp on t_terima_bhp_sub.bhp_id = m_brg_bhp.bhp_id 
                        WHERE t_terima_bhp_sub.terima_bhp_sub_id = $terima_bhp_sub_id";
		        $data['bhp'] = Yii::$app->db->createCommand($sql)->queryOne();
            }else{
                $data = [];
            }
            return $this->asJson($data);
        }
    }
    function actionGetItems(){
		if(Yii::$app->request->isAjax){
            $pemakaian_bhpsub_id = Yii::$app->request->post('pemakaian_bhpsub_id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
			$data['random'] = NULL;
            if(!empty($pemakaian_bhpsub_id)){
                $model = TPemakaianBhpsub::findOne($pemakaian_bhpsub_id);
                $modDetails = TPemakaianBhpsubDetail::find()->where(['pemakaian_bhpsub_id'=>$pemakaian_bhpsub_id])->all();                
            }else{
                $model = [];
                $modDetails = [];
            }
            $data['html'] = '';
            $data['kode'] = '';
            if(count($modDetails)>0){
				$v = 0;                
                foreach($modDetails as $i => $detail){
                    if(!empty($edit)){
                        // $modBhp = TTerimaBhpSub::findOne($detail->terima_bhp_sub_id);
                        $modBhp = MBrgBhp::findOne($detail->bhp_id);
                        $modTerimaBhpsub = TTerimaBhpSub::find()->select(['target_plan','target_peruntukan'])->where(['terima_bhp_sub_id'=>$detail->terima_bhp_sub_id,'bhp_id'=>$detail->bhp_id])->one();;
                        $modStock = ViewStockItemsub::find()->select(['jumlah'])->where(['reff_detail_id'=>$detail->terima_bhp_sub_id])->one();
                        // $modStock = (!empty($datamodStock))? $datamodStock->attributes : 0 ;  
                        $data['html'] .= $this->renderPartial('_addItem',['modDetail'=>$detail,'i'=>$i,'edit'=>$edit,'modBhp'=>$modBhp, 'modTerimaBhpsub'=>$modTerimaBhpsub, 'modStock'=>$modStock ,'v'=>$v]);
                    }else{
                        $data['html'] .= $this->renderPartial('_addItemAfterSave',['modDetail'=>$detail,'i'=>$i,'v'=>$v]);                        
                    }
					$v++;
                }
            }
            return $this->asJson($data);
        }
    }
    public function actionDaftarAfterSave(){
		if(Yii::$app->request->isAjax){
			if(Yii::$app->request->get('dt')=='modal-aftersave'){
                $DepartementID = Yii::$app->user->identity->pegawai->departement_id;
				$param['table']= TPemakaianBhpsub::tableName();
				$param['pk']= $param['table'].".". TPemakaianBhpsub::primaryKey()[0];
				$param['column'] = [$param['table'].'.pemakaian_bhpsub_id',
									$param['table'].'.kode',
									$param['table'].'.tanggal',
									'b.departement_nama',	
                                    'm_brg_bhp.bhp_nm',                   
									't_pemakaian_bhpsub_detail.qty',
									't_terima_bhp_sub.target_plan',
                                    't_terima_bhp_sub.target_peruntukan',
                                    'a.departement_nama as departement_peruntukan',									
                                    't_pemakaian_bhpsub_detail.reff_no',
									't_pemakaian_bhpsub_detail.keterangan',
                                    't_pemakaian_bhpsub_detail.cancel_transaksi_id',
									];
				$param['join']= ['JOIN t_pemakaian_bhpsub_detail ON t_pemakaian_bhpsub_detail.pemakaian_bhpsub_id = '.$param['table'].'.pemakaian_bhpsub_id 
								  JOIN m_departement as b ON b.departement_id = '.$param['table'].'.departement_id
                                  JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_pemakaian_bhpsub_detail.bhp_id
								  JOIN m_departement as a ON a.departement_id = t_pemakaian_bhpsub_detail.dept_peruntukan
                                  JOIN t_terima_bhp_sub ON t_terima_bhp_sub.terima_bhp_sub_id = t_pemakaian_bhpsub_detail.terima_bhp_sub_id                                  
								'];
                $param['where'] = $param['table'].'.departement_id = '.$DepartementID;	
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
    public function actionAbortItem($id,$pemakaian_bhpsub_id){
		if(Yii::$app->request->isAjax){
			$modPakaibhpsubDetail = TPemakaianBhpsubDetail::find()->where(['pemakaian_bhpsub_detail_id'=>$id])->one();
			$modPakaibhpsub = TPemakaianBhpsub::findOne($modPakaibhpsubDetail->pemakaian_bhpsub_id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_pemakaian_bhpsup_detail 
                    $success_3 = false; // h_persediaan_bhp_sub                    

                    $modCancel->load(Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $modPakaibhpsub->kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
					$modCancel->reff_detail_id = $modPakaibhpsubDetail->pemakaian_bhpsub_detail_id;
					$modCancel->bhp_id = $modPakaibhpsubDetail->bhp_id;
					$modCancel->cancel_jml = $modPakaibhpsubDetail->qty;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
							$modPakaibhpsubDetail->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
                            if($modPakaibhpsubDetail->validate()){
								$success_2 = $modPakaibhpsubDetail->save();
							}
							
							// Start Proses Update Stock
                            $modPersediaan = new HPersediaanBhpSub();
                            $modPersediaan->bhp_id = $modPakaibhpsubDetail->bhp_id;
                            $modPersediaan->waktu_transaksi = date('Y-m-d H:i:s');
                            $modPersediaan->qty_in = $modPakaibhpsubDetail->qty;
                            $modPersediaan->qty_out = 0;
                            $modPersediaan->keterangan = "Pembatalan Item Realisasi Budget BHP";
                            $modPersediaan->reff_no = !empty($modPakaibhpsub->kode)?$modPakaibhpsub->kode:"";
                            $modPersediaan->reff_detail_id = !empty($modPakaibhpsubDetail->terima_bhp_sub_id)?$modPakaibhpsubDetail->terima_bhp_sub_id:"";
                            $modPersediaan->tgl_transaksi = $modPakaibhpsub->tanggal ;
                            if($modPersediaan->validate()){
                                if($modPersediaan->save()){
                                    $success_3 = true;
                                }else{
                                    $success_3 = false;
                                }
                            }  
                            // End Proses Update Stock							
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel); 
                    }
					// echo "<pre>";
					// print_r($success_1);
					// echo "<pre>";
					// print_r($success_2);
					// echo "<pre>";
					// print_r($success_3);
                    // exit;

                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Berhasil di Batalkan');
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			
			return $this->renderAjax('_abortItem',['modPakaibhpsubDetail'=>$modPakaibhpsubDetail,'modPakaibhpsub'=>$modPakaibhpsub,'modCancel'=>$modCancel]);
		}
	}
}