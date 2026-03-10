<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class BandsawController extends DeltaBaseController
{
    public $defaultAction = 'index';
	public function actionIndex()
	{
		$model = new \app\models\TBandsaw();
		$model->kode = 'Auto Generate';
		$model->tanggal = date("d/m/Y");
		$checkedBandsaw = [];
		
		if (isset($_GET['bandsaw_id'])) {
			$model = \app\models\TBandsaw::findOne($_GET['bandsaw_id']);
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$modSpk = \app\models\TSpkSawmill::findOne($model->spk_sawmill_id);
			$model->kode_spk = $modSpk->kode;

			$details = \app\models\TBandsawDetail::find()
				->select('nomor_bandsaw')
				->where(['bandsaw_id' => $model->bandsaw_id])
				->groupBy('nomor_bandsaw')
				->asArray()
				->all();
			$checkedBandsaw = array_column($details, 'nomor_bandsaw');
		}
		
		if (Yii::$app->request->post('TBandsaw')) {
			$transaction = \Yii::$app->db->beginTransaction();
			try {
				$success_1 = false;
				$success_2 = false;

				$model->load(Yii::$app->request->post());

				if (!isset($_GET['edit'])) {
					$model->kode = \app\components\DeltaGenerator::kodeBandsaw();
				}

				$model->prepared_by = Yii::$app->user->identity->pegawai_id;

				if ($model->validate()){
					if($model->save()){
						$success_1 = true;
						// edit
						if (isset($_GET['edit'])) {
							\app\models\TBandsawDetail::deleteAll(['bandsaw_id' => $model->bandsaw_id]);
						}

						$modSpk = \app\models\TSpkSawmill::findOne($model->spk_sawmill_id);
						foreach ($_POST['TBandsawDetail'] as $n => $bandsawData) {
							$nomor_bandsaw = $bandsawData['nomor_bandsaw'];

							foreach ($bandsawData as $i => $detail) {
								// memastikan array size
								if (!is_array($detail) || empty($detail['size'])) continue;

								$arr_size = preg_split('/x/i', $detail['size']);
								$produk_t = trim($arr_size[0]);
								$produk_l = trim($arr_size[1]);

								foreach ($detail as $p => $val) {
									if (is_array($val) && isset($val['panjang'])) {
										$pjg = $val['panjang'];
										$qty = !empty($val['qty2']) ? $val['qty2'] : 0;

										$modDetail = new \app\models\TBandsawDetail();
										$modDetail->bandsaw_id = $model->bandsaw_id;
										$modDetail->nomor_bandsaw = $nomor_bandsaw;
										$modDetail->produk_t = $produk_t;
										$modDetail->produk_l = $produk_l;
										$modDetail->produk_p = $pjg;
										$modDetail->qty = $qty;
										$modDetail->kayu_id = $modSpk->kayu_id;

										if ($modDetail->validate()) {
											if ($modDetail->save()) {
												$success_2 = true;
											}
										}
									}
								}
							}
						}

					}
				}

				// print_r('1');
                // print_r($success_1);
                // print_r('2');
                // print_r($success_2);
				// echo '<pre>'; 
				// print_r($_POST); 
				// exit;
				if ($success_1 && $success_2) {
					$transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
					return $this->redirect(['index', 'bandsaw_id'=>$model->bandsaw_id, 'edit'=>'1']);
                    // return $this->redirect(['index', 'bandsaw_id'=>$model->bandsaw_id]);
				} else {
					$transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
				}
			} catch (\yii\db\Exception $ex) {
				$transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
			}
		}
		
		return $this->render('index', ['model' => $model, 'checkedBandsaw' => $checkedBandsaw]);
	}

	/**public function actionIndex(){
        $model = new \app\models\TBandsaw();
        $model->kode = 'Auto Generate';
        $model->tanggal = date("d/m/Y");
		$checkedBandsaw = [];

		if(isset($_GET['bandsaw_id'])){
            $model = \app\models\TBandsaw::findOne($_GET['bandsaw_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modSpk = \app\models\TSpkSawmill::findOne($model->spk_sawmill_id);
			$model->kode_spk = $modSpk->kode;
			$details = \app\models\TBandsawDetail::find()
                ->select('nomor_bandsaw')
                ->where(['bandsaw_id' => $model->bandsaw_id])
                ->groupBy('nomor_bandsaw')
                ->asArray()
                ->all();
    		$checkedBandsaw = array_column($details, 'nomor_bandsaw');
        }

		if( Yii::$app->request->post('TBandsaw')){
			$transaction = \Yii::$app->db->beginTransaction();
			try{
				$success_1 = false; //t_bandsaw
				$success_2 = false; //t_bandsaw_detail

				$model->load(\Yii::$app->request->post());

				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodeBandsaw();
				}
				$model->prepared_by = Yii::$app->user->identity->pegawai_id;
				if($model->validate()){
					if($model->save()){
						$success_1 = true;

						// if(isset($_GET['bandsaw_id'])){
							if(isset($_GET['edit'])){
								\app\models\TBandsawDetail::deleteAll("bandsaw_id = ".$model->bandsaw_id);
							}

							foreach ($_POST['TBandsawDetail'] as $i => $detail) {
								// $size = $detail['size'];
								$nomor_bandsaw = $detail['nomor_bandsaw'];

								// if (!empty($detail['panjang']) && is_array($detail['size'])) {
								if (is_array($detail['size'])) {
									foreach ($detail['size'] as $s => $sz) {
										$arr_size = preg_split('/x/i', $sz);
										$produk_t = trim($arr_size[0]);
										$produk_l = trim($arr_size[1]);

										// simpan data tiap-tiap panjang
										foreach ($detail as $a => $val) {
											if (is_array($val) && isset($val['panjang'])) {
												$pjg = $val['panjang'];
												// $jml = $val['jml'];
												$qty = $val['qty2'] !== ''?$val['qty2']:0;

												// $jumlahSatu = substr_count($jml, '1');

												$modDetail = new \app\models\TBandsawDetail();
												$modDetail->bandsaw_id = $model->bandsaw_id;
												$modDetail->nomor_bandsaw = $nomor_bandsaw;
												$modDetail->produk_t = $produk_t;
												$modDetail->produk_l = $produk_l;
												$modDetail->produk_p = $pjg;
												$modDetail->qty = $qty; // + $jumlahSatu;

												$modSpk = \app\models\TSpkSawmill::findOne($model->spk_sawmill_id);
												$modDetail->kayu_id = $modSpk->kayu_id;

												if ($modDetail->validate() && $modDetail->save()) {
													$success_2 = true;
												}
											}
										}
									}
								}

								
							}
						// } else {
						// 	$success_2 = true;
						// }
					}
				}

				// print_r('1');
                // print_r($success_1);
                // print_r('2');
                // print_r($success_2);
				// echo '<pre>'; print_r($_POST['TBandsawDetail']);
				print_r(is_array($detail['size']));
				exit;
				if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index', 'bandsaw_id'=>$model->bandsaw_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
			} catch(yii\db\Exception $ex) {

			}
		}

		return $this->render('index',['model'=>$model, 'checkedBandsaw'=>$checkedBandsaw]);
	}**/

    public function actionOpenSPK(){
		if(Yii::$app->request->isAjax){
			$edit = Yii::$app->request->post('edit');
			$id = Yii::$app->request->post('id');
			$and_where = "AND NOT EXISTS (select spk_sawmill_id from t_bandsaw where t_bandsaw.spk_sawmill_id = t_spk_sawmill.spk_sawmill_id
						 and cancel_transaksi_id is null)";
			
			if(Yii::$app->request->post('dt') === 'table-spk'){
				$param['table']= \app\models\TSpkSawmill::tableName();
				$param['pk']= $param['table'].".".\app\models\TSpkSawmill::primaryKey()[0];
				$param['column'] = ['spk_sawmill_id',
									'kode',		
									'refisi_ke',
									'tanggal_mulai',
                                    'tanggal_selesai',
                                    'pemenuhan_po',
                                    'peruntukan',
                                    'line_sawmill',
									];
				$param['where']= "cancel_transaksi_id IS NULL AND approval_status = 'APPROVED' AND status_spk is TRUE";

				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('spksawmill', ['id'=>$id, 'edit'=>$edit]);
        }
	}

    public function actionFindSPK(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$edit = Yii::$app->request->get('edit');
			$id = Yii::$app->request->get('id');
			$data = [];
			$active = "";
			if(!empty($term)){
				$and_where = "AND NOT EXISTS (select spk_sawmill_id from t_bandsaw where t_bandsaw.spk_sawmill_id = t_spk_sawmill.spk_sawmill_id)";
				
				$query = "
					SELECT spk_sawmill_id, kode FROM t_spk_sawmill
                    WHERE approval_status = 'APPROVED' and status_spk is true
					AND kode ilike '%{$term}%' AND cancel_transaksi_id IS NULL
					ORDER BY created_at";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['spk_sawmill_id'], 'text'=>$val['kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}

    public function actionSetDetail(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->post('spk_sawmill_id');
			$nobandsawChecked = Yii::$app->request->post('nobandsawChecked');
			$data = []; $data['spk'] = []; $data['nobandsaw'] = [];
			$modSpk = \app\models\TSpkSawmill::findOne($id);
			if(!empty($modSpk)){
				$data['spk'] = $modSpk;
				if(!empty($nobandsawChecked)){
					$data['nobandsaw'] = $nobandsawChecked;
				}
				return $this->asJson($data);
			}
		}
	}

	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
			$spk_sawmill_id = Yii::$app->request->post('spk_sawmill_id');
			$bandsaw_id     = Yii::$app->request->post('bandsaw_id');
			$edit           = Yii::$app->request->post('edit');
			$nobandsaw      = Yii::$app->request->post('nobandsaw');

			$data = [];
			$details = !empty($bandsaw_id) ? \app\models\TBandsawDetail::findAll(['bandsaw_id'=>$bandsaw_id]) : [];

			$data['html'] = '';

			if($nobandsaw){
				foreach($nobandsaw as $n => $noban){
					// ambil data size per nomor bandsaw
					if(!empty($bandsaw_id) && count($details) > 0){
						$modSpk = \app\models\TBandsawDetail::find()
							->select(['produk_t', 'produk_l', 'nomor_bandsaw', 'kayu_id'])
							->where(['bandsaw_id' => $bandsaw_id, 'nomor_bandsaw' => $noban])
							->groupBy(['produk_t', 'produk_l', 'nomor_bandsaw', 'kayu_id'])
							->orderBy('produk_t ASC, produk_l ASC')
							->all();
					} else {
						$modSpk = \app\models\TSpkSawmillDetail::find()
							->select(['produk_t', 'produk_l', 'kategori_ukuran', 'kayu_id'])
							->where(['spk_sawmill_id' => $spk_sawmill_id])
							->groupBy(['produk_t', 'produk_l', 'kategori_ukuran', 'kayu_id'])
							->orderBy('produk_t ASC, produk_l ASC')
							->all();
					}

					$rowspan = count($modSpk);

					foreach($modSpk as $i => $spk){
						$modDetail = new \app\models\TBandsawDetail();
						$modDetail->size = $spk->produk_t . 'x' . $spk->produk_l;

						// query panjang per nomor bandsaw
						if(!empty($bandsaw_id) && count($details) > 0){
							$modDetail->nomor_bandsaw = $spk->nomor_bandsaw;
							$query = "SELECT produk_p FROM t_bandsaw_detail 
									WHERE produk_t = $spk->produk_t AND produk_l = $spk->produk_l 
									AND bandsaw_id = $bandsaw_id  AND nomor_bandsaw = '$noban'
									ORDER BY produk_p";
						} else {
							$query = "SELECT produk_p FROM t_spk_sawmill_detail 
									WHERE produk_t = $spk->produk_t AND produk_l = $spk->produk_l 
									AND spk_sawmill_id =  $spk_sawmill_id AND kategori_ukuran = '$spk->kategori_ukuran'";
						}
						$modPanjang = Yii::$app->db->createCommand($query)->queryAll();

						$data['html'] .= $this->renderPartial('_item', [
							'modSpk'        => $spk,
							'n'             => $n,
							'i'				=> $i,
							'edit'          => $edit,
							'modDetail'     => $modDetail,
							'spk_sawmill_id'=> $spk_sawmill_id,
							'modPanjang'    => $modPanjang,
							'bandsaw_id'    => $bandsaw_id,
							'details'       => $details,
							'noban'         => $noban,
							'rowspan'       => $rowspan,
							'isFirst'       => ($i == 0)
						]);
					}
				}
			}

			return $this->asJson($data);
		}
	}

	function actionGetItems1(){
		if(\Yii::$app->request->isAjax){
			$spk_sawmill_id = Yii::$app->request->post('spk_sawmill_id');
			$bandsaw_id     = Yii::$app->request->post('bandsaw_id');
			$edit           = Yii::$app->request->post('edit');
			$nobandsaw      = Yii::$app->request->post('nobandsaw');

			$data = [];
			$details = !empty($bandsaw_id) ? \app\models\TBandsawDetail::findAll(['bandsaw_id'=>$bandsaw_id]) : [];

			// ambil semua data size
			if((!empty($bandsaw_id)) && (count($details) > 0)){
				$modSpk = \app\models\TBandsawDetail::find()
							->select(['produk_t', 'produk_l', 'nomor_bandsaw'])
							->where(['bandsaw_id' => $bandsaw_id])
							->groupBy(['produk_t', 'produk_l', 'nomor_bandsaw'])
							->all();
			} else {
				$modSpk = \app\models\TSpkSawmillDetail::find()
							->select(['produk_t', 'produk_l', 'kategori_ukuran'])
							->where(['spk_sawmill_id' => $spk_sawmill_id])
							->groupBy(['produk_t', 'produk_l', 'kategori_ukuran'])
							->all();
			}

			$data['html'] = '';

			if($nobandsaw){
				foreach($nobandsaw as $no => $noban){
					// $modSpkForNoband = [];

					// foreach($modSpk as $spk){
					// 	$modSpkForNoband[] = $spk;
					// }

					// $rowspan = count($modSpkForNoband);

					// foreach($modSpkForNoband as $i => $spk){
					$rowspan = count($modSpk);

					foreach($modSpk as $i => $spk){
						$modDetail = new \app\models\TBandsawDetail();
						$modDetail->size = $spk->produk_t . 'x' . $spk->produk_l;

						// query panjang
						if((!empty($bandsaw_id)) && (count($details) > 0)){
							$modDetail->nomor_bandsaw = $spk->nomor_bandsaw;
							$query = "SELECT produk_p FROM t_bandsaw_detail 
									WHERE produk_t = $spk->produk_t AND produk_l = $spk->produk_l 
									AND bandsaw_id = $bandsaw_id AND nomor_bandsaw = '$spk->nomor_bandsaw'";
						} else {
							$query = "SELECT produk_p FROM t_spk_sawmill_detail 
									WHERE produk_t = $spk->produk_t AND produk_l = $spk->produk_l 
									AND spk_sawmill_id = $spk_sawmill_id AND kategori_ukuran = '$spk->kategori_ukuran'";
						}
						$modPanjang = Yii::$app->db->createCommand($query)->queryAll();

						$data['html'] .= $this->renderPartial('_item', [
							'modSpk'        => $spk,
							'i'             => $i,
							'edit'          => $edit,
							'modDetail'     => $modDetail,
							'spk_sawmill_id'=> $spk_sawmill_id,
							'modPanjang'    => $modPanjang,
							'bandsaw_id'    => $bandsaw_id,
							'details'       => $details,
							'noban'         => $noban,
							'rowspan'       => $rowspan,
							'isFirst'       => ($i == 0) // untuk td rowspan
						]);
					}
				}
			}

			return $this->asJson($data);
		}
	}

	function actionGetItems2(){
		if(\Yii::$app->request->isAjax){
            $spk_sawmill_id = Yii::$app->request->post('spk_sawmill_id');
			$bandsaw_id = Yii::$app->request->post('bandsaw_id');
			$edit = Yii::$app->request->post('edit');
			$nobandsaw = Yii::$app->request->post('nobandsaw');
            $data = [];
			$details = !empty($bandsaw_id)?\app\models\TBandsawDetail::findAll(['bandsaw_id'=>$bandsaw_id]):[];
			$modDetail = new \app\models\TBandsawDetail();
			if((!empty($bandsaw_id)) && (count($details) > 0)){
				$modSpk = \app\models\TBandsawDetail::find()
                                    ->select(['produk_t', 'produk_l', 'nomor_bandsaw'])
                                    ->where(['bandsaw_id' => $bandsaw_id])
                                    ->groupBy(['produk_t', 'produk_l', 'nomor_bandsaw'])
                                    ->all();
			} else {
				$modSpk = \app\models\TSpkSawmillDetail::find()
                                    ->select(['produk_t', 'produk_l', 'kategori_ukuran'])
                                    ->where(['spk_sawmill_id' => $spk_sawmill_id])
                                    ->groupBy(['produk_t', 'produk_l', 'kategori_ukuran'])
                                    ->all();
			}
            $data['html'] = '';
            if(count($modSpk)>0){
				if($nobandsaw){
					foreach($nobandsaw as $n => $noban){
						foreach($modSpk as $i => $spk){
							$modDetail->size = $spk->produk_t . 'x' . $spk->produk_l;
							$query = "";
							if((!empty($bandsaw_id)) && (count($details) > 0)){
								$modDetail->nomor_bandsaw = $spk->nomor_bandsaw;
								$query = "SELECT produk_p FROM t_bandsaw_detail WHERE produk_t = $spk->produk_t AND produk_l = $spk->produk_l 
										AND bandsaw_id = $bandsaw_id AND nomor_bandsaw = '$spk->nomor_bandsaw'";
							} else {
								$query = "SELECT produk_p FROM t_spk_sawmill_detail WHERE produk_t = $spk->produk_t AND produk_l = $spk->produk_l 
										AND spk_sawmill_id = $spk_sawmill_id AND kategori_ukuran = '$spk->kategori_ukuran'";
							}
							$modPanjang = Yii::$app->db->createCommand($query)->queryAll();
							$data['html'] .= $this->renderPartial('_item',['modSpk'=>$spk, 'i'=>$i,'edit'=>$edit, 'modDetail'=>$modDetail, 'spk_sawmill_id'=>$spk_sawmill_id, 
																'modPanjang'=>$modPanjang, 'bandsaw_id'=>$bandsaw_id, 'details'=>$details, 'noban'=>$noban]);
						}
					}
				}
            }
            return $this->asJson($data);
        }
    }

	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TBandsaw::tableName();
				$param['pk']= $param['table'].".". \app\models\TBandsaw::primaryKey()[0];
				$param['column'] = [$param['table'].'.bandsaw_id',						//0
									$param['table'].'.kode',							//1
									$param['table'].'.tanggal',							//2
									't_spk_sawmill.kode as kode_spk',					//3
                                    $param['table'].'.line_sawmill',					//4
                                    'm_pegawai.pegawai_nama',					        //5
                                    $param['table'].'.cancel_transaksi_id',				//6
									];
				$param['join']= ['JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.prepared_by
								  JOIN t_spk_sawmill ON t_spk_sawmill.spk_sawmill_id = '.$param['table'].'.spk_sawmill_id'];
				$param['where'] = $param['table'].'.cancel_transaksi_id IS NULL';
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

	public function actionAutoSave(){
		if(\Yii::$app->request->isAjax){
			$bandsaw_detail_id = Yii::$app->request->post('bandsaw_detail_id');
			$qty = Yii::$app->request->post('qty');
			$data['status'] = false;

			$modDetail = \app\models\TBandsawDetail::findOne($bandsaw_detail_id);
			$modDetail->qty = $qty;
			if($modDetail->save()){
				$data['status'] = true;
			}

			return $this->asJson($data);
		}
	}

	public function actionSavePjg(){
		if(\Yii::$app->request->isAjax){
			$bandsaw_id = Yii::$app->request->post('bandsaw_id');
			$spk_sawmill_id = Yii::$app->request->post('spk_sawmill_id');
			$pjg = Yii::$app->request->post('pjg');
			$no_bandsaw = Yii::$app->request->post('no_bandsaw');
			$size = Yii::$app->request->post('size');
			$arr_size = preg_split('/x/i', $size);
			$produk_t = trim($arr_size[0]);
			$produk_l = trim($arr_size[1]);
			$data['status'] = false;
			$modSpk = \app\models\TSpkSawmill::findOne($spk_sawmill_id);

			$modDetail = new \app\models\TBandsawDetail();
			$modDetail->bandsaw_id = $bandsaw_id;
			$modDetail->kayu_id = $modSpk->kayu_id;
			$modDetail->nomor_bandsaw = "$no_bandsaw";
			$modDetail->produk_t = $produk_t;
			$modDetail->produk_l = $produk_l;
			$modDetail->produk_p = $pjg;
			$modDetail->qty = 0;

			// print_r($pjg); exit;
			if($modDetail->save()){
				$data['status'] = true;
			}
			return $this->asJson($data);
		}
	}

	public function actionRemovePjg(){
		if(\Yii::$app->request->isAjax){
			$bandsaw_detail_id = Yii::$app->request->post('bandsaw_detail_id');
			$data['status'] = false;

			$modDetail = \app\models\TBandsawDetail::findOne($bandsaw_detail_id);
			if($modDetail->delete()){
				$data['status'] = true;
			}
			return $this->asJson($data);
		}
	}

	public function actionAddSize(){
		if(\Yii::$app->request->isAjax){
			$bandsaw_id = Yii::$app->request->post('bandsaw_id');
			$n = Yii::$app->request->post('n');
			$i = Yii::$app->request->post('i');
			$p = Yii::$app->request->post('p');
			$noban = Yii::$app->request->post('noban');
			$data['html'] = [];

			$modDetail = new \app\models\TBandsawDetail();
			
			$data['html'] = $this->renderPartial('_addsize',['modDetail'=>$modDetail,'bandsaw_id'=>$bandsaw_id,'n'=>$n, 'i'=>$i, 'p'=>$p, 'noban'=>$noban, 'isFirst'=>false]);
			return $this->asJson($data);
		}
	}

	public function actionSaveSize(){
		if(\Yii::$app->request->isAjax){
			$bandsaw_id = Yii::$app->request->post('bandsaw_id');
			$spk_sawmill_id = Yii::$app->request->post('spk_sawmill_id');
			$size = Yii::$app->request->post('size');
			$pjg = Yii::$app->request->post('panjang');
			$no_bandsaw = Yii::$app->request->post('no_bandsaw');
			$arr_size = preg_split('/x/i', $size);
			$produk_t = trim($arr_size[0]);
			$produk_l = trim($arr_size[1]);
			$data['status'] = false;
			$modSpk = \app\models\TSpkSawmill::findOne($spk_sawmill_id);
			
			$modDetail = new \app\models\TBandsawDetail();
			$modDetail->bandsaw_id = $bandsaw_id;
			$modDetail->kayu_id = $modSpk->kayu_id;
			$modDetail->nomor_bandsaw = "$no_bandsaw";
			$modDetail->produk_t = $produk_t;
			$modDetail->produk_l = $produk_l;
			$modDetail->produk_p = $pjg;
			$modDetail->qty = 0;

			// print_r($modDetail); exit;
			if($modDetail->save()){
				$data['status'] = true;
			}
			return $this->asJson($data);
		}
	}

	public function actionRemoveSize(){
		if(\Yii::$app->request->isAjax){
			$size = Yii::$app->request->post('size');
			$nobandsaw = Yii::$app->request->post('nobandsaw');
			$arr_size = preg_split('/x/i', $size);
			$produk_t = trim($arr_size[0]);
			$produk_l = trim($arr_size[1]);
			$data['status'] = false;

			if(\app\models\TBandsawDetail::deleteAll(['nomor_bandsaw'=>$nobandsaw, 'produk_t'=>$produk_t, 'produk_l'=>$produk_l])){
				$data['status'] = true;
			}
			return $this->asJson($data);
		}
	}

	public function actionCancelBandsaw(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->get('id');
			$model = \app\models\TBandsaw::findOne($id);
			$modCancel = new \app\models\TCancelTransaksi();
			if( Yii::$app->request->post('TCancelTransaksi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_bandsaw
                    $modCancel->load(\Yii::$app->request->post());
					$modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
					$modCancel->cancel_at = date('d/m/Y H:i:s');
					$modCancel->reff_no = $model->kode;
					$modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if($modCancel->validate()){
                        if($modCancel->save()){
							$success_1 = true;
                            if($model->updateAttributes(['cancel_transaksi_id'=>$modCancel->cancel_transaksi_id, 'approval_status'=>$modCancel->status])){
								$success_2 = TRUE;
                                $modApproval = \app\models\TApproval::findAll(['reff_no'=>$model->kode]);
                                foreach($modApproval as $ap => $approval){
                                    $approval->updateAttributes(['status'=>$modCancel->status]);
                                }
							}else{
								$success_2 = FALSE;
							}
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modCancel); 
                    }
					
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'Bandsaw Berhasil di Batalkan');
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
			
			return $this->renderAjax('cancelBandsaw',['model'=>$model,'modCancel'=>$modCancel]);
		}
	}

	public function actionPrintBandsaw()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = \app\models\TBandsaw::findOne($_GET['id']);
		$modDetail = \app\models\TBandsawDetail::findAll(['bandsaw_id'=>$_GET['id']]);
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'Bandsaw');
        if ($caraprint == 'PRINT') {
            return $this->render('print', ['model' => $model, 'paramprint' => $paramprint, 'modDetail'=>$modDetail]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf; 
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('print', ['model' => $model, 'paramprint' => $paramprint, 'modDetail'=>$modDetail]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->render('print', ['model' => $model, 'paramprint' => $paramprint, 'modDetail'=>$modDetail]);
        }
    }
}