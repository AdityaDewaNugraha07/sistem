<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\DtcheckboxAsset::register($this); ?>
<div class="modal fade" id="modal-tbp" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan TBP'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
                            <div class="pull-right"><?= yii\helpers\Html::button(Yii::t('app', '<i class="fa fa-plus"></i> Add'),['class'=>'btn blue ciptana-spin-btn','id'=>'pick-btn','onclick'=>'pickingTBP()']); ?></div>
							<table class="table table-striped table-bordered dt-multiselect" id="table-dt">
								<thead>
									<tr>
										<th><?= Yii::t('app', 'Pick'); ?></th>
										<th style="width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
										<th><?= Yii::t('app', 'Tgl Terima'); ?></th>
										<th><?= Yii::t('app', 'Supplier'); ?></th>
										<th><?= Yii::t('app', 'No. Nota'); ?></th>
										<th><?= Yii::t('app', 'Total PKP'); ?></th>
										<th><?= Yii::t('app', 'Total Tagihan'); ?></th>
										<th><?= Yii::t('app', 'Retur'); ?></th>
										<th><?= Yii::t('app', 'Total Bayar'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
				<input type="hidden" id="select_data">
				<input type="hidden" id="eleid" value="">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    dtTable();
", yii\web\View::POS_READY); ?>
<style>
	.ada_retur { font-weight: bold; color: #f00;}
</style>
<script>
function dtTable(){
    var dt_table =  $('#table-dt').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasing/pengajuantagihan/pickPanelTBP') ?>',data:{dt: 'table-dt'} },
		"createdRow": function( row, data, dataIndex){
			if( data[7] >  0){
				$(row).addClass('ada_retur');
			}
        },		
		'columnDefs': [
			{
				'targets': 0,
				orderable: false,
				'checkboxes': {
					'selectRow': true
				}
			},
			{
				"targets": [ 1 ],
				"class":"td-kecil text-align-center",
				render: function ( data, type, full, meta ) {
					return data+" <input type='hidden' name='wadahvalue' value='"+full[0]+"'>";
				}
			},
			{
				"targets": [ 2 ],
				"class":"td-kecil text-align-left",
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{
				"targets": [ 3 ],
				"class":"td-kecil text-align-left",
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{
				"targets": [ 4 ],
				"class":"td-kecil text-align-left",
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{
				"targets": [ 5 ],
				"class":"td-kecil text-align-right",
				render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
				}
			},
			{
				"targets": [ 6 ],
				"class":"td-kecil text-align-right",
				render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
				}
			},
			{
				"targets": [ 7 ],
				"class":"td-kecil text-align-right",
				render: function ( data, type, full, meta ) {
					var data = data;
					if (data > 0) {
						var retur = data;
					} else {
						var retur = 0;
					}
					return formatNumberForUser(data);
				}
			},
			{
				"targets": [ 8 ],
				"class":"td-kecil text-align-right",
				render: function ( data, type, full, meta ) {
					var totalbayar = full[6] - full[7];
					return formatNumberForUser(totalbayar);
				}
			},
					
			/*{
				"targets": [ 8 ],
				"class":"td-kecil text-align-right",
				render: function ( data, type, full, meta ) {
					var pkp = full[5];
					var tagihan = full[6];
					var retur = full[7];
					if (full[7] > 0) {
						retur = full[7];
					} else {
						retur = 0;
					}
					var total_bayar = tagihan - retur;
					return formatNumberForUser(total_bayar);
				}
			},*/
		],
		'select': {
			'style': 'multi'
		},
		"fnDrawCallback": function( oSettings ) {
			formattingDatatable(oSettings.sTableId);
			$('#table-dt tr').click(function(event) {
				var tr = $(this);
				setTimeout(function(){
					if ($(tr).find("input[type='checkbox']").is(":checked")) {
						var val = '-'+$(tr).find('input[name="wadahvalue"]').val()+'-';
						var current = $('#select_data').val();
						if(current != ''){
							var baru = current+','+val;
						}else{
							var baru = val;
						}
					} else {
						var val = '-'+$(tr).find('input[name="wadahvalue"]').val()+'-';
						var current = $('#select_data').val();
						if(current != ''){
							var baru = current.replace(val, ""); 
						}
					}
					$('#select_data').val(baru);
				},300);
			});
		},
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script> 