<?php

namespace app\modules\hrd\controllers;

use app\components\Params;
use app\components\SSP;
use app\models\MPegawai;
use app\models\TVideoTraining;
use app\models\TVideoTrainingPeserta;
use Exception;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\Json;

class VideotrainingController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new TVideoTraining();

        if(Yii::$app->request->get('dt') === 'table-training') {
            $param['table'] = TVideoTraining::tableName();
            $param['pk']    = $param['table'].".". TVideoTraining::primaryKey()[0];
            $param['column']= [
                $param['table'].'.video_training_id',
                $param['table'].'.tgl_awal',
                $param['table'].'.tgl_akhir',
                $param['table'].'.judul',
                $param['table'].'.deskripsi',
                $param['table'].'.video',
                $param['table'].'.peserta',
                $param['table'].'.evaluasi_peserta',
                $param['table'].'.evaluasi_atasan',
            ];

            return Json::encode(SSP::complex($param));
        }
        return $this->render('index', compact($model));
    }

    /**
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new TVideoTraining();
        if(Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->attributes = Yii::$app->request->post('TVideoTraining');
                $link_video = Yii::$app->request->post('video');
                $videos = [];
                foreach ($link_video as $video) {
                    if(!empty($video)) {
                        $url = str_replace('/view?usp=sharing', '/preview', $video);
                        $videos[] = ['url' => $url];
                    }
                }
                if(!empty($videos)) {
                    $model->video = Json::encode($videos);
                }else {
                    throw new Exception('Video tidak boleh kosong');
                }

                $input_link_peserta = Yii::$app->request->post('evaluasi_peserta');
                $link_peserta = [];
                foreach ($input_link_peserta as $peserta) {
                    if(!empty($peserta)) {
                        $link_peserta[] = ['url' => $peserta];
                    }
                }

                if(!empty($link_peserta)) {
                    $model->evaluasi_peserta = Json::encode($link_peserta);
                }

                $input_link_atasan = Yii::$app->request->post('evaluasi_atasan');
                $link_atasan = [];
                foreach ($input_link_atasan as $atasan) {
                    if(!empty($atasan)) {
                        $link_atasan[] = ['url' => $atasan];
                    }
                }
                if(!empty($link_atasan)) {
                    $model->evaluasi_atasan = Json::encode($link_atasan);
                }

                if($model->validate() && $model->save()) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1]);
                }
            }catch (Exception $exception) {
                $transaction->rollBack();
                if(!empty($exception)) {
                    Yii::$app->session->setFlash('error', Yii::t('app', $exception->getMessage()));
                }else {
                    Yii::$app->session->setFlash('error', Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            }
        }
        return $this->render('create', compact('model'));
    }

    public function actionTambahpeserta($id)
    {
        $model = TVideoTraining::findOne(['video_training_id' => $id]);
        if(Yii::$app->request->get('dt') === 'table-pegawai') {
            $peserta = TVideoTrainingPeserta::findAll(['video_training_id' => $id]);
            $except = [];
            foreach ($peserta as $row) {
                $except[] = $row->pegawai_id;
            }
            $param['table'] = MPegawai::tableName();
            $param['pk']    = $param['table'].".". MPegawai::primaryKey()[0];
            $param['column']= [
                $param['table'].'.pegawai_id',
                $param['table'].'.pegawai_nama',
                'm_jabatan.jabatan_nama',
                'm_departement.departement_nama'
            ];

            $param['join']  = [
                'JOIN m_departement ON m_departement.departement_id = '.$param['table'].'.departement_id',
                'JOIN m_jabatan ON m_jabatan.jabatan_id = '.$param['table'].'.jabatan_id',
            ];
            if(!empty($except)) {
                $param['where'] = [
                    $param['table'].'.pegawai_id NOT IN ' . '(' . join(',', $except) . ')',
                    $param['table'].'.active = true'
                ];
            }
            return Json::encode(SSP::complex($param));
        }
        return $this->renderAjax('tambah-peserta', compact('model'));
    }

    public function actionDaftarpeserta($id)
    {
        if(Yii::$app->request->get('dt') === 'table-peserta') {
            $param['table'] = TVideoTrainingPeserta::tableName();
            $param['pk']    = $param['table'].".". TVideoTrainingPeserta::primaryKey()[0];
            $param['column']= [
                $param['table'].'.video_training_peserta_id',
                'm_pegawai.pegawai_nama',
                'm_jabatan.jabatan_nama',
                'm_departement.departement_nama',
                $param['table'].'.pegawai_id',
                $param['table'].'.tipe',
            ];

            $param['join']  = [
                'JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.pegawai_id',
                'JOIN m_departement ON m_departement.departement_id = m_pegawai.departement_id',
                'JOIN m_jabatan ON m_jabatan.jabatan_id = m_pegawai.jabatan_id',
            ];
            $param['where'] = [
                $param['table'].'.video_training_id = '. $id,
            ];
            return Json::encode(SSP::complex($param));
        }
        return null;
    }

    public function actionBuatpeserta($id_training, $id_pegawai, $tipe)
    {
        $model = new TVideoTrainingPeserta();
        $model->video_training_id = $id_training;
        $model->pegawai_id = $id_pegawai;
        $model->tipe = $tipe;
        if($model->validate() && $model->save()) {
            return Json::encode(['status' => true, 'message' => 'Peserta berhasil ditambahkan']);
        }else {
            return Json::encode(['status' => false, 'message' => 'Peserta gagal ditambahkan']);
        }
    }

    /**
     * @throws StaleObjectException
     */
    public function actionHapuspeserta($id_training, $id_pegawai)
    {
        $model = TVideoTrainingPeserta::findOne(['pegawai_id' => $id_pegawai, 'video_training_id' => $id_training]);
        if($model->delete()) {
            return Json::encode(['status' => true, 'message' => 'Peserta berhasil dihapus']);
        }else {
            return Json::encode(['status' => false, 'message' => 'Peserta gagal dihapus']);
        }
    }

    public function actionEdit($video_training_id)
    {
        $model = TVideoTraining::findOne(['video_training_id' => $video_training_id]);
        $model->tgl_awal    = date('d/m/Y', strtotime($model->tgl_awal));
        $model->tgl_akhir   = date('d/m/Y', strtotime($model->tgl_akhir));
        if(Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->attributes = Yii::$app->request->post('TVideoTraining');
                $link_video = Yii::$app->request->post('video');
                $videos = [];
                foreach ($link_video as $video) {
                    if(!empty($video)) {
                        $url = str_replace('/view?usp=sharing', '/preview', $video);
                        $videos[] = ['url' => $url];
                    }
                }
                if(!empty($videos)) {
                    $model->video = Json::encode($videos);
                }else {
                    throw new Exception('Video tidak boleh kosong');
                }

                $input_link_peserta = Yii::$app->request->post('evaluasi_peserta');
                $link_peserta = [];
                foreach ($input_link_peserta as $peserta) {
                    if(!empty($peserta)) {
                        $link_peserta[] = ['url' => $peserta];
                    }
                }

                if(!empty($link_peserta)) {
                    $model->evaluasi_peserta = Json::encode($link_peserta);
                }

                $input_link_atasan = Yii::$app->request->post('evaluasi_atasan');
                $link_atasan = [];
                foreach ($input_link_atasan as $atasan) {
                    if(!empty($atasan)) {
                        $link_atasan[] = ['url' => $atasan];
                    }
                }
                if(!empty($link_atasan)) {
                    $model->evaluasi_atasan = Json::encode($link_atasan);
                }

                if($model->validate() && $model->save()) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil diubah'));
                    return $this->redirect(['index','success'=>1]);
                }
            }catch (Exception $exception) {
                $transaction->rollBack();
                if(!empty($exception)) {
                    Yii::$app->session->setFlash('error', Yii::t('app', $exception->getMessage()));
                }else {
                    Yii::$app->session->setFlash('error', Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            }
        }
        return $this->render('edit', compact('model'));
    }

    public function actionHapus($video_training_id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $video = TVideoTraining::findOne(['video_training_id' => $video_training_id]);
            if($video) {
                $del_peserta = TVideoTrainingPeserta::deleteAll(['video_training_id' => $video->video_training_id]);
                if($video->delete() && $del_peserta) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil dihapus'));
                    return $this->redirect(['index','success'=>1]);
                }
            }
        }catch(Exception $exception) {
            $transaction->rollBack();
            if(!empty($exception)) {
                Yii::$app->session->setFlash('error', Yii::t('app', $exception->getMessage()));
            }else {
                Yii::$app->session->setFlash('error', Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
            }
        }

        return null;
    }

    public function actionInfo($video_training_id)
    {
        $modVideoTraining = TVideoTraining::find()->with(['peserta'])->where(['video_training_id' => $video_training_id])->one();
        return $this->renderAjax('info', compact('modVideoTraining'));
    }

}
