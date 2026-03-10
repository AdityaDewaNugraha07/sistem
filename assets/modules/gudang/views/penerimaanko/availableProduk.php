<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Ketersediaan Stock Produk'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-laporan">
							<thead>
								<tr>
									<th>Pick</th>
									<th></th>
									<th><?= Yii::t('app', 'Nama Produk'); ?></th>
									<th><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
									<th><?= Yii::t('app', 'Tanggal<br>Produksi'); ?></th>
									<th><?= Yii::t('app', 'Satuan<br>Besar'); ?></th>
									<th><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
									<th></th>
									<th><?= Yii::t('app', 'Kubikasi'); ?></th>
									<th><?= Yii::t('app', 'Lokasi<br>Gudang'); ?></th>
									<th>Detail<br>Produk</th>
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
    dtInfo();
", yii\web\View::POS_READY); ?>
<script>
function dtInfo(){
    var dt_table =  $('#table-laporan').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/gudang/penerimaanko/availableProduk') ?>',data:{dt: 'modal-info'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
//            {	targets: 0,
//				class: "text-align-center",
//				orderable : false, 
//				render: function ( data, type, full, meta ) {
//					return "<a onclick='pick("+data+")' class='btn btn-xs btn-outline blue'><i class='fa fa-plus-circle'></i></a>";
//                }
//			},
			{	targets: 0, visible: false },
			{	targets: 1, visible: false },
			{ 	targets: 3, 
				searchable: false, 
                render: function ( data, type, full, meta ) {
					return "<a onclick='pick(\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+full[3];
                }
            },
			{ 	targets: 4, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 5, 
				class: "text-align-right",
				searchable: false, 
                render: function ( data, type, full, meta ) {
					return full[5]+" Pallet";
                }
            },
			{ 	targets: 6, 
				class: "text-align-right",
				searchable: false, 
                render: function ( data, type, full, meta ) {
					return full[6]+" "+full[7];
                }
            },
			{	targets: 7, visible: false, searchable: false },
			{ 	targets: 8, 
				searchable: false,
				class: "text-align-right",
                render: function ( data, type, full, meta ) {
					return full[8]+" m<sup>3</sup>";
                }
            },
			{ 	targets: 9, 
                render: function ( data, type, full, meta ) {
					return "<center>"+full[9]+"</center>";
                }
            },
			{	targets: 10, 
				width: '50px',
				render: function ( data, type, full, meta ) {
					var ret =  '<center><a class="btn btn-xs btn-outline dark" onclick="infoProduk('+full[1]+')"><i class="fa fa-eye"></i></a></center>';
					return ret;
				}
			}
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/index','tbko_id'=>'']); ?>'+id);
}
function infoProduk(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/ppic/produk/info','id'=>'']); ?>'+id+"&disableAction=1";
	$(".modals-place-2").load(url, function() {
		$("#modal-produk-info").modal('show');
		$("#modal-produk-info").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

</script>