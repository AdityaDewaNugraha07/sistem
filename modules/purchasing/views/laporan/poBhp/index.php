<?php
/* @var $this yii\web\View */
$this->title = 'Laporan PO Bahan Pembantu';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $model]) ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar Purchase Order Bahan Pembantu '); ?><span id="periode-label" class="font-blue-soft"></span></span>
				</div>
				<div class="tools">
					<a href="javascript:;" class="reload"> </a>
					<a href="javascript:;" class="fullscreen"> </a>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-laporan" style="width: 1550px;">
					<thead>
						<tr>
							<th style="width: 50px;"><?= Yii::t('app', 'No.'); ?></th>										<?php //0 ;?>
							<th style="width: 80px;"><?= Yii::t('app', 'Kode PO') ?></th>									<?php //1 ;?>
							<th style="width: 80px;"><?= Yii::t('app', 'Tanggal Order') ?></th>								<?php //2 ;?>
							<th style="width: 100px;"><?= Yii::t('app', 'Nama Item') ?></th>								<?php //3 ;?>
							<th style="width: 40px;"><?= Yii::t('app', 'Qty') ?></th>										<?php //4 ;?>
							<th style="width: 40px;"><?= Yii::t('app', 'Satuan') ?></th>									<?php //5 ;?>
							<th style="width: 40px;"><?= Yii::t('app', 'Status<br>Garansi') ?></th>							<?php //6 ;?>
							<th style="width: 80px;"><?= Yii::t('app', 'Harga') ?></th>										<?php //7 ;?>
							<th><?= Yii::t('app', 'Sub Total') ?></th>                                                      <?php //8 ;?>
							<th style="width: 250px;"><?= Yii::t('app', 'Suplier') ?></th>									<?php //9 ;?>
							<th style="width: 250px;"><?= Yii::t('app', 'Keterangan') ?></th>								<?php //10 ;?>
							<th style="width: 85px;"><?= Yii::t('app', 'Kode TBP') ?></th>									<?php //11 ;?>
							<th style="line-height: 1; width: 80px;"><?= Yii::t('app', 'Tanggal<br>Rencana Kirim') ?></th>	<?php //12 ;?>
							<th style="line-height: 1; width: 80px;"><?= Yii::t('app', 'Tanggal<br>Penerimaan') ?></th>		<?php //13 ;?>
							<th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Info<br>Penawaran') ?></th>													<?php //14 ;?>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>

<?php $this->registerJs("
	$('#form-search-laporan').submit(function(){
		dtLaporan();
		return false;
	});
	formconfig(); 
	dtLaporan();
	changePertanggalLabel();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasing/laporan/poBhp') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                width: '5%',  class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets: 1, class:'td-kecil', },
			{ 	targets: 2, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 3, class:'td-kecil',
				render: function ( data, type, full, meta ) {
					return '<left>'+full[4]+'</left>';
                }
			},
			{ 	targets: 4, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(Math.round(full[5]));
                }
            },
			{ 	targets: 5, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+full[6]+'</center>';
                } 
			},
			{ 	targets: 6, class:"text-align-right td-kecil",
				render: function ( data, type, full, meta ) {
					if(full[16] == false){
						return "<center>-</center>";
					}else{
						return '<center>Bergaransi</center>';
					}
				}                 
            },
			{ 	targets: 7, class:"text-align-right td-kecil" },
			{ 	targets: 8, class:"text-align-right td-kecil",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(Math.round(data));
                }
            },
			{ 	targets: 9, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 10, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<span style="font-size:1.1rem">'+data+'</span>';
                }
            },
			{ 	targets: 11, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					if(data){
							return "<a onclick='infoTBP("+full[11]+","+full[3]+")'>"+full[12]+"</span></a>";						
					}else{
						return "<center>-</center>";
					}
                }
            },
			{ 	targets: 12, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					if(full[13]){
						var date = new Date(full[13]);
						date = date.toString('dd/MM/yyyy');
						return '<center>'+date+'</center>';						
					}else{
						return "<center>-</center>";
					}
                }
            },
			{ 	targets: 13, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					if(full[14]){
						var date = new Date(full[14]);
						date = date.toString('dd/MM/yyyy');
						if(full[16]==null){
							return "<center>-</center>";
						}else{
							return '<center>'+date+'</center>';
						}
					}else{
						return "<center>-</center>";
					}
                }
            },
            {	targets: 14, class:'td-kecil',
	            render: function ( data, type, full, meta ) {
					return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }            
            }

        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			changePertanggalLabel();
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

function infoTBP(terima_bhp_id,bhp_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp']) ?>?id='+terima_bhp_id+'&bhp_id='+bhp_id,'modal-info-tbp','75%');
}
function info(spod_id){
    openModal('<?= \yii\helpers\Url::toRoute('/purchasing/laporan/poBhpInfo') ?>?spod_id='+spod_id,'modal-pobhp-info','80%');
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/laporan/poBhpPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	} else {
		$('#periode-label').html("");
	}
}

</script>