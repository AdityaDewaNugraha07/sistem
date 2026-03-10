<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Monitoring Order Export';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
#table-laporan thead th{
	font-size: 1.2rem;
}
</style>
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $model]) ?>
			<div class="row">
				<div class="col-md-12">
					<a class="btn btn-icon-only btn-default tooltips pull-right" onclick="printout('EXCEL')" data-original-title="Export to Excel"><i class="fa fa-table"></i></a>
					<a class="btn btn-icon-only btn-default tooltips pull-right" onclick="printout('PRINT')" data-original-title="Print Out"><i class="fa fa-print"></i></a>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-laporan">
					<thead>
						<tr>
							<th style="width:30px;" rowspan="2">No.</th>
							<th style="line-height: 1; width:110px;" rowspan="2"><?= Yii::t('app', 'Contract No.') ?></th>
							<th style="line-height: 1;" rowspan="2"><?= Yii::t('app', 'Commodity') ?></th>
							<th style="line-height: 1; width:100px;" rowspan="2"><?= Yii::t('app', 'Size/Profile'); ?></th>
							<th style="line-height: 1; " colspan="3"><?= Yii::t('app', 'Price'); ?></th>
							<th style="line-height: 1; width:40px;" rowspan="2"><?= Yii::t('app', 'Term Of<br>Payment'); ?></th>
							<th style="line-height: 1; width:50px;" rowspan="2"><?= Yii::t('app', 'Code'); ?></th>
							<th style="line-height: 1; width:80px;" rowspan="2"><?= Yii::t('app', 'Planning'); ?></th>
							<th style="line-height: 1; " colspan="5"><?= Yii::t('app', 'Actual'); ?></th>
						</tr>
						<tr>
                                                        <th style="line-height: 1; width:50px;"><?= Yii::t('app', 'Volume') ?></th>
							<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'USD') ?></th>
							<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'Terms') ?></th>
							<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'Inv. No') ?></th>
							<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'Inv. Date') ?></th>
							<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'ETD') ?></th>
							<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'ETA') ?></th>
							<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'Payment<br>Date') ?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery.rowspanizer.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
	formconfig(); 
	search();
	$('select[name*=\"[cust_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Filter By Buyer',
	});
", yii\web\View::POS_READY); ?>
<script>
function search(){
	$("#table-laporan").addClass("animation-loading");
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/exim/laporan/monitoringOrder']); ?>',
		type   : 'POST',
		data   : { formData: $("#form-search-laporan").serialize()},
		success: function (data) {
			$("#table-laporan > tbody").html("");
			if(data.html){
				$("#table-laporan > tbody").html(data.html);
				mergeSameValue();
			}
			$("#table-laporan").removeClass("animation-loading");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/exim/laporan/monitoringOrderPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
function mergeSameValue(){
	var arr = [];
	var coll = [0,1,2,9,10,11,12];
	$("#table-laporan").find('tr').each(function (r, tr) {
		$(this).find('td').each(function (d, td) {
			if ( coll.indexOf(d) !== -1) {
				var $td = $(td);
				var v_dato = $td.html();
				if(typeof arr[d] != 'undefined' && 'dato' in arr[d] && arr[d].dato == v_dato) {
					var rs = arr[d].elem.data('rowspan');
					if(rs == 'undefined' || isNaN(rs)) rs = 1;
					arr[d].elem.data('rowspan', parseInt(rs) + 1).addClass('rowspan-combine');
					$td.addClass('rowspan-remove');
				} else {
					arr[d] = {dato: v_dato, elem: $td};
				};
			}
		});
	});
	$('.rowspan-combine').each(function (r, tr) {
	  var $this = $(this);
	  $this.attr('rowspan', $this.data('rowspan')).css({'vertical-align': 'middle'});
	});
	$('.rowspan-remove').remove();
}
</script>