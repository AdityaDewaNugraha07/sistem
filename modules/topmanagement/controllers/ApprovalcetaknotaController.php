<?php

namespace app\modules\topmanagement\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class ApprovalcetaknotaController extends DeltaBaseController
{
//    public $defaultAction = 'index';
    public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-master'){
            $param['table'] = \app\models\ViewApproval::tableName();
            $param['pk'] = "approval_id";
            $param['column'] = ['approval_id',	
                                    't_nota_penjualan.kode',
                                    'm_customer.cust_an_nama',	
                                    ['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'],
                                    'assigned_nama', 
                                    'approved_by_nama', 
                                    $param['table'].'.status',
                                    $param['table'].'.created_at'
                                ];
            
            $param['where'] = "(substring(parameter1::TEXT,1,3)) = 'INV' and view_approval.status = 'Not Confirmed'";
            $param['join'] = " join t_nota_penjualan on view_approval.reff_no = t_nota_penjualan.kode join m_customer on m_customer.cust_id = t_nota_penjualan.cust_id ";
            if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
                $param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id."  ";
            }

            $param['order'] = "created_at DESC, level ASC";
            return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
        }        
        return $this->render('index',['status' => 'Not Confirmed']);    
        
    }
    
    public function actionIndexConfirmed(){
        if(\Yii::$app->request->get('dt')=='table-master'){
            $param['table'] = \app\models\ViewApproval::tableName();
            $param['pk'] = "approval_id";
            $param['column'] = ['approval_id',	
                                    't_nota_penjualan.kode',
                                    'm_customer.cust_an_nama',	
                                    ['col_name'=>'tanggal_berkas','formatter'=>'formatDateTimeForUser'],
                                    'assigned_nama', 
                                    'approved_by_nama', 
                                    $param['table'].'.status',
                                    $param['table'].'.created_at'
                                ];
            $param['where'] = "(substring(parameter1::TEXT,1,3)) = 'INV' and view_approval.status != 'Not Confirmed' ";
            $param['join'] = "join t_nota_penjualan on view_approval.reff_no = t_nota_penjualan.kode join m_customer on m_customer.cust_id = t_nota_penjualan.cust_id";
            if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
                    $param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
            }
            $param['order'] = "created_at DESC, level ASC";
            return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
        }
        return $this->render('index',['status' => 'Confirmed']);
    }

    public function actionInfo($id){
        if(\Yii::$app->request->isAjax){
                $model = \app\models\TApproval::findOne($id);
                return $this->renderAjax('info',['model'=>$model]);
        }
    }
    public function actionNotAllowed(){
        if(\Yii::$app->request->isAjax){
            $judul = "Agreement Confirm!";
            $pesan = "Anda belum bisa mengkonfirmasi ini, sebelum approver dibawah level anda mengkonfirmasi approval nya.";
            return $this->renderAjax('@views/apps/partial/_globalInfo',['judul'=>$judul,'pesan'=>$pesan,'actionname'=>'']);
        }
    }

    public function actionConfirm(){
        if(\Yii::$app->request->isAjax){
            $approval_id = Yii::$app->request->post('approval_id');
            $modApprove = \app\models\TApproval::findOne($approval_id);
            $modHO = \app\models\TNotaPenjualan::findOne(['kode'=>$modApprove->reff_no] );
            $checkApprovals = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '".$modHO->kode."' AND level < ".$modApprove->level)->queryAll();
            $data = true;
            
            if(count($checkApprovals)>0){
                foreach($checkApprovals as $i => $check){
                    if($check['status'] != \app\models\TApproval::STATUS_NOT_CONFIRMATED){
                            $data &= true;
                    }else{
                        $data &= false;
                    }
                }
            }
            return $this->asJson($data);
        }
    }
    
    public function actionApproveReason($id){
        if(\Yii::$app->request->isAjax){
            $model = \app\models\TApproval::findOne($id);
            $modelReff = \app\models\TNotaPenjualan::findOne(['kode'=>$model->reff_no]);
            if( Yii::$app->request->post('TNotaPenjualan')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; //t_approval
                    $success_2 = false; //t_nota_penjualan
                    $success_3 = false;
                    if(!empty($model) && !empty($_POST['TNotaPenjualan']['approve_reason'])){
                        $model->approved_by = Yii::$app->user->identity->pegawai_id;

                        // ambil user_id untuk update t_nota_penjualan
                        //$user_id = Yii::$app->user->identity->user_id;

                        $model->tanggal_approve = date('Y-m-d');

                        // tanggal jam menit detik hari ini untuk update t_op_ko
                        $updated_at = date('Y-m-d H:i:s');

                        $model->status = \app\models\TApproval::STATUS_APPROVED;
                        if($model->validate()){
                            if($model->save()){
                                $success_1 = true;
                                $arrPost = ['status'=>"APPROVED",
                                            'by'=> $model->approved_by,
                                            'at'=>date('Y-m-d H:i:s'),
                                            'reason'=>$_POST['TNotaPenjualan']['approve_reason']
                                            ];
                                if(!empty($modelReff->approve_reason)){
                                    $reason = \yii\helpers\Json::decode($modelReff->approve_reason);
                                    $approve_reason = [];
                                    foreach($reason as $i => $reas){
                                            $approve_reason[] = $reas;
                                    }
                                    array_push($approve_reason, $arrPost);
                                }else{
                                    $approve_reason[0] = $arrPost;
                                }
                                $modelReff->approve_reason = \yii\helpers\Json::encode($approve_reason);

                                $sql_json = "select approve_reason from t_nota_penjualan where kode = '".$model->reff_no."' ";
                                $jsons = Yii::$app->db->createCommand($sql_json)->queryScalar();

                                if (empty($jsons)) {
                                    $sqlUpdate =  " UPDATE t_nota_penjualan
                                                    SET approve_reason = '$modelReff->approve_reason',status_approval='APPROVED'
                                                    WHERE kode = '".$model->reff_no."' ";
                                    $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();

                                } else if (!empty($jsons)) {
                                    $json = json_decode($jsons);
                                    $pegawai_id = Yii::$app->user->identity->pegawai_id;

                                    foreach($json as $key) {
                                        if ($key->by == $pegawai_id) {
                                            $value = 'ada';
                                        } else {
                                            $value = 'kosong';
                                        }
                                    }

                                    if ($value == 'kosong') {
                                        $sqlUpdate =  " UPDATE t_nota_penjualan
                                                        SET approve_reason = '$modelReff->approve_reason'
                                                        WHERE kode = '".$model->reff_no."' ";
                                        $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();
                                    } else {
                                        $success_2 = true ;
                                    }
                                } else {
                                    $success_2 = true;
                                }
                            }
                        }
                    }else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }

                    $sql_ = "select count(*) from t_approval where approval_id = '".$id."' and approved_by is not NULL ";
                    $query_ = Yii::$app->db->createCommand($sql_)->queryScalar();
                    $result = $query_;
                    $result > 0 ? $success_3 = true : $success_3 = false;

                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['callback'] = '';

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
            return $this->renderAjax('approveReason',['modelReff'=>$modelReff,'id'=>$id]);
        }
    }

    public function actionRejectReason($id){
        if(\Yii::$app->request->isAjax){
            $model = \app\models\TApproval::findOne($id);
            $modelReff = \app\models\TNotaPenjualan::findOne(['kode'=>$model->reff_no]);
            if( Yii::$app->request->post('TNotaPenjualan')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; //t_approval
                    $success_2 = false; //t_nota_penjualan
                    if(!empty($model) && !empty($_POST['TNotaPenjualan']['reject_reason'])){
                        $model->approved_by = Yii::$app->user->identity->pegawai_id;
                        $model->tanggal_approve = date('Y-m-d');
                        $model->status = \app\models\TApproval::STATUS_REJECTED;
                        if($model->validate()) {
                            if($model->save()) {
                                    $success_1 = true;
                                    $arrPost = ['status'=>"REJECTED",
                                    'by'=> $model->approved_by,
                                    'at'=>date('Y-m-d H:i:s'),
                                    'reason'=>$_POST['TNotaPenjualan']['reject_reason']
                                                                            ];
                                    if(!empty($modelReff->reject_reason)){
                                            $reason = \yii\helpers\Json::decode($modelReff->reject_reason);
                                            $reject_reason = [];
                                            foreach($reason as $i => $reas){
                                                    $reject_reason[] = $reas;
                                            }
                                            array_push($reject_reason, $arrPost);
                                    }else{
                                            $reject_reason[0] = $arrPost;
                                    }
                                    $modelReff->reject_reason = \yii\helpers\Json::encode($reject_reason);

                                    $sqlUpdate =  " UPDATE t_nota_penjualan
                                                    SET reject_reason = '$modelReff->reject_reason',status_approval='REJECTED'
                                                    WHERE kode = '".$model->reff_no."' ";
                                    $success_2 = Yii::$app->db->createCommand($sqlUpdate)->execute();

                                    // jika yang reject level 1, update status approval level 2 sekalian jadi reject
                                    if ($model->level == 1) {
                                        $sql_update = "update t_approval set status = 'REJECTED' ".
                                                                        "	, approved_by = 19 ".
                                                                        "	, tanggal_approve = '".$model->tanggal_approve."' ".
                                                                        "	where reff_no = '".$model->reff_no."' ";
                                        $success_3 = Yii::$app->db->createCommand($sql_update)->execute();
                                    } else {
                                        $success_3 = 1;
                                    }

                            }
                        }
                    }else{
                        $data['message']="Maaf, alasan tidak boleh kosong"; 
                    }

                    if ($success_1 && $success_2 && $success_3) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['callback'] = '';
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
            return $this->renderAjax('rejectReason',['modelReff'=>$modelReff,'id'=>$id]);
        }
    }
    public function actionImage($id){
        if(\Yii::$app->request->isAjax){
                $attch = \app\models\TAttachment::findOne($id);
                return $this->renderAjax('image',['attch'=>$attch]);
        }
    }
}
