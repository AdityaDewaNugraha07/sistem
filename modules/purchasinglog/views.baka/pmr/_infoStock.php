<?php
/* @var $this yii\web\View */
$this->title = 'Info Stock';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
\app\assets\InputMaskAsset::register($this);

$model = new \app\models\HPersediaanLog();
if ($jenis_log == "LA") {
    $lokasi_jenis_log = "GUDANG LOG ALAM";
    $produksi_jenis_log = "PRODUKSI LOG ALAM";
    $penjualan_jenis_log = "PENJUALAN LOG ALAM";
    $judul_jenis_log = "Log Alam";
    $ilike_lokasi = 'LOG ALAM';
    $infoStock = "";
} else if ($jenis_log == "LS") {
    $lokasi_jenis_log = "GUDANG LOG SENGON";
    $produksi_jenis_log = "PRODUKSI LOG SENGON";
    $penjualan_jenis_log = "PENJUALAN LOG SENGON";
    $judul_jenis_log = "Log Sengon";
    $ilike_lokasi = 'SENGON';
    $infoStock = "InfoStockLS";
} else {
    $lokasi_jenis_log = "GUDANG LOG JABON";
    $produksi_jenis_log = "PRODUKSI LOG JABON";
    $penjualan_jenis_log = "PENJUALAN LOG JABON";
    $judul_jenis_log = "Log Jabon";
    $ilike_lokasi = 'JABON';
    $infoStock = "InfoStockLJ";
}
?>
<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-infoStock" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', $this->title)." ".$judul_jenis_log; ?></h4>
            </div>
            
            <?php
            if ($jenis_log == "LA") {
            ?>
                <div class="modal-body">
                    <div class="row" style="padding-left: 15px; padding-right: 15px;">
                        <div class="col-md-12 table-scrollable" style="padding-left: 0px; padding-right: 0px;">
                            <table class="table table-striped table-bordered table-hover" id="table-rekap">
                                <thead>
                                    <tr>
                                        <th rowspan="2"><?= Yii::t('app', 'No.'); ?></th>
                                        <th rowspan="2"><?= Yii::t('app', 'Kayu.'); ?></th>
                                        <th colspan="2"><?= Yii::t('app', '< 40'); ?></th>
                                        <th colspan="2"><?= Yii::t('app', '40 - 49'); ?></th>
                                        <th colspan="2"><?= Yii::t('app', '50 - 59'); ?></th>
                                        <th colspan="2"><?= Yii::t('app', '60 - 69'); ?></th>
                                        <th colspan="2"><?= Yii::t('app', '70 >'); ?></th>
                                    </tr>
                                    </tr>
                                        <th>Pcs</th>
                                        <th>Volume</th>
                                        <th>Pcs</th>
                                        <th>Volume</th>
                                        <th>Pcs</th>
                                        <th>Volume</th>
                                        <th>Pcs</th>
                                        <th>Volume</th>
                                        <th>Pcs</th>
                                        <th>Volume</th>
                                    <tr>
                                </thead>
                                <?php
                                $sql = "select a.kayu_id, b.kayu_nama ". 
                                        "   from h_persediaan_log a ". 
                                        "   left join m_kayu b on b.kayu_id = a.kayu_id ". 
                                        "   where 1=1 ". 
                                        "   and lokasi ilike '%".$ilike_lokasi."%'".
                                        "   group by a.kayu_id, b.kayu_nama ".
                                        "   ";
                                $query = Yii::$app->db->createCommand($sql)->queryAll();
                                ?>
                                <tbody>
                                    <?php
                                    $xxx = array('fisik_diameter < 40', 
                                                    'fisik_diameter between 40 and 49', 
                                                    'fisik_diameter between 50 and 59', 
                                                    'fisik_diameter between 60 and 69', 
                                                    'fisik_diameter > 70');
                                    $i = 1;
                                    $tb_stok40 = 0;
                                    $tb_stok4049 = 0;
                                    $tb_stok5059 = 0;
                                    $tb_stok6069 = 0;
                                    $tb_stok70 = 0;
                                    $tv_stok40 = 0;
                                    $tv_stok4049 = 0;
                                    $tv_stok5059 = 0;
                                    $tv_stok6069 = 0;
                                    $tv_stok70 = 0;

                                    $tb = 0;
                                    $tv = 0;
                                    
                                    foreach ($query as $kolom) {
                                        ?>
                                        <tr>
                                            <td class="td-kecil text-center" style="width: 50px;"><?php echo $i;?></td>
                                            <td class="td-kecil"><?php echo $kolom['kayu_nama'];?></td>
                                        <?php
                                        foreach ($xxx as $xx) {
                                            $sql_fisik_in = "select sum(fisik_pcs) from h_persediaan_log where 1=1 ". 
                                                                "   and ".$xx." ".
                                                                "   and status = 'IN' ". 
                                                                "   and kayu_id = ".$kolom['kayu_id']."". 
                                                                "   ";
                                            $fisik_in = Yii::$app->db->createCommand($sql_fisik_in)->queryScalar();
                                            ($fisik_in == NULL) || empty($fisik_in) ? $fisik_in = 0 : $fisik_in = $fisik_in;

                                            $sql_fisik_out = "select sum(fisik_pcs) from h_persediaan_log where 1=1 ". 
                                                                "   and ".$xx." ".
                                                                "   and status = 'OUT' ". 
                                                                "   and kayu_id = ".$kolom['kayu_id']."". 
                                                                "   ";
                                            $fisik_out = Yii::$app->db->createCommand($sql_fisik_out)->queryScalar();
                                            ($fisik_out == NULL) || empty($fisik_out) ? $fisik_out = 0 : $fisik_out = $fisik_out;

                                            $sql_volume_in = "select sum(fisik_volume) from h_persediaan_log where 1=1 ". 
                                                                "   and ".$xx." ".
                                                                "   and status = 'IN' ". 
                                                                "   and kayu_id = ".$kolom['kayu_id']."". 
                                                                "   ";
                                            $volume_in = Yii::$app->db->createCommand($sql_volume_in)->queryScalar();
                                            ($volume_in == NULL) || empty($volume_in) ? $volume_in = 0 : $volume_in = $volume_in;

                                            $sql_volume_out = "select sum(fisik_volume) from h_persediaan_log where 1=1 ". 
                                                                "   and ".$xx." ".
                                                                "   and status = 'OUT' ". 
                                                                "   and kayu_id = ".$kolom['kayu_id']."". 
                                                                "   ";
                                            $volume_out = Yii::$app->db->createCommand($sql_volume_out)->queryScalar();
                                            ($volume_out == NULL) || empty($volume_out) ? $volume_out = 0 : $volume_out = $volume_out;

                                            $fs = $fisik_in - $fisik_out;
                                            $vs = $volume_in - $volume_out;

                                            $xx == 'fisik_diameter < 40' ? $tb_stok40 = $tb_stok40 + $fs : $tb_stok40 = $tb_stok40;
                                            $xx == 'fisik_diameter between 40 and 49' ? $tb_stok4049 = $tb_stok4049 + $fs : $tb_stok4049 = $tb_stok4049;
                                            $xx == 'fisik_diameter between 50 and 59' ? $tb_stok5059 = $tb_stok5059 + $fs : $tb_stok5059 = $tb_stok5059;
                                            $xx == 'fisik_diameter between 60 and 69' ? $tb_stok6069 = $tb_stok6069 + $fs : $tb_stok6069 = $tb_stok6069;
                                            $xx == 'fisik_diameter > 70' ? $tb_stok70 = $tb_stok70 + $fs : $tb_stok70 = $tb_stok70;

                                            $xx == 'fisik_diameter < 40' ? $tv_stok40 = $tv_stok40 + $vs : $tv_stok40 = $tv_stok40;
                                            $xx == 'fisik_diameter between 40 and 49' ? $tv_stok4049 = $tv_stok4049 + $vs : $tv_stok4049 = $tv_stok4049;
                                            $xx == 'fisik_diameter between 50 and 59' ? $tv_stok5059 = $tv_stok5059 + $vs : $tv_stok5059 = $tv_stok5059;
                                            $xx == 'fisik_diameter between 60 and 69' ? $tv_stok6069 = $tv_stok6069 + $vs : $tv_stok6069 = $tv_stok6069;
                                            $xx == 'fisik_diameter > 70' ? $tv_stok70 = $tv_stok70 + $vs : $tv_stok70 = $tv_stok70;

                                            $tb += $fs;
                                            $tv += $vs;

                                            echo "<td class='td-kecil text-right'>".$fs."</td>";
                                            echo "<td class='td-kecil text-right'>".$vs."</td>";
                                        }
                                        ?>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                    ?>
                                    <tr>
                                        <th colspan="2" class="text-right td-kecil">Jumlah</th>
                                        <th class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tb_stok40);?></th>
                                        <th class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tv_stok40,2);?></th>
                                        <th class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tb_stok4049);?></th>
                                        <th class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tv_stok4049,2);?></th>
                                        <th class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tb_stok5059);?></th>
                                        <th class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tv_stok5059,2);?></th>
                                        <th class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tb_stok6069);?></th>
                                        <th class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tv_stok6069,2);?></th>
                                        <th class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tb_stok70);?></th>
                                        <th class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tv_stok70,2);?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class=" td-kecil text-right">Total Pcs</th>
                                        <th colspan="10" class=" td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tb);?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="td-kecil text-right">Total Volume</th>
                                        <th colspan="10" class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($tv,2);?></th>
                                    </tr>
                                    <tr>
                                        <td colspan="12"></td>
                                    </tr>

                                    <?php // ==================================================================================================================== ?>
                                    <?php // SRIMULATSIH ?>
                                    <?php
                                    
                                    $sql_jenis_kayu = "select distinct(a.kayu_id), c.group_kayu, c.kayu_nama ".
                                                            "   from t_loglist_detail a ". 
                                                            "   join t_loglist b on b.loglist_id = a.loglist_id ".
                                                            "   join m_kayu c on c.kayu_id = a.kayu_id ".
                                                            "   join t_pengajuan_pembelianlog d on d.pengajuan_pembelianlog_id = b.pengajuan_pembelianlog_id ".
                                                            "   join t_spk_shipping e on e.spk_shipping_id = d.spk_shipping_id ".
                                                            "   where 1=1 and (e.status_jenis = 0 or e.status_jenis is null) ".
                                                            "   and d.status = 'APPROVED' ".
                                                            "   and e.status = 'APPROVED' ".
                                                            "   ";
                                    $query_jenis_kayu = Yii::$app->db->createCommand($sql_jenis_kayu)->queryAll();
                                    $i = 1;
                                    $stb_stok40 = 0;
                                    $stb_stok4049 = 0;
                                    $stb_stok5059 = 0;
                                    $stb_stok6069 = 0;
                                    $stb_stok70 = 0;
                                    $stv_stok40 = 0;
                                    $stv_stok4049 = 0;
                                    $stv_stok5059 = 0;
                                    $stv_stok6069 = 0;
                                    $stv_stok70 = 0;
                                    $aaa = 0;
                                    
                                    $stb = 0;
                                    $stv = 0;
                                    foreach ($query_jenis_kayu as $kolom) {
                                        ?>
                                        <style>
                                            .blue {
                                                color: #0000FF;
                                            }
                                        </style>
                                        <tr>
                                            <td class="td-kecil text-center" style="width: 50px; color: #00F;"><?php echo $i;?></td>
                                            <td class="td-kecil color: #00F;"><?php echo $kolom['kayu_nama'];?></td>
                                        <?php
                                        $yyy = array("volume_range < '40'","volume_range = '40-49'","volume_range = '50-59'","volume_range = '60-69'","volume_range = '70-up'");
                                        foreach ($yyy as $yy) {
                                            if ($yy == "volume_range < '40'") {
                                                $and_yy = "( volume_range in ('25-29','30-39') )";
                                            } else {
                                                $and_yy = $yy;
                                            }

                                            $sql_sb = "select count(nomor_batang) from t_loglist_detail a ". 
                                                            "   join t_loglist b on b.loglist_id = a.loglist_id ".
                                                            "   join m_kayu c on c.kayu_id = a.kayu_id ".
                                                            "   join t_pengajuan_pembelianlog d on d.pengajuan_pembelianlog_id = b.pengajuan_pembelianlog_id ".
                                                            "   join t_spk_shipping e on e.spk_shipping_id = d.spk_shipping_id ".
                                                            "   where 1=1 and a.kayu_id = ".$kolom['kayu_id']." ".
                                                            "   and ".$and_yy." ". 
                                                            "   and (e.status_jenis = 0 or e.status_jenis is null) ".
                                                            "   ";
                                            $sb = Yii::$app->db->createCommand($sql_sb)->queryScalar();
                                            $sb > 0 ? $sb = $sb : $sb = 0;
                                            
                                            $sql_sv = "select sum(volume_value) from t_loglist_detail a ". 
                                                            "   join t_loglist b on b.loglist_id = a.loglist_id ".
                                                            "   join m_kayu c on c.kayu_id = a.kayu_id ".
                                                            "   join t_pengajuan_pembelianlog d on d.pengajuan_pembelianlog_id = b.pengajuan_pembelianlog_id ".
                                                            "   join t_spk_shipping e on e.spk_shipping_id = d.spk_shipping_id ".
                                                            "   where 1=1 and a.kayu_id = ".$kolom['kayu_id']." ".
                                                            "   and ".$and_yy." ". 
                                                            "   and (e.status_jenis = 0 or e.status_jenis is null) ".
                                                            "   ";
                                            $sv = Yii::$app->db->createCommand($sql_sv)->queryScalar();
                                            $sv > 0 ? $sv = $sv : $sv = 0;

                                            $yy == "volume_range < 40" ? $stb_stok40 = $stb_stok40 + $sb : $stb_stok40 = $stb_stok40;
                                            $yy == "volume_range = '40-49'" ? $stb_stok4049 = $stb_stok4049 + $sb : $stb_stok4049 = $stb_stok4049;
                                            $yy == "volume_range = '50-59'" ? $stb_stok5059 = $stb_stok5059 + $sb : $stb_stok5059 = $stb_stok5059;
                                            $yy == "volume_range = '60-69'" ? $stb_stok6069 = $stb_stok6069 + $sb : $stb_stok6069 = $stb_stok6069;
                                            $yy == "volume_range = '70-up'" ? $stb_stok70 = $stb_stok70 + $sb : $stb_stok70 = $stb_stok70;

                                            $yy == "volume_range < 40" ? $stv_stok40 = $stv_stok40 + $sv : $stv_stok40 = $stv_stok40;
                                            $yy == "volume_range = '40-49'" ? $stv_stok4049 = $stv_stok4049 + $sv : $stv_stok4049 = $stv_stok4049;
                                            $yy == "volume_range = '50-59'" ? $stv_stok5059 = $stv_stok5059 + $sv : $stv_stok5059 = $stv_stok5059;
                                            $yy == "volume_range = '60-69'" ? $stv_stok6069 = $stv_stok6069 + $sv : $stv_stok6069 = $stv_stok6069;
                                            $yy == "volume_range = '70-up'" ? $stv_stok70 = $stv_stok70 + $sv : $stv_stok70 = $stv_stok70;
                                            
                                            $stb += $sb;
                                            $stv += $sv;
                                            
                                            echo "<td class='td-kecil text-right'>".$sb."</td>";
                                            echo "<td class='td-kecil text-right'>".$sv."</td>";
                                        }
                                        ?>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                    <tr>
                                        <th colspan="2" class="text-right td-kecil" style="color: #00f;">Jumlah</th>
                                        <th class="td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stb_stok40);?></th>
                                        <th class="td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stv_stok40,2);?></th>
                                        <th class="td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stb_stok4049);?></th>
                                        <th class="td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stv_stok4049,2);?></th>
                                        <th class="td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stb_stok5059);?></th>
                                        <th class="td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stv_stok5059,2);?></th>
                                        <th class="td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stb_stok6069);?></th>
                                        <th class="td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stv_stok6069,2);?></th>
                                        <th class="td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stb_stok70);?></th>
                                        <th class="td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stv_stok70,2);?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class=" td-kecil text-right" style="color: #00f;">Total Pcs</th>
                                        <th colspan="10" class=" td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stb);?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="td-kecil text-right" style="color: #00f;">Total Volume</th>
                                        <th colspan="10" class="td-kecil text-right" style="color: #00f;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($stv,2);?></th>
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <?php
                                    $gtb_40 = $tb_stok40 + $stb_stok40; $gtv_40 = $tv_stok40 + $stv_stok40;
                                    $gtb_4049 = $tb_stok4049 + $stb_stok4049; $gtv_4049 = $tv_stok4049 + $stv_stok4049;
                                    $gtb_5059 = $tb_stok5059 + $stb_stok5059; $gtv_5059 = $tv_stok5059 + $stv_stok5059;
                                    $gtb_6069 = $tb_stok6069 + $stb_stok6069; $gtv_6069 = $tv_stok6069 + $stv_stok6069;
                                    $gtb_70 = $tb_stok70 + $stb_stok70; $gtv_70 = $tv_stok70 + $stv_stok70;

                                    $gtb = $gtb_40 + $gtb_4049 + $gtb_5059 + $gtb_6069 + $gtb_70;
                                    $gtv = $gtv_40 + $gtv_4049 + $gtv_5059 + $gtv_6069 + $gtv_70;
                                    ?>
                                    <tr>
                                        <th colspan="12"></th>
                                    </tr>
                                    <tr>
                                        <th colspan='2' class='td-kecil text-right'><b>Total</b></th>
                                        <th class='td-kecil text-right'><?php echo $gtb_40;?></th>
                                        <th class='td-kecil text-right'><?php echo $gtv_40;?></th>
                                        <th class='td-kecil text-right'><?php echo $gtb_4049;?></th>
                                        <th class='td-kecil text-right'><?php echo $gtv_4049;?></th>
                                        <th class='td-kecil text-right'><?php echo $gtb_5059;?></th>
                                        <th class='td-kecil text-right'><?php echo $gtv_5059;?></th>
                                        <th class='td-kecil text-right'><?php echo $gtb_6069;?></th>
                                        <th class='td-kecil text-right'><?php echo $gtv_6069;?></th>
                                        <th class='td-kecil text-right'><?php echo $gtb_70;?></th>
                                        <th class='td-kecil text-right'><?php echo $gtv_70;?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class=" td-kecil text-right"><b>Total Pcs</b></th>
                                        <th colspan="10" class=" td-kecil text-right"><b><?php echo $gtb;?></b></th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="td-kecil text-right"><b>Total Volume</b></th>
                                        <th colspan="10" class="td-kecil text-right"><b><?php echo $gtv;?></b></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            } else if ($jenis_log == "LS" || $jenis_log == "LJ") {
            ?>
                <div class="row">
                    <div class="col-md-12" id="place-table-laporan" style="min-height: 100px; width: 100%;"></div>
                </div>
            <?php
            }
            ?>

            <div class="modal-footer">
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
getAvilable();
", yii\web\View::POS_READY); ?>
<style>
    .simulasi {
        background-color: #eee;
        color: #aaa;
    }
</style>
<script>
    function getAvilable(){
        $("#place-table-laporan").html("");
        $("#place-table-laporan").addClass("animation-loading");
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute([$infoStock]); ?>',
            type   : 'GET',
            data   : {},
            success: function (data){
                if(data.html){
                    $("#place-table-laporan").html(data.html);
                    $("#place-table-laporan").removeClass("animation-loading");
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
</script>