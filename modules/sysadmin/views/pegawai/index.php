<?php
/* @var $this yii\web\View */
$this->title = 'Pegawai';
app\assets\DatatableAsset::register($this);

$cari = Yii::$app->request->get('cari');
$departement_id = Yii::$app->request->get('departement_id');
$status = Yii::$app->request->get('status');

isset($cari) ? $cari = $cari : $cari = '';
isset($departement_id) ? $departement_id = $departement_id : $departement_id = 0;
isset($status) ? $status = $status : $status = 'all';

?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Pegawai'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
   

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/pegawai/index"); ?>"> <?= Yii::t('app', 'Pegawai'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/jabatan/index"); ?>"> <?= Yii::t('app', 'Jabatan'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/departement/index"); ?>"> <?= Yii::t('app', 'Departement'); ?> </a>
                    </li>
                </ul>
                <div class="row">

                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Pegawai'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <?php
                                    // CUSTOM FILTER
                                    ?>
                                    <div class="col-md-9" style="margin-left: -30px; margin-bottom: 20px;">
                                        <form name="filter" id="filter" method="get" action="index">
                                            <div class="col-md-3">
                                                <input type="text" name="cari" id="cari" class="form-control" placeholder="Cari ..." value="<?php echo $cari;?>">
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <select name="departement_id" id="departement_id" class="form-control">
                                                    <option value="0">Departement</option>
                                                    <?php
                                                    $sql = "select departement_id, departement_nama from m_departement order by departement_nama asc";
                                                    $departement_ids = Yii::$app->db->createCommand($sql)->queryAll();
                                                    foreach ($departement_ids as $key) {
                                                        if ($key['departement_id'] == $departement_id) {
                                                            $selected = "selected";
                                                        } else {
                                                            $selected = "";
                                                        }
                                                    ?>
                                                    <option value="<?php echo $key['departement_id'];?>" <?php echo $selected;?>><?php echo $key['departement_nama'];?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <select name="status" id="status" class="form-control">
                                                    <option value='all'>All</option>
                                                    <?php
                                                    if ($status == 'true') {
                                                        $selected1 = "selected";
                                                        $selected2 = "";
                                                    } else if ($status == 'false') {
                                                        $selected1 = "";
                                                        $selected2 = "selected";
                                                    } else {
                                                        $selected1 = "";
                                                        $selected2 = "";
                                                    }
                                                    ?>
                                                    <option value='true' <?php echo $selected1;?>>Active</option>
                                                    <option value='false' <?php echo $selected2;?>>Non Active</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <input type="submit" class="btn-primary form-control fa-input pull-left text-left" value="&#xf002; &nbsp; Cari">
                                            </div>
                                        </form>                                
                                    </div>
                                    <?php
                                    // EO CUSTOM FILTER
                                    ?> 
                                    <div class="col-md-3 btn-group text-right pull-right">
                                        <div class="col-sm-12 dataTables_moreaction visible-lg visible-md">
                                            <a class="btn btn-icon-only btn-default tooltips" onclick="create()" data-original-title="Create New"><i class="fa fa-plus"></i></a>
                                            <a class="btn btn-icon-only btn-default tooltips" onclick="printout('PRINT')" data-original-title="Print Out"><i class="fa fa-print"></i></a>
                                            <a class="btn btn-icon-only btn-default tooltips" onclick="printout('PDF')" data-original-title="Export to PDF"><i class="fa fa-files-o"></i></a>
                                            <a class="btn btn-icon-only btn-default tooltips" onclick="printout('EXCEL')" data-original-title="Export to Excel"><i class="fa fa-table"></i></a>
                                        </div>
                                    </div>

                                </div>

                                <style>
                                .dataTables_filter, .dataTables_info { display: none; }                                    
                                .fa-input { font-family: FontAwesome, 'Helvetica Neue', Helvetica, Arial, sans-serif; }
                                </style>

                                <div class="row " style="padding-right: 25px;">
                                    <div class="table-scrollable" class="border: solid 0px;">
                                        <table class="table table-striped table-bordered table-hover dataTable no-footer table-responsive" id="table-master" role="grid">
                                            <thead>
                                                <tr>
                                                    <th><?= Yii::t('app', 'ID Pegawai') ?></th>
                                                    <th><?= Yii::t('app', 'NIK') ?></th>
                                                    <th><?= Yii::t('app', 'Nama Pegawai') ?></th>
                                                    <th><?= Yii::t('app', 'Gender') ?></th>
                                                    <th><?= Yii::t('app', 'Departement') ?></th>
                                                    <th><?= Yii::t('app', 'Jabatan') ?></th>
                                                    <th><?= Yii::t('app', 'Status') ?></th>
                                                    <th style="width: 50px;"></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
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

", yii\web\View::POS_READY); ?>

<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({

        ajax: { 
            url: '<?= \yii\helpers\Url::toRoute('/sysadmin/pegawai/index') ?>',
            data: {dt: 'table-master', cari: '<?php echo $cari;?>', departement_id: '<?php echo $departement_id;?>', status: '<?php echo $status;?>' } 
        },
        bFilter: false,
        order: [[ 6, 'ASC' ]],
        dom: 'rtp',
        columnDefs: [
            {   targets: 0, }, //visible:0
            {	targets: 1,
                render: function (data, type, full, meta) {
                    return full[7];
                }
            },
            {	targets: 2,
                render: function (data, type, full, meta) {
                    return full[1];
                }
            },
            {	targets: 3,
                render: function (data, type, full, meta) {
                    return full[2];
                }    
            },
            {	targets: 4,
                render: function (data, type, full, meta) {
                    return full[3];
                }
            },
            {	targets: 5,
                render: function (data, type, full, meta) {
                    return full[4];
                }
            },
            
            {	targets: 6,
                orderable: false,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(full[5]){
                        ret = 'Active'
                    }else{
                        ret = '<span style="color:#B40404">Non-Active</span>'
                    }
                    return ret;
                }
            },
            {	targets: 7, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
            {   targets: 8, visible: false,
                render: function (data, type, full, meta) {
                    return full[6];
                }

            }
        ],

    });
}

function create(){
	openModal('<?= \yii\helpers\Url::toRoute('/sysadmin/pegawai/create') ?>','modal-master-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/sysadmin/pegawai/info','id'=>'']) ?>'+id,'modal-master-info');
}
function printout(caraprint){
    var cari = '<?php echo $cari;?>';
    var departement_id = '<?php echo $departement_id;?>';
    var status = '<?php echo $status;?>';        
	window.open("<?= yii\helpers\Url::toRoute('/sysadmin/pegawai/printout') ?>?&caraprint="+caraprint+"&cari="+cari+"&departement_id="+departement_id+"&status="+status,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>