<?php

namespace app\modules\ppic\controllers;

use Yii;
use app\models\MKayu;
use yii\helpers\Json;
use app\components\SSP;
use app\models\TTerimaLogalam;
use app\models\TTerimaLogalamDetail;
use app\models\TTerimaLogalamPabrik;
use app\controllers\DeltaBaseController;

class ScanterimalogalamController extends DeltaBaseController
{
    public $defaultAction = 'index';
    public function actionIndex()
    {
        $model = new \app\models\TTerimaLogalamPabrik();
        $modelH = new \app\models\HPersediaanLog();

        if (isset($_POST['terima_logalam_detail_id']) && isset($_POST['no_barcode']) && isset($_POST['kayu_id'])) {
            $data = "";
            $terima_logalam_detail_id = $_POST['terima_logalam_detail_id'];
            $sql_kode_terima_logalam = "select kode from t_terima_logalam a " .
                "   join t_terima_logalam_detail b on b.terima_logalam_detail_id = " . $terima_logalam_detail_id . " " .
                "   limit 1 " .
                "   ";
            $kode_terima_logalam = Yii::$app->db->createCommand($sql_kode_terima_logalam)->queryScalar();
            $no_barcode = $_POST['no_barcode'];
            $kayu_id = $_POST['kayu_id'];
            $session_id = $_SESSION['__id'];
            $pegawai_id_ = Yii::$app->db->createCommand("select pegawai_id from m_user where user_id = " . $session_id . "")->queryScalar();
            $tanggal = date("Y-m-d");
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                // cek sudah ada di db belum
                $sql_cek = "select count(*) from t_terima_logalam_pabrik where terima_logalam_detail_id = " . $terima_logalam_detail_id . "";
                $numrows_cek = Yii::$app->db->createCommand($sql_cek)->queryScalar();
                if ($numrows_cek > 0) {
                    $success_1 = false;
                    $error_msg = "Data sudah ada";
                } else {
                    // t_terima_logalam_pabrik
                    $sql_insert = "insert into t_terima_logalam_pabrik " .
                        "   (terima_logalam_detail_id, tanggal, kode, kayu_id, pic_terima, created_at, created_by, updated_at, updated_by) " .
                        "   values " .
                        "   (" . $terima_logalam_detail_id . ",'" . $tanggal . "','" . $no_barcode . "'," . $kayu_id . "," . $pegawai_id_ . ",
                                    '" . date('Y-m-d H:i:s') . "'," . $session_id . ",'" . date('Y-m-d H:i:s') . "'," . $session_id . ") " .
                        "   ";
                    $success_1 = Yii::$app->db->createCommand($sql_insert)->execute();

                    // h_persediaan_log
                    $tgl_transaksi = date("Y-m-d");
                    $kayu_id = $kayu_id;
                    $no_grade = Yii::$app->db->createCommand("select no_grade from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $no_barcode = $no_barcode;
                    $no_btg = Yii::$app->db->createCommand("select no_btg from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $no_lap = Yii::$app->db->createCommand("select no_lap from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $status = "IN";
                    $reff_no = $kode_terima_logalam;
                    $lokasi = 'GUDANG LOG ALAM';
                    $keterangan = "SCAN RESULT PENERIMAAN LOG ALAM";
                    $fisik_volume = Yii::$app->db->createCommand("select volume from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $pot = Yii::$app->db->createCommand("select kode_potong from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $active = true;
                    $diameter_ujung1 = Yii::$app->db->createCommand("select diameter_ujung1 from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $diameter_ujung2 = Yii::$app->db->createCommand("select diameter_ujung2 from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $diameter_pangkal1 = Yii::$app->db->createCommand("select diameter_pangkal1 from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $diameter_pangkal2 = Yii::$app->db->createCommand("select diameter_pangkal2 from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $fisik_diameter = round(($diameter_ujung1 + $diameter_pangkal1 + $diameter_ujung2 + $diameter_pangkal2) / 4);
                    $fisik_panjang = Yii::$app->db->createCommand("select panjang from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $cacat_panjang = Yii::$app->db->createCommand("select cacat_panjang from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $cacat_gb = Yii::$app->db->createCommand("select cacat_gb from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $cacat_gr = Yii::$app->db->createCommand("select cacat_gr from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $no_produksi = Yii::$app->db->createCommand("select no_produksi from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar();
                    $fsc = Yii::$app->db->createCommand("select fsc from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "")->queryScalar(); // TAMBAH FSC
                    if($fsc == false){
                        $fsc = 0;
                    }

                    // cek sudah ada di db belum
                    $sql_cekH = "select count(*) from h_persediaan_log where no_barcode = '" . $no_barcode . "'";
                    $numrows_cekH = Yii::$app->db->createCommand($sql_cekH)->queryScalar();
                    if ($numrows_cekH > 0) {
                        $success_2 = false;
                        $error_msg = "Data sudah ada";
                    } else {
                        $sql_insertH = "insert into h_persediaan_log " .
                            " (tgl_transaksi, kayu_id, no_grade, no_barcode, no_btg, no_lap, 
                                                status, reff_no, lokasi, keterangan, fisik_volume, fisik_diameter, fisik_panjang, pot, active,
                                                diameter_ujung1, diameter_ujung2, diameter_pangkal1, diameter_pangkal2, 
                                                cacat_panjang, cacat_gb, cacat_gr,
                                                created_at, created_by, updated_at, updated_by, no_produksi, fsc)" .
                            " values " .
                            " ('" . $tgl_transaksi . "', '" . $kayu_id . "', '" . $no_grade . "', '" . $no_barcode . "', '" . $no_btg . "', '" . $no_lap . "', 
                                            '" . $status . "', '" . $reff_no . "', '" . $lokasi . "', '" . $keterangan . "', " . $fisik_volume . ", " . $fisik_diameter . ", " . $fisik_panjang . ", '" . $pot . "', '" . $active . "', 
                                            " . $diameter_ujung1 . ", " . $diameter_ujung2 . ", " . $diameter_pangkal1 . ", " . $diameter_pangkal2 . ", 
                                            " . $cacat_panjang . ", " . $cacat_gb . ", " . $cacat_gr . ",
                                            '" . date('Y-m-d H:i:s') . "','" . $session_id . "','" . date('Y-m-d H:i:s') . "','" . $session_id . "', '" . $no_produksi . "', '" . $fsc . "') " .
                            "   ";
                        $success_2 = Yii::$app->db->createCommand($sql_insertH)->execute();
                    }
                }

                if ($success_1 && $success_2) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data berhasil disimpan'));
                    $data['msg'] = "Data berhasil disimpan";
                } else {
                    $transaction->rollback();
                    if ($success_1) {
                        $error_msg = "Gagal insert t_terima_logalam_pabrik";
                    } else {
                        $error_msg = "Gagal insert h_persediaan_log";
                    }
                    if (empty($error_msg)) {
                        $error_msg = \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE;
                    } else {
                        $error_msg = $error_msg;
                    }
                    $data['msg'] = $error_msg;
                    Yii::$app->session->setFlash('error', !empty($errmsg) ? $errmsg : Yii::t('app', $error_msg));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }

            /* list scanned
                $jumlah_baris = count($_POST['terima_logalam_detail_id']);
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false;

                    for ($i = 0; $i < $jumlah_baris; $i++) {
                        // t_terima_logalam_pabrik
                        $terima_logalam_detail_id = $_POST['terima_logalam_detail_id'][$i];
                        $tanggal = date("Y-m-d");
                        $no_barcode = $_POST['no_barcode'][$i];
                        $kayu_id = $_POST['kayu_id'][$i];
                        $session_id = $_SESSION['__id'];
                        $model->terima_logalam_detail_id = $terima_logalam_detail_id;
                        $model->tanggal = $tanggal;
                        $model->kode = $no_barcode;
                        $model->kayu_id = $kayu_id;
                        $model->pic_terima = $session_id;
                        // cek sudah ada di db belum
                        $sql_cek = "select count(*) from t_terima_logalam_pabrik where terima_logalam_detail_id = ".$terima_logalam_detail_id."";
                        $numrows_cek = Yii::$app->db->createCommand($sql_cek)->queryScalar();
                        if ($numrows_cek > 0) {
                            $success_1 = false;
                            $error_msg = "Data sudah ada! x";
                        } else {
                            $sql_insert = "insert into t_terima_logalam_pabrik ".
                                            "   (terima_logalam_detail_id, tanggal, kode, kayu_id, pic_terima, created_at, created_by, updated_at, updated_by) ".
                                            "   values ".
                                            "   (".$terima_logalam_detail_id.",'".$tanggal."','".$no_barcode."',".$kayu_id.",".$session_id.",
                                            '".date('Y-m-d H:i:s')."',".$session_id.",'".date('Y-m-d H:i:s')."',".$session_id.") ".
                                            "   ";
                            $success_1 = Yii::$app->db->createCommand($sql_insert)->execute();
                        }

                        // h_persediaan_log
                        $tgl_transaksi = date("Y-m-d");
                        $kayu_id = $kayu_id;
                        $no_grade = Yii::$app->db->createCommand("select no_grade from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."")->queryScalar();
                        $no_barcode = $no_barcode;
                        $no_btg = Yii::$app->db->createCommand("select no_btg from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."")->queryScalar();
                        $no_lap = Yii::$app->db->createCommand("select no_lap from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."")->queryScalar();
                        $status = "IN";
                        $reff_no = $no_barcode;
                        $lokasi = 'GUDANG LOG ALAM';
                        $fisik_volume = Yii::$app->db->createCommand("select volume from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."")->queryScalar();
                        $active = true;
                        $diameter_ujung1 = Yii::$app->db->createCommand("select diameter_ujung1 from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."")->queryScalar();
                        $diameter_ujung2 = Yii::$app->db->createCommand("select diameter_ujung2 from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."")->queryScalar();
                        $diameter_pangkal1 = Yii::$app->db->createCommand("select diameter_pangkal1 from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."")->queryScalar();
                        $diameter_pangkal2 = Yii::$app->db->createCommand("select diameter_pangkal2 from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."")->queryScalar();
                        $cacat_panjang = Yii::$app->db->createCommand("select cacat_panjang from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."")->queryScalar();
                        $cacat_gb = Yii::$app->db->createCommand("select cacat_gb from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."")->queryScalar();
                        $cacat_gr = Yii::$app->db->createCommand("select cacat_gr from t_terima_logalam_detail where terima_logalam_detail_id = ".$terima_logalam_detail_id."")->queryScalar();
                        // cek sudah ada di db belum
                        $sql_cekH = "select count(*) from h_persediaan_log where no_barcode = '".$no_barcode."'";
                        $numrows_cekH = Yii::$app->db->createCommand($sql_cekH)->queryScalar();
                        if ($numrows_cekH > 0) {
                            $success_2 = false;
                            $error_msg = "Data sudah ada! y";
                        } else {
                            $sql_insertH = "insert into h_persediaan_log ".
                                            " (tgl_transaksi, kayu_id, no_grade, no_barcode, no_btg, no_lap, 
                                                    status, reff_no, lokasi, fisik_volume, active,
                                                    diameter_ujung1, diameter_ujung2, diameter_pangkal1, diameter_pangkal2, 
                                                    cacat_panjang, cacat_gb, cacat_gr,
                                                    created_at, created_by, updated_at, updated_by)".
                                            " values ".
                                            " ('".$tgl_transaksi."', '".$kayu_id."', '".$no_grade."', '".$no_barcode."', '".$no_btg."', '".$no_lap."', 
                                                '".$status."', '".$reff_no."', '".$lokasi."', ".$fisik_volume.", '".$active."', 
                                                ".$diameter_ujung1.", ".$diameter_ujung2.", ".$diameter_pangkal1.", ".$diameter_pangkal2.", 
                                                ".$cacat_panjang.", ".$cacat_gb.", ".$cacat_gr.",
                                                '".date('Y-m-d H:i:s')."','".$session_id."','".date('Y-m-d H:i:s')."','".$session_id."') ".
                                            "   ";
                            $success_2 = Yii::$app->db->createCommand($sql_insertH)->execute();
                        }
                    }

                    if ($success_1 && $success_2) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Data Loglist Berhasil disimpan'));
                        return $this->redirect(['index','success'=>2]);
                    } else {
                        $transaction->rollback();
                        if (empty($error_msg)) {
                            $error_msg = \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE;
                        } else {
                            $error_msg = $error_msg;
                        }
                        Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', $error_msg));
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', $ex);
                }
            */
            return $this->asJson($data);
        }
        return $this->render('index');
    }

    /*function actionGetItemsScanned(){
		if(\Yii::$app->request->isAjax){
            if(\Yii::$app->request->get('dt')=='table-master'){
                $param['table']= \app\models\TTerimaLogalamDetail::tableName();
                $param['pk']= "terima_logalam_detail_id";
                $param['column'] = ['kayu_nama','no_lap','no_grade','no_btg','kode_potong', 'panjang',
                                        'diameter_ujung1','diameter_ujung2','diameter_pangkal1','diameter_pangkal2','diameter_rata',
                                        'cacat_panjang','cacat_gb','cacat_gr','volume'];
                $param['join'] = "JOIN m_kayu on m_kayu.kayu_id = t_terima_logalam_detail.kayu_id ";
                $param['order'] = "t_terima_logalam_detail.created_at DESC";
                return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
            }
        }
    }*/

    public function actionShowDetail()
    {
        if (\Yii::$app->request->isAjax) {
            $data = [];
            $data['status'] = false;
            $data['msg'] = "";
            // return $this->asJson(['msg' => 'PARAMS: ' . substr($_POST['datas'], 0, 5), 'status' => false]);
            if (substr($_POST['datas'], 0, 5) == "ID : ") {
                $data = explode("\n", $_POST['datas']);
                $baris_id = $data[0];
                $baris_kode = $data[1];
                $terima_logalam_detail = explode(" : ", $baris_id);
                $terima_logalam_detail_id = $terima_logalam_detail[1];

                $sql_terima_logalam_id = "select terima_logalam_id from t_terima_logalam_detail where terima_logalam_detail_id = " . $terima_logalam_detail_id . "";
                $terima_logalam_id = Yii::$app->db->createCommand($sql_terima_logalam_id)->queryScalar();

                $sql_peruntukan = "select peruntukan from t_terima_logalam where terima_logalam_id = " . $terima_logalam_id . "";
                $peruntukan = Yii::$app->db->createCommand($sql_peruntukan)->queryScalar();
                $data['peruntukan'] = $peruntukan;

                if ($peruntukan == "Industri") {
                    $terima_logalam_detail_ = explode(" : ", $baris_kode);
                    $no_barcode = $terima_logalam_detail_[1];
                    $sql_countDetail = "select count(*) from t_terima_logalam_detail " .
                        "   where terima_logalam_detail_id = " . $terima_logalam_detail_id .
                        "   and no_barcode = '" . $no_barcode . "' ";
                    $countDetail = Yii::$app->db->createCommand($sql_countDetail)->queryScalar();
                    $data['sql_countDetail'] = $sql_countDetail;
                    $data['countDetail'] = $countDetail;
                    if ($countDetail > 0) {

                        $modDetail = \app\models\TTerimaLogalamDetail::findOne(['terima_logalam_detail_id' => $terima_logalam_detail_id, 'no_barcode' => $no_barcode]);
                        $kayu_id = $modDetail->kayu_id;
                        $terima_logalam_id = $modDetail->terima_logalam_id;
                        $no_barcode = $modDetail->no_barcode;
                        $modKayu = MKayu::find()->where(['kayu_id' => $kayu_id])->one();
                        $model = \app\models\TTerimaLogalam::findOne($terima_logalam_id);
                        $modPabrik = new \app\models\TTerimaLogalamPabrik();
                        $data['terima_logalam_detail_id'] = $terima_logalam_detail_id;
                        $data['no_barcode'] = $no_barcode;
                        $sql_cek = "select count(*) from t_terima_logalam_pabrik where kode = '" . $no_barcode . "' ";
                        $jumlah_terima_pabrik = Yii::$app->db->createCommand($sql_cek)->queryScalar();
                        if ($jumlah_terima_pabrik > 0) {
                            $data['msg'] = "Data sudah ada";
                        } else {
                            $data['msg'] = "Data ok";
                        }
                        /*$data['html'] = "";
                        $data['html'] .= "<tr>";
                        $data['html'] .= "<input type='hidden' name='terima_logalam_detail_id[]' value='".$terima_logalam_detail_id."'>";
                        $data['html'] .= "<input type='hidden' name='no_barcode[]' value='".$modDetail->no_barcode."'>";
                        $data['html'] .= "<input type='hidden' name='kayu_id[]' value='".$modDetail->kayu_id."'>";
                        $data['html'] .= "<td>".$modKayu->group_kayu." ".$modKayu->kayu_nama."</td>";
                        $data['html'] .= "<td>".$modDetail->no_lap."</td>";
                        $data['html'] .= "<td>".$modDetail->no_grade."</td>";
                        $data['html'] .= "<td>".$modDetail->no_btg."</td>";
                        $data['html'] .= "<td>".$modDetail->kode_potong."</td>";
                        $data['html'] .= "<td>".$modDetail->panjang."</td>";
                        $data['html'] .= "<td>".$modDetail->diameter_ujung1."</td>";
                        $data['html'] .= "<td>".$modDetail->diameter_ujung2."</td>";
                        $data['html'] .= "<td>".$modDetail->diameter_pangkal1."</td>";
                        $data['html'] .= "<td>".$modDetail->diameter_pangkal2."</td>";
                        $data['html'] .= "<td>".$modDetail->diameter_rata."</td>";
                        $data['html'] .= "<td>".$modDetail->cacat_panjang."</td>";
                        $data['html'] .= "<td>".$modDetail->cacat_gb."</td>";
                        $data['html'] .= "<td>".$modDetail->cacat_gr."</td>";
                        $data['html'] .= "<td>".$modDetail->volume."</td>";
                        $data['html'] .= "<td class='text-center'><span id='place-cancelbtn'><a class='btn btn-xs red hapus' onclick='hapus(this);' title='Hapus Detail'><i class='fa fa-remove'></i></a></span></td>";
                        $data['html'] .= "</tr>";*/
                    } else {
                        $data['msg'] = "Data tidak ditemukan";
                    }
                } else {
                    $data['terima_logalam_detail_id'] = $terima_logalam_detail_id;
                    $data['msg'] = "Data log alam untuk dijual";
                }
            } else {
                $data['msg'] = "Invalid QR Code Format -> " . $_POST['datas'];
            }
        }
        return $this->asJson($data);
    }

    public function actionDeleteNomorProduksi($id)
    {
        if (\Yii::$app->request->isAjax) {
            $model = \app\models\TTerimaMutasi::findOne($id);
            if (Yii::$app->request->post('deleteRecord')) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_terima_mutasi
                    if (!empty($model)) {
                        if ($model->delete()) {
                            $success_1 = true;
                        }
                    }

                    if ($success_1) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['callback'] = "$('#table-master').dataTable().fnClearTable();";
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
            return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm', ['id' => $id, 'actionname' => 'deleteNomorProduksi']);
        }
    }

    public function getTotalpaletpermintaan($pengajuan_repacking_id)
    {
        $jml = 0;
        $modPengajuanDetail = \app\models\TPengajuanRepackingDetail::find()->where("pengajuan_repacking_id = " . $pengajuan_repacking_id)->all();
        if (count($modPengajuanDetail) > 0) {
            foreach ($modPengajuanDetail as $iv => $ajuandet) {
                $jml += $ajuandet->qty_besar;
            }
        }
        return $jml;
    }

    public function actionDaftarScanned()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->get('dt') == 'modal-scanned') {
                $param['table'] = TTerimaLogalamPabrik::tableName();
                $param['pk'] = TTerimaLogalamPabrik::primaryKey()[0];
                $param['column'] = ['terima_logalam_pabrik_id', 'tanggal', 'kode', 'kayu_nama', 'pegawai_nama', 'no_grade', 'no_btg', 'no_lap', 'fisik_volume', 'fsc', 'lokasi','terima_logalam_detail_id']; // TAMBAH FSC
                $param['join'] = [
                    'JOIN m_kayu ON m_kayu.kayu_id = ' . $param['table'] . '.kayu_id
                    JOIN m_pegawai ON m_pegawai.pegawai_id = ' . $param['table'] . '.pic_terima
                    JOIN h_persediaan_log on h_persediaan_log.no_barcode = ' . $param['table'] . '.kode
                    AND h_persediaan_log.kayu_id = ' . $param['table'] . '.kayu_id'
                ];
                $param['where'] = ["h_persediaan_log.status = 'IN'"];
                $param['group'] = ['GROUP BY terima_logalam_pabrik_id, kayu_nama, m_pegawai.pegawai_nama, no_grade, no_btg, h_persediaan_log.no_lap, h_persediaan_log.fisik_volume,
                                    h_persediaan_log.fsc, h_persediaan_log.lokasi'];    
                return Json::encode(SSP::complex($param));
            }
            return $this->renderAjax('_daftarScanned');
        }
    }

    public function actionInputManual()
    {
        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('_inputManual');
        }
    }

    public function actionInputManuals()
    {
        if (Yii::$app->request->isAjax) {
            $req = $_POST;
            if($req['clause'] === 'no_lap' || $req['clause'] === 'no_barcode') {
                $modDetail = TTerimaLogalamDetail::findOne([trim($req['clause']) => trim($req['keyword'])]);
                if ($modDetail) {
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : $modDetail->terima_logalam_detail_id\nNo : $modDetail->no_barcode"
                    ]);
                }
            }else {
                $modDetails = TTerimaLogalamDetail::find()
                    ->leftJoin('t_terima_logalam_pabrik', 't_terima_logalam_detail.terima_logalam_detail_id = t_terima_logalam_pabrik.terima_logalam_detail_id')
                    ->innerJoin('t_terima_logalam', 't_terima_logalam.terima_logalam_id = t_terima_logalam_detail.terima_logalam_id')
                    ->where(['t_terima_logalam_detail.' . trim($req['clause']) => trim($req['keyword'])])
                    ->andWhere(['t_terima_logalam.peruntukan' => 'Industri'])
                    ->andWhere(['t_terima_logalam_pabrik.terima_logalam_pabrik_id' => null])
                    ->all();
                if(count($modDetails) === 1) {
                    return $this->asJson([
                        'status' => true,
                        'datas' => "ID : {$modDetails[0]->terima_logalam_detail_id}\nNo : {$modDetails[0]->no_barcode}"
                    ]);
                }else {
                    return $this->asJson([
                        'status' => true,
                        'datas' => $modDetails
                    ]);
                }
            }
        }
        return $this->asJson(['status' => false, 'message' => 'Data tidak ditemukan']);
    }

    public function actionLihatDetail()
    {
        if (\Yii::$app->request->isAjax) {
            $terima_logalam_pabrik_id = $_GET['id'];
            $terima_logalam_pabrik = \app\models\TTerimaLogalamPabrik::findOne($terima_logalam_pabrik_id);
            $terima_logalam_detail_id = $terima_logalam_pabrik->terima_logalam_detail_id;
            $terima_logalam_detail = \app\models\TTerimaLogalamDetail::findOne($terima_logalam_detail_id);
            $terima_logalam_id = $terima_logalam_detail->terima_logalam_id;
            $terima_logalam = \app\models\TTerimaLogalam::findOne($terima_logalam_id);
            if (\Yii::$app->request->get('dt') == 'modal-madul') {
            }
            return $this->renderAjax('_lihatDetail', [
                'terima_logalam_pabrik_id' => $terima_logalam_pabrik_id,
                'terima_logalam_pabrik' => $terima_logalam_pabrik,
                'terima_logalam_detail' => $terima_logalam_detail,
                'terima_logalam' => $terima_logalam
            ]);
        }
    }

    public function actionConfirmHapusDetail($id)
    {
        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('_confirmHapusDetail', ['id' => $id]);
        }
    }

    public function actionReview()
    {
        if (\Yii::$app->request->isAjax) {
            $terima_logalam_detail_id = $_GET['terima_logalam_detail_id'];
            $modDetail = \app\models\TTerimaLogalamDetail::findOne($terima_logalam_detail_id);
            $no_dokumen = TTerimaLogalam::findOne(['terima_logalam_id' => $modDetail->terima_logalam_id])->no_dokumen;
            $kayu_id = $modDetail->kayu_id;
            $modKayu = MKayu::find()->where(['kayu_id' => $kayu_id])->one();
            return $this->renderAjax('_review', ['modDetail' => $modDetail, 'modKayu' => $modKayu, 'no_dokumen' => $no_dokumen]);
        }
    }

    public function actionView()
    {
        // jika log alam untuk dijual tidak masuk ke h_persediaan
        // jika log alam untuk pabrik masuk ke h_persediaan
        if (\Yii::$app->request->isAjax) {
            $terima_logalam_detail_id = $_GET['terima_logalam_detail_id'];
            $modDetail = \app\models\TTerimaLogalamDetail::findOne($terima_logalam_detail_id);
            $no_barcode = $modDetail->no_barcode;
            $kayu_id = $modDetail->kayu_id;
            $modKayu = \app\models\MKayu::findOne(['kayu_id' => $kayu_id]);
            $modPabrik = \app\models\TTerimaLogalamPabrik::findOne(['kode' => $modDetail->no_barcode]);

            $peruntukan = $_GET['peruntukan'];
            if ($peruntukan == "Industri") {
                $title = "<font style='color: #2ebd30;'>LOG SUDAH DITERIMA</font>";
            } else {
                $title = "<font style='color: #f00;'>LOG UNTUK DIJUAL</font>";
            }
            return $this->renderAjax('_view', ['modDetail' => $modDetail, 'modKayu' => $modKayu, 'peruntukan' => $peruntukan, 'title' => $title, 'modPabrik' => $modPabrik]);
        }
    }

    public function actionHapusDetailYes()
    {
        $id = Yii::$app->request->post('id');
        if (\Yii::$app->request->isAjax) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                // hapus
                $kode = Yii::$app->db->createCommand("select kode from t_terima_logalam_pabrik where terima_logalam_pabrik_id = " . $id . "")->queryScalar();
                $delete1 = Yii::$app->db->createCommand()->delete('t_terima_logalam_pabrik', ['terima_logalam_pabrik_id' => $id])->execute();
                $delete2 = Yii::$app->db->createCommand()->delete('h_persediaan_log', ['no_barcode' => $kode])->execute();

                if ($delete1 && $delete2) {

                    // reload ajax tampilkan data setelah dihapus ke tabel semula




                    $sql = "select terima_logalam_pabrik_id, tanggal, kode, kayu_nama, m_pegawai.pegawai_nama, no_grade, no_btg, no_lap, fisik_volume, lokasi " .
                        "   from t_terima_logalam_pabrik " .
                        "   join m_kayu on m_kayu.kayu_id = t_terima_logalam_pabrik.kayu_id " .
                        "   join m_pegawai ON m_pegawai.pegawai_id = t_terima_logalam_pabrik.pic_terima " .
                        "   join h_persediaan_log on h_persediaan_log.no_barcode = t_terima_logalam_pabrik.kode " .
                        "   ";
                    $modTerimaLogalamPabriks = Yii::$app->db->createCommand($sql)->queryAll();
                    $data = [];
                    $data['sql'] = $sql;
                    $data['status'] = false;
                    $data['msg'] = "Data berhasil dihapus";
                    $data['html'] = "";
                    foreach ($modTerimaLogalamPabriks as $modTerimaLogalamPabrik) {
                        $terima_logalam_pabrik_id = $modTerimaLogalamPabrik['terima_logalam_pabrik_id'];
                        $data['html'] .= "<tr class='odd' role='row'>";
                        $data['html'] .= "<td class='text-align-center td-kecil' style='height: 22px;'>" . $modTerimaLogalamPabrik['tanggal'] . "</td>";
                        $data['html'] .= "<td class='text-align-center td-kecil'>" . $modTerimaLogalamPabrik['kode'] . "</td>";
                        $data['html'] .= "<td class='text-align-center td-kecil'>" . $modTerimaLogalamPabrik['kayu_nama'] . "</td>";
                        $data['html'] .= "<td class='text-align-center td-kecil'>" . $modTerimaLogalamPabrik['pegawai_nama'] . "</td>";
                        $data['html'] .= "<td class='text-align-center td-kecil'>" . $modTerimaLogalamPabrik['no_grade'] . "</td>";
                        $data['html'] .= "<td class='text-align-center td-kecil'>" . $modTerimaLogalamPabrik['no_btg'] . "</td>";
                        $data['html'] .= "<td class='text-align-center td-kecil'>" . $modTerimaLogalamPabrik['no_lap'] . "</td>";
                        $data['html'] .= "<td class='text-align-center td-kecil'>" . $modTerimaLogalamPabrik['fisik_volume'] . "</td>";
                        $data['html'] .= "<td class='text-align-center td-kecil'>" . $modTerimaLogalamPabrik['lokasi'] . "</td>";
                        $data['html'] .= "<td class='text-align-center td-kecil'>";
                        $data['html'] .= "<a class='btn btn-xs btn-outline dark' onclick='lihatDetail(" . $terima_logalam_pabrik_id . ")'><i class='fa fa-eye'></i></a>";
                        $data['html'] .= "<a class='btn btn-xs btn-outline btn-danger tooltips' style='margin-right: 0px;' data-original-title='Hapus Detail' onclick='confirmHapusDetail(" . $terima_logalam_pabrik_id . ")'><i class='fa fa-trash-o'></i></a>";
                        $data['html'] .= "</td>";
                        $data['html'] .= "</tr>";
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data berhasil disimpan'));
                    $data['msg'] = "Data berhasil disimpan";
                } else {
                    $transaction->rollback();
                    if (empty($delete1)) {
                        $error_msg = "Gagal hapus TTerimaLogalamPabrik";
                    }
                    if (empty($delete2)) {
                        $error_msg = "Gagal hapus HPersediaanLog";
                    }
                    if (empty($delete1) && empty($delete2)) {
                        $error_msg = "Gagal hapus TTerimaLogalamPabrik && HPersediaanLog !";
                    }
                    if (empty($error_msg)) {
                        $error_msg = \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE;
                    } else {
                        $error_msg = $error_msg;
                    }
                    $data['msg'] = $error_msg;
                    Yii::$app->session->setFlash('error', !empty($errmsg) ? $errmsg : Yii::t('app', $error_msg));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        return $this->asJson($data);
    }
}
