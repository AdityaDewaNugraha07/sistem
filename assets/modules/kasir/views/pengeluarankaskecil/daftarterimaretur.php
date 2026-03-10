<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-returditerima" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Penerimaan Retur BHP yang sudah dilakukan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-returditerima">
							<thead>
								<tr>
									<tr>
										<th><?= Yii::t('app', 'No.'); ?></th>
										<th><?= Yii::t('app', 'Kode Retur') ?></th>
										<th></th>
										<th><?= Yii::t('app', 'Kode TBP') ?></th>
										<th><?= Yii::t('app', 'Tanggal'); ?></th>
										<th><?= Yii::t('app', 'Item'); ?></th>
										<th><?= Yii::t('app', 'Harga Retur'); ?></th>
										<th><?= Yii::t('app', 'Potongan'); ?></th>
										<th><?= Yii::t('app', 'Qty'); ?></th>
										<th></th>
										<th><?= Yii::t('app', 'Total Kembali') ?></th>
										<th></th>
										<th></th>
									</tr>
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
    dtRetur();
", yii\web\View::POS_READY); ?>
<script>
function dtRetur(){
    var dt_table =  $('#table-returditerima').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/kasir/pengeluarankaskecil/returDiterima') ?>',
			data:{
				dt: 'table-returditerima',
			} 
		},
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 2, visible: false },
			{	
				targets: 3, 
				class: 'text-align-center' ,
				render: function ( data, type, full, meta ) {
					var ret="<a onclick=\"infoTBP('"+full[2]+"','"+full[12]+"')\">"+full[3]+"</a>";
					return ret;
                }
			},
			{ 	targets: 4, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 6, 
				class:'text-align-right',
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{ 	targets: 7, 
				class:'text-align-right',
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{ 	targets: 8, 
				class:'text-align-right',
                render: function ( data, type, full, meta ) {
					return data+" "+full[9];
                }
            },
			{	targets: 9, visible: false },
			{ 	targets: 10, 
				class:'text-align-right',
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{	targets: 11, visible: false },
			{	targets: 12, 
                width: '50px',
				orderable: false,
				searchable: false,
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class="btn btn-xs btn-outline dark" onclick="lihatRetur('+full[11]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret;
                }
            },
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}
function lihatRetur(kas_kecil_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/terimaretur','kas_kecil_id'=>'']); ?>'+kas_kecil_id);
}
function infoTBP(terima_bhp_id,bhp_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id+'&bhp_id='+bhp_id;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-tbp").modal('show');
		$("#modal-info-tbp").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}
</script>