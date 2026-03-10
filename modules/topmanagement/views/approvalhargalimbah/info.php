<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<h4 class="modal-title" style="font-weight: bold;"><?= Yii::t('app', 'Approval Harga Limbah ').'<b>'.\app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no).'</b>'; ?> </h4>
			</div>
			<div id="showdetails" id="showdetails" style="border : 1px;">
				<div class="row" style="margin-top: 10px;">
					<div class="col-md-4 text-center">Tanggal Penetapan : <?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($harga_tanggal_penetapan);?></div>
					<div class="col-md-4 text-center">Created By : <?php echo $created_by;?></div>
					<div class="col-md-4 text-center">Created At : <?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($created_at);?></div>
				</div>
				<?= $this->render('show', ['approval_id' => $model->approval_id]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJs(" showdetails('".$model->approval_id."'); ", yii\web\View::POS_READY); ?>
<script>
function confirm(id,type){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalhargalimbah/confirm']); ?>',
		type   : 'POST',
		data   : {approval_id:id},
		success: function (data) {
			if (data) {
				if (type == 'approve') {
					$(".modals-place-2").load('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalhargalimbah/approveReason','id'=>'']); ?>'+id, function() {
						$("#modal-transaksi").modal('show');
						$("#modal-transaksi").on('hidden.bs.modal', function () {
							$("#modal-transaksi").hide();
							$("#modal-transaksi").remove();
						});
						spinbtn();
						draggableModal();
					});
				} else if(type == 'reject') {
						$(".modals-place-2").load('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalhargalimbah/rejectReason','id'=>'']); ?>'+id, function() {
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
				openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalhargalimbah/notAllowed','id'=>'']); ?>'+id,'modal-global-info');
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function approve(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalhargalimbah/approveConfirm','id'=>'']); ?>'+id,'modal-global-confirm');
}
</script>
