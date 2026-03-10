<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class CoaController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        
		return $this->render('index');
	}
	
	public function actionSource(){
		if(\Yii::$app->request->isAjax){		
		$sql = "SELECT acct_tipe as text, min(acct_no) AS id FROM m_acct_rekening WHERE active = TRUE GROUP BY text ORDER BY id ASC";
		$roots = Yii::$app->db->createCommand($sql)->queryAll();
		foreach($roots as $i => $root){
			$data[$i] = $root;
			$data[$i]['id'] = $data[$i]['id'].'-root';
			$sql2 = "SELECT * FROM m_acct_rekening 
					WHERE acct_tipe = '".$root['text']."' AND acct_no ILIKE '%000%' AND active = TRUE AND acct_flag = FALSE 
					ORDER BY acct_no ASC";
			$childrens = Yii::$app->db->createCommand($sql2)->queryAll();
			foreach($childrens as $ii => $child){
				$data[$i]['children'][$ii]['id'] = $child['acct_no'];
				$data[$i]['children'][$ii]['text'] = '<span class="font-blue-steel">'.$child['acct_no']."</span> &nbsp;".$child['acct_nm'];
				$sql3 = "SELECT * FROM m_acct_rekening 
						WHERE acct_tipe = '".$root['text']."' AND active = TRUE 
						AND acct_no ILIKE '%".substr($child['acct_no'], 0,3)."%' AND acct_no != '".$child['acct_no']."' 
						ORDER BY acct_no ASC";
				$children3 = Yii::$app->db->createCommand($sql3)->queryAll();
				foreach($children3 as $iii => $child3){
					$data[$i]['children'][$ii]['children'][$iii]['id'] = $child3['acct_no'];
					$data[$i]['children'][$ii]['children'][$iii]['text'] = '<span class="font-blue-steel">'.$child3['acct_no']."</span> &nbsp;".$child3['acct_nm'];
					$data[$i]['children'][$ii]['children'][$iii]['icon'] = 'jstree-file';
				}
			}
		}
		return $this->asJson($data);
		}
	}
}
