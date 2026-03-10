<?php

namespace app\modules\sysadmin\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class CatatanController extends DeltaBaseController
{	
	
	public $defaultAction = 'index';

	// fungsi untuk menampilkan halaman index
	public function actionIndex() {
        $model = new \app\models\TCatatan();

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
            $param['table'] = \app\models\TCatatan::tableName();

            // tenntukan primary key
            $param['pk'] = $param['table'].".". \app\models\TCatatan::primaryKey()[0];
            
            // siapkan kolom yang mau diambil untuk kolom datatables
            $param['column'] = [$param['table'].'.catatan_id',          // 0
                                    $param['table'].'.tanggal',         // 1
                                    $param['table'].'.jam',             // 2
                                    $param['table'].'.judul',           // 3
                                    $param['table'].'.keterangan',      // 4
                                ];

            $param['join'] = ['JOIN m_user ON m_user.user_id = '.$param['table'].'.user_id
                                    JOIN m_pegawai on m_pegawai.pegawai_id = m_user.pegawai_id' 
                            ];
            
			// munculkan data di halaman index
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));

		}

		// render halaman index
		return $this->render('index');
	}


	// fungsi untuk menampilkan halaman create
	public function actionCreate(){
		// siapkan database
		$model = new \app\models\TCatatan();

		// jika ada varible post (user sudah menginput dan submit form)
		if($model->load(Yii::$app->request->post())) {

			// mulai transaksi
            $transaction = \Yii::$app->db->beginTransaction();

            // validasi
            try {

                // set variable success
                $success_1 = false;
                $success_2 = false;
                
                // load semua variable post
                $model->load(\Yii::$app->request->post());

                // load variable file
                $model->file = \yii\web\UploadedFile::getInstance($model, 'catatan_gambar');

                // validasi
                if ($model->validate()) {

                	// jika file tidak kosong
                    if(!empty($model->file)){

                    	// buat variable randomstring pada nama file untuk keamanan (?)
                        $randomstring = Yii::$app->getSecurity()->generateRandomString(4);

                        // siapkan direktori penyimpanan
                        $dir_path = Yii::$app->basePath.'/web/uploads/catatan';

                        // jika belum ada, buat direktori penyimpanan 
                        if(!is_dir($dir_path)){
                            mkdir($dir_path);
                        }

                        // tentukan nama file yang baru
                        $file_path = $dir_path.'/'.date('Y-m-d_H-i-s').'-'.$model->file->baseName.'-'.$randomstring.'.' . $model->file->extension;

                        // simpan file dalam direktori penyimpanan
                        $model->file->saveAs($file_path,false);

                        // siapkan variable file untuk disimpan dalam kolom table database
                        $model->catatan_gambar = date('Y-m-d_H-i-s').'-'.$model->file->baseName.'-'.$randomstring.'.' .$model->file->extension;
                    }

                    // jika okeh, siap untuk disimpan
                    if ($model->save()) {
                        $success_1 = true;
                    }

                    // cek jam 
                    if (!empty($model->jam)) {
                        $success_2 = true;
                    }

                } else {
                	// jika tidak okeh, munculkan pesan validasi error
                    $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                }

                // jika sukses
                if ($success_1 && $success_2) {
                    // query database untuk disimpan
                    $transaction->commit();

                    // notifikasi sukses
                    $data['status'] = true;
                    $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');

                    // kembali ke halaman index
                    return $this->redirect(['index']);

                } else {

                	// jika validasi gagal tarik ulang data
                    $transaction->rollback();
                    return $this->redirect(['create']);
                }
            } catch (\yii\db\Exception $ex) {
            	// jika gagal tarik ulang data
                $transaction->rollback();

                // munculkan pesan error
                $data['message'] = $ex;
            }

            return $this->asJson($data);
		}

        // render halaman create
		return $this->render('create', ['model' => $model]);
	
	}

	// fungsi menampilkan modal info
    public function actionInfo($id){
		if(\Yii::$app->request->isAjax){
            // ambil data pada tabel t_catatan
			$model = \app\models\TCatatan::findOne($id);

            // ambil user_id dari t_catatan untuk relasi dengan tabel m_user
            // ini sebenernya nggak perlu karena di file /models/TCatatan.php sudah direlasikan
            // tapi untuk bantu logika alur nya ya tulis saja lah
            // $user_id = $model->user_id;

            // relasikan tabel t_catatan dengan tabel m_user
            // ini sebenernya juga nggak perlu karena di file /models/TCatatan.php sudah direlasikan
            // tapi untuk bantu logika alur nya ya tulis saja lah
            //$pegawai_id = $model->user->pegawai_id;

            // relasikan tabel t_catatan dengan m_pegawai melalui m_user :))
            // ini sebenernya juga nggak perlu karena di file /models/TCatatan.php sudah direlasikan
            // tapi untuk bantu logika alur nya ya tulis saja lah
            //$pegawai_nama = $model->user->pegawai->pegawai_nama;
			
            // variable pegawai nama tidak perlu dikirim ke halaman info
            // pada halaman info, variable pegawai_nama tinggal dipanggil dengan cara :
            // $model->user->pegawai->pegawai_nama;
            //return $this->renderAjax('info',['model'=>$model, 'pegawai_nama' => $pegawai_nama]);

            return $this->renderAjax('info',['model'=>$model]);
		}
	}

	// fungsi menampilkan modal image
    public function actionImage($id, $gambar){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TCatatan::findOne($id);
			return $this->renderAjax('image',['model'=>$model, 'gambar'=>$gambar]);
		}
	}	

	// fungsi untuk menampilkan halaman edit
	public function actionEdit($id){

		// siapkan database untuk mengambil data yang akah diedit
        $model = \app\models\TCatatan::findOne($id);
		$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);

        // jika ada variable post (user sudah menginput update data)
		if( Yii::$app->request->post('TCatatan')){

            // mulai transaksi
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                // set variable success_1 false dulu
                $success_1 = false;

                // ambil nama file/gambar lama
                $file_old = $model->catatan_gambar;

                // load variable post
                $model->load(\Yii::$app->request->post());

                // ambil semua variable file/gambar
                $model->file = \yii\web\UploadedFile::getInstance($model, 'catatan_gambar');

                // siapkan data file/gambar yang akan diupload
                // jika ada file/gambar baru gunakan yang baru, jika tidak gunakan data lama
                $model->catatan_gambar = !empty($model->catatan_gambar)? $model->catatan_gambar : $file_old;

                // validasi
                if($model->validate()){

                    // jika file/gambar yang diupload tidak kosong
                    if(!empty($model->file)){

                        // buat variable randomstring pada nama file untuk keamanan (?)
                        $randomstring = Yii::$app->getSecurity()->generateRandomString(4);

                        // siapkan direktori penyimpanan
                        $dir_path = Yii::$app->basePath.'/web/uploads/catatan';

                        // jika belum ada, buat direktori penyimpanan
                        if(!is_dir($dir_path)){
                            mkdir(Yii::$app->basePath.'/web/uploads/catatan');
                            mkdir($dir_path);
                        }

                        // tentukan nama file yang baru
                        $file_path = $dir_path.'/'.date('Y-m-d_H-i-s').'-'.$model->file->baseName.'-'.$randomstring.'.' . $model->file->extension;

                        // simpan file dalam direktori penyimpanan
                        $model->file->saveAs($file_path,false);

                        // siapkan variable file untuk disimpan dalam kolom table database
                        $model->catatan_gambar = date('Y-m-d_H-i-s').'-'.$model->file->baseName.'-'.$randomstring.'.' .$model->file->extension;

                        // jika masih ada file lama, hapus dulu
                        if($file_old != null){
                            if (file_exists(Yii::$app->basePath.'/web/uploads/catatan/'.$file_old)) {
                                unlink(Yii::$app->basePath.'/web/uploads/catatan/'.$file_old);
                            }
                        }
                    }

                    // jika okeh, siap untuk disimpan
                    if ($model->save()) {

                        // set variable success_1 = true
                        $success_1 = true;
                    }

                // jika validasi gagal, kembalikan ke fungsi validasi
                } else {
                    $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                }

                // jika success
                if ($success_1) {

                    // query database untuk disimpan
                    $transaction->commit();

                    // notifikasi sukses
                    $data['status'] = true;
                    $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Diupdate');

                    // redirect ke index
                    return $this->redirect(['index']);
                
                // jika gagal
                } else {
                    // jika gagal tarik ulang data
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

		// render halaman index
		return $this->render('edit', ['model' => $model]);
		
	}


    // fungsi menghapus data
    public function actionDelete($id){

        // untuk hapus data, tidak perlu post, cukup ajax
        if(\Yii::$app->request->isAjax){

            // variable tableid dibawa dari info.php
            $tableid = Yii::$app->request->get('tableid');

            // siapkan database untuk mengambil data yang akah dihapus
            $model = \app\models\TCatatan::findOne($id);

            // ambil nama file/gambar lama
            $file_old = $model->catatan_gambar;
            
            // jika user mengirim varible post untuk menghapus data
            if( Yii::$app->request->post('deleteRecord')){

                // mulai transaksi
                $transaction = \Yii::$app->db->beginTransaction();

                // validasi
                try {

                    // set variable success_1 false dulu 
                    $success_1 = false;

                    // jika beneran mau dihapus
                    if($model->delete()){

                        // jika file lama tidak kosong
                        if($file_old != null){

                            // dan jika file lama beneran ada
                            if (file_exists(Yii::$app->basePath.'/web/uploads/catatan/'.$file_old)) {

                                // hapus file lama
                                unlink(Yii::$app->basePath.'/web/uploads/catatan/'.$file_old);
                            }
                        }

                        // set variable success_ 1
                        $success_1 = true;

                    // jika gagal
                    }else{

                        // notifikasi gagal
                        $data['message'] = Yii::t('app', 'Data Gagal dihapus');
                    }

                    // jika sukses
                    if ($success_1) {

                        // query database untuk dihapus
                        $transaction->commit();

                        // notifikasi berhasil dihapus
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
                        
                    // jika gagal
                    } else {

                        // jika gagal tarik ulang data
                        $transaction->rollback();
                        $data['status'] = false;

                        // notifasi gagal
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } 

                    catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }

                return $this->asJson($data);
            }

            // render modal konfirmasi hapus
            return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid]);
        }
    }

}