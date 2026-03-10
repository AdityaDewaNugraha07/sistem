<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class UseraccessController extends DeltaBaseController
{
	
	public $defaultAction = 'index';
	
    public function actionIndex(){
        $modUserGroup = \app\models\MUserGroup::find()->where(['active'=>TRUE])->andWhere("user_group_id != ".\app\components\Params::USER_GROUP_ID_SUPER_USER)->orderBy(['name'=>SORT_ASC])->all();
        return $this->render('index',['modUserGroup'=>$modUserGroup]);
	}
    
    public function actionLoadUserAccessContent(){
        if(\Yii::$app->request->isAjax){
            $user_group_id = Yii::$app->request->post('user_group_id');
            if(!$user_group_id){
                return $this->asJson("<h5>-- ".Yii::t('app', 'Pilih User Group')." --</h5>");
            }
            $modUserGroup = \app\models\MUserGroup::findOne($user_group_id);
            $modUserAccess = \app\models\MUserAccess::find()->where(['user_group_id'=>$user_group_id])->all();
            return $this->renderAjax('partial/_userAccessContent',['modUserGroup'=>$modUserGroup,'modUserAccess'=>$modUserAccess]);
        }
    }
    
    public function actionCreate($id){
		if(\Yii::$app->request->isAjax){
            $model = new \app\models\MUserAccess();
            $modUserGroup = \app\models\MUserGroup::findOne($id);
            $model->user_group_id = $modUserGroup->user_group_id;
            if( Yii::$app->request->post('MUserAccess')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if(isset($_POST['MUserAccess']['menu_id'])){
                        if(count($_POST['MUserAccess']['menu_id'] > 0)){
                            $success_1 = true;
                            foreach($_POST['MUserAccess']['menu_id'] as $i => $menu_id){
                                $model = new \app\models\MUserAccess();
                                $model->load(\Yii::$app->request->post());
                                $model->user_group_id = \Yii::$app->request->post("MUserAccess")['user_group_id'];
                                $model->menu_id = $menu_id;
                                if($model->validate()){
                                    if($model->save()){
                                        $success_1 = true & $success_1;
                                    }
                                }else{
                                    $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                                }
                            }
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
                    if ($success_1) {
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
            return $this->renderAjax('create',['model'=>$model,'modUserGroup'=>$modUserGroup]);
        }
    }
    
    public function actionSetDropdownMenu(){
        if(\Yii::$app->request->isAjax){
			$menu_group_id = Yii::$app->request->post('menu_group_id');
			$user_group_id = Yii::$app->request->post('user_group_id');
			$module_id = Yii::$app->request->post('module_id');
            $usergrouplist = '';
            $mod = [];
            
            if($menu_group_id!='' || $module_id!=''){
                $query = "
                    SELECT m_menu.menu_id FROM m_menu
                    JOIN m_user_access ON m_user_access.menu_id = m_menu.menu_id
                    WHERE m_menu.active IS TRUE AND m_user_access.user_group_id = ".$user_group_id." 
                        ".(($menu_group_id!='')?'AND m_menu.menu_group_id = '.$menu_group_id:'')."
                    GROUP BY m_menu.menu_id
                    ORDER BY m_menu.sequence ASC
                ";
                $cekmenu = Yii::$app->db->createCommand($query)->queryAll();
                if(count($cekmenu)>0){
                    foreach($cekmenu as $i => $value){
                        $usergrouplist .= $value['menu_id'];
                        $usergrouplist .= ($i+1 == count($cekmenu))?'':',';
                    }
                }
                $query = "
                    SELECT m_menu.menu_id, m_menu.name FROM m_menu
                    JOIN m_module ON m_module.module_id = m_module.module_id
                    WHERE m_menu.active IS TRUE
                        ".(($usergrouplist!='')?'AND menu_id NOT IN ('.$usergrouplist.')':'')."
                        ".(($module_id!='')?'AND m_menu.module_id = '.$module_id:'')."
                        ".(($menu_group_id!='')?'AND m_menu.menu_group_id = '.$menu_group_id:'')."
                    GROUP BY m_menu.menu_id, m_menu.name
                    ORDER BY m_menu.menu_group_id ASC, m_menu.sequence ASC
                ";
                $mod = Yii::$app->db->createCommand($query)->queryAll();
                
            }
			$arraymap = \yii\helpers\ArrayHelper::map($mod, 'menu_id', 'name');
			$html = \yii\bootstrap\Html::tag('option');
			foreach($arraymap as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i,'id'=>$i]);
			}
			$data['html']= $html;
            $data['result_total'] = count($arraymap);
			return $this->asJson($data);
		}
    }
    
    function actionSetDropdownKabupaten(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->post('provinsi_id');
			
			$mod = \app\models\KabupatenM::find()->where(['active'=>true,'provinsi_id'=>$id])->orderBy('nama ASC')->all();
			$arraymap = \yii\helpers\ArrayHelper::map($mod, 'kabupaten_id', 'nama');
			
			$html = \yii\bootstrap\Html::tag('option');
			foreach($arraymap as $i => $val){
				$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
			}
			$data['html'] = $html;
			return $this->asJson($data);
		}
	}
    
    public function actionDelete($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\MUserAccess::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    if($model->delete()){
                        $success_1 = true;
                    }else{
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Menu Access Gagal dihapus');
                    }
                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
						$data['callback'] = 'setUserGroup('.$model->user_group_id.')';
                        $data['message'] = Yii::t('app', 'Menu Access Berhasil Dihapus');
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid]);
		}
	}
    public function actionDeleteAll($id){
		if(\Yii::$app->request->isAjax){
            $tableid = Yii::$app->request->get('tableid');
			$model = \app\models\MUserAccess::find()->where(['user_group_id'=>$id])->all();
            $pesan = 'Apakah Anda yakin akan menghapus semua Accessible Menu pada Group User Ini?';
                if( Yii::$app->request->post('deleteRecord')){
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $success_1 = false;
                        if(count($model)>0){
                            if(\app\models\MUserAccess::deleteAll('user_group_id = '.$id)){
                                $success_1 = true;
                            }else{
                                $data['message'] = Yii::t('app', '<i class="icon-check"></i> Menu Access Gagal dihapus');
                            }
                        }
                        if ($success_1) {
                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = Yii::t('app', 'Menu Access Berhasil Dihapus');
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
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'pesan'=>$pesan,'actionname'=>'DeleteAll','tableid'=>$tableid]);
		}
	}
	
}
