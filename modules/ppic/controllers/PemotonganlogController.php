<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\HPersediaanLog;
use app\models\TLogKeluar;
use app\models\TPemotonganLog;
use app\models\TPemotonganLogDetail;
use app\models\TPemotonganLogDetailPotong;

class PemotonganlogController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TPemotonganLog();
		$model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->peruntukan = 'Industri';
        $model->petugas = Yii::$app->user->identity->pegawai_id;
		$modDetail = [];
        $modDetailPot = [];
		
		if(isset($_GET['pemotongan_log_id'])){
            $model = \app\models\TPemotonganLog::findOne($_GET['pemotongan_log_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
        }
		
		if( Yii::$app->request->post('TPemotonganLog') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_pemotongan_log
                $success_2 = false; // t_pemotongan_log_detail
                $success_3 = false; // t_pemotongan_log_detail_potong
                $model->load(\Yii::$app->request->post());
				if(!isset($_GET['edit'])){
					$model->kode = \app\components\DeltaGenerator::kodePemotonganLog();
				}
                $model->petugas = $_POST['TPemotonganLog']['petugas'] == ''?Yii::$app->user->identity->pegawai_id:$_POST['TPemotonganLog']['petugas'];
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        // print_r($model); exit;

                        if(isset($_GET['edit'])){ // jika proses edit
                            // hapus t_pemotongan_log_detail_potong
                            $details = \app\models\TPemotonganLogDetail::findAll(['pemotongan_log_id'=>$model->pemotongan_log_id]);
                            foreach ($details as $b => $detail){
                                $success_3 = (\app\models\TPemotonganLogDetailPotong::deleteAll(["pemotongan_log_detail_id"=>$detail['pemotongan_log_detail_id'], 'status_penerimaan'=>false]))?true:false;
                            }

                            // hapus t_pemotongan_log_detail
                            $success_2 = (\app\models\TPemotonganLogDetail::deleteAll("pemotongan_log_id = ".$model->pemotongan_log_id))?true:false;
                        }

                        if(isset($_POST['TPemotonganLogDetail'])){
                            foreach($_POST['TPemotonganLogDetail'] as $i => $detail){
                                $modDetail = new \app\models\TPemotonganLogDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->pemotongan_log_id = $model->pemotongan_log_id;
                                $modDetail->reduksi = '0';
                                $modDetail->panjang = $_POST['TPemotonganLogDetail'][$i]['panjang'] !== ''?$_POST['TPemotonganLogDetail'][$i]['panjang']/100:0; // convert panjang cm menjadi m 
                                if($modDetail->validate()){
									if($modDetail->save()){
										$success_2 = true;

                                        if(isset($_POST['TPemotonganLogDetailPotong'])){
                                            foreach($_POST['TPemotonganLogDetailPotong'][$i] as $ii => $detailpotong){
                                                if($_POST['TPemotonganLogDetailPotong'][$i][$ii]['kode_pemotongan']){
                                                    $no_barcode_baru =$modDetail->no_barcode . '.' . $_POST['TPemotonganLogDetailPotong'][$i][$ii]['kode_pemotongan'];
                                                } else {
                                                    $no_barcode_baru =$modDetail->no_barcode;
                                                }
                                                $modDetailPotOld = TPemotonganLogDetailPotong::findOne(['no_barcode_baru'=>$no_barcode_baru]);
                                                // cek yg sdh diterima dan update pemotongan_log_detail_id
                                                if(isset($_GET['edit']) && $modDetailPotOld){
                                                    $modDetailPotOld->pemotongan_log_detail_id = $modDetail->pemotongan_log_detail_id;
                                                    $modDetailPotOld->save();
                                                    continue;
                                                }

                                                $modDetailPot = new \app\models\TPemotonganLogDetailPotong();
                                                $modDetailPot->attributes = $detailpotong;
                                                $modDetailPot->pemotongan_log_detail_id = $modDetail->pemotongan_log_detail_id;
                                                $kode_potong = strtoupper($_POST['TPemotonganLogDetailPotong'][$i][$ii]['kode_pemotongan']);
                                                $modDetailPot->kode_pemotongan = $kode_potong;
                                                $modDetailPot->no_barcode_lama = $modDetail->no_barcode;
                                                $modDetailPot->reduksi_baru = '0';
                                                if( !isset($_POST['TPemotonganLogDetailPotong'][$i][$ii]['grading_rule']) ||
                                                    $_POST['TPemotonganLogDetailPotong'][$i][$ii]['grading_rule'] == '' || 
                                                    $_POST['TPemotonganLogDetailPotong'][$i][$ii]['alokasi'] == 'Gudang' ){
                                                    $grade = null;
                                                } else {
                                                    $grade = $_POST['TPemotonganLogDetailPotong'][$i][$ii]['grading_rule'];
                                                }
                                                $modDetailPot->panjang_baru = $_POST['TPemotonganLogDetailPotong'][$i][$ii]['panjang_baru'] !== ''?$_POST['TPemotonganLogDetailPotong'][$i][$ii]['panjang_baru']/100:0; // convert panjang dari cm ke m
                                                $modDetailPot->grading_rule = $grade;
                                                $modLog = TLogKeluar::findOne(['no_barcode'=>$modDetail->no_barcode]);
				                                $modPersediaan = HPersediaanLog::findOne(['no_barcode'=>$modDetail->no_barcode, 'reff_no'=>$modLog->reff_no]);
                                                if($_POST['TPemotonganLogDetailPotong'][$i][$ii]['kode_pemotongan']){
                                                    $modDetailPot->no_barcode_baru = $modDetail->no_barcode . '.' . $kode_potong;
                                                    $modDetailPot->no_lap_baru = $modPersediaan->no_lap . '.' . $kode_potong;
                                                } else {
                                                    $modDetailPot->no_barcode_baru = $modDetail->no_barcode;
                                                    $modDetailPot->no_lap_baru = $modPersediaan->no_lap;
                                                }
                                                if($modDetailPot->validate()){
                                                    if($modDetailPot->save()){
                                                        $success_3 = true;
                                                    }
                                                }
                                            }
                                        }
									}
								} 
                            }
                        }
                    }
                }
				// echo "<pre>1";
				// print_r($success_1);
				// echo "<pre>2";
				// print_r($success_2);
				// echo "<pre>3";
				// print_r($success_3);
                // print_r($_POST['TPemotonganLogDetailPotong']);
				// exit;
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'pemotongan_log_id'=>$model->pemotongan_log_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		
		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail, 'modDetailPot'=>$modDetailPot]);
	}

    public function actionStockLog(){
        // var_dump(\Yii::$app->request->get('edit')); die;
		if(\Yii::$app->request->isAjax){
            $nomor = Yii::$app->request->get('nomor');
            $edit = Yii::$app->request->get('edit');
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TLogKeluar::tableName();
				$param['pk']= $param['table'].".".\app\models\TLogKeluar::primaryKey()[0];
				$param['column'] = [$param['table'].'.log_keluar_id',
                                    "CONCAT(m_kayu.group_kayu,' - ',m_kayu.kayu_nama) AS kayu",
									$param['table'].'.no_barcode',
									'h_persediaan_log.no_grade',
									'h_persediaan_log.no_btg',
									'h_persediaan_log.no_lap',
                                    'h_persediaan_log.fisik_diameter',
                                    'h_persediaan_log.fisik_panjang',
                                    'h_persediaan_log.fisik_volume'
                                ];
				$param['join'] = [" JOIN h_persediaan_log on t_log_keluar.no_barcode = h_persediaan_log.no_barcode
                                    JOIN m_kayu ON m_kayu.kayu_id = h_persediaan_log.kayu_id
                                "];
                $param['where'] = " t_log_keluar.reff_no = '{$nomor}' AND NOT EXISTS (SELECT t_log_keluar.no_barcode FROM t_pemotongan_log_detail_potong 
				                    WHERE t_log_keluar.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama) AND NOT EXISTS (SELECT t_log_keluar.no_barcode 
                                    FROM view_persediaan_logalam WHERE t_log_keluar.no_barcode = view_persediaan_logalam.no_barcode) AND NOT EXISTS 
                                    (select t_pengembalian_log_detail.no_barcode FROM t_pengembalian_log_detail WHERE t_log_keluar.no_barcode = t_pengembalian_log_detail.no_barcode)";
                $param['group'] = " GROUP BY t_log_keluar.log_keluar_id, t_log_keluar.no_barcode,m_kayu.group_kayu, m_kayu.kayu_nama, h_persediaan_log.no_grade,
                                    h_persediaan_log.no_btg,h_persediaan_log.no_lap,h_persediaan_log.fisik_diameter, h_persediaan_log.fisik_panjang,h_persediaan_log.fisik_volume";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('stockLog', ['nomor'=>$nomor, 'edit'=>$edit]);
        }
	}

    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
			$no_barcode = Yii::$app->request->post('no_barcode');
            $minpotong = Yii::$app->request->post('minpotong');
            // $modPersediaan = \app\models\HPersediaanLog::getCurrentStockPerBatang($no_barcode);
            $modLog = TLogKeluar::findOne(['no_barcode'=>$no_barcode]);
            $modPersediaan = HPersediaanLog::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$modLog->reff_no]);
			$modDetail = new \app\models\TPemotonganLogDetail();
            $modDetailPot = new \app\models\TPemotonganLogDetailPotong();
			$modDetail->attributes = $modPersediaan->attributes;
			$modDetail->panjang = $modPersediaan->fisik_panjang * 100; // convert panjang dari m dijadikan cm
			$modDetail->volume = $modPersediaan->fisik_volume;
            $modDetail->diameter = $modPersediaan->fisik_diameter;
            $no_lap = $modPersediaan->no_lap;
			$modDetail->jumlah_potong = !empty($minpotong)?$minpotong:2;
            $modDetail->potong = true;
            $data['html'] = $this->renderPartial('_item',['modDetail'=>$modDetail, 'modDetailPot'=>$modDetailPot, 'no_lap'=>$no_lap]);
            $data['no_barcode'] = $modPersediaan->no_barcode;
            return $this->asJson($data);
        }
    }

    public function actionAddDetailPotong(){
        $i = Yii::$app->request->post('i');
        $no_barcode = Yii::$app->request->post('no_barcode');
        $modLog = TLogKeluar::findOne(['no_barcode'=>$no_barcode]);
		$modPersediaan = HPersediaanLog::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$modLog->reff_no]);
        $no_lap = $modPersediaan->no_lap;
        $data['html'] = '';
        $modDetailPot = new \app\models\TPemotonganLogDetailPotong();
        $data['html'] = $this->renderPartial('_itemDetailPotong',['modDetailPot'=>$modDetailPot, 'i'=>$i, 'no_lap'=>$no_lap]);
        return $this->asJson($data);
    }

    public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $pemotongan_log_id = Yii::$app->request->post('pemotongan_log_id');
            $edit = Yii::$app->request->post('edit');
            $data = [];
            $data['html'] = '';

            if(!empty($pemotongan_log_id)){
                $modDetail = \app\models\TPemotonganLogDetail::find()->where(['pemotongan_log_id'=>$pemotongan_log_id])->orderBy(['pemotongan_log_detail_id'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    foreach($modDetail as $i => $detail){
                        $data['html'] .= $this->renderPartial('_item',['modDetail'=>$detail,'edit'=>$edit, 'i'=>$i]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPemotonganLog::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TPemotonganLog::primaryKey()[0];
				$param['column'] = ['t_pemotongan_log.pemotongan_log_id',
                                    't_pemotongan_log.kode', 
                                    't_pemotongan_log.nomor', 
                                    't_pemotongan_log.tanggal', 
                                    'm_pegawai.pegawai_nama', 
                                    't_pemotongan_log.peruntukan', 
                                    't_pemotongan_log.keterangan', 
									];
				$param['join'] = [' LEFT JOIN t_pemotongan_log_detail ON t_pemotongan_log_detail.pemotongan_log_id = t_pemotongan_log.pemotongan_log_id
                                    LEFT JOIN m_pegawai ON m_pegawai.pegawai_id = t_pemotongan_log.petugas'];
                $param['group'] = "GROUP BY t_pemotongan_log.pemotongan_log_id,m_pegawai.pegawai_nama";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

    public function actionDaftarAfterSave2(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPemotonganLog::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TPemotonganLog::primaryKey()[0];
				$param['column'] = ['t_pemotongan_log.pemotongan_log_id',
                                    't_pemotongan_log.kode', 
                                    't_pemotongan_log.nomor', 
                                    't_pemotongan_log.tanggal', 
                                    'm_pegawai.pegawai_nama', 
                                    't_pemotongan_log.peruntukan', 
                                    't_pemotongan_log_detail.no_barcode', 
                                    'm_kayu.group_kayu',
                                    't_pemotongan_log_detail.panjang', 
                                    't_pemotongan_log_detail.volume', 
                                    't_pemotongan_log_detail.jumlah_potong',
                                    't_pemotongan_log.keterangan', 
                                    'm_kayu.kayu_nama'
									];
				$param['join'] = [' LEFT JOIN t_pemotongan_log_detail ON t_pemotongan_log_detail.pemotongan_log_id = t_pemotongan_log.pemotongan_log_id
                                    LEFT JOIN m_kayu ON m_kayu.kayu_id = t_pemotongan_log_detail.kayu_id
                                    LEFT JOIN m_pegawai ON m_pegawai.pegawai_id = t_pemotongan_log.petugas'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

    public function actionPrintLog(){
		$this->layout = '@views/layouts/metronic/print';
		$model = \app\models\TPemotonganLog::findOne($_GET['id']);
		$modDetail = \app\models\TPemotonganLogDetail::find()->where(['pemotongan_log_id'=>$_GET['id']])->all();
		$caraprint = Yii::$app->request->get('caraprint');
		$paramprint['judul'] = Yii::t('app', 'PRINT PEMOTONGAN LOG ALAM PABRIK');
		if($caraprint == 'PRINT'){
			return $this->render('printLog',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('printLog',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('printLog',['model'=>$model,'paramprint'=>$paramprint,'modDetail'=>$modDetail]);
		}
	}

    public function actionSetDropdownNo(){
        $peruntukan = Yii::$app->request->post('peruntukan');
        $id = Yii::$app->request->post('id');
        $data['html'] = '';

        $html = \yii\bootstrap\Html::tag('option');
        $model = Yii::$app->db->createCommand("
                        SELECT reff_no FROM t_log_keluar 
                        JOIN view_reffno_pemotongan_log on view_reffno_pemotongan_log.nomor = t_log_keluar.reff_no
                        WHERE t_log_keluar.cara_keluar = '{$peruntukan}' GROUP BY reff_no
                        ")->queryAll(); 
        if(count($model) > 0){
            foreach($model as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val['reff_no'],['value'=>$val['reff_no']]);
			}
            $data['html'] = $html;
        }
        if($id){
            $model = TPemotonganLog::findOne($id);
            $data['nomor'] = $model->nomor;
        }
        
        return $this->asJson($data);
    }

    public function actionPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $caraprint = Yii::$app->request->get('caraprint');
        $pemotongan_log_detail_potong_id = $_GET['id'];
        $no_barcode = $_GET['no_barcode'];
        $paramprint['judul'] = Yii::t('app', 'Print QR Code');
        $qrCodeContent = "ID : " . $pemotongan_log_detail_potong_id .
            "\u000ANo : " . $no_barcode .
            "";
        $modDetpot = TPemotonganLogDetailPotong::findOne($pemotongan_log_detail_potong_id);
        $modDet = TPemotonganLogDetail::findOne($modDetpot->pemotongan_log_detail_id);
        $model = TPemotonganLog::findOne($modDet->pemotongan_log_id);
        if($modDetpot->status_penerimaan){
            $modDetail = HPersediaanLog::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$model->kode]);
        } else {
            $modLogkeluar = TLogKeluar::findOne(['no_barcode'=>$modDet->no_barcode]);
            $modDetail = HPersediaanLog::findOne(['no_barcode'=>$modDet->no_barcode, 'reff_no'=>$modLogkeluar->reff_no]);
        }

        if ($caraprint == 'PRINT') {
            return $this->render('print', ['paramprint' => $paramprint, 'modDetail' => $modDetail, 'modDetpot'=>$modDetpot, 'qrCodeContent' => $qrCodeContent]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] .'-'. $no_barcode . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('print', ['paramprint' => $paramprint, 'modDetail' => $modDetail, 'modDetpot'=>$modDetpot]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->render('print', ['paramprint' => $paramprint, 'modDetail' => $modDetail, 'modDetpot'=>$modDetpot]);
        }
    }

    public function actionTampilanMinPotong(){
        $i = Yii::$app->request->post('i');
        $no_lap = Yii::$app->request->post('no_lap');
        $jml = Yii::$app->request->post('jml');
        $cut = Yii::$app->request->post('cut');
        $panjang = Yii::$app->request->post('panjang');
        $data['html'] = '';
        $modDetailPot = new \app\models\TPemotonganLogDetailPotong();
        $data['html'] = $this->renderPartial('_itemDetailPotong_min',['modDetailPot'=>$modDetailPot, 'i'=>$i, 'no_lap'=>$no_lap, 'jml'=>$jml, 'cut'=>$cut, 'panjang'=>$panjang]);
        return $this->asJson($data);
    }
	
}
