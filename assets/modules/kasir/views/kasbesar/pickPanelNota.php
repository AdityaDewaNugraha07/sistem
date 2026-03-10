<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\DtcheckboxAsset::register($this); ?>
<div class="modal fade" id="modal-nota" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Nota'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered dt-multiselect" id="table-dt">
								<thead>
									<tr>
										<th  style="width: 20px;"><?= Yii::t('app', 'Pick'); ?></th>
										<th style="width: 100px;"><?= Yii::t('app', 'Kode'); ?></th>
										<th><?= Yii::t('app', 'Tanggal'); ?></th>
										<th><?= Yii::t('app', 'Customer'); ?></th>
										<th><?= Yii::t('app', 'Cara Bayar'); ?></th>
										<th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Total<br>Tagihan'); ?></th>
										<th style="line-height: 1"><?= Yii::t('app', 'Status<br>Bayar'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
				<input type="hidden" id="eleid" value="<?= $eleid ?>">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    dtTable();
", yii\web\View::POS_READY); ?>
<script>
function dtTable(){
    var dt_table =  $('#table-dt').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/kasir/kasbesar/pickPanelNota') ?>',data:{dt: 'table-dt'} },
		order: [
            [0, 'desc']
        ],
		'columnDefs': [
			{ 	targets: 0, 
                render: function ( data, type, full, meta ) {
					return "<center><a onclick='pickingNota(\""+full[0]+"\",\""+full[1]+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a></center>";
                }
            },
			{
				"targets": [ 1 ],
				render: function ( data, type, full, meta ) {
					return data+" <input type='hidden' name='wadahvalue' value='"+full[0]+"'>";
				}
			},
			{
				"targets": [ 5 ],
				"class":"text-align-right",
				render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
				}
			},
			{
				"targets": [ 6 ],
				"class":"text-align-center",
				render: function ( data, type, full, meta ) {
					var ret = "";
					if(data=="PAID"){
						ret = "<label class='label label-success'>PAID</label>"
					}else if(data=="PARTIALLY"){
						ret = "<label class='label label-warning'>PARTIALLY</label>"
					}else if(data=="UNPAID"){
						ret = "<label class='label label-default'>UNPAID</label>"
					}
					return ret;
				}
			},
		],
		"autoWidth":true,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>