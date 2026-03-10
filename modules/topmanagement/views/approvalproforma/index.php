<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Approval Proforma Packinglist';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);

/** @var string $status */
$status === 'Not Confirmed' ? $status1 = 'active' : $status1 = '';
$status === 'Confirmed' ? $status2 = 'active' : $status2 = '';

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
                    <li class="<?php echo $status1; ?>">
                        <a href="<?= yii\helpers\Url::toRoute("/topmanagement/approvalproforma/index") ?>"> <?= Yii::t('app', 'Not Confirmed') ?> </a>
                    </li>
                    <li class="<?php echo $status2; ?>">
                        <a href="<?= Url::toRoute("/topmanagement/approvalproforma/indexConfirmed") ?>"> <?= Yii::t('app', 'Confirmed') ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <?= Yii::t('app', 'Daftar Proforma Packinglist') ?>
                                </div>
                                <div class="tools">
                                    <a href="javascript:void(0)" class="reload"> </a>
                                    <a href="javascript:void(0)" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-master">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Berkas') ?></th>
                                        <th style="line-height: 1;"><?= Yii::t('app', 'Nomor<br>Kontrak') ?></th>
                                        <th style="line-height: 1;"><?= Yii::t('app', 'Kode<br>Proforma') ?></th>
                                        <th style="line-height: 1;"><?= Yii::t('app', 'Revisi Ke') ?></th>
                                        <th style="line-height: 1;"><?= Yii::t('app', 'Buyer') ?></th>
                                        <th><?= Yii::t('app', 'Assign To') ?></th>
                                        <th><?= Yii::t('app', 'Approved By') ?></th>
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
<?php $this->registerJs(" dtMaster(); setMenuActive('" . json_encode(app\models\MMenu::getMenuByCurrentURL('Approve Proforma')) . "');", yii\web\View::POS_READY); ?>
<?php $url = Url::toRoute('/topmanagement/approvalproforma/' . $this->context->action->id); ?>
<script>
    function dtMaster() {
        $('#table-master').dataTable({
            ajax: {url: '<?= $url ?>', data: {dt: 'table-master'}},
//		order: [
//            [1, 'desc'],
//        ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    width: '5%',
                    render: function (data, type, full, meta) {
                        return '<div style="text-align: center;">' + (meta.row + 1) + '</div>';
                    }
                },
                {
                    targets: 1, class: "td-kecil text-align-center",
                    render: function (data) {
                        let date = new Date(data);
                        date = date.toString('dd/MM/yyyy');
                        return '<div style="text-align: center;">' + date + '</div>';
                    }
                },
                {targets: 2, class: "td-kecil text-align-center"},
                {targets: 3, class: "td-kecil text-align-center"},
                {targets: 4, class: "td-kecil text-align-center"},
                {targets: 5, class: "td-kecil"},
                {targets: 6, class: "td-kecil"},
                {targets: 7, class: "td-kecil"},
                {
                    targets: 8,
                    width: '10%',
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
                    targets: 9,
                    orderable: false,
                    width: '5%',
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
        openModal('<?= Url::toRoute(['/topmanagement/approvalproforma/info', 'id' => '']) ?>' + id, 'modal-master-info', '90%', " $('#table-master').dataTable().fnClearTable(); ");
    }
</script>