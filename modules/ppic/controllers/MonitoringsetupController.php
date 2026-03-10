<?php

namespace app\modules\ppic\controllers;

use app\components\DeltaFormatter;
use app\components\Params;
use app\components\SSP;
use app\controllers\DeltaBaseController;
use app\models\MDefaultValue;
use app\models\MMtrgSetup;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class MonitoringsetupController extends DeltaBaseController
{
    public function actionIndex()
    {
        $model = new MMtrgSetup();
        $model->jumlah_aktual = 0;
        $model->tanggal = date('d/m/Y');
        if(Yii::$app->request->isPost) {
            $mtrg_setup_id = Yii::$app->request->post('mtrg_setup_id');
            if($mtrg_setup_id !== null) {
                $model = MMtrgSetup::findOne(['mtrg_setup_id' => $mtrg_setup_id]);
            }
            $model->attributes = Yii::$app->request->post('MMtrgSetup');
            $is_duplicate = MMtrgSetup::findOne([
                'tanggal' => $model->tanggal,
                'jenis_proses' => $model->jenis_proses,
                'kategori_proses' => $model->kategori_proses,
                'jenis_kayu' => $model->jenis_kayu,
                'grade' => $model->grade,
            ]);
            if($is_duplicate !== null && $mtrg_setup_id === null) {
                Yii::$app->session->setFlash('error', 'Setup sudah pernah di buat. silahkan di cek terlebih dahulu');
                return $this->redirect(Yii::$app->homeUrl . '/ppic/monitoringsetup');
            }

            if($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                return $this->redirect(Yii::$app->homeUrl . '/ppic/monitoringsetup');
            }

            Yii::$app->session->setFlash('error', array_values($model->firstErrors)[0]);
        }
        return $this->render('index', compact('model'));
    }

    public function actionDatatable()
    {
        $parameter = Yii::$app->request->get();
        $param['table']     = MMtrgSetup::tableName();
        $param['pk']        = "mtrg_setup_id";
        $param['column']    = [
            [
                'col_name'  => 'tanggal',
                'formatter' => 'formatDateTimeForUser'
            ],
            'm_mtrg_setup.jenis_proses',
            'm_mtrg_setup.grade',
            'm_mtrg_setup.kategori_proses',
            'm_mtrg_setup.jenis_kayu',
            'm_mtrg_setup.plan_harian',
            'm_mtrg_setup.jumlah_aktual',
            'm_mtrg_setup.satuan_harian',
            'm_mtrg_setup.sequence',
            'm_mtrg_setup.mtrg_setup_id',
        ];
        $param['where'] = "tanggal = '" . DeltaFormatter::formatDateTimeForDb($parameter['tanggal']) . "'";
        if($parameter['kategori_proses']) {
            $param['where'] .= " AND kategori_proses = '" . $parameter['kategori_proses'] . "'";
        }

        if($parameter['jenis_proses']) {
            $param['where'] .= " AND jenis_proses = '" . $parameter['jenis_proses'] . "'";
        }

        if($parameter['jenis_kayu']) {
            $param['where'] .= " AND jenis_kayu = '" . $parameter['jenis_kayu'] . "'";
        }

        if($parameter['grade']) {
            $param['where'] .= " AND grade = '" . $parameter['grade'] . "'";
        }
        $param['order'] = "created_at DESC";
        return Json::encode(SSP::complex($param));
    }


    public function actionGetgrade($kategori, $proses)
    {
        if ($kategori === 'undefined') {
            return $this->asJson(ArrayHelper::map(
                MDefaultValue::find()
                    ->where(['like', 'type', 'mtrg-grade-'])
                    ->select(['name', 'value'])
                    ->groupBy(['value', 'name'])
                    ->all(),
                'value',
                'name'
            ));
        }

        $kategori = strtolower(str_replace(" ", "-", $kategori));
        $proses = strtolower(str_replace(" ", "-", $proses));
        $types = ['mtrg', 'grade', $kategori, $proses];
        $type = implode('-', $types);
        return $this->asJson(MDefaultValue::getOptionList($type));
    }

    public function actionShow($mtrg_setup_id)
    {
        return isset($mtrg_setup_id)
            ? $this->asJson(MMtrgSetup::findOne(['mtrg_setup_id' => $mtrg_setup_id]))
            : null;
    }
}