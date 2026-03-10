<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Cari Voucher Penerimaan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Tipe'); ?></th>
									<th><?= Yii::t('app', 'Kode BBM'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Bayar'); ?></th>
									<th><?= Yii::t('app', 'Mata Uang'); ?></th>
									<th><?= Yii::t('app', 'Nominal'); ?></th>
									<th><?= Yii::t('app', 'Akun Kredit'); ?></th>
									<th><?= Yii::t('app', 'Cara Bayar'); ?></th>
									<th><?= Yii::t('app', 'Cara<br>Bayar Reff'); ?></th>
									<th>Lihat</th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/finance/voucherpenerimaan/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class:'td-kecil text-align-center' },
            {	targets: 2, class:'td-kecil text-align-center' },
            {	targets: 3, class:'td-kecil text-align-center' },
            {	targets: 4, class:'td-kecil text-align-center' },
            {	targets: 5, 
				class:'td-kecil text-align-right',
				render : function(data, type, full, meta){
					return formatNumberForUser(data);
				}
			},
            {	targets: 6, class:'td-kecil text-align-center' },
            {	targets: 7, class:'td-kecil text-align-center' },
            {	targets: 8, class:'td-kecil' },
            {	targets: 9, 
				class:'td-kecil',
                render: function ( data, type, full, meta ) {
					var tbl_edit = '';
                    var ret =  '<center><a class="btn btn-xs btn-outline dark" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>'+tbl_edit+'</center>';
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
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/finance/voucherpenerimaan/index','voucher_penerimaan_id'=>'']); ?>'+id);
}

</script>