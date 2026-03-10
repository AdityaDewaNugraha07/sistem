<?php

namespace app\modules\ppic\controllers;

use Yii;
use kartik\mpdf\Pdf;
use yii\db\Exception;
use yii\helpers\Json;
use app\components\SSP;
use app\models\TApproval;
use app\models\TLogKeluar;
use app\models\HPersediaanLog;
use app\models\THasilRepacking;
use app\components\DeltaFormatter;
use app\models\TTerimaLogalamPabrik;
use yii\base\InvalidConfigException;
use app\models\TPengajuanPembelianlog;
use app\controllers\DeltaBaseController;
use app\models\TTerimaLogalam;
use app\models\TTerimaLogalamDetail;
use Symfony\Component\Yaml\Exception\DumpException;

class LaporanController extends DeltaBaseController
{
    public function actionCompare()
    {
        $modelTerimaLogalam = new \app\models\TTerimaLogalamDetail();
        isset($_POST['area_pembelian']) ? $area_pembelian = $_POST['area_pembelian'] : $area_pembelian = "";
        isset($_POST['pengajuan_pembelianlog_id']) ? $pengajuan_pembelianlog_id = $_POST['pengajuan_pembelianlog_id'] : $pengajuan_pembelianlog_id = "";
        isset($_POST['spk_shipping_id']) ? $spk_shipping_id = $_POST['spk_shipping_id'] : $spk_shipping_id = "";
        return $this->render('/laporan/compare/index', ['modelTerimaLogalam' => $modelTerimaLogalam, 'area_pembelian' => $area_pembelian, 'pengajuan_pembelianlog_id' => $pengajuan_pembelianlog_id, 'spk_shipping_id' => $spk_shipping_id]);
    }

    public function actionCompares()
    {
        if (Yii::$app->request->isAjax) {
            $request        = Yii::$app->request->post();
            $html_loglist   = "";
            $html_terima    = "";
            $vol_loglist    = 0;
            $vol_terima     = 0;
            $kayu_loglist   = [];
            $kayu_terima    = [];
            // jika area pembelian dari jawa maka ambil dari : t_pengajuan_pembelianlog -> pengajuan_pembelianlog_id
            if ($request['area_pembelian'] == "Jawa") {
                $pengajuan_pembelianlogs = TPengajuanPembelianlog::findAll(['pengajuan_pembelianlog_id' => $request['pengajuan_pembelianlog_id']]);
            }
            // jika area pembelian dari luar jawa maka ambil dari : t_pengajuan_pembelianlog -> t_spk_shipping
            else {
                $pengajuan_pembelianlogs = TPengajuanPembelianlog::findAll(['spk_shipping_id' => $request['spk_shipping_id']]);
            }

            $orderby = $this->__compareOrder($request);
            foreach ($pengajuan_pembelianlogs as $pengajuan_pembelianlog) {
                $html_loglist   .= "<tr><td colspan='11' class='td-kecil'><b>$pengajuan_pembelianlog->kode</b></td></tr>";
                $html_terima    .= "<tr><td colspan='16' class='td-kecil'><b>$pengajuan_pembelianlog->kode</b></td></tr>";
                $loglists       = $this->__compareLoglistData($pengajuan_pembelianlog->pengajuan_pembelianlog_id, $orderby['loglist']);
                $terima         = $this->__comparePenerimaanData($pengajuan_pembelianlog->pengajuan_pembelianlog_id, $orderby['terima']);
                $max            = max(count($loglists), count($terima));
                for($i = 0; $i < $max; $i++) {
                    $no = $i + 1;
                    if(isset($loglists[$i])) {
                        if(isset($kayu_loglist[$loglists[$i]['kayu_nama']])) {
                            $kayu_loglist[$loglists[$i]['kayu_nama']]++;
                        }else {
                            $kayu_loglist[$loglists[$i]['kayu_nama']] = 1;
                        }
                        $vol_loglist    += $loglists[$i]['volume_value'];
                        $html_loglist   .= $this->renderPartial('compare/_loglist', ['loglist' => $loglists[$i], 'i' => $no]);
                    }else {
                        $html_loglist   .= $this->renderPartial('compare/_loglist', ['loglist' => null, 'i' => $no]);
                    }
                    
                    if(isset($terima[$i])) {
                        if(isset($kayu_terima[$terima[$i]['kayu_nama']])) {
                            $kayu_terima[$terima[$i]['kayu_nama']]++;
                        }else {
                            $kayu_terima[$terima[$i]['kayu_nama']] = 1;
                        }
                        $vol_terima     += $terima[$i]['volume']; 
                        $html_terima    .= $this->renderPartial('compare/_penerimaan', ['terima' => $terima[$i], 'i' => $no]);
                    }else {
                        $html_terima    .= $this->renderPartial('compare/_penerimaan', ['terima' => null, 'i' => $no]);
                    }
                }
            }

            $jml_kayu_loglist = count($kayu_loglist);
            $no = 1;
            foreach($kayu_loglist as $kayu => $jml) {
                if($no === 1) {
                    $html_loglist   .= "<tr class='Jloglist'>
                                            <td colspan='4' class='text-right'>$kayu</td>
                                            <th class='text-center' colspan='2'>$jml</th>
                                            <td rowspan='$jml_kayu_loglist' colspan='3' style='vertical-align:middle' class='text-right'>Total Volume</td>
                                            <th rowspan='$jml_kayu_loglist' colspan='2' style='vertical-align:middle' class='text-center'>$vol_loglist</th>
                                        </tr>";
                }else {
                    $html_loglist   .= "<tr class='Jloglist'><td colspan='4' class='text-right'>$kayu</td><th class='text-center' colspan='2'>$jml</th></tr>";
                }
                $no++;
            }

            $jml_kayu_terima = count($kayu_terima);
            $no = 1;
            foreach($kayu_terima as $kayu => $jml) {
                if($no === 1) {
                    $html_terima   .= " <tr class='Jpenerimaan'>
                                            <td colspan='7' class='text-right'>$kayu</td>
                                            <th class='text-center' colspan='2'>$jml</th>
                                            <td rowspan='$jml_kayu_terima' colspan='5' style='vertical-align:middle' class='text-right'>Total Volume</td>
                                            <th rowspan='$jml_kayu_terima' colspan='2' style='vertical-align:middle' class='text-center'>$vol_terima</th>
                                        </tr>";
                }else {
                    $html_terima   .= "<tr class='Jpenerimaan'><td colspan='7' class='text-right'>$kayu</td><th class='text-center' colspan='2'>$jml</th></tr>";
                }
                $no++;
            }
            return $this->asJson([
                'html_loglist' => $html_loglist,
                'html_terima' => $html_terima
            ]);
        }
    }

    private function __compareOrder($request)
    {
        // untuk sorting
        $orderby = ['loglist' => '', 'terima' => ''];
        if (!empty($request['orders']) && !empty($request['orders'])) {
            $orders = ['loglist' => [], 'terima' => []];
            foreach ($request['orders'] as $item) {
                if (!empty($item['direction'])) {
                    if ($item['group'] === 'loglist') {
                        $orders['loglist'][] = $item;
                    } else {
                        $orders['terima'][]  = $item;
                    }
                }
            }
            if (count($orders['loglist']) > 0) {
                $orderby['loglist'] .= " ORDER BY ";
                foreach ($orders['loglist'] as $key => $order) {
                    $template   = $order['column'] . "::" . $order['type'] . " " . $order['direction'];
                    $orderby['loglist'] .= $key === count($orders['loglist']) - 1 ? $template . " " : $template . ", ";
                }
            }
            if (count($orders['terima']) > 0) {
                $orderby['terima'] .= " ORDER BY ";
                foreach ($orders['terima'] as $key => $order) {
                    $template   = $order['column'] . "::" . $order['type'] . " " . $order['direction'];
                    $orderby['terima'] .= $key === count($orders['terima']) - 1 ? $template . " " : $template . ", ";
                }
            }
        }
        // end sorting
        return $orderby;
    }

    private function __compareLoglistData($pengajuan_pembelianlog_id, $orderby = ' ORDER BY t_loglist_detail.nomor_grd::INTEGER ASC')
    {
        $loglistsql     = " SELECT
                                t_loglist_detail.nomor_grd, 
                                t_loglist_detail.nomor_produksi, 
                                t_loglist_detail.nomor_batang, 
                                m_kayu.kayu_nama, 
                                t_loglist_detail.panjang, 
                                t_loglist_detail.cacat_panjang, 
                                t_loglist_detail.cacat_gb, 
                                t_loglist_detail.cacat_gr, 
                                t_loglist_detail.diameter_rata, 
                                t_loglist_detail.volume_value
                            FROM t_loglist_detail
                            INNER JOIN t_loglist ON t_loglist_detail.loglist_id = t_loglist.loglist_id
                            INNER JOIN m_kayu ON t_loglist_detail.kayu_id = m_kayu.kayu_id
                            WHERE t_loglist.pengajuan_pembelianlog_id = $pengajuan_pembelianlog_id  $orderby";
        return  Yii::$app->db->createCommand($loglistsql)->queryAll();
    }

    private function __comparePenerimaanData($pengajuan_pembelianlog_id, $orderby = ' ORDER BY t_terima_logalam_detail.no_grade::INTEGER ASC')
    {
        $terimasql      = " SELECT
                                t_terima_logalam_detail.no_grade,
                                t_terima_logalam_detail.no_btg,
                                t_terima_logalam_detail.no_produksi,
                                t_terima_logalam_detail.no_lap,
                                t_terima_logalam_detail.no_barcode,
                                m_kayu.kayu_nama,
                                t_terima_logalam_detail.panjang,
                                t_terima_logalam_detail.diameter_ujung1,
                                t_terima_logalam_detail.diameter_ujung2,
                                t_terima_logalam_detail.diameter_pangkal1,
                                t_terima_logalam_detail.diameter_pangkal2,
                                t_terima_logalam_detail.cacat_panjang,
                                t_terima_logalam_detail.cacat_gb,
                                t_terima_logalam_detail.cacat_gr,
                                t_terima_logalam_detail.volume 
                            FROM t_terima_logalam_detail
                            INNER JOIN m_kayu ON t_terima_logalam_detail.kayu_id = m_kayu.kayu_id 
                            WHERE pengajuan_pembelianlog_id = $pengajuan_pembelianlog_id  $orderby";
        return Yii::$app->db->createCommand($terimasql)->queryAll();
    }

    public function actionComparePrint()
    {
        $this->layout   = '@views/layouts/metronic/print';
        $request        = Yii::$app->request->get();
        $paramprint['judul']    = Yii::t('app', 'Compare Loglist dan Penerimaan Log');
        
        // jika area pembelian dari jawa maka ambil dari : t_pengajuan_pembelianlog -> pengajuan_pembelianlog_id
        if ($request['area_pembelian'] == "Jawa") {
            $pengajuan_pembelianlogs = TPengajuanPembelianlog::findAll(['pengajuan_pembelianlog_id' => $request['pengajuan_pembelianlog_id']]);
        }
        // jika area pembelian dari luar jawa maka ambil dari : t_pengajuan_pembelianlog -> t_spk_shipping
        else {
            $pengajuan_pembelianlogs = TPengajuanPembelianlog::findAll(['spk_shipping_id' => $request['spk_shipping_id']]);
        }
        



        if ($request['caraprint'] == 'PRINT') {
            return $this->renderPartial('/laporan/laporanStokLog/print', ['model' => $model, 'paramprint' => $paramprint]);
        } else if ($request['caraprint'] == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->renderPartial('/laporan/laporanStokLog/print', ['model' => $model, 'paramprint' => $paramprint]);
            return $pdf->render();
        } else if ($request['caraprint'] == 'EXCEL') {
            $html = '';
            foreach($pengajuan_pembelianlogs as $pengajuan_pembelianlog) {
                $loglists   = $this->__compareLoglistData($pengajuan_pembelianlog->pengajuan_pembelianlog_id);
                $terima     = $this->__comparePenerimaanData($pengajuan_pembelianlog->pengajuan_pembelianlog_id);
                $html      .= $this->renderPartial('/laporan/compare/_itemPrint', [
                    'loglists' => $loglists,
                    'terima' => $terima,
                    'pengajuan_pembelianlog_kode' => $pengajuan_pembelianlog->kode
                ]);
            }
            
            return $this->renderPartial('/laporan/compare/print', ['html' => $html, 'paramprint' => $paramprint]);
        }
    }

    function actionGetQuery()
    {
        $param['table'] = \app\models\TTerimaLogalamDetail::tableName();
        $param['pk'] = $param['table'] . "." . \app\models\TTerimaLogalamDetail::primaryKey()[0];
        //t_loglist_detail : nomor_grd, nomor_produksi, nomor_batang, kayu_id, panjang, diameter_rata, cacat_panjang, cacat_gb, cacat_gr, volume_value
        //t_terima_logalam_detail : no_barcode, no_grade, no_lap, no_btg, kayu_id, panjang, kode_potong, diameter_rata, cacat_panjang, cacat_gb, cacat_gr, volume
        $param['column'] = [
            't_loglist_detail.nomor_grd',
            't_loglist_detail.nomor_produksi',
            't_loglist_detail.nomor_batang',
            'm_kayu.kayu_nama',
            't_loglist_detail.panjang',
            't_loglist_detail.diameter_rata',
            't_loglist_detail.cacat_panjang',
            't_loglist_detail.cacat_gb',
            't_loglist_detail.cacat_gr',
            't_loglist_detail.volume_value',
            't_terima_logalam_detail.no_barcode',
            't_terima_logalam_detail.no_grade',
            't_terima_logalam_detail.no_lap',
            't_terima_logalam_detail.no_btg',
            'm_kayu.kayu_nama',
            't_terima_logalam_detail.panjang',
            't_terima_logalam_detail.kode_potong',
            't_terima_logalam_detail.diameter_rata',
            't_terima_logalam_detail.cacat_panjang',
            't_terima_logalam_detail.cacat_gb',
            't_terima_logalam_detail.cacat_gr',
            't_terima_logalam_detail.volume',
        ];
        $param['join'] = ['JOIN t_loglist_detail ON t_loglist_detail.nomor_grd = t_terima_logalam_detail.no_grade
                            JOIN m_kayu on m_kayu.kayu_id = t_terima_logalam_detail.kayu_id
                            '];
        $param['order'] = ['t_loglist_detail.loglist_detail_id DESC'];
        return $param;
    }

    public function actionLaporanStokLog()
    {      
        $model = new HPersediaanLog();
        $model->tgl_transaksi = date('d/m/Y');
        $params['table']    = 'h_persediaan_log';
        $params['pk']       = 'h_persediaan_log.persediaan_log_id';
        $params['column']   = [
            "h_persediaan_log.persediaan_log_id",
            "m_kayu.kayu_nama",
            "h_persediaan_log.no_barcode",
            "h_persediaan_log.no_grade",
            "h_persediaan_log.no_lap",
            "h_persediaan_log.no_btg",
            "h_persediaan_log.fisik_pcs",
            "h_persediaan_log.fisik_panjang",
            "h_persediaan_log.pot",
            "h_persediaan_log.diameter_ujung1",
            "h_persediaan_log.diameter_ujung2",
            "h_persediaan_log.diameter_pangkal1",
            "h_persediaan_log.diameter_pangkal2",
            "h_persediaan_log.fisik_diameter", 
            "h_persediaan_log.cacat_panjang",
            "h_persediaan_log.cacat_gb",
            "h_persediaan_log.cacat_gr",
            "h_persediaan_log.fisik_volume",
            "(CASE WHEN fsc is true THEN 'FSC 100%' ELSE 'Non FSC' END) as fsc" // TAMBAH FSC
        ];
        $params['join']     = [
            'INNER JOIN m_kayu ON h_persediaan_log.kayu_id = m_kayu.kayu_id',
        ];
        
        $request['tgl_transaksi']   = isset($_GET['tgl_transaksi']) ? $_GET['tgl_transaksi'] : $model->tgl_transaksi;
        $request['kayu_id']         = isset($_GET['kayu_id']) ? $_GET['kayu_id'] : $model->kayu_id; 
        $request['fsc']             = isset($_GET['fsc']) ? $_GET['fsc'] : $model->fsc; // tambah fsc 

        if (!empty($request['tgl_transaksi'])) {
            $tanggal  = " AND ". "h_persediaan_log.tgl_transaksi <= '" . DeltaFormatter::formatDateTimeForDb($request['tgl_transaksi']) . "'";            
        }
        
        if (!empty($request['kayu_id'])) {
            $params['where'][]  = "h_persediaan_log.kayu_id = " . $request['kayu_id'];
            $kayu  = " AND "."A.kayu_id = " . $request['kayu_id'];
            $kayuID  = " AND "."h_persediaan_log.kayu_id = " . $request['kayu_id'];
        }else{
            $kayu = "";
            $kayuID = "";
        }

        if (!empty($request['fsc'])) { // tambah fsc
            $fsc  = " AND ". "h_persediaan_log.fsc = " . $request['fsc'];            
        } else {
            $fsc = '';
        }

        $params['where'][] = "h_persediaan_log.no_barcode <>'-' AND NOT EXISTS ( SELECT A.no_lap FROM h_persediaan_log AS A WHERE A.no_lap = h_persediaan_log.no_lap AND status = 'OUT' $tanggal $kayu) $fsc";       

        if (Yii::$app->request->isAjax) {
            $data       = SSP::complex($params);
            $query      = HPersediaanLog::find()->where(" h_persediaan_log.no_barcode <>'-' $kayuID AND NOT EXISTS ( SELECT A.no_lap FROM h_persediaan_log AS A WHERE A.no_lap = h_persediaan_log.no_lap AND status = 'OUT' $tanggal $kayu) $fsc");
            $total_pcs  = $query->sum('h_persediaan_log.fisik_pcs');
            $total_m3   = $query->sum('h_persediaan_log.fisik_volume');
            $data['total_pcs']  = $total_pcs;
            $data['total_m3']   = $total_m3;            
            return $this->asJson($data);
        }
        
        $caraprint = isset($_GET['caraprint']) ? $_GET['caraprint'] : false;
        // if($caraprint) {            
        //     $this->layout = '@views/layouts/metronic/print';
        //     $paramprint['judul']    = Yii::t('app', 'Laporan Stok Log');
        //     $paramprint['judul2']   = "Per Tanggal {$request['tgl_transaksi']}";
        //     $column = implode(', ', $params['column']);
        //     $table  = $params['table'];
        //     $join   = implode(' ', $params['join']);
        //     $where  = implode(' AND ', $params['where']);
        //     $query  = "SELECT $column FROM $table $join WHERE $where";
        //     $model  = Yii::$app->db->createCommand($query)->queryAll();
        //     if ($caraprint == 'PRINT') {   
        //         return $this->renderPartial('/laporan/laporanStokLog/print', ['model' => $model, 'paramprint' => $paramprint]);
        //     } else if ($caraprint == 'PDF') {
        //         $pdf = Yii::$app->pdf;
        //         $pdf->options   = ['title' => $paramprint['judul']];
        //         $pdf->filename  = $paramprint['judul'] . '.pdf';
        //         $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
        //         $pdf->content   = $this->renderPartial('/laporan/laporanStokLog/print', ['model' => $model, 'paramprint' => $paramprint]);
        //         return $pdf->render();
        //     } else if ($caraprint == 'EXCEL') {
        //         return $this->renderPartial('/laporan/laporanStokLog/print', ['model' => $model, 'paramprint' => $paramprint]);
        //     }
        // }
        return $this->render('/laporan/laporanStokLog/index', compact('model'));
    }

    public function actionLaporanStokLogPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = new \app\models\HPersediaanLog();
        $caraprint = Yii::$app->request->get('caraprint');
        $model->attributes = $_GET['HPersediaanLog'];  
        $model->tgl_transaksi = !empty($_GET['HPersediaanLog']['tgl_transaksi']) ? \app\components\DeltaFormatter::formatDateTimeForDb($_GET['HPersediaanLog']['tgl_transaksi']) : "";
        $model->kayu_id = !empty($_GET['HPersediaanLog']['kayu_id']) ? $_GET['HPersediaanLog']['kayu_id'] : "";
        $model->lokasi = !empty($_GET['HPersediaanLog']['lokasi']) ? $_GET['HPersediaanLog']['lokasi'] : "";
        $model->fsc = !empty($_GET['HPersediaanLog']['fsc']) ? $_GET['HPersediaanLog']['fsc'] : ""; // tambah fsc
        $paramprint['judul'] = Yii::t('app', 'Laporan Stok Log');
        $paramprint['judul2'] = "Per Tanggal " . \app\components\DeltaFormatter::formatDateTimeForUser2(!empty($model->tgl_transaksi) ? $model->tgl_transaksi : date('d/m/Y'));
        
        $params['table']    = 'h_persediaan_log';
        $params['pk']       = 'h_persediaan_log.persediaan_log_id';
        $params['column']   = [
            "h_persediaan_log.persediaan_log_id",
            "m_kayu.kayu_nama",
            "h_persediaan_log.no_barcode",
            "h_persediaan_log.no_grade",
            "h_persediaan_log.no_lap",
            "h_persediaan_log.no_btg",
            "h_persediaan_log.fisik_pcs",
            "h_persediaan_log.fisik_panjang",
            "h_persediaan_log.pot",
            "h_persediaan_log.diameter_ujung1",
            "h_persediaan_log.diameter_ujung2",
            "h_persediaan_log.diameter_pangkal1",
            "h_persediaan_log.diameter_pangkal2",
            "h_persediaan_log.fisik_diameter",
            "h_persediaan_log.cacat_panjang",
            "h_persediaan_log.cacat_gb",
            "h_persediaan_log.cacat_gr",
            "h_persediaan_log.fisik_volume",
            "h_persediaan_log.fsc" // TAMBAH FSC
        ];
        $params['join']     = [
            'INNER JOIN m_kayu ON h_persediaan_log.kayu_id = m_kayu.kayu_id',
        ];
        if (!empty($model->tgl_transaksi)) {
            $tanggal = " AND ". "h_persediaan_log.tgl_transaksi <= '" . $model->tgl_transaksi . "'"; 
        }
        if (!empty($model->kayu_id)) {
            $params['where'][]  = "h_persediaan_log.kayu_id = " . $model->kayu_id;
            $kayu  = " AND "."A.kayu_id = " . $model->kayu_id;
        }else{
            $kayu = "";
        }
        if (!empty($model->fsc)) { // tambah fsc
            $fsc  = " AND ". "h_persediaan_log.fsc = " . $model->fsc;            
        } else {
            $fsc = '';
        }

        $params['where'][] = "h_persediaan_log.no_barcode <>'-' AND NOT EXISTS ( SELECT A.no_lap FROM h_persediaan_log AS A WHERE A.no_lap = h_persediaan_log.no_lap AND status = 'OUT' $tanggal  $kayu) $fsc";       
        $column = implode(', ', $params['column']);
        $table  = $params['table'];
        $join   = implode(' ', $params['join']);
        $where  = implode(' AND ', $params['where']);
        $query  = "SELECT $column FROM $table $join WHERE $where";
        $model  = Yii::$app->db->createCommand($query)->queryAll();

        if ($caraprint == 'PRINT') {          
            return $this->renderPartial('/laporan/laporanStokLog/print', ['model' => $model, 'paramprint' => $paramprint]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->renderPartial('/laporan/laporanStokLog/print', ['model' => $model, 'paramprint' => $paramprint]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->renderPartial('/laporan/laporanStokLog/print', ['model' => $model, 'paramprint' => $paramprint]);
        }        
    }

    public function actionRekapStokLog()
    {
        $model = new \app\models\HPersediaanLog();

        if (Yii::$app->request->post('kayu_id')) {
            $kayu_id = Yii::$app->request->post('kayu_id');
            $model->kayu_id = $kayu_id;
        } else {
            $model->kayu_id = "";
        }

        return $this->render('/laporan/rekapStokLog/index', ['model' => $model]);
    }

    public function actionPenerimaanLogAlam()
    {
        $model = new \app\models\TTerimaLogalam();
        $modDetail = new \app\models\TTerimaLogalamDetail();
        $model->tgl_awal = date('d/m/Y', strtotime('-10 days'));
        $model->tgl_akhir = date('d/m/Y');
        if(Yii::$app->user->identity->pegawai->departement_id == 116){ // department MKT
            $peruntukan = 'Trading';
        } else {
            $peruntukan = 'Industri';
        }
        $model->peruntukan = $peruntukan;
        $modDetail->fsc = ''; // tambah fsc
        $model->lokasi_tujuan = '';

        if (\Yii::$app->request->get('dt') == 'table-laporan') {
            if ((\Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
                $model->tgl_awal = $form_params['TTerimaLogalam']['tgl_awal'];
                $model->tgl_akhir = $form_params['TTerimaLogalam']['tgl_akhir'];
                $model->peruntukan = $form_params['TTerimaLogalam']['peruntukan'];
                $model->no_dokumen = $form_params['TTerimaLogalam']['no_dokumen'];
                $modDetail->fsc = $form_params['TTerimaLogalamDetail']['fsc'];
                $model->lokasi_tujuan = $form_params['TTerimaLogalam']['lokasi_tujuan'];
                if (!empty($model->peruntukan)) {
                    $and_peruntukan = " and peruntukan = '" . $model->peruntukan . "'";
                } else {
                    $and_peruntukan = "";
                }
                if (!empty($model->no_dokumen)) {
                    $andNodokumen = " and no_dokumen ilike '%".$model->no_dokumen."%'";
                } else {
                    $andNodokumen = "";
                }
                if (!empty($modDetail->fsc)) { //tambah fsc
                    $andFSC = " and fsc = '" . $modDetail->fsc . "'";
                } else {
                    $andFSC = "";
                }
                if (!empty($model->lokasi_tujuan)) {
                    $and_lokasi_tujuan = " and lokasi_tujuan ilike '%" . $model->lokasi_tujuan . "%'";
                } else {
                    $and_lokasi_tujuan = "";
                }
                
            }
            $param['table'] = \app\models\TTerimaLogalamDetail::tableName();
            $param['pk'] = \app\models\TTerimaLogalamDetail::primaryKey()[0];
            $param['column'] = [
                't_terima_logalam.kode',
                't_terima_logalam.tanggal',
                't_terima_logalam.no_truk',
                't_terima_logalam.no_dokumen',
                'm_pegawai.pegawai_nama',
                'm_kayu.kayu_nama',
                't_terima_logalam.peruntukan',
                't_terima_logalam_detail.no_barcode',
                't_terima_logalam_detail.no_lap',
                't_terima_logalam_detail.no_grade',
                't_terima_logalam_detail.no_btg',
                't_terima_logalam_detail.no_produksi',
                't_terima_logalam_detail.panjang',
                't_terima_logalam_detail.kode_potong',
                't_terima_logalam_detail.diameter_ujung1',
                't_terima_logalam_detail.diameter_ujung2',
                't_terima_logalam_detail.diameter_pangkal1',
                't_terima_logalam_detail.diameter_pangkal2',
                't_terima_logalam_detail.diameter_rata',
                't_terima_logalam_detail.cacat_panjang',
                't_terima_logalam_detail.cacat_gb',
                't_terima_logalam_detail.cacat_gr',
                't_terima_logalam_detail.volume',
                't_terima_logalam_detail.fsc',
                't_terima_logalam.lokasi_tujuan',
            ];
            $param['join'] = ['JOIN t_terima_logalam ON t_terima_logalam.terima_logalam_id = ' . $param['table'] . '.terima_logalam_id
                                JOIN m_pegawai ON m_pegawai.pegawai_id = t_terima_logalam.pic_ukur
                                JOIN m_kayu ON m_kayu.kayu_id = ' . $param['table'] . '.kayu_id'];
            $param['where'] = ["tanggal between '" . $model->tgl_awal . "' and '" . $model->tgl_akhir . "' " . $and_peruntukan . " " . $andNodokumen . " ". $andFSC . " ". $and_lokasi_tujuan . " "];
            return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
        }

        //===
        /*
        select * from t_spk_shipping_tracking --spk_shipping_id=3
        select * from t_spk_shipping where spk_shipping_id = 3
        select * from t_pengajuan_pembelianlog where spk_shipping_id = 3 --pengajuan_pembelianlog=68
        select * from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = 68
        select * from t_loglist where pengajuan_pembelianlog_id = 68 --loglist_id=61
        select * from t_loglist_detail where loglist_id = 61
        */

        // if (\Yii::$app->request->get('dt') == 'table-laporan2') {
        //     if ((\Yii::$app->request->get('laporan_params2')) !== null) {
        //         $form_params = [];
        //         parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
        //         $model->tgl_awal = $form_params['TTerimaLogalam']['tgl_awal'];
        //         $model->tgl_akhir = $form_params['TTerimaLogalam']['tgl_akhir'];
        //     }
        //     $param['table'] = \app\models\TTerimaLogalamDetail::tableName();
        //     $param['pk'] = \app\models\TTerimaLogalamDetail::primaryKey()[0];
        //     $param['column'] = [
        //         't_terima_logalam.kode',
        //         't_terima_logalam.tanggal',
        //         't_terima_logalam.no_truk',
        //         't_terima_logalam.no_dokumen',
        //         'm_pegawai.pegawai_nama',
        //         'm_kayu.kayu_nama',
        //         't_terima_logalam_detail.no_barcode',
        //         't_terima_logalam_detail.no_grade',
        //         't_terima_logalam_detail.no_lap',
        //         't_terima_logalam_detail.no_btg',
        //         't_terima_logalam_detail.panjang',
        //         't_terima_logalam_detail.kode_potong',
        //         't_terima_logalam_detail.diameter_ujung1',
        //         't_terima_logalam_detail.diameter_pangkal1',
        //         't_terima_logalam_detail.diameter_ujung2',
        //         't_terima_logalam_detail.diameter_pangkal2',
        //         't_terima_logalam_detail.cacat_panjang',
        //         't_terima_logalam_detail.cacat_gb',
        //         't_terima_logalam_detail.cacat_gr',
        //         't_terima_logalam_detail.diameter_rata',
        //     ];
        //     $param['join'] = ['JOIN t_terima_logalam ON t_terima_logalam.terima_logalam_id = ' . $param['table'] . '.terima_logalam_id
        //                         JOIN m_pegawai ON m_pegawai.pegawai_id = t_terima_logalam.pic_ukur
        //                         JOIN m_kayu ON m_kayu.kayu_id = ' . $param['table'] . '.kayu_id'];
        //     $param['where'] = ["tanggal between '" . DeltaFormatter::formatDateTimeForDb($model->tgl_awal) . "' and '" . DeltaFormatter::formatDateTimeForDb($model->tgl_akhir) . "' "];
        //     return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
        // }
        return $this->render('/laporan/penerimaanLogAlam/index', ['model' => $model, 'modDetail' => $modDetail]);
    }

    public function actionPenerimaanLogAlamPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $model = new TTerimaLogalam();
        $modDetail = new TTerimaLogalamDetail();
        $caraprint = Yii::$app->request->get('caraprint');
        $model->tgl_awal = isset($_GET['TTerimaLogalam']['tgl_awal']) ? $_GET['TTerimaLogalam']['tgl_awal'] : '';
        $model->tgl_akhir = isset($_GET['TTerimaLogalam']['tgl_akhir']) ? $_GET['TTerimaLogalam']['tgl_akhir'] : '';
        $model->peruntukan = isset($_GET['TTerimaLogalam']['peruntukan']) ? $_GET['TTerimaLogalam']['peruntukan'] : '';
        $model->no_dokumen = isset($_GET['TTerimaLogalam']['no_dokumen']) ? $_GET['TTerimaLogalam']['no_dokumen'] : '';
        $modDetail->fsc = isset($_GET['TTerimaLogalamDetail']['fsc']) ? $_GET['TTerimaLogalamDetail']['fsc'] : '';
        $model->lokasi_tujuan = isset($_GET['TTerimaLogalam']['lokasi_tujuan']) ? $_GET['TTerimaLogalam']['lokasi_tujuan'] : '';

        // tambah judul
        $subjudul = '';
        if($model->peruntukan){
            $subjudul .= '<br>Peruntukan '.$model->peruntukan;
        }
        if($modDetail->fsc !== ''){
            $fsc = $modDetail->fsc == 'true'? 'FSC 100%' : 'Non FSC';
            $subjudul .= '<br>Status '. $fsc;
        }
        if($model->no_dokumen){
            $subjudul .= '<br>No. Dokumen ' . $model->no_dokumen;
        }
        if($model->lokasi_tujuan){
            $subjudul .= '<br>Customer '.$model->lokasi_tujuan;
        }
        
        $paramprint['judul'] = Yii::t('app', 'Laporan Penerimaan Log');
        $paramprint['judul2'] = "Periode " . DeltaFormatter::formatDateTimeForUser2($model->tgl_awal) . " s/d " . DeltaFormatter::formatDateTimeForUser2($model->tgl_akhir). $subjudul;
        if ($caraprint == 'PRINT') {
            return $this->renderPartial('/laporan/penerimaanLogAlam/print', ['model' => $model, 'paramprint' => $paramprint, 'modDetail'=>$modDetail]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->renderPartial('/laporan/penerimaanLogAlam/print', ['model' => $model, 'paramprint' => $paramprint, 'modDetail'=>$modDetail]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->renderPartial('/laporan/penerimaanLogAlam/print', ['model' => $model, 'paramprint' => $paramprint, 'modDetail'=>$modDetail]);
        }
    }

    public function actionPengeluaranLogAlam()
    {
        $model = new TLogKeluar();
        $model->tgl_awal = date('d/m/Y', strtotime('-10 days'));
        $model->tgl_akhir = date('d/m/Y');
        $model->cara_keluar = '';
        if (Yii::$app->request->get('dt') == 'table-laporan') {
            if ((Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(Yii::$app->request->get('laporan_params'), $form_params);
                $model->tgl_awal = $form_params['TLogKeluar']['tgl_awal'];
                $model->tgl_akhir = $form_params['TLogKeluar']['tgl_akhir'];
                $model->cara_keluar = $form_params['TLogKeluar']['cara_keluar'];
                $model->fsc = $form_params['TLogKeluar']['fsc'];
            }
            return Json::encode(SSP::complex($model->searchLaporanPersediaanDt()));
        }
        return $this->render('/laporan/pengeluaranLogAlam/index', ['model' => $model]);
    }

    public function actionPengeluaranLogAlamPrint()
    {
        $this->layout   = '@views/layouts/metronic/print';
        $request        = Yii::$app->request->get();
        $model          = new TLogKeluar();
        $model          = $model->searchLaporanPersediaan()
                        ->andWhere([
                            'between', 
                            'tgl_transaksi', 
                            DeltaFormatter::formatDateTimeForDb($request['TLogKeluar']['tgl_awal']), 
                            DeltaFormatter::formatDateTimeForDb($request['TLogKeluar']['tgl_akhir'])
                        ]);
        // if(!empty($request['TLogKeluar']['cara_keluar'])) {
        //     $model = $model->andWhere(['cara_keluar' => $request['TLogKeluar']['cara_keluar']]);
        // }

        if(!empty($request['TLogKeluar']['cara_keluar'])) {
            if($request['TLogKeluar']['cara_keluar'] === 'Industri') {
                $model->andWhere("lokasi ILIKE '%PRODUKSI%'");
            }else {
                $model->andWhere("lokasi ILIKE '%PENJUALAN%'");
            }
        }
        if(!empty($request['TLogKeluar']['fsc'])) {
            $model->andWhere("h_persediaan_log.fsc = ". $request['TLogKeluar']['fsc']);
        }
        $tgl_awal = !empty($_GET['TLogKeluar']['tgl_awal']) ? DeltaFormatter::formatDateTimeForUser2($_GET['TLogKeluar']['tgl_awal']) : "";
        $tgl_akhir = !empty($_GET['TLogKeluar']['tgl_akhir']) ? DeltaFormatter::formatDateTimeForUser2($_GET['TLogKeluar']['tgl_akhir']) : "";
        $query = $model->createCommand()->rawSql;
        $model = Yii::$app->db->createCommand($query)->queryAll();
        $paramprint['judul'] = Yii::t('app', 'Laporan Pengeluaran Log Alam');
        $paramprint['judul2'] = "Periode Tanggal " . DeltaFormatter::formatDateTimeForUser2($tgl_awal) . " sd " . DeltaFormatter::formatDateTimeForUser2($tgl_akhir) . " ";
        if ($request['caraprint'] === 'PRINT' || $request['caraprint'] === 'EXCEL') {
            return $this->renderPartial('/laporan/pengeluaranLogAlam/print', compact('model', 'paramprint'));
        } else {
            $user = Yii::$app->user->getIdentity()->userProfile->fullname;
            $time = date('d/m/Y H:i:s');
            $pdf = new Pdf([
                'mode' => PDF::MODE_CORE,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'content' => $this->renderPartial('/laporan/pengeluaranLogAlam/print', compact('model', 'paramprint')),
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'options' => ['title' => $paramprint['judul']],
                'methods' => [
                    'SetHeader' => ["{$paramprint['judul']} || Generate by {$user} At: {$time}"],
                    'SetFooter' => ['{PAGENO}'],
                ]
            ]);
            return $pdf->render();
        }
    }

    public function actionPenerimaanSengon()
    {
        $model = new \app\models\TTerimaSengonDetail();
        $model->tgl_awal = date('d/m/Y', strtotime('-10 days'));
        $model->tgl_akhir = date('d/m/Y');
        if (Yii::$app->request->get('dt') == 'table-laporan') {
            if ((Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(Yii::$app->request->get('laporan_params'), $form_params);
                $model->attributes = $form_params['TTerimaSengonDetail'];
                $model->tgl_awal = $form_params['TTerimaSengonDetail']['tgl_awal'];
                $model->tgl_akhir = $form_params['TTerimaSengonDetail']['tgl_akhir'];
                $model->suplier_id = $form_params['TTerimaSengonDetail']['suplier_id'];
            }
            return Json::encode(SSP::complex($model->searchLaporanDt('Sengon')));
        }
        return $this->render('/laporan/penerimaanSengon/index', ['model' => $model]);
    }

    public function actionPengeluaranSengon()
    {
        $model = new \app\models\HPersediaanLog();
        $model->tgl_awal = date('d/m/Y', strtotime('-10 days'));
        $model->tgl_akhir = date('d/m/Y');
        if (Yii::$app->request->get('dt') == 'table-laporan') {
            if ((Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(Yii::$app->request->get('laporan_params'), $form_params);
                $model->tgl_awal = $form_params['HPersediaanLog']['tgl_awal'];
                $model->tgl_akhir = $form_params['HPersediaanLog']['tgl_akhir'];
            }
            return Json::encode(SSP::complex($model->searchLaporanDtPengeluaran('Sengon')));
        }
        return $this->render('/laporan/pengeluaranSengon/index', ['model' => $model]);
    }

    public function actionPenerimaanJabon()
    {
        $model = new \app\models\TTerimaSengonDetail();
        $model->tgl_awal = date('d/m/Y', strtotime('-10 days'));
        $model->tgl_akhir = date('d/m/Y');
        if (Yii::$app->request->get('dt') == 'table-laporan') {
            if ((Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(Yii::$app->request->get('laporan_params'), $form_params);
                $model->attributes = $form_params['TTerimaSengonDetail'];
                $model->tgl_awal = $form_params['TTerimaSengonDetail']['tgl_awal'];
                $model->tgl_akhir = $form_params['TTerimaSengonDetail']['tgl_akhir'];
                $model->suplier_id = $form_params['TTerimaSengonDetail']['suplier_id'];
            }
            return Json::encode(SSP::complex($model->searchLaporanDt('Jabon')));
        }
        return $this->render('/laporan/penerimaanJabon/index', ['model' => $model]);
    }

    public function actionPengeluaranJabon()
    {
        $model = new \app\models\HPersediaanLog();
        $model->tgl_awal = date('d/m/Y', strtotime('-10 days'));
        $model->tgl_akhir = date('d/m/Y');
        if (Yii::$app->request->get('dt') == 'table-laporan') {
            if ((Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(Yii::$app->request->get('laporan_params'), $form_params);
                $model->tgl_awal = $form_params['HPersediaanLog']['tgl_awal'];
                $model->tgl_akhir = $form_params['HPersediaanLog']['tgl_akhir'];
            }
            return Json::encode(SSP::complex($model->searchLaporanDtPengeluaran('Jabon')));
        }
        return $this->render('/laporan/pengeluaranJabon/index', ['model' => $model]);
    }

    public function actionRejectPenerimaanKayuOlahan()
    {
        $model = new \app\models\TApproval();
        $model->tgl_awal = date('d/m/Y', strtotime('-10 days'));
        $model->tgl_akhir = date('d/m/Y');

        if (isset($_POST['TApproval']['tgl_awal']) && isset($_POST['TApproval']['tgl_akhir'])) {
            $tgl_awal = $_POST['TApproval']['tgl_awal'];
            $tgl_akhir = $_POST['TApproval']['tgl_akhir'];
        } else {
            $model->tgl_awal = date('d/m/Y', strtotime('-10 days'));
            $model->tgl_akhir = date('d/m/Y');
        }

        if (Yii::$app->request->get('dt') == 'table-laporan') {
            $tgl_awal = Yii::$app->request->get('tgl_awal');
            $tgl_akhir = Yii::$app->request->get('tgl_akhir');

            $param['table'] = \app\models\TApproval::tableName();
            $param['pk'] = "approval_id";
            $param['column'] = [
                't_approval.approval_id',
                't_approval.reff_no',
                't_approval.tanggal_berkas',
                't_approval.tanggal_approve',
                'm_brg_produk.produk_nama',
                'm_brg_produk.produk_kode',
                't_kirim_gudang_detail.reject_reason',
                't_kirim_gudang_detail.approve_reason',
                't_kirim_gudang_detail.kirim_gudang_detail_id'
            ];
            $param['join'] = "left join t_kirim_gudang_detail on concat('RPKO',t_kirim_gudang_detail.nomor_produksi) = t_approval.reff_no
                                left join m_brg_produk on m_brg_produk.produk_id = t_kirim_gudang_detail.produk_id
                                ";
            $param['where'] = [];
            array_push($param['where'], "t_approval.reff_no ilike 'RPKO%' and t_approval.status != 'Not Confirmed' ");

            if ((!empty($tgl_awal)) || (!empty($tgl_akhir))) {
                array_push($param['where'], "tanggal_berkas BETWEEN '" . $tgl_awal . "' AND '" . $tgl_akhir . "' ");
            } else {
                array_push($param['where'], "1=1 ");
            }

            /*if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
                if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )){
                    $param['where'] .= "AND assigned_to = ".Yii::$app->user->identity->pegawai_id." ";
                }
			}*/
            $param['order'] = "tanggal_berkas DESC, reff_no desc, level DESC";
            return Json::encode(SSP::complex($param));
        }

        return $this->render('/laporan/rejectPenerimaanKayuOlahan/index', ['model' => $model, 'status' => 'Not Confirmed']);
    }

    public function actionRejectPenerimaanKayuOlahanPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $tgl_awal = !empty($_GET['TApproval']['tgl_awal']) ? DeltaFormatter::formatDateTimeForDb($_GET['TApproval']['tgl_awal']) : "";
        $tgl_akhir = !empty($_GET['TApproval']['tgl_akhir']) ? DeltaFormatter::formatDateTimeForDb($_GET['TApproval']['tgl_akhir']) : "";
        $model = "select t_approval.approval_id AS approval_id, t_approval.reff_no AS reff_no, 
                    t_approval.tanggal_berkas AS tanggal_berkas, t_approval.tanggal_approve AS tanggal_approve, 
                    m_brg_produk.produk_nama AS produk_nama, m_brg_produk.produk_kode AS produk_kode, 
                    t_kirim_gudang_detail.reject_reason AS reject_reason, t_kirim_gudang_detail.approve_reason AS approve_reason 
                    from t_approval 
                    left join t_kirim_gudang_detail ON concat('RPKO',t_kirim_gudang_detail.nomor_produksi) = t_approval.reff_no 
                    left join m_brg_produk ON m_brg_produk.produk_id = t_kirim_gudang_detail.produk_id 
                    where (reff_no ILIKE 'RPKO%') 
                    and (tanggal_berkas between '" . $tgl_awal . "' AND '" . $tgl_akhir . "') ";
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = Yii::t('app', 'Laporan Reject Penerimaan Kayu Olahan');
        $paramprint['judul2'] = "Periode Tanggal " . DeltaFormatter::formatDateTimeForUser($tgl_awal) . " sd " . DeltaFormatter::formatDateTimeForUser($tgl_akhir) . " ";
        if ($caraprint == 'PRINT') {
            return $this->render('/laporan/rejectPenerimaanKayuOlahan/print', ['model' => $model, 'paramprint' => $paramprint]);
        } else if ($caraprint == 'PDF') {
            $pdf = Yii::$app->pdf;
            $pdf->options = ['title' => $paramprint['judul']];
            $pdf->filename = $paramprint['judul'] . '.pdf';
            $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
            $pdf->content = $this->render('/laporan/rejectPenerimaanKayuOlahan/print', ['model' => $model, 'paramprint' => $paramprint]);
            return $pdf->render();
        } else if ($caraprint == 'EXCEL') {
            return $this->render('/laporan/rejectPenerimaanKayuOlahan/print', ['model' => $model, 'paramprint' => $paramprint]);
        }
    }

    public function actionPengirimanblmTerimaGudang()
    {
        $model = new \app\models\TKirimGudang();
        $model->tgl_awal        = date('d/m/Y', strtotime('first day of this month'));
        $model->tgl_akhir       = date('d/m/Y');
        $dt = Yii::$app->request->get('dt');
        $lap_params = Yii::$app->request->get('laporan_params');
        if ($dt == 'table-informasi' && $lap_params !== NULL) {
            $form_params = [];
            parse_str($lap_params, $form_params);
            $model->attributes = $form_params['TKirimGudang'];
            $model->tgl_awal = $form_params['TKirimGudang']['tgl_awal'];
            $model->tgl_akhir = $form_params['TKirimGudang']['tgl_akhir'];
            $model->jenis_produk = $form_params['TKirimGudang']['jenis_produk'];

            return Json::encode(SSP::complex($model->searchLaporanDt()));
        }

        return $this->render('/laporan/pengirimanblmterimagudang/index', ['model' => $model]);
    }

    public function actionPengirimanblmTerimaGudangPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $caraprint = Yii::$app->request->get('caraprint');
        $req    = Yii::$app->request->get('TKirimGudang');
        $model  = new \app\models\TKirimGudang();
        $model->attributes  = $req;
        $model->tgl_awal    = !empty($req['tgl_awal']) ? DeltaFormatter::formatDateTimeForDb($req['tgl_awal']) : "";
        $model->tgl_akhir   = !empty($req['tgl_akhir']) ? DeltaFormatter::formatDateTimeForDb($req['tgl_akhir']) : "";
        $model->jenis_produk = !empty($req['jenis_produk']) ? $req['jenis_produk'] : "";
        $query = $model->searchLaporan()->createCommand()->rawSql;
        $model = Yii::$app->db->createCommand($query)->queryAll();
        $paramprint['judul'] = Yii::t('app', 'Pengiriman Belum Diterima Gudang');

        if ($caraprint === 'PRINT' || $caraprint === 'EXCEL') {
            return $this->renderPartial('/laporan/pengirimanblmterimagudang/print', compact('model', 'paramprint'));
        } else {
            $user = Yii::$app->user->getIdentity()->userProfile->fullname;
            $time = date('d/m/Y H:i:s');
            $pdf = new Pdf([
                'mode' => PDF::MODE_CORE,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'content' => $this->renderPartial('/laporan/pengirimanblmterimagudang/print', compact('model', 'paramprint')),
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'options' => ['title' => $paramprint['judul']],
                'methods' => [
                    'SetHeader' => ["{$paramprint['judul']} || Generate by {$user} At: {$time}"],
                    'SetFooter' => ['{PAGENO}'],
                ]
            ]);
            return $pdf->render();
        }
    }

    public function actionRekapPengirimanblmTerimaGudang()
    {
        $model = new \app\models\TKirimGudangDetail();
        if (Yii::$app->request->get('dt') == 'table-informasi') {
            $param['table'] = \app\models\TKirimGudangDetail::tableName();
            $param['pk'] = $param['table'] . "." . \app\models\TKirimGudangDetail::primaryKey()[0];
            $param['column'] = [
                'm_brg_produk.produk_group', 'count(m_brg_produk.produk_group) as jumlah'
            ];
            $param['join'] = ['JOIN t_kirim_gudang ON t_kirim_gudang.kirim_gudang_id = t_kirim_gudang_detail.kirim_gudang_id
                                   JOIN m_brg_produk ON m_brg_produk.produk_id = t_kirim_gudang_detail.produk_id
                                   JOIN t_hasil_produksi ON t_hasil_produksi.nomor_produksi = t_kirim_gudang_detail.nomor_produksi
                                   LEFT JOIN t_terima_ko ON t_terima_ko.nomor_produksi = t_kirim_gudang_detail.nomor_produksi
                                   '];
            $param['where'] = $param['table'] . ".cancel_transaksi_id IS NULL  AND t_terima_ko.petugas_penerima IS NULL";
            $param['group'] = "GROUP BY 1 ";
            $param['order'] = ['1 DESC'];
            return Json::encode(SSP::complex($param));
        }
        return $this->render('/laporan/pengirimanblmterimagudang/rekap', ['model' => $model]);
    }

    public function actionHasilRepackingblmTerimaGudang()
    {
        $model = new THasilRepacking();
        $model->tgl_awal        = date('d/m/Y', strtotime('first day of this month'));
        $model->tgl_akhir       = date('d/m/Y');
        $dt = Yii::$app->request->get('dt');
        $lap_params = Yii::$app->request->get('laporan_params');
        if ($dt == 'table-informasi' && $lap_params !== NULL) {
            $form_params = [];
            parse_str($lap_params, $form_params);
            $model->attributes = $form_params['THasilRepacking'];
            $model->tgl_awal = $form_params['THasilRepacking']['tgl_awal'];
            $model->tgl_akhir = $form_params['THasilRepacking']['tgl_akhir'];
            $model->jenis_produk = $form_params['THasilRepacking']['jenis_produk'];
            $model->hasil_dari_retur = $form_params['THasilRepacking']['hasil_dari_retur'];

            return Json::encode(SSP::complex($model->searchLaporanDt()));
        }

        return $this->render('/laporan/hasilrepackingblmterimagudang/index', ['model' => $model]);
    }

    public function actionHasilRepackingblmTerimaGudangPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $caraprint = Yii::$app->request->get('caraprint');
        $req    = Yii::$app->request->get('THasilRepacking');
        $model  = new THasilRepacking();
        $model->attributes  = $req;
        $model->tgl_awal    = !empty($req['tgl_awal']) ? DeltaFormatter::formatDateTimeForDb($req['tgl_awal']) : "";
        $model->tgl_akhir   = !empty($req['tgl_akhir']) ? DeltaFormatter::formatDateTimeForDb($req['tgl_akhir']) : "";
        $model->jenis_produk = !empty($req['jenis_produk']) ? $req['jenis_produk'] : "";
        $model->hasil_dari_retur = !empty($req['hasil_dari_retur']) ? $req['hasil_dari_retur'] : "";
        $query = $model->searchLaporan()->createCommand()->rawSql;
        $model = Yii::$app->db->createCommand($query)->queryAll();
        $paramprint['judul'] = Yii::t('app', 'Hasil Repacking & Penanganan Barang Retur Belum Diterima Gudang');

        if ($caraprint === 'PRINT' || $caraprint === 'EXCEL') {
            return $this->renderPartial('/laporan/hasilrepackingblmterimagudang/print', compact('model', 'paramprint'));
        } else {
            $user = Yii::$app->user->getIdentity()->userProfile->fullname;
            $time = date('d/m/Y H:i:s');
            $pdf = new Pdf([
                'mode' => PDF::MODE_CORE,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'content' => $this->renderPartial('/laporan/hasilrepackingblmterimagudang/print', compact('model', 'paramprint')),
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'options' => ['title' => $paramprint['judul']],
                'methods' => [
                    'SetHeader' => ["{$paramprint['judul']} || Generate by {$user} At: {$time}"],
                    'SetFooter' => ['{PAGENO}'],
                ]
            ]);
            return $pdf->render();
        }
    }

    public function actionRekapHasilRepackingblmTerimaGudang()
    {
        $model = new THasilRepacking();
        if (Yii::$app->request->get('dt') == 'table-informasi') {
            $param['table'] = THasilRepacking::tableName();
            $param['pk'] = $param['table'] . "." . THasilRepacking::primaryKey()[0];
            $param['column'] = [
                'm_brg_produk.produk_group', 'count(m_brg_produk.produk_group) as jumlah'
            ];
            $param['join'] = ['
                                   JOIN m_brg_produk ON m_brg_produk.produk_id = t_hasil_repacking.produk_id                                   
                                   LEFT JOIN t_terima_ko ON t_terima_ko.nomor_produksi = t_hasil_repacking.nomor_produksi
                                   '];
            $param['where'] = $param['table'] . ".cancel_transaksi_id IS NULL  AND t_terima_ko.petugas_penerima IS NULL";
            $param['group'] = "GROUP BY 1 ";
            $param['order'] = ['1 DESC'];
            return Json::encode(SSP::complex($param));
        }
        return $this->render('/laporan/hasilrepackingblmterimagudang/rekap', ['model' => $model]);
    }

    public function actionHasilRepackingAll()
    {
        $model                  = new THasilRepacking();
        $model->tgl_awal        = date('d/m/Y', strtotime('first day of this month'));
        $model->tgl_akhir       = date('d/m/Y');
        $dt                     = Yii::$app->request->get('dt');
        $lap_params             = Yii::$app->request->get('laporan_params');
        if ($dt === 'table-informasi' && $lap_params !== NULL) {
            $form_params            = [];
            parse_str($lap_params, $form_params);
            $model->attributes      = $form_params['THasilRepacking'];
            $model->tgl_awal        = $form_params['THasilRepacking']['tgl_awal'];
            $model->tgl_akhir       = $form_params['THasilRepacking']['tgl_akhir'];
            $model->jenis_produk    = $form_params['THasilRepacking']['jenis_produk'];
            $model->jenis_kayu      = $form_params['THasilRepacking']['jenis_kayu'];
            $model->grade           = $form_params['THasilRepacking']['grade'];
            $model->glue            = $form_params['THasilRepacking']['glue'];
            $model->profil_kayu     = $form_params['THasilRepacking']['profil_kayu'];
            $model->kondisi_kayu    = $form_params['THasilRepacking']['kondisi_kayu'];
            $model->hasil_dari_retur    = $form_params['THasilRepacking']['hasil_dari_retur'];

            return Json::encode(SSP::complex($model->searchLaporanDt(true)));
        }
        return $this->render('/laporan/hasilrepacking/index', compact('model'));
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function actionHasilRepackingAllPrint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $caraprint = Yii::$app->request->get('caraprint');
        $req    = Yii::$app->request->get('THasilRepacking');
        $model  = new THasilRepacking();
        $model->attributes      = $req;
        $model->tgl_awal        = !empty($req['tgl_awal']) ? DeltaFormatter::formatDateTimeForDb($req['tgl_awal']) : "";
        $model->tgl_akhir       = !empty($req['tgl_akhir']) ? DeltaFormatter::formatDateTimeForDb($req['tgl_akhir']) : "";
        $model->jenis_produk    = !empty($req['jenis_produk']) ? $req['jenis_produk'] : "";
        $model->jenis_kayu      = !empty($req['jenis_kayu']) ? $req['jenis_kayu'] : "";
        $model->grade           = !empty($req['grade']) ? $req['grade'] : "";
        $model->glue            = !empty($req['glue']) ? $req['glue'] : "";
        $model->profil_kayu     = !empty($req['profil_kayu']) ? $req['profil_kayu'] : "";
        $model->kondisi_kayu    = !empty($req['kondisi_kayu']) ? $req['kondisi_kayu'] : "";
        $model->hasil_dari_retur= !empty($req['hasil_dari_retur']) ? $req['hasil_dari_retur'] : "";

        $query = $model->searchLaporan(true)->createCommand()->rawSql;
        $model = Yii::$app->db->createCommand($query)->queryAll();
        $paramprint['judul'] = Yii::t('app', 'Pengiriman Hasil Repacking & Penanganan Barang Retur Ke Gudang');

        if ($caraprint === 'PRINT' || $caraprint === 'EXCEL') {
            return $this->renderPartial('/laporan/hasilrepacking/print', compact('model', 'paramprint'));
        }

        $user = Yii::$app->user->getIdentity()->userProfile->fullname;
        $time = date('d/m/Y H:i:s');
        $pdf = new Pdf([
            'mode' => PDF::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('/laporan/hasilrepacking/print', compact('model', 'paramprint')),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => $paramprint['judul']],
            'methods' => [
                'SetHeader' => ["{$paramprint['judul']} || Generate by {$user} At: {$time}"],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        return $pdf->render();
    }

    public function actionRekapmonitoring()
    {
        if (Yii::$app->request->isPost) {
            $param['table'] = 't_mtrg_in_out_detail';
            $param['pk'] = 'mtrg_in_out_detail_id';
            $param['column'] = [
                't_mtrg_in_out_detail.mtrg_in_out_detail_id',
                't_mtrg_in_out.tanggal_kupas',
                't_mtrg_in_out.tanggal_produksi',
                't_mtrg_in_out.kode',
                't_mtrg_in_out.shift',
                't_mtrg_in_out.status_in_out',
                't_mtrg_in_out.kategori_proses',
                't_mtrg_in_out.jenis_kayu',
                't_mtrg_in_out_detail.unit',
                't_mtrg_in_out_detail.grade',
                't_mtrg_in_out_detail.tebal',
                't_mtrg_in_out_detail.size',
                't_mtrg_in_out_detail.pcs',
                't_mtrg_in_out_detail.volume',
                't_mtrg_in_out_detail.patching'
            ];

            $param['join'] = ['
                INNER JOIN t_mtrg_in_out ON t_mtrg_in_out.mtrg_in_out_id = t_mtrg_in_out_detail.mtrg_in_out_id
            '];

            $param['where'] = [
                "t_mtrg_in_out.status_approval = '" . TApproval::STATUS_APPROVED . "' ",
                "(t_mtrg_in_out.tanggal_produksi BETWEEN '" . $_POST['startdate'] . "' AND '" . $_POST['enddate'] . "') ",
            ];


            if (!empty($_POST['kode'])) {
                $param['where'][] = "t_mtrg_in_out.kode ILIKE '%" . $_POST['kode'] . "%' ";
            }

            if (!empty($_POST['status_in_out'])) {
                $param['where'][] = "t_mtrg_in_out.status_in_out = '" . $_POST['status_in_out'] . "' ";
            }

            if (!empty($_POST['shift'])) {
                $param['where'][] = "t_mtrg_in_out.shift = '" . $_POST['shift'] . "' ";
            }

            if (!empty($_POST['kategori_proses'])) {
                $param['where'][] = "t_mtrg_in_out.kategori_proses = '" . $_POST['kategori_proses'] . "' ";
            }

            if (!empty($_POST['jenis_kayu'])) {
                $param['where'][] = "t_mtrg_in_out.jenis_kayu = '" . $_POST['jenis_kayu'] . "' ";
            }
            return Json::encode(SSP::complex($param));
        }

        return $this->render('/laporan/rekapmonitoring/index');
    }

    public function actionRekapmonitoringprint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $request = Yii::$app->request->get();
        $paramprint['judul'] = Yii::t('app', 'Rekap Monitoring');
        $sql = "    
            SELECT * 
            FROM 
                t_mtrg_in_out 
            INNER JOIN 
                t_mtrg_in_out_detail ON t_mtrg_in_out_detail.mtrg_in_out_id = t_mtrg_in_out.mtrg_in_out_id
            WHERE t_mtrg_in_out.status_approval = '". TApproval::STATUS_APPROVED ."'
                AND (t_mtrg_in_out.tanggal_produksi BETWEEN '".$request['startdate'] . "' AND '".$request['enddate'] . "') 
        ";

        if (!empty($request['kode'])) {
            $sql .= "AND t_mtrg_in_out.kode ILIKE '%" . $request['kode'] . "%' ";
        }

        if (!empty($request['status_in_out'])) {
            $sql .= "AND t_mtrg_in_out.status_in_out = '" . $request['status_in_out'] . "' ";
        }

        if (!empty($request['shift'])) {
            $sql .= "AND t_mtrg_in_out.shift = '" . $request['shift'] . "' ";
        }

        if (!empty($request['kategori_proses'])) {
            $sql .= "AND t_mtrg_in_out.kategori_proses = '" . $request['kategori_proses'] . "' ";
        }

        if (!empty($request['jenis_kayu'])) {
            $sql .= "AND t_mtrg_in_out.jenis_kayu = '" . $request['jenis_kayu'] . "' ";
        }

        $model = Yii::$app->db->createCommand($sql)->queryAll();
        if ($request['caraprint'] === 'PRINT' || $request['caraprint'] === 'EXCEL') {
            return $this->renderPartial('/laporan/rekapmonitoring/print', compact('paramprint', 'model'));
        }

        $user = Yii::$app->user->getIdentity()->userProfile->fullname;
        $time = date('d/m/Y H:i:s');
        $pdf = new Pdf([
            'mode' => PDF::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('/laporan/rekapmonitoring/print', compact('model', 'paramprint')),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => $paramprint['judul']],
            'methods' => [
                'SetHeader' => ["{$paramprint['judul']} || Generate by {$user} At: {$time}"],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        return $pdf->render();
    }

    public function actionRekapmonitoringrotary()
    {
        if (Yii::$app->request->isPost) {
            $param['table'] = 't_mtrg_rotary_detail';
            $param['pk'] = 'mtrg_rotary_detail_id';
            $param['column'] = [
                't_mtrg_rotary_detail.mtrg_rotary_detail_id',
                't_mtrg_rotary.tanggal',
                't_mtrg_rotary.kode',
                't_mtrg_rotary.shift',
                't_mtrg_rotary.jenis_kayu',
                't_mtrg_rotary.jam_jalan',
                'm_suplier.suplier_nm',
                't_mtrg_rotary_detail.unit',
                't_mtrg_rotary_detail.diameter',
                't_mtrg_rotary_detail.panjang',
                't_mtrg_rotary_detail.pcs',
                't_mtrg_rotary_detail.volume',
            ];

            $param['join'] = [
                'INNER JOIN t_mtrg_rotary ON t_mtrg_rotary.mtrg_rotary_id = t_mtrg_rotary_detail.mtrg_rotary_id', 
                "INNER JOIN m_suplier ON t_mtrg_rotary_detail.suplier_id = m_suplier.suplier_id"
            ];

            $param['where'] = [
                "t_mtrg_rotary.status_approval = '" . TApproval::STATUS_APPROVED . "' ",
                "(t_mtrg_rotary.tanggal BETWEEN '" . $_POST['startdate'] . "' AND '" . $_POST['enddate'] . "') ",
            ];


            if (!empty($_POST['kode'])) {
                $param['where'][] = "t_mtrg_rotary.kode ILIKE '%" . $_POST['kode'] . "%' ";
            }

            if (!empty($_POST['shift'])) {
                $param['where'][] = "t_mtrg_rotary.shift = '" . $_POST['shift'] . "' ";
            }

            if (!empty($_POST['jenis_kayu'])) {
                $param['where'][] = "t_mtrg_rotary.jenis_kayu = '" . $_POST['jenis_kayu'] . "' ";
            }

            return Json::encode(SSP::complex($param));
        }

        return $this->render('/laporan/rekapmonitoring/index');
    }

    public function actionRekapmonitoringrotaryprint()
    {
        $this->layout = '@views/layouts/metronic/print';
        $request = Yii::$app->request->get();
        $paramprint['judul'] = Yii::t('app', 'Rekap Monitoring Rotary');
        $sql = "    
            SELECT * 
            FROM 
                t_mtrg_rotary 
            INNER JOIN t_mtrg_rotary_detail ON t_mtrg_rotary_detail.mtrg_rotary_id = t_mtrg_rotary.mtrg_rotary_id
            INNER JOIN m_suplier ON t_mtrg_rotary_detail.suplier_id = m_suplier.suplier_id
            WHERE t_mtrg_rotary.status_approval = '" . TApproval::STATUS_APPROVED . "'
                AND (t_mtrg_rotary.tanggal BETWEEN '" . $request['startdate'] . "' AND '" . $request['enddate'] . "') 
        ";

        if (!empty($request['kode'])) {
            $sql .= "AND t_mtrg_rotary.kode ILIKE '%" . $request['kode'] . "%' ";
        }

        if (!empty($request['shift'])) {
            $sql .= "AND t_mtrg_rotary.shift = '" . $request['shift'] . "' ";
        }

        if (!empty($request['jenis_kayu'])) {
            $sql .= "AND t_mtrg_rotary.jenis_kayu = '" . $request['jenis_kayu'] . "' ";
        }

        $model = Yii::$app->db->createCommand($sql)->queryAll();
        if ($request['caraprint'] === 'PRINT' || $request['caraprint'] === 'EXCEL') {
            return $this->renderPartial('/laporan/rekapmonitoring/printrotary', compact('paramprint', 'model'));
        }

        $user = Yii::$app->user->getIdentity()->userProfile->fullname;
        $time = date('d/m/Y H:i:s');
        $pdf = new Pdf([
            'mode' => PDF::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('/laporan/rekapmonitoring/printrotary', compact('model', 'paramprint')),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => $paramprint['judul']],
            'methods' => [
                'SetHeader' => ["{$paramprint['judul']} || Generate by {$user} At: {$time}"],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        return $pdf->render();
    }

    public function actionTerimaLogPabrik()
    {
        $model = new TTerimaLogalamPabrik();
        $model->tgl_awal    = date('d/m/Y', time() - (10 * 24 * 60 * 60));
        $model->tgl_akhir   = date('d/m/Y');
        if (Yii::$app->request->isAjax) {
            $data       = SSP::complex($this->_queryTerimaLogPabrik($_GET));
            $query      = TTerimaLogalamPabrik::find()
                ->innerJoin('t_terima_logalam_detail', 't_terima_logalam_detail.terima_logalam_detail_id = t_terima_logalam_pabrik.terima_logalam_detail_id')
                ->where(implode(" AND ", $this->_queryTerimaLogPabrik($_GET)['where']));
            $total_m3   = $query->sum('t_terima_logalam_detail.volume');
            $data['total_m3']   = $total_m3;
            return $this->asJson($data);
        }
        return $this->render('/laporan/penerimaanLogPabrik/index', compact('model'));
    }

    public function actionTerimaLogPabrikPrint()
    {
        if (isset($_GET['caraprint'])) {
            $params = $this->_queryTerimaLogPabrik($_GET);
            $sql = 'SELECT ';
            foreach ($params['column'] as $key => $column) {
                $sql .= $key === count($params['column']) - 1 ? $column . ' ' : $column . ', ';
            }
            $sql .= ' FROM ' . $params['table'] . ' ';
            foreach ($params['join'] as $join) {
                $sql .= $join . ' ';
            }
            if (count($params['where'])) {
                $sql .= ' WHERE ';
                foreach ($params['where'] as $key => $clause) {
                    $sql .= $key === count($params['where']) - 1 ? $clause . ' ' : $clause . ' AND ';
                }
            }

            // if (isset($_GET['start']) && isset($_GET['length'])) {
            //     $sql .= ' OFFSET ' . $_GET['start'] . ' LIMIT ' . $_GET['length'];
            // }
            $data                   = Yii::$app->db->createCommand($sql)->queryAll();
            $paramprint['judul']    = Yii::t('app', 'laporan Penerimaan Log Alam Pabrik');
            $paramprint['judul2']   = "Periode " . DeltaFormatter::formatDateTimeForUser2($_GET['tgl_awal']) . " s/d " . DeltaFormatter::formatDateTimeForUser2($_GET['tgl_awal']);
            $this->layout           = '@views/layouts/metronic/print';

            if ($_GET['caraprint'] !== 'PDF') {
                return $this->renderPartial('/laporan/penerimaanLogPabrik/print', compact('paramprint', 'data'));
            } else {
                $pdf = Yii::$app->pdf;
                $pdf->options   = ['title' => $paramprint['judul']];
                $pdf->filename  = $paramprint['judul'] . '.pdf';
                $pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
                $pdf->content   = $this->renderPartial('/laporan/penerimaanLogPabrik/print', compact('paramprint', 'data'));
                return $pdf->render();
            }
        }
    }

    protected function _queryTerimaLogPabrik($request)
    {
        $params['table']    = 't_terima_logalam_pabrik';
        $params['pk']       = 't_terima_logalam_pabrik.terima_logalam_pabrik_id';
        $params['column']   = [
            't_terima_logalam_pabrik.terima_logalam_pabrik_id',
            't_terima_logalam_pabrik.tanggal',
            'm_kayu.kayu_nama',
            'm_pegawai.pegawai_nama',
            't_terima_logalam_pabrik.kode',
            't_terima_logalam_detail.no_grade',
            't_terima_logalam_detail.no_lap',
            't_terima_logalam_detail.no_btg',
            't_terima_logalam_detail.no_produksi',
            't_terima_logalam_detail.panjang',
            't_terima_logalam_detail.kode_potong',
            't_terima_logalam_detail.diameter_ujung1',
            't_terima_logalam_detail.diameter_ujung2',
            't_terima_logalam_detail.diameter_pangkal1',
            't_terima_logalam_detail.diameter_pangkal2',
            't_terima_logalam_detail.diameter_rata',
            't_terima_logalam_detail.cacat_panjang',
            't_terima_logalam_detail.cacat_gb',
            't_terima_logalam_detail.cacat_gr',
            't_terima_logalam_detail.volume',
            't_terima_logalam_detail.fsc',
        ];

        $params['join']     = [
            'INNER JOIN m_kayu ON t_terima_logalam_pabrik.kayu_id = m_kayu.kayu_id',
            'INNER JOIN m_pegawai ON t_terima_logalam_pabrik.pic_terima = m_pegawai.pegawai_id',
            'INNER JOIN t_terima_logalam_detail ON t_terima_logalam_pabrik.terima_logalam_detail_id = t_terima_logalam_detail.terima_logalam_detail_id'
        ];

        if (!empty($request['tgl_awal']) && !empty($request['tgl_akhir'])) {
            $tgl_awal   = DeltaFormatter::formatDateTimeForDb($request['tgl_awal']);
            $tgl_akhir  = DeltaFormatter::formatDateTimeForDb($request['tgl_akhir']);
            $params['where'][]  = "(t_terima_logalam_pabrik.tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir' )";
        }

        if (!empty($request['kayu_id'])) {
            $params['where'][]  = "t_terima_logalam_pabrik.kayu_id = " . $request['kayu_id'];
        }

        if (!empty($request['fsc'])) {
            $params['where'][]  = "t_terima_logalam_detail.fsc = " . $request['fsc'];
        }

        return $params;
    }

    public function actionRekapPemotongan()
    {
        $model = new \app\models\TPemotonganLog();
        $model->tgl_awal = date('d/m/Y', strtotime('first day of this month'));
        $model->tgl_akhir = date('d/m/Y');
        $model->peruntukan = '';
        $model->kayu_id = '';
        $model->alokasi = '';
        $model->nomor = '';
        $model->grading_rule = '';
        $model->panjang = '';

        if (\Yii::$app->request->get('dt') == 'table-laporan') {
            if ((\Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
                $model->tgl_awal = $form_params['TPemotonganLog']['tgl_awal'];
                $model->tgl_akhir = $form_params['TPemotonganLog']['tgl_akhir'];
                $model->peruntukan = $form_params['TPemotonganLog']['peruntukan'];
                $model->kayu_id = $form_params['TPemotonganLog']['kayu_id'];
                $model->alokasi = $form_params['TPemotonganLog']['alokasi'];
                $model->nomor = $form_params['TPemotonganLog']['nomor'];
                $model->grading_rule = $form_params['TPemotonganLog']['grading_rule'];
                $model->panjang = $form_params['TPemotonganLog']['panjang'];
                if (!empty($model->peruntukan)) {
                    $and_peruntukan = " and peruntukan = '" . $model->peruntukan . "'";
                } else {
                    $and_peruntukan = "";
                }
                if (!empty($model->kayu_id)) {
                    $and_kayu_id = " and t_pemotongan_log_detail.kayu_id = '" . $model->kayu_id . "'";
                } else {
                    $and_kayu_id = "";
                }
                if (!empty($model->alokasi)) {
                    $and_alokasi = " and alokasi = '" . $model->alokasi . "'";
                } else {
                    $and_alokasi = "";
                }
                if (!empty($model->nomor)) {
                    $and_nomor = " and nomor ilike '%" . $model->nomor . "%'";
                } else {
                    $and_nomor = "";
                }
                if (!empty($model->grading_rule)) {
                    $and_grading_rule = " and grading_rule = '" . $model->grading_rule . "'";
                } else {
                    $and_grading_rule = "";
                }
                if (!empty($model->panjang)) {
                    $and_panjang = " and t_pemotongan_log_detail_potong.panjang_baru = '" . $model->panjang . "'";
                } else {
                    $and_panjang = "";
                }
            }
            $param['table'] = \app\models\TPemotonganLog::tableName();
            $param['pk'] = 't_pemotongan_log.pemotongan_log_id';
            $param['column'] = [
                't_pemotongan_log.kode', //0
                't_pemotongan_log.peruntukan', //1
                't_pemotongan_log.nomor', //2
                't_pemotongan_log.tanggal', //3
                'm_pegawai.pegawai_nama', //4
                'm_kayu.kayu_nama', //5
                't_pemotongan_log_detail.no_barcode', //6
                't_pemotongan_log_detail.panjang', //7
                't_pemotongan_log_detail.diameter',//8
                't_pemotongan_log_detail.volume', //9
                't_pemotongan_log_detail.reduksi', //10
                't_pemotongan_log_detail.jumlah_potong', //11
                't_pemotongan_log_detail_potong.no_barcode_baru', //12
                't_pemotongan_log_detail_potong.panjang_baru', //13
                't_pemotongan_log_detail_potong.diameter_ujung1_baru', //14
                't_pemotongan_log_detail_potong.diameter_ujung2_baru', //15
                't_pemotongan_log_detail_potong.diameter_pangkal1_baru', //16
                't_pemotongan_log_detail_potong.diameter_pangkal2_baru',//17
                't_pemotongan_log_detail_potong.cacat_pjg_baru', //18
                't_pemotongan_log_detail_potong.cacat_gb_baru', //19
                't_pemotongan_log_detail_potong.cacat_gr_baru', //20
                't_pemotongan_log_detail_potong.reduksi_baru', //21
                't_pemotongan_log_detail_potong.volume_baru', //22
                't_pemotongan_log_detail_potong.alokasi', //23
                't_pemotongan_log_detail_potong.grading_rule', //24
                't_pemotongan_log_detail_potong.no_lap_baru', //25
                'h_persediaan_log.no_lap', //26
                't_pemotongan_log_detail.potong',
            ];
            $param['join'] = [' LEFT JOIN t_pemotongan_log_detail ON t_pemotongan_log.pemotongan_log_id = t_pemotongan_log_detail.pemotongan_log_id
                                LEFT JOIN t_pemotongan_log_detail_potong ON t_pemotongan_log_detail.pemotongan_log_detail_id = t_pemotongan_log_detail_potong.pemotongan_log_detail_id
                                LEFT JOIN m_pegawai ON m_pegawai.pegawai_id = t_pemotongan_log.petugas
                                LEFT JOIN m_kayu ON m_kayu.kayu_id = t_pemotongan_log_detail.kayu_id
                                JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama AND h_persediaan_log.reff_no = t_pemotongan_log.nomor
                             '];
            $param['where'] = ["tanggal between '" . $model->tgl_awal . "' and '" . $model->tgl_akhir . "' " . $and_peruntukan . " " . $and_kayu_id . " ". $and_alokasi 
                                . " ". $and_nomor . " ". $and_grading_rule . " ". $and_panjang . " "];
            return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
        }
        return $this->render('/laporan/rekapPemotongan/index', ['model' => $model]);
    }

    public function actionRekapPemotonganPrint(){
		$this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TPemotonganLog();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes      = $_GET['TPemotonganLog'];
		$model->tgl_awal        = !empty($_GET['TPemotonganLog']['tgl_awal'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TPemotonganLog']['tgl_awal']):"";
		$model->tgl_akhir       = !empty($_GET['TPemotonganLog']['tgl_akhir'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TPemotonganLog']['tgl_akhir']):"";
		$model->peruntukan      = !empty($_GET['TPemotonganLog']['peruntukan'])?$_GET['TPemotonganLog']['peruntukan']:"";
        $model->kayu_id         = !empty($_GET['TPemotonganLog']['kayu_id'])?$_GET['TPemotonganLog']['kayu_id']:"";
        $model->alokasi         = !empty($_GET['TPemotonganLog']['alokasi'])?$_GET['TPemotonganLog']['alokasi']:"";
        $model->nomor           = !empty($_GET['TPemotonganLog']['nomor'])?$_GET['TPemotonganLog']['nomor']:"";
        $model->grading_rule    = !empty($_GET['TPemotonganLog']['grading_rule'])?$_GET['TPemotonganLog']['grading_rule']:"";
        $model->panjang         = !empty($_GET['TPemotonganLog']['panjang'])?$_GET['TPemotonganLog']['panjang']:"";
		$paramprint['judul']    = Yii::t('app', 'Laporan Data Pemotongan Log');
		if($model->tgl_awal == $model->tgl_akhir){
			$paramprint['judul2'] = "Tanggal <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u>";
		}else{
			$paramprint['judul2'] = "Periode Tanggal <u>". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u> sd <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir)."</u>";
		}
		if($caraprint == 'PRINT'){
			return $this->render('/laporan/rekapPemotongan/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/rekapPemotongan/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/laporan/rekapPemotongan/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}

    public function actionLogkeluarbelumpotong()
    {
        $model = new TLogKeluar();
        $model->tgl_awal = date('d/m/Y', strtotime('-10 days'));
        $model->tgl_akhir = date('d/m/Y');
        if (Yii::$app->request->get('dt') == 'table-laporan') {
            if ((Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(Yii::$app->request->get('laporan_params'), $form_params);
                $model->tgl_awal = $form_params['TLogKeluar']['tgl_awal'];
                $model->tgl_akhir = $form_params['TLogKeluar']['tgl_akhir'];
                $model->fsc = $form_params['TLogKeluar']['fsc'];
            }
            return Json::encode(SSP::complex($model->searchLogKeluarBlmPotongDt()));
        }
        return $this->render('/laporan/logkeluarbelumpotong/index', ['model' => $model]);
    }

    public function actionLogkeluarbelumpotongPrint(){
		$this->layout   = '@views/layouts/metronic/print';
        $request        = Yii::$app->request->get();
        $model          = new TLogKeluar();
        $model          = $model->searchLogKeluarBlmPotong()
                        ->andWhere([
                            'between', 
                            't_log_keluar.tanggal', 
                            DeltaFormatter::formatDateTimeForDb($request['TLogKeluar']['tgl_awal']), 
                            DeltaFormatter::formatDateTimeForDb($request['TLogKeluar']['tgl_akhir'])
                        ]);
        if(!empty($request['TLogKeluar']['fsc'])) {
            $model->andWhere("h_persediaan_log.fsc = ". $request['TLogKeluar']['fsc']);
        }
        $tgl_awal = !empty($_GET['TLogKeluar']['tgl_awal']) ? DeltaFormatter::formatDateTimeForUser2($_GET['TLogKeluar']['tgl_awal']) : "";
        $tgl_akhir = !empty($_GET['TLogKeluar']['tgl_akhir']) ? DeltaFormatter::formatDateTimeForUser2($_GET['TLogKeluar']['tgl_akhir']) : "";
        $query = $model->createCommand()->rawSql;
        $model = Yii::$app->db->createCommand($query)->queryAll();
        $paramprint['judul'] = Yii::t('app', 'Laporan Pengeluaran Log Belum Potong');
        if($tgl_awal == $tgl_akhir){
			$paramprint['judul2'] = "Tanggal <u>".\app\components\DeltaFormatter::formatDateTimeForUser($tgl_awal)."</u>";
		}else{
			$paramprint['judul2'] = "Periode Tanggal <u>". \app\components\DeltaFormatter::formatDateTimeForUser($tgl_awal)."</u> sd <u>".\app\components\DeltaFormatter::formatDateTimeForUser($tgl_akhir)."</u>";
		}

        if($request['caraprint'] == 'PRINT'){
			return $this->render('/laporan/logkeluarbelumpotong/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($request['caraprint'] == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/logkeluarbelumpotong/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($request['caraprint'] == 'EXCEL'){
			return $this->render('/laporan/logkeluarbelumpotong/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}

    public function actionCetaklabelpotonglog()
    {
        $model = new \app\models\TPemotonganLog();
        $model->tgl_awal = date('d/m/Y', strtotime('first day of this month'));
        $model->tgl_akhir = date('d/m/Y');
        $model->peruntukan = '';
        $model->kayu_id = '';
        $model->no_lap = '';
        $model->panjang = '';

        if (\Yii::$app->request->get('dt') == 'table-laporan') {
            if ((\Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
                $model->tgl_awal = $form_params['TPemotonganLog']['tgl_awal'];
                $model->tgl_akhir = $form_params['TPemotonganLog']['tgl_akhir'];
                $model->peruntukan = $form_params['TPemotonganLog']['peruntukan'];
                $model->kayu_id = $form_params['TPemotonganLog']['kayu_id'];
                $model->no_lap = $form_params['TPemotonganLog']['no_lap'];
                $model->panjang = $form_params['TPemotonganLog']['panjang'];
                if (!empty($model->peruntukan)) {
                    $and_peruntukan = " and peruntukan = '" . $model->peruntukan . "'";
                } else {
                    $and_peruntukan = "";
                }
                if (!empty($model->kayu_id)) {
                    $and_kayu_id = " and t_pemotongan_log_detail.kayu_id = '" . $model->kayu_id . "'";
                } else {
                    $and_kayu_id = "";
                }
                if (!empty($model->no_lap)) {
                    $and_no_lap = " and no_lap_baru ilike '%" . $model->no_lap . "%'";
                } else {
                    $and_no_lap = "";
                }
                if (!empty($model->panjang)) {
                    $and_panjang = " and t_pemotongan_log_detail_potong.panjang_baru = '" . $model->panjang . "'";
                } else {
                    $and_panjang = "";
                }
            }
            $param['table'] = \app\models\TPemotonganLog::tableName();
            $param['pk'] = 't_pemotongan_log.pemotongan_log_id';
            $param['column'] = [
                't_pemotongan_log.kode', //0
                't_pemotongan_log.peruntukan', //1
                't_pemotongan_log.nomor', //2
                't_pemotongan_log.tanggal', //3
                'm_pegawai.pegawai_nama', //4
                'm_kayu.kayu_nama', //5
                't_pemotongan_log_detail.no_barcode', //6
                't_pemotongan_log_detail.panjang', //7
                't_pemotongan_log_detail.diameter',//8
                't_pemotongan_log_detail.volume', //9
                't_pemotongan_log_detail.reduksi', //10
                't_pemotongan_log_detail.jumlah_potong', //11
                't_pemotongan_log_detail_potong.no_barcode_baru', //12
                't_pemotongan_log_detail_potong.panjang_baru', //13
                't_pemotongan_log_detail_potong.diameter_ujung1_baru', //14
                't_pemotongan_log_detail_potong.diameter_ujung2_baru', //15
                't_pemotongan_log_detail_potong.diameter_pangkal1_baru', //16
                't_pemotongan_log_detail_potong.diameter_pangkal2_baru',//17
                't_pemotongan_log_detail_potong.cacat_pjg_baru', //18
                't_pemotongan_log_detail_potong.cacat_gb_baru', //19
                't_pemotongan_log_detail_potong.cacat_gr_baru', //20
                't_pemotongan_log_detail_potong.reduksi_baru', //21
                't_pemotongan_log_detail_potong.volume_baru', //22
                't_pemotongan_log_detail_potong.alokasi', //23
                't_pemotongan_log_detail_potong.grading_rule', //24
                't_pemotongan_log_detail_potong.no_lap_baru', //25
                'h_persediaan_log.no_lap', //26
                't_pemotongan_log_detail_potong.pemotongan_log_detail_potong_id'
            ];
            $param['join'] = [' LEFT JOIN t_pemotongan_log_detail ON t_pemotongan_log.pemotongan_log_id = t_pemotongan_log_detail.pemotongan_log_id
                                LEFT JOIN t_pemotongan_log_detail_potong ON t_pemotongan_log_detail.pemotongan_log_detail_id = t_pemotongan_log_detail_potong.pemotongan_log_detail_id
                                LEFT JOIN m_pegawai ON m_pegawai.pegawai_id = t_pemotongan_log.petugas
                                LEFT JOIN m_kayu ON m_kayu.kayu_id = t_pemotongan_log_detail.kayu_id
                                JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama AND h_persediaan_log.reff_no = t_pemotongan_log.nomor
                             '];
            $param['where'] = ["alokasi = 'Gudang' and tanggal between '" . $model->tgl_awal . "' and '" . $model->tgl_akhir . "' " . $and_peruntukan . " " . $and_kayu_id 
                                . " ". $and_no_lap . " ".  $and_panjang . " "];
            return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
        }
        return $this->render('/laporan/cetaklabelpotonglog/index', ['model' => $model]);
    }

    public function actionCetaklabelpotonglogPrint(){
        $this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TPemotonganLog();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes      = $_GET['TPemotonganLog'];
		$model->tgl_awal        = !empty($_GET['TPemotonganLog']['tgl_awal'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TPemotonganLog']['tgl_awal']):"";
		$model->tgl_akhir       = !empty($_GET['TPemotonganLog']['tgl_akhir'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TPemotonganLog']['tgl_akhir']):"";
		$model->peruntukan      = !empty($_GET['TPemotonganLog']['peruntukan'])?$_GET['TPemotonganLog']['peruntukan']:"";
        $model->kayu_id         = !empty($_GET['TPemotonganLog']['kayu_id'])?$_GET['TPemotonganLog']['kayu_id']:"";
        $model->no_lap           = !empty($_GET['TPemotonganLog']['no_lap'])?$_GET['TPemotonganLog']['no_lap']:"";
        $model->panjang         = !empty($_GET['TPemotonganLog']['panjang'])?$_GET['TPemotonganLog']['panjang']:"";
		$paramprint['judul']    = Yii::t('app', 'Cetak Label Pemotongan Log');
		if($model->tgl_awal == $model->tgl_akhir){
			$paramprint['judul2'] = "Tanggal <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u>";
		}else{
			$paramprint['judul2'] = "Periode Tanggal <u>". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u> sd <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir)."</u>";
		}
		if($caraprint == 'PRINT'){
			return $this->render('/laporan/cetaklabelpotonglog/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/cetaklabelpotonglog/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/laporan/cetaklabelpotonglog/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
    }

    public function actionRekappotonglog(){
        $model = new \app\models\TPemotonganLogDetailPotong();
        $model->kayu_id = '';
        $model->alokasi = '';
        $model->grading_rule = '';
        $model->panjang_baru = '';
        $model->tgl_awal = date('d/m/Y', strtotime('-1 week'));

        if (\Yii::$app->request->get('dt') == 'table-laporan') {
            if ((\Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
                $model->kayu_id = $form_params['TPemotonganLogDetailPotong']['kayu_id'];
                $model->alokasi = $form_params['TPemotonganLogDetailPotong']['alokasi'];
                $model->grading_rule = $form_params['TPemotonganLogDetailPotong']['grading_rule'];
                $model->panjang_baru = $form_params['TPemotonganLogDetailPotong']['panjang_baru'];
                $model->tgl_awal = $form_params['TPemotonganLogDetailPotong']['tgl_awal'];
                $model->tgl_akhir = $form_params['TPemotonganLogDetailPotong']['tgl_akhir'];
                $where = [];
                if (!empty($model->tgl_awal) && !empty($model->tgl_akhir)) {
                    $where[] = "t_pemotongan_log.tanggal between '".$model->tgl_awal."' and '".$model->tgl_akhir."'";
                }
                if (!empty($model->kayu_id)) {
                    $kayu_id = $model->kayu_id;
                    $where[] = "t_pemotongan_log_detail.kayu_id = ".$kayu_id;
                }
                if (!empty($model->alokasi)) {
                    $alokasi = $model->alokasi;
                    $where[] = "alokasi = '".$alokasi."'";
                }
                if (!empty($model->grading_rule)) {
                    $grading_rule = $model->grading_rule;
                    $where[] = "grading_rule = '".$grading_rule."'";
                }
                if (!empty($model->panjang_baru)) {
                    $panjang = $model->panjang_baru;
                    $where[] = "panjang_baru = ".$panjang;
                }
            }
            $param['table'] = \app\models\TPemotonganLogDetailPotong::tableName();
            $param['pk'] = \app\models\TPemotonganLogDetailPotong::primaryKey()[0];
            $param['column'] = [
                        'alokasi', 
                        'grading_rule', 
                        'm_kayu.kayu_nama', 
                        'panjang_baru', 
                        'sum(volume_baru) as vol'
            ];
            $param['join'] = [' JOIN t_pemotongan_log_detail ON t_pemotongan_log_detail.pemotongan_log_detail_id = t_pemotongan_log_detail_potong.pemotongan_log_detail_id
                                JOIN t_pemotongan_log ON t_pemotongan_log.pemotongan_log_id = t_pemotongan_log_detail.pemotongan_log_id
                                JOIN m_kayu ON m_kayu.kayu_id = t_pemotongan_log_detail.kayu_id
                             '];
            if (count($where) > 0) {
                $param['where'] = [implode(' AND ', $where)];
            };
            $param['group'] = "GROUP BY 1, 2, 3, 4";
            // $param['order'] = ['1, 2, 3'];
            return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
        }
        return $this->render('/laporan/rekappotonglog/index', ['model' => $model]);
    }

    public function actionRekappotonglogTotal(){
        $model = new \app\models\TPemotonganLogDetailPotong();
        $param = Yii::$app->request->get('TPemotonganLogDetailPotong');
        $model->attributes  	= $param;
        $model->kayu_id		    = $param['kayu_id'];
		$model->alokasi 		= $param['alokasi'];
		$model->grading_rule 	= $param['grading_rule'];
		$model->panjang_baru 	= $param['panjang_baru'];
        $model->tgl_awal 	    = $param['tgl_awal'];
        $model->tgl_akhir 	    = $param['tgl_akhir'];
        $sql = "SELECT alokasi, grading_rule, m_kayu.kayu_nama, panjang_baru, SUM(volume_baru) AS vol
                FROM t_pemotongan_log_detail_potong
                JOIN t_pemotongan_log_detail ON t_pemotongan_log_detail.pemotongan_log_detail_id = t_pemotongan_log_detail_potong.pemotongan_log_detail_id
                JOIN t_pemotongan_log ON t_pemotongan_log.pemotongan_log_id = t_pemotongan_log_detail.pemotongan_log_id
                JOIN m_kayu ON m_kayu.kayu_id = t_pemotongan_log_detail.kayu_id";
        $where = [];
        if (!empty($model->tgl_awal) && !empty($model->tgl_akhir)) {
            $where[] = "t_pemotongan_log.tanggal between '".$model->tgl_awal."' and '".$model->tgl_akhir."'";
        }
        if (!empty($model->kayu_id)) {
            $kayu_id = $model->kayu_id;
            $where[] = "t_pemotongan_log_detail.kayu_id = ".$kayu_id;
        }
        if (!empty($model->alokasi)) {
            $alokasi = $model->alokasi;
            $where[] = "alokasi = '".$alokasi."'";
        }
        if (!empty($model->grading_rule)) {
            $grading_rule = $model->grading_rule;
            $where[] = "grading_rule = '".$grading_rule."'";
        }
        if (!empty($model->panjang_baru)) {
            $panjang = $model->panjang_baru;
            $where[] = "panjang_baru = ".$panjang;
        }
        if (count($where) > 0) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $sql .= " GROUP BY alokasi, grading_rule, m_kayu.kayu_nama, panjang_baru ORDER BY alokasi, grading_rule, kayu_nama, panjang_baru";
        $datas = Yii::$app->db->createCommand($sql)->queryAll();
        $total  = 0;
        foreach($datas as $row) {
			$total += $row['vol'];
        }
        return \yii\helpers\Json::encode(['total' => $total]);
    }

    public function actionRekappotonglogPrint(){
        $this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TPemotonganLogDetailPotong();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes      = $_GET['TPemotonganLogDetailPotong'];
        $model->kayu_id         = !empty($_GET['TPemotonganLogDetailPotong']['kayu_id'])?$_GET['TPemotonganLogDetailPotong']['kayu_id']:"";
        $model->alokasi         = !empty($_GET['TPemotonganLogDetailPotong']['alokasi'])?$_GET['TPemotonganLogDetailPotong']['alokasi']:"";
        $model->grading_rule    = !empty($_GET['TPemotonganLogDetailPotong']['grading_rule'])?$_GET['TPemotonganLogDetailPotong']['grading_rule']:"";
        $model->panjang_baru    = !empty($_GET['TPemotonganLogDetailPotong']['panjang_baru'])?$_GET['TPemotonganLogDetailPotong']['panjang_baru']:"";
        $model->tgl_awal        = !empty($_GET['TPemotonganLogDetailPotong']['tgl_awal'])?$_GET['TPemotonganLogDetailPotong']['tgl_awal']:"";
        $model->tgl_akhir       = !empty($_GET['TPemotonganLogDetailPotong']['tgl_akhir'])?$_GET['TPemotonganLogDetailPotong']['tgl_akhir']:"";
		$paramprint['judul']    = Yii::t('app', 'Rekap Pemotongan Log');
        if($model->tgl_awal == $model->tgl_akhir){
			$paramprint['judul2'] = "Tanggal <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u>";
		}else{
			$paramprint['judul2'] = "Periode Tanggal <u>". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u> sd <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir)."</u>";
		}
		if($caraprint == 'PRINT'){
			return $this->render('/laporan/rekappotonglog/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/rekappotonglog/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/laporan/rekappotonglog/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
    }

    public function actionPlanalokasi(){
        $model = new \app\models\TPlanStoklog();
		if (\Yii::$app->request->get('dt') == 'table-laporan') {
			if ((\Yii::$app->request->get('laporan_params')) !== null) {
				$form_params = [];
				parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
				$model->attributes = $form_params['TPlanStoklog'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex($model->searchLaporanDt()));
		}
		return $this->render('/laporan/planalokasi/index', ['model' => $model]);
    }

    public function actionPlanalokasiPrint(){
        $this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TPlanStoklog();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes = $_GET['TPlanStoklog'];
        $paramprint['judul'] = Yii::t('app', 'Laporan Plan Alokasi Stok Log');
        $paramprint['judul2'] = '';
        if(!empty($model->jenis_alokasi)){
            $paramprint['judul2'] .= 'Alokasi ' . $model->jenis_alokasi . '<br>';
        }
        if(!empty($model->kayu_id)){
            $paramprint['judul2'] .= 'Jenis Kayu ' . $model->kayu->kayu_nama;
        }
        
		if ($caraprint == 'PRINT') {
			return $this->render('/laporan/planalokasi/print', ['model' => $model, 'paramprint' => $paramprint]);
		} else if ($caraprint == 'PDF') {
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'] . '.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: ' . Yii::$app->user->getIdentity()->userProfile->fullname . '||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/planalokasi/print', ['model' => $model, 'paramprint' => $paramprint]);
			return $pdf->render();
		} else if ($caraprint == 'EXCEL') {
			return $this->render('/laporan/planalokasi/print', ['model' => $model, 'paramprint' => $paramprint]);
		}
    }

    public function actionSpksawmill(){
        $model = new \app\models\TSpkSawmill();
		if (\Yii::$app->request->get('dt') == 'table-laporan') {
			if ((\Yii::$app->request->get('laporan_params')) !== null) {
				$form_params = [];
				parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
				$model->attributes = $form_params['TSpkSawmill'];
                $model->tgl_awal = $form_params['TSpkSawmill']['tgl_awal'];
                $model->tgl_akhir = $form_params['TSpkSawmill']['tgl_akhir'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex($model->searchLaporanDt()));
		}
		return $this->render('/laporan/spksawmill/index', ['model' => $model]);
    }

    public function actionSpksawmillPrint(){
        $this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TSpkSawmill();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes      = $_GET['TSpkSawmill'];
        $model->tgl_awal        = !empty($_GET['TSpkSawmill']['tgl_awal'])?$_GET['TSpkSawmill']['tgl_awal']:"";
        $model->tgl_akhir       = !empty($_GET['TSpkSawmill']['tgl_akhir'])?$_GET['TSpkSawmill']['tgl_akhir']:"";
		$paramprint['judul']    = Yii::t('app', 'Laporan SPK Sawmill');
        if($model->tgl_awal == $model->tgl_akhir){
			$paramprint['judul2'] = "Tanggal <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u>";
		}else{
			$paramprint['judul2'] = "Periode Tanggal <u>". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u> sd <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir)."</u>";
		}
		if($caraprint == 'PRINT'){
			return $this->render('/laporan/spksawmill/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/spksawmill/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/laporan/spksawmill/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
    }

    public function actionBrakedown(){
        $model = new \app\models\TBrakedown();
		if (\Yii::$app->request->get('dt') == 'table-laporan') {
			if ((\Yii::$app->request->get('laporan_params')) !== null) {
				$form_params = [];
				parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
				$model->attributes = $form_params['TBrakedown'];
                $model->tgl_awal = $form_params['TBrakedown']['tgl_awal'];
                $model->tgl_akhir = $form_params['TBrakedown']['tgl_akhir'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex($model->searchLaporanDt()));
		}
		return $this->render('/laporan/brakedown/index', ['model' => $model]);
    }

    public function actionBrakedownPrint(){
        $this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TBrakedown();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes      = $_GET['TBrakedown'];
        $model->tgl_awal        = !empty($_GET['TBrakedown']['tgl_awal'])?$_GET['TBrakedown']['tgl_awal']:"";
        $model->tgl_akhir       = !empty($_GET['TBrakedown']['tgl_akhir'])?$_GET['TBrakedown']['tgl_akhir']:"";
		$paramprint['judul']    = Yii::t('app', 'Laporan Input Brakedown');
        if($model->tgl_awal == $model->tgl_akhir){
			$paramprint['judul2'] = "Tanggal <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u>";
		}else{
			$paramprint['judul2'] = "Periode Tanggal <u>". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u> sd <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir)."</u>";
		}
		if($caraprint == 'PRINT'){
			return $this->render('/laporan/brakedown/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/brakedown/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/laporan/brakedown/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
    }

    public function actionBandsaw(){
        $model = new \app\models\TBandsaw();
		if (\Yii::$app->request->get('dt') == 'table-laporan') {
			if ((\Yii::$app->request->get('laporan_params')) !== null) {
				$form_params = [];
				parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
				$model->attributes  = $form_params['TBandsaw'];
                $model->tgl_awal    = $form_params['TBandsaw']['tgl_awal'];
                $model->tgl_akhir   = $form_params['TBandsaw']['tgl_akhir'];
                $model->kayu_id     = $form_params['TBandsaw']['kayu_id'];
                $model->nomor_bandsaw = $form_params['TBandsaw']['nomor_bandsaw'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex($model->searchLaporanDt()));
		}
		return $this->render('/laporan/bandsaw/index', ['model' => $model]);
    }

    public function actionBandsawPrint(){
        $this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TBandsaw();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes      = $_GET['TBandsaw'];
        $model->tgl_awal        = !empty($_GET['TBandsaw']['tgl_awal'])?$_GET['TBandsaw']['tgl_awal']:"";
        $model->tgl_akhir       = !empty($_GET['TBandsaw']['tgl_akhir'])?$_GET['TBandsaw']['tgl_akhir']:"";
        $model->kayu_id         = !empty($_GET['TBandsaw']['kayu_id'])?$_GET['TBandsaw']['kayu_id']:"";
        $model->nomor_bandsaw   = !empty($_GET['TBandsaw']['nomor_bandsaw'])?$_GET['TBandsaw']['nomor_bandsaw']:"";
		$paramprint['judul']    = Yii::t('app', 'Laporan Output Bandsaw');
        if($model->tgl_awal == $model->tgl_akhir){
			$paramprint['judul2'] = "Tanggal <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u>";
		}else{
			$paramprint['judul2'] = "Periode Tanggal <u>". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u> sd <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir)."</u>";
		}
		if($caraprint == 'PRINT'){
			return $this->render('/laporan/bandsaw/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/bandsaw/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/laporan/bandsaw/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
    }

    public function actionRekapBandsaw(){
        $model = new \app\models\TBandsaw();
		if (\Yii::$app->request->get('dt') == 'table-laporan') {
			if ((\Yii::$app->request->get('laporan_params')) !== null) {
				$form_params = [];
				parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
				$model->attributes = $form_params['TBandsaw'];
                $model->nomor_bandsaw = $form_params['TBandsaw']['nomor_bandsaw'];
                $model->tgl_awal = $form_params['TBandsaw']['tgl_awal'];
                $model->tgl_akhir = $form_params['TBandsaw']['tgl_akhir'];
                $model->kayu_id = $form_params['TBandsaw']['kayu_id'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex($model->searchLaporanRekapDt()));
		}
		return $this->render('/laporan/rekapBandsaw/index', ['model' => $model]);
    }

    public function actionRekapBandsawPrint(){
        $this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TBandsaw();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes      = $_GET['TBandsaw'];
        $model->nomor_bandsaw   = !empty($_GET['TBandsaw']['nomor_bandsaw'])?$_GET['TBandsaw']['nomor_bandsaw']:"";
        $model->tgl_awal        = !empty($_GET['TBandsaw']['tgl_awal'])?$_GET['TBandsaw']['tgl_awal']:"";
        $model->tgl_akhir       = !empty($_GET['TBandsaw']['tgl_akhir'])?$_GET['TBandsaw']['tgl_akhir']:"";
        $model->kayu_id         = !empty($_GET['TBandsaw']['kayu_id'])?$_GET['TBandsaw']['kayu_id']:"";
		$paramprint['judul']    = Yii::t('app', 'Rekap Output Bandsaw');
		if($caraprint == 'PRINT'){
			return $this->render('/laporan/rekapBandsaw/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/rekapBandsaw/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/laporan/rekapBandsaw/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
    }

    public function actionRekapinout(){
        $model = new \app\models\TSpkSawmill();
        if (\Yii::$app->request->get('dt') == 'table-laporan') {
            if ((\Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
                $model->tgl_awal    = $form_params['TSpkSawmill']['tgl_awal'];
                $model->tgl_akhir   = $form_params['TSpkSawmill']['tgl_akhir'];
                $model->kode        = $form_params['TSpkSawmill']['kode'];
                $where = '';
                if(!empty($model->kode)){
                    if (is_array($model->kode)) {
                        if (isset($model->kode)) {
                            $subq=null;
                            $cn=1;
                            $subq.='AND (';
                            foreach ($model->kode as $k) {
                                $subq.=" t_spk_sawmill.kode = '".$k."' ";
                                if ($cn < count($model->kode)) {
                                    $subq.=' OR ';
                                }
                                $cn++;
                            }
                            $subq.=')';
                            if (!empty($subq)) {
                                $where = $subq;
                            }
                        }
                    }else{
                        $where = "AND t_spk_sawmill.kode = '".$model->kode."'";
                    }            
                }
            }
            $param['table'] = \app\models\TSpkSawmill::tableName();
            $param['pk'] = \app\models\TSpkSawmill::primaryKey()[0];
            $param['column'] = [
                        't_spk_sawmill.kode',
                        'SUM(bd.vol_brakedown) AS vol_brakedown',
                        'SUM(bs.vol_bandsaw) AS vol_bandsaw'
                        ];
            $param['join'] = [" JOIN (SELECT t_spk_sawmill.kode, SUM(t_brakedown_detail.volume_baru) AS vol_brakedown FROM t_spk_sawmill
                                    JOIN t_brakedown ON t_brakedown.spk_sawmill_id = t_spk_sawmill.spk_sawmill_id
                                    JOIN t_brakedown_detail ON t_brakedown_detail.brakedown_id = t_brakedown.brakedown_id
                                    WHERE t_brakedown.tanggal BETWEEN '".$model->tgl_awal."' AND '".$model->tgl_akhir."'
                                    GROUP BY t_spk_sawmill.kode) bd ON bd.kode = t_spk_sawmill.kode
                                JOIN (SELECT t_spk_sawmill.kode, SUM(total_volume_m3) AS vol_bandsaw FROM t_spk_sawmill
		                            JOIN view_vol_output_bandsaw ON view_vol_output_bandsaw.spk_sawmill_id = t_spk_sawmill.spk_sawmill_id
                                    WHERE view_vol_output_bandsaw.tanggal BETWEEN '".$model->tgl_awal."' AND '".$model->tgl_akhir."'
		                            GROUP BY t_spk_sawmill.kode) bs ON bs.kode = t_spk_sawmill.kode
                            "];
            $param['where'] = "t_spk_sawmill.cancel_transaksi_id is null " .$where;
            $param['group'] = "GROUP BY t_spk_sawmill.kode";
            // $param['order'] = ['1, 2, 3'];
            return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
        }
        return $this->render('/laporan/rekapinout/index', ['model' => $model]);
    }

    public function actionRekapinoutPrint(){
        $this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TSpkSawmill();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes      = $_GET['TSpkSawmill'];
        $model->kode            = !empty($_GET['TSpkSawmill']['kode'])?$_GET['TSpkSawmill']['kode']:"";
        $model->tgl_awal        = !empty($_GET['TSpkSawmill']['tgl_awal'])?$_GET['TSpkSawmill']['tgl_awal']:"";
        $model->tgl_akhir       = !empty($_GET['TSpkSawmill']['tgl_akhir'])?$_GET['TSpkSawmill']['tgl_akhir']:"";
		$paramprint['judul']    = Yii::t('app', 'Rekap Input Brakedown vs Outpun Bandsaw');
        if($model->tgl_awal == $model->tgl_akhir){
			$paramprint['judul2'] = "Tanggal <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u>";
		}else{
			$paramprint['judul2'] = "Periode Tanggal <u>". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u> sd <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir)."</u>";
		}
		if($caraprint == 'PRINT'){
			return $this->render('/laporan/rekapinout/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/rekapinout/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/laporan/rekapinout/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
    }

    public function actionPemenuhanSpk(){
        $model = new \app\models\TSpkSawmill();
		if (\Yii::$app->request->get('dt') == 'table-laporan') {
			if ((\Yii::$app->request->get('laporan_params')) !== null) {
				$form_params = [];
				parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
				$model->attributes  = $form_params['TSpkSawmill'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex($model->searchLaporanMonitoringDt()));
		}
		return $this->render('/laporan/pemenuhanSpk/index', ['model' => $model]);
    }

    public function actionPemenuhanSpkPrint(){
        $this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TSpkSawmill();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes      = $_GET['TSpkSawmill'];
		$paramprint['judul']    = Yii::t('app', 'Monitoring Pemenuhan SPK Sawmill');
		if($caraprint == 'PRINT'){
			return $this->render('/laporan/pemenuhanSpk/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/pemenuhanSpk/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/laporan/pemenuhanSpk/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
    }

    public function actionPerubahanjenis(){
        $model = new \app\models\TLogRubahjenis();
        if (\Yii::$app->request->get('dt') == 'table-laporan') {
            if ((\Yii::$app->request->get('laporan_params')) !== null) {
                $form_params = [];
                parse_str(\Yii::$app->request->get('laporan_params'), $form_params);
                $model->tgl_awal        = $form_params['TLogRubahjenis']['tgl_awal'];
                $model->tgl_akhir       = $form_params['TLogRubahjenis']['tgl_akhir'];
                // $model->no_barcode      = $form_params['TLogRubahjenis']['no_barcode'];
                $model->peruntukan      = $form_params['TLogRubahjenis']['peruntukan'];
                $model->status_approve  = $form_params['TLogRubahjenis']['status_approve'];
                $model->label_no        = $form_params['TLogRubahjenis']['label_no'];
                $model->keyword         = $form_params['TLogRubahjenis']['keyword'];
            }
            $param['table'] = \app\models\TLogRubahjenis::tableName();
            $param['pk'] = \app\models\TLogRubahjenis::primaryKey()[0];
            $param['column'] = [
                            "log_rubahjenis_id", 
                            "kode", 
                            "tanggal", 
                            "peruntukan", 
                            "d->>'no_barcode' AS no_barcode", 
                            "d->>'no_lap' AS no_lap", 
                            "a.kayu_nama as kayu_old", 
                            "b.kayu_nama as kayu_new",
                            "keterangan", 
                            "status_approve"
                        ];
            $param['join'] = [" JOIN LATERAL jsonb_array_elements(t_log_rubahjenis.datadetail::jsonb) d ON true
                                LEFT JOIN m_kayu a ON a.kayu_id = (d->>'kayu_id_old')::int
                                LEFT JOIN m_kayu b ON b.kayu_id = (d->>'kayu_id_new')::int
                            "];
            $param['where'] = "cancel_transaksi_id is null";
            if(!empty($model->tgl_awal) && !empty($model->tgl_akhir)){
                $param['where'] .= " AND tanggal BETWEEN '".$model->tgl_awal."' AND '".$model->tgl_akhir."'";
            }
            if(!empty($model->peruntukan)){
                $param['where'] .= " AND peruntukan = '".$model->peruntukan."'";
            }
            // if(!empty($model->no_barcode)){
            //     $param['where'] .= " AND d->>'no_barcode' ILIKE '%".$model->no_barcode."%'";
            // }
            if(!empty($model->status_approve)){
                $param['where'] .= " AND status_approve = '".$model->status_approve."'";
            }
            if(!empty($model->keyword)){
                if($model->label_no){
                    $param['where'] .= " AND d->>'".$model->label_no."' ILIKE '%".$model->keyword."%'";
                }
            }
            return \yii\helpers\Json::encode(\app\components\SSP::complex($param));
        }
        return $this->render('/laporan/perubahanjenis/index', ['model' => $model]);
    }

    public function actionPerubahanjenisPrint(){
        $this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TLogRubahjenis();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes      = $_GET['TLogRubahjenis'];
        $model->tgl_awal        = !empty($_GET['TLogRubahjenis']['tgl_awal'])?$_GET['TLogRubahjenis']['tgl_awal']:"";
        $model->tgl_akhir       = !empty($_GET['TLogRubahjenis']['tgl_akhir'])?$_GET['TLogRubahjenis']['tgl_akhir']:"";
        // $model->no_barcode      = !empty($_GET['TLogRubahjenis']['no_barcode'])?$_GET['TLogRubahjenis']['no_barcode']:"";
        $model->status_approve  = !empty($_GET['TLogRubahjenis']['status_approve'])?$_GET['TLogRubahjenis']['status_approve']:"";
        $model->label_no        = !empty($_GET['TLogRubahjenis']['label_no'])?$_GET['TLogRubahjenis']['label_no']:"";
        $model->keyword         = !empty($_GET['TLogRubahjenis']['keyword'])?$_GET['TLogRubahjenis']['keyword']:"";
		$paramprint['judul']    = Yii::t('app', 'Laporan Pengajuan Perubahan Jenis Kayu');
        if($model->tgl_awal == $model->tgl_akhir){
			$paramprint['judul2'] = "Tanggal <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u>";
		}else{
			$paramprint['judul2'] = "Periode Tanggal <u>". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)."</u> sd <u>".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir)."</u>";
		}
		if($caraprint == 'PRINT'){
			return $this->render('/laporan/perubahanjenis/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/laporan/perubahanjenis/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/laporan/perubahanjenis/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
    }
}
