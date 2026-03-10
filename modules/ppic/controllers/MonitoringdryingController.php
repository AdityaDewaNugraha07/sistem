<?php

namespace app\modules\ppic\controllers;

use app\components\DeltaFormatter;
use app\components\DeltaGenerator;
use app\components\Params;
use app\components\SSP;
use app\controllers\DeltaBaseController;
use app\models\MMtrgSetup;
use app\models\TApproval;
use app\models\TMtrgInOut;
use app\models\TMtrgInOutDetail;
use Yii;
use yii\db\Exception;
use yii\helpers\Json;

class MonitoringdryingController extends DeltaBaseController
{
    public function actionIndex()
    {
        $model = new TMtrgInOut();
        $model->kode = 'AUTO GENERATE';
        $model->tanggal_kupas = date('d/m/Y');
        $model->status_in_out = MMtrgSetup::OUTPUT;
        $model->kategori_proses = MMtrgSetup::KATEGORI_DRYING;
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
                    $model->kode = DeltaGenerator::kodeMonitoringInOut('DRYING');
                }
                
                // $approvers = [
                //     ['assigned_to' => $model->diperiksa, 'level' => 1, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => ''],
                //     ['assigned_to' => $model->disetujui, 'level' => 2, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => ''],
                //     ['assigned_to' => $model->diketahui, 'level' => 3, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => '']
                // ];
                // berlaku kebijakan per tanggal 06/04/2024 approval hanya sampai level 1
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

                // setup
                $setups = MMtrgSetup::findAll(['kategori_proses' => MMtrgSetup::KATEGORI_DRYING, 'tanggal' => MMtrgSetup::getActiveDate()]);
                if(count($setups) < 1 && date('Y-m-d H:i:s') <= date('Y-m-d') . ' 09:00:00') {
                    throw new Exception("Gagal memproses data<br>Pastikan proses input dilakukan setelah jam 09:00:01 WIB");
                }
                if(count($setups) < 1) {
                    $last_setup_date = MMtrgSetup::find()->where(['kategori_proses' => MMtrgSetup::KATEGORI_DRYING])->orderBy(['tanggal' => SORT_DESC])->one();
                    if($last_setup_date !== null) {
                        $last_setup = MMtrgSetup::findAll(['kategori_proses' => MMtrgSetup::KATEGORI_DRYING, 'tanggal' => $last_setup_date->tanggal]);
                        foreach ($last_setup as $old_setup) {
                            $new_setup = new MMtrgSetup();
                            $new_setup->attributes = $old_setup->attributes;
                            $new_setup->tanggal = date('Y-m-d');
                            $new_setup->jumlah_aktual = 0;

                            if(!$new_setup->validate() || !$new_setup->save()) {
                                throw new Exception('New Setup Error: ' . array_values($new_setup->firstErrors)[0]);
                            }
                        }
                    }else {
                        throw new Exception("Belum pernah ada setup up yang dibuat. <br>Mohon buat setup DRYING untuk pertama kalinya");
                    }
                }
                // end setup

                foreach (Yii::$app->request->post('details') as $detail) {
                    $modDetail = new TMtrgInOutDetail();
                    $modDetail->attributes = $detail;
                    $modDetail->mtrg_in_out_id = $model->mtrg_in_out_id;

                    $drying = MMtrgSetup::findOne([
                        'tanggal' => MMtrgSetup::getActiveDate(),
                        'kategori_proses' => MMtrgSetup::KATEGORI_DRYING,
                        'jenis_kayu' => $model->jenis_kayu,
                        'grade' => $detail['grade'],
                        'jenis_proses' => MMtrgSetup::OUTPUT
                    ]);
                    if($drying !== null) {
                        $modDetail->mtrg_setup_id = $drying->mtrg_setup_id;
                    }else {
                        throw new Exception('Detail Error: setup tidak di temukan.<br>Pastikan "Setup Drying" sudah ada');
                    }

                    if (!$modDetail->validate() || !$modDetail->save()) {
                        throw new Exception(array_values($model->firstErrors)[0]);
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


    public function actionModalhistoryoutput($type = null)
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
            $param['where'] = "status_in_out = '{$_POST['type']}' AND kategori_proses = 'DRYING'";
            return Json::encode(SSP::complex($param));
        }

        return $this->renderAjax('partials/modal-history-output', compact('type'));
    }

    public function actionShow($id)
    {
        $model      = TMtrgInOut::findOne(['mtrg_in_out_id' => $id]);
        $modDetail  = TMtrgInOutDetail::findAll(['mtrg_in_out_id' => $model->mtrg_in_out_id]);
        return $this->asJson(['master' => $model, 'details' => $modDetail]);
    }

    public function actionModalOutputDetail()
    {
        $modDetail = new TMtrgInOutDetail();
        $modDetail->size = 1.67445;
        return $this->renderAjax('partials/modal-output-detail-mask', compact('modDetail'));
    }

    public function actionExtractdetailoutput()
    {
        if(Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $result = [];
            if(isset($data['detail'])) {
                foreach ($data['detail'] as $detail) {
                    if($detail['volume'] > 0) {
                        $result[] = [
                            'unit' => $data['TMtrgInOutDetail']['unit'],
                            'size' => $data['TMtrgInOutDetail']['size'],
                            'grade' => $detail['grade'],
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