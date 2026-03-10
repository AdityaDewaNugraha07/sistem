<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Video Training';
app\assets\DatatableAsset::register($this);

?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->

<div class="row">
    <div class="col-md-12">
        <?= Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs pull-right">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/hrd/videotraining/index"); ?>"> <?= Yii::t('app', $this->title); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/hrd/videotraining/create"); ?>"> <?= Yii::t('app', 'Tambah '.$this->title); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET alias datatables -->
                        <div class="portlet light bordered">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-training" style="width: auto;">
                                    <thead>
                                        <tr>
                                            <th><?= Yii::t('app', 'Periode') ?></th>
                                            <th><?= Yii::t('app', 'Judul') ?></th>
                                            <th><?= Yii::t('app', 'Video') ?></th>
                                            <th><?= Yii::t('app', 'Link Evaluasi Peserta') ?></th>
                                            <th><?= Yii::t('app', 'Link Evaluasi Atasan') ?></th>
                                            <th>Actions</th>
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

<?php // registrasikan fungsi javascript yang akan digunakan, pisahkan masing2 fungsi javascript yang akan didaftarkan dengan tandan titik koma (;) ?>
<?php
$this->registerJs("
dtTraining();
",yii\web\View::POS_READY);

$this->registerCss("
td {
    vertical-align: middle !important;
}
");
?>

<script>
    function tanggal(tanggal) {
        return new Intl.DateTimeFormat('id-ID', { dateStyle: 'long' }).format(new Date(tanggal))
    }

    function dtTraining(){
        $('#table-training').DataTable({
            ajax: { url: '<?= Url::toRoute('/hrd/videotraining/index') ?>',data:{dt: 'table-training'} },
            order: [
                [0, 'desc']
            ],
            dom: "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            columnDefs: [
                // isi columnDefs ditentukan dari controller function actionIndex baris $param['column']
                {   targets: 0,
                    class: 'text-center',
                    render: function ( data, type, full, meta ) {
                        return tanggal(full[1]) + ' - ' + tanggal(full[2]);
                    }
                },
                {   targets: 1,
                    render: function ( data, type, full, meta ) {
                        return full[3];
                    }
                },
                {
                    targets: 2,
                    orderable: false,
                    class: 'text-center',
                    render: function (data, type, full, meta) {
                        return full[5] && JSON.parse(full[5]).map((row, i) => `<a href="${row.url}" target="_blank" class="btn btn-default btn-xs">video ${i + 1}</a>`).join(' ');
                    }
                },
                {
                    targets: 3,
                    orderable: false,
                    class: 'text-center',
                    render: function (data, type, full, meta) {
                        return full[7] && JSON.parse(full[7]).map((row, i) => `<a href="${row.url}" target="_blank" class="btn btn-default btn-xs">link ${i + 1}</a>`).join(' ')
                    }
                },
                {
                    targets: 4,
                    orderable: false,
                    class: 'text-center',
                    render: function (data, type, full, meta) {
                        return full[8] && JSON.parse(full[8]).map((row, i) => `<a href="${row.url}" target="_blank" class="btn btn-default btn-xs">link ${i + 1}</a>`).join(' ')
                    }
                },
                {   targets: 5,
                    orderable: false,
                    class: 'text-center',
                    render: function ( data, type, full, meta ) {
                        return `<div class="btn-group" role="group" style="position:unset; margin-top: 0">
                                    <a class="btn btn-xs blue-hoki btn-outline tooltips"
                                        href="javascript:void(0)"
                                        onclick="infoVideoTraining('${full[0]}')" title="Info">
                                        <i class="fa fa-info-circle"></i>
                                    </a>
                                    <a class="btn btn-xs green-haze btn-outline tooltips"
                                        href="javascript:void(0)"
                                        onclick="tambahPeserta('${full[0]}')" title="Tambah Peserta">
                                        <i class="fa fa-plus-square"></i>
                                    </a>
                                    <a class="btn btn-xs yellow-crusta btn-outline tooltips"
                                        href="<?= Url::toRoute('/hrd/videotraining/edit?video_training_id=') ?>${full[0]}" title="Edit">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    <a class="btn btn-xs red-sunglo btn-outline tooltips"
                                        href="<?= Url::toRoute('/hrd/videotraining/hapus?video_training_id=') ?>${full[0]}"
                                        title="Hapus"
                                        onclick="return confirm('Data akan dihapus. Apakah anda yakin?')">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </div>
                                `;
                    }
                },
            ],
            // bAutoWidth: false,
            // aoColumns: [
            //     { "sWidth": "15%" },
            //     { "sWidth": "45%" },
            //     { "sWidth": "45%" },
            //     { "sWidth": "10%" }
            // ]
        });
        // $('.dataTables_moreaction').hide();
    }

    // fungsi tombol create/tambah data
    function create(){
        window.location = '<?= Url::toRoute('/hrd/videotraining/create') ?>';
    }

    // fungsi tombol tambahPeserta
    function tambahPeserta(id){
        openModal('<?= Url::toRoute(['/hrd/videotraining/tambahpeserta','id'=>'']) ?>'+id,'modal-tambah-peserta','95%');
    }

    function infoVideoTraining(id){
        openModal('<?= Url::toRoute(['/hrd/videotraining/info','video_training_id'=>'']) ?>'+id,'modal-info','90%');
    }
</script>
