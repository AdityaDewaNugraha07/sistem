<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\MPegawai;
use app\models\MDepartement;
use app\models\MJabatan;
use app\models\MUser;

class EmailController extends DeltaBaseController
{	
	
        public $defaultAction = 'index';

        // fungsi untuk menampilkan halaman index
        public function actionIndex() {
                $sql_model = "select a.pegawai_nama, b.departement_nama ". 
                                "   , case ".
                                "   when b.departement_nama = 'Log Purchasing' then 'Purchasing Log' ".
                                "   else b.departement_nama ".
                                "   end ". 
                                "   , case ". 
                                "       when c.jabatan_nama = 'Generap Manager' then 'GM' ".
                                "       when c.jabatan_nama = 'Kepala Departement' then 'Kadep' ".
                                "       when c.jabatan_nama = 'Kepala Divisi' then 'Kadiv' ".
                                "       when c.jabatan_nama = 'Karyawan' then 'Staff' ".                        
                                "       else c.jabatan_nama ". 
                                "   end ". 
                                "   , d.username ". 
                                "   from m_pegawai a ".
                                "   left join m_departement b on b.departement_id = a.departement_id ". 
                                "   left join m_jabatan c on c.jabatan_id = a.jabatan_id ".
                                "   left join m_user d on d.pegawai_id = a.pegawai_id ".
                                "   where a.active = 'true' ". 
                                "   order by b.departement_nama asc, c.jabatan_id asc, a.pegawai_nama ".
                                "   ";   
                $model = Yii::$app->db->createCommand($sql_model)->queryAll();

                // panggil fungsi di \controller\SendmailController.php
                $send_mail = Yii::$app->runAction("/sendmail/email");

                // render halaman index
                return $this->render('index', ['model'=>$model]);
        }
}