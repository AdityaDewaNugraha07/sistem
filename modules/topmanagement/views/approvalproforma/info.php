<?php

use app\components\DeltaGlobalClass;
use app\models\TApproval;
use yii\helpers\Url;

?>
<div class="modal fade" id="modal-master-info" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                        aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= /** @var TApproval $model */
                    Yii::t('app', 'Agreement Summary ') . '<b>' . DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no) . '</b>' ?></h4>
            </div>
            <div id="showdetails" id="showdetails">
                <?= $this->render('show', ['approval_id' => $model->approval_id]) ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJs(" showdetails('".$model->approval_id."'); ", yii\web\View::POS_READY); ?>
<script>
    function showdetails(approval_id) {
        const showdetail = $('#showdetails')
        showdetail.addClass("animation-loading");
        $.ajax({
            url: '<?= Url::toRoute(['/topmanagement/approvalproforma/showDetails']) ?>',
            type: 'POST',
            data: {approval_id: approval_id},
            success: function (data) {
                if (data.html) {
                    $('#showdetails').html(data.html);
                } else {
                    $('#showdetails').html("");
                }
                showdetail.removeClass("animation-loading");
            },
            error: function (jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function approve(id) {
        $(".modals-place-2").load('<?= Url::toRoute(['/topmanagement/approvalproforma/approveConfirm', 'id' => '']) ?>' + id, function () {
            const modalTransaksi = $("#modal-transaksi");
            modalTransaksi.modal('show');
            modalTransaksi.on('hidden.bs.modal', function () {
                modalTransaksi.hide();
                modalTransaksi.remove();
            });
            spinbtn();
            draggableModal();
        });
    }

    function reject(id) {
        $(".modals-place-2").load('<?= Url::toRoute(['/topmanagement/approvalproforma/rejectConfirm', 'id' => '']) ?>' + id, function () {
            const modalTransaksi = $("#modal-transaksi");
            modalTransaksi.modal('show');
            modalTransaksi.on('hidden.bs.modal', function () {
                modalTransaksi.hide();
                modalTransaksi.remove();
            });
            spinbtn();
            draggableModal();
        });
    }
</script>