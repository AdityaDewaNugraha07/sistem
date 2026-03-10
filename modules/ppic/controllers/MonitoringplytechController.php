<?php

namespace app\modules\ppic\controllers;

use app\components\DeltaFormatter;
use app\components\DeltaGenerator;
use app\components\Params;
use app\components\SSP;
use app\controllers\DeltaBaseController;
use app\models\MDefaultValue;
use app\models\MMtrgSetup;
use app\models\TApproval;
use app\models\TMtrgInOut;
use app\models\TMtrgInOutDetail;
use Yii;
use yii\db\Exception;
use yii\helpers\Json;

class MonitoringplytechController extends DeltaBaseController
{
    public function actionIndex()
    {
        $model = new TMtrgInOut();
        $model->kode = 'AUTO GENERATE';
        $model->tanggal_kupas = date('d/m/Y');
        $model->status_in_out = MMtrgSetup::OUTPUT;
        $model->kategori_proses = MMtrgSetup::KATEGORI_PLYTECH;
        $model->status_approval = TApproval::STATUS_NOT_CONFIRMATED;
        $model->disiapkan = Yii::$app->user->identity->pegawai->pegawai_id;
        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $request = Yii::$app->request->post('TMtrgInOut');
                $model->attributes = $request;
                
                //untuk edit
                if(isset($request['mtrg_in_out_id'])) {
                    $model = TMtrgInOut::findOne(['mtrg_in_out_id' => $request['mtrg_in_out_id']]);
                    $model->status_approval = TApproval::STATUS_NOT_CONFIRMATED;
                    $model->attributes = $request; // load ulang
                    TMtrgInOutDetail::deleteAll(['mtrg_in_out_id' => $request['mtrg_in_out_id']]);
                    TApproval::deleteAll(['reff_no' => $model->kode]);
                }else {
                    $model->kode = DeltaGenerator::kodeMonitoringInOut('PLYTECH');
                }
                
                // $approvers = [
                //     ['assigned_to' => $model->diperiksa, 'level' => 1, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => ''],
                //     ['assigned_to' => $model->disetujui, 'level' => 2, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => ''],
                //     ['assigned_to' => $model->diketahui, 'level' => 3, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => '']
                // ];
                // kebijakan per tanggal 06/04/2024 approval hanya level 1 saja
                $approvers = [
                    ['assigned_to' => $model->diperiksa, 'level' => 1, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => '']
                ];

                $model->tanggal_kupas = DeltaFormatter::formatDateTimeForDb($model->tanggal_kupas);
                $jam = $_POST['jam'] === '' ? date('H:i:s') : $_POST['jam'];
                if($jam > '8:51:00' && $jam < '9:00:01'){ //kondisikan setiap input tanggal dan jam 8.51.00 < 09.00.01
                    $model->tanggal_produksi = DeltaFormatter::formatDateTimeForDb($_POST['tanggal']) . ' 09:00:02' ;
                }else{
                    $model->tanggal_produksi = DeltaFormatter::formatDateTimeForDb($_POST['tanggal']) . ' ' . $jam;
                }
                $model->reason_approval = Json::encode($approvers);

                if (!$model->validate() || !$model->save()) {
                    throw new Exception(array_values($model->firstErrors)[0]);
                }

                foreach (Yii::$app->request->post('details') as $detail) {
                    $modDetail = new TMtrgInOutDetail();
                    $modDetail->attributes = $detail;
                    $modDetail->mtrg_in_out_id = $model->mtrg_in_out_id;
                    $modDetail->patching = (int) $modDetail->patching;

                    $reff = [];
                    $grades = array_map(function($val) { return $val->value; }, MDefaultValue::findAll(['type' => 'mtrg-grade-plytech-output']));
                    foreach ($grades as $grade) {
                        $setup_volume = MMtrgSetup::findOne([
                            'tanggal' => MMtrgSetup::getActiveDate(),
                            'kategori_proses' => MMtrgSetup::KATEGORI_PLYTECH,
                            'jenis_kayu' => $model->jenis_kayu,
                            'grade' => $grade,
                            'jenis_proses' => MMtrgSetup::OUTPUT
                        ]);

                        if($setup_volume !== null) {
                            $reff = Json::decode($modDetail->grade);
                            $reff[$grade] = $setup_volume->mtrg_setup_id;
                            $modDetail->grade = Json::encode($reff);
                        }else {
                            $last_setup = MMtrgSetup::find()->where([
                                'kategori_proses' => MMtrgSetup::KATEGORI_PLYTECH,
                                'jenis_kayu' => $model->jenis_kayu,
                                'grade' => $grade,
                                'jenis_proses' => MMtrgSetup::OUTPUT
                            ])->orderBy(['tanggal' => SORT_DESC])->one();

                            $setup = new MMtrgSetup();
                            $setup->attributes = $last_setup->attributes;
                            $setup->tanggal    = date('Y-m-d');
                            $setup->jumlah_aktual = 0;
                            if($setup->validate() && $setup->save()) {
                                $reff[$grade] = $setup->mtrg_setup_id;
                                $modDetail->grade = Json::encode($reff);
                            }
                        }
                    }

                    if (!$modDetail->validate() || !$modDetail->save()) {
                        throw new Exception(array_values($modDetail->firstErrors)[0]);
                    }
                }

                foreach ($approvers as $approver) {
                    $approval = new TApproval();
                    $approval->attributes = $approver;
                    $approval->reff_no = $model->kode;
                    $approval->tanggal_berkas = date('Y-m-d');
                    if (!$approval->validate() || !$approval->save()) {
                        throw new Exception(array_values($approval->firstErrors)[0]);
                    }
                }

                $transaction->commit();
                return $this->asJson(['status' => true, 'message' => Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE]);
            } catch (Exception $exception) {
                return $this->asJson(['status' => false, 'message' => $exception->getMessage()]);
            }
        }
        return $this->render('index', compact('model'));
    }

    public function actionModalhistory($type = null)
    {
        if(Yii::$app->request->isPost) {
            $param['table']     = TMtrgInOut::tableName();
            $param['pk']        = "mtrg_in_out_id";
            $param['column']    = [
                't_mtrg_in_out.mtrg_in_out_id',
                [
                    'col_name'  => 'tanggal_kupas',
                    'formatter' => 'formatDateTimeForUser'
                ],
                [
                    'col_name'  => 'tanggal_produksi',
                    'formatter' => 'formatDateTimeForUser'
                ],
                't_mtrg_in_out.kode',
                't_mtrg_in_out.shift',
                't_mtrg_in_out.kategori_proses',
                't_mtrg_in_out.jenis_kayu',
                't_mtrg_in_out.status_approval',
            ];
            $param['order'] = "created_at DESC";
            $param['where'] = "status_in_out = '{$_POST['type']}' AND kategori_proses = '". MMtrgSetup::KATEGORI_PLYTECH ."'";
            return Json::encode(SSP::complex($param));
        }

        return $this->renderAjax('partials/modal-history', compact('type'));
    }

    public function actionShow($id)
    {
        $model      = TMtrgInOut::findOne(['mtrg_in_out_id' => $id]);
        $modDetail  = TMtrgInOutDetail::findAll(['mtrg_in_out_id' => $model->mtrg_in_out_id]);
        return $this->asJson(['master' => $model, 'details' => $modDetail]);
    }

    public function actionExtractdetailoutput()
    {
        if(Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $result = [];
            if(isset($data['detail'])) {
                $total_pcs  = array_sum(array_column($data['detail'], 'pcs'));
                foreach ($data['detail'] as $detail) {
                    if($detail['volume'] > 0) {
                        $result[] = [
                            'unit' => $data['TMtrgInOutDetail']['unit'],
                            'patching' => round($data['TMtrgInOutDetail']['patching'] * $detail['pcs'] / $total_pcs),
                            'size' => MDefaultValue::findOne(['name' => $detail['size'], 'type' => 'size'])->value,
                            'tebal' => $detail['tebal'],
                            'pcs' => $detail['pcs'],
                            'volume' => $detail['volume'],
                        ];
                    }
                }
            }else {
                $result = $data['TMtrgInOutDetail'];
                $result['id'] = (int)$_POST['id'];
            }

            return $this->asJson($result);
        }

        return null;
    }

    public function actionInsertoutputdetail()
    {
        return $this->renderAjax('partials/modal-output-detail-mask', ['modDetail' => new TMtrgInOutDetail()]);
    }

    public function actionUpdateoutputdetail()
    {
        $modDetail  = new TMtrgInOutDetail();
        $request    = Yii::$app->request->get();
        $id         = null;
        $isUpdate   = false;
        if(isset($request['id'])) {
            $isUpdate   = true;
            $id         = $request['id'];

            if(isset($request['mtrg_in_out_detail_id']) && $request['mtrg_in_out_detail_id'] !== '') {
                $modDetail = TMtrgInOutDetail::findOne(['mtrg_in_out_detail_id' => $request['mtrg_in_out_detail_id']]);
            }else {
                $modDetail->attributes = $request;
            }
        }
        return $this->renderAjax('partials/modal-output-detail', compact('modDetail', 'id', 'isUpdate'));
    }
}