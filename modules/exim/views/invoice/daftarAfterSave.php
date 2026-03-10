<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Invoice'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="width: 140px;"><?= Yii::t('app', 'Invoice No.'); ?></th>
									<th style="width: 120px;"><?= Yii::t('app', 'Kode PL'); ?></th>
									<th><?= Yii::t('app', 'Jenis Produk'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Customer'); ?></th>
									<th><?= Yii::t('app', 'Contract No.'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Payment<br>Status'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Process<br>Status'); ?></th>
									<th style="width: 120px;"></th>
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
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/exim/invoice/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 4, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 7, class:'text-align-center',
				render: function ( data, type, full, meta ) {
					if(data=="UNPAID"){
						return '<span class="label label-default" style="font-size: 11px; padding: 2px 3px;">UNPAID</span>'
					}else if(data=="PARTIALLY"){
						return '<span class="label label-warning" style="font-size: 11px; padding: 2px 3px;">PARTIALLY</span>'
					}else if(data=="PAID"){
						return '<span class="label label-success" style="font-size: 11px; padding: 2px 3px;">PAID</span>'
					}
				}
			},
			{	targets: 8, class:'text-align-center',
				render: function ( data, type, full, meta ) {
					if(data=="PROFORMA"){
						return '<span class="label label-warning" style="font-size: 11px; padding: 2px 3px;">PROFORMA</span>'
					}else if(data=="FINAL"){
						return '<span class="label label-success" style="font-size: 11px; padding: 2px 3px;">FINAL</span>'
					}else if(data=="CANCEL"){
						return '<span class="label label-danger" style="font-size: 11px; padding: 2px 3px;">CANCEL</span>'
					}
				}
			},
			{	targets: 9, class : "lineheight-0-2",
				render: function ( data, type, full, meta ) {
					var display = ""; display2label = "";
					if(full[9]){
						display =  'display:none;';
                        if(full[10] && full[11]){
                            var display2label = '<span style="font-size:1rem;" class="font-blue">'+full[10]+'<br>'+full[11]+'</span>&nbsp;';
                        }else{
                            var display2label = '<span style="font-size:1rem; cursor:pointer;" class="font-blue-steel" onclick="updateBL('+full[0]+')"><i class="icon-plus"></i> Input BL</span>&nbsp;';
                        }
					}
//					var ret =  '<center>\n\
//									'+display2label+'\n\
//									<a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Update" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
//									<a style="'+display+' margin-left: -5px;" class="btn btn-xs btn-outline red-flamingo tooltips" data-original-title="Delete" onclick="deleteInvoice('+full[0]+')"><i class="fa fa-trash-o"></i></a>\n\
//									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
//								</center>';
//					return ret;
                                        if(full[8]=='CANCEL'){
                                        
                                            var ret =  '<center>\n\
									'+display2label+'\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
                                        }else{
                                        
                                            var ret =  '<center>\n\
									'+display2label+'\n\
									<a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Update" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
									<a style="'+display+' margin-left: -5px;" class="btn btn-xs btn-outline red-flamingo tooltips" data-original-title="Delete" onclick="deleteInvoice('+full[0]+')"><i class="fa fa-trash-o"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
                                        }                                   				
                                        
					return ret;
				}
			},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"createdRow": function ( row, data, index ) {
            if(data[12]){
				$(row).addClass("cancelBackground");
			}
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
            if(aData[9]){
                $(nRow).attr("style","background-color: #f9ffe5;");
            }  
		},
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/exim/invoice/index','invoice_id'=>'']); ?>'+id);
}
function deleteInvoice(id){
	var url = "<?= yii\helpers\Url::toRoute("/exim/invoice/deleteInvoice") ?>?id="+id+"&tableid=table-aftersave";
	$(".modals-place-2").load(url, function() {
		$("#modal-delete-record .modal-dialog").css('width','50%');
		$("#modal-delete-record").modal('show');
		$("#modal-delete-record").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function updateBL(id){
	var url = "<?= yii\helpers\Url::toRoute("/exim/invoice/inputBL") ?>?id="+id;
	$(".modals-place-3").load(url, function() {
		$("#modal-input .modal-dialog").css('width','50%');
		$("#modal-input").modal('show');
		$("#modal-input").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

</script>