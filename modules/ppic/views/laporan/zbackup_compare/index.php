<?php
/* @var $this yii\web\View */
$this->title = 'Compare Hasil Loglist vs Penerimaan';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<style>
.Jloglist {background-color: #E9EE94;}
.Jpenerimaan {background-color: #94EE9F;}
.Jno {background-color: #A2B0FB;}
.loglist {background-color: #e8ead5;}
.penerimaan {background-color: #d2ead1;}
.no {background-color: #CDD4F9;}
</style>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $modelTerimaLogalam]) ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject hijau bold"><?= $this->title; ?><span id="periode-label" class="font-blue-soft"></span></span>
				</div>
				<div class="tools">
					<a href="javascript:;" class="reload"> </a>
					<a href="javascript:;" class="fullscreen"> </a>
				</div>
			</div>
			<div class="portlet-body">
                <div class="row">
                    <div class="col-md-12 table-scrollable" style="border: none;">
                        <div class="col-md-6">
                            <table class="table table-striped table-bordered table-hover table-laporan" id="table-loglist" style="border: solid 1px #ececec;">
                                <thead>
                                    <tr>
                                        <th rowspan="3" class="Jno">No.</th>
                                        <th colspan="10" class="Jloglist">LOGLIST</th>
                                    </tr>
                                    <tr>
                                        <th class="td-kecil loglist" rowspan="2">No<br>Grd</th>
                                        <th class="td-kecil loglist" rowspan="2">No<br>Prod</th>
                                        <th class="td-kecil loglist" rowspan="2">No<br>Btg</th>
                                        <th class="td-kecil loglist" rowspan="2">Jenis Kayu</th>
                                        <th class="td-kecil loglist" rowspan="2">Panjang</th>
                                        <th class="td-kecil loglist" colspan="3">Cacat</th>
                                        <th class="td-kecil loglist" rowspan="2">Diameter</th>
                                        <th class="td-kecil loglist" rowspan="2">Volume</th>
                                    </tr>
                                    <tr>
                                        <th class="td-kecil loglist">Panjang</th>
                                        <th class="td-kecil loglist">GB</th>
                                        <th class="td-kecil loglist">GR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-striped table-bordered table-hover table-laporan" id="table-terima" style="border: solid 1px #ececec;">
                                <thead>
                                    <tr>
                                        <th rowspan="3" class="Jno">No.</th>
                                        <th colspan="15" class='Jpenerimaan'>PENERIMAAN</th>
                                    </tr>
                                    <tr>
                                        <th class="td-kecil penerimaan" rowspan="2">QR Code</th>
                                        <th class="td-kecil penerimaan" rowspan="2">No<br>Grd</th>
                                        <th class="td-kecil penerimaan" rowspan="2">No<br>Lap</th>
                                        <th class="td-kecil penerimaan" rowspan="2">No<br>Btg</th>
                                        <th class="td-kecil penerimaan" rowspan="2">Jenis Kayu</th>
                                        <th class="td-kecil penerimaan" rowspan="2">Kode<br>Potong</th>
                                        <th class="td-kecil penerimaan" rowspan="2">Panjang</th>
                                        <th class="td-kecil penerimaan" colspan="4">Diameter</th>
                                        <th class="td-kecil penerimaan" colspan="3">Cacat</th>
                                        <th class="td-kecil penerimaan" rowspan="2">Volume</th>
                                    </tr>
                                    <tr>
                                        <th class="td-kecil penerimaan" style="width: 1000px;">U1</th>
                                        <th class="td-kecil penerimaan">U2</th>
                                        <th class="td-kecil penerimaan">P1</th>
                                        <th class="td-kecil penerimaan">P2</th>
                                        <th class="td-kecil penerimaan" rowspan="2">Panjang</th>
                                        <th class="td-kecil penerimaan" rowspan="2">GB</th>
                                        <th class="td-kecil penerimaan" rowspan="2">GR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<?php $this->registerJs(" 
    mergeSameValue();
", yii\web\View::POS_READY); ?>
<script>
function mergeSameValue(){
	var arr = [];
	var coll = [1,2,3,4,5,6,7,8,9,10];
	$("#table-informasi").find('tr').each(function (r, tr) {
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