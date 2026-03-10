<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Agreement Summary ').'<b>'.\app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no).'</b>'; ?></h4>
            </div>
			<div id="showdetails" id="showdetails">
                <?= $this->render('showOp', ['approval_id' => $model->approval_id]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJs(" showdetails('".$model->approval_id."'); ", yii\web\View::POS_READY); ?>
<script>
function showdetails(approval_id){
	$('#showdetails').addClass("animation-loading");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalop/showDetails']); ?>',
		type   : 'POST',
		data   : {approval_id:approval_id},
		success: function (data) {
			if(data.html){
				$('#showdetails').html(data.html);
			}else{
				$('#showdetails').html("");
			}
			$('#showdetails').removeClass("animation-loading");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function approve(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalop/approveConfirm','id'=>'']); ?>'+id,'modal-global-confirm');
}
function reject(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalop/rejectConfirm','id'=>'']); ?>'+id,'modal-global-confirm');
}
</script>