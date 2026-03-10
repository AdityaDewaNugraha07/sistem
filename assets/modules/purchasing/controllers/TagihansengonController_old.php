<?php

namespace app\modules\purchasing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class TagihansengonController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
	public function actionIndex(){
		$model = new \app\models\TTagihanSengon();
		$model->kode = 'Auto Generate';
        $model->tanggal_tagihan = date('M-Y');
		
		if(isset($_GET['tagihan_sengon_id'])){
            $model = \app\models\TTagihanSengon::findOne($_GET['tagihan_sengon_id']);
            $model->tanggal_tagihan = date('M-Y',strtotime($model->tanggal_tagihan));
        }
		
		if( Yii::$app->request->post('TTagihanSengon') ){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = true;
				$kode = \app\components\DeltaGenerator::kodeTagihanSengon();
				foreach($_POST['TTagihanSengon'] as $i => $post){
					if(is_array($post)){
						if(!empty($post['tagihan_sengon_id'])){
							$model = \app\models\TTagihanSengon::findOne($post['tagihan_sengon_id']);
							$model->tagihan_sengon_id = $post['tagihan_sengon_id'];
						}else{
							$model = new \app\models\TTagihanSengon();
							$model->attributes = $post;
							$model->posengon_id = $_POST['TTagihanSengon']['posengon_id'];
							$model->kode = $kode;
							$model->tanggal_tagihan = \app\components\DeltaFormatter::formatMonthForDb($_POST['TTagihanSengon']['tanggal_tagihan']).'-1';
							$model->npwp = $post['npwp'];
							$model->disetujui = $post['disetujui'];
							$model->total_bayar = $post['totalbayar'];
							$model->status = 'UNPAID';
							$spek = [];
							foreach($post as $i => $post_spek){
								if(is_array($post_spek)){
									$spek[$i] = $post_spek;
								}
							}
							$model->spek = \yii\helpers\Json::encode($spek);
						}
						if($model->validate()){
							if($model->save()){
								$success_1 &= true;
							}
						}else{
							$success_1 &= false;
						}
					}
				}
                if ($success_1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil Disimpan'));
                    return $this->redirect(['index','success'=>1,'tagihan_sengon_id'=>$model->tagihan_sengon_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
		
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionGetDetailItem(){
        if(\Yii::$app->request->isAjax){
			$posengon_id = \Yii::$app->request->post('posengon_id');
			$tanggal_tagihan = \Yii::$app->request->post('tanggal_tagihan');
			$tanggal_tagihan = \app\components\DeltaFormatter::formatMonthForDb($tanggal_tagihan).'-1';
			$data = [];
			if( !empty($posengon_id)&&!empty($tanggal_tagihan) ){
				$modTerima = \app\models\TTerimaSengonDetail::find()
						->select('t_terima_sengon.*,t_terima_sengon_detail.*')
						->where(['posengon_id'=>$posengon_id,'tanggal'=>$tanggal_tagihan])
						->join('JOIN', 't_terima_sengon', 't_terima_sengon_detail.terima_sengon_id = t_terima_sengon.terima_sengon_id')
						->all();
				$sqldiameter = '';
				if(count($modTerima)>0){
					$modPO = \app\models\TPosengon::findOne($posengon_id);
					$speks = \yii\helpers\Json::decode($modPO->diameter_harga);
					$sqldiameter = 'CASE ';
					foreach($speks as $i => $spek){
						$search1 = strpos($i, '<');
						if($search1 !== false){
							$value = str_replace('<', '0 AND ', $i);
							$sqldiameter .= "WHEN diameter BETWEEN $value THEN '$i' ";
						}
						$search2 = strpos($i, '-');
						if($search2 !== false){
							$value = str_replace('-', ' AND ', $i);
							$sqldiameter .= "WHEN diameter BETWEEN $value THEN '$i' ";
						}
						$search3 = strpos(strtolower($i), 'up');
						if($search3 !== false){
							$sqldiameter .= "ELSE '$i' ";
						}
					}
					$sqldiameter .= 'END as range';
					foreach($modTerima as $i => $item){
						$sql = "SELECT jenis, $sqldiameter , 
								COUNT(*) AS pcs, ROUND(SUM(m3)::numeric, 3) AS m3 
								FROM t_terima_sengon_telly 
								WHERE terima_sengon_detail_id = ".$item->terima_sengon_detail_id."  
								GROUP BY jenis, range 
								ORDER BY range ";
						$ress = Yii::$app->db->createCommand($sql)->queryAll();
						$diameter = [];
						foreach($ress as $i => $res){
							if($res['jenis'] != "AFKIR"){
								$diameter[$res['range']] = $res;
							}
						}
						$model = new \app\models\TTagihanSengon();
						$modelload = \app\models\TTagihanSengon::find()->where(['posengon_id'=>$posengon_id,'tanggal_tagihan'=>$tanggal_tagihan,'terima_sengon_detail_id'=>$item->terima_sengon_detail_id])->one();
						if(!empty($modelload)){
							$model->attributes = $modelload->attributes;
							$model->tagihan_sengon_id = $modelload->tagihan_sengon_id;
							$model->spek = \yii\helpers\Json::decode($model->spek);
						}
						$model->terima_sengon_detail_id = $item->terima_sengon_detail_id;
						$model->tanggal_datang = \app\components\DeltaFormatter::formatDateTimeForUser2($item->tanggal_datang);
						$model->nopol = $item->nopol;
						$model->disetujui = \app\components\Params::DEFAULT_PEGAWAI_ID_PAK_WID;
						$model->no_urut = $item->no_urut;
						$data['kode'] = $model->kode;
						$data['detail'][] = $this->renderPartial('_itemDetail',['model'=>$model,'diameter'=>$diameter,'status'=>$item->status_bayar]);
					}
				}
				$po = \app\models\TPosengon::findOne($posengon_id);
				$data['suplier_nm'] = $po->suplier->suplier_nm;
			}
            return $this->asJson($data);
        }
    }
	
	public function actionHapusItem(){
		if(\Yii::$app->request->isAjax){
			$tagihan_sengon_id = Yii::$app->request->post('tagihan_sengon_id');
			$model = \app\models\TTagihanSengon::findOne($tagihan_sengon_id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
					if($model->delete()){
						$success_1 = true;
					}else{
						$data['message'] = Yii::t('app', 'Data Tagihan Gagal dihapus');
					}
					if ($success_1) {
						$transaction->commit();
						$data['status'] = true;
						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Tagihan Berhasil Dihapus');
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$tagihan_sengon_id]);
		}
	}
	
}

