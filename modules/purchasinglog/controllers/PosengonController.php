<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PosengonController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TPosengonRencana();
        $model->tanggal = date("d/m/Y");
        $model->tanggal_pengiriman_awal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_pengiriman_awal);
        $model->tanggal_pengiriman_akhir = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_pengiriman_akhir);
        $model->menyetujui = \app\components\Params::DEFAULT_PEGAWAI_ID_PAK_WID;
        $model->menyetujui_display = \app\models\MPegawai::findOne($model->menyetujui)->pegawai_nama;
        
        if(isset($_GET['posengon_rencana_id'])){
            $model = \app\models\TPosengonRencana::findOne($_GET['posengon_rencana_id']);
            $model->kode = $model->posengon_rencana_id;
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->tanggal_pengiriman_awal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_pengiriman_awal);
            $model->tanggal_pengiriman_akhir = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_pengiriman_akhir);
            $modPmr = \app\models\TPmr::findOne(['posengon_rencana_id'=>$model->posengon_rencana_id]);
            $model->pmr_id = $modPmr->pmr_id;
            $model->kode_permintaan = $modPmr->kode." ".\app\components\DeltaFormatter::formatDateTimeForUser($modPmr->tanggal_dibutuhkan_awal)." - ".\app\components\DeltaFormatter::formatDateTimeForUser($modPmr->tanggal_dibutuhkan_akhir);
            $model->menyetujui_display = \app\models\MPegawai::findOne($model->menyetujui)->pegawai_nama;
        }
        
        if( Yii::$app->request->post('TPosengonRencana') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_posengon_rencana
                $success_2 = true; // t_pmr -- update
                $success_3 = false; // t_approval
                $model->load(\Yii::$app->request->post());
                
                $modPmrs = \app\models\TPmr::findOne($_POST['TPosengonRencana']['pmr_id']);
                if($modPmrs->jenis_log =='LS'){
                    $model->kode = \app\components\DeltaGenerator::rencanaPOSengon();
                }else if($modPmrs->jenis_log =='LJ'){
                    $model->kode = \app\components\DeltaGenerator::rencanaPOJabon();
                }
                
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                        
                        // UPDATE t_pmr
                        $modPmr = \app\models\TPmr::findOne($_POST['TPosengonRencana']['pmr_id']);
                        $modPmr->posengon_rencana_id = $model->posengon_rencana_id;
                        if($modPmr->validate()){
                            if($modPmr->save()){
                                $success_2 = true;
                            }
                        }
                        // END UPDATE
                        
                        // START Create Approval
                        $modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
                        if(count($modelApproval)>0){ // edit mode
                                if(\app\models\TApproval::deleteAll(['reff_no'=>$model->kode])){
                                        $success_3 = $this->saveApproval($model);
                                }
                        }else{ // insert mode
                                $success_3 = $this->saveApproval($model);
                        }
                        // END Create Approval
                    }
                }
//                echo "<pre>1 = ";
//                print_r($success_1);
//                echo "<pre>2 = ";
//                print_r($success_2);
//                echo "<pre>3 = ";
//                print_r($success_3);
//                exit;
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE));
                    return $this->redirect(['index','success'=>1,'posengon_rencana_id'=>$model->posengon_rencana_id]);
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
    
    public function saveApproval($model){
		$success = true;
		if($model->menyetujui){
			$modelApproval = new \app\models\TApproval();
			$modelApproval->assigned_to = $model->menyetujui;
			$modelApproval->reff_no = $model->kode;
			$modelApproval->tanggal_berkas = $model->tanggal;
			$modelApproval->level = 1;
			$modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
			$success &= $modelApproval->createApproval();
		}
		return $success;
	}
    
    public function actionOpenpermintaanlog(){
		if(\Yii::$app->request->isAjax){
            $pick = \Yii::$app->request->get('pick');
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPmr::tableName();
				$param['pk']= $param['table'].".". \app\models\TPmr::primaryKey()[0];
				$param['column'] = [$param['table'].'.pmr_id',
									$param['table'].'.kode',
									$param['table'].'.tanggal',
									$param['table'].'.jenis_log',
									$param['table'].'.tujuan',
									"CONCAT( TO_CHAR(tanggal_dibutuhkan_awal :: DATE, 'dd/mm/yyyy'),' sd ',TO_CHAR(tanggal_dibutuhkan_akhir :: DATE, 'dd/mm/yyyy')) as dibutuhkan",
									'(SELECT SUM(qty_m3) FROM t_pmr_detail WHERE t_pmr_detail.pmr_id = t_pmr.pmr_id) AS total_m3',
									'm_pegawai.pegawai_nama AS dibuat_oleh',
									'pegawai1.pegawai_nama AS approver_1',
									'pegawai2.pegawai_nama AS approver_2',
									'pegawai3.pegawai_nama AS approver_3',
                                    '(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_1) AS approver_1_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_2) AS approver_2_status',
									'(SELECT status FROM t_approval WHERE reff_no = t_pmr.kode AND assigned_to = t_pmr.approver_3) AS approver_3_status',
                                    '( SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT kode FROM t_posengon_rencana WHERE t_posengon_rencana.posengon_rencana_id = t_pmr.posengon_rencana_id) t) AS rencana_sengon',
                                    $param['table'].'.status'
									];
				$param['join']= ['
								JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.dibuat_oleh 
								JOIN m_pegawai AS pegawai1 ON pegawai1.pegawai_id = '.$param['table'].'.approver_1 
								JOIN m_pegawai AS pegawai2 ON pegawai2.pegawai_id = '.$param['table'].'.approver_2 
								LEFT JOIN m_pegawai AS pegawai3 ON pegawai3.pegawai_id = '.$param['table'].'.approver_3 
								'];
				$param['where'] = $param['table'].".cancel_transaksi_id IS NULL AND t_pmr.jenis_log in('LS','LJ')";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
            return $this->renderAjax('openPermintaanLog',['url'=>\yii\helpers\Url::toRoute('/purchasinglog/posengon/openpermintaanlog')]);
        }
    }
    function actionPick(){
		if(\Yii::$app->request->isAjax){
            $kode = Yii::$app->request->post('kode');
            $data = null;
            if(!empty($kode)){
                $model = \app\models\TPmr::findOne(['kode'=>$kode]);
                $data = $model->attributes;
            }
            return $this->asJson($data);
        }
    }
    public function actionSetValue(){
		if(\Yii::$app->request->isAjax){
            $posengon_rencana_id = Yii::$app->request->post('posengon_rencana_id'); $data = '';
            if(is_numeric($posengon_rencana_id)){
                $model = \app\models\TPosengonRencana::findOne($posengon_rencana_id);
                if(!empty($model)){
                    $data['model'] = $model->attributes;
                    $modPmr = \app\models\TPmr::findOne(['posengon_rencana_id'=>$model->posengon_rencana_id]);
                    $data['modPmr'] = $modPmr->attributes;
                    $modApproval = \app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->menyetujui]);
                    if(!empty($modApproval)){
                        $data['modApproval'] = $modApproval->attributes;
                        if($modApproval->status == \app\models\TApproval::STATUS_APPROVED){
                            $data['labelapproval'] = '<label class="label label-sm label-success">'.\app\models\TApproval::STATUS_APPROVED.'</label><br><span class="font-green-seagreen" style="font-size:1rem;">APPROVED at '.\app\components\DeltaFormatter::formatDateTimeForUser2($modApproval->updated_at).'</span>';
                        }else if($modApproval->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
                            $data['labelapproval'] = '<label class="label label-sm label-default">'.\app\models\TApproval::STATUS_NOT_CONFIRMATED.'</label>';
                        }else if($modApproval->status == \app\models\TApproval::STATUS_REJECTED){
                            $data['labelapproval'] = '<label class="label label-sm label-danger">'.\app\models\TApproval::STATUS_REJECTED.'</label><br><span class="font-red-flamingo" style="font-size:1rem;">REJECTED at '.\app\components\DeltaFormatter::formatDateTimeForUser2($modApproval->updated_at).'</span>';
                        }
                    }
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $posengon_rencana_id = Yii::$app->request->post('id'); $data = []; $data['html'] = '';
            if(!empty($posengon_rencana_id)){
                $modRencana = \app\models\TPosengonRencana::findOne($posengon_rencana_id);
                $models = \app\models\TPosengon::find()->where("posengon_rencana_id = {$posengon_rencana_id}")->orderBy("posengon_id ASC")->all();
                if(count($models)>0){
                    $modApproval = \app\models\TApproval::findOne(['reff_no'=>$modRencana->kode,'assigned_to'=>$modRencana->menyetujui]);
                    foreach($models as $i => $model){
                        $data['html'] .= $this->renderPartial('_item',['model'=>$model,'modApproval'=>$modApproval ]);
                    }
                }
            }
            return $this->asJson($data);
        }
    }
    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
            $model = new \app\models\TPosengon();
            $data['html'] = $this->renderPartial('_item',['model'=>$model]);
            return $this->asJson($data);
        }
    }
    function actionSavePO(){
        if(\Yii::$app->request->isAjax){
            $posengon_rencana_id = Yii::$app->request->post('posengon_rencana_id');
            $post = []; parse_str(\Yii::$app->request->post('data'),$post);
            foreach ($post['TPosengon'] as $i => $asd){
                if(is_array($asd)){
                    $postdetail = $asd;
                }
            }
            $data = null; $panjang = [];
            if(!empty($posengon_rencana_id)){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    
                    $modRencana = \app\models\TPosengonRencana::findOne($posengon_rencana_id);
                    $modSuplier = \app\models\MSuplier::findOne($postdetail['suplier_id']);
                    $panjang = []; $kuota = [];
                    foreach($postdetail as $i => $m3){
                        if(is_array($m3)){
                            if($m3['qty_m3']>0){
                                $panjang[] = $i;
                                $kuota[$i] = \app\components\DeltaFormatter::formatNumberForDb($m3['qty_m3']);
                            }
                        }
                    }
                    if(!empty($postdetail['posengon_id'])){
                        $model = \app\models\TPosengon::findOne($postdetail['posengon_id']);
                        $edit = true;
                    }else{
                        $model = new \app\models\TPosengon();
                        $modPmrs = \app\models\TPmr::findOne(['posengon_rencana_id'=>$modRencana->posengon_rencana_id]);
                        if($modPmrs->jenis_log == 'LJ'){
                            $model->kode = \app\components\DeltaGenerator::kodePOJabon();
                        }else if($modPmrs->jenis_log == 'LS'){
                            $model->kode = \app\components\DeltaGenerator::kodePOSengon();
                        } 
                        $edit = false;
                    }
                    
                    $modPmrs = \app\models\TPmr::findOne(['posengon_rencana_id'=>$modRencana->posengon_rencana_id]);
                    if($modPmrs->jenis_log == 'LJ'){
                        $jenisLog = "Log Jabon";
                    }else if($modPmrs->jenis_log == 'LS'){
                        $jenisLog = "Log Sengon";
                    }
                    
                    $model->attributes = $postdetail;
                    $model->tanggal = date("Y-m-d");
                    $model->posengon_rencana_id = $modRencana->posengon_rencana_id;
                    $model->nama_barang = $jenisLog;
                    $model->panjang = !empty($panjang)? \yii\helpers\Json::encode( $panjang ):null;
                    $model->kuota =  !empty($kuota)? \yii\helpers\Json::encode( $kuota ):null;
                    $model->periode_pengiriman_awal = $modRencana->tanggal_pengiriman_awal;
                    $model->periode_pengiriman_akhir = $modRencana->tanggal_pengiriman_akhir;
                    $model->cara_bayar = "Transfer bank ketika barang sampai di PT. Cipta Wijaya Mandiri";
                    $model->rekening_bank = !empty($modSuplier->suplier_norekening)? "<b>".$modSuplier->suplier_norekening."</b> (".$modSuplier->suplier_bank.") an ".$modSuplier->suplier_an_rekening:"-";
                    $model->spesifikasi_log = \yii\helpers\Json::encode([ 'Kualitas'=>'Kayu Segar (Fresh)',
                                                'Tidak Diperbolehkan'=>'Kayu berlubang bagian diameter, Pecah ring / lingkar, Diameter diatas 4 cm, Lapuk, Bentuk S',
                                                'Mata Kayu Sehat'=>'Ukuran maksimal 15 cm, Jumlah hanya 1 mata per potongan kayu atau Benjolan maksimal 4 cm',
                                                'Kayu Pecah Samping'=>'Panjang pecah maksimal 15 cm (dipaku S & lebar pecah maksimal 5 mm)',
                                                'Kelengkungan'=>'Toleransi lengkung 3 cm dari garis lurus ujung ke ujung dan minimal diameter 22 cm',
                                                'Benjolan / Bekas Cabang'=>'Benjolan Maksimal 4 cm',
                                                'Mata Kayu Mati'=>'Boleh, maksimal 3 cm dan 2 mata per batang',
                                                'Bentuk Belimbing / Bontos'=>'Maksimal 3% dari setiap pengiriman',

                                                'Cara ukur diameter, diukur disalah satu sisi batang (Bagian Ujung)',
                                                'Harga tersebut diatas adalah harga log yang diterima sampai Gudang Pembeli',
                                                'Kayu yang dikirim sudah dilengkapi dengan <b>Legalitas kayu (Nota Angkutan) dan keabsahan dokumen</b> yaitu 
                                                 <b>Surat Keterangan Tanah berupa Sertifikat Hak Milik, Letter C, Hak Guna Usaha, Hak Pakai, 
                                                 atau dokumen pemilikan lainnya yang diakui oleh Kementrian Agraria dan Tata Ruang / Badan Pertahanan Nasional (WAJIB)</b>'
                                                ]);
                    $model->disetujui_supplier = $modSuplier->suplier_nm;
                    $model->disetujui_cwm = \app\components\Params::DEFAULT_PEGAWAI_ID_PAK_WID;
                    if(!empty($model->diameter_harga)){
                        $model->diameter_harga = \yii\helpers\Json::decode($model->diameter_harga); $diameter_harga = [];
                        foreach($model->diameter_harga as $i => $diaharga){
                            foreach($diaharga as $ii => $dihar){
                                $diameter_harga[$i][$ii]['panjang'] = \app\components\DeltaFormatter::formatNumberForDb2($dihar['panjang']);
                                $diameter_harga[$i][$ii]['wilayah'] = $dihar['wilayah'];
                                $diameter_harga[$i][$ii]['diameter_awal'] = \app\components\DeltaFormatter::formatNumberForDb2($dihar['diameter_awal']);
                                $diameter_harga[$i][$ii]['diameter_akhir'] = \app\components\DeltaFormatter::formatNumberForDb2($dihar['diameter_akhir']);
                                $diameter_harga[$i][$ii]['harga'] = \app\components\DeltaFormatter::formatNumberForDb2($dihar['harga']);
                            }
                        }
                        $model->diameter_harga = \yii\helpers\Json::encode($diameter_harga);
                    }else{
                        $model->diameter_harga = '[]';
                    }
                    
//                    echo "<pre>";
//                    print_r($postdetail);
//                    print_r($posengon_rencana_id);
//                    echo "<pre>";
//                    print_r($model->attributes);
//                    print_r($model->validate());
//                    print_r($model->errors);
//                    exit;
                    
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = ($edit)?Yii::t('app', "Berhasil Update"):Yii::t('app', "Berhasil Ditambahkan");
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
            }
            return $this->asJson($data);
        }
    }
    public function actionDeletePO($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\TPosengon::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if($model->delete()){
                        $success_1 = true;
                    }else{
                        $data['message'] = Yii::t('app', 'Data Gagal dihapus');
                    }
//                    echo "<pre>1";
//                    print_r($success_1);
//                    exit;
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
						$data['callback'] = "getItems();";
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid,'actionname'=>'deletePO']);
		}
	}
    
    
    public function actionSetHarga(){
		if(\Yii::$app->request->isAjax){
            $model = new \app\models\TPosengon();
            $no_urut = Yii::$app->request->get('no_urut');
            $source = Yii::$app->request->get('source');
            $model->diameter_harga = $source;
            return $this->renderAjax('setHarga',['model'=>$model,'no_urut'=>$no_urut]);
        }
    }
    public function actionAddItemHarga(){
		if(\Yii::$app->request->isAjax){
            $par = Yii::$app->request->post('par');
            $par = explode("-", $par);
            $model = new \app\models\TPosengon();
            $model->panjang = $par[0];
            $model->wilayah = isset($par[1])?$par[1]:"all";
            $model->diameter_awal = 0;
            $model->diameter_akhir = 0;
            $model->harga = 0;
            $data['html'] = "";
            $data['html'] .= $this->renderPartial('_itemHarga',['model'=>$model]);
            return $this->asJson($data);
        }
    }
    public function actionGetItemsHarga(){
		if(\Yii::$app->request->isAjax){
            $model = new \app\models\TPosengon();
            $setharga_source = Yii::$app->request->post('setharga_source');
            if(!empty($setharga_source)){
                
            }
            return $this->renderAjax('setHarga');
        }
    }
    
    public function actionGetItemsHargaFromBottom(){
		if(\Yii::$app->request->isAjax){
            $diameterharga = Yii::$app->request->post('diameter_harga');
            $diameterharga = \yii\helpers\Json::decode($diameterharga);
            $asd = [];
            if(!empty($diameterharga)){
                foreach($diameterharga as $i => $dia_harga){
                    foreach($dia_harga as $ii => $dihar){
                        $model = new \app\models\TPosengon();
                        $model->panjang = $dihar['panjang'];
                        $model->wilayah = $dihar['wilayah'];
                        $model->diameter_awal = $dihar['diameter_awal'];
                        $model->diameter_akhir = $dihar['diameter_akhir'];
                        $model->harga = $dihar['harga'];
                        $asd[$i][$ii]['data'] = $dihar;
                        $asd[$i][$ii]['html'] = $this->renderPartial('_itemHarga',['model'=>$model]);
                    }
                }
            }
            return $this->asJson($asd);
        }
    }
    
    public function actionEditPOUpdateNotconfirm(){
		if(\Yii::$app->request->isAjax){
            $posengon_rencana_id = Yii::$app->request->post('posengon_rencana_id'); 
            $data = false;
            if(!empty($posengon_rencana_id)){
                $modRencana = \app\models\TPosengonRencana::findOne($posengon_rencana_id);
                if(!empty($modRencana)){
                    $modApproval = \app\models\TApproval::findOne(['reff_no'=>$modRencana->kode]);
                    if(!empty($modApproval)){
                        $modApproval->approved_by = null;
                        $modApproval->tanggal_approve = null;
                        $modApproval->status = "Not Confirmed";
                        $data = $modApproval->save();
                    }
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function actionDetailPo(){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TPosengon::findOne($_GET['id']);
			$modRencana = \app\models\TPosengonRencana::findOne($model->posengon_rencana_id);
			$paramprint['judul'] = Yii::t('app', 'PURCHASE ORDER');
			return $this->renderAjax('detailPo',['model'=>$model,'modRencana'=>$modRencana,'paramprint'=>$paramprint]);
        }
	}
    public function actionDetailPoByKode(){
		$this->layout = '@views/layouts/metronic/print';
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TPosengon::findOne(['kode'=>$_GET['kode']]);
			$modRencana = \app\models\TPosengonRencana::findOne($model->posengon_rencana_id);
			$paramprint['judul'] = Yii::t('app', 'PURCHASE ORDER');
			return $this->renderAjax('detailPo',['model'=>$model,'modRencana'=>$modRencana,'paramprint'=>$paramprint]);
        }
	}
    
    public function actionPrintPo(){
		$this->layout = '@views/layouts/metronic/print';
		if(!empty($_GET['id'])){
            $model = \app\models\TPosengon::findOne($_GET['id']);
			$modRencana = \app\models\TPosengonRencana::findOne($model->posengon_rencana_id);
			$paramprint['judul'] = Yii::t('app', 'PURCHASE ORDER');
            $caraprint = Yii::$app->request->get('caraprint');
			if($caraprint == 'PRINT'){
				return $this->render('printPo',['model'=>$model,'modRencana'=>$modRencana,'paramprint'=>$paramprint]);
			}else if($caraprint == 'PDF'){
				$pdf = Yii::$app->pdf;
				$pdf->options = ['title' => $paramprint['judul']];
				$pdf->filename = $paramprint['judul'].'.pdf';
				$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
				$pdf->content = $this->render('printPo',['model'=>$model,'modRencana'=>$modRencana,'paramprint'=>$paramprint]);
				return $pdf->render();
			}else if($caraprint == 'EXCEL'){
				return $this->render('printPo',['model'=>$model,'modRencana'=>$modRencana,'paramprint'=>$paramprint]);
			}
		}
	}
    
    public function actionCariPoSengon(){
		if(\Yii::$app->request->isAjax){
            $pick = \Yii::$app->request->get('pick');
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TPosengon::tableName();
				$param['pk']= $param['table'].".". \app\models\TPosengon::primaryKey()[0];
				$param['column'] = [$param['table'].'.posengon_id',
                                    $param['table'].'.kode',
									$param['table'].'.tanggal',
									'm_suplier.suplier_nm',
									'm_suplier.suplier_almt',
									$param['table'].'.periode_pengiriman_awal',
									$param['table'].'.periode_pengiriman_akhir',
									$param['table'].'.kuota',
									't_approval.status AS approval_status',
									'm_pegawai.pegawai_nama AS pegawai_approver',
									't_approval.updated_at',
                                    $param['table'].'.suplier_id',
                                    '(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT kode FROM t_open_voucher WHERE t_open_voucher.reff_no=t_posengon.kode ) t) AS reff_ov',
                                    "(CASE "
                                        . "when substring(t_posengon.kode,1,3)='PLS' then 'Log Sengon' "
                                        . "when substring(t_posengon.kode,1,3)='PLJ' then 'Log Jabon' "
                                        . "ELSE '-' END) as jenis_log",
									];
				$param['join']= ['
                                JOIN m_suplier ON m_suplier.suplier_id = '.$param['table'].'.suplier_id 
                                JOIN t_posengon_rencana ON t_posengon_rencana.posengon_rencana_id = '.$param['table'].'.posengon_rencana_id 
                                JOIN t_approval ON t_approval.reff_no = t_posengon_rencana.kode 
                                JOIN m_pegawai ON m_pegawai.pegawai_id = t_approval.approved_by 
								'];
				$param['where'] = "";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('cariPOSengon',['pick'=>$pick]);
        }
    }
}