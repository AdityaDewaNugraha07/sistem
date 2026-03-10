<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Ketidaksesuaian Order Penjualan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th rowspan="2"></th>
									<th rowspan="2"><?= Yii::t('app', 'Kode'); ?></th>
									<th rowspan="2"><?= Yii::t('app', 'Jenis Produk'); ?></th>
									<th rowspan="2"><?= Yii::t('app', 'Tanggal'); ?></th>
									<th rowspan="2"><?= Yii::t('app', 'Sales'); ?></th>
									<th rowspan="2"><?= Yii::t('app', 'Sistem Bayar'); ?></th>
									<th rowspan="2"><?= Yii::t('app', 'Tanggal Kirim'); ?></th>
									<th rowspan="2"><?= Yii::t('app', 'Customer'); ?></th>
									<th rowspan="2"><?= Yii::t('app', 'Status'); ?></th>
									<th rowspan="2"></th>
									<th rowspan="2" style="width: 35px;"></th>
									<th colspan="3" style="width: 35px;">Status Approval</th>
									<?php /* <th colspan="2" style="width: 35px;">13-14 Reason</th> */?>
								</tr>
								<tr>
									<th>Approval1</th>
									<th>Approval2</th>
									<th>Approval3</th>
									<?php /* <th>Reason</th>
									<th>Reason</th> */?>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/marketing/penerimaanjasa/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
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
			{ 	targets: 6, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 8,  searchable:false,
				render: function ( data, type, full, meta ) {
					/*if(full[11] != null || full[11] != ''){
						var ret = "<span class='font-yellow-gold'>"+full[11]+"</span> ";
						if(full[14] == "APPROVED"){
							ret = ret+' <i class="fa fa-check font-green-seagreen"></i>';
						}
					}else{
						var ret = "<span class='font-green-seagreen'>Allowed</span>";
					}*/

					if (full[11] != '') {
						var ret = "<span class='font-yellow-gold'>"+full[11]+"</span> ";
						if(full[14] == "APPROVED"){
							var ret = ret+' <i class="fa fa-check font-green-seagreen"></i>';
						} else {
							var ret = '';
						}
					} else {
						var ret = "<span class='font-green-seagreen'>Allowed</span>";
					}
					return ret;
                }
			},
			{	targets: 9, visible: false,  searchable:false, },
			{	targets: 10, searchable:false,
				width: '75px',
				render: function ( data, type, full, meta ) {
					var display = "";

					if (full[2] == "JasaKD") {
						display =  'visibility: display;';
					} else {
						if(full[9] || full[12]){
						//if(full[9] || full[12] || full[13] == 'APPROVED' || full[14] == 'APPROVED' || full[13] == 'REJECTED' || full[14] == 'REJECTED'){
							display =  'visibility: hidden;';
						}
					}

                    // Cendol Dawet Seger
                    //if(full[2] == "JasaKD"){
					// if(full[2] == "JasaKD" || full[2] == "JasaGesek" || full[2] == "JasaMoulding"){
                        //display =  '';
                    //}
					var ret =  '<center>\n\
									<a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
					return ret;

				}
			},
			{ targets: 11,
				render: function ( data, type, full, meta ) {
                    if(full[13]=='APPROVED'){
                        var ret = '<span class="label label-success" style="font-size:1.1rem;">'+full[13]+'</span>';
                    }else if(full[13]=='REJECTED'){
                        var ret = '<span class="label label-danger" style="font-size:1.1rem;">'+full[13]+'</span>';
                    }else if(full[13]=='Not Confirmed'){
                        var ret = '<span class="label label-default" style="font-size:1.1rem;">'+full[13]+'</span>';
                    } else {
						var ret = '';
					}
					return ret;
				}
			},
			{ targets: 12,
				render: function ( data, type, full, meta ) {
					if (full[14] != 'REJECTED') {
						if (full[13] == 'APPROVED') {
							var ret = '<span class="label label-success" style="font-size:1.1rem;">'+full[14]+'</span>';
						} else if (full[13] == 'Not Confirmed' || full[13] == '') {
							var ret = '<span class="label label-default" style="font-size:1.1rem;">Not Confirmed</span>';
						} else {
							var ret = '';
						}
					} else {
						var ret = '<span class="label label-danger" style="font-size:1.1rem;">REJECTED</span>';
					}
					return ret;
				}
			},
			{ targets: 13,
				render: function ( data, type, full, meta ) {
					if (full[15] != 'REJECTED') {
						if (full[14] == 'APPROVED') {
							var ret = '<span class="label label-success" style="font-size:1.1rem;">'+full[15]+'</span>';
						} else if (full[14] == 'Not Confirmed' || full[14] == '') {
							var ret = '<span class="label label-default" style="font-size:1.1rem;">Not Confirmed</span>';
						} else {
							var ret = '';
						}
					} else {
						var ret = '<span class="label label-danger" style="font-size:1.1rem;">REJECTED</span>';
					}
					return ret;
				}
			},
			/*{ targets: 13,
				render: function ( data, type, full, meta ) {
					return full[15];
				}
			},
			{ targets: 14,
				render: function ( data, type, full, meta ) {
				    return full[16];
				}
			},*/
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"createdRow": function ( row, data, index ) {
            if(data[9]){
				$(row).addClass("cancelBackground");
			}
        }
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/index','op_ko_id'=>'']); ?>'+id);
}

</script>