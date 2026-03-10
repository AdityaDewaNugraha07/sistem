<?php
/** @var string $type */
use yii\helpers\Url; ?>
<div class="modal fade" id="modal-history-input" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                        aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Input Rotary') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <table class="table table-bordered table-hover" id="table-history-input" style="width: 100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th><?= Yii::t('app', 'Tanggal') ?></th>
                                <th><?= Yii::t('app', 'Kode') ?></th>
                                <th><?= Yii::t('app', 'Shift') ?></th>
                                <th><?= Yii::t('app', 'Jenis Kayu') ?></th>
                                <th><?= Yii::t('app', 'Status') ?></th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#table-history-input').DataTable({
            ajax: {
                url: '<?= Url::toRoute('/ppic/monitoringrotary/modalhistoryinput')?>',
                type: 'POST',
            },
            responsive: true,
            columns: [
                {
                    targets: 0, orderable: false, class: 'text-align-center',
                    render: function (data, type, full, meta) {
                        return meta.row + 1;
                    }
                },
                {targets: 1, class: 'text-align-center'},
                {targets: 2, class: 'text-align-center'},
                {targets: 3, class: 'text-align-center'},
                {targets: 4, class: 'text-align-center'},
                {
                    targets: 5,
                    class: 'text-align-center',
                    render: function (data) {
                        return `
                            <span class="badge badge-${colorForApprover(data)}">${data}</span>
                        `;
                    }
                },
                {
                    targets: 6, class: 'text-align-center',
                    render: function (data, type, full) {
                        return `
                            <a href="javascript:void(0);" class="btn btn-outline blue-steel btn-xs" onclick="info(${full[0]})"><i class="fa fa-info-circle"></i></a>
                        `;
                    }
                },
            ]
        });
    });

    function info(id) {
        $('.modal').modal('toggle');
        showData(id, 'show');
    }
</script>
