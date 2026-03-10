<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-daftar-setortunai" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Histori Setor Tunai Kas Besar'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-setortunai">
							<thead>
								<tr>
									<th></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Reff. No BCA'); ?></th>
									<th><?= Yii::t('app', 'No. Seri Dok Angkut'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Nominal'); ?></th>
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
    var dt_table =  $('#table-setortunai').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/kasir/setorbank/daftarAftersave') ?>',data:{dt: 'table-setortunai'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1, 
                class: 'text-align-center',
            },
			{	targets: 2, 
                class: 'text-align-center',
            },
			{	targets: 5,
                class: 'text-align-right',
				render: function ( data, type, full, meta ) {
                    return formatNumberForUser(data);
                },
            },
            {	targets: 6, 
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
function lihatPpk(kas_besar_setor_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/kasir/setorbank/index','kas_besar_setor_id'=>'']); ?>'+kas_besar_setor_id);
}
</script>