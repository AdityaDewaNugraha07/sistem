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
                                    <th><?= Yii::t('app', 'PIC ISO'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Dikirim'); ?></th>
									<th><?= Yii::t('app', 'Dikirim Oleh'); ?></th>
                                    <th><?= Yii::t('app', 'Status Penerimaan'); ?></th>
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
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    dtAfterSave();
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/qms/distribusidok/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class:'td-kecil' },
            {	targets: 2, class:'text-align-center td-kecil' },
			{ 	targets: 3, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy H:m:s');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 4, class:'text-align-center td-kecil' },
            { 	targets: 5, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
                    if(data){
                        var ret = "<span style='color:green;'>Sudah Diterima</span>";
                    } else {
                        var ret = "<span style='color:red;'>Belum Diterima</span>";
                    }
					return ret;
                }
            },
			{	targets: 6, class : "lineheight-0-2 text-align-center",
				render: function ( data, type, full, meta ) {
                    if(!full[5]){
                        var ret = ' <a class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Update" onclick="edit('+full[0]+', '+full[3]+')"><i class="fa fa-edit"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+', '+full[3]+')"><i class="fa fa-eye"></i></a>';
                    } else {
                        var ret = ' <a class="btn btn-xs btn-outline grey tooltips"><i class="fa fa-edit"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+', '+full[3]+')"><i class="fa fa-eye"></i></a>';
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
function lihatDetail(id, tgl){
    var date = new Date(tgl);
	date = date.toString('yyyy-mm-dd-H-m-s');
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/qms/distribusidok/index','dokumen'=>'']); ?>'+id+'&tgl_kirim='+date);
}
function edit(id, tgl){
    var date = new Date(tgl);
	date = date.toString('yyyy-mm-dd-H-m-s');
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/qms/distribusidok/index','dokumen'=>'']); ?>'+id+'&tgl_kirim='+date+'&edit=1');
}

</script>