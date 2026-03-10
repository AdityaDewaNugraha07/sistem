<?php
/* @var $this yii\web\View */
$this->title = 'Penerimaan JasaKD';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\Select2Asset::register($this);
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', $this->title ); ?> <span id="periode-label" class="font-blue-soft"></span></span>
				</div>
				<div class="tools">
					<a href="javascript:;" class="reload"> </a>
					<a href="javascript:;" class="fullscreen"> </a>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-dataTable">
                    <thead>
						<tr>
							<th rowspan="2"></th>
							<th rowspan="2"><?= Yii::t('app', 'Kode'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Tanggal'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Tanggal'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Sales'); ?></th>										
							<th rowspan="2"><?= Yii::t('app', 'Cara Bayar'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Tanggal Kirim'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Customer'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Keterangan'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'View'); ?></th>
							<th colspan="2">Status Approval</th>
							<th rowspan="2" style="width: 50px;"></th>
							<?php /* <th colspan="2" style="width: 35px;">13-14 Reason</th> */?>
						</tr>
						<tr>
							<th>Approval1</th>
							<th>Approval2</th>
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
    $('select[name*=\"[cust_an_nama]\"]').select2({
		allowClear: !0,
		placeholder: 'Filter By Customer'
	});
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-dataTable').dataTable({
        ajax: { 
            url: '<?= \yii\helpers\Url::toRoute('/marketing/laporan/penerimaanJasaKD') ?>',
            data:{dt: 'table-dataTable',laporan_params : $("#form-search-laporan").serialize(),} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false, class: "td-kecil", },
			{	targets: 2, visible: false, class: "td-kecil", },
			{ 	targets: 3, class: "td-kecil",
                render: function ( data, type, full, meta ) {
					// var date = new Date(data);
					// date = date.toString('dd/MM/yyyy');
					// date = new Date(date).toUTCString();
					// date = date.split(' ').slice(1, 4).join(' ');
                    var date = formatTanggal(data);
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 5, visible: false},
			{ 	targets: 6,  class: "td-kecil",
                render: function ( data, type, full, meta ) {
					// var date = new Date(data);
					// date = date.toString('dd/MM/yyyy');
					// date = new Date(date).toUTCString();
					// date = date.split(' ').slice(1, 4).join(' ');
                    var date = formatTanggal(data);
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 8, visible: false, searchable:false, class: "td-kecil",
				render: function ( data, type, full, meta ) {
					if (full[11] != '') {
						var ret = "<span class='font-yellow-gold'>"+full[11]+"</span> ";
						if(full[14] == "APPROVED"){
							var ret = ret+' <i class="fa fa-check font-green-seagreen"></i>';
						} else {
							var ret = '';
						}
					} else {
						var ret = "<span class='font-green-seagreen'>Allowed</span>";
					}
					return ret;
                }
			},
			{	targets: 9, visible: false,  searchable:false, class: "td-kecil", },
			{	targets: 12, searchable:false, class: "td-kecil",
				width: '75px',
				render: function ( data, type, full, meta ) {
					var display = "";
					if (full[2] == "JasaKD") {
						display =  'visibility: display;';
					} else {
						if(full[9] || full[12]){
							display =  'visibility: hidden;';
						}
					}
					var ret =  '<center>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
					return ret;
				}
			},
			{ targets: 10, class: "td-kecil", visible: false,
				render: function ( data, type, full, meta ) {
                    if(full[13]=='APPROVED'){
                        var ret = '<span class="label label-success" style="font-size:1.1rem;">'+full[13]+'</span>';
                    }else if(full[13]=='REJECTED'){
                        var ret = '<span class="label label-danger" style="font-size:1.1rem;">'+full[13]+'</span>';
                    }else if(full[13]=='Not Confirmed'){
                        var ret = '<span class="label label-default" style="font-size:1.1rem;">'+full[13]+'</span>';
                    } else {
						var ret = '';
					}
					return ret;
				}
			},
			{ targets: 11, class: "td-kecil", visible: false,
				render: function ( data, type, full, meta ) {
					if (full[14] != 'REJECTED') {
						if (full[13] == 'APPROVED') {
							var ret = '<span class="label label-success" style="font-size:1.1rem;">'+full[14]+'</span>';
						} else if (full[13] == 'Not Confirmed' || full[13] == '') {
							var ret = '<span class="label label-default" style="font-size:1.1rem;">Not Confirmed</span>';
						} else {
							var ret = '';
						}
					} else {
						var ret = '<span class="label label-danger" style="font-size:1.1rem;">REJECTED</span>';
					}
					return ret;
				}
			},
        ],
        "fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		"dom": 'Bfrtip',
		"buttons": [], // hide button
		"autoWidth":false,
        "bDestroy": true,
    });
}

function lihatDetail(id){
	openModal('<?= \yii\helpers\Url::toRoute(['info','id'=>'']) ?>'+id,'modal-madul','90%');
}
function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
function formatTanggal(data) {
    var date = new Date(data);
    if (isNaN(date)) return '-';

    const day = ('0' + date.getDate()).slice(-2);
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const month = monthNames[date.getMonth()];
    const year = date.getFullYear();

    return `${day} ${month} ${year}`;
}

</script>