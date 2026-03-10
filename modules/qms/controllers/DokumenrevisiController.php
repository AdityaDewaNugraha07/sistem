<?php

namespace app\modules\qms\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class DokumenrevisiController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $modRevisi = new \app\models\TDokumenRevisi();
        $modRevisi->tanggal_berlaku = date('d/m/Y');

		if (isset($_GET['dokumen_revisi_id'])) {
            $modRevisi = \app\models\TDokumenRevisi::findOne($_GET['dokumen_revisi_id']);
        }

		if (Yii::$app->request->post('TDokumenRevisi')) {
			$transaction = \Yii::$app->db->beginTransaction();
			try {
                $success_1 = false; // t_dokumen_revisi
                $modRevisi->load(\Yii::$app->request->post());
                $modRevisi->catatan_revisi = $_POST['TDokumenRevisi']['catatan_revisi'] == ''?null:$_POST['TDokumenRevisi']['catatan_revisi'];
                if($modRevisi->validate()){
                    if($modRevisi->save()){
                        $success_1 = true;
                    }
                }

                // print_r($success_1);
                // exit;
                if ($success_1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'dokumen_revisi_id'=>$modRevisi->dokumen_revisi_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
		}

		return $this->render('index', ['modRevisi'=>$modRevisi]);
	}

    public function actionSetDokumen(){
        if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $edit = Yii::$app->request->post('edit');
            $dok_id = Yii::$app->request->post('dok_id');
            $data = [];
            $model = \app\models\MDokumen::findOne($id);
            $modRevisi = new \app\models\TDokumenRevisi();
            $modRevisi->tanggal_berlaku = date('d/m/Y');
            if($dok_id){
                $modRevisi = \app\models\TDokumenRevisi::findOne($dok_id);
                $modRevisi->tanggal_berlaku = \app\components\DeltaFormatter::formatDateTimeForUser2($modRevisi->tanggal_berlaku);
            }
            $data['html'] = $this->renderPartial('showDokAsal',['model'=>$model, 'modRevisi'=>$modRevisi]);
            $data['html2'] = $this->renderPartial('showDokrevisi',['model'=>$model, 'modRevisi'=>$modRevisi, 'dok_id'=>$dok_id]);
            return $this->asJson($data);
        }
    }

    public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TDokumenRevisi::tableName();
				$param['pk']= $param['table'].".". \app\models\TDokumenRevisi::primaryKey()[0];
				$param['column'] = [$param['table'].'.dokumen_revisi_id',
									$param['table'].'.nama_dokumen',
                                    'nomor_dokumen',
									'revisi_ke',
                                    'tanggal_berlaku', 
                                    'catatan_revisi',
                                    'CASE
                                        WHEN BOOL_AND(status_penerimaan) OR BOOL_OR(status_penerimaan) THEN TRUE
                                        WHEN NOT BOOL_AND(status_penerimaan) THEN FALSE
                                        ELSE NULL
                                    END AS status_penerimaan', // jika status_penerimaan di distribusi all true/salah satu true maka true, jika all false maka false, else null
                                    // 'status_penerimaan',
									];
				$param['join']= ['LEFT JOIN t_dokumen_distribusi ON t_dokumen_distribusi.dokumen_revisi_id = t_dokumen_revisi.dokumen_revisi_id
                                  JOIN m_dokumen ON m_dokumen.dokumen_id = t_dokumen_revisi.dokumen_id'];
                $param['group'] = ['GROUP BY t_dokumen_revisi.dokumen_revisi_id, nomor_dokumen'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }

    public function actionSetRevisi(){
        $id = Yii::$app->request->post('id');
        $data = [];

        $maxRevisi = \app\models\TDokumenRevisi::find()->where(['dokumen_id'=>$id])->max('revisi_ke');
        $data = $maxRevisi;
        return $this->asJson($data);
    }

    public function actionRiwayatRevisi(){
        $dokumen_id = Yii::$app->request->get('dokumen_id');
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-riwayat-revisi'){
				$param['table']= \app\models\TDokumenRevisi::tableName();
				$param['pk']= $param['table'].".". \app\models\TDokumenRevisi::primaryKey()[0];
				$param['column'] = [$param['table'].'.dokumen_revisi_id',
									$param['table'].'.nama_dokumen',
                                    'nomor_dokumen',
									'revisi_ke',
                                    'tanggal_berlaku', 
                                    'catatan_revisi',
									];
                $param['where'] = "t_dokumen_revisi.dokumen_id = {$dokumen_id}";
				$param['join']= ['LEFT JOIN t_dokumen_distribusi ON t_dokumen_distribusi.dokumen_revisi_id = t_dokumen_revisi.dokumen_revisi_id
                                  JOIN m_dokumen ON m_dokumen.dokumen_id = t_dokumen_revisi.dokumen_id'];
                $param['group'] = ['GROUP BY t_dokumen_revisi.dokumen_revisi_id, nomor_dokumen'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('riwayatRevisi', ['dokumen_id'=>$dokumen_id]);
        }
    }
}
