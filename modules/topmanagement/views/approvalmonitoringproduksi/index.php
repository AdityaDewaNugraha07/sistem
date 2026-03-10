<?php
/* @var $this yii\web\View */

use app\models\MMenu;
use app\models\TApproval;
use yii\helpers\Url;

$this->title = 'Approval Monitoring Produksi';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="<?= isset($_GET['status']) && $_GET['status'] === TApproval::STATUS_NOT_CONFIRMATED ? 'active' : '' ?>">
                        <a href="<?= Url::toRoute("/topmanagement/approvalmonitoringproduksi/index?status=Not Confirmed") ?>"> <?= Yii::t('app', 'Not Confirmed') ?> </a>
                    </li>
                    <li class="<?= !isset($_GET['status']) ? 'active' : '' ?>">
                        <a href="<?= Url::toRoute("/topmanagement/approvalmonitoringproduksi/index") ?>"> <?= Yii::t('app', 'Confirmed') ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <?= Yii::t('app', 'Daftar Approval') ?>
                                </div>
                                <div class="tools">
                                    <a href="javascript:void(0)" class="reload"> </a>
                                    <a href="javascript:void(0)" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-bordered table-hover" id="table-master" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th><?= Yii::t('app', 'Nomor Referensi') ?></th>
                                        <th><?= Yii::t('app', isset($_GET['status']) && $_GET['status'] === TApproval::STATUS_NOT_CONFIRMATED ? 'Tanggal Berkas' : 'Tanggal Approve') ?></th>
                                        <th><?= Yii::t('app', 'Status') ?></th>
                                        <th><?= Yii::t('app', 'Shift') ?></th>
                                        <th><?= Yii::t('app', 'I/O') ?></th>
                                        <th><?= Yii::t('app', 'Kategori Proses') ?></th>
                                        <th><?= Yii::t('app', 'Assign To') ?></th>
                                        <th><?= Yii::t('app', 'Approved By') ?></th>
                                        <th><?= Yii::t('app', 'Tanggal Produksi') ?></th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" formconfig(); dtMaster(); setMenuActive('" . json_encode(MMenu::getMenuByCurrentURL('Approval Monitoring Sengon')) . "');"); ?>
<?php
$query = isset($_GET['status']) ? '?status=' . $_GET['status'] : '';
$url = Url::toRoute('/topmanagement/approvalmonitoringproduksi/' . $this->context->action->id . $query);
?>
<script>
    function dtMaster() {
        function getUrlParameter(name) {
            name = name.replace(/[[]/, '\\[').replace(/[]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }
        $('#table-master').dataTable({
            // ajax: {url: '<?= $url ?>', data: {dt: 'table-master'}},
            ajax: {
                url: '<?= $url ?>', 
                type: 'GET',
                data: function(d) {
                    // Tambahkan parameter 'dt' dan 'keyword' ke data yang dikirim ke server
                    d.dt = 'table-master';
                    d.keyword = getUrlParameter('keyword'); // Mengambil parameter 'keyword' dari URL
                }
            },
            order: [],
            responsive: true,
            columnDefs: [
                {
                    targets: 0, orderable: false, class: 'td-center',
                    render: function (data, type, full, meta) {
                        return '<div style="text-align: center;">' + (meta.row + 1) + '</div>';
                    }
                },
                {targets: 1, class: "td-center"},
                {
                    targets: 2,
                    class: "td-center",
                    render: function (data, type, full) {
                        const notConfirmed = '<?= isset($_GET['status']) && $_GET['status'] === TApproval::STATUS_NOT_CONFIRMATED ? 'true' : 'false' ?>';
                        if (notConfirmed === 'true') {
                            return data;
                        } else {
                            return full[11];
                        }
                    }
                },
                {
                    targets: 3, class: "td-center",
                    render: function (data) {
                        let ret = ' - ';
                        if (data === 'APPROVED') {
                            ret = '<span class="badge badge-success">' + data + '</span>';
                        } else if (data === 'REJECTED') {
                            ret = '<span class="badge badge-danger">' + data + '</span>';
                        } else if (data === 'Not Confirmed') {
                            ret = '<span class="badge badge-default">' + data + '</span>';
                        }
                        return ret;
                    }
                },
                {targets: 4, class: "td-center"},
                {targets: 5, class: "td-center"},
                {targets: 6, class: "td-center"},
                {targets: 7, class: "td-center"},
                {targets: 8, class: "td-center"},
                {targets: 9, class: "td-center", visible: eval('<?= isset($_GET['status']) && $_GET['status'] === TApproval::STATUS_NOT_CONFIRMATED ? 'false' : 'true' ?>')},
                {
                    targets: 10, orderable: false,
                    render: function (data, type, full) {
                        return '<div style="text-align: center;"><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info(' + full[0] + ')\"><i class="fa fa-info-circle"></i></a></div>';
                    }
                },


            ],
            "dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
            // "bStateSave": true,
        });
    }

    function info(id) {
        openModal('<?= Url::toRoute(['/topmanagement/approvalmonitoringproduksi/info', 'id' => '']) ?>' + id, 'modal-master-info', '95%', " $('#table-master').dataTable().fnClearTable(); ");
    }

</script>