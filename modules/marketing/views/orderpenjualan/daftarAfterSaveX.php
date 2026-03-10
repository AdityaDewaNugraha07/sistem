<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersavex" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Order Penjualan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersavex">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Jenis Produk'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Sales'); ?></th>
									<th><?= Yii::t('app', 'Sistem Bayar'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Kirim'); ?></th>
									<th><?= Yii::t('app', 'Customer'); ?></th>
									<th><?= Yii::t('app', 'Status'); ?></th>
									<th></th>
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
    dtAfterSaveX();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSaveX(){
    var dt_table =  $('#table-aftersavex').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/marketing/orderpenjualan/daftarAfterSaveX') ?>',data:{dt: 'modal-aftersavex'} },
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
					if(full[11]){
						var ret = "<span class='font-yellow-gold'>"+full[11]+"</span> ";
						if(full[12] == "APPROVED"){
							ret = ret+' <i class="fa fa-check font-green-seagreen"></i>';
						}
					}else{
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
//					if(full[9] || full[10]){
					if(full[9] || full[13]){
						display =  'visibility: hidden;';
					}
                    
                    // Cendol Dawet Seger
                    if(full[2] == "JasaKD"){
//                    if(full[2] == "JasaKD" || full[2] == "JasaGesek" || full[2] == "JasaMoulding"){
                        display =  '';
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
		"createdRow": function ( row, data, index ) {
            if(data[9]){
				$(row).addClass("cancelBackground");
			}
        }
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/marketing/orderpenjualan/index','op_ko_id'=>'']); ?>'+id);
}

</script>