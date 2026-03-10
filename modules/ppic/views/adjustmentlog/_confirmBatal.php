<div class="modal fade zzz" id="modal-confirm" tabindex="-1" role="basic" style="margin-top: 50px;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-center text-danger" style="font-weight: bold;">CANCEL DATA ?</div>
            <div class="modal-header text-center text-danger"><textarea class="form-control" id="cancel_reason" placeholder="Alasan"></textarea></div>
            <div class="modal-footer text-center" style="text-align: center;">
                <button id="hapusDetailyes" class="btn btn-md btn-success btn-outline text-center" onclick="batalYes(<?php echo $id;?>)" type="button" value="Yes" >Yes</button>
                &nbsp;&nbsp;&nbsp;
                <button id="hapusDetailNo" class="btn btn-md btn-danger btn-outline text-center" onclick="batalNo()" type="button" value="No">No</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $this->registerJs(" 
formconfig(); 
", yii\web\View::POS_READY); ?>
<?php $this->registerCssFile($this->theme->baseUrl."/pages/css/profile.min.css"); ?>

<script>
function batalYes (id) {
    cancel_reason = $('#cancel_reason').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/adjustmentlog/batalYes']); ?>',
        type   : 'POST',
        data   : {id:id, cancel_reason:cancel_reason},
        success: function (data) {
			if(data){
                $('#modal-confirm').modal('toggle');
                $('#table-daftarAdjustmentLog tbody').html(data.html);
                $('#msg').html(data.msg).show().delay(2000).fadeOut('slow');
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });    
}

function batalNo () {
    $('#modal-confirm').modal('toggle');
}
</script>


