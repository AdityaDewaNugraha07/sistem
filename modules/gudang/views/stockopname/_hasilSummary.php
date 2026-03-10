<div class="col-md-6">
    <div class="row">
        Peserta Verifikasi Data :<br>
        <?php
        $opanamePeserta = app\models\TStockopnamePeserta::find()->where("stockopname_agenda_id = ".$model['stockopname_agenda_id'])->all();
        if(count($opanamePeserta)>0){
            foreach($opanamePeserta as $i => $peserta){
                $modPegawai = app\models\MPegawai::findOne($peserta->pegawai_id);
                $modDepartement = app\models\MDepartement::find()->where("departement_id = ".$modPegawai['departement_id'])->one();
                if($modPegawai){
                        echo "<b style='font-size:1.1rem'>".($i+1).". </b><span class='font-red-flamingo' style='font-size:1.1rem'>".$modPegawai->pegawai_nama." </span><h style='font-size:1.1rem'>(".$modDepartement->departement_nama.")</h><br>";
                }
            }
        }
        ?>        
    </div>    
</div>
<div class="col-md-6 visible-lg visible-md">   
    <?php if(empty($summaryonly)){ ?>
    <a class="pull-right btn btn-icon-only btn-default tooltips" onclick="printout('EXCEL')" data-original-title="Export to Excel"><i class="fa fa-table"></i></a>
    <a class="pull-right btn btn-icon-only btn-default tooltips" onclick="printout('PDF')" data-original-title="Export to PDF"><i class="fa fa-files-o"></i></a>
    <a class="pull-right btn btn-icon-only btn-default tooltips" onclick="printout('PRINT')" data-original-title="Print Out"><i class="fa fa-print"></i></a>
    <?php } ?>
</div>
<div class="row">
    <div class="col-md-12 text-align-center" >
        <h4><?= "Hasil Verifikasi Data Agenda <b>{$modAgenda->kode}</b> pada tanggal ".\app\components\DeltaFormatter::formatDateTimeForUser2($modAgenda->tanggal) ?></h4>
        <h5 style="margin-top: -10px;">Jenis Produk : <u><?= (count($jenis_produk)>6)?"All": implode(", ", $jenis_produk) ?></u></h5>
    </div>
</div>
<div class="row">
    <div class="col-md-6" style="margin-bottom: -12px;">
        <h5><?= "Summary Hasil" ?></h5>
    </div>
    <div class="col-md-6" style="margin-bottom: -12px;">
        <i><h5 class="pull-right font-red-flamingo">TRIAL VERSION</h5></i>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-scrollable">
            <table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-detail">
                <tbody>
                    <tr>
                        <td rowspan="2" class="" style="border-right: 1px solid #595959; border-bottom: 1px solid #595959;"></td>
                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #f1f4e6;">Fisik-<b>Yes</b> System-<b>Yes</b></td>
                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #ffdbe3;">Fisik-<b>Yes</b> System-<b>No</b></td>
                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #f9f1d4;">Fisik-<b>No</b> System-<b>Yes</b></td>
                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #d6d6d6;"><b>Total Fisik</b></td>
                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #d6d6d6;"><b>Total System</b></td>
                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #d6d6d6;"><b>Selisih</b></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #000;">
                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #f1f4e6; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #f1f4e6; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #ffdbe3; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #ffdbe3; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #f9f1d4; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #f9f1d4; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                    </tr>
                    <?php
                    $rows= (!empty($model->stockopname_hasil_id))?yii\helpers\Json::decode($model->jenis_produk):$jenis_produk;
                    array_push($rows, "total");
                    foreach($rows as $i => $jnsprod){
                        if(!empty($model->stockopname_hasil_id)){
                            if($jnsprod!="total"){
                                $model->attributes = Yii::$app->db->createCommand("SELECT * FROM t_stockopname_hasil_detail WHERE stockopname_hasil_id = {$model->stockopname_hasil_id} AND jenis_produk = '{$jnsprod}'")->queryOne();
                            }else{
                                $model->attributes = Yii::$app->db->createCommand("SELECT * FROM t_stockopname_hasil WHERE stockopname_hasil_id = {$model->stockopname_hasil_id}")->queryOne();
                            }
                        }else{
                            if($jnsprod!="total"){
                                $que = Yii::$app->runAction("/gudang/stockopname/getParamJenisProduk",['jenis_produk'=>$jnsprod])['query'];
                                $model->attributes = Yii::$app->runAction("/gudang/stockopname/getDataSummary",['stockopname_agenda_id'=>$modAgenda->stockopname_agenda_id,'queryJenisProduk'=>$que,'stockopname_hasil_id'=>$model->stockopname_hasil_id]);
                            }else{
                                $que = Yii::$app->runAction("/gudang/stockopname/getParamJenisProduk",['jenis_produk'=>(implode(",", $jenis_produk))])['query'];
                                $model->attributes = Yii::$app->runAction("/gudang/stockopname/getDataSummary",['stockopname_agenda_id'=>$modAgenda->stockopname_agenda_id,'queryJenisProduk'=>$que,'stockopname_hasil_id'=>$model->stockopname_hasil_id]);
                            }
                        }                        
                    ?>
                    <tr class="text-align-right">
                        <td class="text-align-right" style="border-right: 1px solid #595959;"><?= strtoupper($jnsprod) ?></td>
                        <td style="background-color: #f1f4e6; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "fisik_yes_system_yes_palet") ?>
                            <span id="label-fisik_yes_system_yes_palet"><?= $model->fisik_yes_system_yes_palet ?></span>
                        </td>
                        <td style="background-color: #f1f4e6; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "fisik_yes_system_yes_m3") ?>
                            <span id="label-fisik_yes_system_yes_m3">
                                <?php
                                $sqlX = "select round(sum(t_terima_ko.qty_m3)::numeric,4) as kubikasi ".
                                            " from t_stockopname ".
                                            " left join m_brg_produk ON m_brg_produk.produk_id = t_stockopname.produk_id  ".
                                            " left join t_terima_ko on t_terima_ko.nomor_produksi = t_stockopname.nomor_produksi ".
                                            " where status = 'FYSY'  ".
                                            " and stockopname_agenda_id = ".$modAgenda->stockopname_agenda_id."  ".
                                            " and m_brg_produk.produk_group = '".$jnsprod."'  ".
                                            "   ";
                                $modXFysy = Yii::$app->db->createCommand($sqlX)->queryScalar();
                                $modXFysy < 0 ? $modXFysy = 0 : $modXFysy = $modXFysy;
                                if ($modXFysy > 0) {
                                    echo $modXFysy;
                                } else {
                                    echo 0;
                                }
                                ?>
                            </span>
                        </td>
                        <td style="background-color: #ffdbe3; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "fisik_yes_system_no_palet") ?>
                            <span id="label-fisik_yes_system_no_palet"><?= $model->fisik_yes_system_no_palet ?></span>
                        </td>
                        <td style="background-color: #ffdbe3; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "fisik_yes_system_no_m3") ?>
                            <span id="label-fisik_yes_system_no_m3">
                                <?php
                                $sqlY = "select round(sum(t_terima_ko.qty_m3)::numeric,4) as kubikasi ".
                                            " from t_stockopname ".
                                            " left join m_brg_produk ON m_brg_produk.produk_id = t_stockopname.produk_id  ".
                                            " left join t_terima_ko on t_terima_ko.nomor_produksi = t_stockopname.nomor_produksi ".
                                            " where status = 'FYSN'  ".
                                            " and stockopname_agenda_id = ".$modAgenda->stockopname_agenda_id."  ".
                                            " and m_brg_produk.produk_group = '".$jnsprod."'  ".
                                            "   ";
                                $modYFysn = Yii::$app->db->createCommand($sqlY)->queryScalar();
                                if ($modYFysn > 0) {
                                    echo $modYFysn;
                                } else {
                                    echo "0";
                                }
                                ?>
                            </span>
                        </td>
                        <td style="background-color: #f9f1d4; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "fisik_no_system_yes_palet") ?>
                            <span id="label-fisik_no_system_yes_palet"><?= $model->fisik_no_system_yes_palet ?></span>
                        </td>
                        <td style="background-color: #f9f1d4; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "fisik_no_system_yes_m3") ?>
                            <span id="label-fisik_no_system_yes_m3">
                                <?php
                                $today = date('Y-m-d');
                                $sqlZ = "select round(sum(in_qty_m3-out_qty_m3)::numeric,4) AS kubikasi ".
                                            " from h_persediaan_produk ".
                                            " join m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id ".
                                            " where tgl_transaksi <= '".$today."' AND ( m_brg_produk.produk_group = '".$jnsprod."' ) ".
                                            " and nomor_produksi not in ( ".
                                            "       select t_stockopname.nomor_produksi ".
                                            "       from t_stockopname  ".
                                            "       left JOIN m_brg_produk ON m_brg_produk.produk_id = t_stockopname.produk_id ".
                                            "       where status = 'FYSY'  ".
                                            "       and stockopname_agenda_id = '".$modAgenda->stockopname_agenda_id."'  ".
                                            "       and m_brg_produk.produk_group = '".$jnsprod."' ".
                                            "       group BY 1 ".
                                            "     ) ".
                                            " having sum(in_qty_palet-out_qty_palet) > 0 ".
                                            "   ";
                                $modZFnsy = Yii::$app->db->createCommand($sqlZ)->queryScalar();
                                echo $modZFnsy;
                                ?>
                            </span>
                        </td>
                        <?php
                        $fisik_saja = $modXFysy + $modYFysn;
                        $system_saja = $modXFysy + $modZFnsy;
                        $selisih = $fisik_saja - $system_saja;
                        ?>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "total_fisik_palet") ?>
                            <span id="label-total_fisik_palet"><?= $model->total_fisik_palet ?></span>
                        </td>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "total_fisik_m3") ?>
                            <span id="label-total_fisik_m3"><?php echo $fisik_saja;?></span>
                        </td>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "total_system_palet") ?>
                            <span id="label-total_system_palet"><?= $model->total_system_palet ?></span>
                        </td>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "total_system_m3") ?>
                            <span id="label-total_system_m3"><?php echo $system_saja; ?></span>
                        </td>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;" class="font-red-flamingo"><?php echo abs($model->total_fisik_palet-$model->total_system_palet) ?></td>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;" class="font-red-flamingo"><?php echo abs(round($selisih,4)) ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <i style="font-size: 0.9rem;">Data Fisik yang dimaksud adalah palet yang memiliki label QRCode dan sudah di scan.</i>
        </div>
    </div>
</div>
<br>
<div style="display: <?= (!empty($summaryonly)?"none;":"") ?>">
<div class="row">
    <div class="col-md-10" style="">
        <h5 id=""><?= Yii::t('app', 'Detail Hasil Verifikasi Data'); ?></h5>
    </div>
    <div class="col-md-2 pull-right" style="margin-top:10px;">
        Filter By : <?= yii\helpers\Html::dropDownList("filter_status","FYSY",["FYSY"=>"FYSY","FYSN"=>"FYSN","FNSY"=>"FNSY"],['id'=>'filter_status','class'=>'form-control','onchange'=>'setFilterStatus()']) ?>
    </div>
</div>
<div class="row" style="margin-left: -30px; margin-right: -30px;">
    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
        <!--<div class="table-scrollable">-->
            <table class="table table-striped table-bordered table-advance table-hover table-laporan" style="width: 100%; border: 1px solid #A0A5A9;" id="table-master">
                <thead>
                    <tr>
                        <th style="width: 25px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
                        <th style="width: 100px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
                        <th style="font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Produk'); ?></th>
                        <th style="width: 40px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Lokasi<br>Gudang'); ?></th>
                        <th style="width: 80px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Agenda'); ?></th>
                        <th style="width: 35px; line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty'); ?></th>
                        <th style="width: 45px; font-size: 1.2rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
                        <th style="width: 90px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Scaned'); ?></th>
                        <th style="width: 35px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Stat'); ?></th>
                        <th style="width: 35px;"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        <!--</div>-->
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="text-align: center;">
        <?php if(!empty($model->stockopname_hasil_id)){ ?>
        <h4>Di Close pada tanggal <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?> Oleh <?= app\models\MUser::findOne($model->created_by)->pegawai->pegawai_nama ?></h4>
            Status : <b><?= $model->status ?></b><br>
            <?php if(!empty($model->by_gmopr)){ ?>
                <div class="col-md-6">
                    <?php
                    $modApproval = app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->by_gmopr]);
                    echo "<span><b><u>".$modApproval->assignedTo->pegawai_nama."</u></b></span><br>";
                    echo $modApproval->status;
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    $modApproval = app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->by_dirut]);
                    echo "<span><b><u>".$modApproval->assignedTo->pegawai_nama."</u></b></span><br>";
                    echo $modApproval->status;
                    ?>
                </div>

            <?php } ?>
        <?php }else{
            echo ((count($jenis_produk)>6)?
                    \yii\helpers\Html::button( Yii::t('app', 'Selesai SO!'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'confirmHasil();']) :
                    \yii\helpers\Html::button( Yii::t('app', 'Selesai SO!'),['id'=>'btn-save-disabled','class'=>'btn grey btn-outline']) );
        }
        ?>

    </div>
</div>
</div>