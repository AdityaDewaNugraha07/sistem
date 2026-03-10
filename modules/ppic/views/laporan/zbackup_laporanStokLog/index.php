<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Persediaan Log';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);

if (isset($_POST['HPersediaanLog']['kayu_id']) && $_POST['HPersediaanLog']['kayu_id'] != "") {
    $kayu_id = $_POST['HPersediaanLog']['kayu_id'];
    $model->kayu_id = $kayu_id;
    $and_kayu_id = "and a.kayu_id = ".$kayu_id."";
} else {
    $and_kayu_id = "";
}

if (isset($_POST['HPersediaanLog']['tgl_transaksi']) && $_POST['HPersediaanLog']['tgl_transaksi'] != "") {
    $tgl_transaksi = $_POST['HPersediaanLog']['tgl_transaksi'];
    $model->tgl_transaksi = $tgl_transaksi;
    $and_tgl_transaksi = " and tgl_transaksi <= '".$tgl_transaksi."' ";
} else {
    $and_tgl_transaksi = "";
}
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered form-search">
                            <div class="portlet-title">
                                <div class="tools panel-cari">
                                    <button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
                                    <span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian'); ?></span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?php $form = \yii\bootstrap\ActiveForm::begin([
                                    'id' => 'form-search-laporan',
                                    'fieldConfig' => [
                                        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                                        'labelOptions'=>['class'=>'col-md-4 control-label'],
                                    ],
                                    'enableClientValidation'=>false
                                ]); ?>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <?= $form->field($model, 'tgl_transaksi',[
                                                                    'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo $form->field($model, 'kayu_id')->dropDownList(\app\models\MKayu::getOptionList(),['prompt'=>'All'])->label(Yii::t('app', 'Jenis Kayu')); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php echo $this->render('@views/apps/form/tombolSearch') ?>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
                <div class="row" style="padding-left: 15px; padding-right: 15px;">
                    <div class="col-md-12 table-scrollable" style="padding-left: 0px; padding-right: 0px;">
                        <table class="table table-striped table-bordered table-hover" id="table-rekap">
                            <thead>
                                <tr>
                                    <th rowspan="2"><?= Yii::t('app', 'No.'); ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'Kayu') ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'No. QRcode') ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'No. Grade') ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'No. Lap') ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'No. Batang') ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'Pcs') ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'Panjang') ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'Kode Potong') ?></th>
                                    <th colspan="5">Diameter</th>
                                    <th colspan="3">Cacat</th>
                                    <th rowspan="2"><?= Yii::t('app', 'Volume<br>(m<sup>3</sup>>)') ?></th>
                                </tr>
                                <tr>
                                    <th>Ujung 1</th>
                                    <th>Pangkal 1</th>
                                    <th>Ujung 2</th>
                                    <th>Pangkal 2</th>
                                    <th>Rata-rata</th>
                                    <th>Panjang</th>
                                    <th>Gubal</th>
                                    <th>Growong</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "select a.no_barcode, b.kayu_nama ". 
                                    "   from h_persediaan_log a ". 
                                    "   left join m_kayu b on b.kayu_id = a.kayu_id ". 
                                    "   where 1=1 ". 
                                    "   and lokasi ilike '%LOG ALAM%' ".
                                    "   ".$and_kayu_id."".
                                    "   ".$and_tgl_transaksi."".
                                    "   group by a.no_barcode, b.kayu_nama ".
                                    "   ";
                            $query = Yii::$app->db->createCommand($sql)->queryAll();
                            $i = 1;
                            $total_batang = 0;
                            $total_volume = 0;
                            foreach ($query as $kolom) {
                                // in 
                                $sql_fisik_pcs_in = "select sum(fisik_pcs) as masuk from h_persediaan_log ".                                                        
                                                    "   where no_barcode = '".$kolom['no_barcode']."' ".
                                                    "   and lokasi = 'GUDANG LOG ALAM' ".
                                                    "   and status = 'IN'".
                                                    "   ".$and_tgl_transaksi."".
                                                    "   ";
                                $fisik_pcs_in = Yii::$app->db->createCommand($sql_fisik_pcs_in)->queryScalar();

                                // out
                                $sql_fisik_pcs_out = "select sum(fisik_pcs) as keluar from h_persediaan_log ".
                                                    "   where no_barcode = '".$kolom['no_barcode']."' ".
                                                    "   and (lokasi = 'PRODUKSI LOG ALAM' OR lokasi = 'PENJUALAN LOG ALAM') ".
                                                    "   and status = 'OUT'".
                                                    "   ".$and_tgl_transaksi."".
                                                    "   ";
                                $fisik_pcs_out = Yii::$app->db->createCommand($sql_fisik_pcs_out)->queryScalar();

                                $fisik_pcs_stok = $fisik_pcs_in - $fisik_pcs_out;
                                if ($fisik_pcs_stok > 0) {
                                $sql_lagi = "select a.no_barcode, b.kayu_nama, a.no_barcode, a.no_grade, a.no_btg, a.no_lap, a.fisik_volume, a.fisik_panjang, a.pot ". 
                                                "   , a.diameter_ujung1, a.diameter_pangkal1, a.diameter_ujung2, a.diameter_pangkal2, a.cacat_panjang, a.cacat_gb, a.cacat_gr ". 
                                                "   from h_persediaan_log a ". 
                                                "   left join m_kayu b on b.kayu_id = a.kayu_id ". 
                                                "   where 1=1 ". 
                                                "   and no_barcode = '".$kolom['no_barcode']."' ".
                                                "   and b.kayu_nama = '".$kolom['kayu_nama']."' ".
                                                "   ".$and_tgl_transaksi."".
                                                "   ";
                                $query_lagi = Yii::$app->db->createCommand($sql_lagi)->queryOne();
                                $diameter_rata = ($query_lagi['diameter_ujung1'] + $query_lagi['diameter_pangkal1'] + $query_lagi['diameter_ujung2'] + $query_lagi['diameter_pangkal2']) / 4;
                            ?>
                            <tr>
                                <td class="td-kecil text-center"><?php echo $i;?></td>
                                <td class="td-kecil text-left"><?php echo $query_lagi['kayu_nama'];?></td>
                                <td class="td-kecil text-center"><?php echo $query_lagi['no_barcode'];?></td>
                                <td class="td-kecil text-center"><?php echo $query_lagi['no_grade'];?></td>
                                <td class="td-kecil text-center"><?php echo $query_lagi['no_lap'];?></td>
                                <td class="td-kecil text-center"><?php echo $query_lagi['no_btg'];?></td>
                                <td class="td-kecil text-right"><?php echo $fisik_pcs_stok;?></td>
                                <td class="td-kecil text-right"><?php echo $query_lagi['fisik_panjang'];?></td>
                                <td class="td-kecil text-center"><?php echo $query_lagi['pot'];?></td>
                                <td class="td-kecil text-right"><?php echo $query_lagi['diameter_ujung1'];?></td>
                                <td class="td-kecil text-right"><?php echo $query_lagi['diameter_pangkal1'];?></td>
                                <td class="td-kecil text-right"><?php echo $query_lagi['diameter_ujung2'];?></td>
                                <td class="td-kecil text-right"><?php echo $query_lagi['diameter_pangkal2'];?></td>
                                <td class="td-kecil text-right"><?php echo $diameter_rata;?></td>
                                <td class="td-kecil text-right"><?php echo $query_lagi['cacat_panjang'];?></td>
                                <td class="td-kecil text-right"><?php echo $query_lagi['cacat_gb'];?></td>
                                <td class="td-kecil text-right"><?php echo $query_lagi['cacat_gr'];?></td>
                                <td class="td-kecil text-right"><?php echo $query_lagi['fisik_volume'];?></td>
                            </tr>
                            <?php
                                $i++;
                                $total_batang += $fisik_pcs_stok;
                                $total_volume += $query_lagi['fisik_volume'];
                                }
                            }
                            ?>
                            <tr>
                                <th colspan="6" class="text-center">Jumlah</th>
                                <th class="text-right"><?php echo $total_batang;?></th>
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
                                <th class="text-right"><?php echo $total_volume;?></th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END EXAMPLE TABLE PORTLET-->

<?php $this->registerJs("
	$('#form-search-laporan').submit(function(){
	});
	formconfig(); 
", yii\web\View::POS_READY); ?>
<script>
/*function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/ppic/laporan/laporanStokLogPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}*/
</script>