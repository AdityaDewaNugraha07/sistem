<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Umur Stock';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
</style>
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $model]) ?>
			<div class="portlet-body" style="padding-top: 0px">
				<a id="geserkanan" class="btn btn-icon-only btn-default tooltips pull-right" data-original-title="Geser Kanan"><i class="icon-arrow-right"></i></a>
				<a id="geserkiri" class="btn btn-icon-only btn-default tooltips pull-right" data-original-title="Geser Kiri" style="margin-right: 3px;"><i class="icon-arrow-left"></i></a>
				<a class="btn btn-icon-only btn-default tooltips pull-right" onclick="printout('EXCEL')" data-original-title="To Excel" style="margin-right: 3px;"><i class="icon-grid"></i></a>
				<!--<a class="btn btn-icon-only btn-default tooltips pull-right" onclick="printout('PRINT')" data-original-title="Print Out" style="margin-right: 3px;"><i class="fa fa-print"></i></a>-->
				<div class="table-scrollable">
				<div class="outer" style="position:relative">
					<div class="inner" style="overflow-x:scroll; overflow-y:visible; width:420px; margin-left:615px;">
						<table class="table table-striped table-bordered table-hover" id="table-laporan" style="table-layout: fixed; width: 100%; margin-bottom: 10px;">
							<thead>
								
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
					<table class="table table-striped table-bordered table-hover" id="table-laporan2">
						<tfoot>
							<tr style="background-color: #F1F4F7">
								<td style="width: 385px; padding:10px; font-weight: 600; text-align: right; font-size: 1.3rem;">TOTAL &nbsp;</td>
								<td id="total_palet" style="width: 60px; vertical-align: middle; text-align: center; font-weight: 600; font-size: 1.3rem;"></td>
								<td id="total_qty" style="width: 85px; vertical-align: middle; text-align: right; font-weight: 600; font-size: 1.3rem;"></td>
								<td id="total_kubikasi" style="width: 85px; vertical-align: middle; text-align: right; font-weight: 600; font-size: 1.3rem;"></td>
								<td></td>
							</tr>
						</tfoot>
					</table>
				</div>
				
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<?php $this->registerJs("
	$('#form-search-laporan').submit(function(){
		getData();
		return false;
	});
	formconfig(); 
	setFilterByProdukGroup(function(){
//		getData();
	});
	$('#geserkanan').click(function(){
		$('.inner').animate({scrollLeft: $('.inner').scrollLeft() + 200 }, 500);
	})
	$('#geserkiri').click(function(){
		$('.inner').animate({scrollLeft: $('.inner').scrollLeft() - 200 }, 500);
	})
	fixedHeader();
", yii\web\View::POS_READY); ?>
<script>
function fixedHeader(){
	$('table').each(function(index){
		var table = $(this);
		var fixedheader = $('<div class="header-fixed" style="position: fixed; top: 0px; left: 277px; z-index: 9999; margin-top: 50px; background-color: rgb(227, 231, 234);"></div>');
		var tableOffset = table.offset().top;
		var tableleft = table.offset().left;
		var tablewidth = table.width();
		var tableheight = table.height();
		if($('thead',table).length < 1) {
			if($('th',table).length > 0){
				$('th',table).eq(0).parent().wrap('<thead class="theader"></thead>');
				$('.theader',table).prependTo(table);
			} 
			else $('tr',table).eq(0).wrap('<thead></thead>');
		}

		var $header = $("thead", table).clone();
//		var newTable = $('<table class="'+table.attr('class')+'"></table>');
		var newTable = $('<table class="table table-striped table-bordered table-hover dataTable no-footer" style="margin: 0px; width: 100%;">\n\
							<thead>\n\
								<th rowspan="1" style="width: 25px; font-size: 1.2rem;">No.</th>\n\
								<th rowspan="1" style="width: 285px; font-size: 1.2rem;">Nama Produk</th>\n\
								<th rowspan="1" style="width: 40px; font-size: 1.2rem;">Lokasi<br>Gudang</th>\n\
								<th rowspan="1" style="width: 50px; font-size: 1.2rem;">Total<br>Palet</th>\n\
								<th rowspan="1" style="width: 75px; font-size: 1.2rem;">Total<br>Qty</th>\n\
								<th rowspan="1" style="width: 75px; font-size: 1.2rem;">Total<br>Kubikasi M<sup>3</sup></th>\n\
							</thead>\n\
						</table>');
		$header.appendTo(newTable);
		newTable.css('margin','0');
		var $fixedHeader = fixedheader.append(newTable);

		table.find('th').each(function(index, valuee){
			//console.log($(this).width()+'px');
			$header.find('th').eq(index).css('width',$(this).width()+'px');
		});

		$(window).on("scroll", function() {
			var offset = $(this).scrollTop();
			tableOffset = table.offset().top;
			tablewidth = table.width();
			tableheight = table.height();
			if (offset >= tableOffset && $fixedHeader.is(":hidden") && offset < tableOffset+tableheight) {
				fixedheader.appendTo('body');
				$fixedHeader.fadeIn(100);
				table.addClass('stuck');
			}
			else if (offset < tableOffset || offset > tableOffset+tableheight-30) {
				$fixedHeader.fadeOut(150);
				table.removeClass('stuck');
			}
		});

	});
}
function getData(){
	$('.table-scrollable').addClass('animation-loading');
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/gudang/laporan/deadStock']); ?>',
		type   : 'GET',
		data   : {params:$("#form-search-laporan").serialize()},
		success: function (data) {
			if(data.head){
				$('#table-laporan > thead').html(data.head);
			}
			if(data.html){
				$('#table-laporan > tbody').html(data.html);
			}else{
				$('#table-laporan > tbody').html("<tr><td colspan='7' style='font-size:1.1rem;'><i><center>Data tidak ditemukan</center></i></td></tr>");
			}
			setTotal();
            $('.table-scrollable').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setFilterByProdukGroup(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:none");
	$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:none");
	$("#<?= yii\bootstrap\Html::getInputId($model, "glue") ?>").parents(".form-group").attr("style","display:none");
	$("#<?= yii\bootstrap\Html::getInputId($model, "profil_kayu") ?>").parents(".form-group").attr("style","display:none");
	$("#<?= yii\bootstrap\Html::getInputId($model, "kondisi_kayu") ?>").parents(".form-group").attr("style","display:none");
	setDropdownJenisKayu(function(){
		setDropdownGrade(function(){
			setDropdownGlue(function(){
				setDropdownProfilKayu(function(){
					setDropdownKondisiKayu(function(){
						if(callback){ callback(); }
					});
				});
			});
		});
	});
	if(jenis_produk == "Plywood" || jenis_produk == "Lamineboard" || jenis_produk == "Platform"){
		$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "glue") ?>").parents(".form-group").attr("style","display:");
	}
	if(jenis_produk == "Sawntimber"){
		$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "kondisi_kayu") ?>").parents(".form-group").attr("style","display:");
	}
	if(jenis_produk == "Moulding"){
		$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "profil_kayu") ?>").parents(".form-group").attr("style","display:");
	}
	if(jenis_produk == "Veneer"){
		$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
	}
}
function setDropdownJenisKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'jenis_kayu') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownJenisKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'jenis_kayu') ?>").html(data.html);
				$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").prepend('<option value="" selected="selected">All</option>');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownGrade(callback=null){
    var jenis_produk = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownGrade']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'grade') ?>").html(data.html);
				$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").prepend('<option value="" selected="selected">All</option>');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownGlue(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'glue') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownGlue']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'glue') ?>").html(data.html);
				$("#<?= yii\bootstrap\Html::getInputId($model, "glue") ?>").prepend('<option value="" selected="selected">All</option>');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownProfilKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'profil_kayu') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownProfilKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'profil_kayu') ?>").html(data.html);
				$("#<?= yii\bootstrap\Html::getInputId($model, "profil_kayu") ?>").prepend('<option value="" selected="selected">All</option>');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function setDropdownKondisiKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'kondisi_kayu') ?>").html("");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/ppic/produk/setDropdownKondisiKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'kondisi_kayu') ?>").html(data.html);
				$("#<?= yii\bootstrap\Html::getInputId($model, "kondisi_kayu") ?>").prepend('<option value="" selected="selected">All</option>');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setTotal(){
	var total_palet = 0;
	var total_qty = 0;
	var total_kubikasi = 0;
	$("#table-laporan > tbody > tr").each(function(){
		total_palet += unformatNumber( $(this).find('.total_palet').val() );
		total_qty += unformatNumber( $(this).find('.total_qty').val() );
		total_kubikasi += unformatNumber( $(this).find('.total_kubikasi').val() );
	});
	$("#total_palet").html(formatNumberForUser(total_palet));
	$("#total_qty").html(formatNumberForUser(total_qty));
	$("#total_kubikasi").html(formatNumberForUser(total_kubikasi));
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/gudang/laporan/DeadStockPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>