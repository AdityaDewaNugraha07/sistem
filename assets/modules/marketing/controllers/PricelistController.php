<?php

namespace app\modules\marketing\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PricelistController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	
	public function actionIndex(){
        $model = new \app\models\MBrgProduk();
        $modHarga = new \app\models\MHargaProduk();
        /*
        if(isset($_GET['jp']) && isset($_GET['tp'])){
            $model->produk_group = $_GET['jp'];
        }
        */
        if(isset($_GET['jp']) && isset($_GET['tp'])){
            $model->produk_group = $_GET['jp'];
        } else {
            $model->produk_group = 'Platform';
        }
        
        if( Yii::$app->request->post('MHargaProduk')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = true;
                $model->load(\Yii::$app->request->post());
                foreach($_POST['MHargaProduk'] as $i => $detailpost){
                    $modCurrentHarga = \app\models\MHargaProduk::find()->where(['produk_id'=>$detailpost['produk_id'],'harga_tanggal_penetapan'=>\app\components\DeltaFormatter::formatDateTimeForDb($_POST['harga_tanggal_penetapan'])])->one();
                    if(count($modCurrentHarga)>0){
                        $modCurrentHarga->delete();
                    }
                    $modHarga = new \app\models\MHargaProduk();
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
                    return $this->redirect(['index','success'=>1,'jp'=>$model->produk_group,'tp'=>$modHarga->harga_tanggal_penetapan]);
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
            $modHarga = new \app\models\MHargaProduk();
			$jenis_produk = Yii::$app->request->get('jp');
            $selectgroup = "m_brg_produk.produk_id, m_brg_produk.produk_kode, m_brg_produk.produk_nama";
            $sql = "SELECT produk_id,produk_kode,produk_nama,produk_dimensi FROM m_brg_produk
                    WHERE produk_group = '".$jenis_produk."' 
                    AND active = TRUE
                    GROUP BY produk_id,produk_kode,produk_nama";
            $models = \Yii::$app->db->createCommand($sql)->queryAll();
            if(count($models)>0){
                return $this->renderAjax('_content',['models'=>$models,'modHarga'=>$modHarga]);
            }else{
                return '<tr><td colspan="6" style="text-align: center;"><i>'.Yii::t('app', 'Data tidak ditemukan').'</i></td></tr>';
            }
		}
    }
    
    public function actionSetTglDropdown(){
        if(\Yii::$app->request->isAjax){
			$produk_group = Yii::$app->request->post('produk_group');
            $mod = [];
            $sql = "SELECT harga_tanggal_penetapan FROM m_harga_produk
                    JOIN m_brg_produk ON m_brg_produk.produk_id = m_harga_produk.produk_id
                    WHERE m_brg_produk.produk_group = '".$produk_group."' 
                        AND m_harga_produk.active = TRUE 
                    ORDER BY harga_tanggal_penetapan DESC";
            $mod = \Yii::$app->db->createCommand($sql)->queryAll();
            
            $arraymap = \yii\helpers\ArrayHelper::map($mod, 'harga_tanggal_penetapan', 'harga_tanggal_penetapan');
            $html = "";
            foreach($arraymap as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',Yii::t('app', 'Price List Tanggal : ').\app\components\DeltaFormatter::formatDateTimeForUser($val),['value'=>$val]);
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
    
    public function actionSetPrice(){
        if(\Yii::$app->request->isAjax){
            $produk_id = Yii::$app->request->post('produk_id');
            $tgl_penetapan = Yii::$app->request->post('tgl_penetapan');
            $tgl_penetapan = \app\components\DeltaFormatter::formatDateTimeForDb($tgl_penetapan);
            $sql = "SELECT harga_distributor,harga_agent,harga_enduser,harga_hpp FROM m_harga_produk
                    WHERE produk_id = '".$produk_id."' AND harga_tanggal_penetapan = '".$tgl_penetapan."' AND active = TRUE";
            $models = \Yii::$app->db->createCommand($sql)->queryOne();
            if($models){
                $models['harga_distributor'] = \app\components\DeltaFormatter::formatNumberForUser($models['harga_distributor']);
                $models['harga_agent'] = \app\components\DeltaFormatter::formatNumberForUser($models['harga_agent']);
                $models['harga_enduser'] = \app\components\DeltaFormatter::formatNumberForUser($models['harga_enduser']);
                $models['harga_hpp'] = \app\components\DeltaFormatter::formatNumberForUser($models['harga_hpp']);
                $models['harga_distributor_formatted'] = \app\components\DeltaFormatter::formatUang($models['harga_distributor']);
                $models['harga_agent_formatted'] = \app\components\DeltaFormatter::formatUang($models['harga_agent']);
                $models['harga_enduser_formatted'] = \app\components\DeltaFormatter::formatUang($models['harga_enduser']);
                $models['harga_hpp_formatted'] = \app\components\DeltaFormatter::formatUang($models['harga_hpp']);
            }
            return $this->asJson($models);
            
		}
    }
    
    public function actionDelete($jp,$tp){
		if(\Yii::$app->request->isAjax){
            $jp = Yii::$app->request->get('jp');
            $tp = Yii::$app->request->get('tp');
            $pesan = "Apakah Anda yakin akan menghapus Price List '<b>".$jp."</b>' tanggal '<b>". \app\components\DeltaFormatter::formatDateTimeForUser2($tp)."</b>'";
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $produklist = [];
                    $modProduk = \app\models\MBrgProduk::find()->where(['produk_group'=>$jp])->all();
                    foreach($modProduk as $i => $produk){
                        $produklist[] = $produk->produk_id;
                    }
                    $delete = \app\models\MHargaProduk::deleteAll(['and', 'harga_tanggal_penetapan = :tanggal', ['in', 'produk_id', $produklist]], [':tanggal' => $tp ]);
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
			return $this->renderAjax('_deleteConfirm',['pesan'=>$pesan,'jp'=>$jp,'tp'=>$tp]);
		}
	}
	
}
