<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-daftar-bkk" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar BKK yang telah dilakukan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-bkk">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Tipe'); ?></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Penerima'); ?></th>
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
    dtBkk();
", yii\web\View::POS_READY); ?>
<script>
function dtBkk(){
    var dt_table =  $('#table-bkk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/kasir/bkk/daftarBkk') ?>',data:{dt: 'table-bkk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 2, 
                class: 'text-align-center',
            },
            {	targets: 5,
				class: 'text-align-right',
                render: function ( data, type, full, meta ) {
                    return formatNumberForUser(data);
                }
            },
            {	targets: 6, 
                width: '50px',
				orderable: false,
				searchable: false,
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat detail PPK" onclick="lihatBkk('+full[0]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret;
                }
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatBkk(bkk_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/kasir/bkk/index','bkk_id'=>'']); ?>'+bkk_id);
}
</script>