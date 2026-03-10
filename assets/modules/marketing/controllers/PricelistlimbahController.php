<?php

namespace app\modules\marketing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PricelistlimbahController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	
	public function actionIndex(){
        $model = new \app\models\MBrgLimbah();
        $modHarga = new \app\models\MHargaLimbah();
        if( Yii::$app->request->post('MHargaLimbah')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = true;
                $model->load(\Yii::$app->request->post());
                foreach($_POST['MHargaLimbah'] as $i => $detailpost){
                    $modCurrentHarga = \app\models\MHargaLimbah::find()->where(['limbah_id'=>$detailpost['limbah_id'],'harga_tanggal_penetapan'=>\app\components\DeltaFormatter::formatDateTimeForDb($_POST['harga_tanggal_penetapan'])])->one();
                    if(count($modCurrentHarga)>0){
                        $modCurrentHarga->delete();
                    }
                    $modHarga = new \app\models\MHargaLimbah();
                    $modHarga->attributes = $detailpost;
                    $modHarga->harga_tanggal_penetapan = isset($_POST['harga_tanggal_penetapan'])?\app\components\DeltaFormatter::formatDateTimeForDb($_POST['harga_tanggal_penetapan']):'';
					
                    if($modHarga->validate()){
                        if($modHarga->save()){
                            $success_1 &= true;
                        }else{
                            $success_1 &= false;
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($modHarga); 
                    }
                }
                if ($success_1) {
                    $transaction->commit();
                    $data['status'] = true;
                    $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                    return $this->redirect(['index','success'=>1,'tp'=>$modHarga->harga_tanggal_penetapan]);
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
        }
		return $this->render('index',['model'=>$model,'modHarga'=>$modHarga]);
	}
	
    public function actionGetContent(){
        if(\Yii::$app->request->isAjax){
            $modHarga = new \app\models\MHargaLimbah();
			$tgl = Yii::$app->request->post('tgl');
			$tipe = Yii::$app->request->post('tipe');
			$check = \Yii::$app->db->createCommand("SELECT harga_tanggal_penetapan FROM m_harga_limbah ORDER BY harga_tanggal_penetapan DESC")->queryOne();
			$data['html'] = '';
			if($tipe == "input"){
				$tgl = $check['harga_tanggal_penetapan'];
			}
			$tgl = !empty($tgl)?$tgl:date("Y-m-d");			
			$modLimbahs = \app\models\MBrgLimbah::find()->where(['active'=>true])->orderBy("seq ASC")->all();
			if(count($modLimbahs)>0){
				foreach($modLimbahs as $i => $modLimbah){
					$model = new \app\models\MHargaLimbah();
					$modHarga = \Yii::$app->db->createCommand("SELECT * FROM m_harga_limbah WHERE harga_tanggal_penetapan = '{$tgl}' AND limbah_id = {$modLimbah->limbah_id}")->queryOne();
					$model->limbah_id = $modLimbah->limbah_id;
					$model->limbah_kode = $modLimbah->limbah_kode;
					$model->limbah_nama = $modLimbah->limbah_nama;
					$model->limbah_satuan_jual = $modLimbah->limbah_satuan_jual;
					$model->limbah_satuan_muat = $modLimbah->limbah_satuan_muat;
					$model->harga_enduser = isset($modHarga['harga_enduser'])?$modHarga['harga_enduser']:0;
					$model->harga_keterangan = isset($modHarga['harga_keterangan'])?$modHarga['harga_keterangan']:"";
					
					$data['html'] .= $this->renderPartial('_content',['model'=>$model,'i'=>$i,'tipe'=>$tipe]);
				}
			}else{
				$data['html'] = '<tr><td colspan="6" style="text-align: center;"><i>'.Yii::t('app', 'Data tidak ditemukan').'</i></td></tr>';
			}
			return $this->asJson($data);
		}
    }
    
    public function actionSetPrice(){
        if(\Yii::$app->request->isAjax){
            $limbah_id = Yii::$app->request->post('limbah_id');
            $tgl_penetapan = Yii::$app->request->post('tgl_penetapan');
            $tgl_penetapan = \app\components\DeltaFormatter::formatDateTimeForDb($tgl_penetapan);
            $sql = "SELECT harga_enduser,harga_keterangan FROM m_harga_limbah
                    WHERE limbah_id = '".$limbah_id."' AND harga_tanggal_penetapan = '".$tgl_penetapan."' AND active = TRUE";
            $models = \Yii::$app->db->createCommand($sql)->queryOne();
            if($models){
                $models['harga_enduser'] = \app\components\DeltaFormatter::formatNumberForUser($models['harga_enduser']);
                $models['harga_enduser_formatted'] = \app\components\DeltaFormatter::formatUang($models['harga_enduser']);
                $models['harga_keterangan'] = $models['harga_keterangan'];
            }
            return $this->asJson($models);
            
		}
    }
    
    public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            $id = Yii::$app->request->get('id');
            $pesan = "Apakah Anda yakin akan menghapus Price List tanggal '<b>". \app\components\DeltaFormatter::formatDateTimeForUser2($id)."</b>'";
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $produklist = [];
                    $delete = \app\models\MHargaLimbah::deleteAll(['and', 'harga_tanggal_penetapan = :tanggal'], [':tanggal' => $id ]);
                    if($delete){
                        $success_1 = true;
                    }else{
                        $data['message'] = Yii::t('app', 'Data Price List Gagal dihapus');
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Price List Berhasil Dihapus');
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id]);
		}
	}
	
}
