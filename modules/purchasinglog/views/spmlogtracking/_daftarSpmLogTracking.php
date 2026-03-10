<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-daftarSpmLogTracking" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar SPM Log'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-daftarSpmLogTracking">
							<thead>
								<tr>
									<th></th>
									<th style="width: 100px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Tanggal'); ?></th>
									<th style="width: 140px;"><?= Yii::t('app', 'Jenis'); ?></th>
									<th style="width: 200px;"><?= Yii::t('app', 'Lokasi'); ?></th>
									<th style="width: 200px;"><?= Yii::t('app', 'Keterangan'); ?></th>
									<th style="width: 80px;">Edit</th>
									<th style="width: 80px;">Tracking</th>
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
    daftarSpmLogTracking();
", yii\web\View::POS_READY); ?>
<script>
function daftarSpmLogTracking(){
    var dt_table =  $('#table-daftarSpmLogTracking').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/spmlogtracking/daftarSpmLogTracking') ?>',data:{dt: 'modal-daftarSpmLogTracking'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1, class:"text-center"},
			{	targets: 2, class:"text-center",
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy HH:mm:ss');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 3, class:"text-left"},
            {	targets: 4, class:"text-left"},
            {	targets: 5, class:"text-left"},
            {	targets: 6, class:"text-center",
                render : function ( data, type, full, meta ) {
                    if (full[6] == null || full[6] == 0) {
                        var edit = '<a class="btn btn-xs btn-danger white-gallery btn-outline" onclick="editSpmLogTracking('+full[0]+')"><i class="fa fa-edit"></i></a>';
                    } else {
                        var edit = '';
                    }
                    return edit;
                }
            },
            {	targets: 7, class:"text-center",
                render : function ( data, type, full, meta ) {
                    var tracking = '<a class="btn btn-xs white-gallery btn-outline" onclick="openDetailTracking('+full[7]+')" title="Tracking"><i class="fa fa-random"></i></a>';
                    return tracking;
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function openDetailTracking(id) {
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/spmlog/openDetailTracking2','id'=>'']) ?>'+id,'modal-madul','80%');
}
</script>