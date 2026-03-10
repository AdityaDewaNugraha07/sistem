<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<a class="btn btn-icon-only btn-default tooltips" onclick="create()" data-original-title="Create New" style="float: right; margin-right: 5px;"><i class="fa fa-plus"></i></a>
                <h4 class="modal-title"><?= Yii::t('app', 'Master Customer'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-customer">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode') ?></th>
									<th><?= Yii::t('app', 'Atas Nama') ?></th>
									<th><?= Yii::t('app', 'Perusahaan') ?></th>
									<th><?= Yii::t('app', 'Alamat') ?></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Max Plafond') ?></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Piutang Aktif') ?></th>
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
    var dt_table =  $('#table-customer').dataTable({
        ajax: { url: '<?= isset($url)?$url:\yii\helpers\Url::toRoute('/marketing/customer/masterOnModal'); ?>',data:{dt: 'table-customer'} },
        order: [
            [2, 'asc']
        ],
        columnDefs: [
			{	targets: 0, visible: false, searchable: false },
			{	targets: 1, visible: false, searchable: false },
            {	targets: 2,
                render: function ( data, type, full, meta ) {
					var ret = '<i>Perorangan</i>';
					var par = full[2];
                    if(full[3]){
                        par = full[2]+' - '+full[3];
                    }
                    return "<a onclick='pick(\""+full[0]+"\",\""+par+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+full[2];
                }
            },
            {	targets: 3,
                render: function ( data, type, full, meta ) {
                    var ret = '<i>Perorangan</i>';
                    if(data){
                        ret = data;
                    }
                    return ret;
                }
            },
			{	targets: 5, class: "text-align-right", searchable: false,
                render: function ( data, type, full, meta ) {
                    return formatNumberForUser(data);
                }
            },
            {	targets: 6, class: "text-align-right", searchable: false,
                render: function ( data, type, full, meta ) {
                    return formatNumberForUser(data);
                }
            },
            {	targets: 7, 
                orderable: false, searchable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"autoWidth":false
    });
}

function create(){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/customer/create']); ?>';
	$(".modals-place-2-min").load(url, function() {
		$("#modal-customer-create").modal('show');
		$("#modal-customer-create").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function info(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/customer/info','id'=>'']); ?>'+id;
	$(".modals-place-2-min").load(url, function() {
		$("#modal-customer-info").modal('show');
		$("#modal-customer-info").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
</script>