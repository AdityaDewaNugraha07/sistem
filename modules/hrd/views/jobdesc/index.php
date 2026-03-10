<?php
/* @var $this yii\web\View */
$this->title = 'Jobdesc';

use yii\helpers\Url;
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET alias datatables -->
                        <div class="portlet light bordered">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-jobdesc"><!--  style="width: 1540px;" -->
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Nama Pegawai') ?></th>
                                            <th><?= Yii::t('app', 'Departement') ?></th>
                                            <th><?= Yii::t('app', 'Nama File') ?></th>
                                            <th><?= Yii::t('app', 'View') ?></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET alias datatables -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<? // registrasikan fungsi javascript yang akan digunakan, pisahkan masing2 fungsi javascript yang akan didaftarkan dengan tandan titik koma (;) ?>
<?php 
$this->registerJs("
dtJobdesc();
",yii\web\View::POS_READY);
?>

<script>
function nl2br (str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
function dtJobdesc(){
    var dt_table =  $('#table-jobdesc').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/hrd/jobdesc/index') ?>',data:{dt: 'table-jobdesc'} },
        order: [
            [1, 'asc']
        ],
        columnDefs: [
            {   targets: 0, visible: false },
            {   targets: 1,
                render: function ( data, type, full, meta ) {
                    return full[1];
                }
            },
            {   targets: 2,
                orderData: [3],
                render: function ( data, type, full, meta ) {
                    return full[3];
                }
            },
            {   targets: 3,
                orderData: [2],
                render: function ( data, type, full, meta ) {
                    return full[2];
                }
            },
            {   targets: 4,
                orderable: false,
                render: function ( data, type, full, meta ) {
                    if(full[0]){
                        var btn_info    = '<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="info('+full[0]+')" title="Info"><i class="fa fa-info-circle"></i></a>';
                        var btn_tambah  = '<a class="btn btn-xs green-haze btn-outline tooltips" title="Tambah Jobdesc"><i class="fa fa-plus-square"></i></a>';
                        var btn_edit    = '<a class="btn btn-xs yellow-crusta btn-outline tooltips" onclick="edit('+full[0]+')" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                        var btn_hapus   = '<a class="btn btn-xs red-sunglo btn-outline tooltips" title="Hapus" onclick="hapusData('+full[0]+')"><i class="fa fa-trash-o"></i></a>';
                    } else {
                        var btn_info    = '<a class="btn btn-xs blue-hoki btn-outline tooltips" title="Info"><i class="fa fa-info-circle"></i></a>';
                        var btn_tambah  = '<a class="btn btn-xs green-haze btn-outline tooltips" href="javascript:void(0)" onclick="tambahJobdesc('+full[4]+')" title="Tambah Jobdesc"><i class="fa fa-plus-square"></i></a>';
                        var btn_edit    = '<a class="btn btn-xs yellow-crusta btn-outline tooltips" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                        var btn_hapus   = '<a class="btn btn-xs red-sunglo btn-outline tooltips" title="Hapus"><i class="fa fa-trash-o"></i></a>';
                    }
                    return '<center><div class="btn-group" role="group" style="position:unset; margin-top: 0">'+ btn_info + btn_tambah + btn_edit + btn_hapus + '</div></center>';
                }
            },   
        ],
        bAutoWidth: false,
        aoColumns: [{ "sWidth": "0%" }, { "sWidth": "20%" },{ "sWidth": "20%" }, { "sWidth": "45%" }, { "sWidth": "15%" }],
        dom: "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
    $('.dataTables_moreaction').hide();
}

function create(){
    openModal('<?= \yii\helpers\Url::toRoute('/hrd/jobdesc/create') ?>','modal-jobdesc-create', '80%');
}

function info(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/hrd/jobdesc/info','id'=>'']) ?>'+id,'modal-jobdesc-info','95%');
}

function tambahJobdesc(id){
    openModal('<?= Url::toRoute(['/hrd/jobdesc/tambahJobdesc','id'=>'']) ?>'+id,'modal-jobdesc-create','80%');
}

function edit(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/hrd/jobdesc/edit', 'id'=>'']) ?>'+id, 'modal-jobdesc-edit', '80%');
}

function hapusData(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/hrd/jobdesc/delete', 'tableid'=>'table-jobdesc', 'id'=>'']) ?>'+id, 'modal-delete-record');
}
</script>
