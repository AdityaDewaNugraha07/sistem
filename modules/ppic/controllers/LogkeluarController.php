<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\models\HPersediaanLog;
use app\models\TTerimaLogalamDetail;
use app\models\ViewTerimaLogalamPabrik;


class LogkeluarController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TLogKeluar();
        $model->tanggal = date("d/m/Y");
		$model->cara_keluar = 1;
        $model->pic_log_keluar = Yii::$app->user->identity->pegawai->pegawai_id;

		if(isset($_GET['log_keluar_id'])){
			$model = \app\models\TLogKeluar::findOne($_GET['log_keluar_id']);
			$model->kode = $model->kode;
			$model->tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);
            $model->cara_keluar == 'Trading' ? $model->cara_keluar = 0 : $model->cara_keluar = 1;
            $model->keterangan = $model->keterangan;
		}

        if( Yii::$app->request->post('TLogKeluar')){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = false; // t_log_keluar
                $success_2 = false; // h_persediaan_log
                $model->load(\Yii::$app->request->post());

                $kode = \app\components\DeltaGenerator::kodeLogKeluar();
                foreach ($_POST['ViewTerimaLogalamPabrik'] as $kolom) {                    
                    // T_LOG_KELUAR
                    $model = new \app\models\TLogKeluar();
                    $model->tanggal = $_POST['TLogKeluar']['tanggal'];
                    $model->no_barcode = $kolom['no_barcode'];
                    $_POST['TLogKeluar']['cara_keluar'] == 0 ? $model->cara_keluar = 'Trading' : $model->cara_keluar = "Industri";
                    $model->reff_no = $_POST['TLogKeluar']['reff_no'];
                    $model->keterangan = $_POST['TLogKeluar']['keterangan'];
                    $model->pic_log_keluar = $_POST['TLogKeluar']['pic_log_keluar'];
                    $model->kode = $kode;

                    // t_log_keluar : simpan data
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                        } else {
                            $success_1 = false;
                        }
                    }

                    // H_PERSEDIAAN
                    $modelHPersediaan = new \app\models\HPersediaanLog();
                    $modelHPersediaan->tgl_transaksi = $_POST['TLogKeluar']['tanggal'];
                    $modelHPersediaan->kayu_id = $kolom['kayu_id'];
                    $modelHPersediaan->no_grade = $kolom['no_grade'];
                    $modelHPersediaan->no_barcode = $kolom['no_barcode'];
                    $modelHPersediaan->no_btg = $kolom['no_btg'];
                    $modelHPersediaan->no_lap = $kolom['no_lap'];
                    $modelHPersediaan->status = 'OUT';
                    $modelHPersediaan->reff_no = $model->reff_no;
                    $diameter_rata = Yii::$app->db->createCommand("select diameter_rata from t_terima_logalam_detail where no_barcode = '".$kolom['no_barcode']."' ")->queryScalar();
                    $modelHPersediaan->fisik_diameter = $diameter_rata;
                    $modelHPersediaan->fisik_panjang = $kolom['panjang'];
                    $modelHPersediaan->fisik_volume = $kolom['volume'];
                    $kode_potong = Yii::$app->db->createCommand("select kode_potong from t_terima_logalam_detail where no_barcode = '".$kolom['no_barcode']."' ")->queryScalar();
                    $modelHPersediaan->pot = $kode_potong;

                    if ($model->cara_keluar == "Industri") { 
                        $modelHPersediaan->lokasi = 'PRODUKSI LOG ALAM';
                        $modelHPersediaan->keterangan = 'MUTASI LOG DARI GUDANG LOG ALAM MENUJU PRODUKSI';
                    } else {
                        $modelHPersediaan->lokasi = 'PENJUALAN LOG ALAM';
                        $modelHPersediaan->keterangan = 'MUTASI LOG DARI GUDANG LOG ALAM MENUJU PENJUALAN';
                    }
                    
                    $modelHPersediaan->fisik_pcs = 1;
                    $modelHPersediaan->diameter_ujung1 = $kolom['diameter_ujung1'];
                    $modelHPersediaan->diameter_ujung2 = $kolom['diameter_ujung2'];
                    $modelHPersediaan->diameter_pangkal1 = $kolom['diameter_pangkal1'];
                    $modelHPersediaan->diameter_pangkal2 = $kolom['diameter_pangkal2'];
                    $modelHPersediaan->cacat_panjang = $kolom['cacat_panjang'];
                    $modelHPersediaan->cacat_gb = $kolom['cacat_gb'];
                    $modelHPersediaan->cacat_gr = $kolom['cacat_gr'];
                    $fsc = Yii::$app->db->createCommand("select fsc from t_terima_logalam_detail where no_barcode = '".$kolom['no_barcode']."' ")->queryScalar();
                    $modelHPersediaan->fsc = $fsc;

                    // h_persediaan_log : simpan data
                    if($modelHPersediaan->validate()){
                        if($modelHPersediaan->save()){
                            $success_2 = true;
                        } else {
                            $success_2 = false;
                        }
                    }
                }
                // print_r($modelHPersediaan); exit;
                // echo"<pre>";print_r($_POST['ViewTerimaLogalamPabrik']);echo"<br>";echo"</pre>";exit;
                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    return $this->redirect(['index','success'=>1,'log_keluar_id'=>$model->log_keluar_id]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?(implode(",", array_values($errmsg)[0])):Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        
		return $this->render('index',['model'=>$model]);
	}

    public function actionShowDetail(){
		if(\Yii::$app->request->isAjax){
            $data = [];
			$data['status'] = false;
			$data['msg'] = "";
            if (substr($_POST['datas'], 0, 5) == "ID : ") {
                $data = explode("\n",$_POST['datas']);
                $baris_id = $data[0];
                    $terima_logalam_detail = explode(" : ",$baris_id);
                    $terima_logalam_detail_id = $terima_logalam_detail[1];
                $baris_kode = $data[1];
                    $no_barcode = explode(" : ",$baris_kode);
                    $no_barcode = $no_barcode[1];

                // cek dulu ada tidaknya data di t_terima_logalam_detail
                /*$sql_terima_logalam_id = "select count(terima_logalam_id) from t_terima_logalam_detail ". 
                                            "   where terima_logalam_detail_id = ".$terima_logalam_detail_id."".
                                            "   ";
                $t_terima_logalam = Yii::$app->db->createCommand($sql_terima_logalam_id)->queryScalar();*/
                $sql_h_persediaan_log_in = "select sum(fisik_pcs) from h_persediaan_log ". 
                                            "   where no_barcode = '".$no_barcode."' ". 
                                            "   and status = 'IN' ".
                                            "   ";
                $h_persediaan_log_in = Yii::$app->db->createCommand($sql_h_persediaan_log_in)->queryScalar();

                $sql_h_persediaan_log_out = "select sum(fisik_pcs) from h_persediaan_log ". 
                                                "   where no_barcode = '".$no_barcode."' ". 
                                                "   and status = 'OUT' ".
                                                "   ";
                $h_persediaan_log_out = Yii::$app->db->createCommand($sql_h_persediaan_log_out)->queryScalar();

                $stok = $h_persediaan_log_in - $h_persediaan_log_out;

                // jika ada data di t_terima_logalam_detail
                if ($stok > 0) {
                    // cek lagi sudah pernah keluar atau belum
                    //$sql_no_barcode = "select count(*) from t_log_keluar where no_barcode = '".$no_barcode."'";
                    //$no_barcode = Yii::$app->db->createCommand($sql_no_barcode)->queryScalar();
                    //if ($no_barcode > 0) {
                    //    $data['msg'] = "Log sudah pernah dikeluarkan";
                    //} else {
                        $data['msg'] = "Log siap dikeluarkan";
                        //$data['msg'] = "x = ".$sql_h_persediaan_log_in."<br>stok = ".$stok;
                        $data['terima_logalam_detail_id'] = $terima_logalam_detail_id;
                    //}
                // jika tidak ada data di t_terima_logalam_detail
                } else if ($sql_h_persediaan_log_in == 0) {
                    $data['msg'] = "Stok kosong";
                    //$data['msg'] = "y = ".$sql_h_persediaan_log_in."<br>stok = ".$stok;
                } else {
                    $data['msg'] = "Data tidak ditemukan";
                    //$data['msg'] = "z = ".$sql_h_persediaan_log_in."<br>stok = ".$stok;
                }
                /*$sql_peruntukan = "select peruntukan from t_terima_logalam where terima_logalam_id = ".$terima_logalam_id."";
                $peruntukan = Yii::$app->db->createCommand($sql_peruntukan)->queryScalar();
                $data['peruntukan'] = $peruntukan;

                if ($peruntukan == "Industri") {
                    $terima_logalam_detail_ = explode(" : ", $baris_kode);
                    $no_barcode = $terima_logalam_detail_[1];
                    $sql_countDetail = "select count(*) from t_terima_logalam_detail ". 
                                            "   where terima_logalam_detail_id = ".$terima_logalam_detail_id.
                                            "   and no_barcode = '".$no_barcode."' ";
                    $countDetail = Yii::$app->db->createCommand($sql_countDetail)->queryScalar();
                    $data['sql_countDetail'] = $sql_countDetail;
                    $data['countDetail'] = $countDetail;
                    if ($countDetail > 0) {
                        $modDetail = \app\models\TTerimaLogalamDetail::findOne(['terima_logalam_detail_id'=>$terima_logalam_detail_id, 'no_barcode'=>$no_barcode]);
                        $kayu_id = $modDetail->kayu_id;
                        $terima_logalam_id = $modDetail->terima_logalam_id;
                        $no_barcode = $modDetail->no_barcode;
                        $modKayu = \app\models\MKayu::findOne($kayu_id);
                        $model = \app\models\TTerimaLogalam::findOne($terima_logalam_id);
                        $modPabrik = new \app\models\TTerimaLogalamPabrik();
                        $data['terima_logalam_detail_id'] = $terima_logalam_detail_id;
                        $data['no_barcode'] = $no_barcode;
                        $sql_cek = "select count(*) from t_terima_logalam_pabrik where kode = '".$no_barcode."' ";
                        $jumlah_terima_pabrik = Yii::$app->db->createCommand($sql_cek)->queryScalar();
                        if ($jumlah_terima_pabrik > 0) {
                            $data['msg'] = "Data sudah ada<br>baris_id = ".$baris_id."<br>baris_kode = ".$baris_kode;
                        } else {
                            $data['msg'] = "Data ok<br>baris_id = ".$baris_id."<br>baris_kode = ".$baris_kode;
                        }
                    } else {
                        $data['msg'] = "Data tidak ditemukan<br>baris_id = ".$baris_id."<br>baris_kode = ".$baris_kode;
                    }
                } else {
                    $data['terima_logalam_detail_id'] = $terima_logalam_detail_id;
                    $data['msg'] = "Data log alam untuk dijual<br>baris_id = ".$baris_id."<br>baris_kode = ".$baris_kode;
                }*/

            } else {
                $data['msg'] = "Invalid QR Code Format";
            }
		}
        return $this->asJson($data);
	}

    public function actionShowDetailManual(){
		if(\Yii::$app->request->isAjax){
            $data           = [];
			$data['status'] = false;
			$data['msg']    = "";
            $keyword        = trim($_POST['keyword']);
            $jenis_input    = $_POST['jenis_input'];
            if (!empty($keyword) || $keyword != '') {
                $in     = HPersediaanLog::find()->where([$jenis_input => $keyword])->andWhere(['status' => 'IN'])->sum('fisik_pcs');
                $out    = HPersediaanLog::find()->where([$jenis_input => $keyword])->andWhere(['status' => 'OUT'])->sum('fisik_pcs');
                $stok   = $in - $out;
                if ($stok > 0) {
                    $data['msg'] = "Log siap dikeluarkan";
                    // $data['terima_logalam_detail_id'] = TTerimaLogalamDetail::findOne([$jenis_input => $keyword])->terima_logalam_detail_id;
                    $data['terima_logalam_detail_id'] = ViewTerimaLogalamPabrik::findOne([$jenis_input => $keyword])->terima_logalam_detail_id;
                } else {
                    $data['msg'] = "Data tidak ditemukan / Stok kosong";  
                }
            } else {
                $data['msg'] = "Invalid Input";
            }
		}
        return $this->asJson($data);
	}

    public function actionReview(){
        if(\Yii::$app->request->isAjax){
            $terima_logalam_detail_id = $_GET['terima_logalam_detail_id'];
            $modDetail = \app\models\ViewTerimaLogalamPabrik::findOne(['terima_logalam_detail_id'=>$terima_logalam_detail_id]);
            $modPabrik = \app\models\TTerimaLogalamPabrik::findOne(['kode'=>$modDetail->no_barcode]);
            $modTerimaLog = TTerimaLogalamDetail::findOne($terima_logalam_detail_id);
            $no_barcode = $modDetail->no_barcode;
            $kayu_id = $modDetail->kayu_id;
            $modKayu = \app\models\MKayu::findOne(['kayu_id' => $kayu_id]);
            return $this->renderAjax('_review',['modDetail'=>$modDetail, 'modPabrik'=>$modPabrik, 'modKayu'=>$modKayu, 'modTerimaLog'=>$modTerimaLog]);
        }
    }

    public function actionView(){
        // jika log alam untuk dijual tidak masuk ke h_persediaan
        // jika log alam untuk pabrik masuk ke h_persediaan
        if(\Yii::$app->request->isAjax){
            $terima_logalam_detail_id = $_GET['terima_logalam_detail_id'];
            $modDetail = \app\models\ViewTerimaLogalamPabrik::findOne(['terima_logalam_detail_id'=>$terima_logalam_detail_id]);
            $no_barcode = $modDetail->no_barcode;
            $kayu_id = $modDetail->kayu_id;
            $modKayu = \app\models\MKayu::findOne($kayu_id);
            $modPabrik = \app\models\TTerimaLogalamPabrik::findOne(['kode'=>$modDetail->no_barcode]);

            $peruntukan = $_GET['peruntukan'];
            if ($peruntukan == "Industri") {
                $title = "<font style='color: #2ebd30;'>LOG SUDAH DITERIMA</font>";
            } else {
                $title = "<font style='color: #f00;'>LOG UNTUK DIJUAL</font>";
            }
            return $this->renderAjax('_view',['modDetail'=>$modDetail, 'modKayu'=>$modKayu, 'peruntukan'=>$peruntukan, 'title'=>$title, 'modPabrik'=>$modPabrik]);
        }
    }

    public function actionAddItem(){
        if(\Yii::$app->request->isAjax){
			$terima_logalam_detail_id = Yii::$app->request->post('terima_logalam_detail_id');
            $kayu_id = Yii::$app->request->post('kayu_id');;
            // cek oelangsoe
            $sql_no_barcode = "select no_barcode from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."";
            $no_barcode = Yii::$app->db->createCommand($sql_no_barcode)->queryScalar();
            $sql_cek = "select count(*) from t_log_keluar where no_barcode = '".$no_barcode."'";
            $cek = Yii::$app->db->createCommand($sql_cek)->queryScalar();
            if ($cek > 0) {
                $data['msg'] = "Log sudah pernah dikeluarkan";
            } else {
                $modDetail = \app\models\ViewTerimaLogalamPabrik::findOne(['terima_logalam_detail_id'=>$terima_logalam_detail_id]);
                $modTerimaLog = TTerimaLogalamDetail::findOne($terima_logalam_detail_id);
                $data['msg'] = "Log siap dikeluarkan";
                $data['html'] = "";
                $data['html'] .= $this->renderPartial('_item',['terima_logalam_detail_id'=>$terima_logalam_detail_id, 'kayu_id'=>$kayu_id, 'modDetail'=>$modDetail, 'edit'=>'0', 'modTerimaLog'=>$modTerimaLog]);
            }
            return $this->asJson($data);
        }
    }

    public function actionDaftarLogKeluar(){
        $user_group_id = Yii::$app->db->createCommand("select user_group_id from m_user where user_id = ".$_SESSION['__id']."")->queryScalar();
        if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-daftarLogKeluar'){
				$param['table']= \app\models\TLogKeluar::tableName();
				$param['pk']= $param['table'].'.'.\app\models\TLogKeluar::primaryKey()[0];
				$param['column'] = ['t_log_keluar.log_keluar_id',
									't_log_keluar.kode',
									't_log_keluar.tanggal',
									't_log_keluar.no_barcode',
									't_log_keluar.cara_keluar',
									't_log_keluar.reff_no',
									't_log_keluar.keterangan',
                                    'm_pegawai.pegawai_nama',
									];
                $param['join']= ['
                JOIN m_pegawai ON m_pegawai.pegawai_id = '.$param['table'].'.pic_log_keluar 
                '];                                    
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('_daftarLogKeluar',['user_group_id'=>$user_group_id]);
        }
    }

    public function actionConfirmHapusDetail($id){
        if(\Yii::$app->request->isAjax){
			return $this->renderAjax('_confirmHapusDetail',['id'=>$id]);
        }
    }

    public function actionHapusDetailYes() {
        $log_keluar_id = Yii::$app->request->post('id');
		if(\Yii::$app->request->isAjax){
            // hapus
            $no_barcode = Yii::$app->db->createCommand("select no_barcode from t_log_keluar where log_keluar_id = ".$log_keluar_id."")->queryScalar();
            $persediaan_log_id = Yii::$app->db->createCommand("select persediaan_log_id from h_persediaan_log where no_barcode = '".$no_barcode."' and status='OUT'")->queryScalar();
            $delete1 = Yii::$app->db->createCommand()->delete('t_log_keluar', ['log_keluar_id' => $log_keluar_id])->execute();
            $delete2 = Yii::$app->db->createCommand()->delete('h_persediaan_log', ['persediaan_log_id' => $persediaan_log_id])->execute();
            
            if ($delete1 && $delete2) {
                // reload ajax tampilkan data setelah dihapus ke tabel semula
                $sql = "select a.kode, a.tanggal, a.no_barcode, a.cara_keluar, a.reff_no, a.keterangan, b.pegawai_nama, a.log_keluar_id ".
                        "   from t_log_keluar a ".
                        "   left join m_pegawai b on b.pegawai_id = a.pic_log_keluar".
                        "   ";
                $modLogKeluars = Yii::$app->db->createCommand($sql)->queryAll();
                $data = [];
                $data['status'] = false;
                //$data['msg'] = "Data berhasil dihapus";
                $data['msg'] = "Data berhasil dihapus";
                $data['html'] = "";
                foreach ($modLogKeluars as $modLogKeluar) {
                    $data['html'] .= "<tr class='odd' role='row'>";
                    $data['html'] .= "<td class='text-center td-kecil' style='height: 22px;'>".$modLogKeluar['kode']."</td>";
                    $data['html'] .= "<td class='text-center td-kecil'>".$modLogKeluar['tanggal']."</td>";
                    $data['html'] .= "<td class='text-center td-kecil'>".$modLogKeluar['no_barcode']."</td>";
                    $data['html'] .= "<td class='text-center td-kecil'>".$modLogKeluar['cara_keluar']."</td>";
                    $data['html'] .= "<td class='text-center td-kecil'>".$modLogKeluar['reff_no']."</td>";
                    $data['html'] .= "<td class='td-kecil'>".$modLogKeluar['keterangan']."</td>";
                    $data['html'] .= "<td class='td-kecil'>".$modLogKeluar['pegawai_nama']."</td>";
                    $data['html'] .= "<td class='text-center td-kecil'><a class='btn btn-xs btn-outline btn-danger tooltips' style='margin-right: 0px;' data-original-title='Hapus Detail' onclick='confirmHapusDetail(".$modLogKeluar['log_keluar_id'].")'><i class='fa fa-trash-o'></i></a></td>";
                    $data['html'] .= "</tr>";
                }
            } else {
                $data['msg'] = "Data gagal dihapus";
            }
		}
        return $this->asJson($data);
    }   
}
