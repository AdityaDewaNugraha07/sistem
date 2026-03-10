<?php

namespace app\modules\marketing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PricelistController extends DeltaBaseController
{
    
    public $defaultAction = 'index';
    
    public function actionIndex(){
        $model = new \app\models\MBrgProduk();
        $modHarga = new \app\models\MHargaProduk();
        
        $xxx = \app\models\MHargaProduk::find()->where(['kode' => $modHarga->kode])->orderBy(['harga_id' => SORT_DESC])->one();
        
        if(isset($_GET['jp']) && isset($_GET['tp'])){
            $model->produk_group = $_GET['jp'];
        } else {
            $model->produk_group = 'Platform';
        }
        return $this->render('index',['model'=>$model,'modHarga'=>$modHarga, 'xxx'=>$xxx]);
    }
    
    public function actionXxx(){
        $jp = Yii::$app->request->get('jp');
        $tp = Yii::$app->request->get('tp');
        $kode = Yii::$app->request->get('kode');
        return $this->renderAjax('_xxx',['jp'=>$jp, 'tp'=>$tp, 'kode'=>$kode]);
    }

    // 2020-07-17 tambah status approval di atas halaman
    public function actionZzz(){
        $jp = Yii::$app->request->get('jp');
        $tp = Yii::$app->request->get('tp');
        $kode = Yii::$app->request->get('kode');

        $t_approval = \app\models\TApproval::find()->where(['reff_no'=>$kode])->all();
        return $this->renderAjax('_zzz',['jp'=>$jp, 'tp'=>$tp, 'kode'=>$kode, 't_approval'=>$t_approval]);
    }

    public function actionCreate(){
        $model = new \app\models\MBrgProduk();
        $modHarga = new \app\models\MHargaProduk();

        if(isset($_GET['jp'])){
            $model->produk_group = $_GET['jp'];
            $jp = $model->produk_group;
        } else {
            $model->produk_group = 'Platform';
            $jp = $model->produk_group;
        }

        $sql_cek = "select b.produk_group, b.produk_id, b.produk_nama, a.harga_enduser, a.status_approval ".
                        "   from m_harga_produk a ".
                        "   left join m_brg_produk b on b.produk_id = a.produk_id ".
                        "   where b.produk_group = '".$jp."' ".
                        "   and a.status_approval = 'Not Confirmed' ".
                        "   ";
        $query_cek = Yii::$app->db->createCommand($sql_cek)->queryAll();
        $count_cek = count($query_cek);
        
        if ($count_cek > 0) {
            return $this->render('index',['model'=>$model,'modHarga'=>$modHarga]);
        }

        if( Yii::$app->request->post('MHargaProduk')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = true;
                $success_2 = true;
                $success_3 = true;

                // detailpost = Array ( [produk_id] => 2576 [harga_hpp] => 0 [harga_distributor] => 0 [harga_agent] => 0 [harga_enduser] => 10 ) 
                $harga_produk_id = '';
                $harga_id = '';
                $harga_hpp = '';
                $harga_distributor = '';
                $harga_agent = '';
                $harga_enduser = '';
                $cek_harga_enduser = 0;

                $model->load(\Yii::$app->request->post());
                $kode = trim(\app\components\DeltaGenerator::kodeHargaProduk());

                foreach ($_POST['MHargaProduk'] as $i => $detailpost) {
                    
                    $modHarga = new \app\models\MHargaProduk();
                    $modHarga->attributes = $detailpost;
                    $modHarga->harga_tanggal_penetapan = isset($_POST['tanggalx']) ? \app\components\DeltaFormatter::formatDateTimeForDb($_POST['tanggalx']) : '';
                    $modHarga->kode = $kode;
                    $modHarga->status_approval = 'Not Confirmed';
                    $modHarga->active = false;
                    $modHarga->harga_enduser = isset($detailpost['harga_enduser']) ? \app\components\DeltaFormatter::formatNumberForDb2($detailpost['harga_enduser']) : '';

                    if ($modHarga->validate() && $detailpost['harga_enduser'] > 0){
                        if ($modHarga->save()){
                            $success_1 &= true;
                        } else {
                            $success_1 &= false;
                        }
                    } else {
                        $data['message']=\yii\widgets\ActiveForm::validate($modHarga); 
                    }
                    
                    $cek_harga_enduser += $detailpost['harga_enduser'];
                }

                // cek dulu kalau total harga enduser kosong jangan disimpan
                if ($cek_harga_enduser > 0) {
                    $success_2 = true;
                } else {
                    $success_2 = false;
                    $data['message'] = 'Data kosong';
                }

                // batal
                //approval 1 : gm marketing (inge tjandra 122)
                //approval 2 : kadiv akt (nowo eko yulianto 58)
                //approval 3 : dirut (heryanto suwardi 22)
                //approval 4 : dir (agus soewito 59)
                
                // revisi 2020-05-20
                //approval 1 : kadiv marketing (iwan s 19)
                //approval 2 : gm marketing (inge tjandra 122)
                //approval 3 : dirut (heryanto suwardi 22)
                //approval 4 : dir (agus soewito 59)

                $approval_array = array(1=>19, 2=>122, 3=>22, 4=>59);
                foreach ($approval_array as $level => $approver) {
                    $model_t_approval = new \app\models\TApproval();
                    $model_t_approval->assigned_to = $approver;
                    $model_t_approval->reff_no = $modHarga->kode;
                    $model_t_approval->tanggal_berkas = date('Y-m-d H:i:s');
                    $model_t_approval->level = $level;
                    $model_t_approval->status = "Not Confirmed";
                    $model_t_approval->active = false;
                    $model_t_approval->created_at = date('Y-m-d H:i:s');
                    $model_t_approval->created_by = Yii::$app->user->identity->user_id;
                    $model_t_approval->save();
                }

                if ($model_t_approval->save()) {
                    $success_3 &= true;
                } else {
                    $success_3 &= false;
                }

                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    $data['status'] = true;
                    $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                    return $this->redirect(['index','success'=>1,'jp'=>$model->produk_group,'tp'=>$modHarga->harga_tanggal_penetapan]);
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

        }
        return $this->render('create',['model'=>$model,'modHarga'=>$modHarga]);
    }

    public function actionEdit(){
        $model = new \app\models\MBrgProduk();
        $modHarga = new \app\models\MHargaProduk();

        $jp = Yii::$app->request->get('jp');
        $tp = Yii::$app->request->get('tp');
        $kode = Yii::$app->request->get('kode');
        $modHarga = new \app\models\MHargaProduk();

        /*$sql = "select m_brg_produk.produk_id, m_brg_produk.produk_kode, m_brg_produk.produk_nama, m_brg_produk.produk_group, m_brg_produk.produk_dimensi  ". 
                    "   , m_harga_produk.harga_enduser ".
                    "   from m_harga_produk ".
                    "   join m_brg_produk on m_brg_produk.produk_id = m_harga_produk.produk_id ".
                    "   where m_brg_produk.produk_group = '{$jp}' ".
                    "   and m_harga_produk.harga_tanggal_penetapan = '{$tp}' ".
                    "   and m_harga_produk.kode = '{$kode}' ".
                    "   order by m_brg_produk.produk_id ".
                    "   ";*/
        $sql = "select * from m_brg_produk where produk_group = '".$jp."' and active = 'true' order by produk_id ";
        $modele = Yii::$app->db->createCommand($sql)->queryAll();
        
        if( Yii::$app->request->post('MHargaProduk')){

            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $success_1 = true;
                $success_2 = true;
                $success_3 = true;

                // detailpost = Array ( [produk_id] => 2576 [harga_hpp] => 0 [harga_distributor] => 0 [harga_agent] => 0 [harga_enduser] => 10 ) 
                $harga_produk_id = '';
                $harga_id = '';
                $harga_hpp = '';
                $harga_distributor = '';
                $harga_agent = '';
                $harga_enduser = '';
                $cek_harga_enduser = 0;

                $model->load(\Yii::$app->request->post());
                $kode = trim(\app\components\DeltaGenerator::kodeHargaProduk());
                $kode_lama = Yii::$app->request->post('kode_lama');

                foreach ($_POST['MHargaProduk'] as $i => $detailpost) {
                    
                    $modHarga = new \app\models\MHargaProduk();
                    $modHarga->attributes = $detailpost;
                    $modHarga->harga_tanggal_penetapan = isset($_POST['tanggalx']) ? \app\components\DeltaFormatter::formatDateTimeForDb($_POST['tanggalx']) : '';
                    $modHarga->kode = $kode;
                    $modHarga->status_approval = 'Not Confirmed';
                    $modHarga->active = false;
                    $modHarga->harga_enduser = isset($detailpost['harga_enduser']) ? \app\components\DeltaFormatter::formatNumberForDb2($detailpost['harga_enduser']) : '';

                    if ($modHarga->validate() && $detailpost['harga_enduser'] > 0){
                        if ($modHarga->save()){
                            $success_1 &= true;
                        } else {
                            $success_1 &= false;
                        }
                    } else {
                        $data['message']=\yii\widgets\ActiveForm::validate($modHarga); 
                    }
                    
                    $cek_harga_enduser += $detailpost['harga_enduser'];
                }

                // cek dulu kalau total harga enduser kosong jangan disimpan
                if ($cek_harga_enduser > 0) {
                    $success_2 = true;
                } else {
                    $success_2 = false;
                    $data['message'] = 'Data kosong';
                }

                // batal
                //approval 1 : gm marketing (inge tjandra 122)
                //approval 2 : kadiv akt (nowo eko yulianto 58)
                //approval 3 : dirut (heryanto suwardi 22)
                //approval 4 : dir (agus soewito 59)
                
                // revisi 2020-05-20
                //approval 1 : kadiv marketing (iwan s 19)
                //approval 2 : gm marketing (inge tjandra 122)
                //approval 3 : dirut (heryanto suwardi 22)
                //approval 4 : dir (agus soewito 59)

                $approval_array = array(1=>19, 2=>122, 3=>22, 4=>59);
                foreach ($approval_array as $level => $approver) {
                    $model_t_approval = new \app\models\TApproval();
                    $model_t_approval->assigned_to = $approver;
                    $model_t_approval->reff_no = $modHarga->kode;
                    $model_t_approval->tanggal_berkas = date('Y-m-d H:i:s');
                    $model_t_approval->level = $level;
                    $model_t_approval->status = "Not Confirmed";
                    $model_t_approval->active = false;
                    $model_t_approval->created_at = date('Y-m-d H:i:s');
                    $model_t_approval->created_by = Yii::$app->user->identity->user_id;
                    $model_t_approval->save();
                }

                if ($model_t_approval->save()) {
                    $success_3 &= true;
                } else {
                    $success_3 &= false;
                }
                echo "<br>".$success_1." ".$success_2." ".$success_3;
                if ($success_1 && $success_2 && $success_3) {
                    //delete m_harga_barang
                    Yii::$app->db->createCommand()->delete('m_harga_produk', ['kode' => $kode_lama])->execute();
                    
                    //delete t_approval
                    Yii::$app->db->createCommand()->delete('t_approval', ['reff_no' => $kode_lama])->execute();
                    
                    $transaction->commit();
                    $data['status'] = true;
                    $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                    return $this->redirect(['index','success'=>1,'jp'=>$model->produk_group,'tp'=>$modHarga->harga_tanggal_penetapan]);
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

            exit();

        }

        return $this->render('edit',['model'=>$model,'modele'=>$modele,'modHarga'=>$modHarga, 'jp'=>$jp, 'tp'=>$tp, 'kode'=>$kode]);
    }

    public function actionGetContent(){
        if(\Yii::$app->request->isAjax){
            $modHarga = new \app\models\MHargaProduk();
            $jenis_produk = Yii::$app->request->get('jp');
            $kode = Yii::$app->request->get('kode');

            //$selectgroup = "m_brg_produk.produk_id, m_brg_produk.produk_kode, m_brg_produk.produk_nama";
            $sql = " select a.produk_id, a.produk_kode, a.produk_nama".
                        "   from m_brg_produk a ".
                        "   left join m_harga_produk b on b.produk_id = a.produk_id ".
                        "   where a.produk_group = '".$jenis_produk."'  ".
                        //"   and a.active = TRUE ".
                        "   and b.kode = '".$kode."' ".
                        "   group by a.produk_id, a.produk_kode, a.produk_nama ".
                        "   order by a.produk_id asc ".
                        "   ";

            $models = Yii::$app->db->createCommand($sql)->queryAll();

            $sql_cek = "select a.produk_id, a.status_approval ".
                            "   from m_harga_produk a ".
                            "   left join m_brg_produk b on b.produk_id = a.produk_id ".
                            "   where b.produk_group = '".$jenis_produk."' ".
                            "   and a.kode = '".$kode."' ".
                            //"   and b.active = TRUE ".
                            "   ";
            $query_cek = Yii::$app->db->createCommand($sql_cek)->queryAll();

            if(count($models)>0){
                return $this->renderAjax('_content',['modHarga'=>$modHarga,'jenis_produk'=>$jenis_produk,'kode'=>$kode,'sql'=>$sql,'models'=>$models,'query_cek'=>$query_cek]);
            } else {
                return $this->renderAjax('_content',[]);
            }
        }
    }


    public function actionGetContentEdit(){
        if(\Yii::$app->request->isAjax){
            $jp = Yii::$app->request->get('jp');
            $tp = Yii::$app->request->get('tp');
            $kode = Yii::$app->request->get('kode');
            $modHarga = new \app\models\MHargaProduk();
            //$modBrgProduks = \app\models\MBrgProduk::find()->where(['active'=>true])->orderBy("produk_nama ASC")->all();
			//if(count($modBrgProduks)>0){
			//	foreach($modBrgProduks as $i => $modBrgProduk){
                    $sql = "select m_brg_produk.produk_id, m_brg_produk.produk_kode, m_brg_produk.produk_nama, m_brg_produk.produk_group, m_brg_produk.produk_dimensi  ". 
                                "   , m_harga_produk.harga_enduser ".
                                "   from m_harga_produk ".
                                "   left join m_brg_produk on m_brg_produk.produk_id = m_harga_produk.produk_id ".
                                "   where m_brg_produk.produk_group = '{$jp}' ".
                                "   and m_harga_produk.harga_tanggal_penetapan = '{$tp}' ".
                                "   and b.kode = '{$kode}' ".
                                "   ";
                    $models = Yii::$app->db->createCommand($sql)->queryAll();
			//	}
            //}
			return $this->renderAjax('_contentEdit',['models'=>$models,'modHarga'=>$modHarga,]);
		}
    }

    public function actionSetTglDropdown(){
        if(\Yii::$app->request->isAjax){
            $produk_group = Yii::$app->request->post('produk_group');
            $mod = [];

            /*select distinct(harga_tanggal_penetapan) as harga_tanggal_penetapan, status_approval
            from m_harga_produk a
            join m_brg_produk b ON b.produk_id = a.produk_id 
            where b.produk_group = 'Platform'
            group by status_approval, harga_tanggal_penetapan
            order by harga_tanggal_penetapan DESC*/

            $sql = "select a.kode, a.harga_tanggal_penetapan
                        from m_harga_produk a
                        join m_brg_produk b ON b.produk_id = a.produk_id 
                        where b.produk_group = '".$produk_group."'
                        group by a.kode, a.harga_tanggal_penetapan
                        order by a.harga_tanggal_penetapan desc 
                        limit 3 ".
                    " ";

            $mod = \Yii::$app->db->createCommand($sql)->queryAll();
            
            $arraymap = \yii\helpers\ArrayHelper::map($mod, 'kode', 'kode');
            $html = "";

            foreach($arraymap as $i => $kode){
                $sql_harga_tanggal_penetapan = "select harga_tanggal_penetapan from m_harga_produk where kode = '".$kode."' order by harga_id desc limit 1";
                $harga_tanggal_penetapan = Yii::$app->db->createCommand($sql_harga_tanggal_penetapan)->queryScalar();

                $sql_status_approval = "select status_approval from m_harga_produk where kode = '".$kode."' order by harga_id desc limit 1";
                $status_approval = Yii::$app->db->createCommand($sql_status_approval)->queryScalar();

                $sql_harga_enduser = "select sum(harga_enduser) from m_harga_produk where kode = '".$kode."'";
                $total_harga_enduser = Yii::$app->db->createCommand($sql_harga_enduser)->queryScalar(); 
                $total_harga_enduser = \app\components\DeltaFormatter::formatNumberForAllUser($total_harga_enduser);

                if ($status_approval == "APPROVED") {
                    $style = "background-color: #fff; color: #5cb85c;";
                } else if ($status_approval == "REJECTED") {
                    $style = "background-color: #fff; color: #d9534f;";
                } else {
                    $style = "background-color: #fff; color: #000;";
                }
                $html .= \yii\bootstrap\Html::tag('option',Yii::t('app', 'Price List Tanggal : ').\app\components\DeltaFormatter::formatDateTimeForUser($harga_tanggal_penetapan).' '.$status_approval.' '.$total_harga_enduser.' '.$kode.'',['style'=>$style,'value'=>$harga_tanggal_penetapan, 'name'=>'harga_tanggal_penetapan', 'label'=>$kode]);
            }
            $data['html'] = $html;
            return $this->asJson($data);
        }
    }
    
    // !!!
    public function actionSetPrice(){
        if(\Yii::$app->request->isAjax){
            $produk_id = Yii::$app->request->post('produk_id');
            $tgl_penetapan = Yii::$app->request->post('tgl_penetapan');
            $kode = Yii::$app->request->post('kode');
            $tgl_penetapan = \app\components\DeltaFormatter::formatDateTimeForDb($tgl_penetapan);
            $sql = "SELECT harga_distributor, harga_agent, harga_enduser, harga_hpp, status_approval
                    FROM m_harga_produk
                    WHERE produk_id = '".$produk_id."' 
                    AND harga_tanggal_penetapan = '".$tgl_penetapan."' 
                    AND kode = '".$kode."' 
                    ";
            $models = \Yii::$app->db->createCommand($sql)->queryOne();
            if($models){
                $models['harga_distributor'] = \app\components\DeltaFormatter::formatNumberForUser($models['harga_distributor']);
                $models['harga_agent'] = \app\components\DeltaFormatter::formatNumberForUser($models['harga_agent']);
                $models['harga_enduser'] = \app\components\DeltaFormatter::formatNumberForUser($models['harga_enduser']);
                $models['harga_hpp'] = \app\components\DeltaFormatter::formatNumberForUser($models['harga_hpp']);
                $models['harga_distributor_formatted'] = \app\components\DeltaFormatter::formatUang($models['harga_distributor']);
                $models['harga_agent_formatted'] = \app\components\DeltaFormatter::formatUang($models['harga_agent']);
                $models['harga_enduser_formatted'] = \app\components\DeltaFormatter::formatUang($models['harga_enduser']);
                $models['harga_hpp_formatted'] = \app\components\DeltaFormatter::formatUang($models['harga_hpp']);
            }
            return $this->asJson($models);
        }
    }
    
    public function actionDelete($jp,$tp){
        if(\Yii::$app->request->isAjax){
            $jp = Yii::$app->request->get('jp');
            $tp = Yii::$app->request->get('tp');
            $kode = Yii::$app->request->get('kode');
            $pesan = "Apakah Anda yakin akan menghapus data ini : <br>Price List '<b>".$jp."</b>' <br>Tanggal '<b>". \app\components\DeltaFormatter::formatDateTimeForUser2($tp)."</b>' <br>Kode '<b>".$kode." </b>'";
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;

                    $produklist = [];
                    // m_brg_produk
                    $modProduk = \app\models\MBrgProduk::find()->where(['produk_group'=>$jp])->all();
                    foreach($modProduk as $i => $produk){
                        $produklist[] = $produk->produk_id;
                    }
                    $delete = \app\models\MHargaProduk::deleteAll(['and', 'harga_tanggal_penetapan = :tanggal', ['in', 'produk_id', $produklist]], [':tanggal' => $tp ]);
                    if($delete){
                        $success_1 = true;
                    }else{
                        $data['message'] = Yii::t('app', 'Data Price List Gagal dihapus');
                    }

                    // t_approval 
                    $deletex = \app\models\TApproval::deleteAll(['reff_no' => $kode]);
                    if($deletex){
                        $success_2 = true;
                    }else{
                        $data['message'] = Yii::t('app', 'Data Approval Gagal dihapus kode = '.$kode);
                    }
                    
                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Price List Berhasil Dihapus');
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
            return $this->renderAjax('_deleteConfirm',['pesan'=>$pesan,'jp'=>$jp,'tp'=>$tp,'kode'=>$kode]);
        }
    }

	public function actionPriceListPrint(){
        $this->layout = '@views/layouts/metronic/print';
		isset($_GET['jp']) ? $jp = $_GET['jp'] : $jp = 'Platform';
        isset($_GET['tp']) ? $tp = $_GET['tp'] : $tp = '2020-01-01';
        isset($_GET['tp']) ? $kode = $_GET['kode'] : $kode = '';

        $sql = "select m_brg_produk.produk_nama, m_brg_produk.produk_kode, m_brg_produk.produk_dimensi, m_harga_produk.harga_tanggal_penetapan, m_harga_produk.harga_enduser ".
                                "   from m_harga_produk ". 
                                "   join m_brg_produk on m_brg_produk.produk_id = m_harga_produk.produk_id ".
                                "   where m_brg_produk.produk_group = '".$jp."' ".
                                "   and harga_tanggal_penetapan = '".$tp."' ".
                                "   and kode = '".$kode."' ".
                                "   order by harga_id asc ".
                                "   ";
        $model = Yii::$app->db->createCommand($sql)->queryAll();

        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'Daftar Harga Jenis Produk '.$jp.' ');
        $paramprint['judul2'] = "Tanggal Penetapan ".\app\components\DeltaFormatter::formatDateTimeForUser($tp)." ";
        
        if($caraprint == 'PRINT'){
			return $this->render('/pricelist/print',['model'=>$model,'paramprint'=>$paramprint]);
        }
        
        else if ($caraprint == 'PDF') {
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/pricelist/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
        } 
        
        else if ($caraprint == 'EXCEL') {
			return $this->render('/pricelist/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}    

	public function actionHistory(){
        if(\Yii::$app->request->get('dt')=='table-history'){
			$param['table']= \app\models\MHargaProduk::tableName();
			$param['pk']= \app\models\MHargaProduk::primaryKey()[0];
            $param['column'] = ['harga_tanggal_penetapan','kode','produk_group','status_approval'];
            $param['join'] = ['join m_brg_produk on m_brg_produk.produk_id = m_harga_produk.produk_id '];
            $param['group'] = ['group by harga_tanggal_penetapan, kode, produk_group, status_approval'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		
		return $this->render('history');
    }
    
    public function actionHistoryInfo($kode, $harga_tanggal_penetapan){
		if(\Yii::$app->request->isAjax){
            $sql = "select m_brg_produk.produk_id, produk_nama, produk_kode, produk_dimensi, harga_enduser, harga_tanggal_penetapan ". 
                        "   from m_harga_produk ".
                        "   join m_brg_produk on m_brg_produk.produk_id = m_harga_produk.produk_id ".
                        "   where kode = '".$kode."' ". 
                        "   ";
            $model = Yii::$app->db->createCommand($sql)->queryAll();

            return $this->renderAjax('_historyInfo',['model'=>$model,'kode'=>$kode,'harga_tanggal_penetapan'=>$harga_tanggal_penetapan]);
		}
    }

    public function actionGraf($id){
		if(\Yii::$app->request->isAjax){
            $MBrgProduk = \app\models\MBrgProduk::findOne($id);
            $produk_id = $MBrgProduk->produk_id;
            $MHargaProduk = \app\models\MHargaProduk::find()->where(['produk_id' => $produk_id])->all();
            return $this->renderAjax('_graf',['MBrgProduk'=>$MBrgProduk,'MHargaProduk'=>$MHargaProduk]);
		}
    }
        
}
