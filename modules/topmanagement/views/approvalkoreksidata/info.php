<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Approval ').'<b>'.\app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no).'</b>'; ?></h4>
            </div>
			<div id="showdetails" id="showdetails">
                <?= $this->render('show', ['model'=>$model,'modReff'=>$modReff,'modDetail'=>$modDetail]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
function confirm(id,type){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalkoreksidata/confirm']); ?>',
		type   : 'POST',
		data   : {approval_id:id},
		success: function (data) {
			if(data){
				if(type == 'approve'){
					approve(id);
				}else if(type == 'reject'){
					$(".modals-place-2").load('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalkoreksidata/rejectReason','id'=>'']); ?>'+id, function() {
						$("#modal-transaksi").modal('show');
						$("#modal-transaksi").on('hidden.bs.modal', function () {
							$("#modal-transaksi").hide();
							$("#modal-transaksi").remove();
						});
						spinbtn();
						draggableModal();
					});
				}
			}else{
				openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalkoreksidata/notAllowed','id'=>'']); ?>'+id,'modal-global-info');
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function approve(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalkoreksidata/approveConfirm','id'=>'']); ?>'+id,'modal-global-confirm');
}
</script>