<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-daftar-spo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar PO yang telah dibuat'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-spo">
							<thead>
								<tr>
									<th></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode PO'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Supplier'); ?></th>
									<th><?= Yii::t('app', 'Total Bayar'); ?></th>
									<th style="width: 50px;"><?= Yii::t('app', 'Approved Status'); ?></th>
									<th></th>
									<th style="width: 50px;"><?= Yii::t('app', 'Penerimaan BHP'); ?></th>
									<th style="width: 50px;"><?= Yii::t('app', 'Approval'); ?></th>
									<th style="width: 50px;"></th>
									<th></th>
									<th></th>
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
    dtSpo();
", yii\web\View::POS_READY); ?>
<script>
function dtSpo(){
    var dt_table =  $('#table-spo').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasing/pobhp/daftarSpo') ?>',data:{dt: 'table-spo'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1, class:'td-kecil'},
			{	targets: 2, class:'td-kecil'},
			{	targets: 3, class:'td-kecil'},
			{	targets: 4, 
                width: '150px',
				class:'td-kecil',
                createdCell :  function (td, cellData, rowData, row, col) {
					$(td).attr('class', 'text-align-right'); 
				},
				render: function ( data, type, full, meta ) {
                    var ret = "<span style='float:left;'>"+full[11]+"</span>"+formatInteger(data);
                    return ret;
                }
            },
            {	targets: 5,
				width: '200px',
				class:'td-kecil',
				searchable: false,
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
					if(full[8]){
						ret = '<span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED ?></span>';
					}else{
						if(data == "<?= app\models\TApproval::STATUS_APPROVED ?>"){
                        ret = '<span class="label label-sm label-success"> '+data+' </span>';
						}else if(data == "<?= app\models\TApproval::STATUS_NOT_CONFIRMATED ?>"){
                        ret = '<span class="label label-sm label-default"> '+data+' </span>';
						}else if(data == "<?= app\models\TApproval::STATUS_REJECTED ?>"){
						ret = '<span class="label label-sm label-danger"> '+data+' </span>';
						}
					}
                    return ret;
                },
				createdCell :  function (td, cellData, rowData, row, col) {
					$(td).attr('class', 'text-align-center'); 
				},
            },
			{	targets: 6, visible: false },
			{	targets: 8, 
				class:'text-align-center',
                render: function ( data, type, full, meta ) {
                    var ret = data;
                    if(full[8]){
                        ret = '<span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED ?></span>';
                    }else{
						ret = ' <span><b>Assigne to :</b> '+full[9]+'</span><br>\n\
								<span><b>Confirm by :</b> '+full[10]+'</span>';
					}
                    return ret;
                },
				createdCell :  function (td, cellData, rowData, row, col) {
					$(td).attr('style', 'font-size:1rem'); 
				},
            },
			{	targets: 7, 
				class:'td-kecil text-align-center',
				render: function ( data, type, full, meta ) {
					var ret = "-";
					if(full[8]){
						ret = '<span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED ?></span>';
					}else{
						if(data){
							ret = '<a onclick="infoTBP(\''+full[6]+'\')" >'+data+'</a>';
						}
					}
                    return ret;
                }
            },
            {	targets: 9, 
                width: '50px',
				orderable: false,
				searchable: false,
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat detail PO" onclick="lihatSpo('+full[0]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret;
                }
            },
			{	targets: 10, visible: false },
			{	targets: 11, visible: false },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatSpo(spo_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasing/pobhp/index','spo_id'=>'']); ?>'+spo_id);
}
function infoTBP(terima_bhp_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp','id'=>'']); ?>'+terima_bhp_id;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-tbp").modal('show');
		$("#modal-info-tbp").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}
</script>