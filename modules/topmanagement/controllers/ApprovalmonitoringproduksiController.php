<?php

namespace app\modules\topmanagement\controllers;

use app\components\Params;
use app\components\SSP;
use app\models\MMtrgSetup;
use app\models\TApproval;
use app\models\TMtrgInOut;
use app\models\TMtrgInOutDetail;
use app\models\TMtrgRotary;
use app\models\TMtrgRotaryDetail;
use app\models\ViewApproval;
use Yii;
use app\controllers\DeltaBaseController;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Response;


class ApprovalmonitoringproduksiController extends DeltaBaseController
{
    public function actionIndex()
    {
        // Tangkap semua parameter GET terlebih dahulu
        $dt = Yii::$app->request->get('dt', 'no-value');
        $status = Yii::$app->request->get('status');
        $keyword = Yii::$app->request->get('keyword');

        // Debug untuk memastikan nilai dt, status, dan keyword
        // var_dump($dt);
        // var_dump($status);
        // var_dump($keyword);

        // Cek apakah 'dt' bernilai 'table-master'
        if ($dt === 'table-master') {
            // Parameter yang digunakan dalam query
            $param['table'] = ViewApproval::tableName();
            $param['pk'] = "approval_id";
            $param['column'] = [
                'approval_id',
                't_mtrg_in_out.kode',
                [
                    'col_name' => 'tanggal_berkas',
                    'formatter' => 'formatDateTimeForUser'
                ],
                $param['table'] . '.status',
                't_mtrg_in_out.shift',
                't_mtrg_in_out.status_in_out',
                't_mtrg_in_out.kategori_proses',
                'assigned_nama',
                'approved_by_nama',
                [
                    'col_name' => 'tanggal_produksi',
                    'formatter' => 'formatDateTimeForUser'
                ],
                $param['table'] . '.created_at',
                [
                    'col_name' => 'tanggal_approve',
                    'formatter' => 'formatDateTimeForUser'
                ],
            ];

            // Join dengan table lain
            $param['join'] = "join t_mtrg_in_out on view_approval.reff_no = t_mtrg_in_out.kode";

            // Cek status
            if ($status === TApproval::STATUS_NOT_CONFIRMATED) {
                $param['where'] = "view_approval.status = 'Not Confirmed'";
                $param['order'] = "created_at ASC, level ASC";
            } else {
                $param['where'] = "view_approval.status <> 'Not Confirmed'";
                $param['order'] = "created_at DESC, level ASC";
            }

            // Tambahkan filter berdasarkan keyword
            if (isset($keyword) && $keyword !== '') {
                $param['where'] .= " AND left(reff_no,3) = '" . $keyword . "'";
            }

            // Tambahkan filter berdasarkan user_group_id
            if (Yii::$app->user->identity->user_group_id !== Params::USER_GROUP_ID_SUPER_USER) {
                $param['where'] .= " AND assigned_to = " . Yii::$app->user->identity->pegawai_id . " ";
            }

            // Return hasil JSON
            return Json::encode(SSP::complex($param));
        }

        // Render default jika tidak ada 'table-master'
        return $this->render('index');
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
     * @param $id
     * @return string|Response
     * @throws Exception
     */
    public function actionApproveReason($id)
    {
        $model      = TApproval::findOne($id);
        if (Yii::$app->request->isPost) {
            $transaction    = Yii::$app->db->beginTransaction();
            $alasan         = $_POST['TApproval']['keterangan'];
            try {
                $model->status  = TApproval::STATUS_APPROVED;
                $model->tanggal_approve = date('Y-m-d');
                $model->approved_by = Yii::$app->user->identity->pegawai->pegawai_id;
                if (!$model->validate() || !$model->save()) {
                    throw new Exception(array_values($model->firstErrors)[0]);
                }

                $modReff = TMtrgInOut::findOne(['kode' => $model->reff_no]);
                // if ($model->level === 3) { //kebijakan awal berlaku approval level 1,2,3
                if ($model->level === 1) { //sesuai dengan kebijakan per tanggal 06/04/2024 berlaku satu approval hanya level 1
                    $modReff->status_approval = TApproval::STATUS_APPROVED;
                }
                $reasons = array_map(static function ($value) use ($model, $alasan) {
                    return $value['level'] === $model->level
                        ? [
                            'assigned_to' => $model->assigned_to,
                            'level' => $model->level,
                            'status' => TApproval::STATUS_APPROVED,
                            'tanggal_approve' => date('Y-m-d H:i:s'),
                            'reason' => $alasan
                        ]
                        : $value;
                }, Json::decode($modReff->reason_approval));
                $modReff->reason_approval = Json::encode($reasons);

                if (!$modReff->validate() || !$modReff->save()) {
                    throw new Exception(array_values($modReff->firstErrors)[0]);
                }

                if ($modReff->status_approval === TApproval::STATUS_APPROVED) {
                    if ($modReff->kategori_proses === MMtrgSetup::KATEGORI_PLYTECH) {
                        // kondisikan waktu sekarang
                        $current_date   = date('Y-m-d');
                        $current_dateTime = date('Y-m-d H:i:s');  
                        if(MMtrgSetup::getActiveDate() === $current_date){
                            if($modReff->tanggal_produksi > MMtrgSetup::getActiveDate().' 09:00:00'){
                                $setKondisitglproduksi = ['>', 't_mtrg_in_out.tanggal_produksi', MMtrgSetup::getActiveDate() . ' 09:00:00'];
                            }else{
                                $setKondisitglproduksi = ['>', 't_mtrg_in_out.tanggal_produksi', MMtrgSetup::getActiveDate() . ' 01:00:00'];
                            }
                        }else{
                            $setKondisitglproduksi = ['>','t_mtrg_in_out.tanggal_produksi', MMtrgSetup::getActiveDate() . ' 09:00:00'];
                        }
                        $query          = TMtrgInOutDetail::find()
                                            ->join('INNER JOIN', 't_mtrg_in_out', 't_mtrg_in_out.mtrg_in_out_id = t_mtrg_in_out_detail.mtrg_in_out_id')
                                            ->where([
                                                't_mtrg_in_out.kategori_proses' => MMtrgSetup::KATEGORI_PLYTECH,
                                                't_mtrg_in_out.jenis_kayu' => $modReff->jenis_kayu,
                                                //'date(t_mtrg_in_out.tanggal_produksi)' => MMtrgSetup::getActiveDate()
                                            ])
                                            ->andWhere($setKondisitglproduksi);
                        $total_pcs      = $query->sum('t_mtrg_in_out_detail.pcs');
                        $total_patch    = $query->sum('t_mtrg_in_out_detail.patching');
                        $total_volume   = $query->sum('t_mtrg_in_out_detail.volume');
                        $grades         = TMtrgInOutDetail::find()->where(['mtrg_in_out_id' => $modReff->mtrg_in_out_id])->one();
                        $grades         = Json::decode($grades->grade);

                        $setup_m3       = MMtrgSetup::findOne(['mtrg_setup_id' => $grades['M3']]);
                        $setup_m3->jumlah_aktual = $total_volume;
                        if (!$setup_m3->validate() || !$setup_m3->save()) {
                            throw new Exception(array_values($setup_m3->firstErrors)[0]);
                        }

                        $startDate  = MMtrgSetup::getActiveDate(0, $modReff->created_at);
                        $startDate .= " 07:00:00";
                        $jam_jalan  = round(abs(strtotime($modReff->tanggal_produksi) - strtotime($startDate)) / (60 * 60), 1);
                        $setup_jam  = MMtrgSetup::findOne(['mtrg_setup_id' => $grades['Jam Jalan']]);

                        $setup_jam->jumlah_aktual = $jam_jalan;
                        if (date('H:i:s') > '13:00:00') {
                            --$setup_jam->jumlah_aktual;
                        }

                        if (date('H:i:s') > '01:00:00' && date('H:i:s') < '09:00:00') {
                            $setup_jam->jumlah_aktual -= 2;
                        }

                        if (!$setup_jam->validate() || !$setup_jam->save()) {
                            throw new Exception(array_values($setup_jam->firstErrors)[0]);
                        }

                        $setup_output_per_jam = MMtrgSetup::findOne(['mtrg_setup_id' => $grades['Output / Jam']]);
                        $setup_output_per_jam->jumlah_aktual = $setup_m3->jumlah_aktual / $setup_jam->jumlah_aktual;

                        if (!$setup_output_per_jam->validate() || !$setup_output_per_jam->save()) {
                            throw new Exception(array_values($setup_output_per_jam->firstErrors)[0]);
                        }

                        $setup_patch_per_pcs = MMtrgSetup::findOne(['mtrg_setup_id' => $grades['Patching / Pcs']]);
                        $setup_patch_per_pcs->jumlah_aktual = (int)$total_patch / (int)$total_pcs;

                        if (!$setup_patch_per_pcs->validate() || !$setup_patch_per_pcs->save()) {
                            throw new Exception(array_values($setup_patch_per_pcs->firstErrors)[0]);
                        }

                        $setup_patching = MMtrgSetup::findOne(['mtrg_setup_id' => $grades['Patching']]);
                        $setup_patching->jumlah_aktual = $total_patch;

                        if(!$setup_patching->validate() || !$setup_patching->save()) {
                            throw new Exception(array_values($setup_patching->firstErrors)[0]);
                        }

                        $setup_qty = MMtrgSetup::findOne(['mtrg_setup_id' => $grades['Qty']]);
                        $setup_qty->jumlah_aktual = $total_pcs;

                        if(!$setup_qty->validate() || !$setup_qty->save()) {
                            throw new Exception(array_values($setup_qty->firstErrors)[0]);
                        }

                    } else if ($modReff->status_in_out === MMtrgSetup::OUTPUT && $modReff->kategori_proses === MMtrgSetup::KATEGORI_REPAIR) {
                        $grades     = MMtrgSetup::findAll([
                            'kategori_proses' => MMtrgSetup::KATEGORI_REPAIR,
                            'jenis_proses' => MMtrgSetup::OUTPUT,
                            'tanggal' => MMtrgSetup::getActiveDate(0, $modReff->created_at),
                            'jenis_kayu' => $modReff->jenis_kayu
                        ]);
                        $grades = array_filter($grades, function($val) { return $val->grade !== 'Output';});
                        $setup_ids = '(';
                        $index = 0;
                        foreach ($grades as $grade) {
                            if ($index !== count($grades) - 1) {
                                $setup_ids .= $grade->mtrg_setup_id . ', ';
                            } else {
                                $setup_ids .= $grade->mtrg_setup_id . ')';
                            }
                            $index++;
                        }
                        
                        $modDetail  = Yii::$app->db->createCommand("
                            SELECT 
                                mtrg_setup_id,grade,
                                SUM ( volume ) AS jml_volume 
                            FROM 
                                t_mtrg_in_out_detail 
                            WHERE 
                                t_mtrg_in_out_detail.mtrg_setup_id IN $setup_ids
                            GROUP BY 
                                mtrg_setup_id,grade
                        ")->queryAll();
                        if ($modDetail === null) {
                            throw new Exception("Data setup tidak di temukan");
                        }
                        
                        $output = 0;
                        foreach ($modDetail as $detail) {
                            $setup = MMtrgSetup::findOne(['mtrg_setup_id' => $detail['mtrg_setup_id'], 'grade' => $detail['grade']]); //
                            $input = MMtrgSetup::findOne(['tanggal' => MMtrgSetup::getActiveDate(0, $modReff->created_at), 'grade' => 'Input', 'jenis_kayu' => $modReff->jenis_kayu, 'kategori_proses' => MMtrgSetup::KATEGORI_REPAIR]);
                            $jml_input = TMtrgInOutDetail::find()->where(['mtrg_setup_id' => $input->mtrg_setup_id,'grade' => $detail['grade'] ])->sum('volume'); //

                            $output += $detail['jml_volume'];
                            $setup->jumlah_aktual = $detail['jml_volume'] ; // / $jml_input * 100;
                                // throw new Exception("Data output ".$output." jumlah aktual ".$setup->jumlah_aktual." jmlvol ".$detail['jml_volume']." jmlinput ".$jml_input ); exit;
                            if (!$setup->validate() || !$setup->save()) {
                                throw new Exception(array_values($setup->firstErrors)[0]);
                            }
                        }

                        $output_actual = MMtrgSetup::find()->where(['tanggal' => MMtrgSetup::getActiveDate(0, $modReff->created_at), 'grade' => 'Output', 'jenis_kayu' => $modReff->jenis_kayu, 'kategori_proses' => MMtrgSetup::KATEGORI_REPAIR])->one();
                        if (!empty($output_actual)) {
                                $output_actual->jumlah_aktual = $output;
                        
                            if (!$output_actual->validate() || !$output_actual->save()) {
                                throw new Exception(array_values($output_actual->firstErrors)[0]);
                            }
                        }
                    } else {
                        $modDetail = Yii::$app->db->createCommand("
                            SELECT 
                                mtrg_setup_id, 
                                SUM ( volume ) AS jml_volume 
                            FROM 
                                t_mtrg_in_out_detail 
                            WHERE 
                                mtrg_in_out_id = $modReff->mtrg_in_out_id 
                            GROUP BY 
                                mtrg_setup_id, 
                                grade
                        ")->queryAll();
                        if ($modDetail === null) {
                            throw new Exception("Data setup tidak di temukan");
                        }
                        foreach ($modDetail as $detail) {
                            $setup = MMtrgSetup::findOne(['mtrg_setup_id' => $detail['mtrg_setup_id']]);
                            $setup->jumlah_aktual += $detail['jml_volume'];
                            if (!$setup->validate() || !$setup->save()) {
                                throw new Exception(array_values($setup->firstErrors)[0]);
                            }
                        }
                    }
                }

                $transaction->commit();
                return $this->asJson([
                    'status' => true,
                    'message' => Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE,
                    'callback' => "$('.modal').each(function(i,e) {\$(e).modal('toggle')})"
                ]);
            } catch (Exception $exception) {
                $data['status']     = false;
                $data['message']    = $exception->getMessage();
                $transaction->rollback();
                return $this->asJson($data);
            }
        }
        return $this->renderAjax('approveReason', compact('model'));
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionRejectReason($id)
    {
        $model      = TApproval::findOne($id);
        if (Yii::$app->request->isPost) {
            $transaction    = Yii::$app->db->beginTransaction();
            $alasan         = $_POST['TApproval']['keterangan'];
            try {
                $model->status  = TApproval::STATUS_REJECTED;
                $model->tanggal_approve = date('Y-m-d');
                $model->approved_by = Yii::$app->user->identity->pegawai->pegawai_id;
                if (!$model->validate() || !$model->save()) {
                    throw new Exception(array_values($model->firstErrors)[0]);
                }

                $modReff = TMtrgInOut::findOne(['kode' => $model->reff_no]);
                $modReff->status_approval = TApproval::STATUS_REJECTED;
                $reasons = Json::decode($modReff->reason_approval);
                $reasons = array_map(static function ($value) use ($model, $alasan) {
                    return $value['level'] === $model->level
                        ? [
                            'assigned_to' => $model->assigned_to,
                            'level' => $model->level,
                            'status' => TApproval::STATUS_REJECTED,
                            'tanggal_approve' => date('Y-m-d H:i:s'),
                            'reason' => $alasan
                        ]
                        : $value;
                }, $reasons);
                $modReff->reason_approval = Json::encode($reasons);

                // if ($model->level < 3) {
                //     for ($level = $model->level + 1; $level <= 3; $level++) {
                //         $approval = TApproval::findOne(['reff_no' => $modReff->kode, 'level' => $level]);
                //         $approval->status = TApproval::STATUS_REJECTED;
                //         $approval->tanggal_approve = date('Y-m-d');
                //         $approval->approved_by = $model->assigned_to;
                //         if (!$approval->validate() || !$approval->save()) {
                //             throw new Exception(array_values($approval->firstErrors)[0]);
                //         }

                //         $reasons = array_map(static function ($value) use ($approval, $model) {
                //             return $value['level'] === $approval->level
                //                 ? [
                //                     'assigned_to' => $approval->assigned_to,
                //                     'level' => $approval->level,
                //                     'status' => TApproval::STATUS_REJECTED,
                //                     'tanggal_approve' => date('Y-m-d H:i:s'),
                //                     'reason' => 'Rejected by ' . $model->assignedTo->pegawai_nama
                //                 ]
                //                 : $value;
                //         }, $reasons);
                //     }
                // }

                // $modReff->reason_approval = Json::encode($reasons);

                if (!$modReff->validate() || !$modReff->save()) {
                    throw new Exception(array_values($modReff->firstErrors)[0]);
                }

                $transaction->commit();
                return $this->asJson([
                    'status' => true,
                    'message' => Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE,
                    'callback' => "$('.modal').each(function(i,e) {\$(e).modal('toggle')})"
                ]);
            } catch (Exception $exception) {
                $data['status']     = false;
                $data['message']    = $exception->getMessage();
                $transaction->rollback();
                return $this->asJson($data);
            }
        }
        return $this->renderAjax('rejectReason', compact('model'));
    }

    public function actionIndexrotary()
    {
        if (Yii::$app->request->get('dt') === 'table-master') {
            $param['table']     = ViewApproval::tableName();
            $param['pk']        = "approval_id";
            $param['column']    = [
                'approval_id',
                't_mtrg_rotary.kode',
                [
                    'col_name'  => 'tanggal_berkas',
                    'formatter' => 'formatDateTimeForUser'
                ],
                $param['table'] . '.status',
                't_mtrg_rotary.shift',
                'assigned_nama',
                [
                    'col_name'  => 'tanggal',
                    'formatter' => 'formatDateTimeForUser'
                ],
                'approved_by_nama',
                $param['table'] . '.created_at',
                [
                    'col_name'  => 'tanggal_approve',
                    'formatter' => 'formatDateTimeForUser'
                ],
            ];

            $param['join']      = " join t_mtrg_rotary on view_approval.reff_no = t_mtrg_rotary.kode";
            if (isset($_GET['status']) && $_GET['status'] === TApproval::STATUS_NOT_CONFIRMATED) {
                $param['where'] = "view_approval.status = 'Not Confirmed'";
                $param['order'] = "created_at ASC, level ASC";
            } else {
                $param['where'] = "view_approval.status <> 'Not Confirmed'";
                $param['order'] = "created_at DESC, level ASC";
            }
            if (Yii::$app->user->identity->user_group_id !== Params::USER_GROUP_ID_SUPER_USER) {
                $param['where'] .= "AND assigned_to = " . Yii::$app->user->identity->pegawai_id . "  ";
            }

            return Json::encode(SSP::complex($param));
        }
        return $this->render('index-rotary');
    }

    /**
     * @param $id
     * @return string|void
     */
    public function actionInforotary($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = TApproval::findOne($id);
            return $this->renderAjax('info-rotary', ['model' => $model]);
        }
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionRejectReasonRotary($id)
    {
        $model      = TApproval::findOne($id);
        if (Yii::$app->request->isPost) {
            $transaction    = Yii::$app->db->beginTransaction();
            $alasan         = $_POST['TApproval']['keterangan'];
            try {
                $model->status  = TApproval::STATUS_REJECTED;
                $model->tanggal_approve = date('Y-m-d');
                $model->approved_by = Yii::$app->user->identity->pegawai->pegawai_id;
                if (!$model->validate() || !$model->save()) {
                    throw new Exception(array_values($model->firstErrors)[0]);
                }

                $modReff = TMtrgRotary::findOne(['kode' => $model->reff_no]);
                $modReff->status_approval = TApproval::STATUS_REJECTED;

                $reasons = Json::decode($modReff->reason_approval);
                $reasons = array_map(static function ($value) use ($model, $alasan) {
                    return $value['level'] === $model->level
                        ? [
                            'assigned_to' => $model->assigned_to,
                            'level' => $model->level,
                            'status' => TApproval::STATUS_REJECTED,
                            'tanggal_approve' => date('Y-m-d H:i:s'),
                            'reason' => $alasan
                        ]
                        : $value;
                }, $reasons);
                $modReff->reason_approval = Json::encode($reasons);

                // if ($model->level < 3) {
                //     for ($level = $model->level + 1; $level <= 3; $level++) {
                //         $approval = TApproval::findOne(['reff_no' => $modReff->kode, 'level' => $level]);
                //         $approval->status = TApproval::STATUS_REJECTED;
                //         $approval->tanggal_approve = date('Y-m-d');
                //         $approval->approved_by = $model->assigned_to;
                //         if (!$approval->validate() || !$approval->save()) {
                //             throw new Exception(array_values($approval->firstErrors)[0]);
                //         }

                //         $reasons = array_map(static function ($value) use ($approval, $model) {
                //             return $value['level'] === $approval->level
                //                 ? [
                //                     'assigned_to' => $approval->assigned_to,
                //                     'level' => $approval->level,
                //                     'status' => TApproval::STATUS_REJECTED,
                //                     'tanggal_approve' => date('Y-m-d H:i:s'),
                //                     'reason' => 'Rejected by ' . $model->assignedTo->pegawai_nama
                //                 ]
                //                 : $value;
                //         }, $reasons);
                //     }
                // }

                // $modReff->reason_approval = Json::encode($reasons);

                if (!$modReff->validate() || !$modReff->save()) {
                    throw new Exception(array_values($modReff->firstErrors)[0]);
                }

                $transaction->commit();
                return $this->asJson([
                    'status' => true,
                    'message' => Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE,
                    'callback' => "$('.modal').each(function(i,e) {\$(e).modal('toggle')})"
                ]);
            } catch (Exception $exception) {
                $data['status']     = false;
                $data['message']    = $exception->getMessage();
                $transaction->rollback();
                return $this->asJson($data);
            }
        }
        return $this->renderAjax('reject-reason-rotary', compact('model'));
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     */
    public function actionApproveReasonRotary($id)
    {
        $model      = TApproval::findOne($id);
        if (Yii::$app->request->isPost) {
            $transaction    = Yii::$app->db->beginTransaction();
            $alasan         = $_POST['TApproval']['keterangan'];
            try {
                $model->status  = TApproval::STATUS_APPROVED;
                $model->tanggal_approve = date('Y-m-d');
                $model->approved_by = Yii::$app->user->identity->pegawai->pegawai_id;
                if (!$model->validate() || !$model->save()) {
                    throw new Exception(array_values($model->firstErrors)[0]);
                }

                $modReff = TMtrgRotary::findOne(['kode' => $model->reff_no]);
                // if ($model->level === 3) { //kebijakan awal
                if ($model->level === 1) { //kebijakan per tanggal 06/04/2024 hanya ada satu level approval
                    $modReff->status_approval = TApproval::STATUS_APPROVED;
                }

                $reasons = array_map(static function ($value) use ($model, $alasan) {
                    return $value['level'] === $model->level
                        ? [
                            'assigned_to' => $model->assigned_to,
                            'level' => $model->level,
                            'status' => TApproval::STATUS_APPROVED,
                            'tanggal_approve' => date('Y-m-d H:i:s'),
                            'reason' => $alasan
                        ]
                        : $value;
                }, Json::decode($modReff->reason_approval));
                $modReff->reason_approval = Json::encode($reasons);

                if (!$modReff->validate() || !$modReff->save()) {
                    throw new Exception(array_values($modReff->firstErrors)[0]);
                }

                if ($modReff->status_approval === TApproval::STATUS_APPROVED) {
                    $setup      = MMtrgSetup::findOne(['mtrg_setup_id' => $modReff->mtrg_setup_id]);
                    $modDetail  = TMtrgRotaryDetail::find()->where(['mtrg_rotary_id' => $modReff->mtrg_rotary_id]);
                    $setup->jumlah_aktual += $modDetail->sum('volume');
                    if (!$setup->validate() || !$setup->save()) {
                        throw new Exception(array_values($setup->firstErrors)[0]);
                    }


                    $setup_jam  = MMtrgSetup::findOne([
                        'grade' => 'Jam Jalan',
                        'kategori_proses' => MMtrgSetup::KATEGORI_ROTARY,
                        'jenis_proses' => MMtrgSetup::INPUT,
                        'tanggal' => MMtrgSetup::getActiveDate(0, $modReff->created_at),
                        'jenis_kayu' => $modReff->jenis_kayu
                    ]);

                    //                    PERHITUNGAN JAM JALAN LAMA

                    //                    $rotary_jam = TMtrgRotary::find()->where(['mtrg_setup_id' => $modReff->mtrg_setup_id])->orderBy(['tanggal' => SORT_DESC])->one();
                    //                    $startDate  = MMtrgSetup::getActiveDate();
                    //                    $startDate .= " 07:00:00";
                    //                    if($rotary_jam !== null) {
                    //                        $jam_jalan += round(abs(strtotime($rotary_jam->tanggal) - strtotime($startDate)) / (60*60), 1);
                    //                    }
                    //
                    //                    $setup_jam->jumlah_aktual = $jam_jalan;
                    //                    if(date('H:i:s') > '13:00:00') {
                    //                        --$setup_jam->jumlah_aktual;
                    //                    }
                    //
                    //                    if(date('H:i:s') > '01:00:00' && date('H:i:s') < '09:00:00') {
                    //                        $setup_jam->jumlah_aktual -= 2;
                    //                    }

                    //                    $setup_jam->jumlah_aktual += $modReff->jam_jalan / 60;
                    $jam = TMtrgRotary::find()
                        ->where(['between', 'tanggal', MMtrgSetup::getActiveDate(0, $modReff->created_at) . ' 09:00:00', MMtrgSetup::getActiveDate(1, $modReff->created_at) . ' 08:59:59'])
                        ->andWhere(['jenis_kayu' => $modReff->jenis_kayu])
                        ->sum('jam_jalan');
                    $setup_jam->jumlah_aktual = $jam ? $jam / 60 : 0;
                    if (!$setup_jam->validate() || !$setup_jam->save()) {
                        throw new Exception(array_values($setup->firstErrors)[0]);
                    }

                    $perjam  = MMtrgSetup::findOne([
                        'grade' => 'Input / Jam',
                        'kategori_proses' => MMtrgSetup::KATEGORI_ROTARY,
                        'jenis_kayu' => $modReff->jenis_kayu,
                        'tanggal' => MMtrgSetup::getActiveDate(0, $modReff->created_at)
                    ]);
                    if ($perjam !== null) {
                        $perjam->jumlah_aktual = $setup->jumlah_aktual / $setup_jam->jumlah_aktual;
                        //                        $perjam->plan_harian = $setup->plan_harian / $setup_jam->plan_harian;
                        $perjam->satuan_harian = 'm3';
                        if (!$perjam->validate() || !$perjam->save()) {
                            throw new Exception(array_values($perjam->firstErrors)[0]);
                        }
                    }
                }

                $transaction->commit();
                return $this->asJson([
                    'status' => true,
                    'message' => Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE,
                    'callback' => "$('.modal').each(function(i,e) {\$(e).modal('toggle')})"
                ]);
            } catch (Exception $exception) {
                $data['status']     = false;
                $data['message']    = $exception->getMessage();
                $transaction->rollback();
                return $this->asJson($data);
            }
        }
        return $this->renderAjax('approveReason', compact('model'));
    }
}
