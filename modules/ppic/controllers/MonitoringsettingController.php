<?php

namespace app\modules\ppic\controllers;

use Yii;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Response;
use app\components\SSP;
use app\models\TApproval;
use app\components\Params;
use app\models\MMtrgSetup;
use app\models\TMtrgInOut;
use app\models\TMtrgInOutDetail;
use app\components\DeltaFormatter;
use app\components\DeltaGenerator;
use app\controllers\DeltaBaseController;

class MonitoringsettingController extends DeltaBaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string|Response
     */
    public function actionInput()
    {
        $model = new TMtrgInOut();
        $model->kode = 'AUTO GENERATE';
        $model->status_in_out = MMtrgSetup::INPUT;
        $model->kategori_proses = MMtrgSetup::KATEGORI_SETTING;
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
                    $model->kode = DeltaGenerator::kodeMonitoringInOut('SETTING');
                }
                
                // $approvers = [
                //     ['assigned_to' => $model->diperiksa, 'level' => 1, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => ''],
                //     ['assigned_to' => $model->disetujui, 'level' => 2, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => ''],
                //     ['assigned_to' => $model->diketahui, 'level' => 3, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => '']
                // ];
                // kebijakan per tanggal 06/04/2024 papproval hanya level 1 saja
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
                $setups = MMtrgSetup::findAll(['kategori_proses' => MMtrgSetup::KATEGORI_SETTING, 'tanggal' => MMtrgSetup::getActiveDate()]);
                if(count($setups) < 1 && date('Y-m-d H:i:s') <= date('Y-m-d') . ' 09:00:00') {
                    throw new Exception("Gagal memproses data<br>Pastikan proses input dilakukan setelah jam 09:00:01 WIB");
                }
                if($setups < 1) {
                    $last_setup_date = MMtrgSetup::find()->where(['kategori_proses' => MMtrgSetup::KATEGORI_SETTING])->orderBy(['tanggal' => SORT_DESC])->one();
                    if($last_setup_date !== null) {
                        $last_setup = MMtrgSetup::findAll(['kategori_proses' => MMtrgSetup::KATEGORI_SETTING, 'tanggal' => $last_setup_date->tanggal]);
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
                        throw new Exception("Belum pernah ada setup up yang dibuat. <br>Mohon buat setup SETTING untuk pertama kalinya");
                    }
                }
                // end setup

                foreach (Yii::$app->request->post('details') as $detail) {
                    $modDetail = new TMtrgInOutDetail();
                    $modDetail->attributes = $detail;
                    $modDetail->mtrg_in_out_id = $model->mtrg_in_out_id;

                    $prev_setup = MMtrgSetup::findOne([
                        'tanggal' => MMtrgSetup::getActiveDate(),
                        'kategori_proses' => MMtrgSetup::KATEGORI_SETTING,
                        'jenis_kayu' => $model->jenis_kayu,
                        'grade' => 'Input',
                        'jenis_proses' => MMtrgSetup::INPUT
                    ]);

                    if($prev_setup !== null) {
                        $modDetail->mtrg_setup_id = $prev_setup->mtrg_setup_id;
                    }else {
                        $last_setup = MMtrgSetup::find()->where([
                            'kategori_proses' => MMtrgSetup::KATEGORI_SETTING,
                            'jenis_kayu' => $model->jenis_kayu,
                            'grade' => 'Input',
                            'jenis_proses' => MMtrgSetup::INPUT
                        ])->orderBy(['tanggal' => SORT_DESC])->one();
                        $setup = new MMtrgSetup();
                        $setup->attributes = $last_setup->attributes;
                        $setup->tanggal    = date('Y-m-d');
                        $setup->jumlah_aktual = 0;
                        if($setup->validate() && $setup->save()) {
                            $modDetail->mtrg_setup_id = $setup->mtrg_setup_id;
                        }
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
        return $this->renderAjax('partials/input', compact('model'));
    }

    public function actionUpdateinputdetail()
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
        return $this->renderAjax('partials/modal-input-detail', compact('modDetail', 'id', 'isUpdate'));
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
            $param['where'] = "status_in_out = '{$_POST['type']}' AND kategori_proses = '". MMtrgSetup::KATEGORI_SETTING ."'";
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

    /**
     * @return string|Response
     */
    public function actionOutput()
    {
        $model = new TMtrgInOut();
        $model->kode = 'AUTO GENERATE';
        $model->status_in_out = MMtrgSetup::OUTPUT;
        $model->kategori_proses = MMtrgSetup::KATEGORI_SETTING;
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
                    $model->kode = DeltaGenerator::kodeMonitoringInOut('SETTING');
                }
                
                // $approvers = [
                //     ['assigned_to' => $model->diperiksa, 'level' => 1, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => ''],
                //     ['assigned_to' => $model->disetujui, 'level' => 2, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => ''],
                //     ['assigned_to' => $model->diketahui, 'level' => 3, 'status' => TApproval::STATUS_NOT_CONFIRMATED, 'tanggal_approve' => null, 'reason' => '']
                // ];
                // kebijakan per tanggal 06/04/2024 papproval hanya level 1 saja
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
                $setups = MMtrgSetup::findAll(['kategori_proses' => MMtrgSetup::KATEGORI_SETTING, 'tanggal' => MMtrgSetup::getActiveDate()]);
                if(count($setups) < 1 && date('Y-m-d H:i:s') <= date('Y-m-d') . ' 09:00:00') {
                    throw new Exception("Gagal memproses data<br>Pastikan proses input dilakukan setelah jam 09:00:01 WIB");
                }
                if($setups < 1) {
                    $last_setup_date = MMtrgSetup::find()->where(['kategori_proses' => MMtrgSetup::KATEGORI_SETTING])->orderBy(['tanggal' => SORT_DESC])->one();
                    if($last_setup_date !== null) {
                        $last_setup = MMtrgSetup::findAll(['kategori_proses' => MMtrgSetup::KATEGORI_SETTING, 'tanggal' => $last_setup_date->tanggal]);
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
                        throw new Exception("Belum pernah ada setup up yang dibuat. <br>Mohon buat setup SETTING untuk pertama kalinya");
                    }
                }
                // end setup

                foreach (Yii::$app->request->post('details') as $detail) {
                    $modDetail = new TMtrgInOutDetail();
                    $modDetail->attributes = $detail;
                    $modDetail->mtrg_in_out_id = $model->mtrg_in_out_id;

                    $prev_setup = MMtrgSetup::findOne([
                        'tanggal' => MMtrgSetup::getActiveDate(),
                        'kategori_proses' => MMtrgSetup::KATEGORI_SETTING,
                        'jenis_kayu' => $model->jenis_kayu,
                        'grade' => $detail['grade'],
                        'jenis_proses' => MMtrgSetup::OUTPUT
                    ]);

                    if($prev_setup !== null) {
                        $modDetail->mtrg_setup_id = $prev_setup->mtrg_setup_id;
                    }else {
                        $last_setup = MMtrgSetup::find()->where([
                            'kategori_proses' => MMtrgSetup::KATEGORI_SETTING,
                            'jenis_kayu' => $model->jenis_kayu,
                            'grade' => $detail['grade'],
                            'jenis_proses' => MMtrgSetup::OUTPUT
                        ])->orderBy(['tanggal' => SORT_DESC])->one();
                        $setup = new MMtrgSetup();
                        $setup->attributes = $last_setup->attributes;
                        $setup->tanggal    = date('Y-m-d');
                        $setup->jumlah_aktual = 0;
                        if($setup->validate() && $setup->save()) {
                            $modDetail->mtrg_setup_id = $setup->mtrg_setup_id;
                        }
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
        return $this->renderAjax('partials/output', compact('model'));
    }

    public function actionModaloutputdetail()
    {
        $modDetail = new TMtrgInOutDetail();
        $modDetail->size = 1.67445;
        $jenis_kayu = $_GET['jenis_kayu'];
        return $this->renderAjax('partials/modal-output-detail-mask', compact('modDetail', 'jenis_kayu'));
    }

    public function actionExtractdetail()
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