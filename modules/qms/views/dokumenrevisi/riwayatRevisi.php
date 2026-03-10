<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-riwayat-revisi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Dokumen Revisi'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-riwayat-revisi">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Nama Dokumen'); ?></th>
                                    <th><?= Yii::t('app', 'Nomor Dokumen'); ?></th>
									<th style="width: 120px;"><?= Yii::t('app', 'Revisi Ke'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Berlaku'); ?></th>
									<th><?= Yii::t('app', 'Catatan Revisi'); ?></th>
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
    dtTable();
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function dtTable(){
    var dt_table =  $('#table-riwayat-revisi').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute(['/qms/dokumenrevisi/riwayatRevisi', 'dokumen_id'=>'']) ?>'+<?= $dokumen_id; ?>,data:{dt: 'modal-riwayat-revisi'} },
        order: [
            [3, 'asc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            { 	targets: 3, class:'text-align-center'},
			{ 	targets: 4, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            { 	targets: 5, 
                render: function ( data, type, full, meta ) {
					var ret = data?data:'-';
					return ret;
                }
            },
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"createdRow": function ( row, data, index ) {
            if(data[12]){
				$(row).addClass("cancelBackground");
			}
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
            if(aData[9]){
                $(nRow).attr("style","background-color: #f9ffe5;");
            }  
		},
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/qms/dokumenrevisi/index','dokumen_revisi_id'=>'']); ?>'+id);
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/qms/dokumenrevisi/index','dokumen_revisi_id'=>'']); ?>'+id+'&edit=1');
}

</script>