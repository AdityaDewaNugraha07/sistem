<?php
use app\components\DeltaGlobalClass;
use app\models\TApproval;
use yii\helpers\Url;

/** @var TApproval $model */
?>
<div class="modal fade" id="modal-master-info" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Data Customer ') . '<b>' . DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no) . '</b>'; ?></h4>
            </div>
            <div id="showdetails" id="showdetails">
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
		url    : '<?= Url::toRoute(['/topmanagement/approvalcustomer/confirm']); ?>',
		type   : 'POST',
		data   : {approval_id:id},
		success: function (data) {
			if (data) {
				if (type === 'approve') {
					$(".modals-place-2").load('<?= Url::toRoute(['/topmanagement/approvalcustomer/approveReason','id'=>'']); ?>'+id, function() {
                        let modTrans = $("#modal-transaksi");
                        modTrans.modal('show');
						modTrans.on('hidden.bs.modal', function () {
							modTrans.hide();
							modTrans.remove();
						});
						spinbtn();
						draggableModal();
					});
				} else if(type === 'reject') {
                    $(".modals-place-2").load('<?= Url::toRoute(['/topmanagement/approvalcustomer/rejectReason','id'=>'']); ?>'+id, function() {
                        let modTrans = $("#modal-transaksi")
                        modTrans.modal('show');
                        modTrans.on('hidden.bs.modal', function () {
                            modTrans.hide();
                            modTrans.remove();
                        });
                        spinbtn();
                        draggableModal();
					});
				}
			}else{
				openModal('<?= Url::toRoute(['/topmanagement/approvalcustomer/notAllowed','id'=>'']); ?>'+id,'modal-global-info');
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function approve(id) {
	openModal('<?= Url::toRoute(['/topmanagement/approvalcustomer/approveConfirm','id'=>'']); ?>'+id,'modal-global-confirm');
}
</script>
