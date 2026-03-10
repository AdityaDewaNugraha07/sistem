<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class BlankController extends DeltaBaseController
{	
	
	public $defaultAction = 'index';

	// fungsi untuk menampilkan halaman index
	public function actionIndex() {

		// render halaman xxx
        return $this->render('index', []);
	}

}