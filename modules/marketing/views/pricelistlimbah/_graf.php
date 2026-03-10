<div class="modal fade" id="modal-madul" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Grafik Penetapan Harga'.' <b>'.$MBrgLimbah->limbah_id.' - '.$MBrgLimbah->limbah_kode.'</b>'); ?></h4>
            </div>

            <div class="modal-body">
                <div id="sparkline_bar"></div>
            </div>
            
            <div class="modal-footer">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet box blue-hoki bordered">
                                <div class="portlet-title">
                                    <div class="tools" style="float: left;">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a> &nbsp; 
                                    </div>
                                    <div class="caption">Daftar Harga Limbah</div>
                                </div>                                
                                <div class="portlet-body" style="background-color: #d9e2f0" >
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-scrollable">
                                                <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                    <tr>
                                                        <th class='col-md-3 text-center'>Tanggal Penetapan</th>
                                                        <th class='col-md-3 text-center'>Kode</th>
                                                        <th class='col-md-3 text-center'>Harga</th>
                                                        <th class='col-md-3 text-center'>Status Approval</th>
                                                    </tr>
                                                <?php
                                                $x = 1;
                                                $i = 1;
                                                $grafContentX = " ";
                                                $grafContentY = "0, ";
                                                foreach ($MHargaLimbah as $kolom) {
                                                    //0: 'Jul',1: 'Aug',2: 'Sep',3: 'Oct',4: 'Nov',5: 'Dev',
                                                    $harga_tanggal_penetapan = \app\components\DeltaFormatter::formatDateTimeForUser2($kolom['harga_tanggal_penetapan']);
                                                    $grafContentX .= $x.": '".$harga_tanggal_penetapan." - ', ";

                                                    $harga_enduser = $kolom['harga_enduser'];
                                                    $grafContentY .= $harga_enduser.", ";
                                                    $x++;
                                                    $i++;
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $harga_tanggal_penetapan;?></td>
                                                    <td class="text-center"><?php echo $kolom['kode'];?></td>
                                                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($harga_enduser);?></td>
                                                    <td class="text-center"><?php echo $kolom['status_approval'];?></td>
                                                </tr>
                                                <?php
                                                }
                                                $grafContentX = substr(trim($grafContentX), 0, -1);
                                                $grafContentY = substr(trim($grafContentY), 0, -1);
                                                ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>            
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $this->registerJs(" 
getSpark();
formconfig(); 
", yii\web\View::POS_READY); ?>

<style type="text/css">
    .jqstooltip {
        border-radius: 5px;
        background-color: #000;
        color: #fff;
        z-index: 10052;
    }
</style>

<?php $this->registerCssFile($this->theme->baseUrl."/pages/css/profile.min.css"); ?>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery.sparkline.min.js") ?>

    <script type="text/javascript">
    function getSpark() {
        $("#sparkline_bar").sparkline([<?php echo $grafContentY;?>], {
            type: "bar",
            width: "100",
            barWidth: 7,
            height: "70",
            barColor: "#86a426",
            negBarColor: "#e02222",
            tooltipFormat: '{{offset:offset}} {{value}}',
            tooltipValueLookups: {
                'offset': {<?php echo $grafContentX;?>}
            },
        });
    }
</script>

