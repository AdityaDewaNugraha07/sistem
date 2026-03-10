<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\Zloggerz;
use app\models\MUser;
use app\models\MPegawai;

class ZloggerzController extends DeltaBaseController
{	
	
	public $defaultAction = 'index';

	// fungsi untuk menampilkan halaman index
	public function actionIndex() {
        $searchModel = new \app\models\ZloggerzSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		// jika ada request get untuk datatables
        if(\Yii::$app->request->get('dt')=='table-catatan'){

            // JIKA TABEL YANG DIAMBIL TANPA RELASI
        	// siapkan nama tabel
			//$param['table']= \app\models\TCatatan::tableName();

			// siapkan primary key
			//$param['pk']= \app\models\TCatatan::primaryKey()[0];

			// siapkan kolom yang mau diambil untuk kolom datatables
			//$param['column'] = ['catatan_id', ['col_name'=>'tanggal','formatter'=>'formatDateForUser2'], 'judul', 'keterangan', 'catatan_gambar'];
			

            // JIKA TABEL YANG DIAMBIL MEMPUNYAI RELASI DENGAN TABEL LAIN 
            // siapkan nama tabel
            $param['table'] = \app\models\Zloggerz::tableName();

            // tenntukan primary key
            $param['pk'] = $param['table'].".". \app\models\Zlogger::primaryKey()[0];
            
            // siapkan kolom yang mau diambil untuk kolom datatables
            $param['column'] = [$param['table'].'.id',			        // 0
            						$param['table'].'.level',     		// 1
                                    $param['table'].'.category',		// 2
                                    $param['table'].'.logtime',			// 3
                                    $param['table'].'.prefix',      	// 4
                                    $param['table'].'.message',			// 5
                                ];

            $param['join'] = ['JOIN m_user ON m_user.username = '.$param['table'].'.prefix
                                    JOIN m_pegawai on m_pegawai.pegawai_id = m_user.user_id' 
                            ];

			// munculkan data di halaman index
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));

		}

		// render halaman index
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

}