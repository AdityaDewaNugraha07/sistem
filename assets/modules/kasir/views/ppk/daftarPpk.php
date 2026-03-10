<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-daftar-ppk" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar PPK yang telah dilakukan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-ppk">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Tipe'); ?></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Nominal'); ?></th>
									<th><?= Yii::t('app', 'Tgl Diperlukan'); ?></th>
									<th><?= Yii::t('app', 'Voucher'); ?></th>
									<th><?= Yii::t('app', 'Terima Uang'); ?></th>
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
<?php $this->registerJs("
    dtPpk();
", yii\web\View::POS_READY); ?>
<script>
function dtPpk(){
    var dt_table =  $('#table-ppk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/kasir/ppk/daftarPpk') ?>',data:{dt: 'table-ppk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 2, 
                class: 'text-align-center',
            },
			{	targets: 4,
                class: 'text-align-right',
				render: function ( data, type, full, meta ) {
                    return formatNumberForUser(data);
                },
            },
			{	targets: 6,
                class: 'text-align-center',
				render: function ( data, type, full, meta ) {
                    if(data){
						if(data=='UNPAID'){
							return '<span class="label label-sm label-warning">UNPAID</span>';
						}else if(data=='PAID'){
							return '<span class="label label-sm label-success">PAID</span>';
						}
						return data;
					}else{
						return '-';
					}
                },
            },
			{	targets: 7,
                class: 'text-align-center',
				render: function ( data, type, full, meta ) {
                    if(data==true){
						return '<span class="label label-sm label-success">Telah Diterima</span>';
					}else{
						if(full[6] == "PAID"){
							return '<center><a class="btn btn-xs btn-outline blue-hoki" onclick="terimaTopUp('+full[0]+')"><i class="fa fa-download"></i>Terima Uang</a></center>';
						}else{
							return '-';
						}
						return '-';
					}
                },
            },
            {	targets: 8, 
                width: '50px',
				orderable: false,
				searchable: false,
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat detail PPK" onclick="lihatPpk('+full[0]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret;
                }
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatPpk(ppk_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/kasir/ppk/index','ppk_id'=>'']); ?>'+ppk_id);
}
function terimaTopUp(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/ppk/terimaTopup','id'=>'']); ?>'+id;
	$(".modals-place-confirm").load(url, function() {
		$("#modal-transaksi").modal('show');
		$("#modal-transaksi").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}
</script>