<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class AppsController extends DeltaBaseController
{   
    /**
     * Displays homepage.
     *
     * @return string
     */
    public $defaultAction = 'index';
	
	public function actionCoba(){                
        echo Yii::$app->controller->action->id;
    }

    public function actionRekoreko(){
        // $this->layout = "rekoreko";
        return $this->render('rekoreko');
    }
    
    public function actionIndex(){
        $message = '';
        return $this->render('index',['message'=>$message]);
    }
    
	public function actionApi(){
        $sql = "SELECT SUBSTR(produk_kode, 2, 3) AS jenis, nomor_produksi, m_brg_produk.produk_nama, m_brg_produk.produk_dimensi, SUM(in_qty_kecil) AS pcs, SUM(in_qty_m3) AS m3 
                FROM h_persediaan_produk
                JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
                GROUP BY 1,2,3,4
                HAVING SUM(in_qty_palet-out_qty_palet)!=0
                LIMIT 30
                ";
        $model = \Yii:: $app->db->createCommand($sql)->queryAll();
        $ret['Search'] = $model;
        $ret['totalResults'] = 'True';
        $ret['Response'] = 'True';
        return $this->asJson($model);
    }
    
    public function actionLogout(){
        \app\components\DeltaGlobalClass::setLoginStatus(0);
        
        // update token
        $username = str_replace("'", "''", $_SESSION['sess_username']);
        $sql_update = "update m_user_token set token='', time='".date('Y-m-d H:i:s')."' where username='".$username."'";
        $query_update = Yii::$app->db->createCommand($sql_update)->execute();

		Yii::$app->response->cookies->remove('user-last-url');
        Yii::$app->user->logout();
        return $this->goHome();
    }
    
    public function actionLogin()
    {
        $this->layout = 'login';
        $model = new \app\models\Login();

        // simpan data user yang akses cis dari luar
        if(\Yii::$app->request->isAjax){
            $datetime = date("Y-m-d H:i:s");
            $ipaddress = Yii::$app->request->post('ipaddress');
                $ipaddress_x = substr($ipaddress,0,9);
            $latitude = Yii::$app->request->post('latitude');
			$longitude = Yii::$app->request->post('longitude');
            $agent = $_SERVER['HTTP_USER_AGENT'];
                $agent_x = substr($agent, 0, 23);
            if ($agent_x != 'Mozilla/5.0 (Windows NT' && $ipaddress == '10.10.10.80') {
                $sql_insert = "insert into t_loc ".
                            " (datetime, ipaddress, latitude, longitude, agent) ".
                            " values ". 
                            " ('".$datetime."', '".$ipaddress."', ".$latitude.", ".$longitude.", '".$agent."') ".
                            "   ";
                $query_insert = Yii::$app->db->createCommand($sql_insert)->execute();
            }

            $data['result'] = 'sip';
            return $this->asJson($data);
        }        
        // eo simpan data user yang akses cis dari luar

        if (!Yii::$app->user->isGuest) { 
            return $this->goHome();
        }

        if(Yii::$app->request->post('Login')){
            $model->attributes = Yii::$app->request->post('Login');
            if ($model->load(Yii::$app->request->post()) && $model->login()) {

                // do a magic here

                // generate token
                function getToken($length){
                    $token = "";
                    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
                    $codeAlphabet .= "0123456789";
                    $codeAlphabet .= "!@#$%^&*()";
                    $max = strlen($codeAlphabet); // edited

                    for ($i=0; $i < $length; $i++) {
                        $token .= $codeAlphabet[random_int(0, $max-1)];
                    }
                    return $token;
                }                

                $token = getToken(10);
                $_SESSION['sess_username'] = $_POST['Login']['username'];
                $username = str_replace("'", "''", $_SESSION['sess_username']);
                $_SESSION['token'] = $token;
                $_SESSION['language'] = 'id-ID';

                // Update user token 
                $sql = "select count(*) as allcount from m_user_token where username = '".$username."' ";
                $numrows = Yii::$app->db->createCommand($sql)->queryScalar();
                if ($numrows > 0) {
                    $sql_update = "update m_user_token set token='".$_SESSION['token']."', time='".date('Y-m-d H:i:s')."' where username='".$username."'";
                    $query_update = Yii::$app->db->createCommand($sql_update)->execute();
                } else {
                    $sql_insert = "insert into m_user_token(username,token,time) values ('".$username."','".$_SESSION['token']."','".date('Y-m-d H:i:s')."') ";
                    $query_insert = Yii::$app->db->createCommand($sql_insert)->execute();
                }
             
                \app\components\DeltaGlobalClass::setLoginStatus(1);

				//if(Yii::$app->request->cookies->has('user-last-url')){
					//return $this->redirect(Yii::$app->request->cookies->getValue('user-last-url'));
				//}else{
					return $this->goHome();
				//}
            }
        }        

        return $this->render('login', [
            'model' => $model,
        ]);
    }
    
    public function actionError()
    {
        $this->layout = 'error';
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
    
    public function actionGetNameEnDefaultValue(){
        if(\Yii::$app->request->isAjax){
			$type = Yii::$app->request->post('type');
			$value = Yii::$app->request->post('value');
            $modDefVal = \app\models\MDefaultValue::find()->where(['active'=>true,'type'=>$type,'value'=>$value])->one();
            $data['result'] = $modDefVal->name_en;
            return $this->asJson($data);
        }
    }

    public function actionReload() {
		$data = [];
		$user_id = Yii::$app->request->post('user_id');
		isset($user_id) ? $user_id = $user_id : $user_id = 'zonk';

        $data['user_id'] = $user_id;
		return $this->asJson($data);
    }
    
    public function actionReloadShow(){
		$user_id = Yii::$app->request->post('user_id');
        isset($user_id) ? $user_id = $user_id : $user_id = 'zonk';
        
        $m_user = \app\models\MUser::find()->where(['user_id' => $user_id])->one();
        $pegawai_id = $m_user->pegawai_id;

        $sql_superuser = "select name from view_user where user_id = ".$user_id."";
        $superuser = Yii::$app->db->createCommand($sql_superuser)->queryScalar();

        /*if ($superuser == "Super User") {
            $t_approval = \app\models\TApproval::find()
                                ->where(['status'=>'Not Confirmed'])
                                ->andWhere(['>=', 'tanggal_berkas', '2020-01-01'])
                                ->orderBy('tanggal_berkas DESC')
                                ->all();
        } else {
            $t_approval = \app\models\TApproval::find()
                                ->where(['assigned_to'=>$pegawai_id])
                                ->andWhere(['status'=>'Not Confirmed'])
                                ->andWhere(['>=', 'tanggal_berkas', '2020-01-01'])
                                ->orderBy('tanggal_berkas DESC')
                                ->all();        
        }*/
        // approval
        // $sql = "select * from t_approval where tanggal_berkas > '2020-01-01' and status = 'Not Confirmed' and assigned_to = ".$pegawai_id." ";
        // $t_approval = Yii::$app->db->createCommand($sql)->queryAll();
        // $jumlah_t_approvalJml = count($t_approval);
        
        // agreemenet
        $t_agreement = \app\models\TAgreement::find()
                        ->where(['assigned_to'=>$pegawai_id])
                        ->andWhere(['status'=>'Not Confirmed'])
                        ->andWhere(['not like', 'reff_no', 'MOP'])
                        // ->orderBy('tanggal_berkas DESC')
                        ->all();
        $jumlah_t_agreement = count($t_agreement);
        // approval
        $t_approval = \app\models\TApproval::find()
                        ->where(['assigned_to'=>$pegawai_id])
                        ->andWhere(['status'=>'Not Confirmed'])
                        ->andWhere(['>=', 'tanggal_berkas', '2020-01-01'])
                        // ->orderBy('tanggal_berkas DESC')
                        ->all();
        $jumlah_t_approvalJml = count($t_approval);

        // kondisikan notif untuk masing-masing proses pada NCR
        // controlNcr
        $sq_controlNcr = "select count(ncr_id) as jmlcontrol from t_ncr 
        where status_control = 'f' and diketahui2 > 0 
            and exists (select ncr_pic_control from m_ncr_pic_control where m_ncr_pic_control.ncr_pic_control = ".$pegawai_id.")";
        $sql_controlNcr = Yii::$app->db->createCommand($sq_controlNcr)->queryScalar();

        // analisaNcr
        $sq_analisaNcr  = "select count(ncr_id) as jmlanalisa from t_ncr 
        where status_control = 't' and status_analisa = 'f' and diketahui2 > 0
                and exists (select ncr_pic_analisa from m_ncr_pic_analisa where m_ncr_pic_analisa.ncr_pic_analisa = ".$pegawai_id.") ";
        $sql_analisaNcr = Yii::$app->db->createCommand($sq_analisaNcr)->queryScalar();

        // penangananNcr
        $sq_penangananNcr  = "select count(ncr_detail_id) as jmltindakan from view_ncr_detail 
            where not exists (select * from t_ncr_perbaikan where t_ncr_perbaikan.ncr_detail_id=view_ncr_detail.ncr_detail_id) 
                and status_approve = 1 and ncr_tindakan_pic = ".$pegawai_id." ";
        $sql_penangananNcr = Yii::$app->db->createCommand($sq_penangananNcr)->queryScalar();

        // verifipenangananNcr
        $sq_verifipenangananNcr = "select count(status_verifikator) as jmlverifikasi
                from view_ncr_perbaikan 
                where ncr_verifikator_pic > 0 and status_verifikator = 'f' and ncr_verifikator_pic = ".$pegawai_id." ";
        $sql_verifipenangananNcr = Yii::$app->db->createCommand($sq_verifipenangananNcr)->queryScalar();

        // statusncr_efektifitas
        $periodeEfektifitas = 1;// kebijakan sebelumnya 90 hari, per tanggal 12 maret 2024 1 hari atau 1 x 24 jam
        $sq_statusncr_efektifitas = "SELECT 
                        COUNT(view_ncr.ncr_efektifitas) AS jmlefektifitas
                    FROM 
                        view_ncr
                    WHERE 
                        view_ncr.ncr_tgl >= '2024-03-14'
                        AND view_ncr.ncr_status = 'f'
                        AND view_ncr.ncr_efektifitas_tgl IS NULL
                        AND view_ncr.diketahui2 IS NOT NULL
                        AND view_ncr.ncr_status_tgl<=(current_date - INTERVAL '{$periodeEfektifitas} day')
                        AND view_ncr.pic_approve_efektifitas ILIKE '%$pegawai_id%'
                        AND NOT EXISTS (
                            SELECT 1
                            FROM t_ncr,json_array_elements(view_ncr.status_efektifitas_reason) AS reason
                            WHERE reason->>'id' = '$pegawai_id' and t_ncr.ncr_id = view_ncr.ncr_id
                        ) ";
        $sql_statusncr_efektifitas = Yii::$app->db->createCommand($sq_statusncr_efektifitas)->queryScalar();
        
        // kondisikan notif untuk masing-masing proses pada Open Tiket
        // penangananBap
        $sq_penangananBap = "select count(bap_tindakan_pic) from view_bap_detail 
                                where bap_tindakan_pic = ".$pegawai_id."
                                        and not exists (
                                                select * from t_bap_perbaikan where t_bap_perbaikan.bap_detail_id=view_bap_detail.bap_detail_id 
                                        )";
        $sql_penangananBap = Yii::$app->db->createCommand($sq_penangananBap)->queryScalar();

        // kondisikan notif untuk masing-masing proses pada CCR
        // penangananccr
        $sq_penangananccr = "select count(ccr_tindakan_pic) as jumlah_ccr
        from view_ccr_detail 
        where ccr_tindakan_pic = ".$pegawai_id."
                and status_approve = 1
                and not exists (
                        select * from t_ccr_perbaikan where t_ccr_perbaikan.ccr_detail_id=view_ccr_detail.ccr_detail_id 
                )";
        $sql_penangananccr = Yii::$app->db->createCommand($sq_penangananccr)->queryScalar();

        // grand total notifikasi
        $jumlah_t_approval = $jumlah_t_agreement + $jumlah_t_approvalJml + $sql_controlNcr + $sql_analisaNcr + $sql_penangananNcr + $sql_verifipenangananNcr + $sql_statusncr_efektifitas + $sql_penangananBap + $sql_penangananccr;

        return $this->render('/layouts/metronic/_counter_t_approval.php',['user_id'=>$user_id, 'pegawai_id' => $pegawai_id
                                ,'jumlah_t_approval'=>$jumlah_t_approval
                                ,'superuser'=>$superuser
                                ,'jumlah_t_agreement'=>$jumlah_t_agreement
                                ,'jumlah_t_approvalJml'=>$jumlah_t_approvalJml
                                ,'t_agreement'=>$t_agreement]);
    }

    public function actionReloadSpm() {
		$data = [];
		$user_id = Yii::$app->request->post('user_id');
		isset($user_id) ? $user_id = $user_id : $user_id = 'zonk';

        $data['user_id'] = $user_id;
		return $this->asJson($data);
    }
    
    public function actionReloadSpmShow(){
		$user_id = Yii::$app->request->post('user_id');
        isset($user_id) ? $user_id = $user_id : $user_id = 'zonk';
        
        $m_user = \app\models\MUser::find()->where(['user_id' => $user_id])->one();
        $pegawai_id = $m_user->pegawai_id;
        $user_group_id = $m_user->user_group_id;
        
        // kadiv mkt 8, staff mkt 9, gm mkt 74, adm mkt 77
        //$user_group_id == 8 || $user_group_id == 74 || 
        if ($user_group_id == 9 || $user_group_id == 77) {
        // hitung op_ko_id di table t_op_ko yang tidak ada di t_spm_ko
            $sql_jumlah_t_op_ko_t_spm_ko = "select count(*) from t_spm_ko a ".
                                            "   join t_op_ko b on b.op_ko_id = a.op_ko_id ".
                                            "   where 1=1 ".
                                            "   and a.jenis_penjualan = 'lokal' ".
                                            "   and a.status_cetak < 1 ".
                                            "   and b.tanggal >= '2020-11-07' ".
                                            "   and b.status_approval = 'APPROVED' ". 
                                            "   ";
            $jumlah_t_op_ko_t_spm_ko = Yii::$app->db->createCommand($sql_jumlah_t_op_ko_t_spm_ko)->queryScalar();
            
            // hitung status_cetak di table t_spm_ko yang status_cetaknya 0
            $sql_status_jumlah_cetak_t_spm = "select count(*) from t_spm_ko where status_cetak < 1";
            $jumlah_status_cetak_t_spm = Yii::$app->db->createCommand($sql_status_jumlah_cetak_t_spm)->queryScalar();

            // agreemenet
        $t_agreement = \app\models\TAgreement::find()
        ->where(['assigned_to'=>$pegawai_id])
        ->andWhere(['status'=>'Not Confirmed'])
        ->andWhere(['not like', 'reff_no', 'MOP'])
        // ->orderBy('tanggal_berkas DESC')
        ->all();
        $jumlah_t_agreement = count($t_agreement);
        // approval
        $t_approval = \app\models\TApproval::find()
                ->where(['assigned_to'=>$pegawai_id])
                ->andWhere(['status'=>'Not Confirmed'])
                ->andWhere(['>=', 'tanggal_berkas', '2020-01-01'])
                // ->orderBy('tanggal_berkas DESC')
                ->all();
        $jumlah_t_approvalJml = count($t_approval);

        // kondisikan notif untuk masing-masing proses pada NCR
        // controlNcr
        $sq_controlNcr = "select count(ncr_id) as jmlcontrol from t_ncr 
        where status_control = 'f' and diketahui2 > 0 
        and exists (select ncr_pic_control from m_ncr_pic_control where m_ncr_pic_control.ncr_pic_control = ".$pegawai_id.")";
        $sql_controlNcr = Yii::$app->db->createCommand($sq_controlNcr)->queryScalar();

        // analisaNcr
        $sq_analisaNcr  = "select count(ncr_id) as jmlanalisa from t_ncr 
        where status_control = 't' and status_analisa = 'f' and diketahui2 > 0
        and exists (select ncr_pic_analisa from m_ncr_pic_analisa where m_ncr_pic_analisa.ncr_pic_analisa = ".$pegawai_id.") ";
        $sql_analisaNcr = Yii::$app->db->createCommand($sq_analisaNcr)->queryScalar();

        // penangananNcr
        $sq_penangananNcr  = "select count(ncr_detail_id) as jmltindakan from view_ncr_detail 
        where not exists (select * from t_ncr_perbaikan where t_ncr_perbaikan.ncr_detail_id=view_ncr_detail.ncr_detail_id) 
        and status_approve = 1 and ncr_tindakan_pic = ".$pegawai_id." ";
        $sql_penangananNcr = Yii::$app->db->createCommand($sq_penangananNcr)->queryScalar();

        // verifipenangananNcr
        $sq_verifipenangananNcr = "select count(status_verifikator) as jmlverifikasi
        from view_ncr_perbaikan 
        where ncr_verifikator_pic > 0 and status_verifikator = 'f' and ncr_verifikator_pic = ".$pegawai_id." ";
        $sql_verifipenangananNcr = Yii::$app->db->createCommand($sq_verifipenangananNcr)->queryScalar();

        // statusncr_efektifitas
        $periodeEfektifitas = 1;// kebijakan sebelumnya 90 hari, per tanggal 12 maret 2024 1 hari atau 1 x 24 jam
        $sq_statusncr_efektifitas = "SELECT 
                COUNT(view_ncr.ncr_efektifitas) AS jmlefektifitas
            FROM 
                view_ncr
            WHERE 
                view_ncr.ncr_tgl >= '2024-03-14'
                AND view_ncr.ncr_status = 'f'
                AND view_ncr.ncr_efektifitas_tgl IS NULL
                AND view_ncr.diketahui2 IS NOT NULL
                AND view_ncr.ncr_status_tgl<=(current_date - INTERVAL '{$periodeEfektifitas} day')
                AND view_ncr.pic_approve_efektifitas ILIKE '%$pegawai_id%'
                AND NOT EXISTS (
                    SELECT 1
                    FROM t_ncr,json_array_elements(view_ncr.status_efektifitas_reason) AS reason
                    WHERE reason->>'id' = '$pegawai_id' and t_ncr.ncr_id = view_ncr.ncr_id
                ) ";
        $sql_statusncr_efektifitas = Yii::$app->db->createCommand($sq_statusncr_efektifitas)->queryScalar();

        // kondisikan notif untuk masing-masing proses pada Open Tiket
        // penangananBap
        $sq_penangananBap = "select count(bap_tindakan_pic) from view_bap_detail 
                        where bap_tindakan_pic = ".$pegawai_id."
                                and not exists (
                                        select * from t_bap_perbaikan where t_bap_perbaikan.bap_detail_id=view_bap_detail.bap_detail_id 
                                )";
        $sql_penangananBap = Yii::$app->db->createCommand($sq_penangananBap)->queryScalar();

        // kondisikan notif untuk masing-masing proses pada CCR
        // penangananccr
        $sq_penangananccr = "select count(ccr_tindakan_pic) as jumlah_ccr
                                from view_ccr_detail 
                                where ccr_tindakan_pic = ".$pegawai_id."
                                            and not exists (
                                                    select * from t_ccr_perbaikan where t_ccr_perbaikan.ccr_detail_id=view_ccr_detail.ccr_detail_id 
                                            )";
        $sql_penangananccr = Yii::$app->db->createCommand($sq_penangananccr)->queryScalar();

        // grand total notifikasi
        $jumlah_t_approval = $jumlah_t_agreement + $jumlah_t_approvalJml + $sql_controlNcr + $sql_analisaNcr + $sql_penangananNcr + $sql_verifipenangananNcr + $sql_statusncr_efektifitas + $sql_penangananBap + $sql_penangananccr;

        }

        return $this->render('/layouts/metronic/_counter_t_op_ko_t_spm_ko.php',
                                ['user_id'=>$user_id
                                    , 'pegawai_id' => $pegawai_id
                                    ,'jumlah_t_op_ko_t_spm_ko'=>$jumlah_t_op_ko_t_spm_ko
                                    ,'jumlah_status_cetak_t_spm'=>$jumlah_status_cetak_t_spm
                                    ,'jumlah_t_approval'=>$jumlah_t_approval]);
    }

    public function actionReloadTuk() {
		$data = [];
		$user_id = Yii::$app->request->post('user_id');
		isset($user_id) ? $user_id = $user_id : $user_id = 'zonk';

        $data['user_id'] = $user_id;
		return $this->asJson($data);
    }
    
    public function actionReloadTukShow(){
		$user_id = Yii::$app->request->post('user_id');
        isset($user_id) ? $user_id = $user_id : $user_id = 'zonk';
        
        $m_user = \app\models\MUser::find()->where(['user_id' => $user_id])->one();
        $pegawai_id = $m_user->pegawai_id;
        $user_group_id = $m_user->user_group_id;
        
        // denny 20, agus 21, staff_tuk 27, kampret 359
        if ($user_group_id == 27) {
        // hitung nota penjualan yang sudah diterbitkan oleh marketing
            $sql_jumlah_nota_penjualan = "select count(*) from t_nota_penjualan ".
                                            "   where t_nota_penjualan.cancel_transaksi_id is null ".
                                            "   and t_nota_penjualan.spm_ko_id not in (select spm_ko_id from t_dokumen_penjualan) ".
                                            "   and t_nota_penjualan.jenis_produk not in ('Limbah') ".
                                            "   and t_nota_penjualan.op_ko_id != 999999 ".
                                            "   ";
            $jumlah_nota_penjualan = Yii::$app->db->createCommand($sql_jumlah_nota_penjualan)->queryScalar();
        }

        return $this->render('/layouts/metronic/_counter_tuk.php',
                                ['user_id'=>$user_id
                                    , 'pegawai_id' => $pegawai_id
                                    ,'jumlah_nota_penjualan'=>$jumlah_nota_penjualan]);
    }
    
    public function actionReloadOpexportPackinglist() {
        $data = [];
        $user_id = Yii::$app->request->post('user_id');
        isset($user_id) ? $user_id = $user_id : $user_id = 'zonk';

        $data['user_id'] = $user_id;
        return $this->asJson($data);
    }
    
    public function actionReloadOpexportPackinglistShow(){
        $user_id = Yii::$app->request->post('user_id');
        isset($user_id) ? $user_id = $user_id : $user_id = 'zonk';
        
        $m_user = \app\models\MUser::find()->where(['user_id' => $user_id])->one();
        $pegawai_id = $m_user->pegawai_id;
        $user_group_id = $m_user->user_group_id; // \app\components\Params::USER_GROUP_ID_PPIC_STAFF; //
//        $pegawai_1 = \app\components\Params::DEFAULT_PEGAWAI_ID_SRI_RAHAYUNINGSIH;
//        $pegawai_2 = \app\components\Params::DEFAULT_PEGAWAI_ID_AGUSTINA_SRI_HARTATIK;
                
        if ($user_group_id == 103  && $pegawai_id == 5 ) { //jika pengelola moulding "Staff PPIC"
            // hitung op export yang belum di terbitkan proforma packinglist
            $sql_opexport_packinglist = "select count(jenis_produk) as jml from t_op_export 
                                        where not exists(
                                                select op_export_id from t_packinglist where t_op_export.op_export_id=t_packinglist.op_export_id 
                                        )and jenis_produk in('Moulding')";
            $opexport_packinglist = Yii::$app->db->createCommand($sql_opexport_packinglist)->queryScalar();
        }
        
        if ($user_group_id == 103 && $pegawai_id == 98 ) { //jika pengelola plywood,platform,lamineboard "Staff PPIC"
            // hitung op export yang belum di terbitkan proforma packinglist
            $sql_opexport_packinglist = "select count(jenis_produk) as jml from t_op_export 
                                        where not exists(
                                                select op_export_id from t_packinglist where t_op_export.op_export_id=t_packinglist.op_export_id 
                                        )and jenis_produk in('Platform','Plywood','Lamineboard')";
            $opexport_packinglist = Yii::$app->db->createCommand($sql_opexport_packinglist)->queryScalar();
        }
        return $this->render('/layouts/metronic/_counter_opexport_packinglist.php',
                                ['user_id'=>$user_id
                                    , 'id' => $pegawai_id
                                    ,'opexport_packinglist'=>$opexport_packinglist]);
    }
    
    public function actionSaveLog() {
        // simpan data user yang akses cis dari luar
        if(\Yii::$app->request->isAjax){
            $datetime = date("Y-m-d H:i:s");
            $ipaddress = Yii::$app->request->post('ipaddress');
                $ipaddress_x = substr($ipaddress,0,9);
            $latitude = Yii::$app->request->post('latitude');
			$longitude = Yii::$app->request->post('longitude');
            $agent = $_SERVER['HTTP_USER_AGENT'];
                $agent_x = substr($agent, 0, 23);
            //if ($agent_x != 'Mozilla/5.0 (Windows NT' && $ipaddress == '10.10.10.80') {
                $sql_insert = "insert into t_loc ".
                            " (datetime, ipaddress, latitude, longitude, agent) ".
                            " values ". 
                            " ('".$datetime."', '".$ipaddress."', ".$latitude.", ".$longitude.", '".$agent."') ".
                            "   ";
                $query_insert = Yii::$app->db->createCommand($sql_insert)->execute();
            //}

            $data['result'] = 'sip';
            return $this->asJson($data);
        }        
        // eo simpan data user yang akses cis dari luar        
    }

    public function actionToggleLanguage()
    {
        if(Yii::$app->request->isGet) {
            $language = Yii::$app->request->get('language');
            Yii::$app->session->set('language', $language);
        }
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}
