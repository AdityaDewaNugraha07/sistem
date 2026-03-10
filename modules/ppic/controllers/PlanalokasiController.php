<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PlanalokasiController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TPlanStoklog();
		return $this->render('index',['model'=>$model]);
	}

    function actionGetItemsScanned(){
		if(\Yii::$app->request->isAjax){
            if(\Yii::$app->request->get('dt')=='table-master'){
                $jenis_alokasi = Yii::$app->request->get('jenis_alokasi');
                $param['table']= \app\models\TPlanStoklog::tableName();
                $param['pk']= \app\models\TPlanStoklog::primaryKey()[0];
                $param['column'] = ['plan_stoklog_id', 't_plan_stoklog.no_barcode', 'kayu_nama', 'kubikasi', 't_plan_stoklog.created_at'];
                $param['join'] = [" JOIN m_kayu ON m_kayu.kayu_id = t_plan_stoklog.kayu_id
                                    JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_plan_stoklog.no_barcode"];
                $param['where'] = ["jenis_alokasi = '$jenis_alokasi'"];
                $param['group'] = 'GROUP BY plan_stoklog_id, kayu_nama';
                $param['having'] = "HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0";
                $param['order'] = "created_at DESC";
                return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
            }
        }
    }

    public function actionTotal(){
        $jenis_alokasi = Yii::$app->request->get('jenis_alokasi');
        $total = 0;
        // $model = \app\models\TPlanStoklog::findAll(['jenis_alokasi'=>$jenis_alokasi]);
        $model = Yii::$app->db->createCommand(" SELECT * FROM t_plan_stoklog
                                                JOIN (SELECT no_barcode, SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS total_stock FROM h_persediaan_log
                                                    GROUP BY no_barcode HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0) s 
                                                    ON t_plan_stoklog.no_barcode = s.no_barcode
                                                WHERE jenis_alokasi = '$jenis_alokasi'")->queryAll();
        if(count($model) > 0){
            foreach($model as $i => $mod){
                $total += $mod['kubikasi'];
            }
        }
        return \yii\helpers\Json::encode(['total' => $total]);
    }
    
    public function actionSaveNomorBarcode(){
		if(\Yii::$app->request->isAjax){
			$data['status'] = false;
			$data['msg'] = "";
			$no_barcode = \Yii::$app->request->post('no_barcode');
            $jenis_alokasi = \Yii::$app->request->post('jenis_alokasi');
            $modelPlan = \app\models\TPlanStoklog::findOne(['no_barcode'=>$no_barcode]);
            $modAlokasi = \app\models\TPlanStoklog::findOne(['no_barcode'=>$no_barcode, 'jenis_alokasi'=>$jenis_alokasi]);
            $modPersediaan = Yii::$app->db->createCommand("
                                SELECT no_barcode, kayu_id, fisik_volume FROM h_persediaan_log 
                                WHERE no_grade <> '-' AND no_barcode = '$no_barcode' 
                                GROUP BY no_barcode, kayu_id, fisik_volume
                                HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0")->queryAll();
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_plan_stoklog
                if(empty($modelPlan)){
                    if(!empty($modPersediaan)){
                        $modH = \app\models\HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
                        $model = new \app\models\TPlanStoklog();
                        $model->jenis_alokasi = $jenis_alokasi;
                        $model->no_barcode = $no_barcode;
                        $model->kayu_id = $modH->kayu_id;
                        $model->kubikasi = $modH->fisik_volume;
                        if($model->validate()){
                            if($model->save()){
                                $success_1 = true;
                            }
                        }
                    }else{
                        $data['status'] = false;
                        $data['msg'] = "Log tidak tersedia di stok!";
                    }
                } else {
                    if($modAlokasi){
                        $data['status'] = false;
                        $data['msg'] = "Log telah discan!";
                    } else {
                        $data['status'] = false;
                        $data['msg'] = "Log telah discan di plan alokasi lain!";
                    }
                }
            //    echo "<pre>";
            //    print_r($success_1);
            //    exit;
                
                if ($success_1 ) {
                    $transaction->commit();
                    $data['status'] = true;
                    $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                } else {
                    $transaction->rollback();
                    $data['status'] = false;
                    (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                    (isset($data['message_validate']) ? $data['message'] = null : '');
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                $data['message'] = $ex;
            }
			return $this->asJson($data);
		}
	}
    
    public function actionDeleteNomorBarcode($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TPlanStoklog::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_plan_stoklog
                    if(!empty($model)){
                        if($model->delete()){
                            $success_1 = true;
                        }
                    }

                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['callback'] = "$('#table-master').dataTable().fnClearTable();";
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
            }
            return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'actionname'=>'deleteNomorBarcode']);
		}
	}

    public function actionInputManual()
    {
        if (\Yii::$app->request->isAjax) {
            $jenis_alokasi   = Yii::$app->request->get('jenis_alokasi');
            return $this->renderAjax('_inputManual', ['jenis_alokasi'=>$jenis_alokasi]);
        }
    }
    
    public function actionInputManuals()
    {
        if (Yii::$app->request->isAjax) {
            $req = $_POST;
            if($req['clause'] === 'no_lap' || $req['clause'] === 'no_barcode') {
                $modDetail = Yii::$app->db->createCommand(" SELECT no_grade,no_btg,no_lap,no_barcode FROM h_persediaan_log 
                                                            WHERE no_grade <> '-' and ".trim($req['clause'])." = '".trim($req['keyword'])."'
                                                            GROUP BY no_grade,no_btg,no_lap,no_barcode
                                                            HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0
                                                            ")->queryOne();
                $modH = \app\models\HPersediaanLog::findOne(['no_barcode'=>$modDetail['no_barcode']]);
                if (count($modDetail) > 0) {
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : {$modH->persediaan_log_id}\nNo : {$modDetail['no_barcode']}",
                    ]);
                }
            }else {
                $modDetails = Yii::$app->db->createCommand("SELECT no_grade,no_btg,no_lap,no_barcode FROM h_persediaan_log 
                                                            WHERE no_grade <> '-' and ".trim($req['clause'])." = '".trim($req['keyword'])."'
                                                            GROUP BY no_grade,no_btg,no_lap,no_barcode
                                                            HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0
                                                            ")->queryAll();
                if(count($modDetails) === 1) {
                    $modH = \app\models\HPersediaanLog::findOne(['no_barcode'=>$modDetails[0]['no_barcode']]);
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : {$modH->persediaan_log_id}\nNo : {$modDetails[0]['no_barcode']}",
                    ]);
                }else {
                    return $this->asJson([
                        'status' => true,
                        'datas' => $modDetails
                    ]);
                }
            }
        }
        return $this->asJson(['status' => false, 'message' => 'Data tidak ditemukan']);
    }

    public function actionInfo(){
        if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->get('id');
			$model = \app\models\TPlanStoklog::findOne($id);
            $modH = \app\models\HPersediaanLog::findOne(['no_barcode'=>$model->no_barcode]);
            $modKayu = \app\models\MKayu::findOne($model->kayu_id);
			return $this->renderAjax('info',['model'=>$model, 'modH'=>$modH, 'modKayu'=>$modKayu]);
		}
    }

    public function actionLihatdetail(){
        if(\Yii::$app->request->isAjax){
            $jenis_alokasi = Yii::$app->request->get('jenis_alokasi');
            $kayu_id = Yii::$app->request->get('kayu_id');
            $modKayu = \app\models\MKayu::findOne($kayu_id);
            if(\Yii::$app->request->get('dt')=='table-detail'){
                $jenis_alokasi = Yii::$app->request->get('jenis_alokasi');
                $kayu_id = Yii::$app->request->get('kayu_id');
                $param['table']= \app\models\HPersediaanLog::tableName();
                $param['pk']= \app\models\TPlanStoklog::primaryKey()[0];
                $param['column'] = ['h_persediaan_log.no_barcode', 'no_btg', 'no_lap', 'no_grade', 'fisik_panjang', 'fisik_diameter', 'fisik_volume', 'pot', 
                                    'diameter_ujung1', 'diameter_ujung2', 'diameter_pangkal1', 'diameter_pangkal2', 'cacat_panjang', 'cacat_gb', 'cacat_gr', 
                                    'fsc'];
                $param['join'] = [" INNER JOIN t_plan_stoklog ON h_persediaan_log.no_barcode = t_plan_stoklog.no_barcode"];
                $param['where'] = ["jenis_alokasi = '$jenis_alokasi' AND t_plan_stoklog.kayu_id = $kayu_id"];
                $param['group'] = 'GROUP BY h_persediaan_log.no_barcode,no_btg,no_lap,no_grade,fisik_panjang,fisik_diameter,fisik_volume,pot,
                                    diameter_ujung1,diameter_ujung2, diameter_pangkal1,diameter_pangkal2,cacat_panjang, cacat_gb, cacat_gr, fsc';
                $param['having'] = "HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0";
                return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
            }
            return $this->renderAjax('lihatdetail',['jenis_alokasi'=>$jenis_alokasi,'kayu'=>$modKayu->kayu_nama, 'kayu_id'=>$kayu_id]);
        }
    }

    /**public function actionLihatdetail(){
        if(\Yii::$app->request->isAjax){
            $jenis_alokasi = Yii::$app->request->get('jenis_alokasi');
            $kayu_id = Yii::$app->request->get('kayu_id');
            $modKayu = \app\models\MKayu::findOne($kayu_id);
            $model = \app\models\HPersediaanLog::find()
                        ->select('h_persediaan_log.no_barcode, no_btg, no_lap, no_grade, fisik_panjang, fisik_diameter, fisik_volume, pot, 
                                    diameter_ujung1, diameter_ujung2, diameter_pangkal1, diameter_pangkal2, cacat_panjang, cacat_gb, cacat_gr, 
                                    fsc')
                        ->join('INNER JOIN','t_plan_stoklog', 'h_persediaan_log.no_barcode = t_plan_stoklog.no_barcode')
                        ->where(['jenis_alokasi'=>$jenis_alokasi, 't_plan_stoklog.kayu_id'=>$kayu_id])
                        ->groupBy('h_persediaan_log.no_barcode,no_btg,no_lap,no_grade,fisik_panjang,fisik_diameter,fisik_volume,pot,
                                    diameter_ujung1,diameter_ujung2, diameter_pangkal1,diameter_pangkal2,cacat_panjang, cacat_gb, cacat_gr, fsc')
                        ->having(['>', "SUM(CASE WHEN status ='IN' THEN 1 ELSE -1 END)", 0])
                        ->all();
            return $this->renderAjax('lihatdetail',['model'=>$model, 'jenis_alokasi'=>$jenis_alokasi,'kayu'=>$modKayu->kayu_nama]);
        }
    }*/

    public function actionShowDetail(){
        $data['status']         = false;
        if(Yii::$app->request->isAjax){
			$no_barcode     	= Yii::$app->request->post('no_barcode');
            $jenis_alokasi     	= Yii::$app->request->post('jenis_alokasi');
            $data['no_barcode'] = $no_barcode;
            $modelPlan = \app\models\TPlanStoklog::findOne(['no_barcode'=>$no_barcode]);
            $modAlokasi = \app\models\TPlanStoklog::findOne(['no_barcode'=>$no_barcode, 'jenis_alokasi'=>$jenis_alokasi]);
            $modPersediaan = Yii::$app->db->createCommand("
                                SELECT no_barcode, kayu_id, fisik_volume FROM h_persediaan_log 
                                WHERE no_grade <> '-' AND no_barcode = '$no_barcode' 
                                GROUP BY no_barcode, kayu_id, fisik_volume
                                HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0")->queryAll();
			if(empty($modelPlan)){
                if(!empty($modPersediaan)){
                    $data['status'] = true;
					$data['msg']    = "Data ok";
                }else{
                    $data['status'] = false;
                    $data['msg'] = "Log tidak tersedia di stok!";
                }
            } else {
                if($modAlokasi){
                    $data['status'] = false;
                    $data['msg'] = "Log telah discan!";
                } else {
                    $data['status'] = false;
                    $data['msg'] = "Log telah discan di plan alokasi lain!";
                }
            }
		} else {
            $data['msg'] = "xxx";
        }
        return $this->asJson($data);
	}

    public function actionReview(){
        if(Yii::$app->request->isAjax){
            $no_barcode = Yii::$app->request->get('no_barcode');
            $jenis_alokasi = Yii::$app->request->get('jenis_alokasi');
			$modH = \app\models\HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
            return $this->renderAjax('_review',['modH'=>$modH, 'jenis_alokasi'=>$jenis_alokasi]);
        }
    }

    public function actionAlokasiTotal()
    {
        $jenis_alokasi = Yii::$app->request->get('jenis_alokasi');
        $kayu_id = Yii::$app->request->get('kayu_id');
        $total  = 0; 
        $query = "  SELECT h_persediaan_log.no_barcode, no_btg, no_lap, no_grade, fisik_panjang, fisik_diameter, fisik_volume, pot, diameter_ujung1, diameter_ujung2, 
                        diameter_pangkal1, diameter_pangkal2, cacat_panjang, cacat_gb, cacat_gr, fsc 
                    FROM h_persediaan_log
                    INNER JOIN t_plan_stoklog ON h_persediaan_log.no_barcode = t_plan_stoklog.no_barcode
                    WHERE jenis_alokasi = '$jenis_alokasi' AND t_plan_stoklog.kayu_id = $kayu_id
                    GROUP BY h_persediaan_log.no_barcode,no_btg,no_lap,no_grade,fisik_panjang,fisik_diameter,fisik_volume,pot,
                        diameter_ujung1,diameter_ujung2, diameter_pangkal1,diameter_pangkal2,cacat_panjang, cacat_gb, cacat_gr, fsc
                    HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0";
        $model = Yii::$app->db->createCommand($query)->queryAll();
        foreach($model as $row) {
			$total += $row['fisik_volume'];
        }
        return \yii\helpers\Json::encode(['total' => $total]);
    }
}
