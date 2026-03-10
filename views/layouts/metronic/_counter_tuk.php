<div id='ajax_counter_tuk' class='text-center'>
    <?php
    if ($jumlah_nota_penjualan > 0) {
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
    <button type="button" class="btn btn-<?php echo $xxx;?> btn-xs" onclick="window.location.href='/cis3/web/tuk/dokumenpenjualan/index'">
        <b><i class="fa fa-bell-o fa-1x text-<?php echo $xxx;?>" aria-hidden="true" style="color: #fff;" ></i>
        <font style="color: #fff;"><?php echo $jumlah_nota_penjualan;?></font></b>
    </button>    
</div>