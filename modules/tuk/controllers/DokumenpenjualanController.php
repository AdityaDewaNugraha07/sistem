<?php

namespace app\modules\tuk\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class DokumenpenjualanController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TDokumenPenjualan();
        $model->nomor_dokumen = '-';
        $model->tanggal = date('d/m/Y');
		$model->masaberlaku_awal = $model->tanggal;
		$model->masaberlaku_akhir = date('d/m/Y',strtotime("+2 day"));
		
		if(isset($_GET['dokumen_penjualan_id'])){
            $model = \app\models\TDokumenPenjualan::findOne($_GET['dokumen_penjualan_id']);
            $model->kode_spm = $model->spmKo->kode;
            $model->cust_an_nama = $model->cust->cust_an_nama;
            $model->cust_pr_nama = $model->cust->cust_pr_nama;
            $model->cust_an_alamat = $model->cust->cust_an_alamat;
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$diff=date_diff( date_create($model->masaberlaku_awal) ,date_create($model->masaberlaku_akhir) );
			$model->masaberlaku_hari = ($diff->days+1);
            $model->masaberlaku_awal = date('d/m/Y', strtotime($model->masaberlaku_awal));
            $model->masaberlaku_akhir = date('d/m/Y', strtotime($model->masaberlaku_akhir));
			$modCust = \app\models\MCustomer::findOne($model->cust_id);
			$model->cust_is_pkp = ($modCust->cust_is_pkp)?1:0;
			$model->petugas_legalkayu = $model->petugasLegalkayu->pegawai->pegawai_nama;
        }
		
        if( Yii::$app->request->post('TDokumenPenjualan')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_dokumen_penjualan
                $success_2 = true; // t_dokumen_penjualan_detail
                $model->load(\Yii::$app->request->post());
				$cekformat = explode("/", $model->nomor_dokumen);
				if(count( (explode("/", $model->nomor_dokumen)) == 5)){
					if(strlen($cekformat[0]) != 3){
						$errmsg = "Format Nomor Dokumen harus 3 digit pada slice pertama contoh XXX/XXX/CWM/X/YYYY";
					}
				}
                if($model->validate()){ 
                    if($model->save()){ 
                        $success_1 = ( isset($errmsg)? false : true );
						if((isset($_GET['edit'])) && (isset($_GET['dokumen_penjualan_id']))){
							$modDetail = \app\models\TDokumenPenjualanDetail::find()->where(['dokumen_penjualan_id'=>$_GET['dokumen_penjualan_id']])->all();
							if(count($modDetail)>0){
								\app\models\TDokumenPenjualanDetail::deleteAll(['dokumen_penjualan_id'=>$_GET['dokumen_penjualan_id']]);
							}
						}
						foreach($_POST['TDokumenPenjualanDetail'] as $i => $detail){
							$modDetail = new \app\models\TDokumenPenjualanDetail();
							$modDetail->attributes = $detail;
							$modDetail->dokumen_penjualan_id = $model->dokumen_penjualan_id;
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
                    }
                }
				
//				echo "<pre>1";
//				print_r($success_1);
//				echo "<pre>2";
//				print_r($success_2);
//				exit;
				
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'dokumen_penjualan_id'=>$model->dokumen_penjualan_id]);
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
	
	public function actionOpenSPMTernota(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-spm'){
				$param['table']= \app\models\TNotaPenjualan::tableName();
				$param['pk']= $param['table'].".".\app\models\TNotaPenjualan::primaryKey()[0];
				$param['column'] = [$param['table'].'.spm_ko_id',
									$param['table'].'.kode',
									$param['table'].'.jenis_produk',
									$param['table'].'.tanggal',
									'm_customer.cust_an_nama',
									$param['table'].'.syarat_jual',
									$param['table'].'.kendaraan_nopol',
									$param['table'].'.kendaraan_supir',
									$param['table'].'.alamat_bongkar'
									];
				$param['join']= ['JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id'];
				$param['where']=$param['table'].".cancel_transaksi_id IS NULL AND t_nota_penjualan.spm_ko_id NOT IN( SELECT spm_ko_id FROM t_dokumen_penjualan ) AND ".$param['table'].".jenis_produk NOT IN('Limbah', 'Log') AND op_ko_id != 999999 ";
				//$param['where']=$param['table'].".cancel_transaksi_id IS NULL AND t_nota_penjualan.status_approval ='APPROVED' AND t_nota_penjualan.spm_ko_id NOT IN( SELECT spm_ko_id FROM t_dokumen_penjualan ) AND ".$param['table'].".jenis_produk NOT IN('Limbah', 'Log') AND op_ko_id != 999999 ";
				$param['order']="ORDER BY t_nota_penjualan.nota_penjualan_id DESC";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('openSPMTernota');
        }
	}
	
	public function actionFindNota(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT * FROM t_nota_penjualan 
					WHERE kode ilike '%{$term}%' AND cancel_transaksi_id IS NULL AND t_nota_penjualan.spm_ko_id NOT IN( SELECT spm_ko_id FROM t_dokumen_penjualan )
					ORDER BY created_at DESC";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['spm_ko_id'], 'text'=>$val['kode']." - ".$val['jenis_produk']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	
	function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $nota_penjualan_id = Yii::$app->request->post('nota_penjualan_id');
			$modNota = \app\models\TNotaPenjualan::findOne($nota_penjualan_id);
			$modCust = \app\models\MCustomer::findOne($modNota->cust_id);
			$modDetail = [];
            $data = [];
            if(!empty($nota_penjualan_id)){
                $modNotaDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id'=>$nota_penjualan_id])->all();
            }
            $data['html'] = '';
            if(count($modNotaDetail)>0){
                foreach($modNotaDetail as $i => $notadetail){
					$modSPMDetail = \app\models\TSpmKoDetail::findOne(['spm_ko_id'=>$modNota->spm_ko_id,'produk_id'=>$notadetail->produk_id]);
					$modDetail = new \app\models\TDokumenPenjualanDetail();
					$modDetail->attributes = $notadetail->attributes;
					$modDetail->spm_kod_id = $modSPMDetail->spm_kod_id;
					$data['html'] .= $this->renderPartial('_item',['modDetail'=>$modDetail,'i'=>$i,'modNota'=>$modNota,'modSPMDetail'=>$modSPMDetail]);
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionSetDropdownPetugas(){
        if(\Yii::$app->request->isAjax){
			$jenis_produk = Yii::$app->request->post('jenis_produk');
            $mod = [];
			if(!empty($jenis_produk)){
				if($jenis_produk=="Log"){
					$mod = \app\models\MPetugasLegalkayu::find()
								->select("m_petugas_legalkayu.*,m_pegawai.*")
								->join("JOIN", "m_pegawai", "m_pegawai.pegawai_id = m_petugas_legalkayu.pegawai_id")
								->where(['m_petugas_legalkayu.active'=>true])
								->andWhere("jenis = 'Kayu Bulat'")
								->orderBy('pegawai_nama ASC')->all();
				}else{
					$mod = \app\models\MPetugasLegalkayu::find()
								->select("m_petugas_legalkayu.*,m_pegawai.*")
								->join("JOIN", "m_pegawai", "m_pegawai.pegawai_id = m_petugas_legalkayu.pegawai_id")
								->where(['m_petugas_legalkayu.active'=>true])
								->andWhere("jenis != 'Kayu Bulat'")
								->orderBy('pegawai_nama ASC')->all();
				}
			}
			$html = \yii\bootstrap\Html::tag('option','',['value'=>'']);
			if(!empty($mod)){
				foreach($mod as $i => $m){
					$html .= \yii\bootstrap\Html::tag('option',$m['pegawai_nama']." - ".$m['noreg'],['value'=>$m['petugas_legalkayu_id']]);
				}
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	
	public function actionSetPetugas(){
        if(\Yii::$app->request->isAjax){
			$petugas_legalkayu_id = Yii::$app->request->post('petugas_legalkayu_id');
			$data = [];
			if(!empty($petugas_legalkayu_id)){
				$data = \app\models\MPetugasLegalkayu::findOne($petugas_legalkayu_id)->attributes;
			}
			return $this->asJson($data);
		}
    }
	
	function actionGetItemsById(){
		if(\Yii::$app->request->isAjax){
            $dokumen_penjualan_id = Yii::$app->request->post('id');
			$modDetail = [];
            $data = [];
            $modNota = [];
            if(!empty($dokumen_penjualan_id)){
                $modDokPenjualan = \app\models\TDokumenPenjualanDetail::find()->where(['dokumen_penjualan_id'=>$dokumen_penjualan_id])->all();
                $model = \app\models\TDokumenPenjualan::findOne($dokumen_penjualan_id);
                $modSpm = \app\models\TSpmKo::findOne($model->spm_ko_id);
                $modNota = \app\models\TNotaPenjualan::findOne(['spm_ko_id'=>$model->spm_ko_id]);
            }
            $data['html'] = '';
            if(count($modDokPenjualan)>0){
                foreach($modDokPenjualan as $i => $dokjual){
					$modDetail = new \app\models\TDokumenPenjualanDetail();
					$modDetail->attributes = $dokjual->attributes;
                    $modSPMDetail = \app\models\TSpmKoDetail::findOne(['spm_ko_id'=>$modNota->spm_ko_id,'produk_id'=>$dokjual->produk_id]);
                    $data['html'] .= $this->renderPartial('_item',['modDetail'=>$modDetail,'i'=>$i,'modNota'=>$modNota,'modSPMDetail'=>$modSPMDetail]);
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TDokumenPenjualan::tableName();
				$param['pk']= $param['table'].".". \app\models\TDokumenPenjualan::primaryKey()[0];
				$param['column'] = [$param['table'].'.dokumen_penjualan_id',
									$param['table'].'.jenis_dokumen',
									$param['table'].'.tanggal',
									$param['table'].'.nomor_dokumen',
									't_nota_penjualan.kode',
									'm_customer.cust_an_nama',
									$param['table'].'.kendaraan_nopol',
									$param['table'].'.kendaraan_supir',
									$param['table'].'.alamat_bongkar',
									'm_pegawai.pegawai_nama',
									$param['table'].'.noreg',
									$param['table'].'.skshhko_no',
									$param['table'].'.cancel_transaksi_id',
									$param['table'].'.jenis_produk'
									];
				$param['join']= ['JOIN t_nota_penjualan ON t_nota_penjualan.spm_ko_id = '.$param['table'].'.spm_ko_id 
								  JOIN m_customer ON m_customer.cust_id = '.$param['table'].'.cust_id
								  JOIN m_petugas_legalkayu ON m_petugas_legalkayu.petugas_legalkayu_id = '.$param['table'].'.petugas_legalkayu_id
								  JOIN m_pegawai ON m_pegawai.pegawai_id = m_petugas_legalkayu.pegawai_id
					'];
				$param['where'] = ["jenis_dokumen <> 'DKB'"];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionPrintDokumen(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TDokumenPenjualan::findOne($_GET['id']);
		$modDetail = \app\models\TDokumenPenjualanDetail::find()->where(['dokumen_penjualan_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		if($model->jenis_dokumen == "DKO"){
			$paramprint['judul'] = Yii::t('app', 'DAFTAR KAYU OLAHAN');
		}else if($model->jenis_dokumen == "DKB"){
			$paramprint['judul'] = Yii::t('app', 'DAFTAR KAYU BULAT');
		}else if($model->jenis_dokumen == "Nota Perusahaan"){
			$paramprint['judul'] = Yii::t('app', 'NOTA PERUSAHAAN');
		}
		
		if($caraprint == 'PRINT'){
			return $this->render('printDokumen',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printDokumen',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printDokumen',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}
	
	public function actionSetNota(){
		if(\Yii::$app->request->isAjax){
			$spm_ko_id = \Yii::$app->request->post('spm_ko_id');
			$data = []; $data['tempo'] = [];
			if(!empty($spm_ko_id)){
				$model = \app\models\TNotaPenjualan::findOne(['spm_ko_id'=>$spm_ko_id]);
				$modCust = \app\models\MCustomer::findOne($model->cust_id);
				if(!empty($model)){
					$data = $model->attributes;
				}
				if(!empty($modCust)){
					$data['cust'] = $modCust->attributes;
					$data['cust']['cust_pr_nama'] = (!empty($modCust->cust_pr_nama)?$modCust->cust_pr_nama:"-");
				}
			}
			return $this->asJson($data);
		}
	}
	
}
