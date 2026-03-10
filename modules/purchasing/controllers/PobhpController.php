<?php

namespace app\modules\purchasing\controllers;

use app\components\Params;
use Yii;
use app\controllers\DeltaBaseController;
use Codeception\Util\JsonArray;

class PobhpController extends DeltaBaseController
{

    public $defaultAction = 'index';

    public function actionIndex()
    {
        $model = new \app\models\TSpo(['scenario' => \app\models\TSpo::SCENARIO_SPO_BARU]);
        $model->spo_kode = 'Auto Generate';
        $model->spo_tanggal = date('d/m/Y');
        $model->spo_is_pkp = true;
        $model->spo_is_ppn = false;
        $model->spo_ppn_nominal = 0;
        $model->spo_pph_nominal = 0;
        $model->spo_total = 0;
        $modDetail = [];
        $terimabhp_kode = '-';

        if (isset($_GET['spo_id'])) {
            $model = \app\models\TSpo::findOne($_GET['spo_id']);
            $model->spo_tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($model->spo_tanggal);
            $model->tanggal_kirim = \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);
            $modDetail = \app\models\TSpoDetail::find()->where(['spo_id' => $model->spo_id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->all();
            $spo_id = $model->spo_id;
            $sql_terimabhp_kode = "select terimabhp_kode from t_terima_bhp where spo_id = '" . $spo_id . "' and cancel_transaksi_id is null ";
            $terimabhp_kode = Yii::$app->db->createCommand($sql_terimabhp_kode)->queryScalar();
        }

        if (Yii::$app->request->post('TSpo')) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $success_1 = true; // t_spo
                $success_2 = true; // t_spo_detail
                $success_3 = true; // insert t_approval
                $success_4 = true; // insert map_spp_detail_reff && update tmp_spp_spo_spl_tbp
                $success_5 = false; // update map_penawaran_bhp
                $assigned = [];
                $spo_total_all = 0;
                $spo_ppn_nominal_all = 0;
                $spo_pph_nominal_all = 0;

                // start search approver
                if ((isset($_POST['TSpoDetail'])) && (count($_POST['TSpoDetail']) > 0)) {
                    foreach ($_POST['TSpoDetail'] as $detail) {
                        $peg_id = $this->getAssigned($detail['spod_harga'])['pegawai_id'];

                        if (!empty($assigned[$peg_id])) {
                            if ($_POST['TSpo']['spo_is_pkp'] == 1) {
                                $assigned[$peg_id]['spo_ppn_nominal'] += $detail['subtotal'] * Params::DEFAULT_PPN;
                                $assigned[$peg_id]['spo_total'] += $detail['subtotal'] + ($detail['subtotal'] * Params::DEFAULT_PPN);
                            } else {
                                $assigned[$peg_id]['spo_ppn_nominal'] += 0;
                                $assigned[$peg_id]['spo_total'] += $detail['subtotal'];
                            }
                        } else {
                            if ($_POST['TSpo']['spo_is_pkp'] == 1) {
                                $assigned[$peg_id]['spo_ppn_nominal'] = $detail['subtotal'] * Params::DEFAULT_PPN;
                                $assigned[$peg_id]['spo_total'] = $detail['subtotal'] + ($detail['subtotal'] * Params::DEFAULT_PPN);
                            } else {
                                $assigned[$peg_id]['spo_ppn_nominal'] = 0;
                                $assigned[$peg_id]['spo_total'] = $detail['subtotal'];
                            }
                        }
                    }
                }
                // end search approver

                if (count($assigned) > 0) {
                    foreach ($assigned as $peg => $assign) {
                        $model = new \app\models\TSpo();
                        $model->load(\Yii::$app->request->post());
                        $model->spo_kode = \app\components\DeltaGenerator::kodeSpo();
                        $model->approve_status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
                        $model->spo_disetujui = $peg;
                        $model->spo_ppn_nominal = $assign['spo_ppn_nominal'];
                        $model->spo_total = $assign['spo_total'];
//                        $model->spo_ppn_nominal = $_POST['TSpo']['spo_ppn_nominal'];
//                        $model->spo_total = $_POST['TSpo']['spo_total'];
//                        echo "<pre>";
//                        print_r($model->attributes);die;

                        if ($model->validate()) {
                            if ($model->save()) {
                                $success_1 &= true;
                                if ((isset($_POST['TSpoDetail'])) && (count($_POST['TSpoDetail']) > 0)) {
                                    foreach ($_POST['TSpoDetail'] as $i => $detail) {
                                        if ($peg == $this->getAssigned($detail['spod_harga'])['pegawai_id']) {
                                            $modDetail = new \app\models\TSpoDetail();
                                            $modDetail->attributes = $detail;                                            
                                            $modDetail->spo_id = $model->spo_id;
                                            $modDetail->spod_garansi = $detail['spod_garansi'] == '1' ? true : false;                                            
                                            if ($modDetail->validate()) {
                                                if ($modDetail->save()) {
                                                    $success_2 &= true;
                                                } else {
                                                    $success_2 &= false;
                                                }
                                            } else {
                                                $success_2 = false;
                                            }
                                            // echo"<pre>";
                                            // print_r($modDetail->attributes);
                                            // echo"</pre>";
                                            // START INSERT MAPPING TABLE
                                            $sql = "SELECT bhp_id, sum(sppd_qty) FROM t_spp_detail 
													WHERE t_spp_detail.suplier_id = " . $model->suplier_id . " 
														AND bhp_id = " . $detail['bhp_id'] . " 
													GROUP BY bhp_id
													ORDER BY bhp_id
													";
                                            $mods = \Yii::$app->db->createCommand($sql)->queryOne();
                                            $sql2 = "SELECT bhp_id, sum(spod_qty), string_agg(spod_keterangan, ', ') AS keterangan FROM t_spo_detail 
													JOIN t_spo ON t_spo.spo_id = t_spo_detail.spo_id 
													WHERE t_spo.suplier_id = " . $model->suplier_id . " 
														AND t_spo.cancel_transaksi_id IS NULL 
														AND bhp_id = " . $detail['bhp_id'] . " 
													GROUP BY bhp_id
													ORDER BY bhp_id
													";
                                            $mods2 = \Yii::$app->db->createCommand($sql2)->queryOne();
                                            $sql3 = "SELECT * FROM t_spp_detail 
                                                        WHERE suplier_id = " . $model->suplier_id . " AND bhp_id = " . $detail['bhp_id'] . "  
                                                            AND status_closed IS NULL
                                                                            AND NOT EXISTS (
                                                                                    SELECT 1 
                                                                                    FROM map_spp_detail_reff 
                                                                                    WHERE map_spp_detail_reff.sppd_id = t_spp_detail.sppd_id
                                                                                        and reff_no ilike'%spo%'
                                                                            )";
                                            $mods3 = \Yii::$app->db->createCommand($sql3)->queryAll();
                                            $qty_ordering = 0;
                                            foreach ($mods3 as $j => $abc) {
                                                $qty_ordering = $qty_ordering + $abc['sppd_qty'];
                                                $qty_current = $mods2['sum'] - $detail['spod_qty'];
                                                // kasus : MapSppDetailReff kosong
                                                // 2020-06-29 diaktifkan lagi karena MapSppDetailReff double insert
                                                // if ($qty_ordering > $qty_current) {
                                                    $params['sppd_id'] = $abc['sppd_id'];
                                                    $params['reff_no'] = $model->spo_kode;
                                                    $params['reff_detail_id'] = $modDetail->spod_id;
                                                    $success_4 &= \app\models\MapSppDetailReff::simpanMapping($params);
                                                    $mapTmpReffno = \app\models\TmpSppSpoSplTbp::findOne(['sppd_id'=>$abc['sppd_id']]);
                                                    $sq1 = "SELECT 
                                                                json_agg(json_build_object('reffno', reff_no, 'reffdetailid', reff_detail_id)) as reffno
                                                            FROM map_spp_detail_reff
                                                            WHERE sppd_id = $abc[sppd_id]";
                                                    $modsReffno = \Yii::$app->db->createCommand($sq1)->queryOne(); 
                                                    $mapTmpReffno->reff_no = $modsReffno['reffno'];
                                                    // echo"<pre>";print_r($mapTmpReffno->reff_no);echo"</pre>";                                                    
                                                    // echo"<pre>";
                                                    // echo"sppd_id : ";
                                                    // print_r($params['sppd_id'] );
                                                    // echo"<br>";
                                                    // echo"reff_no : ";
                                                    // print_r($params['reff_no'] );
                                                    // echo"<br>";
                                                    // echo"reff_detail_id : ";
                                                    // print_r($params['reff_detail_id'] );
                                                    // echo"<br>";
                                                    // echo"</pre>";
                                                    $mapTmpReffno->save();

                                                    $sql = "SELECT * FROM map_penawaran_bhp WHERE sppd_id = $abc[sppd_id]";
                                                    $modPenawaran = \Yii::$app->db->createCommand($sql)->queryAll();
                                                    foreach ($modPenawaran as $penawaran) {
                                                        $mapPenawaran = \app\models\MapPenawaranBhp::findOne(['sppd_id'=>$penawaran['sppd_id']]);
                                                        if ($mapPenawaran->sppd_id == $detail['sppd_id']) {
                                                            //    echo "<pre>";
                                                            //    print_r($detail['sppd_id']);
                                                            //    echo "</pre>";
                                                            //    exit;

                                                            $mapPenawaran->spod_id = $modDetail->spod_id;
                                                            //    echo "<pre>";
                                                            //    print_r($mapPenawaran->attributes);
                                                            //    print_r($mapPenawaran->validate());

                                                            if ($mapPenawaran->validate()) {
                                                                if ($mapPenawaran->save()) {
                                                                    $success_5 &= true;
                                                                } else {
                                                                    $success_5 &= false;
                                                                }
                                                            } else {
                                                                $success_5 = false;
                                                            }
                                                        } else {
                                                            $success_5 = false;
                                                        }
                                                    }

                                                // }
                                            }
                                            // END INSERT MAPPING TABLE

                                            // if (!empty(Yii::$app->request->post("TSpo")['penawaran'])) {
                                            //     $post_penawaran = \yii\helpers\Json::decode(Yii::$app->request->post("TSpo")['penawaran']);
                                            //     if (count($post_penawaran) > 0) {
                                            //         foreach ($post_penawaran as $itwrr => $map_penawaran_bhp_id) {
                                            //             $mapPenawaran = \app\models\MapPenawaranBhp::findOne($map_penawaran_bhp_id);
                                            //             if ($mapPenawaran->sppd_id == $detail['sppd_id']) {
                                            //                 $mapPenawaran->spod_id = $modDetail->spod_id;
                                            //                 if ($mapPenawaran->validate()) {
                                            //                     if ($mapPenawaran->save()) {
                                            //                         $success_5 &= true;
                                            //                     } else {
                                            //                         $success_5 &= false;
                                            //                     }
                                            //                 } else {
                                            //                     $success_5 = false;
                                            //                 }
                                            //             } else {
                                            //                 $success_5 = false;
                                            //             }
                                            //         }
                                            //     }
                                            // }
                                        }
                                    }
                                } else {
                                    $success_2 = false;
                                    Yii::$app->session->setFlash('error', !empty($errmsg) ? $errmsg : Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                                }
                                // START Create Approval
                                // exec ini jika proses edit
                                $modelApproval = \app\models\TApproval::findOne(['reff_no' => $model->spo_kode]);
                                if (count($modelApproval) > 0) {
                                    \app\models\TApproval::deleteAll(['reff_no' => $model->spo_kode]);
                                }
                                // exec ini jika proses edit
                                $modelApproval = new \app\models\TApproval();
                                $modelApproval->assigned_to = $model->spo_disetujui;
                                $modelApproval->reff_no = $model->spo_kode;
                                $modelApproval->tanggal_berkas = $model->spo_tanggal;
                                $modelApproval->level = $this->getDisetujui($model)['level'];
                                $modelApproval->status = \app\models\TApproval::STATUS_NOT_CONFIRMATED;
                                $success_3 &= $modelApproval->createApproval();
                                // END Create Approval
                            }
                        }
                    }
                }
                // echo"<pre>";
                // print_r($modDetail->attributes);
                // echo"<hr>";
                // print_r($_POST['TSpoDetail']);
                // echo"</pre>";

                // echo"<pre>";
                // print_r($success_1." success 1");
                // echo"<br>";
                // print_r($success_2." success 2");
                // echo"<br>";
                // print_r($success_3." success 3");
                // echo"<br>";
                // print_r($success_4." success 4");
                // echo"<br>";
                // echo"</pre>";
                // exit;
                if ($success_1 && $success_2 && $success_3 && $success_4) { //&& $success_5
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data SPO Berhasil disimpan'));
                    return $this->redirect(['index']);
                    //$transaction->rollback();
                    //Yii::$app->session->setFlash('success', Yii::t('app', $model->spo_total));
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg) ? $errmsg : Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
        }
        return $this->render('index', ['model' => $model, 'modDetail' => $modDetail, 'terimabhp_kode' => $terimabhp_kode]);
    }

    public function getDisetujui($model)
    {
        $modNominal = \app\models\MApprovalNominallevel::find()->where(['active' => TRUE])->orderBy(['approval_nominallevel_id' => SORT_ASC])->all();
        $total = $model->spo_total;
        $pegawai_id = '';
        foreach ($modNominal as $i => $value) {
            if ($value->nominal > $total) {
                $pegawai_id = $value->attributes;
                break;
            }
        }
        if (empty($pegawai_id)) {
            $modPegawai = \app\models\MApprovalNominallevel::find()->where(['active' => TRUE])->orderBy(['level' => SORT_DESC, 'nominal' => SORT_DESC])->one();
            $pegawai_id = $modPegawai->attributes;
        }
        return $pegawai_id;
    }

    public function getAssigned($nominal)
    {
        $modNominal = \app\models\MApprovalNominallevel::find()->where(['active' => TRUE])->orderBy(['approval_nominallevel_id' => SORT_ASC])->all();
        $pegawai_id = '';
        foreach ($modNominal as $i => $value) {
            if ($value->nominal > $nominal) {
                $pegawai_id = $value->attributes;
                break;
            }
        }
        if (empty($pegawai_id)) {
            $modPegawai = \app\models\MApprovalNominallevel::find()->where(['active' => TRUE])->orderBy(['level' => SORT_DESC, 'nominal' => SORT_DESC])->one();
            $pegawai_id = $modPegawai->attributes;
        }
        return $pegawai_id;
    }


    public function actionPickPanel()
    {
        if (\Yii::$app->request->isAjax) {
            $modGroup = \app\models\TSppDetail::find()
                ->select("t_spp_detail.bhp_id,bhp_nm, SUM(sppd_qty) as sppd_qty")
                ->join('JOIN', 't_spp', 't_spp.spp_id = t_spp_detail.spp_id')
                ->join('JOIN', 'm_brg_bhp', 'm_brg_bhp.bhp_id = t_spp_detail.bhp_id')
                ->groupBy("t_spp_detail.bhp_id,bhp_nm")
                ->all();
            return $this->renderAjax('rekapSpp', ['modGroup' => $modGroup]);
        }
    }

    public function actionAddItem()
    {
        if (\Yii::$app->request->isAjax) {
            $bhp_id = Yii::$app->request->post('bhp_id');
            $qty = Yii::$app->request->post('qty');
            $data['html'] = '';
            $modSpoDetail = new \app\models\TSpoDetail();
            $modBhp = \app\models\MBrgBhp::findOne($bhp_id);
            if (count($modBhp) > 0) {
                $data['detail'] = $modBhp->attributes;
                $modSpoDetail->bhp_id = $modBhp->bhp_id;
                $modSpoDetail->spod_qty = $qty;
                $modSpoDetail->spod_harga = $modBhp->bhp_harga;
                $modSpoDetail->subtotal = $modSpoDetail->spod_qty * $modSpoDetail->spod_harga;
                $modSpoDetail->spod_harga = \app\components\DeltaFormatter::formatNumberForUser($modSpoDetail->spod_harga);
                $modSpoDetail->spod_harga_bantu = $modSpoDetail->spod_harga;
                $modSpoDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUser($modSpoDetail->subtotal);
                $data['html'] .= $this->renderPartial('_item', ['modSpoDetail' => $modSpoDetail]);
            }
            return $this->asJson($data);
        }
    }

    public function actionGetItemsBySpo()
    {
        if (\Yii::$app->request->isAjax) {
            $spo_id = Yii::$app->request->post('spo_id');
            $data = [];
            $data['html'] = '';
            if (!empty($spo_id)) {
                $modSpo = \app\models\TSpo::findOne($spo_id);
                $modDetailSpo = \app\models\TSpoDetail::find()->where(['spo_id' => $spo_id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->all();
                if (count($modDetailSpo) > 0) {
                    foreach ($modDetailSpo as $i => $detail) {
                        $detail->qty_kebutuhan = " - ";
                        $detail->satuan = $detail->bhp->bhp_satuan;
                        $sql = "SELECT * FROM map_spp_detail_reff 
								JOIN t_spp_detail ON map_spp_detail_reff.sppd_id = t_spp_detail.sppd_id
								JOIN t_spp ON t_spp.spp_id = t_spp_detail.spp_id
								WHERE reff_no = '" . $modSpo->spo_kode . "' AND reff_detail_id = " . $detail->spod_id;
                        $mod = Yii::$app->db->createCommand($sql)->queryOne();
                        $data['html'] .= $this->renderPartial('_itemAfterSave', ['detail' => $detail, 'i' => $i, 'mod' => $mod]);
                    }
                }
                $data['supplier'] = $modSpo->suplier->attributes;
            }
            return $this->asJson($data);
        }
    }

    public function actionDaftarSpo()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->get('dt') == 'table-spo') {
                $param['table'] = \app\models\TSpo::tableName();
                $param['pk'] = $param['table'] . "." . \app\models\TSpo::primaryKey()[0];
                $param['column'] = [$param['table'] . '.spo_id',                                            //0
                    'spo_kode',                                                        //1
                    ['col_name' => 'spo_tanggal', 'formatter' => 'formatDateForUser2'], //2
                    'suplier_nm',                                                    //3
                    'spo_total',                                                    //4
                    'approve_status',                                                //5
                    $param['table'] . '.terima_bhp_id',                                //6
                    'terimabhp_kode',                                                //7
                    $param['table'] . '.cancel_transaksi_id',                            //8
                    'assigned.pegawai_nama',                                        //9
                    'assigned.pegawai_nama',                                        //10
                    'm_default_value.name_en as mata_uang',                            //11
                    't_terima_bhp.cancel_transaksi_id',                                //12
                    't_approval.updated_at',                                        //13
                    $param['table'] . '.cancel_transaksi_id'                            //14

                ];
                $param['join'] = [' JOIN m_pegawai ON m_pegawai.pegawai_id = ' . $param['table'] . '.spo_disetujui' .
                    ' JOIN m_suplier ON m_suplier.suplier_id = ' . $param['table'] . '.suplier_id' .
                    ' LEFT JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = ' . $param['table'] . '.terima_bhp_id' .
                    ' LEFT JOIN t_approval ON t_approval.reff_no = ' . $param['table'] . '.spo_kode' .
                    ' LEFT JOIN m_pegawai AS assigned ON t_approval.assigned_to = assigned.pegawai_id ' .
                    ' LEFT JOIN m_pegawai AS confirmed ON t_approval.approved_by = confirmed.pegawai_id ' .
                    ' JOIN m_default_value ON m_default_value.value = ' . $param['table'] . '.mata_uang'];
                return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
            }
            return $this->renderAjax('daftarSpo');
        }
    }

    public function actionSetDetail()
    {
        if (\Yii::$app->request->isAjax) {
            $suplier_id = Yii::$app->request->post('suplier_id');
            $data = [];
            $data['html'] = '';
            $data['sppdetail'] = null;
            $data['spp'] = null;
            $data['penawaran'] = null;
            $data['penawaran_bhp_id'] = null;
            $data['berkas'] = true;
            $modSpoDetail = new \app\models\TSpoDetail();
            if (!empty($suplier_id)) {
                $modSupplier = \app\models\MSuplier::findOne($suplier_id);
                $sql = "SELECT t_spp_detail.spp_id,spp_kode,sppd_id,bhp_id,sppd_qty,sppd_ket 
                        FROM t_spp_detail 
						JOIN t_spp ON t_spp.spp_id = t_spp_detail.spp_id 
						WHERE t_spp_detail.suplier_id = " . $suplier_id . "
                                AND status_closed IS NULL
                                AND NOT EXISTS (
                                        SELECT 1 
                                        FROM map_spp_detail_reff 
                                        WHERE map_spp_detail_reff.sppd_id = t_spp_detail.sppd_id
                                            and reff_no ilike'%spo%'
                                )
						ORDER BY sppd_id asc";
                $mods = \Yii::$app->db->createCommand($sql)->queryAll();
                if (count($mods) > 0) {
                    foreach ($mods as $i => $detail) {

                        // Ambil data penawaran jika sppd_id ada
                        if (!empty($modSppDetail->sppd_id)) {
                            $mapPenawaran = \app\models\MapPenawaranBhp::find()
                                ->where(['sppd_id' => $detail->sppd_id])
                                ->all();

                            if (!empty($mapPenawaran)) {
                                foreach ($mapPenawaran as $penawaran) {
                                    $data['penawaran'][] = $penawaran->map_penawaran_bhp_id;
                                    $data['penawaran_bhp_id'][] = $penawaran->penawaran_bhp_id;
                                    // Ambil data penawaran jika sppd_id ada
                                    if (!empty($modSppDetail->sppd_id)) {
                                        $mapPenawaran = \app\models\MapPenawaranBhp::find()
                                            ->where(['sppd_id' => $detail->sppd_id])
                                            ->all();

                                        if (!empty($mapPenawaran)) {
                                            foreach ($mapPenawaran as $penawaran) {
                                                $data['penawaran'][] = $penawaran->map_penawaran_bhp_id;
                                                $data['penawaran_bhp_id'][] = $penawaran->penawaran_bhp_id;
                                            }
                                        }
                                    }
                                }
                            }                            
                        }
                        $data['detail'] = $detail;
                        $data['html'] .= $this->createItemDetail($modSpoDetail, $detail );
                    }                    
                }               

                $data['supplier'] = $modSupplier->attributes;
                if (!empty($data['penawaran'])) {
                    $data['penawaran'] = \yii\helpers\Json::encode($data['penawaran']);
                    $penawaranBhpid = \yii\helpers\Json::encode($data['penawaran_bhp_id']);
                    $penawaranBhpnya = str_replace("[","",$penawaranBhpid);
                    $penawaranBhp = str_replace("]","",$penawaranBhpnya);
                    $sql = "SELECT attachment from t_penawaran_bhp where penawaran_bhp_id in($penawaranBhp) and attachment =''";
                    $modPenawaran = \Yii::$app->db->createCommand($sql)->queryAll();
                    if (count($modPenawaran) > 0) {
                        $data['berkas'] = false;
                    }
                }  
            }
            return $this->asJson($data);
        }
    }

    public function actionSetDetailBySppId()
    {
        if (\Yii::$app->request->isAjax) {
            $spp_id = Yii::$app->request->post('spp_id');
            $data = [];
            $data['html'] = '';
            $modSpoDetail = new \app\models\TSpoDetail();
            $modSppDetail = \app\models\TSppDetail::find()->where(['spp_id' => $spp_id])->all();
            if (count($modSppDetail) > 0) {
                foreach ($modSppDetail as $i => $detail) {
                    $data['detail'] = $detail;
                    $qty = $detail['sppd_qty'];
                    $detail->keterangan = $detail->sppd_ket;
                    $data['html'] .= $this->createItemDetail($modSpoDetail, $detail, $qty);
                }
            }            
            return $this->asJson($data);
        }
    }

    public function createItemDetail($modSpoDetail, $detail) //, $qty, $modSpp = null, $modSppDetail = null
    {
        $modBhp = \app\models\MBrgBhp::findOne($detail['bhp_id']);
        $modSpp = \app\models\TSpp::findOne($detail['spp_id']);
        $modSppDetail = \app\models\TSppDetail::findOne($detail['sppd_id']);
        $modSpoDetail->attributes = $detail;
        $modSpoDetail->bhp_id = $detail['bhp_id'];
        $modSpoDetail->sppd_id = $detail['sppd_id'];
        $modSpoDetail->spod_qty = $detail['sppd_qty'];
        $modSpoDetail->sppd_qty = $detail['sppd_qty'];
        $modSpoDetail->spod_keterangan = $detail['sppd_ket'];
        $modSpoDetail->spod_harga = (!empty($modBhp->bhp_harga)) ? $modBhp->bhp_harga : 0;
        $modSpoDetail->subtotal = $modSpoDetail->spod_qty * $modSpoDetail->spod_harga;
        $modSpoDetail->spod_harga = \app\components\DeltaFormatter::formatNumberForUser($modSpoDetail->spod_harga);
        $modSpoDetail->spod_harga_bantu = $modSpoDetail->spod_harga;
        $modSpoDetail->subtotal = \app\components\DeltaFormatter::formatNumberForUser($modSpoDetail->subtotal);
        $modSpoDetail->satuan = $modBhp->bhp_satuan;
        return $this->renderPartial('_item', ['modSpoDetail' => $modSpoDetail, 'modBhp' => $modBhp, 'modSpp' => $modSpp, 'modSppDetail' => $modSppDetail]);
    }

    public function actionPrintSpo()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = \app\models\TSpo::findOne($_GET['id']);
        $modDetail = \app\models\TSpoDetail::find()->where(['spo_id' => $model->spo_id])->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->all();
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'SURAT PESANAN');
        if ($caraprint == 'PRINT') {
            return $this->renderPartial('printSpo', ['model' => $model, 'modDetail' => $modDetail, 'paramprint' => $paramprint]);
        }
    }

    public function actionCancelSpo($id)
    {
        if (\Yii::$app->request->isAjax) {
            $modSpo = \app\models\TSpo::findOne($id);
            $modCancel = new \app\models\TCancelTransaksi();
            if (Yii::$app->request->post('TCancelTransaksi')) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false; // t_cancel_transaksi
                    $success_2 = false; // t_spo
                    $success_3 = false; // map_spp_detail_reff
                    $success_4 = false; // t_approval

                    $modCancel->load(\Yii::$app->request->post());
                    $modCancel->cancel_by = Yii::$app->user->identity->pegawai_id;
                    $modCancel->cancel_at = date('d/m/Y H:i:s');
                    $modCancel->reff_no = $modSpo->spo_kode;
                    $modCancel->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                    if ($modCancel->validate()) {
                        if ($modCancel->save()) {
                            $success_1 = true;
                            $modSpo->cancel_transaksi_id = $modCancel->cancel_transaksi_id;
//                            $modSpo->approve_status =  \app\models\TCancelTransaksi::STATUS_ABORTED;

                            if ($modSpo->validate()) {
                                $success_2 = $modSpo->save();
                                // Start delete Mapping Table
                                $success_3 = \app\models\MapSppDetailReff::deleteAll("reff_no = '" . $modSpo->spo_kode . "' ");
                                // End delete Mapping Table

                            }
                            $modApproval = \app\models\TApproval::findOne(['reff_no' => $modSpo->spo_kode]);
                            $modApproval->status = \app\models\TCancelTransaksi::STATUS_ABORTED;
                            if ($modApproval->validate()) {
                                $success_4 = $modApproval->save();
                            }
                        }
                    } else {
                        $data['message_validate'] = \yii\widgets\ActiveForm::validate($modCancel);
                    }
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					echo "<pre>";
//					print_r($success_3);
//					echo "<pre>";
//					print_r($success_4);
//					exit;
                    if ($success_1 && $success_2 && $success_3 && $success_4) {
                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = Yii::t('app', 'PO Berhasil di Batalkan');
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

            return $this->renderAjax('cancelSpo', ['modSpo' => $modSpo, 'modCancel' => $modCancel]);
        }
    }

    public function actionPodibuat()
    {
        $model = new \app\models\TSpoDetail();
        $model->tgl_awal = date('d/m/Y', strtotime("-30 day"));
        $model->tgl_akhir = date('d/m/Y');
        return $this->render('podibuat', ['model' => $model]);
    }

    public function actionPodibuatGetItems()
    {
        if (\Yii::$app->request->isAjax) {
            $data['html'] = "<tr><td colspan='10' style='text-align: center;'>" . Yii::t('app', 'No Data Available') . "</td></tr>";
            $form_params = [];
            parse_str($_POST['formdata'], $form_params);
            $filterTgl = (!empty($form_params['TSpoDetail']['tgl_awal']) ? " t_spo.spo_tanggal BETWEEN '" . $form_params['TSpoDetail']['tgl_awal'] . "' AND '" . $form_params['TSpoDetail']['tgl_akhir'] . "'" : "");
            $filterSpoKode = (!empty($form_params['TSpoDetail']['spo_kode']) ? " AND spo_kode ILIKE '%" . $form_params['TSpoDetail']['spo_kode'] . "%' " : "");
            $filterBhpNm = (!empty($form_params['TSpoDetail']['bhp_nm']) ? " AND bhp_nm ILIKE '%" . $form_params['TSpoDetail']['bhp_nm'] . "%' " : "");
            $filterSuplierId = (!empty($form_params['TSpoDetail']['suplier_id']) ? " AND t_spo.suplier_id = " . $form_params['TSpoDetail']['suplier_id'] . " " : "");
            $filterStatus = (!empty($form_params['TSpoDetail']['status']) ? $form_params['TSpoDetail']['status'] : "");
            $where = ((!empty($filterTgl)) || (!empty($filterSpoKode)) || (!empty($filterBhpNm)) || (!empty($filterSuplierId))) ? "WHERE " : "";

            $sql = "SELECT * FROM t_spo_detail
					JOIN t_spo ON t_spo.spo_id =  t_spo_detail.spo_id
					JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_spo_detail.bhp_id
					JOIN m_suplier ON m_suplier.suplier_id = t_spo.suplier_id
						" . $where . " " . $filterTgl . " " . $filterSpoKode . " " . $filterBhpNm . " " . $filterSuplierId . " 
					ORDER BY t_spo.created_at DESC, t_spo_detail.spod_id ASC
					";
            $mods = \Yii::$app->db->createCommand($sql)->queryAll();
            if (count($mods) > 0) {
                $data['html'] = "";
                $no = 0;
                foreach ($mods as $i => $detail) {
                    $showrow = false;
                    $par['qty_terima'] = 0;
                    $par['kode_terima'] = [];
                    $sql0 = "SELECT * FROM t_terima_bhp_detail
							 JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id =  t_terima_bhp_detail.terima_bhp_id
							 JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_terima_bhp_detail.bhp_id
							 WHERE spo_id = " . $detail['spo_id'] . " AND t_terima_bhp_detail.bhp_id = " . $detail['bhp_id'];
                    $modTerima = Yii::$app->db->createCommand($sql0)->queryAll();
                    $where = "";

                    if (count($modTerima) > 0) {
                        foreach ($modTerima as $ii => $res) {
                            $par['qty_terima'] += $res['terimabhpd_qty'];
                            $par['kode_terima'][] = "<a onclick='infoTBP(\"" . $res['terima_bhp_id'] . "\",\"" . $res['bhp_id'] . "\")'>" . $res['terimabhp_kode'] . "</a>";
                            $sqlRetur = "SELECT * FROM t_retur_bhp WHERE terima_bhpd_id = " . $res['terima_bhpd_id'];
                            $modRetur = Yii::$app->db->createCommand($sqlRetur)->queryOne();
                            if (!empty($modRetur)) {
                                $par['kode_terima'][] .= '<a onclick="infoReturBHP(' . $modRetur['retur_bhp_id'] . ');" class="blue-steel" style="font-size: 1rem">' . $modRetur['kode'] . '</a>';
                            }
                        }
                    }
                    if (!empty($par['kode_terima'])) {
                        $par['kode_terima'] = implode("<br>", $par['kode_terima']);
                    } else {
                        $par['kode_terima'] = "";
                    }


                    if (!empty($detail['cancel_transaksi_id'])) {
                        $par['status'] = \app\models\TCancelTransaksi::STATUS_ABORTED;
                        $par['html_status'] = '<span class="label label-danger" style="font-size: 1.0rem">' . \app\models\TCancelTransaksi::STATUS_ABORTED . '</span>';
                    } else {
                        if ($par['qty_terima'] == 0) {
                            $par['status'] = "TO-DO";
                            $par['html_status'] = '<span class="label label-info" style="font-size: 1.0rem">' . $par['status'] . '</span>';
                        } else {
                            if ($par['qty_terima'] < $detail['spod_qty']) {
                                $par['status'] = "PARTIALLY";
                                $par['html_status'] = '<span class="label label-warning" style="font-size: 1.0rem">' . $par['status'] . '</span>';
                            } else {
                                $par['status'] = "COMPLETE";
                                $par['html_status'] = '<span class="label label-success" style="font-size: 1.0rem">' . $par['status'] . '</span>';
                            }
                        }
                    }

                    if ($filterStatus == "UNCOMPLETED") {
                        if (($par['status'] == "TO-DO") || ($par['status'] == "PARTIALLY")) {
                            $showrow = true;
                        }
                    } else if ($filterStatus == "COMPLETE") {
                        if (($par['status'] == "COMPLETE") || ($par['status'] == \app\models\TCancelTransaksi::STATUS_ABORTED)) {
                            $showrow = true;
                        }
                    } else {
                        $showrow = true;
                    }

                    if ($showrow == true) {
                        $no = $no + 1;
                        $data['html'] .= $this->renderPartial('_itemPodibuat', ['detail' => $detail, 'no' => $no, 'par' => $par]);
                    }

                }
            }
            return $this->asJson($data);
        }
    }

    public function actionPenawaranTerpilih($id, $by = "SPP")
    {
        if ($by == "SPP") {
            $param['where'] = "map_penawaran_bhp.sppd_id = " . $id;
            $model = \app\models\TSppDetail::findOne($id);
        } else if ($by == "SPO") {
            $param['where'] = "map_penawaran_bhp.spod_id = " . $id;
            $model = \app\models\TSpoDetail::findOne($id);
        }
        if (\Yii::$app->request->post('dt') == 'table-penawaran') {
            $param['table'] = \app\models\MapPenawaranBhp::tableName();
            $param['pk'] = \app\models\MapPenawaranBhp::primaryKey()[0];
            $param['join'] = ['JOIN t_penawaran_bhp ON t_penawaran_bhp.penawaran_bhp_id = map_penawaran_bhp.penawaran_bhp_id
							   JOIN m_suplier ON m_suplier.suplier_id = t_penawaran_bhp.suplier_id'];
            $param['column'] = ['map_penawaran_bhp.penawaran_bhp_id', 'kode', 'tanggal', 'suplier_nm', 'map_penawaran_bhp.qty', 'satuan_kecil', 'harga_satuan', 'keterangan', 'attachment'];
            $param['order'] = "map_penawaran_bhp.created_at DESC";
            return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
        }
        return $this->renderAjax('penawaranTerpilih', ['id' => $id, 'by' => $by, 'model' => $model]);
    }

    public function actionWarningPenawaran()
    {
        if (\Yii::$app->request->isAjax) {

            return $this->renderAjax('warningPenawaran');
        }
    }
    
}
