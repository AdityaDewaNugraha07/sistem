<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Keberangkatan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-spm">
							<thead>
								<tr>
									<th></th>
									<th style="width:200px"><?= Yii::t('app', 'Kode Keberangkatan'); ?></th>
									<th ><?= Yii::t('app', 'Nama Tongkang'); ?></th>
									<th style="width:100px"><?= Yii::t('app', 'ETA'); ?></th>
									<th style="width:100px"><?= Yii::t('app', 'Jml Loglist'); ?></th>
									<th style="width:100px"><?= Yii::t('app', 'Total Batang'); ?></th>
									<th style="width:100px"><?= Yii::t('app', 'Total M<sup>3</sup>'); ?></th>
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
	formconfig();
    dtMaster();
", yii\web\View::POS_READY); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-spm').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/tuk/incomingpelabuhan/openKeberangkatan') ?>',data:{dt: 'table-keberangkatan'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
			{	targets: 0, visible: false },
			{	targets: 1,
                render: function ( data, type, full, meta ) {
                    return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
			{ 	targets: 3, class:"text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 4, class:"text-align-right" },
			{ 	targets: 5, class:"text-align-right" },
			{ 	targets: 6, class:"text-align-right" },
			
        ],
		"autoWidth" : false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>