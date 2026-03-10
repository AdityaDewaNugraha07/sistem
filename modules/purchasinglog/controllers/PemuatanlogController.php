<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PemuatanlogController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TLogBayarMuat();
		$model->kode = 'Auto Generate';
        $model->tanggal = date('d/m/Y');
		$modDetail = [];
		
		if(isset($_GET['log_bayar_muat_id'])){
            $model = \app\models\TLogBayarMuat::findOne($_GET['log_bayar_muat_id']);
            $model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
			$model->loglist_kode = $model->loglist->loglist_kode.' - '.$model->logKontrak->nomor;
        }
		
		if( Yii::$app->request->post('TLogBayarMuat') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $model->load(\Yii::$app->request->post());
                $model->kode = \app\components\DeltaGenerator::kodePemuatanLog();
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                    }
                }
                if ($success_1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Pengajuan DP Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'log_bayar_muat_id'=>$model->log_bayar_muat_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		
		return $this->render('index',['model'=>$model,'modDetail'=>$modDetail]);
	}
	
	public function actionLoglistsetted(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
            $data = [];
            if(!empty($loglist_id)){
                $model = \app\models\TLoglist::findOne($loglist_id);
                $modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$loglist_id])->all();
                $modDp = \app\models\TLogBayarDp::find()->where(['log_kontrak_id'=>$model->log_kontrak_id])->all();
				$modLogKontrak = \app\models\TLogKontrak::findOne($model->log_kontrak_id);
				$modKeputusan = \app\models\TPengajuanPembelianlog::findOne($model->pengajuan_pembelianlog_id);
                if(!empty($model)){
                    $data = $model->attributes;
                    $data['modKontrak'] = $modLogKontrak->attributes;
                    $data['modKeputusan'] = $modKeputusan->attributes;
                    $data['volumem3'] = \app\models\TLoglistDetail::getTotalByLoglistId($loglist_id);
                    $data['hargam3'] = $model->logKontrak->hargafob;
					$data['htmlloglist'] = $this->renderPartial('_rekaploglist',['model'=>$model,'modDetail'=>$modDetail]);
                }
				if(!empty($modDp)){
					$data['html'] = '';
					$data['totalsemua_dp'] = 0;
					foreach($modDp as $i => $dp){
						$data['html'] .= $this->renderPartial('_itemdp',['modDp'=>$dp]);
						if($dp->status=='PAID'){
							$data['totalsemua_dp'] += $dp->total_dp;
						}
					}
					$data['html'] .= '<tr><td colspan="3" style="text-align: right;"> &nbsp; Total</td><td style="text-align: right;"><b>'.\app\components\DeltaFormatter::formatUang($data['totalsemua_dp']).'</b></td></tr>';
                }
            }
            return $this->asJson($data);
        }
	}
	
	public function actionGetItemsByPk(){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $data = [];
            $data['html'] = '';
            if(!empty($id)){
                $model = \app\models\TLogKontrak::findOne($id);
                if(!empty($model)){
                    $data['html'] .= $this->renderPartial('_showItem',['model'=>$model]);
                }
            }
            return $this->asJson($data);
        }
    }
	
	public function actionDaftarAfterSave(){
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TLogBayarMuat::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TLogKontrak::primaryKey()[0];
				$param['column'] = [$param['table'].'.log_bayar_muat_id',
									't_log_bayar_muat.kode',
									't_log_bayar_muat.tanggal',
									't_loglist.loglist_kode',
									't_pengajuan_pembelianlog.kode AS kode_keputusan',
									't_log_kontrak.kode AS kode_po',
									't_log_kontrak.nomor AS nomor_kontrak',
									't_log_bayar_muat.total_bayar',
									't_voucher_pengeluaran.status_bayar'];
				$param['join'] = ['JOIN t_loglist ON t_loglist.loglist_id = t_log_bayar_muat.loglist_id
								   JOIN t_pengajuan_pembelianlog ON t_pengajuan_pembelianlog.pengajuan_pembelianlog_id = t_log_bayar_muat.pengajuan_pembelianlog_id
								   JOIN t_log_kontrak ON t_log_kontrak.log_kontrak_id = t_loglist.log_kontrak_id
								   LEFT JOIN t_voucher_pengeluaran ON t_voucher_pengeluaran.voucher_pengeluaran_id = t_log_bayar_muat.voucher_pengeluaran_id'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
}
