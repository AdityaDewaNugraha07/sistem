<?php

use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Approval Customer';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);

$status1 = '';
$status2 = '';
if (!empty($status)) {
    $status1 = ($status == 'Not Confirmed') ? 'active' : '';
    $status2 = ($status == 'Confirmed')     ? 'active' :  '';
}
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
                    <li class="<?php echo $status1;?>">
						<a href="<?= Url::toRoute("/topmanagement/approvalcustomer/index"); ?>"> <?= Yii::t('app', 'Not Confirmed'); ?> </a>
                    </li>
					<li class="<?php echo $status2;?>">
						<a href="<?= Url::toRoute("/topmanagement/approvalcustomer/indexConfirmed") ?>"> <?= Yii::t('app', 'Confirmed'); ?> </a>
					</li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<?= Yii::t('app', 'Daftar Pengajuan Update Data Customer'); ?>
                                </div>
                                <div class="tools">
                                    <a href="javascript::void(0)" class="reload"> </a>
                                    <a href="javascript::void(0)" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-master">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Kode Customer') ?> </th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Nama') ?> </th>                                            
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Berkas') ?></th>
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

<?php
$this->registerJs(" dtMaster();
setMenuActive('".Json::encode(app\models\MMenu::getMenuByCurrentURL('Approval Customer'))."');"
, View::POS_READY);
?>

<?php $url = Url::toRoute('/topmanagement/approvalcustomer/'.$this->context->action->id); ?>

<script>
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function dtMaster(){
    $('#table-master').dataTable({
        ajax: {
            url : '<?= $url ?>',
            data: {
                dt : 'table-master'
            }
        },
        order: [],
        columnDefs: [
            {
                targets     : 0,
                orderable   : false,
                class       : 'text-center',
                render      : (data, type, full, meta) => meta.row + 1
            },
            {
                targets     : 1,
                class       : "td-kecil text-align-center"
            },
            {
                targets     : 2,
                class       : "td-kecil text-align-center"
            },
            {
                targets     : 3,
                class       : "td-kecil text-center",
                render      : data => new Date(data).toString('dd/MM/yyyy')
            },
            {
                targets     : 4,
                class       : "td-kecil text-align-left",
                render      : (data, type, full) => '<span style="padding-left: 10px;">' + full[4] + '</span>'
            },
            {
                targets     : 5,
                class       : "td-kecil text-align-left",
                render      : (data, type, full) => full[5]
            },
            {
                targets     : 6,
                class       : "td-kecil text-align-center",
                render      : function (data, type, full) {
                    switch (full[7]) {
                        case 'APPROVED':
                            return '<span class="label label-success" style="font-size:1.1rem;">' + full[7] + '</span>';
                        case 'REJECTED':
                            return '<span class="label label-danger" style="font-size:1.1rem;">' + full[7] + '</span>';
                        case 'Not Confirmed':
                            return '<span class="label label-default" style="font-size:1.1rem;">' + full[7] + '</span>';
                        default:
                            return ' - ';
                    }
                }
            },
            {
                targets     : 7,
                orderable   : false,
                class       : 'text-center',
                render      : (data, type, full) => '<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="info(' + full[0] + ')"><i class="fa fa-info-circle"></i></a>'
            },
        ],
        "dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
        "bStateSave": true,
    });
}

function info(id){
	openModal('<?= Url::toRoute(['/topmanagement/approvalcustomer/info','id'=>'']) ?>'+id,'modal-master-info','90%'," $('#table-master').dataTable().fnClearTable(); ");
}

function image(tipe, reff_no) {
    const url = '<?= Url::toRoute(['/topmanagement/approvalcustomer/image', 'reff_no' => '']) ?>' + reff_no + '&tipe=' + tipe;
    $(".modals-place-2").load(url, function() {
        let modal = $("#modal-image");
        modal.modal('show');
        modal.on('hidden.bs.modal', function () { });
        $("#modal-image .modal-dialog");
        spinbtn();
        draggableModal();
    });
}
</script>