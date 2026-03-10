<?php

namespace app\modules\topmanagement\controllers;

use app\components\Params;
use app\components\SSP;
use app\models\TApproval;
use app\models\TPackinglist;
use app\models\ViewApproval;
use Yii;
use app\controllers\DeltaBaseController;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Response;

class ApprovalproformaController extends DeltaBaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->request->get('dt') === 'table-master') {
            $param['table'] = ViewApproval::tableName();
            $param['pk'] = "approval_id";
            $param['column'] = ['approval_id',
                'tanggal_berkas',
                't_op_export.nomor_kontrak',
                't_packinglist.kode',
                't_packinglist.revisi_ke',
                'm_customer.cust_an_nama',
                'assigned_nama',
                'approved_by_nama',
                $param['table'] . '.status',
                $param['table'] . '.created_at',
                $param['table'] . '.created_at'];
            $param['where'] = "(reff_no ILIKE '%PPL%' and view_approval.status = 'Not Confirmed' )";
            $param['join'] = "left join t_packinglist on view_approval.reff_no = t_packinglist.kode
							  left join m_customer on m_customer.cust_id = t_packinglist.cust_id
							  left join t_op_export on t_op_export.op_export_id = t_packinglist.op_export_id";
            if (Yii::$app->user->identity->user_group_id !== Params::USER_GROUP_ID_SUPER_USER) {
                $param['where'] .= "AND assigned_to = " . Yii::$app->user->identity->pegawai_id . " ";
            }
            $param['order'] = "created_at DESC, level ASC";
            return Json::encode(SSP::complex($param));
        }
        return $this->render('index', ['status' => 'Not Confirmed']);
    }

    /**
     * @return string
     */
    public function actionIndexConfirmed()
    {
        if (Yii::$app->request->get('dt') === 'table-master') {
            $param['table'] = ViewApproval::tableName();
            $param['pk'] = "approval_id";
            $param['column'] = ['approval_id',
                'tanggal_berkas',
                't_op_export.nomor_kontrak',
                't_packinglist.kode',
                't_packinglist.revisi_ke',
                'm_customer.cust_an_nama',
                'assigned_nama',
                'approved_by_nama',
                $param['table'] . '.status',
                $param['table'] . '.created_at',
                $param['table'] . '.created_at'];
            $param['where'] = "(reff_no ILIKE '%PPL%' and view_approval.status != 'Not Confirmed' )";
            $param['join'] = "left join t_packinglist on view_approval.reff_no = t_packinglist.kode
							  left join m_customer on m_customer.cust_id = t_packinglist.cust_id
							  left join t_op_export on t_op_export.op_export_id = t_packinglist.op_export_id";
            if (Yii::$app->user->identity->user_group_id !== Params::USER_GROUP_ID_SUPER_USER) {
                $param['where'] .= "AND assigned_to = " . Yii::$app->user->identity->pegawai_id . " ";
            }
            $param['order'] = "created_at DESC";
            return Json::encode(SSP::complex($param));
        }
        return $this->render('index', ['status' => 'Confirmed']);
    }

    /**
     * @param $id
     * @return string|void
     */
    public function actionInfo($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = TApproval::findOne($id);
            return $this->renderAjax('info', ['model' => $model]);
        }
    }

    /**
     * @return void|Response
     */
    public function actionShowDetails()
    {
        if (Yii::$app->request->isAjax) {
            $approval_id = Yii::$app->request->post('approval_id');
            $model = TApproval::findOne($approval_id);
            $modReff = TPackinglist::findOne(['kode' => $model->reff_no]);
            $data['html'] = $this->renderPartial('show', ['model' => $model, 'modReff' => $modReff, 'approval_id' => $approval_id]);
            return $this->asJson($data);
        }
    }

    /**
     * @param $id
     * @return string|void|Response
     * @throws Exception
     */
    public function actionApproveConfirm($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = TApproval::findOne($id);
            if (Yii::$app->request->isPost) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if ($model !== null) {
                        $model->approved_by = Yii::$app->user->identity->pegawai_id;
                        $model->tanggal_approve = date('Y-m-d');
                        $model->status = TApproval::STATUS_APPROVED;
                        if ($model->validate() && $model->save()) {
                            $success_1 = true;
                            $packlist = TPackinglist::findOne(['kode' => $model->reff_no]);
                            $build = [];
                            foreach (Json::decode($packlist->reason_approval, false) as $approval) {
                                if ($approval->pegawai_id === $model->assigned_to && $approval->level === $model->level) {
                                    $build[] = [
                                        'pegawai_id' => $model->assigned_to,
                                        'tanggal' => $model->updated_at,
                                        'reason' => $_POST['TApproval']['keterangan'],
                                        'status' => $model->status,
                                        'level' => $model->level
                                    ];
                                } else {
                                    $build[] = [
                                        'pegawai_id' => $approval->pegawai_id,
                                        'tanggal' => $approval->tanggal,
                                        'reason' => $approval->reason,
                                        'status' => $approval->status,
                                        'level' => $approval->level
                                    ];
                                }
                            }
                            $packlist->reason_approval = Json::encode($build);
                            $packlist->save();
                            // cek jika approval terakhir (level 3) approved maka update status approval di t_pakcinglist
                            $last_approve = TApproval::find()->where(['reff_no' => $model->reff_no])->orderBy(['level' => SORT_DESC])->one();
                            if ($last_approve->status === TApproval::STATUS_APPROVED) {
                                $packinglist = TPackinglist::findOne(['kode' => $last_approve->reff_no]);
                                $packinglist->status_approval = TApproval::STATUS_APPROVED;
                                $packinglist->save();
                            }
                        }
                    }
                    if ($success_1) {
                        if ($transaction !== null) {
                            $transaction->commit();
                        }
                        $data['status'] = true;
                        $data['message'] = Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE;
                        $data['callback'] = 'setTimeout(() => {window.location.href = "/cis/web/topmanagement/approvalproforma/indexConfirmed"}, 500)';
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
//            return $this->renderAjax('@views/apps/partial/_globalConfirm', ['id' => $id, 'pesan' => $pesan, 'actionname' => 'ApproveConfirm']);
            return $this->renderAjax('approveReason', compact('model'));
        }
    }

    /**
     * @param $id
     * @return string|void|Response
     * @throws Exception
     */
    public function actionRejectConfirm($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = TApproval::findOne($id);
            if (Yii::$app->request->isPost) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if ($model !== null) {
                        $model->approved_by = Yii::$app->user->identity->pegawai_id;
                        $model->tanggal_approve = date('Y-m-d');
                        $model->status = TApproval::STATUS_REJECTED;
                        if ($model->validate() && $model->save()) {
                            $success_1 = true;
                            $packlist = TPackinglist::findOne(['kode' => $model->reff_no]);
                            $build = [];
                            foreach (Json::decode($packlist->reason_approval, false) as $approval) {
                                if ($approval->pegawai_id === $model->assigned_to && $approval->level === $model->level) {
                                    $build[] = [
                                        'pegawai_id' => $model->assigned_to,
                                        'tanggal' => $model->updated_at,
                                        'reason' => $_POST['TApproval']['keterangan'],
                                        'status' => $model->status,
                                        'level' => $model->level
                                    ];
                                } else {
                                    $build[] = [
                                        'pegawai_id' => $approval->pegawai_id,
                                        'tanggal' => $approval->tanggal,
                                        'reason' => $approval->reason,
                                        'status' => $approval->status,
                                        'level' => $approval->level
                                    ];
                                }
                            }
                            $packlist->reason_approval = Json::encode($build);
                            $packlist->save();
                            // cek jika approval terakhir (level 3) REJECT maka update status approval di t_pakcinglist
                            $last_approve = TApproval::find()->where(['reff_no' => $model->reff_no])->orderBy(['level' => SORT_DESC])->one();
                            if ($last_approve->status === TApproval::STATUS_REJECTED) {
                                $packinglist = TPackinglist::findOne(['kode' => $last_approve->reff_no]);
                                $packinglist->status_approval = TApproval::STATUS_REJECTED;
                                $packinglist->save();
                            }
                        }
                    }
                    if ($success_1) {
                        if ($transaction !== null) {
                            $transaction->commit();
                        }
                        $data['status'] = true;
                        $data['message'] = Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE;
                        $data['callback'] = 'setTimeout(() => {window.location.href = "/cis/web/topmanagement/approvalproforma/indexConfirmed"}, 500)';
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
            return $this->renderAjax('rejectReason', compact('model'));
        }
    }

//    /**
//     * @return void|Response
//     */
//    public function actionConfirm()
//    {
//        if (Yii::$app->request->isAjax) {
//            $approval_id = Yii::$app->request->post('approval_id');
//            $modApprove = TApproval::findOne($approval_id);
//            $model = TPackinglist::findOne(['kode' => $modApprove->reff_no]);
//            $modApproveMenyetujui = TApproval::findOne(['reff_no' => $model->kode, 'assigned_to' => $model->disetujui]);
//            if ($modApprove->assigned_to === $modApproveMenyetujui->assigned_to) {
//                $data = TRUE;
//            } else if ($modApproveMenyetujui->status === TApproval::STATUS_APPROVED) {
//                $data = TRUE;
//            } else {
//                $data = FALSE;
//            }
//            return $this->asJson($data);
//        }
//    }
//
//    public function actionNotAllowed()
//    {
//        if (Yii::$app->request->isAjax) {
//            $judul = "Agreement Confirm!";
//            $pesan = "Proforma ini belum dapat di konfirmasi, karena orang yang menyetujui belum melakukan konfirmasi.";
//            return $this->renderAjax('@views/apps/partial/_globalInfo', ['judul' => $judul, 'pesan' => $pesan, 'actionname' => '']);
//        }
//    }

}
