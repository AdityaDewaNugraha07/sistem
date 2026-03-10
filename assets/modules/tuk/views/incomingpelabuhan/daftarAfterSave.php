<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Incoming Pelabuhan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="line-height: 1; width: 180px;"><?= Yii::t('app', 'Kode<br>Incoming'); ?></th>
									<th style="line-height: 1; width: 120px;"><?= Yii::t('app', 'Tanggal<br>Incoming'); ?></th>
									<th style="line-height: 1; width: 180px;"><?= Yii::t('app', 'Kode<br>Keberangkatan'); ?></th>
									<th style="line-height: 1; width: 200px;"><?= Yii::t('app', 'Tongkang'); ?></th>
									<th><?= Yii::t('app', 'Keterangan'); ?></th>
									<th style="width: 70px;"></th>
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
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/tuk/incomingpelabuhan/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 1, class:"text-align-center" },
			{ 	targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            }, 
			{ 	targets: 3, class:"text-align-center" },
            {	targets: 6, 
                render: function ( data, type, full, meta ) {
                    var display = "";
					
					var ret =  '<center>\n\
									<a style="" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
					return ret;
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/tuk/incomingpelabuhan/index','incoming_pelabuhan_id'=>'']); ?>'+id);
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/tuk/incomingpelabuhan/index','incoming_pelabuhan_id'=>'']); ?>'+id+'&edit=1');
}
</script>