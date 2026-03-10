<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar SPK Sawmill Yang Telah Dibuat'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Revisi Ke'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Mulai'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Selesai'); ?></th>
                                    <th><?= Yii::t('app', 'Kode PO'); ?></th>
									<th><?= Yii::t('app', 'Peruntukan'); ?></th>
									<th><?= Yii::t('app', 'Line Sawmill'); ?></th>
									<th><?= Yii::t('app', 'Status SPK'); ?></th>
									<th><?= Yii::t('app', 'Status Approval'); ?></th>
									<th><?= Yii::t('app', 'Prepared By'); ?></th>
									<th style="width: 80px;"></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/spksawmill/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            { 	targets: 1, class:'text-align-center td-kecil'},
            { 	targets: 2, class:'text-align-center td-kecil'},
			{ 	targets: 3, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            { 	targets: 4, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            { 	targets: 5, class:'td-kecil'},
            { 	targets: 6, class:'text-align-center td-kecil'},
            { 	targets: 7, class:'text-align-center td-kecil'},
            { 	targets: 8, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					if(full[8]){
						var status = 'OPEN';
						var label = 'label label-info';
					} else {
						var status = 'CLOSED';
						var label = 'label label-danger';
					}
                    if(!full[11] && full[9] == 'APPROVED'){ // jika tidak cancel & status approved, baru bisa diklik/diubah statusnya
                        var ret = '<a href="javascript:void(0);" onclick="setStatus('+full[0]+');" class="'+label+'" style="font-size: 1.0rem;">'+status+'</a>';
                    } else {
                        var ret = '<a href="javascript:void(0);" class="'+label+'" style="font-size: 1.0rem; cursor: default;">'+status+'</a>';
                    }
					return ret;
                }
            },     
            { 	targets: 9, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					if(full[9]=='APPROVED'){
                        ret = '<span class="label label-sm label-success">'+full[9]+'</span>';
                    }else if(full[9]=='REJECTED'){
                        ret = '<span class="label label-sm label-danger">'+full[9]+'</span>';
                    }else if(full[9]=='Not Confirmed'){
                        ret = '<span class="label label-sm label-default">'+full[9]+'</span>';
                    }else if(full[9]=='ABORTED'){
                        ret = '<span class="label label-sm label-danger">'+full[9]+'</span>';
                    }
					return ret;
                }
            },    
            { 	targets: 10, class:'text-align-center td-kecil'},
            { 	targets: 11, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					var display = ""; 
                    if((full[9]=="<?= app\models\TApproval::STATUS_REJECTED ?>") || (full[9]=="<?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?>") ) {
						display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline grey tooltips" data-original-title="Edit"><i class="fa fa-edit"></i></a>';
					}else{
                        display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>';
                    }
					var ret =  '<center>\n\
									'+display+'\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
					return ret;
                }
            },    
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/spksawmill/index','spk_sawmill_id'=>'']); ?>'+id);
}

function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/spksawmill/index','spk_sawmill_id'=>'']); ?>'+id+'&edit=1');
}

function setStatus(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/ppic/spksawmill/setStatus','id'=>'']) ?>'+id;
	var modal_id = 'modal-status-spk';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) { });
		$("#"+modal_id+" .modal-dialog").css('width',"40%");
	});
	return false;
}

</script>