<?php 
app\assets\DatatableAsset::register($this); 
app\assets\InputMaskAsset::register($this);
?>
<div class="modal fade" id="modal-show-tbp" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Tagihan Suplier : '.$modSuplier->suplier_nm); ?></h4>
            </div>
            <div class="modal-body">
				<div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover" id="table-show">
								<thead>
									<tr style="background-color: #F1F4F7; ">
										<th></th>
										<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Tanggal'); ?></th>
										<th style="text-align: center; width: 120px;"><?= Yii::t('app', 'Kode TBP'); ?></th>
										<th style="text-align: center; width: 120px;"><?= Yii::t('app', 'Kode PO'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Invoice'); ?></th>
										<th style="text-align: center; width: 120px; line-height: 1"><?= Yii::t('app', 'Nominal<br>Tagihan'); ?></th>
										<th style="text-align: center; width: 100px; line-height: 1"><?= Yii::t('app', 'Payment<br>Status'); ?></th>
										<th style="text-align: center; width: 120px; "><?= Yii::t('app', 'Payment Reff'); ?></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							<div>
								<table class="table table-striped table-bordered table-hover" style="width: 100%;">
									<tr style="background-color: #D7E1EC">
										<td style="text-align: right"><b>Total</b></td>
										<td style="width:130px; font-weight: 600;" class='text-align-center'>
											<span class='pull-right' id='place-totaltagihanshow'></span>
										</td>
										<td style="width:230px; font-weight: 600;" class='text-align-center'></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    dtTableshow();
", yii\web\View::POS_READY); ?>
<script>
function dtTableshow(){
    var dt_table =  $('#table-show').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/finance/laporan/TagihanSuplier') ?>',data:{show:'tbp', dt: 'table-show', suplier_id:<?= $modSuplier->suplier_id ?>} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 1, class: "text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 2, class: "text-align-center", 
				render:function(data,type,full,meta){
					return "<a onclick='infoTBP("+full[0]+")'>"+data+"</a>";
				}
			},
			{ 	targets: 3, class: "text-align-center", 
				render:function(data,type,full,meta){
					return "<a onclick='infoSPO("+full[8]+")'>"+data+"</a>";
				}
			},
			{ 	targets: 4, class: "text-align-center", },
			{ 	targets: 5, class: "text-align-right", 
				render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
				}
			},
			{ 	targets: 6, class: "text-align-center td-kecil",
				render: function ( data, type, full, meta ) {
					var ret = '-';
                    if(data){
						var date = new Date(full[7]);
						date = date.toString('dd/MM/yyyy');
						var tgl = '<br><span style="font-size:1.1rem">'+date+'</span>';
                        ret = '<b>'+data+'</b>'+tgl;
                    }
                    return ret;
                }
			},
			{	targets: 7, class: "text-align-center td-kecil",
				render: function ( data, type, full, meta ) {
					if(full[9]){
						return "<a onclick='infoBBK("+full[10]+")'>"+full[9]+"</a>";
					}else{
						return "-";
					}
				}
			},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"fnDrawCallback": function( oSettings ) {
			var api = this.api(), data;
			// Remove the formatting to get integer data for summation
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};
			// Total over all pages
			var totaltagihan = api.column( 5 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
			console.log(totaltagihan);
			$("#place-totaltagihanshow").html( formatNumberForUser(Math.round(totaltagihan)) );
		},
    });
}
</script>