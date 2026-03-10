<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Purchase Order Customer'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Jenis Produk'); ?></th>
									<th><?= Yii::t('app', 'Tanggal PO'); ?></th>
									<th><?= Yii::t('app', 'Sales'); ?></th>
									<th><?= Yii::t('app', 'Sistem - Cara Bayar'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Kirim'); ?></th>
									<th><?= Yii::t('app', 'Customer'); ?></th>
									<th><?= Yii::t('app', 'Status'); ?></th>
									<th><?= Yii::t('app', 'Status Approval'); ?></th>
									<th style="width: 35px;"></th>
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
    dtAfterSave();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/marketing/purchaseorder/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 3, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 5, 
                render: function ( data, type, full, meta ) {
					return data+' - '+full[11];
                }
            },
			{ 	targets: 6, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {
                targets: 7,
                render: function ( data, type, full, meta ) {
					if(!full[8]){
						ret = data;
					} else {
						ret = full[8];
					}
					return '<center>'+ret+'</center>';
                }
            },
			{
                targets: 8, class: 'text-align-center',
                render: function ( data, type, full, meta ) {
					if(full[14]){
						var status = 'OPEN';
						var label = 'label label-info';
					} else {
						var status = 'CLOSED';
						var label = 'label label-danger';
					}
					var ret = '<a href="javascript:void(0);" onclick="setStatusPO('+full[0]+');" class="'+label+'" style="font-size: 1.0rem;">'+status+'</a>';
					return ret;
                }
            },
			{	targets: 9, class:'text-align-center',
				render: function ( data, type, full, meta ) {
					if(full[9]){
						var ret =  '<span class="label label-sm label-default"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>';
					}else{
						if(full[10] == 'APPROVED'){
							var ret =  '<span class="label label-success">'+full[10]+'</span>';
							// if(!full[12]){
							// 	ret += '<br><span class="td-kecil2">Dibutuhkan upload gambar/file PO</span>';
							// }
						}else if(full[10] == 'REJECTED'){
							var ret =  '<span class="label label-danger">'+full[10]+'</span>';
						} else {
							var ret =  '<span class="label label-warning">'+full[10]+'</span>';
						}
					}
					return ret;
                }
			},
			{	targets: 10, searchable:false,
				width: '75px',
				render: function ( data, type, full, meta ) {
					var display = "";
					// if(full[9] || full[13] || full[10] == 'REJECTED'){
					if(full[9] || full[10] !== 'Not Confirmed'){
						display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline grey tooltips" data-original-title="Edit""><i class="fa fa-edit"></i></a>';
					} else {
						display = '<a style="margin-left: -5px;" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>';
					}
					var ret =  '<center>\n\
									'+display+'\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
					return ret;
				}
			},
			{	targets: 11, visible: false},
			{	targets: 12, visible: false},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/index','po_ko_id'=>'']); ?>'+id);
}

function setStatusPO(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/setStatusPO','id'=>'']) ?>'+id;
	var modal_id = 'modal-status-po';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) { });
		$("#"+modal_id+" .modal-dialog").css('width',"40%");
	});
	return false;
}

</script>