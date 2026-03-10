<?php

namespace app\modules\marketing\controllers;

use app\components\Params;
use app\models\MHargaLog;
use app\models\TApproval;
use Yii;
use app\controllers\DeltaBaseController;
use yii\db\Exception;
use yii\web\Response;

class PricelistlogController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	
	public function actionIndex(){
        $model              = new \app\models\MBrgLog();
        $modHarga           = new MHargaLog();
        $cek_total_harga    = 0;

        if( Yii::$app->request->post('MHargaLog')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1  = true;
                $success_2  = true;
                $success_3  = true;
                // var_dump($_POST);die;
                $kode       = trim(\app\components\DeltaGenerator::kodeHargaLog());
                $model->load(\Yii::$app->request->post());

                $cek_tanggal_terpakai = MHargaLog::find()->where([
                    'harga_tanggal_penetapan' => \app\components\DeltaFormatter::formatDateTimeForDb($_POST['harga_tanggal_penetapan'])
                ])->count();
                $cek_not_confirmed  = MHargaLog::find()->where([
                    'status_approval' => 'Not Confirmed'
                ])->andWhere([
                    'harga_tanggal_penetapan' => \app\components\DeltaFormatter::formatDateTimeForDb($_POST['harga_tanggal_penetapan'])
                ])->count();
                
                if($cek_tanggal_terpakai > 0) {
                    if($cek_not_confirmed > 0) {
                        foreach($_POST['MHargaLog'] as $i => $detailupdate) {
                            $modHarga   = MHargaLog::find()
                            ->andWhere(['harga_tanggal_penetapan' => \app\components\DeltaFormatter::formatDateTimeForDb($_POST['harga_tanggal_penetapan'])])
                            ->andWhere(['status_approval' => 'Not Confirmed'])
                            ->andWhere(['log_id' => $detailupdate['log_id']])
                            ->one();
                            if($modHarga !== null){
                                $modHarga->harga_enduser = !empty($detailupdate['harga_enduser'])?$detailupdate['harga_enduser']:'0';
                                $modHarga->save();
                            } else{
                                $data['message']   = 'Terdapat error pada inputan harga';
                            }
                            
                            
                        } 
                    }else {
                        $data['message']   = 'Tanggal sudah di approve, perubahan di gagalkan!';
                        return $this->render('index',['model'=>$model,'modHarga'=>$modHarga]);
                    }
                }else {
                    foreach($_POST['MHargaLog'] as $i => $detailpost){
                        $modCurrentHarga = MHargaLog::find()->where([
                            'log_id'=>$detailpost['log_id'],
                            'harga_tanggal_penetapan'=>\app\components\DeltaFormatter::formatDateTimeForDb($_POST['harga_tanggal_penetapan'])
                        ])->one();
                        if(count($modCurrentHarga)>0){
                            $modCurrentHarga->delete();
                        }
                        $modHarga = new MHargaLog();
                        $modHarga->attributes = $detailpost;
                        $modHarga->harga_tanggal_penetapan = isset($_POST['harga_tanggal_penetapan'])?\app\components\DeltaFormatter::formatDateTimeForDb($_POST['harga_tanggal_penetapan']):'';
                        $modHarga->kode = $kode;
                        $modHarga->status_approval = 'Not Confirmed';
                        $modHarga->active = false;
                        $modHarga->harga_enduser = isset($detailpost['harga_enduser']) ? \app\components\DeltaFormatter::formatNumberForDb2($detailpost['harga_enduser']) : '';
    
                        if($modHarga->validate()){
                            if($modHarga->save()){
                                $success_1 = true;
                            }else{
                                $success_1 = false;
                            }
                        }else{
                            $data['message']=\yii\widgets\ActiveForm::validate($modHarga); 
                        }
    
                        // var_dump($detailpost);die;
                        $cek_total_harga += (int)$detailpost['harga_enduser'];
                    }
    
                    // cek kalau total harga end user 0 jangan disimpan
                    if($cek_total_harga > 0) {
                        $success_2  = true;
                    }else {
                        $success_2  = false;
                        $data['message']   = 'Data Kosong';
                    }
    
                    // approval 1 : kadiv marketing (iwan s 19)
                    // approval 2 : dirut (heryanto suwardi 22)
                    $array_approval = [
                        1 => Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO,
                        2 => Params::DEFAULT_PEGAWAI_ID_ASENG,
                        3 => Params::DEFAULT_PEGAWAI_ID_DIREKTUR_UTAMA
                    ];
    
                    foreach($array_approval as $level => $approver) {
                        $model_t_approval = new \app\models\TApproval();
                        $model_t_approval->assigned_to      = $approver;
                        $model_t_approval->reff_no          = $modHarga->kode;
                        $model_t_approval->tanggal_berkas   = date('Y-m-d H:i:s');
                        $model_t_approval->level            = $level;
                        $model_t_approval->status           = 'Not Confirmed';
                        $model_t_approval->active           = false;
                        $model_t_approval->created_at       = date('Y-m-d H:i:s');
                        $model_t_approval->created_by       = Yii::$app->user->identity->user_id;
                        $execute = $model_t_approval->save();
                        if(!$execute) {
                            $success_3 = false;
                        } 
                    }
                }

                // print_r($modHarga['harga_tanggal_penetapan']); exit;

                // var_dump([
                //     'proses 1' => $success_1, 
                //     [
                //         'proses 2' => $success_2, 
                //         'harga' => $cek_total_harga
                //     ], 
                //     'proses 3' => $success_3
                // ]);die;
                if ($success_1 && $success_2 && $success_3) {
                    $transaction->commit();
                    $data['message'] = Yii::t('app', Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                    Yii::$app->session->setFlash('success', Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                    return $this->redirect(['index','success'=>1,'tp'=>$modHarga['harga_tanggal_penetapan']]);
                } else {
                    $transaction->rollback();
                    (!isset($data['message']) ? $data['message'] = Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                    (isset($data['message_validate']) ? $data['message'] = null : '');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $data['message'] = $ex;
            }
        }

		return $this->render('index',['model'=>$model,'modHarga'=>$modHarga]);
	}

    public function actionSetTglDropdown()
    {
        if(Yii::$app->request->isAjax) {
            $selectedTanggal = Yii::$app->request->post('selected');
            $query = Yii::$app->db->createCommand(
                "SELECT SUM
                    ( harga_enduser ) AS total_harga,
                    harga_tanggal_penetapan,
                    kode,
                    status_approval 
                FROM
                    m_harga_log 
                GROUP BY
                    harga_tanggal_penetapan,
                    kode,
                    status_approval"
            )->queryAll();
    
            $html = '';
            foreach($query as $row) {
                if($row['status_approval'] == 'APPROVED') {
                    $style = "background-color: #fff; color: #5cb85c;";
                }else if($row['status_approval'] == 'REJECTED') {
                    $style = "background-color: #fff; color: #d9534f;";
                }else {
                    $style = "background-color: #fff; color: #000;";
                }
    
                $text   = Yii::t('app', 'Price List Tanggal : ')
                    .\app\components\DeltaFormatter::formatDateTimeForUser($row['harga_tanggal_penetapan'])
                    .' '.$row['status_approval'].' '.$row['total_harga'].' '.$row['kode'];
                $options= [
                    'style'=>$style,
                    'value'=>$row['harga_tanggal_penetapan'], 
                    'name'=>'harga_tanggal_penetapan', 
                    'label'=>$row['kode']
                ];

                if($selectedTanggal && $selectedTanggal == $row['harga_tanggal_penetapan']) {
                    $options['selected'] = true;
                }
                $html  .= \yii\bootstrap\Html::tag('option', $text, $options);
            }
            $data['html'] = $html;
            return $this->asJson($data);
        }
    }

    public function actionStatusApproval()
    {
        $tp         = Yii::$app->request->get('tp');
        $kode       = Yii::$app->request->get('kode');
        $t_approval = \app\models\TApproval::find()->where(['reff_no' => $kode])->all();
        return $this->renderAjax('_statusApproval', compact('tp', 'kode', 't_approval'));
    }
	
    public function actionGetContent(){
        if(\Yii::$app->request->isAjax){
            $tgl                    = Yii::$app->request->post('tgl');
            $tipe                   = Yii::$app->request->post('tipe');
            $data['html']           = '';
            $tanggal                = NULL;
            $status                 = NULL;
    
            if($tipe == 'input') {
                $tanggal_terakhir   = MHargaLog::find()
                                    ->select('harga_tanggal_penetapan')
                                    ->andWhere(['status_approval' => 'APPROVED'])
                                    ->orderBy(['harga_tanggal_penetapan' => SORT_DESC])
                                    ->limit(1)
                                    ->one();
                $tanggal            = $tanggal_terakhir['harga_tanggal_penetapan'];
                $status             = 'APPROVED';
            }else if ($tipe == 'edit') { // edit 
                $check              = MHargaLog::find()
                                    ->andWhere(['status_approval' => 'Not Confirmed'])
                                    ->andWhere(['harga_tanggal_penetapan' => \app\components\DeltaFormatter::formatDateTimeForDb($tgl)])
                                    ->count();
    
                if($check > 0) {
                    $tanggal        = $tgl;
                    $status         = 'Not Confirmed';
                }
            }else { // view
                $tanggal            = $tgl;
            }
    
            // if($tanggal != NULL) {
                // $models             = \app\models\MHargaLimbah::find()
                //                     ->with('limbah')
                //                     ->andWhere(['harga_tanggal_penetapan' => $tanggal])
                //                     ->filterWhere(['status_approval' => $status])
                //                     ->orderBy(['harga_enduser' => SORT_DESC])
                //                     ->all();
                $MBrgLog = \app\models\MBrgLog::find()
                        ->where(['active' => true])
                        ->orderBy(['seq' => SORT_ASC])
                        ->all();
                foreach($MBrgLog as $i => $m) {
                    $MHargaLog                  = $m->getHargaLog()
                                                ->andWhere(['harga_tanggal_penetapan' => \app\components\DeltaFormatter::formatDateTimeForDb($tanggal)])
                                                ->one();
                    $model = new MHargaLog();
                    $model->log_id           = $m->log_id;
                    $model->log_kode         = $m->log_kode;
                    $model->log_nama         = $m->log_nama;
                    $model->log_satuan_jual  = $m->log_satuan_jual;
                    $model->harga_enduser    = isset($MHargaLog->harga_enduser) ? $MHargaLog->harga_enduser : 0;
                    $model->harga_keterangan = isset($MHargaLog->harga_keterangan) ? $MHargaLog->harga_keterangan : "";
                    $data['html'] .= $this->renderPartial('_content', compact('model', 'i', 'tipe', 'm'));
                }
            // }else {
            //     $data['html']       = '<tr><td colspan="6" style="text-align: center;"><i>'.Yii::t('app', 'Data tidak ditemukan').'</i></td></tr>';
            // }
            $data['status'] = $status;
            return $this->asJson($data);
        }

        // $check = \Yii::$app->db->createCommand("SELECT harga_tanggal_penetapan FROM m_harga_limbah ORDER BY harga_tanggal_penetapan DESC")->queryOne();
        // $data['html'] = '';
        // if($tipe == "input"){
        // 	$tgl = $check['harga_tanggal_penetapan'];
        // }
        // $tgl = !empty($tgl)?$tgl:date("Y-m-d");			
        // $modLimbahs = \app\models\MBrgLimbah::find()->where(['active'=>true])->orderBy("seq ASC")->all();
        // if(count($modLimbahs)>0){
        // 	foreach($modLimbahs as $i => $modLimbah){
        // 		$model = new \app\models\MHargaLimbah();
        // 		$modHarga = \Yii::$app->db->createCommand("SELECT * FROM m_harga_limbah WHERE harga_tanggal_penetapan = '{$tgl}' AND limbah_id = {$modLimbah->limbah_id}")->queryOne();
        // 		$model->limbah_id = $modLimbah->limbah_id;
        // 		$model->limbah_kode = $modLimbah->limbah_kode;
        // 		$model->limbah_nama = $modLimbah->limbah_nama;
        // 		$model->limbah_satuan_jual = $modLimbah->limbah_satuan_jual;
        // 		$model->limbah_satuan_muat = $modLimbah->limbah_satuan_muat;
        // 		$model->harga_enduser = isset($modHarga['harga_enduser'])?$modHarga['harga_enduser']:0;
        // 		$model->harga_keterangan = isset($modHarga['harga_keterangan'])?$modHarga['harga_keterangan']:"";
                
        // 		$data['html'] .= $this->renderPartial('_content',['model'=>$model,'i'=>$i,'tipe'=>$tipe]);
        // 	}
        // }else{
        // 	$data['html'] = '<tr><td colspan="6" style="text-align: center;"><i>'.Yii::t('app', 'Data tidak ditemukan').'</i></td></tr>';
        // }
        // return $this->asJson($data);
    }
    
    public function actionSetPrice(){
        if(\Yii::$app->request->isAjax){
            $log_id = Yii::$app->request->post('log_id');
            $tgl_penetapan = Yii::$app->request->post('tgl_penetapan');
            $tgl_penetapan = \app\components\DeltaFormatter::formatDateTimeForDb($tgl_penetapan);
            $sql = "SELECT harga_enduser,harga_keterangan FROM m_harga_log
                    WHERE log_id = '".$log_id."' AND harga_tanggal_penetapan = '".$tgl_penetapan."' AND active = TRUE";
            $models = \Yii::$app->db->createCommand($sql)->queryOne();
            if($models){
                $models['harga_enduser'] = \app\components\DeltaFormatter::formatNumberForUser($models['harga_enduser']);
                $models['harga_enduser_formatted'] = \app\components\DeltaFormatter::formatUang($models['harga_enduser']);
                $models['harga_keterangan'] = $models['harga_keterangan'];
            }
            return $this->asJson($models);
            
		}
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;
                    $log = MHargaLog::findOne(['harga_tanggal_penetapan' => $id]);
                    $delete = MHargaLog::deleteAll(['and', 'harga_tanggal_penetapan = :tanggal'], [':tanggal' => $id ]);
                    if($delete) $success_1 = true;

                    $approval = TApproval::deleteAll(['reff_no' => $log->kode]);
                    if($approval) $success_2 = true;

                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Price List Berhasil Dihapus');
                        $data['callback'] = 'setTimeout(() => {document.location.reload()}, 500)';
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
		}
        return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id' => $id]);
	}

    public function actionGraf($id){
		if(\Yii::$app->request->isAjax){
            $MBrgLog     = \app\models\MBrgLog::findOne($id);
            $log_id      = $MBrgLog->log_id;
            $MHargaLog   = MHargaLog::find()->where(['log_id' => $log_id])->orderBy('harga_tanggal_penetapan')->all();
            return $this->renderAjax('_graf',['MBrgLog'=>$MBrgLog,'MHargaLog'=>$MHargaLog]);
        }
    }
	
}
