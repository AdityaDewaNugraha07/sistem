<?php
/* @var $this yii\web\View */
$this->title = 'Rekap Persediaan Log';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
\app\assets\InputMaskAsset::register($this);

if (isset($_POST['HPersediaanLog']['kayu_id']) && $_POST['HPersediaanLog']['kayu_id'] != "") {
    $kayu_id = $_POST['HPersediaanLog']['kayu_id'];
    $model->kayu_id = $kayu_id;
    $and_kayu_id = "and a.kayu_id = ".$kayu_id."";
} else {
    $and_kayu_id = "";
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
                                    <th rowspan="2"><?= Yii::t('app', 'No'); ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'Kayu'); ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'Status FSC'); ?></th> <!-- TAMBAH FSC -->
                                    <th colspan="2"><?= Yii::t('app', '< 40'); ?></th>
                                    <th colspan="2"><?= Yii::t('app', '40 - 49'); ?></th>
                                    <th colspan="2"><?= Yii::t('app', '50 - 59'); ?></th>
                                    <th colspan="2"><?= Yii::t('app', '60 - 69'); ?></th>
                                    <th colspan="2"><?= Yii::t('app', '70 >='); ?></th>
                                    <th colspan="2"><?= Yii::t('app', 'Total'); ?></th>
                                </tr>
                                </tr>
                                    <th>Pcs</th>
                                    <th>M<sup>3</sup></th>
                                    <th>Pcs</th>
                                    <th>M<sup>3</sup></th>
                                    <th>Pcs</th>
                                    <th>M<sup>3</sup></th>
                                    <th>Pcs</th>
                                    <th>M<sup>3</sup></th>
                                    <th>Pcs</th>
                                    <th>M<sup>3</sup></th>
                                    <th>Pcs</th>
                                    <th>M<sup>3</sup></th>
                                <tr>
                            </thead>
                            <?php // TAMBAH FSC - tambah kolom fsc + group bynya
                            $sql = "select a.kayu_id, b.kayu_nama, fsc". 
                                    "   from h_persediaan_log a ". 
                                    "   left join m_kayu b on b.kayu_id = a.kayu_id ". 
                                    "   where ". 
                                    "   lokasi ilike '%LOG ALAM%'  ".
                                    "   ".$and_kayu_id."".
                                    "   group by a.kayu_id, b.kayu_nama, fsc".
                                    "   order by b.kayu_nama".
                                    "   ";
                            $query = Yii::$app->db->createCommand($sql)->queryAll();
                            ?>
                            <tbody>
                                <?php
                                $i = 1;
                                $total_bstok40 = 0;
                                $total_bstok4049 = 0;
                                $total_bstok5059 = 0;
                                $total_bstok6069 = 0;
                                $total_bstok70 = 0;
                                $total_vstok40 = 0;
                                $total_vstok4049 = 0;
                                $total_vstok5059 = 0;
                                $total_vstok6069 = 0;
                                $total_vstok70 = 0;

                                $total_b = 0;
                                $total_v = 0;
                                foreach ($query as $kolom) {
                                    // TAMBAH FSC - untuk menambah kondisi di query berdasarkan status fsc
                                    if(isset($kolom['fsc'])){
                                        if($kolom['fsc'] === false){
                                            $kolom_fsc = 'false';
                                        } else {
                                            $kolom_fsc = 'true';
                                        }
                                    } else {
                                        $kolom_fsc = 'false';
                                    }
                                    
                                    // kolom status fsc
                                    if($kolom_fsc === 'true'){
                                        $status_fsc = 'FSC 100%';
                                    } else {
                                        $status_fsc = 'Non FSC';
                                    }
                                    //eo FSC

                                    // BATANG - VOLUME
                                    $sql_40 = " SELECT  SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS batang, 
                                                        SUM(CASE WHEN status = 'IN' THEN fisik_volume ELSE 0 END) AS vol_in,
                                                        SUM(CASE WHEN status = 'OUT' THEN fisik_volume ELSE 0 END) AS vol_out
                                                FROM h_persediaan_log 
                                                WHERE fisik_diameter > 0 and fisik_diameter < 40 and lokasi ilike '%LOG ALAM%' 
                                                    and kayu_id = ".$kolom['kayu_id']." and fsc = ".$kolom_fsc."
                                                GROUP BY kayu_id";
                                    $data_40 = Yii::$app->db->createCommand($sql_40)->queryOne();

                                    $sql_4049 = " SELECT SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS batang, 
                                                         SUM(CASE WHEN status = 'IN' THEN fisik_volume ELSE 0 END) AS vol_in,
                                                         SUM(CASE WHEN status = 'OUT' THEN fisik_volume ELSE 0 END) AS vol_out
                                                FROM h_persediaan_log 
                                                WHERE fisik_diameter between 40 and 49 and lokasi ilike '%LOG ALAM%' 
                                                    and kayu_id = ".$kolom['kayu_id']." and fsc = ".$kolom_fsc."
                                                GROUP BY kayu_id";
                                    $data_4049 = Yii::$app->db->createCommand($sql_4049)->queryOne();

                                    $sql_5059 = " SELECT SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS batang, 
                                                         SUM(CASE WHEN status = 'IN' THEN fisik_volume ELSE 0 END) AS vol_in,
                                                         SUM(CASE WHEN status = 'OUT' THEN fisik_volume ELSE 0 END) AS vol_out
                                                FROM h_persediaan_log
                                                WHERE fisik_diameter between 50 and 59 and lokasi ilike '%LOG ALAM%' 
                                                    and kayu_id = ".$kolom['kayu_id']." and fsc = ".$kolom_fsc."
                                                GROUP BY kayu_id";
                                    $data_5059 = Yii::$app->db->createCommand($sql_5059)->queryOne();

                                    $sql_6069 = " SELECT SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS batang, 
                                                         SUM(CASE WHEN status = 'IN' THEN fisik_volume ELSE 0 END) AS vol_in,
                                                         SUM(CASE WHEN status = 'OUT' THEN fisik_volume ELSE 0 END) AS vol_out
                                                FROM h_persediaan_log 
                                                WHERE fisik_diameter between 60 and 69 and lokasi ilike '%LOG ALAM%' 
                                                    and kayu_id = ".$kolom['kayu_id']." and fsc = ".$kolom_fsc."
                                                GROUP BY kayu_id";
                                    $data_6069 = Yii::$app->db->createCommand($sql_6069)->queryOne();

                                    $sql_70 = " SELECT  SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS batang, 
                                                        SUM(CASE WHEN status = 'IN' THEN fisik_volume ELSE 0 END) AS vol_in,
                                                        SUM(CASE WHEN status = 'OUT' THEN fisik_volume ELSE 0 END) AS vol_out
                                                FROM h_persediaan_log
                                                WHERE fisik_diameter >= 70 and lokasi ilike '%LOG ALAM%' 
                                                    and kayu_id = ".$kolom['kayu_id']." and fsc = ".$kolom_fsc."
                                                GROUP BY kayu_id";
                                    $data_70 = Yii::$app->db->createCommand($sql_70)->queryOne();
                                    
                                    // hasil batang
                                    $bstok40    = isset($data_40['batang'])?$data_40['batang']:0;
                                    $bstok4049  = isset($data_4049['batang'])?$data_4049['batang']:0;
                                    $bstok5059  = isset($data_5059['batang'])?$data_5059['batang']:0;
                                    $bstok6069  = isset($data_6069['batang'])?$data_6069['batang']:0;
                                    $bstok70    = isset($data_70['batang'])?$data_70['batang']:0;

                                    // hasil volume
                                    $vstok40    = $data_40['vol_in'] - $data_40['vol_out'];
                                    $vstok4049  = $data_4049['vol_in'] - $data_4049['vol_out'];
                                    $vstok5059  = $data_5059['vol_in'] - $data_5059['vol_out'];
                                    $vstok6069  = $data_6069['vol_in'] - $data_6069['vol_out'];
                                    $vstok70    = $data_70['vol_in'] - $data_70['vol_out'];

                                    if ($vstok40 != 0 || $vstok4049 != 0 || $vstok5059 != 0 || $vstok6069 != 0 || $vstok70 != 0) {
                                    ?>
                                        <tr>
                                            <td class="td-kecil text-center" style="width: 50px;"><?php echo $i;?></td>
                                            <td class="td-kecil"><?php echo $kolom['kayu_nama']; ?></td>
                                            <td class="td-kecil text-center"><?php echo $status_fsc; ?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($bstok40);?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($vstok40, 2);?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($bstok4049);?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($vstok4049, 2);?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($bstok5059);?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($vstok5059, 2);?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($bstok6069);?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($vstok6069, 2);?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($bstok70);?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($vstok70, 2);?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($bstok40 + $bstok4049 + $bstok5059 + $bstok6069 + $bstok70);?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($vstok40 + $vstok4049 + $vstok5059 + $vstok6069 + $vstok70,2);?></td>
                                        </tr>
                                    <?php
                                    $i++;
                                    }
                                    ?>
                                <?php
                                    $total_bstok40 += $bstok40;
                                    $total_bstok4049 += $bstok4049;
                                    $total_bstok5059 += $bstok5059;
                                    $total_bstok6069 += $bstok6069;
                                    $total_bstok70 += $bstok70;

                                    $total_vstok40 += $vstok40;
                                    $total_vstok4049 += $vstok4049;
                                    $total_vstok5059 += $vstok5059;
                                    $total_vstok6069 += $vstok6069;
                                    $total_vstok70 += $vstok70;
                                    $total_b = $total_bstok40 + $total_bstok4049 + $total_bstok5059 + $total_bstok6069 + $total_bstok70;
                                    $total_v = $total_vstok40 + $total_vstok4049 + $total_vstok5059 + $total_vstok6069 + $total_vstok70;
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Jumlah</th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_bstok40);?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_vstok40,2);?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_bstok4049);?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_vstok4049,2);?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_bstok5059);?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_vstok5059,2);?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_bstok6069);?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_vstok6069,2);?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_bstok70);?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_vstok70,2);?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_b);?></th>
                                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_v,2);?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<?php $this->registerJs("
	$('#form-search-rekap').submit(function(){
		return false;
	});
", yii\web\View::POS_READY); ?>
<script>

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/ppic/rekap/rekapStokLogPrint') ?>?"+$('#form-search-rekap').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>