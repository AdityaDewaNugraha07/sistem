<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar BPB Belum Diterima'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
								<thead>
									<tr>
										<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
										<th><?= Yii::t('app', 'Kode'); ?></th>
										<th><?= Yii::t('app', 'Tanggal BPB'); ?></th>
										<th style="width: 210px;"><?= Yii::t('app', 'Penerimaan'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
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
    dtAftersave();
", yii\web\View::POS_READY); ?>
<script>
function dtAftersave(){
    var dt_table =  $('#table-aftersave').dataTable({
            pageLength: 50,
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/logistik/spb/DaftarBpbblmterima') ?>?dept_id=<?= $departement_id ?>',data:{dt: 'table-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
			{ 	targets: 0, visible:false },
            { 	targets: 1, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {                    
					return data;
                }
            },
			{ 	targets:2, class:"td-kecil",
				render: function ( data, type, full, meta ) {
                    var date = new Date(full[3]);
					date = date.toString('dd/MM/yyyy');
                    var today = new Date();
                    var bpbDate = new Date(full[3]); // Tanggal BPB
                    // Set both dates to midnight
					today.setHours(0, 0, 0, 0);
					bpbDate.setHours(0, 0, 0, 0);
                    var diffTime = Math.abs(today - bpbDate);
                    var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
					return '<center>'+date+' <h class="text-danger"> (' + diffDays + ' hari yang lalu)</h></center>';
                }
			},		
			{ 	targets: 3,  class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					data = full[2];
					var ret = "";
					if(data){
                        ret += "<a class='btn btn-xs btn-outline red tooltips' data-original-title='Klick Untuk Melakukan Proses Penerimaan BPB' onclick='infoBpb("+full[0]+")'> <i class='fa fa-plus-circle'></i><span>";
					}else{
						ret = "<i>-- Belum ada BPB --</i>";
					}
					return ret;
                }
            },
            
        ],
		"autoWidth":false,
		"bStateSave": true,
		"bDestroy": true,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

</script>