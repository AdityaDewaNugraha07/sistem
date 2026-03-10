<?php

namespace app\modules\marketing\controllers;

use AllowDynamicProperties;
use app\components\DeltaFormatter;
use app\components\Params;
use app\components\SSP;
use yii\helpers\Json;
use app\models\TPoKo;
use app\models\TPoKoDetail;
use app\models\TAttachment;
use app\models\TApproval;
use Yii;
use app\controllers\DeltaBaseController;
use app\models\MBrgLog;
use app\models\MCustomer;
use yii\db\Exception;
use yii\web\Response;

class PurchaseorderController extends DeltaBaseController
{

	public $defaultAction = 'index';

    public function actionIndex(){
        $model                      = new TPoKo();
        $model->kode                = 'Auto Generate';
        $model->syarat_jual         = "Loco";
        $model->cara_bayar          = "Transfer Bank";
        $modAttachment              = new TAttachment();
        $model->jenis_produk        = 'Log';
        $model->tanggal             = date('d/m/Y');
        $model->tanggal_kirim       = date('d/m/Y');
        $model->tanggal_po          = date('d/m/Y');
        $model->tanggal_bayarmax    = date('d/m/Y');
        $model->provinsi_bongkar    = 'JAWA TENGAH';
        $model->sales_id            = 22;
		$model->top_hari            = 0;

        if(isset($_GET['po_ko_id'])){
            $model                  = TPoKo::findOne($_GET['po_ko_id']);
            $model->tanggal         = DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->tanggal_po      = DeltaFormatter::formatDateTimeForUser2($model->tanggal_po);
			$model->tanggal_kirim   = DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);
			// $model->tanggal_bayarmax= DeltaFormatter::formatDateTimeForUser2($model->tanggal_bayarmax);  
            $modDetail              = TPoKoDetail::findAll(['po_ko_id'=>$_GET['po_ko_id']]);      
            $model->customer        = $model->cust->cust_an_nama. (!empty($model->cust->cust_pr_nama)?" - ".$model->cust->cust_pr_nama:"");

            $file   = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>1, 'active'=>'true']);
			$file1  = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>2, 'active'=>'true']);
			$file2  = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>3, 'active'=>'true']);
        } else {
			$file = '';
			$file1 = '';
			$file2 = '';
            $modDetail = '';
		}

        if( Yii::$app->request->post('TPoKo')){
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                $success_1 = false;     // t_po_ko
                $success_2 = true;      // t_po_ko_detail
                $success_3 = false;     // t_approval
                $success_4 = false;     // t_attachment

                $model->load(\Yii::$app->request->post());
				$model->status_approval = "Not Confirmed";
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodePO();
				}

				if($_POST['TPoKo']['sistem_bayar'] == 'Tempo'){
					$maks_plafon = isset($_POST['TPoKo']['maks_plafon']) ? $_POST['TPoKo']['maks_plafon'] : '0';
					$piutang_aktif = isset($_POST['TPoKo']['sisa_piutang']) ? $_POST['TPoKo']['sisa_piutang'] : '0';
					$op_aktif = isset($_POST['TPoKo']['op_aktif']) ? $_POST['TPoKo']['op_aktif'] : '0';
					$sisa_plafon = isset($_POST['TPoKo']['sisa_plafon']) ? $_POST['TPoKo']['sisa_plafon'] : '0';
					$arrPost = ['maks_plafon'=> $maks_plafon,
								'piutang_aktif'=> $piutang_aktif,
								'op_aktif'=> $op_aktif,
								'sisa_plafon'=>$sisa_plafon
								];
					$piutang[0] = $arrPost;
					$model->data_piutang = \yii\helpers\Json::encode($piutang);
				} else {
					$model->data_piutang = null;
				}

                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;

                        if(isset($_GET['edit'])){ // jika proses edit
                            $success_2 = (TPoKoDetail::deleteAll("po_ko_id = ".$model->po_ko_id))?true:false;
						}

                        foreach($_POST['TPoKoDetail'] as $i => $detail){
							$modDetail = new TPoKoDetail();
							$modDetail->attributes = $detail;
							$modDetail->po_ko_id = $model->po_ko_id;
                            $modDetail->qty_besar = 0;
                            $modDetail->qty_kecil = 0;
                            $modDetail->satuan_besar = 'M3';
                            $modDetail->satuan_kecil = 'pcs';
							$produk_id_alias= isset($_POST['TPoKoDetail'][$i]['produk_id_alias']) ? $_POST['TPoKoDetail'][$i]['produk_id_alias'] : [];
                			$modDetail->produk_id_alias = $produk_id_alias?implode(',', $produk_id_alias):null;

                            if($modDetail->validate()){
								if($modDetail->save()){
									$success_2 &= true;
								}else{
									$success_2 = false;
								}
							}else{
								$success_2 = false;
							}
						}

						$dir_path = Yii::$app->basePath.'/web/uploads/mkt/purchaseorder';
                        if(isset($_FILES['TAttachment'])){
							$success_4 = true;
							$files = [];
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file1');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file2');
							$files[] = \yii\web\UploadedFile::getInstance($modAttachment, 'file3');

							foreach($files as $i => $file){
								if(!empty($file)){
									$modAttachment = new TAttachment();
									$modAttachment->reff_no = $model->kode;
									$modAttachment->file_type = $file->type;
									$modAttachment->file_ext = $file->extension;
									$modAttachment->file_size = $file->size;
									$modAttachment->dir_path = $dir_path;
									$modAttachment->seq = ($i+1);
									$randomstring_attch = Yii::$app->getSecurity()->generateRandomString(4);
									if(!is_dir($dir_path)){ 
										if(!is_dir(Yii::$app->basePath.'/web/uploads/mkt')){
											mkdir(Yii::$app->basePath.'/web/uploads/mkt');
										}
										mkdir($dir_path); 
									}
									$file_path = date('Ymd_His').'-attch-'.$randomstring_attch.'.'  . $file->extension;
									$file->saveAs($dir_path.'/'.$file_path);
									$modAttachment->file_name = $file_path;

									$sql_cek = "select count(*) from t_attachment where reff_no = '".$model->kode."' and seq = ".$modAttachment->seq." ";
									$query_cek = Yii::$app->db->createCommand($sql_cek)->queryScalar();

									if ($query_cek > 0) {
										$sql_na = "update t_attachment set active = 'false' where reff_no = '".$model->kode."' and seq = ".$modAttachment->seq." ";
										$query_na = Yii::$app->db->createCommand($sql_na)->execute();
									}

									$modAttachment->validate();
									$modAttachment->save();
								}
							}
						}

						
						// membuat approval jika sudah upload foto 
						$file1 = \yii\web\UploadedFile::getInstance($modAttachment, 'file1');
						$file2 = \yii\web\UploadedFile::getInstance($modAttachment, 'file2');
						$file3 = \yii\web\UploadedFile::getInstance($modAttachment, 'file3');
						if(!isset($_GET['edit'])){
							if(empty($file1) && empty($file2) && empty($file3)){
								$success_3 = true;
							} else {
								$this->saveApproval($model);
								$success_3 = true;
							}
						} else {
							$modApproval = TApproval::find()->where(['reff_no'=>$model->kode])->all();
							$modAttachment = TAttachment::find()->where(['reff_no'=>$model->kode])->all();
							if(empty($modAttachment)){
								if(!empty($modApproval)){
									$sql_delete = "delete from t_approval where reff_no = '".$model->kode."'";
									Yii::$app->db->createCommand($sql_delete)->execute();
								}
								$success_3 = true;
							} else {
								if(!empty($modApproval)){
									$success_3 = true;
								} else {
									$this->saveApproval($model);
									$success_3 = true;
								}
							}
						}
                    }
                }
				// echo '1'; print_r($success_1);
				// echo '2'; print_r($success_2);
				// echo '3'; print_r($success_3);
				// echo '4'; print_r($success_4);
				// print_r($model->tanggal_bayarmax);
				// exit;
                if ($success_1 && $success_2 && $success_3 && $success_4){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
					return $this->redirect(['index','success'=>1,'po_ko_id'=>$model->po_ko_id]);
                } else {
                    $transaction->rollback();
					Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
					$errmsg = "1".$success_1."\n2".$success_2."\n3".$success_3."\n4".$success_4;
					Yii::$app->session->setFlash('error', !empty($errmsg)? $errmsg : Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }

            } catch(Exception $ex){
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }

		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail,'modAttachment'=>$modAttachment,'file'=>$file,'file1'=>$file1,'file2'=>$file2]);
	}

	public function saveApproval($model){
		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO; // kadiv mkt
		$modelApproval->reff_no = trim($model->kode);
		$modelApproval->tanggal_berkas = date("Y-m-d");
		$modelApproval->level = 1;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success = $modelApproval->createApproval();

		$modelApproval = new \app\models\TApproval();
		$modelApproval->assigned_to = Params::DEFAULT_PEGAWAI_ID_ASENG; // direktur
		$modelApproval->reff_no = trim($model->kode);
		$modelApproval->tanggal_berkas = date("Y-m-d");
		$modelApproval->level = 2;
		$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
		$success &= $modelApproval->createApproval();

		return $success;
    }

    public function actionSetCustomer(){
		if(\Yii::$app->request->isAjax){
			$cust_id = \Yii::$app->request->post('cust_id');
			$data = [];
			if(!empty($cust_id)){
				$model = \app\models\MCustomer::findOne($cust_id);
				if(!empty($model)){
					$data = $model->attributes;
				}
			}

			return $this->asJson($data);
		}
    }

    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $modDetail = new TPoKoDetail();
            $modProduk = new \app\models\MBrgProduk();
            $jns_produk = Yii::$app->request->post('jns_produk');
            // $modDetail->harga = 0;
            $data['item'] = $this->renderPartial('_addItem',['modDetail'=>$modDetail,'modProduk'=>$modProduk,'jns_produk'=>$jns_produk, 'edit'=>'']);
            return $this->asJson($data);
        }
    }

    public function actionFindProdukActive(){
        if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$jns_produk = Yii::$app->request->get('type');

			$data = [];
			if(!empty($term)){
                if ($jns_produk == "Log"){
                    $query      = "
                        SELECT
                            m_brg_log.log_id AS produk_id,
                            CONCAT ( m_brg_log.log_nama ) AS produk_nama,
                            m_brg_log.log_kode AS produk_kode,
                            m_brg_log.log_nama,
                            m_brg_log.log_satuan_jual 
                        FROM
                            m_brg_log
							JOIN m_kayu ON m_kayu.kayu_id = m_brg_log.kayu_id
                        WHERE
                            m_brg_log.active IS TRUE 
                            AND ( m_brg_log.log_kode ILIKE'%$term%' OR m_brg_log.log_nama ILIKE'%$term%' ) 
                        ORDER BY
                            m_brg_log.log_id ASC
                    ";
				}
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['produk_id'], 'text'=> $val['produk_nama']];
					}
				}
			}
            return $this->asJson($data);
        }
    }

	public function actionSetTopHari(){
		if(\Yii::$app->request->isAjax){
			$cust_id = \Yii::$app->request->post('cust_id');
			$jns_produk = \Yii::$app->request->post('jns_produk');
			$id = \Yii::$app->request->post('id');
			$data = [];
			$data['top_hari']= 0;
			$data['sisa_piutang']= 0;
			$data['op_aktif']= 0;
			$data['sisa_plafon']= 0;
			$data['maks_plafon']= 0;
			if(!empty($cust_id)){
				$model = \app\models\MCustomer::findOne($cust_id);
				if(!empty($model)){
					$data['cust'] = $model->attributes;
					$modTop = \app\models\MCustTop::findOne(['cust_id'=>$cust_id,'custtop_jns'=>$jns_produk]);
					if(!empty($modTop)){
						$data['top_hari']= $modTop->custtop_top;
					}
					$data['maks_plafon']= $model->cust_max_plafond;
					$data['sisa_piutang']= \app\models\MCustomer::getSisaPiutang($cust_id);
					$modOPAktif = Yii::$app->db->createCommand("
										SELECT * FROM t_op_ko 
										JOIN t_op_ko_detail ON t_op_ko_detail.op_ko_id = t_op_ko.op_ko_id
										WHERE sistem_bayar = 'Tempo' AND NOT EXISTS 
										(SELECT op_ko_id FROM t_nota_penjualan WHERE t_nota_penjualan.op_ko_id = t_op_ko.op_ko_id) 
										AND cust_id = {$cust_id}")->queryAll();
					if($modOPAktif){
						foreach($modOPAktif as $i => $mop){
							$data['op_aktif'] += $mop['harga_jual'] * $mop['kubikasi'];
						}
					}
					$data['sisa_plafon'] = $data['maks_plafon'] - $data['sisa_piutang'] - $data['op_aktif'];
				}
				// if($id){
				// 	$model = TPoKo::findOne($id);
				// 	$data_piutang = \yii\helpers\Json::decode($model->data_piutang);
				// 	$data['maks_plafon']= $data_piutang[0]['maks_plafon'];
				// 	$data['sisa_piutang']= $data_piutang[0]['piutang_aktif'];
				// 	$data['op_aktif']= $data_piutang[0]['op_aktif'];
				// 	$data['sisa_plafon'] = $data_piutang[0]['sisa_plafon'];
				// }
			}
			return $this->asJson($data);
		}
	}

    public function actionOpenlog($disableAction=null,$tr_seq=null,$jenis_produk=null){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-log'){
				$term = Yii::$app->request->get('term');
				$param['table']= \app\models\MBrgLog::tableName();
				$param['pk']= \app\models\MBrgLog::primaryKey()[0];
				$param['column']= [
									'm_brg_log.log_id',
									"CONCAT(m_brg_log.log_kelompok, ' - ', m_kayu.kayu_nama) as jenis_kayu",
					                'm_brg_log.log_kode',
					                'm_brg_log.log_nama',
					                "CONCAT(m_brg_log.range_awal, 'cm - ', m_brg_log.range_akhir, 'cm') as range_diameter",
									'm_brg_log.fsc'
					            ];
				$param['join']  = ['JOIN m_kayu on m_kayu.kayu_id = m_brg_log.kayu_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}

			return $this->renderAjax('masterlog',['disableAction'=>$disableAction,'tr_seq'=>$tr_seq,'jenis_produk'=>$jenis_produk]);
		}
	}

    public function actionPickProduk(){
		if(\Yii::$app->request->isAjax){
			$produk_id = \Yii::$app->request->post('produk_id');
			$jns_produk = \Yii::$app->request->post('jns_produk');
			$data = [];
			if(!empty($produk_id)){
                if($jns_produk == "Log"){
					$model = \app\models\MBrgLog::findOne($produk_id);
                    $data = (!empty($model))? $model->attributes:null;
                    $data['produk_id'] = $model->log_id;
				}else{
                    $model = \app\models\MBrgProduk::findOne($produk_id);
                    $data = (!empty($model))? $model->attributes:null;
                }
			}
			return $this->asJson($data);
		}
    }

	public function actionDaftarAfterSave() {
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= TpoKo::tableName();
				$param['pk']= $param['table'].".". TpoKo::primaryKey()[0];
				$param['column'] = [$param['table'].'.po_ko_id',
									$param['table'].'.kode',
									$param['table'].'.jenis_produk',
									$param['table'].'.tanggal_po',
									'm_sales.sales_nm',
									$param['table'].'.sistem_bayar',
									$param['table'].'.tanggal_kirim',
									'm_customer.cust_an_nama',
									'm_customer.cust_pr_nama',
									$param['table'].'.cancel_transaksi_id',
									$param['table'].'.status_approval',
									$param['table'].'.cara_bayar',
									"(CASE WHEN t_attachment.reff_no IS NOT NULL THEN 'Ada' ELSE NULL END) AS attachment_status",
									"(CASE WHEN t_op_ko.po_ko_id IS NOT NULL THEN 'Ada' ELSE NULL END) AS op_ko",
									$param['table'].'.status_po'
									];
				$param['join']= ['JOIN m_sales ON m_sales.sales_id = '.$param['table'].'.sales_id 
								  JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  LEFT JOIN t_approval ON t_approval.reff_no = t_po_ko.kode
								  LEFT JOIN t_attachment ON t_attachment.reff_no = t_po_ko.kode
								  LEFT JOIN t_op_ko ON t_op_ko.po_ko_id = t_po_ko.po_ko_id
									'];
				$param['group'] = "GROUP BY ".$param['table'].".po_ko_id,
											".$param['table'].".kode, 
											".$param['table'].".jenis_produk,
											".$param['table'].".tanggal_po,
											m_sales.sales_nm,
											".$param['table'].".sistem_bayar,
											".$param['table'].".tanggal_kirim,
											m_customer.cust_an_nama,
											m_customer.cust_pr_nama,
											".$param['table'].".cancel_transaksi_id,
											t_attachment.reff_no,
											t_op_ko.po_ko_id"; 
				// $param['where'] = "t_po_ko.cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

	function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $po_ko_id = Yii::$app->request->post('po_ko_id');
			$edit = Yii::$app->request->post('edit');
            $data = [];
            if(!empty($po_ko_id)){
                $model = TPoKo::findOne($po_ko_id);
                $modDetails = TPoKoDetail::find()->where(['po_ko_id'=>$po_ko_id])->all();
				$modCust = MCustomer::findOne($model->cust_id);
				$cust_alamat = empty($modCust->cust_pr_alamat)?$modCust->cust_an_alamat:$modCust->cust_pr_alamat;
            }else{
                $model = [];
                $modDetails = [];
            }
            $data['html'] = '';
			$data['alamat'] = $cust_alamat;
            if(count($modDetails)>0){
				$v = 0;
                foreach($modDetails as $i => $detail){
                    if(!empty($edit)){
                        $detail->harga = DeltaFormatter::formatNumberForUserFloat($detail->harga);
                        $data['html'] .= $this->renderPartial('_addItem',['modDetail'=>$detail,'i'=>$i,'edit'=>$edit,'jns_produk'=>$model->jenis_produk, 'v'=>$v]);
                    }else{
                        $data['html'] .= $this->renderPartial('_addItemAfterSave',['modDetail'=>$detail,'i'=>$i,'v'=>$v]);
                    }
					$v++; 
                }
            }
            return $this->asJson($data);
        }
    }

	public function actionHapusfile(){
		$attachment_id = $_POST['attachment_id'];

		$TAttachment = TAttachment::findOne($attachment_id);
		$file_name = $TAttachment->file_name;
		$kode = $TAttachment->reff_no;

		$sql_cek_attachment = "select count(*) from t_attachment where reff_no = '".$kode."' and active = 'true' ";
		$jumlah_attachment = Yii::$app->db->createCommand($sql_cek_attachment)->queryScalar();

		$sql_t_po_ko = "select po_ko_id from t_po_ko where kode = '".$kode."' ";
		$po_ko_id = Yii::$app->db->createCommand($sql_t_po_ko)->queryScalar();

		// hapus file
		$dir_path = Yii::$app->basePath.'\web\uploads\mkt\purchaseorder';
		unlink($dir_path.'\\'.$file_name);

		// hapus database
		$sql_delete = "delete from t_attachment where attachment_id = ".$attachment_id."";
		$query_delete = Yii::$app->db->createCommand($sql_delete)->execute();

		$model = new TPoKo();
		$modAttachment = new TAttachment();

		$file = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>1, 'active'=>'true']);
		$file1 = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>2, 'active'=>'true']);
		$file2 = TAttachment::findOne(['reff_no'=>$model->kode, 'seq'=>3, 'active'=>'true']);

		return $this->redirect(array('/marketing/purchaseorder/index','po_ko_id'=>$po_ko_id, 'edit'=>1, 'jumlah_attachment'=>$jumlah_attachment));
	}

	public function actionPrintPO(){
		$this->layout = '@views/layouts/metronic/print';
		$model = TPoKo::findOne($_GET['id']);
		$modDetail = TPoKoDetail::find()->where(['po_ko_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'PURCHASE ORDER');
		if($caraprint == 'PRINT'){
			return $this->render('printPO',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printPO',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printPO',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}

	public function actionCancelTransaksi($id){
		if(\Yii::$app->request->isAjax){
			$model = TPoKo::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_po_ko
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $model->kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
							$model->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
                            if($model->validate()){
								$success_2 = $model->save();

							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel);
                    }

					$modApproval = TApproval::find()->where(['reff_no'=>$model->kode])->all();
					if(!empty($modApproval)){
						$sql_t_approval = "delete from t_approval where reff_no = '".$model->kode."' ";
                    	$success_3 = Yii::$app->db->createCommand($sql_t_approval)->execute();
					} else {
						$success_3 = true;
					}
                    
					// print_r($success_3);exit;
                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Transaksi Berhasil di Batalkan');
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}

			return $this->renderAjax('cancelTransaksi',['model'=>$model,'modCancel'=>$modCancel]);
		}
	}

	public function actionDaftarPO(){
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->get('dt')=='table-po'){
                $param['table'] = TPoKo::tableName();
                $param['pk']= $param['table'].".". TPoKo::primaryKey()[0];
                $param['column'] = [$param['table'].'.po_ko_id','kode','tanggal_po','cust_an_nama','cust_pr_nama', 'tanggal_kirim', 'nomor_po', 'status_po'];
                $param['join'] = ['JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id'];
                $param['where'] = "cancel_transaksi_id IS NULL and t_po_ko.status_approval = 'APPROVED' AND status_po is true";
				$param['group'] = ["GROUP BY t_po_ko.po_ko_id, cust_an_nama, cust_pr_nama"];
                return Json::encode(SSP::complex( $param ));
            }
            return $this->renderAjax('pick');
        }
    }

	public function actionShowFile($id){
		if(\Yii::$app->request->isAjax){
			$attch = \app\models\TAttachment::findOne($id);
			$ext = $attch->file_ext;
			return $this->renderAjax('showFile',['attch'=>$attch, 'ext'=>$ext]);
		}
	}

	public function actionSetStatusPO($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TPoKo::findOne($id);
			if( Yii::$app->request->post('TPoKo')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
					if($_POST['TPoKo']['status_po'] == 1){
						$status = true;
					} else {
						$status = false;
					}
					$model->status_po = $status;
					$model->close_po = $_POST['TPoKo']['close_po'];

					if($model->validate()){
						if($model->save()){
							$success_1 = true;
						}
					}
				
					// print_r($success_1); exit;
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        // $data['message'] = Yii::t('app', 'Status berhasil diubah');
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        // (!isset($data['message']) ? $data['message'] = Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        // (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = 'slaah';//$ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('setStatusPO',['model'=>$model]);
		}
    }

	public function actionSetDDProdukIdAlias(){
        if(\Yii::$app->request->isAjax){
			$jns_produk = Yii::$app->request->post('jns_produk');
			$id = Yii::$app->request->post('id');
			$range_diameter = Yii::$app->request->post('range_diameter');
			$fsc = Yii::$app->request->post('fsc');
            $data['html'] = '';
			$html = '<option></option>';

			$drop_produk_ids = [];
			if($range_diameter){
				$range = explode('-', $range_diameter);
				$range_awal = $range[0];
				$range_akhir = $range[1];
				$model = Yii::$app->db->createCommand("SELECT * FROM m_brg_log WHERE range_awal = {$range_awal} AND range_akhir = {$range_akhir} AND fsc = {$fsc}")->queryAll();
				if(count($model) > 0){
					foreach($model as $i => $tag){
						$drop_produk_ids[$tag['log_id']] = $tag['log_nama'];
					}
				}
				foreach($drop_produk_ids as $i => $val){
					$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
				}
			}
            if($id){
				$modDetail = TPoKoDetail::findOne($id);
				$produk_ids = explode(',', $modDetail->produk_id_alias);
				if($modDetail->produk_id){
					$data['produk_ids'] = ["$modDetail->produk_id"];
				} else {
					$data['produk_ids'] = $produk_ids;
				}
			}
			$data['html'] = $html;
			return $this->asJson($data);
		}
    }

	public function actionSetDiameterAlias(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->post('id');
			if($id){
				$model = TPoKoDetail::find($id);
				if($model->range_diameter){
					$data['range_diameter'] = $model->range_diameter;
				} 
			}
		}
	}

}
?>