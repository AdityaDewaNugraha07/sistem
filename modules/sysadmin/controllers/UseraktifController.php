<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class UseraktifController extends DeltaBaseController
{
	public $defaultAction = 'index';

	public function actionIndex(){
        if(\Yii::$app->request->get('dt')=='table-user'){
			$param['table']= \app\models\MUser::tableName();
			$param['pk']= \app\models\MUser::primaryKey()[0];
            $param['column'] = ['m_user.user_id',                       //0    
                                    'm_user.username',                  //1
                                    'm_user.active',              //2
                                    'm_pegawai.pegawai_id',             //3
                                    'm_pegawai.pegawai_nama',           //4
                                    'm_pegawai.active',                 //5
                                    'm_pegawai.pegawai_jk',             //6
                                    'm_departement.departement_id',     //7
                                    'm_departement.departement_nama',   //8
                                    'm_jabatan.jabatan_id',             //9
                                    'm_jabatan.jabatan_nama'];          //10
			$param['join']= ['left join m_pegawai on m_pegawai.pegawai_id = m_user.pegawai_id',
                                'left join m_jabatan on m_jabatan.jabatan_id = m_pegawai.jabatan_id',
                                'left join m_departement on m_departement.departement_id = m_pegawai.departement_id'];
            $param['where'] = ['m_user.login_status is true or m_pegawai.active is false'];
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
		}
		return $this->render('index');
	}
}
