<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\MUser;
use app\models\MPegawai;

class GpsController extends DeltaBaseController
{	
	
	public $defaultAction = 'index';
	
	public function actionIndex(){
		$latitude = '';
		$latitudex = '';
		$longitude = '';
		$longitudex = '';
		return $this->render('index',['latitude'=>$latitude, 'longitude'=>$longitude, 'latitudex'=>$latitudex, 'longitudex'=>$longitudex]);
	}

	public function actionSaveLoc(){
		//$latitudex = "select latitude from t_loc order by id desc limit 1";
		//	$latitudex = Yii::$app->db->createCommand($latitudex)->queryScalar();
		//$longitudex = "select longitude from t_loc order by id desc limit 1";
		//	$longitudex = Yii::$app->db->createCommand($latitudex)->queryScalar();

		/*$datetime = date("Y-m-d H:i:s");
		$latitude = Yii::$app->request->post('latitude');
		$longitude = Yii::$app->request->post('longitude');
		$ipaddress = Yii::$app->request->post('ipaddress');
			$ipaddress_x = substr($ipaddress,0,9);
		$agent = $_SERVER['HTTP_USER_AGENT'];
			$agent_x = substr($agent, 0, 23);
		//if ($agent_x != 'Mozilla/5.0 (Windows NT' && $ipaddress_x != '10.10.10.') {
			$sql_insert = "insert into t_loc (datetime, latitude,longitude,agent,ipaddress) ".
								"	values ('".$datetime."','".$latitude."','".$longitude."', '".$agent."','".$ipaddress."') ". 
								"	";
			$query_insert = Yii::$app->db->createCommand($sql_insert)->execute();
		//}
		*/
		//return $this->render('index',['latitude'=>$latitude, 'longitude'=>$longitude, 'ipaddress'=>$ipaddress]);
		return $this->render('index');
	}

	public function actionShowLoc(){
		$latitude = Yii::$app->request->post('latitude');
		$longitude = Yii::$app->request->post('longitude');

		if (!empty($latitude) && !empty($longitude)) {
			$latitudex = $latitude;
			$longitudex = $longitude;
		} else {
			$latitudex = "select latitude from t_loc order by id desc limit 1";
				$latitudex = Yii::$app->db->createCommand($latitudex)->queryScalar();
			$longitudex = "select longitude from t_loc order by id desc limit 1";
				$longitudex = Yii::$app->db->createCommand($longitudex)->queryScalar();
		}

		return $this->render('_show',['latitudex'=>$latitudex, 'longitudex'=>$longitudex]);
	}	
}

