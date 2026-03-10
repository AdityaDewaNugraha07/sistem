<?php
/* @var $this yii\web\View */
$this->title = 'Catatan';

// ambil semua fungsi yang akan ditetapkan
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
                <ul class="nav nav-tabs pull-right">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/laporan/catatan"); ?>"> <?= Yii::t('app', $this->title); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/laporan/catatanCreate"); ?>"> <?= Yii::t('app', 'Tambah '.$this->title); ?> </a>
                    </li>
                    <li class="">
                        <a href="javascript:;" class="reload"> <i class="fa fa-refresh"></i> </a>
                    </li>
                    <li class="">
                        <a href="javascript:;" class="fullscreen"> <i class="fa fa-expand"></i>  </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">

                        <!-- BEGIN EXAMPLE TABLE PORTLET alias datatables -->
                        <div class="portlet light bordered">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-catatan" style="width: 1540px;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="width: 100px;"><?= Yii::t('app', 'Tanggal') ?></th>
                                            <th style="width: 100px;"><?= Yii::t('app', 'Judul') ?></th>
                                            <th style="width: 50px;">View</i></th>
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
dtCatatan();
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
function dtCatatan(){
    var dt_table =  $('#table-catatan').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/sysadmin/laporan/catatan') ?>',data:{dt: 'table-catatan'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            // isi columnDefs ditentukan dari controller function actionIndex baris $param['column']
            {   targets: 0, visible: false },
            {   targets: 1, align: 'center', 
                render: function ( data, type, full, meta ) {
                    var tanggals = full[1].split("-");
                    var tanggal = tanggals[2]+'-'+tanggals[1]+'-'+tanggals[0];
                    if ((full[2] != '') && (full[2] != null)) {
                        var tanggal_jam = tanggal+'<br>&nbsp;&nbsp;'+full[2];
                    } else {
                        var tanggal_jam = tanggal;
                    }
                    
                    return tanggal_jam;
                }
            },
            {   targets: 2,
                render: function ( data, type, full, meta ) {
                    return full[3];
                }
            },
            {   targets: 3,
                render: function ( data, type, full, meta ) {
                    return full[4].split(/\s+/).slice(0,10).join(" ");
                }
            },
            {   targets: 4,
                orderable: false,
                render: function ( data, type, full, meta ) {
                    //return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"create\" onclick=\"info('+full[0]+')\"><i class="fa fa-eye"></i></a></center>';
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
            /*{   targets: 3,
                render: function ( data, type, full, meta ) {
                    //
                    return '<div><img src=\"http://localhost/cis/web/uploads/catatan/'+full[3]+'\" class=\"text-center\" style=\"height: 20px; cursor: pointer; text-align: center; display: block; margin: auto;\" onclick=\"image('+full[0]+')\" alt=\"#\"></div>';
                }
            },*/            
        ],
        bAutoWidth: false,
        aoColumns: [{ "sWidth": "0%" }, { "sWidth": "5%" }, { "sWidth": "30%" }, { "sWidth": "60%" }, { "sWidth": "5%" }]
    });
}

// fungsi tombol create/tambah data
function create(){
    window.location = '<?= \yii\helpers\Url::toRoute('/sysadmin/laporan/catatanCreate') ?>'; 
}

// fungsi tombol info
function info(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/sysadmin/laporan/catatanInfo','id'=>'']) ?>'+id,'modal-catatan-info');
}

</script>
