<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class TerimaloglistController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TLoglist();
		$model->loglist_kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
        $model->model_ukuran_loglist = 0;
        $model->area_pembelian = 1;
        $modDetail = new \app\models\TLoglist();
		//$modDetail = [];
		$modDkg = [];
        $lampiran = 1;
        
        if(isset($_GET['loglist_id'])){
            $model = \app\models\TLoglist::findOne($_GET['loglist_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$model->loglist_id])->all();
			$modDkg = \app\models\TDkg::find()->where(['loglist_id'=>$model->loglist_id])->all();
			$model->kode_po = $model->logKontrak->kode." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($model->logKontrak->tanggal_po);
			$model->nomor_kontrak = $model->logKontrak->nomor;
            if($model->model_ukuran_loglist == "2 Diameter"){
                $model->model_ukuran_loglist = 0;
            }else if($model->model_ukuran_loglist == "4 Diameter"){
                $model->model_ukuran_loglist = 1;
            }

            if($model->area_pembelian == "Jawa"){
                $model->area_pembelian = 0;
            }else if($model->area_pembelian == "Luar Jawa"){
                $model->area_pembelian = 1;
            }

        }
        
		if( Yii::$app->request->post('TLoglist') || Yii::$app->request->post('TLoglistDetail') ) {
            if (Yii::$app->request->post('TLoglist')) {
                // JIKA TRANSAKSI LOGLIST
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = true;
                    $success_3 = true;
                    $model->load(\Yii::$app->request->post());
                    $dkg = [];
                    foreach($_POST['TLoglist'] as $postloglist){
                        if(is_array($postloglist)){
                            $dkg[] = $postloglist['grader_id'];
                        }
                    }

                    if(!empty($dkg)){
                        $model->grader_id = "-";
                    }
                    
                    if($model->area_pembelian == "Jawa"){
                        $model->area_pembelian = 0;
                    }else if($model->area_pembelian == "Luar Jawa"){
                        $model->area_pembelian = 1;
                    }

                    // insert generate loglist kode dul
                    $model->loglist_kode = \app\components\DeltaGenerator::kodeLoglistJoinGrade();

                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                            // v ndisik wis tau ngene

                            // ^ ndisik wis tau ngene
                            if(!empty($dkg)){
                                if(isset($_GET['edit'])){
                                    
                                }
                                foreach($dkg as $dkg_id){
                                    $modDkg = \app\models\TDkg::findOne($dkg_id);
                                    $modDkg->loglist_id = $model->loglist_id;
                                    if($modDkg->validate()){
                                        if($modDkg->save()){
                                            $success_2 &= true;
                                        }else{
                                            $success_2 &= false;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Data Loglist Berhasil disimpan'));
                        return $this->redirect(['index','success'=>1,'loglist_id'=>$model->loglist_id]);
                    } else {
                        $transaction->rollback();
                        Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', $ex);
                }
            
            } else {
                // JIKA TRANSAKSI LOGLIST DETAIL
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    // v ndisik wis tau ngene
                    $sql_lampiran_terakhir = "select distinct(lampiran) from t_loglist_detail where loglist_id = ".$model->loglist_id."";
                    $lampiran_terakhir = Yii::$app->db->createCommand($sql_lampiran_terakhir)->queryScalar();
                    empty($lampiran_terakhir) ? $lampiran_terakhir = 1 : $lampiran_terakhir = $_POST['TLoglistDetail']['0']['lampiran'];
                    if( (isset($_POST['TLoglistDetail'])) && (count($_POST['TLoglistDetail'])>0) ){
                        $success_1 = \app\models\TLoglistDetail::deleteAll("loglist_id = ".$model->loglist_id." and lampiran = ".$lampiran_terakhir."");
                        // jika yang akan disimpan adalah model ukuran loglist 2 diameter
                        //echo "<pre>";
                        //echo "<br>".$model->model_ukuran_loglist;
                        //echo "<br>";
                        //print_r (Yii::$app->request->post('TLoglistDetail'));
                        //echo "<br>model ukuran loglist ".$model->model_ukuran_loglist;
                        //echo "<br>area pembelian ".$model->area_pembelian;
                        if ($model->model_ukuran_loglist == 0 || $model->model_ukuran_loglist == '2 Diameter') {
                            foreach($_POST['TLoglistDetail'] as $i => $detail){
                                $modDetail = new \app\models\TLoglistDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->loglist_id = $model->loglist_id;
                                $modDetail->lampiran = $lampiran_terakhir * 1;
                                //echo "<br>disini";
                                if ($modDetail->validate()) {
                                    if ($modDetail->save()) {
                                        $success_1 = true;
                                        //echo "<br>0";
                                    } else {
                                        $success_1 = false;
                                        //echo "<br>1";
                                    }
                                }
                            }                            
                        } 
                        // jika yang akan disimpan adalah model ukuran loglist 4 meter
                        else if ($model->model_ukuran_loglist == 1 || $model->model_ukuran_loglist == '4 Diameter') {
                            foreach($_POST['TLoglistDetail'] as $i => $detail){
                                $modDetail = new \app\models\TLoglistDetail();
                                $modDetail->attributes = $detail;
                                $modDetail->loglist_id = $model->loglist_id;
                                $modDetail->lampiran = $lampiran_terakhir * 1;
                                $modDetail->diameter_ujung = 0;
                                $modDetail->diameter_pangkal = 0;
                                if ($modDetail->validate()) {
                                    if ($modDetail->save()) {
                                        $success_1 = true;
                                    } else {
                                        $success_1 = false;
                                        //echo "<br>2";
                                    }
                                }
                            }                        
                        } else {
                            $success_1 = false;
                            //echo "<br>3";
                        }
                    }else{
                        $success_1 = false;
                        //echo "<br>4";
                    }
                    // ^ ndisik wis tau ngene
                    //echo "<pre>";
                    //print_r($_POST);
                    //echo "success_1 = ".$modDetail->validate();
                    //echo "<br>";
                    //print_r($_POST);
                    //exit();
                    if ($success_1) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Data Loglist Berhasil disimpan'));
                        return $this->redirect(['index','success'=>2,'loglist_id'=>$model->loglist_id]);
                    } else {
                        $transaction->rollback();
                        Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', $ex);
                }
            } 
        }
        empty($lampiran) ? $lampiran = 1 : $lampiran = $lampiran;
		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail,'modDkg'=>$modDkg,'lampiran'=>$lampiran]);
	}
	
	public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
			$loglist_id = Yii::$app->request->post('loglist_id');
			$add = Yii::$app->request->post('loglist_id');
            $ukuran = Yii::$app->request->post('ukuran');
            $area_pembelian = Yii::$app->request->post('area_pembelian');
            $lampiran = Yii::$app->request->post('lampiran');
            $tambah_lampiran = Yii::$app->request->post('tambah_lampiran');
            $data['html'] = "";
            $modDetail = new \app\models\TLoglistDetail();
			$last_tr = [];
            //parse_str(\Yii::$app->request->post('last_tr'),$last_tr);
			if(!empty($last_tr)){
                if(!empty($last_tr['diameter_ujung'])){
                    foreach($last_tr['TLoglistDetail'] as $qwe){
                        $last_tr = $qwe;
                    }
                    $modDetail->attributes = $last_tr;
                    $modDetail->nomor_grd = "";
                    $modDetail->nomor_produksi = "";
                    $modDetail->nomor_batang = "";
                    $modDetail->diameter_rata = ($last_tr['diameter_ujung']+$last_tr['diameter_pangkal'])/2;
                }
			}
			$modDetail->loglist_id = $loglist_id;

            // 2021-03-20 cek t_loglist_detail dulu cuy
            $sql_detail = "select distinct(lampiran) from t_loglist_detail where loglist_id = ".$loglist_id." order by lampiran desc limit 1";
            $lampiran_terakhir = Yii::$app->db->createCommand($sql_detail)->queryScalar();
            if (empty($lampiran_terakhir) || $lampiran_terakhir == null || $lampiran_terakhir < 1 ) {
                $and_lampiran = " ";
            } else { 
                /*if ($tambah_lampiran == 1) {
                    $lampiran = $lampiran_terakhir + 1;
                    $and_lampiran = "and lampiran = ".$lampiran;
                } else {
                    $and_lampiran = "and lampiran = ".$lampiran_terakhir;
                }*/
                $and_lampiran = "and lampiran = ".$lampiran;
            }
            $baris_halaman = 40;
            if ($tambah_lampiran == 1) {
                $lampiran = $lampiran_terakhir + 1;
                $total = 0;
                $jumlah_baris = $baris_halaman - $total;
            } else {
                // 2021-03-17 cek lampiran dulu cuy
                $sql_lampiran = "select lampiran from t_loglist_detail where loglist_id = ".$loglist_id." ".$and_lampiran." order by lampiran limit 1";
                $lampiran_lama = Yii::$app->db->createCommand($sql_lampiran)->queryScalar();
                $lampiran = $lampiran_lama;

                // 2021-03-17 cek nomor urut dulu cuy
                $sql_total = "select count(*) from t_loglist_detail where loglist_id = ".$loglist_id." ".$and_lampiran." ";
                $total = Yii::$app->db->createCommand($sql_total)->queryScalar();
                $jumlah_baris = $baris_halaman - $total;
                
            }
            
            if($ukuran=="2 Diameter"){
                // 2021-03-17 inputan loglist_detail langsung buka 40 baris cuy
                $sql_total = $lampiran;
                for ($i=$total+1; $i<=$baris_halaman; $i++) {
                    $data['html'] .= $this->renderPartial('_item',['i'=>$i,'jumlah_baris'=>$jumlah_baris,'lampiran'=>$lampiran,'modDetail'=>$modDetail,'last_tr'=>$last_tr,'edit'=>'0']);
                }
            }else{
                // 2021-03-17 inputan loglist_detail langsung buka 40 baris cuy
                for ($i=$total+1; $i<=$baris_halaman; $i++) {
                    $data['html'] .= $this->renderPartial('_item4D',['i'=>$i,'jumlah_baris'=>$jumlah_baris,'lampiran'=>$lampiran,'modDetail'=>$modDetail,'last_tr'=>$last_tr,'edit'=>'0']);
                }
            }

            return $this->asJson($data);
        }
    }
	
	public function actionSaveitem(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $datas = explode(":::",Yii::$app->request->post('formData'));

            foreach ($datas as $data) {
                $form_params = []; 
                //parse_str(\Yii::$app->request->post('formData'),$form_params);
                parse_str($data,$form_params);

                if( isset($form_params['TLoglistDetail']) ){
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $success_1 = false; // t_loglist_detail
                        $post = $form_params['TLoglistDetail'];
                        if(count($post)>0){
                            foreach($post as $peng) { 
                                $post = $peng; 
                            }
                            $mod = new \app\models\TLoglistDetail();
                            if(!empty($post['loglist_detail_id'])){
                                $mod = \app\models\TLoglistDetail::findOne($post['loglist_detail_id']);
                            }
                            $mod->attributes = $post;
                            $mod->diameter_ujung = (isset($post['diameter_ujung'])?$post['diameter_ujung']: ($post['diameter_ujung1']+$post['diameter_ujung2'])/2 );
                            $mod->diameter_pangkal = (isset($post['diameter_pangkal'])?$post['diameter_pangkal']: ($post['diameter_pangkal1']+$post['diameter_pangkal2'])/2 );
                            if($mod->validate()){
                                if($mod->save()){
                                    $success_1 = true;
                                }
                            }else{
                                $success_1 = false;
                                $data['message_validate']=\yii\widgets\ActiveForm::validate($mod); 
                            }
                        }

                        if ($success_1) {
                            $transaction->commit();
                            $data = $mod->attributes;
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                            (isset($data['message_validate']) ? $data['message'] = 'a' : 'b');
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
            }
			
        }
    }
	
	public function actionGetItems(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
            $lampiran = Yii::$app->request->post('lampiran');
            empty($lampiran) ? $lampiran = 1 : $lampiran = $lampiran;
            $edit = Yii::$app->request->post('edit');
            //$area_pembelian = Yii::$app->request->post('area_pembelian');
            $data = [];
            $data['html'] = '';
			$disabled = false;
            if(!empty($loglist_id)){
                if (!empty($loglist_id)) { 
                    $model = \app\models\TLoglist::findOne(['loglist_id'=>$loglist_id]);
                    $ukuran = $model->model_ukuran_loglist;
                }
                $modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$loglist_id, 'lampiran'=>$lampiran])->orderBy(['created_at'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    $i = 1;
                    foreach($modDetail as $x => $detail){
                        $lampiran = $detail->lampiran;
                        if ($ukuran == "2 Diameter") {
                            $data['html'] .= $this->renderPartial('_item',['i'=>$i,'lampiran'=>$lampiran,'modDetail'=>$detail,'edit'=>$edit]);
                        } else if ($ukuran == "4 Diameter") {
                            $data['html'] .= $this->renderPartial('_item4D',['i'=>$i,'lampiran'=>$lampiran,'modDetail'=>$detail,'edit'=>$edit]);
                        } else {

                        }
                        $i++;
                    }
                }
            }
            return $this->asJson($data);
        }
    }

	public function actionLihatLampiran(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
            $lampiran = Yii::$app->request->post('lampiran');
            $edit = 0;
            $model = \app\models\TLoglist::findOne(['loglist_id'=>$loglist_id]);
            $ukuran = $model->model_ukuran_loglist;
            if ($ukuran == 0 || $ukuran == '2 Diameter') {
                $ukuran = '2 Diameter';
            } else if ($ukuran == 1 || $ukuran == '4 Diameter') {
                $ukuran = '4 Diameter';
            } else {
                $ukuran = 99;
            }
            $area_pembelian = $model->area_pembelian;
            $data = [];
            $data['html'] = '';
			$disabled = false;
            if(!empty($loglist_id)){
                $modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$loglist_id,'lampiran'=>$lampiran])->orderBy(['created_at'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    $i = 1;
                    foreach($modDetail as $x => $detail){
                        $lampiran = $detail->lampiran;
                        if($ukuran=="2 Diameter"){
                            $data['html'] .= $this->renderPartial('_item',['xxx'=>$ukuran,'i'=>$i,'area_pembelian'=>$area_pembelian,'lampiran'=>$lampiran,'modDetail'=>$detail,'edit'=>$edit]);
                        }else{
                            $data['html'] .= $this->renderPartial('_item4D',['xxx'=>$ukuran,'i'=>$i,'area_pembelian'=>$area_pembelian,'lampiran'=>$lampiran,'modDetail'=>$detail,'edit'=>$edit]);
                        }
                        $i++;
                    }
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function actionLihatRekap(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
            $lampiran = Yii::$app->request->post('lampiran');
            $edit = 0;
            $model = \app\models\TLoglist::findOne(['loglist_id'=>$loglist_id]);
            $data = [];
            $data['html'] = '';
            if(!empty($loglist_id)){
                /*$modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$loglist_id,'lampiran'=>$lampiran])->orderBy(['created_at'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    $i = 1;
                    foreach($modDetail as $x => $detail){
                        $lampiran = $detail->lampiran;
                        $data['html'] .= $this->renderPartial('_rekap',['i'=>$i,'model'=>$model,'modDetail'=>$modDetail]);
                        $i++;
                    }
                }*/
                $sql_jenis_kayu = "select distinct(a.kayu_id), b.group_kayu, b.kayu_nama ".
                                    "   from t_loglist_detail a ". 
                                    "   join m_kayu b on b.kayu_id = a.kayu_id ".
                                    "   where loglist_id = ".$loglist_id." ".
                                    "   and lampiran = ".$lampiran."".
                                    "   ";
                $query_jenis_kayu = Yii::$app->db->createCommand($sql_jenis_kayu)->queryAll();
                $i = 1;
                $tot_batang_2529 = 0; $tot_volume_2529 = 0;
                $tot_batang_3039 = 0; $tot_volume_3039 = 0;
                $tot_batang_4049 = 0; $tot_volume_4049 = 0;
                $tot_batang_5059 = 0; $tot_volume_5059 = 0;
                $tot_batang_6069 = 0; $tot_volume_6069 = 0;
                $tot_batang_70up = 0; $tot_volume_70up = 0;
                foreach ($query_jenis_kayu as $kolom) {
                    $sql_batang_2529 = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '25-29' and lampiran = ".$lampiran." ".
                                        "   ";
                    $batang_2529 = Yii::$app->db->createCommand($sql_batang_2529)->queryScalar();
                    $batang_2529 == 0 ? $batang_2529 = '-' : $batang_2529 = $batang_2529;
                    $sql_volume_2529 = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '25-29' and lampiran = ".$lampiran."  ".
                                        "   ";
                    $volume_2529 = Yii::$app->db->createCommand($sql_volume_2529)->queryScalar();
                    $volume_2529 == 0 ? $volume_2529 = '-' : $volume_2529 = $volume_2529;
                    //=============================================================================
                    $sql_batang_3039 = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '30-39' and lampiran = ".$lampiran."  ".
                                        "   ";
                    $batang_3039 = Yii::$app->db->createCommand($sql_batang_3039)->queryScalar();
                    $batang_3039 == 0 ? $batang_3039 = '-' : $batang_3039 = $batang_3039;
                    $sql_volume_3039 = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '30-39' and lampiran = ".$lampiran."  ".
                                        "   ";
                    $volume_3039 = Yii::$app->db->createCommand($sql_volume_3039)->queryScalar();
                    $volume_3039 == 0 ? $volume_3039 = '-' : $volume_3039 = $volume_3039;
                    //=============================================================================
                    $sql_batang_4049 = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '40-49' and lampiran = ".$lampiran."  ".
                                        "   ";
                    $batang_4049 = Yii::$app->db->createCommand($sql_batang_4049)->queryScalar();
                    $batang_4049 == 0 ? $batang_4049 = '-' : $batang_4049 = $batang_4049;
                    $sql_volume_4049 = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '40-49' and lampiran = ".$lampiran."  ".
                                        "   ";
                    $volume_4049 = Yii::$app->db->createCommand($sql_volume_4049)->queryScalar();
                    $volume_4049 == 0 ? $volume_4049 = '-' : $volume_4049 = $volume_4049;
                    //=============================================================================
                    $sql_batang_5059 = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '50-59' and lampiran = ".$lampiran."  ".
                                        "   ";
                    $batang_5059 = Yii::$app->db->createCommand($sql_batang_5059)->queryScalar();
                    $batang_5059 == 0 ? $batang_5059 = '-' : $batang_5059 = $batang_5059;
                    $sql_volume_5059 = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '50-59' and lampiran = ".$lampiran."  ".
                                        "   ";
                    $volume_5059 = Yii::$app->db->createCommand($sql_volume_5059)->queryScalar();
                    $volume_5059 == 0 ? $volume_5059 = '-' : $volume_5059 = $volume_5059;
                    //=============================================================================
                    $sql_batang_6069 = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '60-69' and lampiran = ".$lampiran."  ".
                                        "   ";
                    $batang_6069 = Yii::$app->db->createCommand($sql_batang_6069)->queryScalar();
                    $batang_6069 == 0 ? $batang_6069 = '-' : $batang_6069 = $batang_6069;
                    $sql_volume_6069 = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '60-69' and lampiran = ".$lampiran."  ".
                                        "   ";
                    $volume_6069 = Yii::$app->db->createCommand($sql_volume_6069)->queryScalar();
                    $volume_6069 == 0 ? $volume_6069 = '-' : $volume_6069 = $volume_6069;
                    //=============================================================================
                    $sql_batang_70up = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '70-up' and lampiran = ".$lampiran."  ".
                                        "   ";
                    $batang_70up = Yii::$app->db->createCommand($sql_batang_70up)->queryScalar();
                    $batang_70up == 0 ? $batang_70up = '-' : $batang_70up = $batang_70up;
                    $sql_volume_70up = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '70-up' and lampiran = ".$lampiran."  ".
                                        "   ";
                    $volume_70up = Yii::$app->db->createCommand($sql_volume_70up)->queryScalar();
                    $volume_70up == 0 ? $volume_70up = '-' : $volume_70up = $volume_70up;
                    //=============================================================================
                    
                    $data['html'] .= $this->renderPartial('_rekap',['sql_volume_6069' => $sql_volume_6069, 'i'=>$i,'model'=>$model,'kolom'=>$kolom,
                                                                'batang_2529'=>$batang_2529,'volume_2529'=>$volume_2529,
                                                                'batang_3039'=>$batang_3039,'volume_3039'=>$volume_3039,
                                                                'batang_4049'=>$batang_4049,'volume_4049'=>$volume_4049,
                                                                'batang_5059'=>$batang_5059,'volume_5059'=>$volume_5059,
                                                                'batang_6069'=>$batang_6069,'volume_6069'=>$volume_6069,
                                                                'batang_70up'=>$batang_70up,'volume_70up'=>$volume_70up
                                                                ]);
                    $i++;
                    $tot_batang_2529 += $batang_2529; $tot_volume_2529 += $volume_2529;
                    $tot_batang_3039 += $batang_3039; $tot_volume_3039 += $volume_3039;
                    $tot_batang_4049 += $batang_4049; $tot_volume_4049 += $volume_4049;
                    $tot_batang_5059 += $batang_5059; $tot_volume_5059 += $volume_5059;
                    $tot_batang_6069 += $batang_6069; $tot_volume_6069 += $volume_6069;
                    $tot_batang_70up += $batang_70up; $tot_volume_70up += $volume_70up;
                }
                $data['html'] .= "<tr>";
                $data['html'] .= "<td colspan='2' class='td=leco; text-right'><b>TOTAL</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_2529,0)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_2529,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_3039,0)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_3039,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_4049,0)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_4049,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_5059,0)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_5059,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_6069,0)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_6069,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_70up,0)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_70up,2)."</b></td>";
                $data['html'] .= "</tr>";
            }
            return $this->asJson($data);
        }
    }  
	
    public function actionTambahLampiran(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
            $sql_lampiran_baru = "select lampiran from t_loglist_detail where loglist_id = ".$loglist_id." order by lampiran desc limit 1";
            $lampiran_baru = Yii::$app->db->createCommand($sql_lampiran_baru)->queryScalar() + 1;
            $lampiran = Yii::$app->request->post('lampiran');
            $tambah_lampiran = Yii::$app->request->post('tambah_lampiran');
            $edit = 0;
            $model = \app\models\TLoglist::findOne(['loglist_id'=>$loglist_id]);
            $ukuran = $model->model_ukuran_loglist;
            $area_pembelian = $model->area_pembelian;
            $data = [];
            $data['html'] = '';
            $data['lampiran_baru'] = '';
			$disabled = false;
            if(!empty($loglist_id)){
                $data['lampiran_baru'] = $lampiran_baru;
                $modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$loglist_id,'lampiran'=>$lampiran])->orderBy(['created_at'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    $i = 1;
                    foreach($modDetail as $x => $detail){
                        if($ukuran=="2 Diameter"){
                            $data['html'] .= $this->renderPartial('_item',['i'=>$i,'lampiran'=>$lampiran,'lampiran_baru'=>$lampiran_baru,'tambah_lampiran'=>$tambah_lampiran,'modDetail'=>$detail,'edit'=>$edit]);
                        }else{
                            $data['html'] .= $this->renderPartial('_item4D',['i'=>$i,'lampiran'=>$lampiran,'lampiran_baru'=>$lampiran_baru,'tambah_lampiran'=>$tambah_lampiran,'modDetail'=>$detail,'edit'=>$edit]);
                        }
                        $i++;
                    }
                }
            }
            return $this->asJson($data);
        }
    }

	public function actionDeleteItem($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TLoglistDetail::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					
					if ($success_1) {
						$transaction->commit();
                        $data['html'] = '';
						$data['status'] = true;
						//$data['callback'] = 'getItems()';
						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                        $data['html'] = $this->renderPartial('_item',['i'=>10,'lampiran'=>1,'lampiran_baru'=>0,'tambah_lampiran'=>1,'modDetail'=>$model,'edit'=>0]);
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'actionname'=>'deleteItem']);
		}
	}
	
	function actionSetDropdownGrader(){
		if(\Yii::$app->request->isAjax){
			$selected_items = Yii::$app->request->post('selected_items');
            if(!empty($selected_items)){
                $selected_items = implode(', ', $selected_items);
            }
			$query = "
                SELECT * FROM m_graderlog
                WHERE m_graderlog.active IS TRUE
                    ".(($selected_items!='')?'AND graderlog_id NOT IN ('.$selected_items.')':'')." 
                ORDER BY m_graderlog.graderlog_nm ASC
            ";
            $mod = Yii::$app->db->createCommand($query)->queryAll();
			$arraymap = \yii\helpers\ArrayHelper::map($mod, 'graderlog_id', 'graderlog_nm');
			$html = \yii\bootstrap\Html::tag('option');
			foreach($arraymap as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
			}
			$data['html'] = $html;
			return $this->asJson($data);
		}
	}
	
	public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TLoglist::tableName();
				$param['pk']= \app\models\TLoglist::primaryKey()[0];
				$param['column'] = ['loglist_id','loglist_kode','t_pengajuan_pembelianlog.kode as kode_keputusan','t_log_kontrak.kode' ,'t_log_kontrak.nomor',['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],'tongkang',$param['table'].'.lokasi_muat'];
				$param['join'] = ['JOIN t_log_kontrak ON t_log_kontrak.log_kontrak_id = '.$param['table'].'.log_kontrak_id
								   JOIN t_pengajuan_pembelianlog ON t_pengajuan_pembelianlog.pengajuan_pembelianlog_id = '.$param['table'].'.pengajuan_pembelianlog_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
	
	public function actionRepeaterSetDropdown(){
        if(\Yii::$app->request->isAjax){
			$list = Yii::$app->request->post('list');
            $html = '';
            $data['habis'] = false;
            if(!empty($list)){
                $params = "";
                foreach($list as $i => $val){
                    $params .= "'".$val."'";
                    if(count($list)>$i+1){
                        $params .= ",";
                    }
                }
                $mod = [];
				$mod = \app\models\TDkg::find()
						->andWhere("status = '".\app\models\TDkg::AKTIF_DINAS."'")
						->andWhere('dkg_id NOT IN ('.$params.')')
						->orderBy(['created_at'=>SORT_DESC])->all();
				$arraymap = [];
				if(count($mod)){
					foreach($mod as $i => $dkg){
						$arraymap[$dkg->dkg_id] = $dkg->graderlog->graderlog_nm;
					}
				}
                foreach($arraymap as $i => $val){
                    $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
                }
            }
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	
	public function actionSetKeputusan($pengajuan_pembelianlog_id){
		if(\Yii::$app->request->isAjax){
			$data = [];
			if(!empty($pengajuan_pembelianlog_id)){
				$model = \app\models\TPengajuanPembelianlog::findOne($pengajuan_pembelianlog_id);
				$modKontrak = \app\models\TLogKontrak::findOne($model->log_kontrak_id);
				$modKontrak->tanggal_po = \app\components\DeltaFormatter::formatDateTimeForUser2($modKontrak->tanggal_po);
				if(!empty($model)){
					$data['model'] = $model->attributes;
				}
				if(!empty($modKontrak)){
					$data['modKontrak'] = $modKontrak->attributes;
				}
			}
			return $this->asJson($data);
		}
	}
    
	public function actionUpdateModelLoglist(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = \Yii::$app->request->post("loglist_id");
            $modeluk = \Yii::$app->request->post("modeluk");
            $modelarea = \Yii::$app->request->post("modelarea");
			$data = false;
			if(!empty($loglist_id)){
                $model = \app\models\TLoglist::findOne($loglist_id);
                if(!empty($model)){
                    $model->model_ukuran_loglist = $modeluk;
                    $model->area_pembelian = $modelarea;
                    $data = $model->save();
                }
			}
			return $this->asJson($data);
		}
	}
}
