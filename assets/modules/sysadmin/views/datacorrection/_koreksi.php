<?php if($tipe == "KOREKSI HARGA JUAL" || $tipe == "KOREKSI NOPOL MOBIL" || $tipe == "POTONGAN PIUTANG"){
    echo \yii\helpers\Html::activeHiddenInput($model, 'jenis_produk');
} ?>
<div class="row">
    <?php if($tipe == "KOREKSI HARGA JUAL"){ ?>
    <div class="col-md-2">
        <div class="form-group">
            <label class="col-md-12 control-label"><?= Yii::t('app', 'Koreksi Data'); ?></label>
        </div>
    </div>
    <div class="col-md-8">
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
                                <th>Harga Lama</th>
                                <th>Harga Baru</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php    
                            $modNotaDetails = \app\models\TNotaPenjualanDetail::find()->where( "nota_penjualan_id = ".$model->nota_penjualan_id )->all();
                            $total_harga = 0;
                            foreach($modNotaDetails as $i => $detail){
                                if($detail->produk->produk_group == "Plywood" || $detail->produk->produk_group == "Lamineboard" || $detail->produk->produk_group == "Platform"){
                                    $subtotal = $detail->harga_jual * $detail->qty_kecil;
                                }else{
                                    $subtotal = $detail->harga_jual * number_format($detail->kubikasi,4);
                                }
                                if($model->cust_is_pkp==TRUE){
                                    $detail->ppn = \app\components\DeltaFormatter::formatNumberForUserFloat( $detail->ppn );
                                }else{
                                    $detail->ppn = 0;
                                }
                                $detail->harga_jual_baru = \app\components\DeltaFormatter::formatNumberForUserFloat( $detail->harga_jual );
                                $detail->subtotal = \app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal );
                                
                                if(!empty($modAjuan->pengajuan_manipulasi_id)){
                                    $datadetail = yii\helpers\Json::decode($modAjuan->datadetail1); $hargabaru = 0;
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
                                echo "  <td style='text-align:left;'>".$detail->produk->produk_nama."</td>";
                                echo "  <td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil)."</td>";
                                echo "  <td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($detail->kubikasi)."</td>";
                                echo "  <td style='text-align:right;'>".number_format($detail->harga_jual)."</td>";
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
                                <td colspan="5" class="text-align-right">
                                    <?php echo \yii\bootstrap\Html::activeTextInput($model, 'keterangan_potongan',['style'=>'font-weight:400; display:'.(!empty($model->keterangan_potongan)?'':'none').';','class'=>'form-control','placeholder'=>'Isikan Keterangan Potongan Harga']); ?>
                                </td>
                                <td class="text-align-right"><b>
                                    Potongan &nbsp;
                                </b></td>
                                <td style="font-size: 1.2rem; line-height: 0.9; padding: 5px;">
                                    <?= yii\bootstrap\Html::activeTextInput($model, "total_potongan",['class'=>'form-control float','style'=>'font-weight:600; padding:2px;','onblur'=>'total();','disabled'=>true]); ?>
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
    <div class="col-md-2">
        <div class="form-group">
            <label class="col-md-12 control-label"><?= Yii::t('app', 'Koreksi Data'); ?></label>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <div class="col-md-12" style="padding-bottom: 5px;">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover" id="table-koreksi">
                        <thead>
                            <tr>
                                <th>Nopol Lama</th>
                                <th>Nopol Baru</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php    
                            echo "<tr>";
                            echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modAjuan, '[datadetail1][old]nopol_lama',['class'=>'form-control','style'=>'font-size:1.2rem;','disabled'=>true])."</td>";
                            echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modAjuan, '[datadetail1][new]nopol_baru',['class'=>'form-control','style'=>'font-size:1.2rem;'])."</td>";
                            echo "</tr>";
                            ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <?php }else if($tipe == "POTONGAN PIUTANG"){ ?>
    <div class="col-md-2">
        <div class="form-group">
            <label class="col-md-12 control-label"><?= Yii::t('app', 'Potongan Piutang'); ?></label>
        </div>
    </div>
    <div class="col-md-8">
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
                            echo "  <td style='text-align:right;'>".yii\bootstrap\Html::activeTextInput($modReff, 'bill_reff',['class'=>'form-control','style'=>'font-size:1.2rem;','disabled'=>true])."</td>";
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
            WHERE piutang_alert_id = {$modReff->piutang_alert_id} ";
    $modDetail = Yii::$app->db->createCommand($sql)->queryAll();
    ?>
    <div class="col-md-2">
        <div class="form-group">
            <label class="col-md-12 control-label"><?= Yii::t('app', 'Koreksi Data'); ?></label>
        </div>
    </div>
    
    <div class="col-md-10">
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
                                <th>Sisa Piutang Lama</th>
                                <th>Potongan</th>
                                <th>Sisa Piutang Baru</th>
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
                                    $modPiutangDetail->sisa_bayar_baru = number_format( $modPiutangDetail->sisa_bayar - $modPiutangDetail->potongan );
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
                                    echo "  <td style='text-align:right; vertical-align: middle;'>". number_format($detail['termin_tagihan'])."</td>";
                                    echo "  <td style='text-align:right; vertical-align: middle;'>".number_format($detail['termin_terbayar'])."</td>";
                                    echo "  <td style='text-align:right; vertical-align: middle;'>".number_format($detail['sisa_bayar'])."</td>";
                                    echo "  <td style='text-align:right; vertical-align: middle;'>".yii\bootstrap\Html::activeTextInput($modPiutangDetail, '[ii]potongan',['class'=>'form-control float','onblur'=>'subtotalLogjasa(this)','style'=>'font-size:1.2rem; height:30px;'])."</td>";
                                    echo "  <td style='text-align:right; vertical-align: middle;'>".yii\bootstrap\Html::activeTextInput($modPiutangDetail, '[ii]sisa_bayar_baru',['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; height:30px;'])."</td>";
                                    echo "</tr>";
                                }
                                echo "<tr style='background-color:#f1f4f7'>";
                                echo "  <td style='text-align:right; vertical-align: middle;'><b>TOTAL</b></td>";
                                echo "  <td style='text-align:right; vertical-align: middle;'><b>". number_format($modReff->tagihan_jml)."</b></td>";
                                echo "  <td style='text-align:right; vertical-align: middle;'><b>".number_format($termin_terbayar)."</b></td>";
                                echo "  <td style='text-align:right; vertical-align: middle;'><b>".number_format($sisa_bayar)."</b></td>";
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