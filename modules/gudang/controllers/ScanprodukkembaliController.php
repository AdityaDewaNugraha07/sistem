<?php

namespace app\modules\gudang\controllers;

use app\components\DeltaGenerator;
use app\components\Params;
use app\components\SSP;
use app\controllers\DeltaBaseController;
use app\models\HPersediaanProduk;
use app\models\MBrgProduk;
use app\models\MGudang;
use app\models\TApproval;
use app\models\TPengajuanManipulasi;
use app\models\TProdukKeluar;
use app\models\TProdukKembali;
use app\models\TProduksi;
use app\models\TSpmKo;
use app\models\TSpmKoDetail;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;

class ScanprodukkembaliController extends DeltaBaseController
{
    /**
     * @return string
     * @throws Exception
     */
    public function actionIndex()
    {
        $model = new TProdukKembali();
        $sql = "SELECT
                    t_spm_ko.spm_ko_id,
                    concat ( t_pengajuan_manipulasi.reff_no, ' - ', m_customer.cust_an_nama ) 
                FROM
                    t_pengajuan_manipulasi
                    INNER JOIN t_spm_ko ON t_pengajuan_manipulasi.reff_no = t_spm_ko.kode
                    INNER JOIN m_customer ON t_spm_ko.cust_id = m_customer.cust_id 
                WHERE
                    t_pengajuan_manipulasi.tipe = 'PERMINTAAN HAPUS SPM EXPORT' 
                  AND t_pengajuan_manipulasi.status = 'PROCESS'
                  AND t_spm_ko.status = 'REALISASI'";
        $spmDropdown = Yii::$app->db->createCommand($sql)->queryAll();
        $spmDropdown = ArrayHelper::map($spmDropdown, 'spm_ko_id', 'concat');

        $gudangDropdown = MGudang::find()->all();
        $gudangDropdown = ArrayHelper::map($gudangDropdown, 'gudang_id', 'gudang_nm');

        if(Yii::$app->request->isPost) {
            $spm_ko_id = Yii::$app->request->post('spm_ko_id');
            return Json::encode(SSP::complex($model->searchLaporanDt($spm_ko_id)));
        }

        return $this->render('index', compact('model', 'spmDropdown', 'gudangDropdown'));
    }

    public function actionShowDetail()
    {
        $data['status'] = false;
        if (Yii::$app->request->isAjax) {
            $spm_ko_id = Yii::$app->request->post('spm_ko_id');
            $nomor_produksi = Yii::$app->request->post('nomor_produksi');

            $modSpm = TSpmKo::findOne($spm_ko_id);
            if ($modSpm === null) {
                $data['status'] = false;
                $data['msg'] = "Data SPM tidak ditemukan!";
                return $this->asJson($data);
            }

            $modProdukKeluar = TProdukKeluar::findOne(['nomor_produksi' => $nomor_produksi, 'reff_no' => $modSpm->kode]);
            if($modProdukKeluar === null) {
                $data['status'] = false;
                $data['msg'] = "Produk dengan nomor produksi " . $nomor_produksi . ' belum pernah dikeluarkan';
                return $this->asJson($data);
            }

            $manipulasi = TPengajuanManipulasi::findOne(['reff_no' => $modSpm->kode, 'status' => 'PROCESS']);
            $modProdukKembali = TProdukKembali::findOne(['nomor_produksi' => $nomor_produksi, 'reff_no' => $manipulasi->kode]);
            if ($modProdukKembali !== null) {
                $data['status'] = false;
                $data['msg'] = "Produk sudah pernah kembali!";
                return $this->asJson($data);
            }

            $modSpmDetail = TSpmKoDetail::findAll(['spm_ko_id' => $spm_ko_id, 'produk_id' => $modProdukKeluar->produk_id]);
            if (count($modSpmDetail) < 1) {
                $data['status'] = false;
                $data['msg'] = "Produk tidak sesuai dengan SPM";
                return $this->asJson($data);
            }

            $modPersediaan = HPersediaanProduk::findAll(['nomor_produksi' => $nomor_produksi]);
            if (count($modPersediaan) < 1) {
                $data['status'] = false;
                $data['msg'] = "Belum pernah ada produk yang dikeluarkan";
                return $this->asJson($data);
            }

            $data['status'] = true;
            $data['msg'] = "Data ok";
            return $this->asJson($data);
        }

        $data['msg'] = "xxx";
        return $this->asJson($data);
    }

    /**
     * @return string|void
     */

    public function actionReview()
    {
        if (Yii::$app->request->isAjax) {
            $spm_ko_id = $_GET['spm_ko_id'];
            $nomor_produksi = $_GET['nomor_produksi'];
            $gudang_id = $_GET['gudang_id'];
            $modProduksi = TProduksi::findOne(['nomor_produksi' => $nomor_produksi]);
            $modBrgProduk = MBrgProduk::findOne(['produk_id' => $modProduksi->produk_id]);
            $modPersediaan = HPersediaanProduk::findOne(['nomor_produksi' => $nomor_produksi]);
            return $this->renderAjax('_review', [
                'spm_ko_id' => $spm_ko_id,
                'gudang_id' => $gudang_id,
                'nomor_produksi' => $nomor_produksi,
                'modBrgProduk' => $modBrgProduk,
                'modPersediaan' => $modPersediaan,
            ]);
        }
    }


    /**
     * @return void|Response
     */
    public function actionSaveNomorProduksi()
    {
        if(Yii::$app->request->isPost) {
            $data['status'] = true;
            $data['msg'] = [];
            $nomor_produksi = Yii::$app->request->post('nomor_produksi');
            $spm_ko_id = Yii::$app->request->post('spm_ko_id');
            $gudang_id = Yii::$app->request->post('gudang_id');
            $modSpm = TSpmKo::findOne(['spm_ko_id' => $spm_ko_id]);
            $modManipulasi = TPengajuanManipulasi::findOne(['reff_no' => $modSpm->kode, 'status' => 'PROCESS']);
            $modProduksi = TProduksi::findOne(['nomor_produksi' => $nomor_produksi]);
            $modProdukKeluar = TProdukKeluar::findOne(['nomor_produksi' => $nomor_produksi]);
            $approval_1 = TApproval::findOne(['reff_no' => $modSpm->kode, 'level' => 1, 'parameter1' => $modManipulasi->kode]);

            if(!isset($nomor_produksi, $spm_ko_id)) {
                $data['msg'][] = 'Nomor Produksi / ID Spm tidak boleh kosong';
            }

            if($modSpm === null) {
                $data['status']= false;
                $data['msg'][] = 'SPM tidak ditemukan!';
            }

            if($modManipulasi === null) {
                $data['status']= false;
                $data['msg'][] = 'Tidak ada permintaan batal SPM';
            }

            if($modProduksi === null) {
                $data['status']= false;
                $data['msg'][] = 'Nomor Produksi tidak diketahui';
            }

            if($modProdukKeluar === null) {
                $data['status']= false;
                $data['msg'][] = 'Produk belum pernah dikeluarkan';
            }

            if($approval_1->status !== TApproval::STATUS_APPROVED) {
                $data['status']= false;
                $panggilan = $approval_1->assignedTo->pegawai_jk === 'Perempuan' ? 'Ibu ' : 'Bapak ';
                $nama = ucwords(strtolower($approval_1->assignedTo->pegawai_nama));
                $data['msg'][] = "<i class='icon-close'></i> Data Gagal dimuat karena $panggilan <strong> $nama </strong> belum melakukan approve";
            }

            if($data['status']) {
                $modProdKembali = new TProdukKembali();
                $modProdKembali->attributes = $modProdukKeluar->attributes;
                $modProdKembali->kode = DeltaGenerator::kodeProdukkembali($modProduksi->produk->produk_group);
                $modProdKembali->tanggal = date('Y-m-d');
                $modProdKembali->nomor_produksi = $nomor_produksi;
                $modProdKembali->cara_kembali = TProdukKembali::CARA_KEMBALI_BATAL_SPM;
                $modProdKembali->reff_detail_id = null;
                $modProdKembali->reff_no = $modManipulasi->kode;
                $modProdKembali->petugas_penerima = Yii::$app->user->identity->pegawai_id;
                $modProdKembali->gudang_id = (int)$gudang_id;

                if($modProdKembali->validate() && $modProdKembali->save()) {
                    $data['msg'][] = 'Data berhasil disimpan';

                    $produk_keluar = TProdukKeluar::find()
                                                    ->where(['nomor_produksi' => $nomor_produksi])
                                                    ->andWhere(['or', ['cancel_transaksi_id' => null], ['produk_kembali_id' => null]])
                                                    ->one();
                    if(isset($produk_keluar)) {
                        $produk_keluar->produk_kembali_id = $modProdKembali->produk_kembali_id;
                        $produk_keluar->validate();
                        $produk_keluar->save();
                    }
                }else {
                    $data['status'] = false;
                    $data['msg'] = $modProdKembali->getErrors();
                }
            }

            return $this->asJson($data);
        }
    }

    /**
     * @param $id
     * @return string|void|Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionHapusProdukKembali($id)
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post('deleteRecord')) {
                $data['status']     = false;
                $data['message']    = Params::DEFAULT_FAILED_TRANSACTION_MESSAGE;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $produkKembali = TProdukKembali::findOne($id);
                    if($produkKembali === null) {
                        throw new Exception('Data tidak ditemukan');
                    }

                    $manipulasi = TPengajuanManipulasi::findOne(['kode' => $produkKembali->reff_no]);
                    if($manipulasi === null) {
                        throw new Exception('Tidak ada permintaan pembatalan SPM');
                    }

                    if($manipulasi->status !== 'PROCESS') {
                        throw new Exception('Proses permintaan pembatalan SPM sudah selesai, data tidak bisa di hapus');
                    }

                    $produkkeluar = TProdukKeluar::findOne(['produk_kembali_id' => $produkKembali->produk_kembali_id]);
                    $produkkeluar->produk_kembali_id = null;
                    if($produkkeluar->validate() && $produkkeluar->save() && $produkKembali->delete()) {
                        $transaction->commit();
                        $data['status']     = true;
                        $data['message']    = Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE;
                        $data['callback']   = 'window.tableProduk.ajax.reload()';
                    }
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $data['status']     = false;
                    $data['message']    = $ex->getMessage();
                }
                return $this->asJson($data);
            }
            return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm', [
                'id'            => $id,
                'actionname'    => 'hapusProdukKembali'
            ]);
        }
    }
}
