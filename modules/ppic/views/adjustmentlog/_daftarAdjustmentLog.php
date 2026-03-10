<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-daftarAdjustmentLog" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Adjustment Log'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right text-danger" style="position: absolute; right: 20px; z-index: 9999; top: 12px; font-weight: bold;" id="msg"></div>
                        <table class="table table-striped table-bordered table-hover" id="table-daftarAdjustmentLog">
							<thead>
								<tr>
									<th></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th class="td-kecil" style="width: 70px;"><?= Yii::t('app', 'Tanggal'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'Kode Keputusan'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'Kode Loglist'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'Kode SPK Shipping'); ?></th>
									<th class="td-kecil" style="width: 70px;"><?= Yii::t('app', 'Jumlah<br>Pcs<br>Loglist'); ?></th>
									<th class="td-kecil" style="width: 70px;"><?= Yii::t('app', 'Jumlah<br>Volume<br>Loglist'); ?></th>
									<th class="td-kecil" style="width: 70px;"><?= Yii::t('app', 'Jumlah<br>Pcs<br>Terima'); ?></th>
									<th class="td-kecil" style="width: 70px;"><?= Yii::t('app', 'Jumlah<br>Volume<br>Terima'); ?></th>
									<th class="td-kecil" style="width: 250px;"><?= Yii::t('app', 'Uraian'); ?></th>
                                    <th class="td-kecil" style="width: 100px;"></th>
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
    daftarAdjustmentLogs();
", yii\web\View::POS_READY); ?>
<script>
function daftarAdjustmentLogs(){
    var dt_table =  $('#table-daftarAdjustmentLog').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/adjustmentlog/daftarAdjustmentLog') ?>',data:{dt: 'modal-daftarAdjustmentLog'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1, class:"td-kecil text-center",
                render: function ( data, type, full, meta ) {
					return '<center>'+full[1]+'</center>';
                }
            },
            {	targets: 2, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 3, class:"td-kecil text-center",},
            {	targets: 4, class:"td-kecil text-center",},
            {	targets: 5, class:"td-kecil text-center",},
            {	targets: 6, class:"td-kecil text-right",},
            {	targets: 7, class:"td-kecil text-right",},
            {	targets: 8, class:"td-kecil text-right",},
            {	targets: 9, class:"td-kecil text-right",},
            {	targets: 10, class:"td-kecil text-left",},
            {	targets: 11, class:"td-kecil text-center",
                render: function ( data, type, full, meta) {
                    var adjustment_log_id = full[0];
                    var view =  '<a class="btn btn-xs btn-default tooltips" style="margin-right: 0px;" data-original-title="VIEW" onclick="confirmView('+adjustment_log_id+')"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    var edit =  '<a class="btn btn-xs btn-outline btn-warning tooltips" style="margin-right: 0px;" data-original-title="EDIT" onclick="confirmEdit('+adjustment_log_id+')"><i class="fa fa-edit"></i></a>';                        
                    var batal =  '<a class="btn btn-xs btn-outline btn-danger tooltips" style="margin-right: 0px;" data-original-title="ABORT" onclick="confirmBatal('+adjustment_log_id+')"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>';
                    var hapus =  '<a class="btn btn-xs btn-outline btn-danger tooltips" style="margin-right: 0px;" data-original-title="DELETE" onclick="confirmHapus('+adjustment_log_id+')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
                    if (full[11] == "Not Confirmed") {    
                        var ret = edit+" "+hapus;
                    } else if (full[11] == "PENDING") {
                        var ret = batal;
                    } else if (full[11] == "APPROVED" || full[11] == "REJECT") {
                    } else {
                        var ret = "";
                    }
                    return view+' '+ret;
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function confirmView(id) {
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/adjustmentlog/index','adjustment_log_id'=>'']); ?>'+id+'&view=1');
}

function confirmEdit(id) {
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/adjustmentlog/index','adjustment_log_id'=>'']); ?>'+id+'&edit=1');
}

function confirmBatal(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/adjustmentlog/confirmBatal','id'=>'']) ?>'+id,'modal-confirm','250px');
}

function confirmHapus(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/adjustmentlog/confirmHapus','id'=>'']) ?>'+id,'modal-confirm','250px');
}

</script>
