<?php

use app\models\MBrgLog;
use app\models\MKayu;

 if($tipe == "KOREKSI HARGA JUAL" || $tipe == "KOREKSI NOPOL MOBIL" || $tipe == "KOREKSI ALAMAT BONGKAR" || $tipe == "POTONGAN PIUTANG"){
    echo \yii\helpers\Html::activeHiddenInput($model, 'jenis_produk');
} ?>
<div class="row">
    <?php if($tipe == "KOREKSI HARGA JUAL"){ ?>
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12" style="padding-bottom: 5px;">
                <div class="table-scrollable">
                    <div class="mt-checkbox-list" style="padding: 0px;">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input name="TNotaPenjualan[cust_is_pkp]" value="0" type="hidden">
                            <input id="tnotapenjualan-cust_is_pkp" name="TNotaPenjualan[cust_is_pkp]" value="1" type="checkbox" onchange="subTotal();" disabled="disabled"> 
                            <span class="help-block"></span>
                            <div style="padding-top: 3px; margin-left: 10px;">PKP</div>
                        </label> 
                    </div>
                    <table class="table table-striped table-bordered table-advance table-hover" id="table-koreksi">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Produk</th>
                                <th>Pcs</th>
                                <th>M<sup>3</sup></th>
                                <th>Harga </th>
                                <th>Harga Koreksi</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php    
                            $modNotaDetails = \app\models\TNotaPenjualanDetail::find()->where( "nota_penjualan_id = ".$model->nota_penjualan_id )->all();
                            $total_harga = 0;
                            foreach($modNotaDetails as $i => $detail){
                                if($model->jenis_produk == "Log"){
                                    $subtotal = $detail->harga_jual * number_format($detail->kubikasi,2);
                                } else {
                                    if($detail->produk->produk_group == "Plywood" || $detail->produk->produk_group == "Lamineboard" || $detail->produk->produk_group == "Platform"){
                                        $subtotal = $detail->harga_jual * $detail->qty_kecil;
                                    }else{
                                        $subtotal = $detail->harga_jual * number_format($detail->kubikasi,4);
                                    }
                                }
                                if($model->cust_is_pkp==TRUE){
                                    $detail->ppn = \app\components\DeltaFormatter::formatNumberForUserFloat( $detail->ppn );
                                }else{
                                    $detail->ppn = 0;
                                }
                                $detail->harga_jual_lama = \app\components\DeltaFormatter::formatNumberForUserFloat( $detail->harga_jual );
                                $detail->subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat($subtotal);
                                
                                if(!empty($modAjuan->pengajuan_manipulasi_id)){
                                    $datadetail = yii\helpers\Json::decode($modAjuan->datadetail1);
                                    $hargabaru = 0;

                                    foreach($datadetail['old']['t_nota_penjualan_detail'] as $hh => $detail_old){
                                        if($detail_old['nota_penjualan_detail_id'] == $detail->nota_penjualan_detail_id){
                                            $detail->harga_jual_lama = $detail_old['harga_jual'];
                                        }
                                    }

                                    foreach($datadetail['new']['t_nota_penjualan_detail'] as $ii => $detttt){
                                        if($detttt['nota_penjualan_detail_id'] == $detail->nota_penjualan_detail_id){
                                            $detail->harga_jual_baru = $detttt['harga_jual'];
                                            
                                        }
                                    }
                                }
                                
                                echo "<tr>";
                                echo "  <td style='text-align:center;'>".
                                        ($i+1).
                                        \yii\helpers\Html::activeHiddenInput($detail, '[ii]nota_penjualan_detail_id').
                                        \yii\helpers\Html::activeHiddenInput($detail, '[ii]qty_kecil').
                                        \yii\helpers\Html::activeHiddenInput($detail, '[ii]kubikasi').
                                        "</td>";
                                if($detail->notaPenjualan->jenis_produk == "Limbah"){
                                    $produk = $detail->limbah->limbah_kode . ' - ' . '( '. $detail->limbah->limbah_produk_jenis . ' ) ' . $detail->limbah->limbah_nama;
                                } else if($detail->notaPenjualan->jenis_produk == "JasaKD" || $detail->notaPenjualan->jenis_produk == "JasaGesek" || $detail->notaPenjualan->jenis_produk == "JasaMoulding"){
                                    $produk = $detail->produkJasa->nama;
                                } else if($detail->notaPenjualan->jenis_produk == "Log"){
                                    $modKayu = MKayu::findOne($detail->log->kayu_id);
                                    $produk = $detail->log->log_kelompok . '-' . $modKayu->kayu_nama . ' (' . $detail->log->range_awal .' cm - ' . $detail->log->range_akhir .' cm)';
                                } else {
                                    $produk = $detail->produk->produk_nama;
                                }
                                echo "  <td style='text-align:left;'>". $produk ."</td>";
                                echo "  <td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil)."</td>";
                                echo "  <td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($detail->kubikasi)."</td>";
                                echo "  <td style='text-align:right;'>".\app\components\DeltaFormatter::formatnumberforUserFloat($detail->harga_jual_lama)."</td>";
                                echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($detail, '[ii]harga_jual_baru',['class'=>'form-control float','style'=>'font-size:1.2rem;','onblur'=>'subTotalNota()'])."</td>";
                                echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($detail, '[ii]subtotal',['class'=>'form-control float','style'=>'font-size:1.2rem;','disabled'=>true])."</td>";
                                echo "</tr>";
                                $total_harga += $subtotal;
                            }
                            $model->total_harga = number_format($total_harga);
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-align-right"><b>
                                    Total Harga &nbsp;
                                </b></td>
                                <td style="font-size: 1.2rem; line-height: 0.9; padding: 5px;">
                                    <?= yii\bootstrap\Html::activeTextInput($model, "total_harga",['class'=>'form-control float','disabled'=>'disabled','style'=>'font-weight:600; padding:2px;']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-align-right"><b>
                                    TOTAL &nbsp;
                                </b></td>
                                <td>
                                    <?= yii\bootstrap\Html::textInput('total_bayar',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <?php }else if($tipe == "KOREKSI NOPOL MOBIL"){ ?>
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12" style="padding-bottom: 5px;">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover" id="table-koreksi">
                        <thead>
                            <tr>
                                <?php
                                if(!empty($modAjuan->pengajuan_manipulasi_id)){
                                    if($modAjuan->tanggal >'2021-05-03'){ ?>
                                        <th>Nama Sopir Lama</th>
                                        <th>Nopol Lama</th>
                                        <th>Nama Sopir Baru</th>
                                        <th>Nopol Baru</th>
                                    <?php }else{ ?>
                                        <th>Nopol Lama</th>
                                        <th>Nopol Baru</th>
                                    <?php } 
                                
                                }else{
                                   ?>
                                       <th>Nama Sopir Lama</th>
                                        <th>Nopol Lama</th>
                                        <th>Nama Sopir Baru</th>
                                        <th>Nopol Baru</th>
                                <?php
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($modAjuan->pengajuan_manipulasi_id)){
                                $modelAjuan = \app\models\TPengajuanManipulasi::findOne($modAjuan->pengajuan_manipulasi_id);
                                $modDetailAjuan = \yii\helpers\Json::decode($modelAjuan->datadetail1);
                                if($modAjuan->tanggal >'2021-05-03'){
                                    echo "<tr>";
                                    echo "  <td style='text-align:center;font-size:1.2rem;'>".$modDetailAjuan['supir_old']."</td>";
                                    echo "  <td style='text-align:center;font-size:1.2rem;'>".$modDetailAjuan['old']."</td>";
                                    echo "  <td style='text-align:center;font-size:1.2rem;'>".$modDetailAjuan['supir_new']."</td>";
                                    echo "  <td style='text-align:center;font-size:1.2rem;'>".$modDetailAjuan['new']."</td>";
                                    echo "</tr>";
                                }else{
                                    echo "<tr>";
                                    echo "  <td style='text-align:center;font-size:1.2rem;'>".$modDetailAjuan['old']."</td>";
                                    echo "  <td style='text-align:center;font-size:1.2rem;'>".$modDetailAjuan['new']."</td>";
                                    echo "</tr>";
                                }
                            }else{
                                echo "<tr>";
                                echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modAjuan, '[datadetail1][supir_old]kendaraan_supir_lama',['class'=>'form-control','style'=>'font-size:1.2rem;','readonly'=>"readonly",'value'=>$model->kendaraan_supir])."</td>";
                                echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modAjuan, '[datadetail1][old]nopol_lama',['class'=>'form-control','style'=>'font-size:1.2rem;','readonly'=>"readonly"])."</td>";
                                echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modAjuan, '[datadetail1][supir_new]kendaraan_supir_baru',['class'=>'form-control','style'=>'font-size:1.2rem;','value'=>$model->kendaraan_supir])."</td>";                            
                                echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modAjuan, '[datadetail1][new]nopol_baru',['class'=>'form-control','style'=>'font-size:1.2rem;'])."</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <?php }else if($tipe == "KOREKSI ALAMAT BONGKAR"){ ?>
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12" style="padding-bottom: 5px;">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover" id="table-koreksi">
                        <thead>
                            <tr>
                                <th>Alamat Bongkar Lama</th>
                                <th>Alamat Bongkar Baru</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php    
                            if(!empty($modAjuan->pengajuan_manipulasi_id)){
                                $modelAjuan = \app\models\TPengajuanManipulasi::findOne($modAjuan->pengajuan_manipulasi_id);
                                $modDetailAjuan = \yii\helpers\Json::decode($modelAjuan->datadetail1);
                                echo "<tr>";
                                echo "  <td style='text-align:center;font-size:1.2rem;'>".$modDetailAjuan['old']."</td>";
                                echo "  <td style='text-align:center;font-size:1.2rem;'>".$modDetailAjuan['new']."</td>";
                                echo "</tr>";
                            }else{
                                echo "<tr>";
                                echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextarea($modAjuan, '[datadetail1][old]alamat_bongkar_lama',['class'=>'form-control','style'=>'font-size:1.2rem;','disabled'=>true])."</td>";
                                echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextarea($modAjuan, '[datadetail1][new]alamat_bongkar_baru',['class'=>'form-control','style'=>'font-size:1.2rem;'])."</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <?php }else if($tipe == "POTONGAN PIUTANG"){ ?>
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12" style="padding-bottom: 5px;">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover" id="table-koreksi">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Data Piutang Customer : <u id=""><?= $model->cust->cust_an_nama ?></u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            echo \yii\helpers\Html::activeHiddenInput($modReff, 'cust_id');
                            echo \yii\helpers\Html::activeHiddenInput($modReff, 'tanggal_bill');
                            
                            echo "<tr>";
                            echo "  <td style='text-align:right;'>Bill Reff</td>";
                            echo "  <td>".yii\bootstrap\Html::activeTextInput($modReff, 'bill_reff',['class'=>'form-control','style'=>'font-size:1.2rem;text-align:right;','disabled'=>true])."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "  <td style='text-align:right;'>Nominal Bill</td>";
                            echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modReff, 'nominal_bill',['class'=>'form-control float','style'=>'font-size:1.2rem;','disabled'=>true])."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "  <td style='text-align:right;'>Pernah Terbayar</td>";
                            echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modReff, 'nominal_terbayar',['class'=>'form-control float','style'=>'font-size:1.2rem;','disabled'=>true])."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "  <td style='text-align:right;'>Sisa Tagihan</td>";
                            echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modReff, 'tagihan',['class'=>'form-control float','style'=>'font-size:1.2rem;','disabled'=>true])."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "  <td style='text-align:right;'>Potongan</td>";
                            echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modReff, 'bayar',['class'=>'form-control float','style'=>'font-size:1.2rem;','onblur'=>'totalPotongan()'])."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "  <td style='text-align:right;'>Sisa Piutang</td>";
                            echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modReff, 'sisa',['class'=>'form-control float','style'=>'font-size:1.2rem;','disabled'=>true])."</td>";
                            echo "</tr>";
                            ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <?php }else if($tipe == "KOREKSI PIUTANG LOG & JASA"){ ?>
    <?php
    $modCustomer = app\models\MCustomer::findOne($modReff->customer_id);
    $sql = "SELECT *, (termin_tagihan - termin_terbayar) AS sisa_bayar 
            FROM t_piutang_alert_detail 
            WHERE piutang_alert_id = {$modReff->piutang_alert_id} ORDER BY termin ASC";
    $modDetail = Yii::$app->db->createCommand($sql)->queryAll();
    ?>
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-6" style="padding-bottom: 5px;">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 100px; vertical-align: top;"><b>Customer</b></td>
                        <td style="width: 30px; vertical-align: top;"><b>:</b></td>
                        <td><?= $modCustomer->cust_an_nama ?></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"><b>Alamat</b></td>
                        <td style="vertical-align: top;"><b>:</b></td>
                        <td><?= $modCustomer->cust_an_alamat ?></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"><b>No. NPWP</b></td>
                        <td style="vertical-align: top;"><b>:</b></td>
                        <td><?= !empty($modCustomer->cust_no_npwp)? 
                                substr($modCustomer->cust_no_npwp,0,2).".".
                                substr($modCustomer->cust_no_npwp,3,3).".".
                                substr($modCustomer->cust_no_npwp,6,3).".".
                                substr($modCustomer->cust_no_npwp,9,1)."-". 
                                substr($modCustomer->cust_no_npwp,10,3).".". 
                                substr($modCustomer->cust_no_npwp,13,3)
                                :"-"
                            ?></td>
                        <!--99.999.999.9-999.999-->
                    </tr>
                </table>
            </div>
            <div class="col-md-6" style="padding-bottom: 5px;">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 100px; vertical-align: top;"><b>Tanggal Nota</b></td>
                        <td style="width: 30px; vertical-align: top;"><b>:</b></td>
                        <td><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tgl_nota) ?></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"><b>Tempo Bayar</b></td>
                        <td style="vertical-align: top;"><b>:</b></td>
                        <td><?= $modReff->tempo_bayar." Hari" ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12" style="padding-bottom: 5px;">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover" id="table-koreksi">
                        <thead>
                            <tr>
                                <th>Termin</th>
                                <th>Tagihan</th>
                                <th>Terbayar</th>
                                <th>Sisa Piutang</th>
                                <th>Potongan</th>
                                <th>Koreksi Sisa Piutang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(count($modDetail)>0){
                                $termin_terbayar = 0; $sisa_bayar=0;
                                foreach($modDetail as $i => $detail){
                                    $modPiutangDetail = new app\models\TPiutangAlertDetail();
                                    $modPiutangDetail->piutang_alert_detail_id = $detail['piutang_alert_detail_id'];
                                    $modPiutangDetail->sisa_bayar = $detail['sisa_bayar'];
                                    $modPiutangDetail->potongan = 0;
                                    $modPiutangDetail->sisa_bayar_baru = \app\components\DeltaFormatter::formatnumberforUser( $modPiutangDetail->sisa_bayar - $modPiutangDetail->potongan );
                                    $termin_terbayar = $detail['termin_terbayar'];
                                    $sisa_bayar = $detail['sisa_bayar'];
                                    
                                    
                                    if(!empty($modAjuan->pengajuan_manipulasi_id)){
                                        $datadetail = yii\helpers\Json::decode($modAjuan->datadetail1); $hargabaru = 0;
                                        foreach($datadetail['new']['t_piutang_alert_detail'] as $ii => $detttt){
                                            if($detttt['piutang_alert_detail_id'] == $modPiutangDetail->piutang_alert_detail_id){
                                                $modPiutangDetail->potongan = $detttt['potongan'];
                                                $modPiutangDetail->sisa_bayar_baru = $detttt['sisa_bayar_baru'];
                                            }
                                        }
                                    }
                                    
                                    echo "<tr class='tr-isi'>";
                                    echo yii\helpers\Html::activeHiddenInput($modPiutangDetail, "[ii]piutang_alert_detail_id");
                                    echo yii\helpers\Html::activeHiddenInput($modPiutangDetail, "[ii]sisa_bayar");
                                    echo "  <td style='text-align:center; vertical-align: middle;'>".$detail['termin']."</td>";
                                    echo "  <td style='text-align:right; vertical-align: middle;'>". \app\components\DeltaFormatter::formatnumberforUser($detail['termin_tagihan'])."</td>";
                                    echo "  <td style='text-align:right; vertical-align: middle;'>".\app\components\DeltaFormatter::formatnumberforUser($detail['termin_terbayar'])."</td>";
                                    echo "  <td style='text-align:right; vertical-align: middle;'>".\app\components\DeltaFormatter::formatnumberforUser($detail['sisa_bayar'])."</td>";
                                    echo "  <td style='text-align:right; vertical-align: middle;'>".yii\bootstrap\Html::activeTextInput($modPiutangDetail, '[ii]potongan',['class'=>'form-control float','onblur'=>'subtotalLogjasa(this)','style'=>'font-size:1.2rem; height:30px;'])."</td>";
                                    echo "  <td style='text-align:right; vertical-align: middle;'>".yii\bootstrap\Html::activeTextInput($modPiutangDetail, '[ii]sisa_bayar_baru',['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; height:30px;'])."</td>";
                                    echo "</tr>";
                                }
                                echo "<tr style='background-color:#f1f4f7'>";
                                echo "  <td style='text-align:right; vertical-align: middle;'><b>TOTAL</b></td>";
                                echo "  <td style='text-align:right; vertical-align: middle;'><b>". \app\components\DeltaFormatter::formatnumberforUser($modReff->tagihan_jml)."</b></td>";
                                echo "  <td style='text-align:right; vertical-align: middle;'><b>".\app\components\DeltaFormatter::formatnumberforUser($termin_terbayar)."</b></td>";
                                echo "  <td style='text-align:right; vertical-align: middle;'><b>".\app\components\DeltaFormatter::formatnumberforUser($sisa_bayar)."</b></td>";
                                echo "  <td style='text-align:right; vertical-align: middle;'><b>".yii\bootstrap\Html::activeTextInput($modReff, 'potongan',['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; height:30px;'])."</b></td>";
                                echo "  <td style='text-align:right; vertical-align: middle;'><b>".yii\bootstrap\Html::activeTextInput($modReff, 'sisa_bayar_baru',['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; height:30px;'])."</b></td>";
                                echo "</tr>";
                            }
                            
                            ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<script>
</script>