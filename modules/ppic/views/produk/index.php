<?php
/* @var $this yii\web\View */
$this->title = 'Master Produk';
app\assets\DatatableAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Produk'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/ppic/produk/index"); ?>"> <?= Yii::t('app', 'Produk'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/ppic/grade/index"); ?>"> <?= Yii::t('app', 'Grade'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/ppic/jeniskayu/index"); ?>"> <?= Yii::t('app', 'Jenis Kayu'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/ppic/glue/index"); ?>"> <?= Yii::t('app', 'Glue'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/ppic/profilkayu/index"); ?>"> <?= Yii::t('app', 'Profil Kayu'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/ppic/kondisikayu/index"); ?>"> <?= Yii::t('app', 'Kondisi Kayu'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/ppic/kayu/index"); ?>"> <?= Yii::t('app', 'Bahan Kayu'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/ppic/warnakayu/index"); ?>"> <?= Yii::t('app', 'Warna Kayu'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Barang Produk'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-produk">
                                    <thead>
                                        <tr>
                                            <th>produk_id</th>
                                            <th style="width: 10%;"><?= Yii::t('app', 'Jenis Produk') ?></th>
                                            <th><?= Yii::t('app', 'Kode Produk') ?></th>
                                            <th><?= Yii::t('app', 'Nama Produk') ?></th>
                                            <th><?= Yii::t('app', 'Dimensi') ?></th>
                                            <th><?= Yii::t('app', 'Status') ?></th>
                                            <th style="width: 75px; line-height: 1;"><?= Yii::t('app', 'Created<br>At') ?></th>
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
<?php $this->registerJs(" dtProduk();", yii\web\View::POS_READY); ?>

<script>
function dtProduk(){
    var dt_table =  $('#table-produk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/produk/index') ?>',data:{dt: 'table-produk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
//            {	targets: 0, visible: false },
            {	targets: 5,
                orderable: false,
				class:"text-align-center",
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data){
                        ret = '<span class="font-green-seagreen"><i class="fa fa-check"></i></span>';
                    }else{
                        ret = '<span class="font-red-flamingo"><i class="fa fa-close"></i></span>';
                    }
                    return ret;
                }
            },
            {	targets: 6, class:'td-kecil' },
            {	targets: 7, 
                orderable: false,
				class:"text-align-center",
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
        ],
        "fnDrawCallback": function( oSettings ) {
			formattingDatatableMasterThis(oSettings.sTableId);
		},
        "autoWidth": false,
    });
}

function create(){
	openModal('<?= \yii\helpers\Url::toRoute('/ppic/produk/create') ?>','modal-produk-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/ppic/produk/info','id'=>'']) ?>'+id,'modal-produk-info');
}
<?php if(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER){ ?>
function formattingDatatableMasterThis(sTableId){
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').html("\
        <a class='btn btn-icon-only btn-default tooltips' onclick='create()' data-original-title='Create New'><i class='fa fa-plus'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PRINT\")' data-original-title='Print Out'><i class='fa fa-print'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PDF\")' data-original-title='Export to PDF'><i class='fa fa-files-o'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
    ");
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
    $('#'+sTableId+'_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
    $(".tooltips").tooltip({ delay: 50 });
}
<?php }else{ ?>
function formattingDatatableMasterThis(sTableId){
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').html("\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PRINT\")' data-original-title='Print Out'><i class='fa fa-print'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PDF\")' data-original-title='Export to PDF'><i class='fa fa-files-o'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
    ");
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
    $('#'+sTableId+'_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
    $(".tooltips").tooltip({ delay: 50 });
}
<?php } ?>

function updateStatus(id){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/updateStatus','id'=>'']); ?>'+id,
        type   : 'POST',
        data   : {},
        success: function (data) {
			if(data){
                $('#modal-produk-info').modal('hide');
				$('#table-produk').dataTable().fnClearTable();
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function printout(caraPrint){
    var search = $('.form-control.input-sm.input-small.input-inline').val();
	window.open("<?= yii\helpers\Url::toRoute('/ppic/produk/ProdukPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint+"&search="+search,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>