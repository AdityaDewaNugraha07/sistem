<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<!-- <a class="btn btn-icon-only btn-default tooltips" onclick="create()" data-original-title="Create New" style="float: right; margin-right: 5px;"><i class="fa fa-plus"></i></a> -->
                <h4 class="modal-title"><?= Yii::t('app', 'Master Dokumen'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-dokumen">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Nomor Dokumen') ?></th>
									<th><?= Yii::t('app', 'Nama Dokumen') ?></th>
                                    <th><?= Yii::t('app', 'Revisi') ?></th>
									<th><?= Yii::t('app', 'Jenis Dokumen') ?></th>
									<th><?= Yii::t('app', 'Kategori Dokumen') ?></th>
									<th style="width: 50px;"></th>
								</tr>
							</thead>
						</table>
						</div>
                    </div>
                </div>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
	formconfig();
    dtMaster();
", yii\web\View::POS_READY); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-dokumen').dataTable({
        ajax: { url: '<?= isset($url)?$url:\yii\helpers\Url::toRoute('/qms/distribusidok/pick'); ?>',data:{dt: 'table-dokumen'} },
        order: [
            [2, 'asc']
        ],
        columnDefs: [
			{	targets: 0, visible: false, searchable: false },
            {	targets: 3, class:'text-align-center'},
            {	targets: 4, class:'text-align-center'},
            {	targets: 5, class:'text-align-center'},
            {	targets: 6, 
                orderable: false, searchable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return "<center><a onclick='pick(\""+full[0]+"\",\""+full[1]+"\",\""+full[2]+"\",\""+full[3]+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a><center>";
                }
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"autoWidth":false
    });
}

function create(){
	var url = '<?= \yii\helpers\Url::toRoute(['/qms/dokumen/create']); ?>';
	$(".modals-place-2-min").load(url, function() {
		$("#modal-master-create").modal('show');
		$("#modal-master-create").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function info(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/qms/dokumen/info','id'=>'']); ?>'+id;
	$(".modals-place-2-min").load(url, function() {
		$("#modal-master-info").modal('show');
		$("#modal-master-info").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
</script>