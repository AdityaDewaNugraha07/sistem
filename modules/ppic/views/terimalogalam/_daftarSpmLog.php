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
									<th class="td-kecil" style="width: 70px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th class="td-kecil" style="width: 70px;"><?= Yii::t('app', 'Tanggal'); ?></th>
									<th class="td-kecil" style="width: 70px;"><?= Yii::t('app', 'ETD'); ?></th>
									<th class="td-kecil" style="width: 70px;"><?= Yii::t('app', 'ETD Logpond'); ?></th>
									<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'ETA'); ?></th>
									<th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'Nama Tongkang'); ?></th>
									<th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'Lokasi Muat'); ?></th>
									<th class="td-kecil" style="width: 70px;"><?= Yii::t('app', 'Est. Batang'); ?></th>
									<th class="td-kecil" style="width: 70px;"><?= Yii::t('app', 'Est. Volume'); ?></th>
									<th class="td-kecil" style="width: 180px;"><?= Yii::t('app', 'PIC'); ?></th>
									<th class="td-kecil" style="width: 50px;"></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/terimalogalam/daftarSpmLog') ?>',data:{dt: 'modal-daftarSpmLog'} },
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
            {	targets: 8, class:"td-kecil text-right",
                render: function (data, type, full, meta) {
                    return formatNumberForUser(full[8]);
                }
            },
            {	targets: 9, class:"td-kecil text-right",
                render: function (data, type, full, meta) {
                    return formatNumberForUser(full[9]);
                }
            },
            {	targets: 10, class:"td-kecil"},
            {	targets: 11, class:"td-kecil text-center",
                render : function ( data, type, full, meta ) {
                    //full[11] = status, full[12] = status_jenis
                    if (full[12] == 1) {
                        return "<a onclick='pickDaftarSpmLog(\""+full[0]+"\", \""+full[1]+"\", )' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>";
                    } else {
                        return "";
                    }
                    
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>