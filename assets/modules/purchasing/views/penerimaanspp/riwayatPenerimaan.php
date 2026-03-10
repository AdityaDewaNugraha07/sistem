<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-riwayat" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Pembelian Yang Telah Dilakukan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-laporan">
							<thead>
								<tr>
									<th><?= Yii::t('app', 'No.'); ?></th>
									<th><?= Yii::t('app', 'Kode Penerimaan') ?></th>
									<th><?= Yii::t('app', 'Tanggal Terima') ?></th>
									<th><?= Yii::t('app', 'Supplier') ?></th>
									<th><?= Yii::t('app', 'Kode Item') ?></th>
									<th><?= Yii::t('app', 'Nama Item') ?></th>
									<th><?php echo Yii::t('app', 'Qty') ?></th>
									<th><?php echo Yii::t('app', 'Satuan') ?></th>
									<th><?php echo Yii::t('app', 'Harga Satuan') ?></th>
									<th><?php echo Yii::t('app', 'Ppn') ?></th>
									<th><?php echo Yii::t('app', 'Pph') ?></th>
									<th><?php echo Yii::t('app', 'Total') ?></th>
									<th><?php echo Yii::t('app', 'Keterangan') ?></th>
									<th></th>
									<th></th>
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
    dtBkk();
", yii\web\View::POS_READY); ?>
<script>
function dtBkk(){
    var dt_table =  $('#table-laporan').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/riwayatPenerimaan') ?>',data:{dt: 'table-laporan'} },
        order: [
            [2, 'desc']
        ],
		pageLength: 10,
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 5, 
                render: function ( data, type, full, meta ) {
					var parse1 = $.trim(data.split('/')[1]);
					var parse2 = '';
					var parse3 = '';
					if($.trim(data.split('/')[2])){
						parse2 = '/'+$.trim(data.split('/')[2]);
					}
					if($.trim(data.split('/')[3])){
						parse3 = '/'+$.trim(data.split('/')[3]);
					}
					var ret = parse1+parse2+parse3;
					return ret;
                }
            },
			{ 	targets: 6, // Qty
                render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
                }
            },
			{ 	targets: 7, // SAtuan
                render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
                }
            },
			{ 	targets: 8, // Harga
				className: 'dt-body-right',
                render: function ( data, type, full, meta ) {
					<?php if(empty($hide)){ ?>
						if(full[15]){ var mu = full[15]; }else{ var mu = "Rp"; }
						return "<span class='pull-left'>"+mu+"</span>"+formatInteger(data);
					<?php }else{ ?>
						return '';
					<?php } ?>
                }
            },
			{ 	targets: 9, // PPn
				className: 'dt-body-right',
                render: function ( data, type, full, meta ) {
					var ret = "<i style='font-size:1.1rem'>Non-PKP</i>";
					if( ((full[10]!=null) && (full[13]!=0)) || (full[13]!=0) ){
						ret = formatInteger(data);
					}
					<?php if(empty($hide)){ ?>
						return ret;
					<?php }else{ ?>
						return '';
					<?php } ?>
                }
            },
			{ 	targets: 10, // PPh (spo_id)
				className: 'dt-body-right',
                render: function ( data, type, full, meta ) {
					if(full[14]){
						var ret = formatNumberForUser(full[14]);
					}else{
						var ret = 0;
					}
					<?php if(empty($hide)){ ?>
						return ret;
					<?php }else{ ?>
						return '';
					<?php } ?>
					
                }
            },
			{ 	targets: 11, // Total
				className: 'dt-body-right',
                render: function ( data, type, full, meta ) {
					var ret = full[8] * full[6];
					if(full[15]){ var mu = full[15]; }else{ var mu = "Rp"; }
					if( (full[10]!=null)  && (full[13]!=0) || (full[13]!=0)){
						ret = ret + (ret*0.1);
					}
					<?php if(empty($hide)){ ?>
						return "<span class='pull-left'>"+mu+"</span>"+formatInteger(ret);
					<?php }else{ ?>
						return '';
					<?php } ?>
                }
            },
			{ 	targets: 12, // Keterangan
                render: function ( data, type, full, meta ) {
					return '<span style="padding:3px;font-size:1.1rem;">'+data+'</span>';
                }
            },
			{	targets: 13, visible: false },
			{	targets: 14, visible: false },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"bStateSave": true,
    });
}
function lihatBkk(bkk_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/kasir/bkk/index','bkk_id'=>'']); ?>'+bkk_id);
}
</script>