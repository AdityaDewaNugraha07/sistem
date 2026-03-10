<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class SiteController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	public function actionIndex(){
		$model = \app\models\CCompanyProfile::findOne(1);
		if( Yii::$app->request->post('CCompanyProfile')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false;
                $model->load(\Yii::$app->request->post());
                if($model->validate()){
                    if($model->save()){
                        $success_1 = true;
                    }
                }
                if ($success_1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1]);
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
	
	public function actionSystemconfig(){
		$model = \app\models\CSiteConfig::findOne(1);
		if(\Yii::$app->request->post('update')){
			$transaction = \Yii::$app->db->beginTransaction();
			try {
				$success_1 = TRUE;
				$notif = \Yii::$app->request->post('notif');
				if(!empty($notif)){
					$model->notifikasi = ($notif=="ON")?TRUE:FALSE;
				}
				if($model->validate()){
					$success_1 = $model->save();
				}else{
					$success_1 = FALSE;
				}
				if ($success_1) {
					$transaction->commit();
					$data['status'] = true;
					$data['callback'] = '';
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
		return $this->render('systemconfig',['model'=>$model]);
    }
    
	public function actionSystemupdate(){
		$data = '';
		exec('git pull origin master',$output);
		if(!empty($output)){
			foreach($output as $i => $res){
				$data .= $res."<br>";
			}
		}
	}
	
	public function actionBackupdb(){
		header('Content-type: text/plain');
		system('pg_dump test');
	}
	
	public function actionServermonitor(){
		if(\Yii::$app->request->isAjax){
			if( Yii::$app->request->post('getSummary')){
				$data['info1'] = '';
				exec('lscpu',$info1);
				if(!empty($info1)){
					foreach($info1 as $res1){
						$data['info1'] .= $res1."<br>";
					}
				}
				$data['info2'] = '';
				exec('hostnamectl',$info2);
				if(!empty($info2)){
					foreach($info2 as $res2){
						$data['info2'] .= $res2."<br>";
					}
				}
				$data['temp'] = '';
				exec('sensors',$temp);
				if(!empty($temp)){
					foreach($temp as $res3){
						$data['temp'] .= $res3."<br>";
					}
				}
				$data['memory'] = '';
				exec('free -m',$memory);
				if(!empty($memory)){
					foreach($memory as $i => $res4){
						if($i!=2){
							$data['memory'] .= $res4."<br>";
						}
					}
				}
				
				return $this->asJson($data);
			}
			if( Yii::$app->request->post('getRealtime')){
				$data['temp'] = [];
				exec("sensors | grep -oP 'Physical.*?\+\K[0-9.]+'",$physical);
				exec("sensors | grep -oP 'temp1.*?\+\K[0-9.]+'",$pci_adapter);
				exec("sensors | grep -oP 'Core 0.*?\+\K[0-9.]+'",$core_0);
				exec("sensors | grep -oP 'Core 1.*?\+\K[0-9.]+'",$core_1);
				exec("sensors | grep -oP 'Core 2.*?\+\K[0-9.]+'",$core_2);
				exec("sensors | grep -oP 'Core 3.*?\+\K[0-9.]+'",$core_3);
				$data['temp']['physical'] = $physical[0]." &deg;C";
				//$data['temp']['pci_adapter'] = $pci_adapter[0]." &deg;C";
				$data['temp']['core_0'] = $core_0[0]." &deg;C";
				$data['temp']['core_1'] = $core_1[0]." &deg;C";
				$data['temp']['core_2'] = $core_2[0]." &deg;C";
				$data['temp']['core_3'] = $core_3[0]." &deg;C";
				
				$data['cpu'] = [];
				exec("grep 'cpu ' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'",$cpu);
				exec("grep 'cpu0' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'",$cpu0);
				exec("grep 'cpu1' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'",$cpu1);
				exec("grep 'cpu2' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'",$cpu2);
				exec("grep 'cpu3' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'",$cpu3);
				exec("grep 'cpu4' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'",$cpu4);
				exec("grep 'cpu5' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'",$cpu5);
				exec("grep 'cpu6' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'",$cpu6);
				exec("grep 'cpu7' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'",$cpu7);
				$data['cpu']['cpu'] = $cpu[0];
				$data['cpu']['cpu0'] = $cpu0[0];
				$data['cpu']['cpu1'] = $cpu1[0];
				$data['cpu']['cpu2'] = $cpu2[0];
				$data['cpu']['cpu3'] = $cpu3[0];
				$data['cpu']['cpu4'] = $cpu4[0];
				$data['cpu']['cpu5'] = $cpu5[0];
				$data['cpu']['cpu6'] = $cpu6[0];
				$data['cpu']['cpu7'] = $cpu7[0];
				
        //				$data['memory'] = '';
        //				exec('free -m',$memory);
        //				if(!empty($memory)){
        //					foreach($memory as $i => $res4){
        //						if($i!=2){
        //							$data['memory'] .= $res4."<br>";
        //						}
        //					}
        //				}
				return $this->asJson($data);
			}
		}
		return $this->render('servermonitor',[]);
    }
    
    public function actionServermonitors(){
		return $this->render('servermonitors',[]);
	}
    
    public function actionCdb() {
        $model = new \app\models\TSpb();    

        if (Yii::$app->request->post()) {
            $_POST['TSpb']['tgl_awal'] != "" ? $tgl_awal = $_POST['TSpb']['tgl_awal'] : $tgl_awal = date('d/m/Y');
            $_POST['TSpb']['tgl_akhir'] != "" ? $tgl_akhir = $_POST['TSpb']['tgl_akhir'] : $tgl_akhir = date('d/m/Y');
            $_POST['TSpb']['spb_kode'] != "" ? $spb_kode = $_POST['TSpb']['spb_kode'] : $spb_kode = '';
            $_POST['TSpb']['departement_id'] != "" ? $departement_id = $_POST['TSpb']['departement_id'] : $departement_id = '';
            $_POST['TSpb']['spb_status'] != "" ? $spb_status = $_POST['TSpb']['spb_status'] : $spb_status = '';
            $_POST['TSpb']['approve_status'] != "" ? $approve_status = $_POST['TSpb']['approve_status'] : $approve_status = '';
            $_POST['TSpb']['spb_diminta'] != "" ? $spb_diminta = $_POST['TSpb']['spb_diminta'] : $spb_diminta = '';

            $model->tgl_awal = $tgl_awal;
            $model->tgl_akhir = $tgl_akhir;
            $model->spb_kode = $spb_kode;
            $model->departement_id = $departement_id;
            $model->spb_status = $spb_status;
            $model->approve_status = $approve_status;
            $model->spb_diminta = $spb_diminta;
        }
        
        if (!empty($model->tgl_awal)) {
            $andTanggal = " and (spb_tanggal between '".\app\components\DeltaFormatter::formatDateTimesForDb($tgl_awal)."' and '".\app\components\DeltaFormatter::formatDateTimesForDb($tgl_akhir)."')";
        } else {
            $andTanggal = "";
        }

        if (!empty($model->spb_kode)) {
            $andSpbKode = " and spb_kode ilike '%".$spb_kode."%'";
        } else {
            $andSpbKode = "";
        }

        if (!empty($model->departement_id)) {
            $andDepartementId = " and m_departement.departement_id = ".$departement_id."";
        } else {
            $andDepartementId = "";
        }

        if (!empty($model->spb_status)) {
            $andSpbStatus = " and spb_status = '".$spb_status."'";
        } else {
            $andSpbStatus = "";
        }

        if (!empty($model->spb_diminta)) {
            $andSpbDiminta = " and spb_diminta = ".$spb_diminta."";
        } else {
            $andSpbDiminta = "";
        }

        if (!empty($model->approve_status)) {
            $andApproveStatus = " and approve_status = '".$approve_status."'";
        } else {
            $andApproveStatus = "";
        }

        $total_rows1 = Yii::$app->db->createCommand("select count(*) from t_spb")->queryScalar();
        $total_rows2 = Yii::$app->db2->createCommand("select count(*) from t_spb")->queryScalar();

        $sql = "select spb_id, spb_kode, spb_tanggal, spb_tipe, departement_nama, pegawai_nama, spb_status, approve_status ".
                    "   from t_spb ".
                    "   join m_departement on m_departement.departement_id = t_spb.departement_id ".
                    "   join m_pegawai on m_pegawai.pegawai_id = t_spb.spb_diminta ".
                    "   where 1=1 ".
                    "   ".
                    $andTanggal.
                    $andSpbKode.
                    $andDepartementId.
                    $andSpbStatus.
                    $andSpbDiminta.
                    $andApproveStatus.
                    "   ".
                    "   order by t_spb.spb_id desc ".
                    "   limit 10 ".
                    "   ";
        $models1 = Yii::$app->db->createCommand($sql)->queryAll();
        $models2 = Yii::$app->db2->createCommand($sql)->queryAll();
        return $this->render('cdb',['total_rows1'=>$total_rows1,'total_rows2'=>$total_rows2,'model'=>$model,'models1'=>$models1,'models2'=>$models2]);
    }

    public function actionCdbInfo($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TSpb::findOne($id);
            $modDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$id])->all();
			return $this->renderAjax('cdb_info',['model'=>$model,'modDetail'=>$modDetail]);
		}
	}

    public function actionSetDropdownPegawai(){
        if(\Yii::$app->request->isAjax){
			$departement_id = Yii::$app->request->post('departement_id');
			$pegawai_id = Yii::$app->request->post('pegawai_id');
            $mod = [];
			if(!empty($departement_id)){
				$mod = \app\models\MPegawai::find()->where(['active'=>true,'departement_id'=>$departement_id])->orderBy(['pegawai_nama'=>SORT_ASC])->all();
			}else{
				$mod = \app\models\MPegawai::find()->where(['active'=>true,''])->orderBy(['pegawai_nama'=>SORT_ASC])->all();
			}
			$arraymap = \yii\helpers\ArrayHelper::map($mod, 'pegawai_id', 'pegawai_nama');
			$html = \yii\bootstrap\Html::tag('option','All',['value'=>'']);
			foreach($arraymap as $i => $val){
                if ($pegawai_id == $i) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i, $selected=>'selected']);
			}
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
}
