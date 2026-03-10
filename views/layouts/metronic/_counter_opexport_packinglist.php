<div id='ajax_counter_opexport' class='text-center'>
    <?php
    if ($opexport_packinglist > 0) {
        $xxx = "danger";
        ?>
        <audio autoplay>
            <source src="http://10.10.10.2/ext/ding.mp3" type="audio/mp3">
        </audio>
    <?php        
    } else {
        $xxx = "warning";
    }       
    
    echo"
    <button type='button' class='btn btn-$xxx btn-xs' onclick=\"opennotifOpexportProforma($id);\">
        <b><i class='fa fa-bell-o fa-1x text-$xxx' aria-hidden='true' style='color: #fff;' ></i>
        <font style='color: #fff;'>$opexport_packinglist</font></b>
    </button>";
    ?>

</div>