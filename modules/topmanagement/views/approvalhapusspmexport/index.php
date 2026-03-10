<?php
/* @var $this yii\web\View */

use app\models\MMenu;
use app\models\TApproval;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Approval Permintaan Hapus SPM Export';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="<?= isset($_GET['status']) && $_GET['status'] === TApproval::STATUS_NOT_CONFIRMATED ? 'active': ''?>">
                        <a href="<?= Url::toRoute("/topmanagement/approvalhapusspmexport/index?status=Not Confirmed") ?>"> <?= Yii::t('app', 'Not Confirmed') ?> </a>
                    </li>
                    <li class="<?= !isset($_GET['status']) ? 'active': ''?>">
                        <a href="<?= Url::toRoute("/topmanagement/approvalhapusspmexport/index") ?>"> <?= Yii::t('app', 'Confirmed') ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
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
                                <table class="table table-striped table-bordered table-hover" id="table-master">
                                    <thead>
                                    <tr class='text-left'>
                                        <th>No</th>
                                        <th><?= Yii::t('app', 'Nomor SPM') ?></th>
                                        <th><?= Yii::t('app', 'Status Realisasi SPM') ?></th>
                                        <th><?= Yii::t('app', isset($_GET['status']) && $_GET['status'] === TApproval::STATUS_NOT_CONFIRMATED ? 'Tanggal': 'Tanggal Approve') ?></th>
                                        <th><?= Yii::t('app', 'Assign To') ?></th>
                                        <th><?= Yii::t('app', 'Approved By') ?></th>
                                        <th><?= Yii::t('app', 'Alasan Batal') ?></th>
                                        <th><?= Yii::t('app', 'Status') ?></th>
                                        <th style="width: 50px;"></th>

                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" formconfig(); dtMaster(); setMenuActive('" . json_encode(MMenu::getMenuByCurrentURL('Approval Hapus SPM Export')) . "');", View::POS_READY); ?>
<?php
$query = isset($_GET['status']) ? '?status='. $_GET['status'] : '';
$url = Url::toRoute('/topmanagement/approvalhapusspmexport/' . $this->context->action->id . $query);
?>
<script>
    function dtMaster() {
        $('#table-master').dataTable({
            ajax: {url: '<?= $url ?>', data: {dt: 'table-master'}},
            order: [],
            columnDefs: [
                {
                    targets: 0, orderable: false,
                    render: function (data, type, full, meta) {
                        return '<div style="text-align: center;">' + (meta.row + 1) + '</div>';
                    }
                },
                {targets: 1, class: "td-kecil text-align-left"},
                {
                    targets: 2, class: "td-kecil text-align-center",
                    render: function (data) {
                        let render = '';
                        if(data !== null) {
                            const parse = JSON.parse(data);
                            if(parse.status) {
                                render = `<span class="btn btn-xs btn-info">${parse.status}</span>`;
                            }else {
                                render = `<span class="btn btn-xs btn-default">BELUM REALISASI</span>`;
                            }
                        }

                        return render;
                    }
                },
                {
                    targets: 3,
                    class: "td-kecil text-align-left",
                    render: function(data, type, full) {
                        const notConfirmed = '<?= isset($_GET['status']) && $_GET['status'] === TApproval::STATUS_NOT_CONFIRMATED ? 'true': 'false' ?>';
                        if(notConfirmed === 'true') {
                            return data;
                        }else {
                            return full[9];
                        }
                    }
                },
                {targets: 4, class: "td-kecil text-align-left"},
                {targets: 5, class: "td-kecil text-align-left"},
                {targets: 6, class: "td-kecil text-align-left"},
                {
                    targets: 7, class: "td-kecil text-align-center",
                    render: function (data) {
                        let ret = ' - ';
                        if (data === 'APPROVED') {
                            ret = '<span class="label label-success">' + data + '</span>';
                        } else if (data === 'REJECTED') {
                            ret = '<span class="label label-danger">' + data + '</span>';
                        } else if (data === 'Not Confirmed') {
                            ret = '<span class="label label-default">' + data + '</span>';
                        }
                        return ret;
                    }
                },
                {
                    targets: 8, orderable: false,
                    render: function (data, type, full) {
                        return '<div style="text-align: center;"><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info(' + full[0] + ')\"><i class="fa fa-info-circle"></i></a></div>';
                    }
                },


            ],
            "dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
            "bStateSave": true,
        });
    }

    function info(id) {
        openModal('<?= Url::toRoute(['/topmanagement/approvalhapusspmexport/info', 'id' => '']) ?>' + id, 'modal-master-info', '95%', " $('#table-master').dataTable().fnClearTable(); ");
    }

</script>