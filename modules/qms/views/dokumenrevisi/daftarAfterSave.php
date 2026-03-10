<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Dokumen Revisi'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Nama Dokumen'); ?></th>
                                    <th><?= Yii::t('app', 'Nomor Dokumen'); ?></th>
									<th style="width: 120px;"><?= Yii::t('app', 'Revisi Ke'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Berlaku'); ?></th>
									<th><?= Yii::t('app', 'Catatan Revisi'); ?></th>
									<th style="width: 120px;"></th>
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
    dtAfterSave();
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/qms/dokumenrevisi/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
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
			{	targets: 6, class : "lineheight-0-2 text-align-center",
                searchable: false,
				render: function ( data, type, full, meta ) {
                    // jika revisi ke-0 atau status penerimaan true, maka tdk bisa edit
                    if(data || full[3] == 0){
                        var ret = ' <a class="btn btn-xs btn-outline grey tooltips"><i class="fa fa-edit"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>';
                    } else {
                        var ret = ' <a class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Update" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>';
                    }
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