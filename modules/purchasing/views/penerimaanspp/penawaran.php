<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\DtcheckboxAsset::register($this); ?>
<style>
tr:hover .button {
  opacity: 1;
}
</style>
<div class="modal fade" id="modal-penawaran" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Semua Penawaran Yang Pernah Dilakukan untuk Bahan Pembantu : <b>').$modBhp->bhp_nm."</b>"; ?></h4>
            </div>
            <div class="modal-body">
                <table class="table tracking table-striped table-bordered table-hover" id="table-penawaran">
					<thead>
						<tr style="background-color: #F1F4F7; ">
							<th style="width: 40px;"><?= Yii::t('app', 'Pilih'); ?></th>
							<th style="text-align: center; width: 100px; line-height: 1"><?= Yii::t('app', 'Kode<br>Penawaran'); ?></th>
							<th style="text-align: center; width: 100px; line-height: 1"><?= Yii::t('app', 'Tanggal<br>Penawaran'); ?></th>
							<th style="text-align: center; width: 200px;"><?= Yii::t('app', 'Supplier'); ?></th>
							<th style="text-align: center; width: 60px;"><?= Yii::t('app', 'Qty'); ?></th>
							<th></th>
							<th style="text-align: center; width: 80px; line-height: 1"><?= Yii::t('app', 'Harga<br>Satuan'); ?></th>
							<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
							<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Attachment'); ?></th>
							<th style="text-align: center; width: 30px;"></th>
						</tr> 
					</thead>
				</table>
            </div>
			<div class="modal-footer text-align-center" style="padding-top: 10px;">
				<input type="hidden" id="current_data" value="<?= $current_data ?>">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs(" 
dtPenawaran();
", yii\web\View::POS_READY); ?>
<script>
function dtPenawaran(){
	var bhp_id = "<?= $modBhp->bhp_id ?>";
    var dt_table =  $('#table-penawaran').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/penawaran','bhp_id'=>'']) ?>'+bhp_id,type:"POST",data:{dt: 'table-penawaran'} },
        columnDefs: [
            {	'targets': 0,'orderable': false,
				'checkboxes': {
					'selectRow': true,
				}
			},
            {	targets: 1, class: "text-align-center kode_penawaran",
				render: function ( data, type, full, meta ) {
					return data+" <input type='hidden' name='wadahvalue' value='"+full[0]+"'>";
				}
			},
			{	targets: 2, 
				class: "text-align-center tgl_penawaran",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return date;
                }
            },
			{	targets: 3, class: "supplier", },
			{	targets: 4, 
				class: "text-align-center qty",
                render: function ( data, type, full, meta ) {
					return data+" "+full[5];
                }
            },
			{	targets: 5, visible: false },
			{	targets: 6, class: "text-align-right harga_satuan", },
			{	targets: 7, class: "keterangan", },
			{	targets: 8, class: "attachment", },
			{	targets: 9, class: "text-align-center action", 
				orderable: false,
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"infoPenawaran('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
			}
            
        ],
		'select': {
			'style': 'multi',
		},
		"autoWidth":false,
		"fnDrawCallback": function( oSettings ) {
			formattingDatatable(oSettings.sTableId);
			$('#table-penawaran tr').click(function(event) {
				updateCurrentData();
			});
			setTerpilih( $("#current_data").val() );
		},
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function updateCurrentData(){
	setTimeout(function(){
		var arr = [];
		$('#table-penawaran .dt-checkboxes:checked').each(function(i){
			arr[i] = parseInt( $(this).parents('tr').find("input[name='wadahvalue']").val() );
		});
		setTimeout(function(){
			if(arr){
				var current_data = JSON.stringify(arr);
				$("#current_data").val(current_data);
			}
		},100);
	},300);
}

function setTerpilih(current_data){
	if(current_data){
		var terpilih = $.parseJSON(current_data);
		$(terpilih).each(function(key,value){
			$('#table-penawaran > tbody > tr').each(function(){
				if( $(this).find("input[name='wadahvalue']").val() == value ){
					$(this).find('input[type="checkbox"]').prop('checked', true);
					$(this).addClass('selected');
				}
			});
		});
	}
}

function formattingDatatable(sTableId){
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').html("\
        <a class='btn btn-default tooltips' onclick='createPenawaran("+<?= $modBhp->bhp_id ?>+")' data-original-title='Create New'><i class='fa fa-plus'></i> Penawaran Baru</a>\n\
    ");
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
    $('#'+sTableId+'_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
    $(".tooltips").tooltip({ delay: 50 });
}
</script>