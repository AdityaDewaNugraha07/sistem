<?php 
use yii\helpers\Url;
/*
pastikan div id modal adalah id yang dipanggil di index.php
*/
?>
<div class="modal fade" id="modal-notif" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Approval'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- <h class="text-danger" style="font-size:1.1rem; font-style: italic;">Jumlah(Data) notifikasi yang ditampilkan(dihitung) terhitung mulai 01/01/2020</h> -->
                    <?php // KONTEN MODAL ?>
                    <div id="yyy" class="portlet-body" id="ajax" style="padding-left: -15px; padding-right: -15px;">
                        <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-hover" id="table-list" style="width: 500px;">
                                <thead style="background-color: #F5FCC9">
                                    <tr>
                                        <!-- <th style="text-align: center; width: 30px;"><?//= Yii::t('app', 'No.'); ?></th> -->
                                        <th style="text-align: center; width: 200px; line-height: 1;"><?= Yii::t('app', 'Permintaan Approval'); ?></th>
                                        <th style="text-align: center; width: 50px; line-height: 1;"><?= Yii::t('app', 'Qty'); ?></th>
                                        <th style="text-align: center; width: 50px; line-height: 1;"><?= Yii::t('app', 'View'); ?></th>

                                    </tr>
                                </thead>   
                                <tbody>                 
                                <?php
                                $user_id = $_SESSION['__id'];
                                $m_pegawai = \app\models\MUser::find()->where(['user_id'=>$user_id])->one();
                                $pegawai_id = $m_pegawai->pegawai_id;
                                $t_approval = \app\models\TApproval::find()
                                                    ->where(['assigned_to'=>$pegawai_id])
                                                    ->andWhere(['status'=>'Not Confirmed'])
                                                    ->andWhere(['>=', 'tanggal_berkas', '2020-01-01'])
                                                    ->orderBy('tanggal_berkas DESC')
                                                    ->all();
                                $jumlah_t_approval = count($t_approval);

                                $sql_t_approval = "select left(reff_no,3) as kode,parameter1 from t_approval ".
                                                    "   where assigned_to = ".$pegawai_id." ".
                                                    "   and status = 'Not Confirmed' ".
                                                    "   and tanggal_berkas >= '2020-01-01' ".
                                                    "   group by left(reff_no,3),parameter1 ".
                                                    "   ";
                                $query_t_approval = Yii::$app->db->createCommand($sql_t_approval)->queryAll();
                                $total = 0;
                                $total2 = 0;
                                $total3 = 0;
                                // notifikasi approval
                                if ($jumlah_t_approval > 0) {
                                    $i = 1;
                                    $total = 0;
                                    foreach ($query_t_approval as $kolom) {
                                        $kode = $kolom['kode'];
                                        $parameter1 = $kolom['parameter1'];
                                        if($parameter1<>''){
                                            $andParameter1 = "and parameter1 = '".$parameter1."'";
                                        }else{
                                            $andParameter1 = "";
                                        }
                                        $sql_jumlah = "select count(*) from t_approval ".
                                                        "   where left(reff_no,3) = '".$kode."' ". 
                                                        "   $andParameter1 ".
                                                        "   and assigned_to = ".$pegawai_id." ".
                                                        "   and status = 'Not Confirmed' ".
                                                        "   and tanggal_berkas >= '2020-01-01' ".                                                    " ".
                                                        "   ";
                                        $jumlah = Yii::$app->db->createCommand($sql_jumlah)->queryScalar();

                                        /*$approval_id = $kolom['approval_id'];
                                        $tanggal_berkas = $kolom['tanggal_berkas'];
                                        $reff_no = $kolom['reff_no'];
                                        $status = $kolom['status'];

                                        if ($i - $jumlah_t_approval == 0) {
                                            $koma = ".";
                                        } else {
                                            $koma = ", ";
                                        }*/

                                        // get first 3 digit
                                        //$kode = substr($reff_no, 0, 3);
                                        
                                        /*AMD /cis/web/topmanagement/approvalcetaklabel/index
                                        ARP /cis/web/topmanagement/approvalpengajuanrepacking/index
                                        ASO /cis/web/topmanagement/approvalagendaso/index
                                        CUS /cis/web/topmanagement/approvalcustomer/index
                                        HOL /cis/web/topmanagement/approvalhasilorientasi/index
                                        KOP 
                                        MOP 
                                        OVK /cis/web/topmanagement/approvalopenvoucher/index
                                        PBL /cis/web/topmanagement/approvalpengajuanpembelianlog/index
                                        PDG /cis/web/topmanagement/approvalkasgrader/uangdinas
                                        PMG /cis/web/topmanagement/approvalkasgrader/uangmakan
                                        PMR /cis/web/topmanagement/approvalpmr/index
                                        POP /cis/web/topmanagement/approvalop/index
                                        PPL /cis/web/topmanagement/approvalproforma/index /cis/web/topmanagement/approvalaliaspackinglist/index
                                        PRP /cis/web/topmanagement/approvalhargaproduk/index
                                        RBS /cis/web/topmanagement/approvalposengon/index
                                        SOP 
                                        SPB /cis/web/topmanagement/approvalcenter/spb
                                        SPO /cis/web/topmanagement/approvalcenter/spo
                                        TBP /cis/web/topmanagement/approvalterimabarang/index
                                        VOP /cis/web/topmanagement/approvalop/vop
                                        */

                                        if ($kode == "AMD" && empty($parameter1) ) {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalcetaklabel/index';
                                            $keterangan = 'Cetak Label';
                                        } else if ($kode == "AMD" && $parameter1 == "Data Correction") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalkoreksidata/index';
                                            $keterangan = 'Koreksi Data';
                                        } else if ($kode == "ARP") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalpengajuanrepacking/index';
                                            $keterangan = 'Pengajuan Repacking';
                                        } else if ($kode == "ASO") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalagendaso/index';
                                            $keterangan = 'Agenda SO';
                                        } else if ($kode == "CUS") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalcustomer/index';
                                            $keterangan = 'Customer';
                                        } else if ($kode == "HOL") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalhasilorientasi/index';
                                            $keterangan = 'Hasil Orientasi';
                                        } else if ($kode == "OVK") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalopenvoucher/index';
                                            $keterangan = 'Open Voucher';
                                        } else if ($kode == "PBL") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalpengajuanpembelianlog/index';
                                            $keterangan = 'Pengajuan Pembelian Log';
                                        } else if ($kode == "PDG") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalkasgrader/uangdinas';
                                            $keterangan = 'Uang Dinas Grader';
                                        } else if ($kode == "PMG") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalkasgrader/uangmakan';
                                            $keterangan = ' Uang Makan Grader';
                                        } else if ($kode == "PMR") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalpmr/index';
                                            $keterangan = 'Permintaan Log (PMR)';
                                        } else if ($kode == "PPL") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalproforma/index';
                                            $keterangan = 'Proforma Packinglist';
                                        } else if ($kode == "PPL" && $parameter1 == "ALIAS PACKINGLIST") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalaliaspackinglist/index';
                                            $keterangan = 'Alias Packinglist';
                                        }else if ($kode == "PRP") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalhargaproduk/index';
                                            $keterangan = 'Harga Produk';
                                        } else if ($kode == "RBS") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalposengon/index';
                                            $keterangan = 'PO Sengon';
                                        } else if ($kode == "SPB") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalcenter/spb';
                                            $keterangan = 'SPB';
                                        } else if ($kode == "SPO") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalcenter/spo';
                                            $keterangan = 'SPO';
                                        } else if ($kode == "TBP") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalterimabarang/index';
                                            $keterangan = 'TBP';
                                        } else if ($kode == "POP" || $kode == "VOP" || $kode == "SOP" || $kode == "MOP" || $kode == "LOP" || $kode == "BOP" || $kode == "FOP"
                                                || $kode == "HOP"  || $kode == "KOP"  || $kode == "GOP"  || $kode == "DOP" ) {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalop/index';
                                            $keterangan = 'Verifikasi OP';
                                        } else if ($kode == "HNP" || $kode == "KNP" || $kode == "SNP" || $kode == "VNP" || $kode == "LGI" || $kode == "LPI" && $parameter1 == "PAL") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalketerlambataninputalert/index';
                                            $keterangan = 'Keterlambatan Input Alert Piutang';
                                        } else if ($kode == "HNP" || $kode == "KNP" || $kode == "SNP" || $kode == "VNP" || $kode == "LGI" || $kode == "LPI" && $parameter1 == "INV") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalcetaknota/index';
                                            $keterangan = 'Cetak Nota Penjualan';
                                        } else if ($kode == "RPK") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalrejectpenerimaankayuolahan/index';
                                            $keterangan = 'Reject Penerimaan Kayu Olahan';
                                        } else if ($kode == "ASR") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalasuransi/index';
                                            $keterangan = 'Pengajuan Asuransi Tongkang';
                                        } else if ($kode == "PRL") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalhargalimbah/index';
                                            $keterangan = 'Harga Limbah';
                                        } else if (substr($parameter1, 0, 3) == "AMD") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalhapusspmexport/index?status=Not Confirmed';
                                            $keterangan = 'Permintaan Hapus SPM Export';
                                        } else if ($kode == "IMP") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalmonitoringproduksi/indexrotary?status=Not Confirmed';
                                            $keterangan = 'Monitoring Input Rotary';
                                        } else if ($kode == "AUT" || $kode == "DMP" || $kode == "CMP" || $kode == "PMP" || $kode == "SMP" || $kode == "RMP" || $kode == "OMP") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalmonitoringproduksi/index?status=Not Confirmed&keyword='.$kode;
                                            // $link = Url::to([
                                            //     '/topmanagement/approvalmonitoringproduksi/index', 
                                            //     'status' => 'Not Confirmed', 
                                            //     'keyword' => $kode
                                            // ], true);
                                            if($kode == 'DMP'){$kategori_proses = "Drying";}
                                            else if($kode == 'CMP'){$kategori_proses = "Core Builder";}
                                            else if($kode == 'PMP'){$kategori_proses = "Plytech";}
                                            else if($kode == 'RMP'){$kategori_proses = "Repair";}
                                            else if($kode == 'OMP'){$kategori_proses = "Output Rotary";}
                                            else if($kode == 'SMP'){$kategori_proses = "Setting";}
                                            else if($kode == 'AUT'){$kategori_proses = "Produksi";}
                                            else{$kategori_proses == '';}
                                            $keterangan = 'Monitoring '.$kategori_proses ;
                                        } else if ($kode == "POC") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvalpocust/index';
                                            $keterangan = 'PO LOG';
                                        } else if ($kode == "DRP") {
                                            $link = Yii::$app->homeUrl.'topmanagement/approvaldrp/index';
                                            $keterangan = 'Rencana Pembayaran';
                                        } else {
                                            $link = '';
                                            $keterangan = '';
                                        }                                        
                                        ?>

                                        <tr>
                                            <!-- <td class='text-center'><?php //echo $i;?></td> -->
                                            <td class='text-left'><?php echo $keterangan;?></td>
                                            <td class='text-right'><?php echo $jumlah;?></td>
                                            <td class='text-center'><a class="btn btn-xs blue-hoki btn-outline tooltips" href='<?php echo $link;?>'><i class="fa fa-info-circle"></i></a></td>
                                        </tr>
                                        <?php
                                        $i++;
                                        $total = $total + $jumlah;
                                    }
                                }                           
                                // buatkan notifikasi untuk aplikasi pendukung cis disini
                                $t_agreement = \app\models\TAgreement::find()
                                                    ->where(['assigned_to'=>$pegawai_id])
                                                    ->andWhere(['status'=>'Not Confirmed'])
                                                    ->andWhere(['not like', 'reff_no', 'MOP%'])
                                                    ->orderBy('tanggal_berkas DESC')
                                                    ->all();
                                $jumlah_t_agreement = count($t_agreement);

                                $sql_t_agreement = "select left(reff_no,3) as kode from t_agreement ".
                                                    "   where assigned_to = ".$pegawai_id." ".
                                                    "   and status = 'Not Confirmed' and left(reff_no,3) <> 'MOP'".
                                                    "   group by left(reff_no,3) ".
                                                    "   ";
                                $query_t_agreement = Yii::$app->db->createCommand($sql_t_agreement)->queryAll();
                                                                                                        
                                // notifikasi agreement
                                if ($jumlah_t_agreement > 0) {
                                    $ii = 1;
                                    $total2 = 0;
                                    foreach ($query_t_agreement as $kolom2) {
                                        $kode2 = $kolom2['kode'];
                                        $sql_jumlah2 = "select count(*) from t_agreement ".
                                                        "   where left(reff_no,3) = '".$kode2."' ".
                                                        "   and assigned_to = ".$pegawai_id." ".
                                                        "   and status = 'Not Confirmed' ";
                                        $jumlah2= Yii::$app->db->createCommand($sql_jumlah2)->queryScalar();
                                        
                                        if ($kode2 == "PDL" || $kode2 == 'RDL') {
                                            $link2 = Yii::$app->request->hostInfo.'/dinasluar/approved';
                                            $keterangan2 = 'Approval Dinas Luar';
                                        } else if ($kode2 == "PJM" || $kode2 == 'RJM') {
                                            $link2 = Yii::$app->request->hostInfo.'/jamuan/approveJamuan';
                                            $keterangan2 = 'Approval Jamuan Makan';
                                        } else if ($kode2 == "BA/") {
                                            $link2 = Yii::$app->request->hostInfo.'/openticket/approvedbap';
                                            $keterangan2 = 'Approval BAP';
                                        } else if ($kode2 == "OPT") {
                                            $link2 = Yii::$app->request->hostInfo.'/openticket/approveopenticket';
                                            $keterangan2 = 'Approval Open Ticket';
                                        } else if ($kode2 == "CCR") {
                                            $link2 = Yii::$app->request->hostInfo.'/ccr/approvedccr';
                                            $keterangan2 = 'Approval CCR';
                                        } else if ($kode2 == "PRJ") {
                                            $link2 = Yii::$app->request->hostInfo.'/project/approveProject';
                                            $keterangan2 = 'Approval Project Management';
                                        // } else if ($kode2 == "MOP") {
                                        //     $link2 = Yii::$app->request->hostInfo.'/mop/approve_mop';
                                        //     $keterangan2 = 'Approval Monitoring OutPut Produksi';
                                        } else if($kode2 == "NCR"){
                                            $link2 = Yii::$app->request->hostInfo.'/ncr/approvedncr';
                                            $keterangan2 = 'Approval NCR';
                                        } else if ($kode2 == "TLH") {
                                            $link2 = Yii::$app->request->hostInfo.'/satpam/approval';
                                            $keterangan2 = 'Approval Laporan Harian Satpam';
                                        } else if ($kode2 == "BHP") {
                                            $link2 = Yii::$app->request->hostInfo.'/report/approve_adjustbhp';
                                            $keterangan2 = 'Approval Adjustment Stock Bahan Pembantu';
                                        } else {
                                            $link2 = '';
                                            $keterangan2 = '';
                                        } 
                                        ?>
                                        <tr>
                                            <!-- <td class='text-center'><?php //echo $i;?></td> -->
                                            <td class='text-left'><?php echo $keterangan2;?></td>
                                            <td class='text-right'><?php echo $jumlah2;?></td>
                                            <td class='text-center'><a class="btn btn-xs blue-hoki btn-outline tooltips" target="_blank" href='<?php echo $link2;?>'><i class="fa fa-info-circle"></i></a></td>
                                        </tr>
                                        <?php
                                        $ii++;
                                        $total2 = $total2 + $jumlah2;
                                    }                                 
                                    
                                }
                                
                                // kondisikan notif untuk masing-masing proses pada NCR
                                // approvedncr
                                $sq_approvedncr = "select count(status) as jmlagreement from view_agreement_ncr where status ='Not Confirmed' and assigned_to = ".$pegawai_id."";
                                $sql_approvedncr = Yii::$app->db->createCommand($sq_approvedncr)->queryScalar();

                                // controlNcr
                                $sq_controlNcr = "select count(ncr_id) as jmlcontrol from t_ncr 
                                                    where status_control = 'f' and diketahui2 > 0 
                                                        and exists (select ncr_pic_control from m_ncr_pic_control where m_ncr_pic_control.ncr_pic_control = ".$pegawai_id.")";
                                $sql_controlNcr = Yii::$app->db->createCommand($sq_controlNcr)->queryScalar();

                                // analisaNcr
                                $sq_analisaNcr  = "select count(ncr_id) as jmlanalisa from t_ncr 
                                                    where status_control = 't' and status_analisa = 'f' and diketahui2 > 0
                                                            and exists (select ncr_pic_analisa from m_ncr_pic_analisa where m_ncr_pic_analisa.ncr_pic_analisa = ".$pegawai_id.") ";
                                $sql_analisaNcr = Yii::$app->db->createCommand($sq_analisaNcr)->queryScalar();
                                
                                // penangananNcr
                                $sq_penangananNcr  = "select count(ncr_detail_id) as jmltindakan from view_ncr_detail 
                                                        where not exists (select * from t_ncr_perbaikan where t_ncr_perbaikan.ncr_detail_id=view_ncr_detail.ncr_detail_id) 
                                                            and status_approve = 1 and ncr_tindakan_pic = ".$pegawai_id." ";
                                $sql_penangananNcr = Yii::$app->db->createCommand($sq_penangananNcr)->queryScalar();
                                
                                // verifipenangananNcr
                                $sq_verifipenangananNcr = "select count(status_verifikator) as jmlverifikasi 
                                                            from view_ncr_perbaikan 
                                                            where ncr_verifikator_pic > 0 and status_verifikator = 'f' and ncr_verifikator_pic = ".$pegawai_id." ";
                                $sql_verifipenangananNcr = Yii::$app->db->createCommand($sq_verifipenangananNcr)->queryScalar();
                                // statusncr_efektifitas
                                $periodeEfektifitas = 1;// kebijakan sebelumnya 90 hari, per tanggal 12 maret 2024 1 hari atau 1 x 24 jam
                                $sq_statusncr_efektifitas = "SELECT 
                                                                    COUNT(view_ncr.ncr_efektifitas) AS jmlefektifitas
                                                                FROM 
                                                                    view_ncr
                                                                WHERE 
                                                                    view_ncr.ncr_tgl >= '2024-03-14'
                                                                    AND view_ncr.ncr_status = 'f'
                                                                    AND view_ncr.ncr_efektifitas_tgl IS NULL
                                                                    AND view_ncr.diketahui2 IS NOT NULL
                                                                    AND view_ncr.ncr_status_tgl<=(current_date - INTERVAL '{$periodeEfektifitas} day')
                                                                    AND view_ncr.pic_approve_efektifitas ILIKE '%$pegawai_id%'
                                                                    AND NOT EXISTS (
                                                                        SELECT 1
                                                                        FROM t_ncr,json_array_elements(view_ncr.status_efektifitas_reason) AS reason
                                                                        WHERE reason->>'id' = '$pegawai_id' and t_ncr.ncr_id = view_ncr.ncr_id
                                                                    ) ";
                                $sql_statusncr_efektifitas = Yii::$app->db->createCommand($sq_statusncr_efektifitas)->queryScalar();

                                $total3 = 0;
                                    if($sql_controlNcr > 0){
                                        $link_sql_controlNcr = Yii::$app->request->hostInfo.'/ncr/controlNcr';
                                        echo"
                                        <tr>
                                            <td class='text-left'>Permintaan Control NCR</td>
                                            <td class='text-right'>$sql_controlNcr</td>
                                            <td class='text-center'><a class='btn btn-xs blue-hoki btn-outline tooltips' target='_blank' href='$link_sql_controlNcr'><i class='fa fa-info-circle'></i></a></td>
                                        </tr>";                                     
                                    }
                                    if($sql_analisaNcr > 0){
                                        $link_sql_analisaNcr = Yii::$app->request->hostInfo.'/ncr/analisaNcr';
                                        echo"
                                        <tr>
                                            <td class='text-left'>Permintaan Analisa NCR</td>
                                            <td class='text-right'>$sql_analisaNcr</td>
                                            <td class='text-center'><a class='btn btn-xs blue-hoki btn-outline tooltips' target='_blank' href='$link_sql_analisaNcr'><i class='fa fa-info-circle'></i></a></td>
                                        </tr>"; 
                                    }
                                    if($sql_penangananNcr > 0){
                                        $link_sql_penangananNcr = Yii::$app->request->hostInfo.'/ncr/penangananNcr';
                                        echo"
                                        <tr>
                                            <td class='text-left'>Permintaan Penanganan NCR</td>
                                            <td class='text-right'>$sql_penangananNcr</td>
                                            <td class='text-center'><a class='btn btn-xs blue-hoki btn-outline tooltips' target='_blank' href='$link_sql_penangananNcr'><i class='fa fa-info-circle'></i></a></td>
                                        </tr>";
                                    }
                                    if($sql_verifipenangananNcr > 0){
                                        $link_sql_verifipenangananNcr = Yii::$app->request->hostInfo.'/ncr/verifipenangananNcr';
                                        echo"
                                        <tr>
                                            <td class='text-left'>Permintaan Verifikasi Penanganan NCR</td>
                                            <td class='text-right'>$sql_verifipenangananNcr</td>
                                            <td class='text-center'><a class='btn btn-xs blue-hoki btn-outline tooltips' target='_blank' href='$link_sql_verifipenangananNcr'><i class='fa fa-info-circle'></i></a></td>
                                        </tr>";
                                    }
                                    if($sql_statusncr_efektifitas > 0){
                                        $link_sql_statusncr_efektifitas = Yii::$app->request->hostInfo.'/ncr/statusncr_efektifitas';
                                        echo"
                                        <tr>
                                            <td class='text-left'>Permintaan Status Efektifitas NCR</td>
                                            <td class='text-right'>$sql_statusncr_efektifitas</td>
                                            <td class='text-center'><a class='btn btn-xs blue-hoki btn-outline tooltips' target='_blank' href='$link_sql_statusncr_efektifitas'><i class='fa fa-info-circle'></i></a></td>
                                        </tr>";
                                    } 

                                $total3 = $sql_controlNcr + $sql_analisaNcr + $sql_penangananNcr + $sql_verifipenangananNcr + $sql_statusncr_efektifitas;
                                
                                // kondisikan notif untuk masing-masing proses pada Open Tiket                                
                                // penangananBap
                                $sq_penangananBap = "select count(bap_tindakan_pic) as jumlah_bap 
                                                        from view_bap_detail 
                                                        where bap_tindakan_pic = ".$pegawai_id."
                                                                and not exists (
                                                                        select * from t_bap_perbaikan where t_bap_perbaikan.bap_detail_id=view_bap_detail.bap_detail_id 
                                                                )";
                                $sql_penangananBap = Yii::$app->db->createCommand($sq_penangananBap)->queryScalar();

                                    $total4 = 0;
                                    if($sql_penangananBap > 0){
                                        $link_sql_penangananBap = Yii::$app->request->hostInfo.'/openticket/penangananBap';
                                        echo"
                                        <tr>
                                            <td class='text-left'>Permintaan Penanganan BAP</td>
                                            <td class='text-right'>$sql_penangananBap</td>
                                            <td class='text-center'><a class='btn btn-xs blue-hoki btn-outline tooltips' target='_blank' href='$link_sql_penangananBap'><i class='fa fa-info-circle'></i></a></td>
                                        </tr>";
                                    } 

                                $total4 = $sql_penangananBap;
                                
                                // kondisikan notif untuk masing-masing proses pada CCR                               
                                // penangananccr
                                $sq_penangananccr = "select count(ccr_tindakan_pic) as jumlah_ccr
                                                        from view_ccr_detail 
                                                        where ccr_tindakan_pic = ".$pegawai_id."
                                                                and status_approve = 1
                                                                and not exists (
                                                                        select * from t_ccr_perbaikan where t_ccr_perbaikan.ccr_detail_id=view_ccr_detail.ccr_detail_id 
                                                                )";
                                $sql_penangananccr = Yii::$app->db->createCommand($sq_penangananccr)->queryScalar();

                                    $total5 = 0;
                                    if($sql_penangananccr > 0){
                                        $link_sql_penangananccr= Yii::$app->request->hostInfo.'/ccr/penangananccr';
                                        echo"
                                        <tr>
                                            <td class='text-left'>Permintaan Penanganan CCR</td>
                                            <td class='text-right'>$sql_penangananccr</td>
                                            <td class='text-center'><a class='btn btn-xs blue-hoki btn-outline tooltips' target='_blank' href='$link_sql_penangananccr'><i class='fa fa-info-circle'></i></a></td>
                                        </tr>";
                                    } 

                                $total5 = $sql_penangananccr;

                                // grand total
                                $Grandtotal = $total + $total2 + $total3 + $total4 + $total5;
                                ?>       
                                        
                                        <tr>
                                            <!-- <th></th> -->
                                            <th>Total</th>
                                            <th class='text-right'><?php echo $Grandtotal;?></th>
                                            <th></th>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php /* EO KONTEN MODAL */ ?>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
