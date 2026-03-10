<div id='ajax_counter_t_approval' class='text-center'>
    <?php
    if ($jumlah_t_approval > 0) {
        $xxx = "danger";
    } else {
        $xxx = "warning";
    }
    ?>
    <button type="button" class="btn btn-<?php echo $xxx;?> btn-xs" onclick="opennotifTApproval(<?php echo $user_id;?>,<?php echo $pegawai_id;?>);">
        <b><i class="fa fa-bell-o fa-1x text-<?php echo $xxx;?>" aria-hidden="true" style="color: #fff;" ></i>
        <font style="color: #fff;"><?php echo $jumlah_t_approval;?></font></b>
    </button>
    <?php
    /*if ($jumlah_t_approval > 0) {
        $i = 1;
        foreach ($t_approval as $kolom) {
            $approval_id = $kolom['approval_id'];
            $reff_no = $kolom['reff_no'];
            if ($i - $jumlah_t_approval == 0) {
                $koma = ".";
            } else {
                $koma = ", ";
            }
            // get first 3 digit
            $kode = substr($reff_no, 0, 3);
            
            /*AMD /cis/web/topmanagement/approvalcetaklabel/index -- jika AMD /cis/web/topmanagement/approvalcetaklabel/index && parameter 1 = Data Correction
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
            POP 
            PPL /cis/web/topmanagement/approvalproforma/index /cis/web/topmanagement/approvalaliaspackinglist/index
            PRP /cis/web/topmanagement/approvalhargaproduk/index
            RBS /cis/web/topmanagement/approvalposengon/index
            SOP 
            SPB /cis/web/topmanagement/approvalcenter/spb
            SPO /cis/web/topmanagement/approvalcenter/spo
            TBP /cis/web/topmanagement/approvalterimabarang/index
            VOP /cis/web/topmanagement/approvalop/vop
            */

            /*if ($kode == "AMD") {
                $link = '/cis/web/topmanagement/approvalcetaklabel/index';
            } else if ($kode == "ARP") {
                $link = '/cis/web/topmanagement/approvalpengajuanrepacking/index';
            } else if ($kode == "ASO") {
                $link = '/cis/web/topmanagement/approvalagendaso/index';
            } else if ($kode == "CUS") {
                $link = '/cis/web/topmanagement/approvalcustomer/index';
            } else if ($kode == "HOL") {
                $link = '/cis/web/topmanagement/approvalhasilorientasi/index';
            } else if ($kode == "OVK") {
                $link = '/cis/web/topmanagement/approvalopenvoucher/index';
            } else if ($kode == "PBL") {
                $link = '/cis/web/topmanagement/approvalpengajuanpembelianlog/index';
            } else if ($kode == "PDG") {
                $link = '/cis/web/topmanagement/approvalkasgrader/uangdinas';
            } else if ($kode == "PMG") {
                $link = '/cis/web/topmanagement/approvalkasgrader/uangmakan';
            } else if ($kode == "PMR") {
                $link = '/cis/web/topmanagement/approvalpmr/index';
            } else if ($kode == "PPL") {
                $link = '/cis/web/topmanagement/approvalaliaspackinglist/index';
            } else if ($kode == "PRP") {
                $link = '/cis/web/topmanagement/approvalhargaproduk/index';
            } else if ($kode == "RBS") {
                $link = '/cis/web/topmanagement/approvalposengon/index';
            } else if ($kode == "SPB") {
                $link = '/cis/web/topmanagement/approvalcenter/spb';
            } else if ($kode == "SPO") {
                $link = '/cis/web/topmanagement/approvalcenter/spo';
            } else if ($kode == "TBP") {
                $link = '/cis/web/topmanagement/approvalterimabarang/index';
            } else if ($kode == "VOP") {
                $link = '/cis/web/topmanagement/approvalop/vop';
            } else {
                $link = '';
            }

            //echo "<a href='".$link."'>".$reff_no."".$koma."</a>";
            ?>

            <?php
            $i++;
        }
    }*/
    ?>
</div>