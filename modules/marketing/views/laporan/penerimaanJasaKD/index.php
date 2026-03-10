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
							<th rowspan="2"><?= Yii::t('app', 'Sales'); ?></th>										
							<th rowspan="2"><?= Yii::t('app', 'Tanggal Kirim'); ?></th>
							<th rowspan="2"><?= Yii::t('app', 'Customer'); ?></th>
                            <th rowspan="2"><?= Yii::t('app', 'Tanggal<br>Terima/Hasil'); ?></th>
                            <th rowspan="2"><?= Yii::t('app', 'Nopol'); ?></th>
                            <th rowspan="2"><?= Yii::t('app', 'No. Palet'); ?></th>
                            <th rowspan="2"><?= Yii::t('app', 'Produk'); ?></th>
                            <th rowspan="2"><?= Yii::t('app', 'Dimensi<br>(t x l x p)'); ?></th>
                            <th colspan="2"><?= Yii::t('app', 'Dokumen'); ?></th>
                            <th colspan="2"><?= Yii::t('app', 'Penerimaan Aktual'); ?></th>
                            <th rowspan="2"><?= Yii::t('app', 'Ket'); ?></th>
                            <th colspan="3"><?= Yii::t('app', 'Dikirim'); ?></th>
						</tr>
                        <tr>
                            <th>Qty</th>
                            <th>Vol</th>
                            <th>Qty</th>
                            <th>Vol</th>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Vol</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <!-- <tfoot>
                        <tr>
                            <th colspan="11" style="text-align:right" class="td-kecil">Total Per Page:</th>
                            <th style="text-align:right" id='qty_dok' class="td-kecil"></th>
                            <th style="text-align:right" id='vol_dok' class="td-kecil"></th>
                            <th style="text-align:right" id="qty_act" class="td-kecil"></th>
                            <th style="text-align:right" id="vol_act" class="td-kecil"></th>
                            <th colspan="3"></th>
                            <th style="text-align:right" id='vols' class="td-kecil"></th>
                        </tr>
                        <tr>
                            <th colspan="11" style="text-align:right;" class="td-kecil">Total All Page:</th>
                            <th style="text-align:right;" id='qty_dok_all' class="td-kecil"></th>
                            <th style="text-align:right" id='vol_dok_all' class="td-kecil"></th>
                            <th style="text-align:right" id="qty_act_all" class="td-kecil"></th>
                            <th style="text-align:right" id="vol_act_all" class="td-kecil"></th>
                            <th colspan="3"></th>
                            <th style="text-align:right" id='vols_all' class="td-kecil"></th>
                        </tr>
                    </tfoot> -->
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
            {	targets: 1, class: "td-kecil", },
			{ 	targets: 2, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
                    var date = formatTanggal(data);
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 3, class: "td-kecil text-align-center", },
			{ 	targets: 4,  class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
                    var date = formatTanggal(data);
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 5, class: "td-kecil  text-align-center", 
                render: function ( data, type, full, meta ) {
                    if(full[6]){
                        var ret = full[6];
                    } else {
                        var ret = data;
                    }
					return ret;
                }
            },
            {	targets: 6, class: "td-kecil text-align-center", 
                render: function ( data, type, full, meta ) {
                    var date = formatTanggal(full[7]);
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 7, class: "td-kecil text-align-center", 
                render: function ( data, type, full, meta ) {
					return full[8];
                }
            },
            {	targets: 8, class: "td-kecil text-align-center", 
                render: function ( data, type, full, meta ) {
					return full[9];
                }
            },
            {	targets: 9, class: "td-kecil", 
                render: function ( data, type, full, meta ) {
					return full[10];
                }
            },
            {	targets: 10, class: "td-kecil text-align-center", 
                render: function ( data, type, full, meta ) {
					return full[11];
                }
            },
            {	targets: 11, class: "td-kecil text-align-right", 
                render: function ( data, type, full, meta ) {
					return full[12];
                }
            },
            {	targets: 12, class: "td-kecil text-align-right", 
                render: function ( data, type, full, meta ) {
					return full[13];
                }
            },
            {	targets: 13, class: "td-kecil text-align-right", 
                render: function ( data, type, full, meta ) {
					return full[14];
                }
            },
            {	targets: 14, class: "td-kecil text-align-right", 
                render: function ( data, type, full, meta ) {
					return full[15];
                }
            },
            {	targets: 15, class: "td-kecil", 
                render: function ( data, type, full, meta ) {
					return full[16];
                }
            },
            {	targets: 16, class: "td-kecil text-align-center", 
                render: function ( data, type, full, meta ) {
                    var ret = '-';
                    if(full[17] == 'REALISASI'){
                        ret = full[18];
                    }
					return ret;
                }
            },
            {	targets: 17, class: "td-kecil text-align-center", 
                render: function ( data, type, full, meta ) {
                    var ret = '-';
                    if(full[17] == 'REALISASI'){
                        var date = formatTanggal(full[19]);
                        ret = date;
                    }
					return ret;
                }
            },
            {	targets: 18, class: "td-kecil text-align-right", 
                render: function ( data, type, full, meta ) {
                    var ret = '-'
                    if(full[17] == 'REALISASI'){
                        var ret = full[15];
                    }
					return ret;
                }
            },
        ],
        "fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			changePertanggalLabel();
            // $('#'+oSettings.sTableId+'_wrapper').find('.dataTables_moreaction').html("\
            //     <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
            // ");
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			} 
		},
        // footerCallback: function(row, data, start, end, display) {
		// 	var api = this.api();
        //     // Remove the formatting to get integer data for summation
        //     var intVal = function ( i ) {
        //         return typeof i === 'string' ?
        //             i.replace(/[\$,]/g, '')*1 :
        //             typeof i === 'number' ?
        //                  i : 0;
        //     };

        //     // Qty dokumen
        //     page_qtydok = api.column( 12, { page: 'current'} ).data()
        //         .reduce( function (a, b) {
        //             return intVal(a) + intVal(b);
        //         }, 0 );
            
        //     // Vol dokumen
        //     page_voldok = api.column( 13, { page: 'current'} ).data()
        //         .reduce( function (a, b) {
        //             return intVal(a) + intVal(b);
        //         }, 0 );

        //     // Qty actual
        //     page_qtyact = api.column( 14, { page: 'current'} ).data()
        //         .reduce( function (a, b) {
        //             return intVal(a) + intVal(b);
        //         }, 0 );

        //     // Vol actual
        //     page_volact = api.column( 15, { page: 'current'} ).data()
        //         .reduce( function (a, b) {
        //             return intVal(a) + intVal(b);
        //         }, 0 );

        //     // Vols
        //     page_vols = api.rows({ page: 'current' }).data().reduce(function(total, row) {
        //             if (row[17] && row[17].toString().trim() !== '') {
        //                 total += intVal(row[15]);
        //             }
        //             return total;
        //         }, 0);

        //     // Update footer
        //     $('#qty_dok', api.table().footer() ).html(`${formatNumberForUser(page_qtydok.toFixed(0).toLocaleString())}`);
        //     $('#vol_dok', api.table().footer() ).html(`${formatNumberForUser(page_voldok.toFixed(4).toLocaleString())}`);
        //     $('#qty_act', api.table().footer() ).html(`${formatNumberForUser(page_qtyact.toFixed(0).toLocaleString())}`);
        //     $('#vol_act', api.table().footer() ).html(`${formatNumberForUser(page_volact.toFixed(4).toLocaleString())}`);
        //     $('#vols', api.table().footer() ).html(`${formatNumberForUser(page_vols.toFixed(4).toLocaleString())}`);
        //     $.ajax({
        //         url: "<?php //echo yii\helpers\Url::toRoute('/marketing/laporan/penerimaanJasaKDTotal') ?>?"+$('#form-search-laporan').serialize(),
        //         success: res => {
        //             $('#qty_dok_all', api.table().footer() ).html(`${formatNumberForUser(JSON.parse(res).qtydok.toFixed(0).toLocaleString())}`);
        //             $('#vol_dok_all', api.table().footer() ).html(`${formatNumberForUser(JSON.parse(res).voldok.toFixed(4).toLocaleString())}`);
        //             $('#qty_act_all', api.table().footer() ).html(`${formatNumberForUser(JSON.parse(res).qtyact.toFixed(0).toLocaleString())}`);
        //             $('#vol_act_all', api.table().footer() ).html(`${formatNumberForUser(JSON.parse(res).volact.toFixed(4).toLocaleString())}`); 
        //             $('#vols_all', api.table().footer() ).html(`${formatNumberForUser(JSON.parse(res).vols.toFixed(4).toLocaleString())}`);  
        //         }                                                                                                                                                                                                                                                                                                                                                                                                             
        //     })
		// },
        "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
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
function printout(caraprint) {
    window.open("<?= yii\helpers\Url::toRoute('/marketing/laporan/penerimaanJasakdPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraprint,"",'location=_new, width=1200px, scrollbars=yes');
} 

</script>