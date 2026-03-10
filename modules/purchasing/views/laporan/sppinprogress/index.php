<?php
/* @var $this yii\web\View
 * @var $model TSppDetail
 */

use app\models\TSppDetail;
use yii\helpers\Url;

$this->title = 'Laporan Permintaan Pembelian';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $model]) ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar Permintaan Pembelian Yang Sedang Berjalan ') ?><span id="periode-label" class="font-blue-soft"></span></span>
				</div>
				<div class="tools">
					<a href="javascript:" class="reload"> </a>
					<a href="javascript:" class="fullscreen"> </a>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-laporan">
					<thead>
						<tr>
							<th><?= Yii::t('app', 'No.') ?></th>
							<th><?= Yii::t('app', 'Tanggal Permintaan') ?></th>
							<th><?= Yii::t('app', 'Kode SPP') ?></th>
							<th><?= Yii::t('app', 'Nama Item') ?></th>
							<th><?= Yii::t('app', 'Qty') ?></th>
							<th><?= Yii::t('app', 'Satuan') ?></th>
							<th><?= Yii::t('app', 'Keterangan') ?></th>
							<th><?= Yii::t('app', 'Suplier') ?></th>
							<th><?= Yii::t('app', 'Dept Pemesan') ?></th>
							<th><?= Yii::t('app', 'Tracking') ?></th>
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
"); ?>
<script>
function dtLaporan(){
    $('#table-laporan').dataTable({
        pageLength: 20,
        ajax: {
            url: '<?= Url::toRoute('/purchasing/laporan/sppinprogress') ?>',
            data: {
                dt: 'table-laporan',
                laporan_params: $("#form-search-laporan").serialize(),
            }
        },
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                width: '2%',
                render: function (data, type, full, meta) {
                    return '<div class="text-center">' + (meta.row + 1) + '</div>';
                }
            },
            {
                targets: 1,
                width: '11%',
                render: function (data, type, full, meta) {
                    let date = new Date(data);
                    date = date.toString('dd/MM/yyyy');
                    return '<div class="text-center">' + date + '</div>';
                }
            },
            {
                targets: 2, class: "text-center"
            },
            {
                targets: 4, class: "text-center"
            },
            {
                targets: 5, class: "text-center"
            },
            {
                targets: 9,
                class:'text-center',
                render: function (data) {
                    return `<button class="btn btn-xs btn-outline blue" type="button" onclick="tracking(${data})"> <i class="fa fa-eye"></i></button>`;
                }
            }
        ],
        "fnDrawCallback": function (oSettings) {
            formattingDatatableReport(oSettings.sTableId);
            changePertanggalLabel();
            if (oSettings.aLastSort[0]) {
                $('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
                $('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
            }
        },
        order: [],
        "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
        "bDestroy": true,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/laporan/sppPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function tracking(bhp_id) {
    openModal('<?= Url::toRoute('/purchasing/laporan/trackterimabybhp')?>?bhp_id='+bhp_id, 'modal-tracking');
}
</script>