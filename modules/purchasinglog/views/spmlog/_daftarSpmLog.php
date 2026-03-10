<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-daftarSpmLog" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar SPM Log'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-daftarSpmLog">
							<thead>
								<tr>
									<th></th>
									<th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'Tanggal'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'ETD'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'ETD Logpond'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'ETA'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'Nama Tongkang'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'Lokasi Muat'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'Est. Pcs'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'Est. Volume'); ?></th>
									<th class="td-kecil" style="width: 180px;"><?= Yii::t('app', 'PIC'); ?></th>
									<th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'Status'); ?></th>
									<th class="td-kecil" style="width: 100px;">View</th>
									<th class="td-kecil" style="width: 100px;">Tracking</th>
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
    daftarSpmLogs();
", yii\web\View::POS_READY); ?>
<script>
function daftarSpmLogs(){
    var dt_table =  $('#table-daftarSpmLog').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/spmlog/daftarSpmLog') ?>',data:{dt: 'modal-daftarSpmLog'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1, class:"td-kecil"},
			{	targets: 2, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 3, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 4, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 5, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 6, class:"td-kecil"},
            {	targets: 7, class:"td-kecil"},
            {	targets: 8, class:"td-kecil text-right"},
            {	targets: 9, class:"td-kecil text-right"},
            {	targets: 10, class:"td-kecil"},
            {	targets: 11, class:"td-kecil"},
            {	targets: 12, class:"td-kecil text-center",
                render : function ( data, type, full, meta ) {
                    var status = full[11];
                    if (status == 'Not Confirmed') {
                        var view = '<a class="btn btn-xs white-gallery btn-outline" onclick="viewSpmLog('+full[0]+')" title="View"><i class="fa fa-eye"></i></a> &nbsp; <a class="btn btn-xs btn-danger white-gallery btn-outline" onclick="editSpmLog('+full[0]+')"><i class="fa fa-edit"></i></a>';
                    } else {
                        var view = '<a class="btn btn-xs white-gallery btn-outline" onclick="viewSpmLog('+full[0]+')" title="View"><i class="fa fa-eye"></i></a>';
                    }                    
                    return view;
                }
            },
            {	targets: 13, class:"td-kecil text-center",
                render : function ( data, type, full, meta ) {
                    var tracking = '<a class="btn btn-xs white-gallery btn-outline" onclick="openDetailTracking('+full[0]+')" title="Tracking"><i class="fa fa-random"></i></a>';
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

