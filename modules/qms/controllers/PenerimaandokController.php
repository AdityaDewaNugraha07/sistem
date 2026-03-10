<?php

namespace app\modules\qms\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PenerimaandokController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TDokumenDistribusi();
        $model->tanggal_dikirim = date('d/m/Y');
        $model->dikirim_oleh = Yii::$app->user->identity->pegawai->pegawai_id;

		if (isset($_GET['dokumen_distribusi_id'])) {
            $model = \app\models\TDokumenDistribusi::findOne($_GET['dokumen_distribusi_id']);
        }

		if (Yii::$app->request->post('TDokumenDistribusi')) {
			$transaction = \Yii::$app->db->beginTransaction();
			try {
                $success_1 = false; // t_dokumen_distribusi
                $model->load(\Yii::$app->request->post());
                $model->status_penerimaan = true;
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                    }
                }

                // print_r($success_1);
                // exit;
                if ($success_1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'dokumen_distribusi_id'=>$model->dokumen_distribusi_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
		}

		return $this->render('index', ['model'=>$model]);
	}

    public function actionTerimaDokumen($id){
        if(\Yii::$app->request->isAjax){
			$model = \app\models\TDokumenDistribusi::findOne($id);
            $modDokRev = \app\models\TDokumenRevisi::findOne($model->dokumen_revisi_id);
            $modDokumen = \app\models\MDokumen::findOne($modDokRev->dokumen_id);
			if( Yii::$app->request->post('TDokumenDistribusi')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $model->load(\Yii::$app->request->post());
                    $model->status_penerimaan = true;
                    $model->diterima_oleh = Yii::$app->user->identity->pegawai->pegawai_id;
					$model->catatan_penerimaan = $_POST['TDokumenDistribusi']['catatan_penerimaan'] !== ''?$_POST['TDokumenDistribusi']['catatan_penerimaan']:null;
                    $model->tanggal_penerimaan = date('Y-m-d H:i:s');

					if($model->validate()){
						if($model->save()){
							$success_1 = true;
						}
					}
				
					// print_r($success_1); exit;
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                    }
                } catch (yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('terimaDokumen',['model'=>$model, 'modDokRev'=>$modDokRev, 'modDokumen'=>$modDokumen]);
		}
    }

    public function actionRefreshTable(){
        $pegawai_id = Yii::$app->user->identity->pegawai->pegawai_id;

        $query = "SELECT dokumen_distribusi_id, t_dokumen_revisi.nama_dokumen, t_dokumen_revisi.revisi_ke, m_dokumen.nomor_dokumen, m_dokumen.jenis_dokumen,
                tanggal_dikirim, b.pegawai_nama as dikirim_oleh, a.pegawai_nama as pic_iso
                FROM t_dokumen_distribusi
                JOIN t_dokumen_revisi ON t_dokumen_revisi.dokumen_revisi_id = t_dokumen_distribusi.dokumen_revisi_id
                JOIN m_dokumen ON m_dokumen.dokumen_id = t_dokumen_revisi.dokumen_id
                JOIN m_pic_iso ON m_pic_iso.pic_iso_id = t_dokumen_distribusi.pic_iso_id
                JOIN m_pegawai a ON a.pegawai_id = m_pic_iso.pegawai_id
                JOIN m_pegawai b ON b.pegawai_id = t_dokumen_distribusi.dikirim_oleh
                WHERE status_penerimaan IS NOT TRUE AND m_pic_iso.pegawai_id = $pegawai_id";
        $model = Yii::$app->db->createCommand($query)->queryAll();

        return $this->renderPartial('_item', ['model' => $model]);
    }

    public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
                $pegawai_id = Yii::$app->user->identity->pegawai->pegawai_id;
				$param['table']= \app\models\TDokumenDistribusi::tableName();
				$param['pk']= $param['table'].".". \app\models\TDokumenDistribusi::primaryKey()[0];
				$param['column'] = [$param['table'].'.dokumen_distribusi_id',
									't_dokumen_revisi.nama_dokumen',
									'revisi_ke',
                                    'm_dokumen.nomor_dokumen', 
                                    'm_dokumen.jenis_dokumen',
                                    'tanggal_dikirim', 
                                    'b.pegawai_nama as dikirim_oleh', 
                                    'a.pegawai_nama as pic_iso'
									];
				$param['join']= ['  JOIN t_dokumen_revisi ON t_dokumen_revisi.dokumen_revisi_id = t_dokumen_distribusi.dokumen_revisi_id
                                    JOIN m_dokumen ON m_dokumen.dokumen_id = t_dokumen_revisi.dokumen_id
                                    JOIN m_pic_iso ON m_pic_iso.pic_iso_id = t_dokumen_distribusi.pic_iso_id
                                    JOIN m_pegawai a ON a.pegawai_id = m_pic_iso.pegawai_id
                                    JOIN m_pegawai b ON b.pegawai_id = t_dokumen_distribusi.dikirim_oleh'];
                $param['where'] = ['status_penerimaan IS TRUE AND m_pic_iso.pegawai_id = ' . $pegawai_id];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
}