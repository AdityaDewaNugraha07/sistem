<?php

namespace app\modules\topmanagement\controllers;

use app\components\Params;
use app\components\SSP;
use app\models\HPersediaanProduk;
use app\models\TApproval;
use app\models\TInvoice;
use app\models\TInvoiceDetail;
use app\models\TPackinglist;
use app\models\TPengajuanManipulasi;
use app\models\TProdukKeluar;
use app\models\TProdukKembali;
use app\models\TSpmKo;
use app\models\TSpmKoDetail;
use app\models\ViewApproval;
use Yii;
use app\controllers\DeltaBaseController;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\Json;
use yii\web\Response;

class ApprovalhapusspmexportController extends DeltaBaseController
{
    public function actionIndex()
    {
        if (Yii::$app->request->get('dt') === 'table-master') {
            $param['table']     = ViewApproval::tableName();
            $param['pk']        = "approval_id";
            $param['column']    = ['approval_id',
                't_pengajuan_manipulasi.reff_no',
                't_pengajuan_manipulasi.datadetail1',
                [
                    'col_name'  => 'tanggal_berkas',
                    'formatter' => 'formatDateTimeForUser'
                ],
                'assigned_nama',
                'approved_by_nama',
                't_pengajuan_manipulasi.reason',
                $param['table'] . '.status',
                $param['table'] . '.created_at',
                [
                    'col_name'  => 'tanggal_approve',
                    'formatter' => 'formatDateTimeForUser'
                ],
            ];

            $param['join']      = " join t_pengajuan_manipulasi on view_approval.reff_no = t_pengajuan_manipulasi.reff_no AND view_approval.parameter1 = t_pengajuan_manipulasi.kode";
            if(isset($_GET['status']) && $_GET['status'] === TApproval::STATUS_NOT_CONFIRMATED) {
                $param['where'] = "view_approval.status = 'Not Confirmed'";
                $param['where'] .= " AND t_pengajuan_manipulasi.status = 'PROCESS' ";
            }else {
                $param['where'] = "view_approval.status <> 'Not Confirmed'";
//                $param['where'] .= " AND t_pengajuan_manipulasi.status <> 'PROCESS' ";
            }
            $param['where'] .= " AND t_pengajuan_manipulasi.tipe = 'PERMINTAAN HAPUS SPM EXPORT' ";
            if (Yii::$app->user->identity->user_group_id !== Params::USER_GROUP_ID_SUPER_USER) {
                $param['where'] .= "AND assigned_to = " . Yii::$app->user->identity->pegawai_id . "  ";
            }

            $param['order'] = "created_at DESC, level ASC";
            return Json::encode(SSP::complex($param));
        }
        return $this->render('index');

    }

    /**
     * @param $id
     * @return string|void
     */
    public function actionInfo($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = TApproval::findOne($id);
            return $this->renderAjax('info', ['model' => $model]);
        }
    }

    /**
     * @param $id
     * @return false|Response
     * @throws Exception
     */
    public function actionApproveReason($id)
    {
        $model      = TApproval::findOne($id);
        $modelReff  = TPengajuanManipulasi::findOne(['reff_no' => $model->reff_no, 'kode' => $model->parameter1]);
        if (Yii::$app->request->isPost) {
            $transaction    = Yii::$app->db->beginTransaction();
            $alasan         = $_POST['TPengajuanManipulasi']['reason_approval'];
            try {
                $spm            = Json::decode($modelReff->datadetail1, false);
                $prevApproval   = TApproval::findOne(['level' => $model->level - 1, 'reff_no' => $spm->kode, 'parameter1' => $modelReff->kode]);
                $produkKeluar   = TProdukKeluar::findAll(['reff_no' => $spm->kode]);
                $produkKembali  = TProdukKembali::findAll(['reff_no' => $modelReff->kode]);
                if($spm->status === TSpmKo::REALISASI) {
                    if ($model->level === 2) {
                        if($prevApproval->status === TApproval::STATUS_NOT_CONFIRMATED || $prevApproval->status !== TApproval::STATUS_APPROVED) {
                            throw new Exception('Approver sebelumnnya belum / tidak menyetujui' );
                        }

                        if(count($produkKeluar) < 1) {
                            throw new Exception("Barang belum di scan pengembalian");
                        }

                        if(count($produkKeluar) !== count($produkKembali)) {
                            throw new Exception("Jumlah barang yang dimuat dengan yang dikembalikan tidak sama");
                        }
                    }elseif ($model->level === 3) {
                        if($prevApproval->status === TApproval::STATUS_NOT_CONFIRMATED || $prevApproval->status !== TApproval::STATUS_APPROVED) {
                            throw new Exception('Approver sebelumnnya belum / tidak menyetujui' );
                        }
                    }elseif ($model->level === 4) {
                        if($prevApproval->status === TApproval::STATUS_NOT_CONFIRMATED || $prevApproval->status !== TApproval::STATUS_APPROVED) {
                            throw new Exception('Approver sebelumnnya belum / tidak menyetujui' );
                        }

                        if(!$this->_hapusSPM($spm)) {
                            throw new Exception('Pembatalan SPM Gagal' );
                        }

                        foreach ($produkKembali as $produk) {
                            $persediaan                         = new HPersediaanProduk();
                            $persediaan->attributes             = $produk->attributes;
                            $persediaan->reff_no                = $produk->kode;
                            $persediaan->reff_detail_id         = null;
                            $persediaan->tgl_transaksi          = date('Y-m-d');
                            $persediaan->in_qty_palet           = $produk->qty_besar;
                            $persediaan->in_qty_kecil           = $produk->qty_kecil;
                            $persediaan->in_qty_kecil_satuan    = $produk->satuan_kecil;
                            $persediaan->in_qty_m3              = $produk->kubikasi;
                            $persediaan->out_qty_palet          = 0;
                            $persediaan->out_qty_kecil          = 0;
                            $persediaan->out_qty_kecil_satuan   = $produk->satuan_kecil;
                            $persediaan->out_qty_m3             = 0;
                            $persediaan->keterangan             = 'PENERIMAAN PERSEDIAAN DARI BATAL SPM EXPORT';
                            if(!$persediaan->validate() || !$persediaan->save()) {
                                $msg = '<ul style="text-align: left; margin-left: -30px">';
                                foreach ($persediaan->errors as $error) {
                                    $msg .= '<li>'. $error[0] .'</li>';
                                }
                                $msg .= '</ul>';
                                throw new Exception($msg);
                            }
                        }

                        $modelReff->status = 'DONE';
                        $modelReff->save();
                    }
                }else if($model->level === 2) {
                    if($prevApproval->status === TApproval::STATUS_NOT_CONFIRMATED || $prevApproval->status !== TApproval::STATUS_APPROVED) {
                        throw new Exception('Approver sebelumnnya belum melakukan approve' );
                    }

                    if(count($produkKeluar) > 0) {
                        throw new Exception("Scan Pemuatan untuk kode SPM <strong>$spm->kode</strong> belum dihapus.<br>Mohon dikoordinasikan dengan bagian terkait");
                    }

                    if(!$this->_hapusSPM($spm)) {
                        throw new Exception('Pembatalan SPM Gagal' );
                    }

                    $modelReff->status = 'DONE';
                    $modelReff->save();
                }

                return $this->_saveApprove($transaction, $model, $modelReff, $alasan);

            } catch (Exception $exception) {
                $data['status']     = false;
                $data['message']    = $exception->getMessage();
                $transaction->rollback();
                return $this->asJson($data);
            }
        }
        return $this->renderAjax('approveReason', compact('modelReff'));
    }

    /**
     * @param $id
     * @return string|void|Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionRejectReason($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = TApproval::findOne($id);
            $modelReff = TPengajuanManipulasi::findOne(['reff_no' => $model->reff_no, 'kode' => $model->parameter1, 'status' => 'PROCESS']);
            if (Yii::$app->request->isPost) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->approved_by = Yii::$app->user->identity->pegawai->pegawai_id;
                    $model->tanggal_approve = date('Y-m-d');
                    $model->status = TApproval::STATUS_REJECTED;
                    $build = [];
                    if(!empty($modelReff->reason_approval) && $model->validate() && $model->save()) {
                        foreach (Json::decode($modelReff->reason_approval, false) as $approval) {
                            if($approval->pegawai_id === $model->assigned_to && $approval->level === $model->level) {
                                $build[] = [
                                    'pegawai_id' => $model->assigned_to,
                                    'tanggal' => $model->tanggal_berkas,
                                    'status' => $model->status,
                                    'reason' => $_POST['TPengajuanManipulasi']['reason_approval'],
                                    'level' => $model->level
                                ];
                            }else if($approval->level > $model->level) {
                                $build[] = [
                                    'pegawai_id' => $approval->pegawai_id,
                                    'tanggal' => $approval->tanggal,
                                    'status' => TApproval::STATUS_REJECTED,
                                    'reason' => 'Rejected by ' . $model->assignedTo->pegawai_nama,
                                    'level' => $approval->level
                                ];
                            }else {
                                $build[] = [
                                    'pegawai_id' => $approval->pegawai_id,
                                    'tanggal' => $approval->tanggal,
                                    'status' => $approval->status,
                                    'reason' => $approval->reason,
                                    'level' => $approval->level
                                ];
                            }
                        }
                    }

                    $modSpm = TSpmKo::findOne(['kode' => $modelReff->reff_no]);
                    if(($modSpm->status === TSpmKo::REALISASI) && $model->level > 1) {
                        $prevApproval = TApproval::findOne(['reff_no' => $model->reff_no, 'parameter1' => $model->parameter1, 'level' => 1]);
                        if($prevApproval->status === TApproval::STATUS_APPROVED) { // jika approval level 1 TIDAK APPROVED maka tidak ada scan kembali -> proses bisa di skip
                            $produkKembali = TProdukKembali::findAll(['reff_no' => $modelReff->kode]);
                            if(count($produkKembali) > 0) {
                                foreach ($produkKembali as $produk) {
                                    if(!$produk->delete()) {
                                        throw new Exception('Gagal menghapus produk yang telah di scan untuk di kembalikan ke gudang');
                                    }
                                }
                            }
                        }
                    }

                    $modelReff->reason_approval = Json::encode($build);
                    $modelReff->status          = 'REJECT';

                    if($transaction !== null && $modelReff->validate() && $modelReff->save()) {

                        // reject sisa level yang ada

                        for ($i = $model->level + 1; $i <= 4; $i++) {
                            $approver = TApproval::findOne(['reff_no' => $model->reff_no, 'level' => $i, 'parameter1' => $model->parameter1]);
                            $approver->status = TApproval::STATUS_REJECTED;
                            $approver->approved_by = Yii::$app->user->identity->pegawai->pegawai_id;
                            $approver->tanggal_approve = date('Y-m-d');
                            if(!$approver->validate() || !$approver->save()) {
                                throw new Exception(Params::DEFAULT_FAILED_TRANSACTION_MESSAGE);
                            }
                        }

                        $transaction->commit();
                        $data['status'] = true;
                        $data['message'] = 'Berhasil menolak permintaan penghapusan SPM Export';
                        $data['callback'] = 'setTimeout(() => window.location.href = "/cis/web/topmanagement/approvalhapusspmexport/index",1000);';
                        return $this->asJson($data);
                    }
                } catch (Exception $exception) {
                    $transaction->rollback();
                    $data['status'] = false;
                    $data['message'] = $exception->getMessage();
                    return $this->asJson($data);
                }
            }
            return $this->renderAjax('rejectReason', compact('modelReff'));
        }
    }

    /**
     * @param $transaction
     * @param $model
     * @param $modelReff
     * @param $alasan
     * @return false|Response
     */
    private function _saveApprove($transaction, $model, $modelReff, $alasan)
    {
        $model->approved_by = Yii::$app->user->identity->pegawai->pegawai_id;
        $model->tanggal_approve = date('Y-m-d');
        $model->status = TApproval::STATUS_APPROVED;
        $build = [];
        if(!empty($modelReff->reason_approval) && $model->validate() && $model->save()) {
            foreach (Json::decode($modelReff->reason_approval, false) as $approval) {
                if($approval->pegawai_id === $model->assigned_to && $approval->level === $model->level) {
                    $build[] = [
                        'pegawai_id' => $model->assigned_to,
                        'tanggal' => $model->tanggal_berkas,
                        'status' => $model->status,
                        'reason' => $alasan,
                        'level' => $model->level
                    ];
                }else {
                    $build[] = [
                        'pegawai_id' => $approval->pegawai_id,
                        'tanggal' => $approval->tanggal,
                        'status' => $approval->status,
                        'reason' => $approval->reason,
                        'level' => $approval->level
                    ];
                }
            }
        }

        $modelReff->reason_approval = Json::encode($build);
        if( $transaction !== null && $modelReff->validate() && $modelReff->save()) {
            $transaction->commit();
            $data['status'] = true;
            $data['message'] = 'Berhasil melakukan approve';
            $data['callback'] = 'setTimeout(() => window.location.href = "/cis/web/topmanagement/approvalhapusspmexport/index",1000);';
            return $this->asJson($data);
        }

        return false;
    }

    /**
     * @throws StaleObjectException
     */
    private function _hapusSPM($spm)
    {
        /**
         * PROSEDUR HAPUS SPM DAN INVOICE
         * 1. update status packinglist jadi proforma
         * 2. hapus detail invoice
         * 3. hapus invoice
         * 4. hapus detail spm
         * 5. hapus spm
         */        
        $packinglist = TPackinglist::findOne(['packinglist_id' => $spm->packinglist_id]);
        $packinglist->status = 'PROFORMA';
        $pkglistUpdated = $packinglist->save();
        //throw new Exception("ID ".$packinglist->packinglist_id." status : '".$packinglist->status."'");
        // if(!$packinglist->save()){
        //     Yii::error($packinglist->errors);
        //     throw new Exception("Status Packinglist tidak berhasil dirubah menjadi PROFORMA");
        // }
        $invoice = TInvoice::findOne(['packinglist_id' => $spm->packinglist_id]);
        $invDetailDeleted = true;
        $invDeleted = true;
        if($invoice) {
            $invDetailDeleted = TInvoiceDetail::deleteAll(['invoice_id' => $invoice->invoice_id]);
            $invDeleted = $invoice->delete();
        }
        $spmDetailDeleted = TSpmKoDetail::deleteAll(['spm_ko_id' => $spm->spm_ko_id]);
        $spmDeleted = TSpmKo::findOne(['spm_ko_id' => $spm->spm_ko_id])->delete();
        // throw new Exception("updatepackinglist :".$pkglistUpdated." del inv detail :".$invDetailDeleted." del inv :".$invDeleted." del spm detail : ".$spmDetailDeleted." del spm : ".$spmDeleted );exit;
        return $pkglistUpdated && $invDetailDeleted && $invDeleted && $spmDetailDeleted && $spmDeleted;
    }
}
