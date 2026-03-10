<div id='ajax_counter_t_spm' class='text-center'>
    <?php
    //$total = $jumlah_t_op_ko_t_spm_ko+$jumlah_status_cetak_t_spm;
    $total = $jumlah_t_op_ko_t_spm_ko;
    //if ($jumlah_t_op_ko_t_spm_ko + $jumlah_status_cetak_t_spm > 0) {
    if ($jumlah_t_op_ko_t_spm_ko > 0) {
        $xxx = "danger";
        ?>
        <audio autoplay>
            <source src="http://10.10.10.2/ext/ding.mp3" type="audio/mp3">
        </audio>
    <?php        
    } else {
        $xxx = "warning";
    }
    ?>
    <button type="button" class="btn btn-<?php echo $xxx;?> btn-xs" onclick="window.location.href='/cis/web/marketing/spm/index'">
        <b><i class="fa fa-bell-o fa-1x text-<?php echo $xxx;?>" aria-hidden="true" style="color: #fff;" ></i>
        <font style="color: #fff;"><?php echo $total;?></font></b>
    </button>    
</div>