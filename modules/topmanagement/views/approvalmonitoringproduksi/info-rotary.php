<?php

use app\models\TApproval;
use yii\helpers\Url;

/**
 * @var TApproval $model
 */

?>
<div class="modal fade" id="modal-master-info" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                        aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Approval Monitoring Rotary') ?></h4>
            </div>
            <div id="showdetails" id="showdetails">
                <?= $this->render('show-data-rotary', ['model' => $model]) ?>
            </div>
        </div>
    </div>
</div>
<script>
    function approve(id) {
        $(".modals-place-2").load('<?= Url::toRoute(['/topmanagement/approvalmonitoringproduksi/approveReasonRotary', 'id' => '']) ?>' + id, function () {
            const modTarns = $("#modal-transaksi");
            modTarns.modal('show');
            modTarns.on('hidden.bs.modal', function () {
                modTarns.hide();
                modTarns.remove();
            });
            spinbtn();
            draggableModal();
        });
    }

    function reject(id) {
        $(".modals-place-2").load('<?= Url::toRoute(['/topmanagement/approvalmonitoringproduksi/rejectReasonRotary', 'id' => '']) ?>' + id, function () {
            const modTarns = $("#modal-transaksi");
            modTarns.modal('show');
            modTarns.on('hidden.bs.modal', function () {
                modTarns.hide();
                modTarns.remove();
            });
            spinbtn();
            draggableModal();
        });
    }
</script>