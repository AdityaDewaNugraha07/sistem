<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Permintaan Barang Jadi'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
                                    <th><?= Yii::t('app', 'Keperluan'); ?></th>
									<th><?= Yii::t('app', 'Status'); ?></th>
									<?php /*<th><?= Yii::t('app', 'Prepared By'); ?></th>
                                    <th></th>
									<th><?= Yii::t('app', 'Approved By'); ?></th>
									<th class="td-kecil">Acknowledge By</th>
									<th></th>*/?>
                                    <th><?= Yii::t('app', 'Status Approval'); ?></th>
                                    <th></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/pengajuanrepacking/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            { 	targets: 1, class:"text-align-center", },
			{ 	targets: 2, class:"text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            { 	targets: 3, class:"text-align-center", },
            { 	targets: 4, class :"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					var ret = '';
                    if(data=='SEDANG DIAJUKAN'){
                        ret = '<label class="label label-default" style="font-size:1.1rem;">SEDANG DIAJUKAN</label>';
                    }else if(data=='MUTASI INPROGRESS'){
                        ret = '<label class="label label-warning" style="font-size:1.1rem;">MUTASI INPROGRESS</label>';
                    }else if(data=='MUTASI COMPLETE'){
                        ret = '<label class="label label-success" style="font-size:1.1rem;">MUTASI COMPLETE</label>';
                    }
					return ret;
                }
            }, 
            /*{ 	targets: 5, class :"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					var status = full[6];
					if(status=="APPROVED"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="REJECTED"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					return "<span style='font-size:1rem;'><b>"+full[5]+"</b></span><br>"+status+"";
                }
            }, 
            { 	targets: 6, visible:false },
            { 	targets: 7, class :"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					var status = full[8];
					if(status=="APPROVED"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="REJECTED"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					return "<span style='font-size:1rem;'><b>"+full[7]+"</b></span><br>"+status+"";
                }
            }, 
            { 	targets: 8, class :"text-align-center td-kecil", 
                render: function ( data, type, full, meta ) {
					var status = full[10];
					if(status=="APPROVED"){
						return "<span style='font-size:1rem;'><b>"+full[9]+"</b></span><br><span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="REJECTED"){
						return "<span style='font-size:1rem;'><b>"+full[9]+"</b></span><br><span class='font-red-flamingo'>"+status+"</span>";
                    } else if (status=="Not Confirmed") {
                        return "<span style='font-size:1rem;'><b>"+full[9]+"</b></span><br><span>"+status+"</span>";
					} else {
                        return '';
                    }
                }
            },
            { 	targets: 9, visible:false },
			{	targets: 10, searchable:false,
				render: function ( data, type, full, meta ) {
					var display = "";
					if(full[6] != 'Not Confirmed' || full[8] != 'Not Confirmed'){
						display =  'visibility: hidden;';
					}
					var ret =  '<center>\n\
									<a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
					return ret;
				}
			},*/
            { 	targets: 5, class :"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					var ret = '';
                    if(data=='Not Confirmed'){
                        ret = '<label class="label label-default" style="font-size:1.1rem;">Not Confirmed</label>';
                    }else if(data=='APPROVED'){
                        ret = '<label class="label label-success" style="font-size:1.1rem;">APPROVED</label>';
                    }else if(data=='REJECTED'){
                        ret = '<label class="label label-warning" style="font-size:1.1rem;">REJECTED</label>';
                    }
					return ret;
                }
            },
            {	targets: 6, searchable:false,
				render: function ( data, type, full, meta ) {
					var display = "";
                    if (full[5] == null && full[6] == null && full[7] == null) {
                        display =  'visibility: hidden;';
                    } else {
                        if(full[6] == null && full[7] == null){
                            display =  '';
                        } else {
                            display =  'visibility: hidden;';
                        }
                    }
					var ret =  '<center>\n\
									<a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
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
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/index','pengajuan_repacking_id'=>'']); ?>'+id);
}

</script>