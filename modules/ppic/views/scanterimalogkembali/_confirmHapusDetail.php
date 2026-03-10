<div class="modal fade zzz" id="modal-confirm" tabindex="-1" role="basic" style="margin-top: 50px;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-center text-danger" style="font-weight: bold;">DELETE DATA ?</div>
            <div class="modal-footer text-center" style="text-align: center;">
                <button id="hapusDetailyes" class="btn hijau btn-outline ciptana-spin-btn" onclick="hapusDetailYes(<?php echo $id;?>)" type="button" value="Yes" >Yes</button>
                &nbsp;&nbsp;&nbsp;
                <button id="hapusDetailNo" class="btn btn-danger btn-outline ciptana-spin-btn ladda-button text-center" onclick="hapusDetailNo()" type="button" value="No">No</button>
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
function hapusDetailYes (id) {
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/scanterimalogkembali/hapusDetailYes']); ?>',
        type   : 'POST',
        data   : {id:id},
        success: function (data) {
			if(data.status){
                $('#modal-confirm').modal('hide');
                cisAlert(data.msg);
                // Refresh table
                $('#table-aftersave').DataTable().ajax.reload();
            } else {
                cisAlert(data.msg);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });    
}

function hapusDetailNo () {
    $('#modal-confirm').modal('toggle');
}
</script>


