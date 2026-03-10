<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Open Voucher';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Open Voucher '); ?><span id="periode-label" class="font-blue-soft"></span></span>
				</div>
				<div class="tools">
					<a href="javascript:;" class="reload"> </a>
					<a href="javascript:;" class="fullscreen"> </a>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-laporan">
					<thead>
						<tr>
							<th><?= Yii::t('app', 'No.'); ?></th>
							<th><?= Yii::t('app', 'Kode') ?></th>
							<th><?= Yii::t('app', 'Tanggal') ?></th>
							<th><?= Yii::t('app', 'Tipe'); ?></th>
							<th><?= Yii::t('app', 'Dept') ?></th>
							<th><?= Yii::t('app', 'Reff No') ?></th>
							<th><?= Yii::t('app', 'Penerima') ?></th>
							<th><?= Yii::t('app', 'Penerima QQ') ?></th>
							<th><?= Yii::t('app', 'Cara Bayar') ?></th>
							<th><?= Yii::t('app', 'Total Tagihan') ?></th>
							<th><?= Yii::t('app', 'Total Kubikasi') ?></th>
							<th><?= Yii::t('app', 'Status Pembayaran') ?></th>
							<th><?= Yii::t('app', 'Prepared By') ?></th>
							<th><?= Yii::t('app', 'Keterangan') ?></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
                            <th colspan="9" style="text-align:right">Total Per Page:</th>
                            <th colspan="1" style="text-align:right"></th>
                        </tr>
                        <tr>
                            <th colspan="9" style="text-align:right;" class="td-kecil">Total All Page:</th>
                            <th colspan="1" style="text-align:right;" class="td-kecil"></th>
                        </tr>
					</tfoot>
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
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/finance/laporan/openVoucher') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, class: 'td-kecil',
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 1, class:"text-align-center td-kecil" },
			{	targets: 2, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 3, class:"text-align-left td-kecil" },
			{	targets: 4, class:"text-align-center td-kecil",
				render: function ( data, type, full, meta ) {
					return full[26];
                }
			 },
			{	targets: 5, class:"text-align-left td-kecil",
				render: function ( data, type, full, meta ) {
					if(data == null){
						ret = '-';
					} else {
						ret = data;
					}
					return ret;
                }
			 },
			{	targets: 6, class:"text-align-left td-kecil",
                render: function ( data, type, full, meta ) {
                    var penerima = '-';
					if(full[3]=="REGULER"){
						if(full[6] !== null){
							penerima = '<b>'+full[7]+'</b><br>'+full[8];
						} else{
							penerima = '<b>'+full[13]+'</b><br>'+full[14];
						}
					} else if(full[3]=="PEMBAYARAN LOG ALAM"){
						penerima = '<b>'+full[10]+'</b><br>'+full[11];
					} else if(full[3]=="DEPOSIT SUPPLIER LOG"){
						penerima = '<b>'+full[10]+'</b><br>'+full[11];
					} else if(full[3] == "DP LOG SENGON"){
						penerima = '<b>'+full[10]+'</b><br>'+full[27];
					} else if(full[3] == "PELUNASAN LOG SENGON"){
						penerima = '<b>'+full[10]+'</b><br>'+full[27];
					} else if(full[3] == "PEMBAYARAN ASURANSI LOG SHIPPING"){
						penerima = full[28];
					}
                    return penerima;
                }
            },
            {	targets: 7, class:"text-align-left td-kecil",
				render: function ( data, type, full, meta) {
                    function nl2br (str, is_xhtml) {   
                        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
                        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
                    }                   
                    if (full[25] == '' || full[25] == null) {
                        ret = ''
                    } else {
                        ret = nl2br(full[25]);
                    }
                    return ret;
                }
			 },
			{	targets: 8, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta) {
                    return full[15];
                }
            },
			{	targets: 9, class:"text-align-right td-kecil", width: '8%',
                render: function ( data, type, full, meta ) {
                    var ret = "<span class='pull-left'>Rp.</span>"+formatNumberForUser(full[16]);
					return ret;
                }
            },
			{	targets: 10, class:"text-align-right td-kecil",
                render: function ( data, type, full, meta ) {
					ret = '';
                    if( full[3]=="PELUNASAN LOG SENGON" ){
                        var asd = $.parseJSON(full[24]);
                        var totalm3=0;
                        $(asd).each(function(i,v){
                            var m3 = 0;
                            $(v.diameter_harga).each(function(ii,vv){
                                m3 += unformatNumber( vv.m3 );
                            });
                            totalm3 += m3;
                        });
                        ret += "<br>"+formatNumberForUser3Digit( totalm3 )+" m<sup>3</sup>";
                    }
					return ret;
                }
            },
			{	targets: 11, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    if(full[18] == "WAITING"){
						tanggal = '';
					} else {
						if(full[18] == "PAID"){
							a = 'at ';
						} else {
							a = 'plan ';
						}
						var date = new Date(full[29]);
						date = date.toString('dd/MM/yyyy');
						tanggal = '<br>' + a + date;
					}
                    return full[18] + '<span class="td-kecil2">' + tanggal + '</span>';
                }
            },
			{	targets: 12, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    return full[19];
                }
            },
			{	targets: 13, class:"text-align-left td-kecil2",
                render: function ( data, type, full, meta ) {
                    var ret = "";
                    if( full[3]=="PELUNASAN LOG SENGON" ){
                        var asd = $.parseJSON(full[24]);
                        $(asd).each(function(i,v){
                            var m3 = 0;
                            ret += "<b>"+v.reff_no+"</b> - "+formatNumberForUser3Digit( v.total_m3 )+" m<sup>3</sup>";
                            if((i+1) < asd.length){
                                ret += "<br>";
                            }
                        });
                    }else{
                        ret = full[23];
                    }
                    return ret;
                }
            },
			{ 	targets: 14,visible:false},
			{ 	targets: 15,visible:false},
			{ 	targets: 16,visible:false},
			{ 	targets: 17,visible:false},
			{ 	targets: 18,visible:false},
			{ 	targets: 19,visible:false},
			{ 	targets: 20,visible:false},
			{ 	targets: 21,visible:false},
			{ 	targets: 22,visible:false},
			{ 	targets: 23,visible:false},
			{ 	targets: 24,visible:false},
			{ 	targets: 25,visible:false},
			{ 	targets: 26,visible:false},
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		footerCallback: function(row, data, start, end, display) {
			var api = this.api();
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                         i : 0;
            };

			// console.log(api.column(22).data().toArray());

            // Total over all pages
            total = api
                .column( 16 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 16, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $('tr:eq(0) th:eq(1)', api.table().footer() ).html(`${formatNumberForUser(pageTotal.toFixed(0).toLocaleString())}`);
            $.ajax({
                url: "<?= yii\helpers\Url::toRoute('/finance/laporan/openVoucherTotal') ?>?"+$('#form-search-laporan').serialize(),
                success: res => {
                    $('tr:eq(1) th:eq(1)', api.table().footer() ).html(`${formatNumberForUser(JSON.parse(res).total.toFixed(0).toLocaleString())}`)  
                }                                                                                                                                                                                                                                                                                                                                                                                                             
            })
		},
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/finance/laporan/openVoucherPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>