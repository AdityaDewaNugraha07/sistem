<?php
app\assets\DatatableAsset::register($this);
?>
<div class="modal fade draggable-modal" id="modal-all-spl" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Spl By Item'); ?></h4>
            </div>
            <div class="modal-body">
				<!--asdasd-->
				<div class="row">
                    <div class="col-md-12">
						<table class="table table-striped table-bordered table-hover" id="table-laporan4">
							<thead>
								<tr>
									<th><?= Yii::t('app', 'No.'); ?></th>
									<th><?= Yii::t('app', 'Kode SPL') ?></th>
									<th><?= Yii::t('app', 'Nama Item') ?></th>
									<th><?= Yii::t('app', 'Qty Order') ?></th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>\
<?php $this->registerJs(" 
dtLaporan();
formconfig();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan4').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasing/tracking/infoAllSplByItem') ?>',
			data:{
				dt: 'table-laporan4',
				bhp_id : "<?= $bhp_id ?>",
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
			{	targets: 1, 
                class : 'text-align-center',
				render: function ( data, type, full, meta ) {
					return "<a onclick='infoSPL("+full[0]+","+full[5]+")'>"+data+"</a>";
                }
            },
			{	targets: 3, 
                class : 'text-align-right',
				render: function ( data, type, full, meta ) {
					return formatNumberForUser(data)+" ("+full[4]+")";
                }
            },
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
		},
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}
function infoSPL(spl_id,bhp_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpl','id'=>'']) ?>'+spl_id+"&bhp_id="+bhp_id;
	var modal_id = 'modal-info-spl';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
</script>