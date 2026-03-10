<table class="table table-striped table-bordered table-hover" id="table-laporan" style="width:100%">
    <thead>
        <tr>
            <th colspan="15" style="line-height: 1; padding: 10px; background-color: #ddf5e4" class="font-green-seagreen"><i><b>STOCK LOG SENGON KONDISI BAGUS - (GUDANG LOG UTUH)</b></i></th>
        </tr>
        <tr>
            <th rowspan="2" style="line-height: 1; width: 30px;">No.</th>
            <th rowspan="2" style="line-height: 1;">Panjang Log</th>
            <th colspan="2" style="line-height: 1;">< 19</th>
            <th colspan="2" style="line-height: 1;">19-24</th>
            <th colspan="2" style="line-height: 1;">25-29</th>
            <th colspan="2" style="line-height: 1;">30-40</th>
            <th colspan="2" style="line-height: 1;">> 40</th>
            <th colspan="2" style="line-height: 1;">Total</th>
        </tr>
        <tr>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $reff_no = explode("-", $model->reff_no);
        $sql = "SELECT 
                    fisik_panjang, 
                    ( (SELECT COALESCE(SUM(fisik_pcs),0) FROM h_persediaan_log AS a 
                            WHERE lokasi IN ('GUDANG LOG SENGON') AND status = 'IN' 
                            AND a.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND a.tgl_transaksi <= '{$model->tgl_transaksi}' 
                       ) - 
                      (SELECT COALESCE(SUM(fisik_pcs),0) FROM h_persediaan_log AS b 
                            WHERE lokasi IN ('GUDANG LOG SENGON') AND status = 'OUT' 
                            AND b.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND b.tgl_transaksi <= '{$model->tgl_transaksi}' 
                       ) 
                    ) AS stock
                FROM h_persediaan_log WHERE lokasi IN ('GUDANG LOG SENGON') GROUP BY 1 ORDER BY 1 ASC ";
        $mods = \Yii::$app->db->createCommand($sql)->queryAll();
        $total_ver_kur_19_pcs = 0;
        $total_ver_kur_19_m3 = 0;
        $total_ver_19_24_pcs = 0;
        $total_ver_19_24_m3 = 0;
        $total_ver_25_29_pcs = 0;
        $total_ver_25_29_m3 = 0;
        $total_ver_30_40_pcs = 0;
        $total_ver_30_40_m3 = 0;
        $total_ver_leb_40_pcs = 0;
        $total_ver_leb_40_m3 = 0;
        $total_ver_total_pcs = 0;
        $total_ver_total_m3 = 0;
        if(count($mods)>0){
            foreach($mods as $i => $mod){
        ?>
        <tr>
            <td style="text-align: center;"><?= ($i+1) ?></td>
            <td style="line-height: 1; text-align: left; padding-left: 5px;"><?= $mod['fisik_panjang'] ?> Cm</td>
            <?php
            
            $dia_kur_19 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG SENGON", 29, $mod['fisik_panjang'], $model->tgl_transaksi, -9999, 18);
            $dia_19_24 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG SENGON", 29, $mod['fisik_panjang'], $model->tgl_transaksi, 19, 24);
            $dia_25_29 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG SENGON", 29, $mod['fisik_panjang'], $model->tgl_transaksi, 25, 29);
            $dia_30_40 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG SENGON", 29, $mod['fisik_panjang'], $model->tgl_transaksi, 30, 40);
            $dia_leb_40 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG SENGON", 29, $mod['fisik_panjang'], $model->tgl_transaksi, 41, 9999);
            
            $total_pcs = $dia_kur_19['pcs'] + $dia_19_24['pcs'] + $dia_25_29['pcs'] + $dia_30_40['pcs'] + $dia_leb_40['pcs'];
            $total_m3 = $dia_kur_19['m3'] + $dia_19_24['m3'] + $dia_25_29['m3'] + $dia_30_40['m3'] + $dia_leb_40['m3'];
            
            $total_ver_kur_19_pcs += $dia_kur_19['pcs'];
            $total_ver_kur_19_m3 += $dia_kur_19['m3'];
            $total_ver_19_24_pcs += $dia_19_24['pcs'];
            $total_ver_19_24_m3 += $dia_19_24['m3'];
            $total_ver_25_29_pcs += $dia_25_29['pcs'];
            $total_ver_25_29_m3 += $dia_25_29['m3'];
            $total_ver_30_40_pcs += $dia_30_40['pcs'];
            $total_ver_30_40_m3 += $dia_30_40['m3'];
            $total_ver_leb_40_pcs += $dia_leb_40['pcs'];
            $total_ver_leb_40_m3 += $dia_leb_40['m3'];
            $total_ver_total_pcs += $total_pcs;
            $total_ver_total_m3 += $total_m3;
            
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_kur_19['pcs']).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_kur_19['m3'],3).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_19_24['pcs']).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_19_24['m3'],3).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_25_29['pcs']).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_25_29['m3'],3).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_30_40['pcs']).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_30_40['m3'],3).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_leb_40['pcs']).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_leb_40['m3'],3).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;"><b>'.number_format($total_pcs).'</b></td>';
            echo '<td style="text-align: right; padding-right: 5px;"><b>'.number_format($total_m3,3).'</b></td>';
            ?>
        </tr>
        <?php
            }
        }
        ?>		
        <tr>
            <td style="text-align: right; padding-right: 5px;" colspan="2"> <b>TOTAL &nbsp; </b></td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_kur_19_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_kur_19_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_19_24_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_19_24_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_25_29_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_25_29_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_30_40_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_30_40_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_leb_40_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_leb_40_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_total_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_total_m3,3 )?></b> </td>
        </tr>
    </tbody>
    
</table>

<table class="table table-striped table-bordered table-hover" id="table-laporan" style="width:100%">
    <thead>
        <tr>
            <th colspan="14" style="line-height: 1; padding: 10px; background-color: #FFE5E1" class="font-red-flamingo"><i><b>STOCK LOG SENGON KONDISI AFKIR - (GUDANG LOG AFKIR)</b></i></th>
        </tr>
        <tr>
            <th rowspan="2" style="line-height: 1; width: 30px;">No.</th>
            <th rowspan="2" style="line-height: 1;">Panjang Log</th>
            <th colspan="2" style="line-height: 1;">< 19</th>
            <th colspan="2" style="line-height: 1;">19-24</th>
            <th colspan="2" style="line-height: 1;">25-29</th>
            <th colspan="2" style="line-height: 1;">30-40</th>
            <th colspan="2" style="line-height: 1;">> 40</th>
            <th colspan="2" style="line-height: 1;">Total</th>
        </tr>
        <tr>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $reff_no = explode("-", $model->reff_no);
        $sql = "SELECT  fisik_panjang, 
                    ( (SELECT COALESCE(SUM(fisik_pcs),0) FROM h_persediaan_log AS a 
                            WHERE lokasi = 'GUDANG LOG SENGON' AND status = 'IN' 
                            AND a.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND a.tgl_transaksi <= '{$model->tgl_transaksi}' 
                       ) - 
                      (SELECT COALESCE(SUM(fisik_pcs),0) FROM h_persediaan_log AS b 
                            WHERE lokasi = 'GUDANG LOG SENGON' AND status = 'OUT' 
                            AND b.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND b.tgl_transaksi <= '{$model->tgl_transaksi}' 
                       ) 
                    ) AS stock
                FROM h_persediaan_log WHERE lokasi = 'GUDANG LOG SENGON' GROUP BY 1 ORDER BY 1 ASC";
        $mods = \Yii::$app->db->createCommand($sql)->queryAll();
        $total_ver_kur_19_pcs = 0;
        $total_ver_kur_19_m3 = 0;
        $total_ver_19_24_pcs = 0;
        $total_ver_19_24_m3 = 0;
        $total_ver_25_29_pcs = 0;
        $total_ver_25_29_m3 = 0;
        $total_ver_30_40_pcs = 0;
        $total_ver_30_40_m3 = 0;
        $total_ver_leb_40_pcs = 0;
        $total_ver_leb_40_m3 = 0;
        $total_ver_total_pcs = 0;
        $total_ver_total_m3 = 0;
        if(count($mods)>0){
            foreach($mods as $i => $mod){
        ?>
        <tr>
            <td style="text-align: center;"><?= ($i+1) ?></td>
            <td style="line-height: 1; text-align: left; padding-left: 5px;"><?= $mod['fisik_panjang'] ?> Cm</td>
            <?php
            
            $dia_kur_19 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG AFKIR", 29, $mod['fisik_panjang'], $model->tgl_transaksi, -9999, 18);
            $dia_19_24 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG AFKIR", 29, $mod['fisik_panjang'], $model->tgl_transaksi, 19, 24);
            $dia_25_29 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG AFKIR", 29, $mod['fisik_panjang'], $model->tgl_transaksi, 25, 29);
            $dia_30_40 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG AFKIR", 29, $mod['fisik_panjang'], $model->tgl_transaksi, 30, 40);
            $dia_leb_40 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG AFKIR", 29, $mod['fisik_panjang'], $model->tgl_transaksi, 41, 9999);
            
            $total_pcs = $dia_kur_19['pcs'] + $dia_19_24['pcs'] + $dia_25_29['pcs'] + $dia_30_40['pcs'] + $dia_leb_40['pcs'];
            $total_m3 = $dia_kur_19['m3'] + $dia_19_24['m3'] + $dia_25_29['m3'] + $dia_30_40['m3'] + $dia_leb_40['m3'];
            
            $total_ver_kur_19_pcs += $dia_kur_19['pcs'];
            $total_ver_kur_19_m3 += $dia_kur_19['m3'];
            $total_ver_19_24_pcs += $dia_19_24['pcs'];
            $total_ver_19_24_m3 += $dia_19_24['m3'];
            $total_ver_25_29_pcs += $dia_25_29['pcs'];
            $total_ver_25_29_m3 += $dia_25_29['m3'];
            $total_ver_30_40_pcs += $dia_30_40['pcs'];
            $total_ver_30_40_m3 += $dia_30_40['m3'];
            $total_ver_leb_40_pcs += $dia_leb_40['pcs'];
            $total_ver_leb_40_m3 += $dia_leb_40['m3'];
            $total_ver_total_pcs += $total_pcs;
            $total_ver_total_m3 += $total_m3;
            
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_kur_19['pcs']>0 )? number_format($dia_kur_19['pcs']) :"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_kur_19['m3']>0 )? number_format($dia_kur_19['m3'],3):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_19_24['pcs']>0 )? number_format($dia_19_24['pcs']) :"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_19_24['m3']>0 )? number_format($dia_19_24['m3'],3):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_25_29['pcs']>0 )? number_format($dia_25_29['pcs']):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_25_29['m3']>0 )? number_format($dia_25_29['m3'],3):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_30_40['pcs']>0 )? number_format($dia_30_40['pcs']):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_30_40['m3']>0 )? number_format($dia_30_40['m3'],3):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_leb_40['pcs']>0 )? number_format($dia_leb_40['pcs']):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_leb_40['m3']>0 )? number_format($dia_leb_40['m3'],3):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;"><b>'.(( $total_pcs>0 )? number_format($total_pcs):"0").'</b></td>';
            echo '<td style="text-align: right; padding-right: 5px;"><b>'.(( $total_m3>0 )? number_format($total_m3,3):"0").'</b></td>';
            ?>
        </tr>
        <?php
            }
        }
        ?>
        <tr>
            <td style="text-align: right; padding-right: 5px;" colspan="2"> <b>TOTAL &nbsp; </b></td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_kur_19_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_kur_19_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_19_24_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_19_24_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_25_29_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_25_29_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_30_40_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_30_40_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_leb_40_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_leb_40_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_total_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_total_m3,3 )?></b> </td>
        </tr>
    </tbody>
    
</table>

<!--jabon-->
<?php /*
<table class="table table-striped table-bordered table-hover" id="table-laporan" style="width:100%">
    <thead>
        <tr>
            <th colspan="15" style="line-height: 1; padding: 10px; background-color: #ddf5e4" class="font-green-seagreen"><i><b>STOCK LOG JABON KONDISI BAGUS - (GUDANG LOG UTUH)</b></i></th>
        </tr>
        <tr>
            <th rowspan="2" style="line-height: 1; width: 30px;">No.</th>
            <th rowspan="2" style="line-height: 1;">Panjang Log</th>
            <th colspan="2" style="line-height: 1;">< 19</th>
            <th colspan="2" style="line-height: 1;">19-24</th>
            <th colspan="2" style="line-height: 1;">25-29</th>
            <th colspan="2" style="line-height: 1;">30-40</th>
            <th colspan="2" style="line-height: 1;">> 40</th>
            <th colspan="2" style="line-height: 1;">Total</th>
        </tr>
        <tr>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $reff_no = explode("-", $model->reff_no);
        $sql = "SELECT 
                    fisik_panjang, 
                    ( (SELECT COALESCE(SUM(fisik_pcs),0) FROM h_persediaan_log AS a 
                            WHERE lokasi IN ('GUDANG LOG JABON') AND status = 'IN' 
                            AND a.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND a.tgl_transaksi <= '{$model->tgl_transaksi}' 
                       ) - 
                      (SELECT COALESCE(SUM(fisik_pcs),0) FROM h_persediaan_log AS b 
                            WHERE lokasi IN ('GUDANG LOG JABON') AND status = 'OUT' 
                            AND b.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND b.tgl_transaksi <= '{$model->tgl_transaksi}' 
                       ) 
                    ) AS stock
                FROM h_persediaan_log GROUP BY 1 ORDER BY 1 ASC ";
        $mods = \Yii::$app->db->createCommand($sql)->queryAll();
        $total_ver_kur_19_pcs = 0;
        $total_ver_kur_19_m3 = 0;
        $total_ver_19_24_pcs = 0;
        $total_ver_19_24_m3 = 0;
        $total_ver_25_29_pcs = 0;
        $total_ver_25_29_m3 = 0;
        $total_ver_30_40_pcs = 0;
        $total_ver_30_40_m3 = 0;
        $total_ver_leb_40_pcs = 0;
        $total_ver_leb_40_m3 = 0;
        $total_ver_total_pcs = 0;
        $total_ver_total_m3 = 0;
        if(count($mods)>0){
            foreach($mods as $i => $mod){
        ?>
        <tr>
            <td style="text-align: center;"><?= ($i+1) ?></td>
            <td style="line-height: 1; text-align: left; padding-left: 5px;"><?= $mod['fisik_panjang'] ?> Cm</td>
            <?php
            
            $dia_kur_19 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG JABON", 24, $mod['fisik_panjang'], $model->tgl_transaksi, -9999, 18);
            $dia_19_24 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG JABON", 24, $mod['fisik_panjang'], $model->tgl_transaksi, 19, 24);
            $dia_25_29 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG JABON", 24, $mod['fisik_panjang'], $model->tgl_transaksi, 25, 29);
            $dia_30_40 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG JABON", 24, $mod['fisik_panjang'], $model->tgl_transaksi, 30, 40);
            $dia_leb_40 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG JABON", 24, $mod['fisik_panjang'], $model->tgl_transaksi, 41, 9999);
            
            $total_pcs = $dia_kur_19['pcs'] + $dia_19_24['pcs'] + $dia_25_29['pcs'] + $dia_30_40['pcs'] + $dia_leb_40['pcs'];
            $total_m3 = $dia_kur_19['m3'] + $dia_19_24['m3'] + $dia_25_29['m3'] + $dia_30_40['m3'] + $dia_leb_40['m3'];
            
            $total_ver_kur_19_pcs += $dia_kur_19['pcs'];
            $total_ver_kur_19_m3 += $dia_kur_19['m3'];
            $total_ver_19_24_pcs += $dia_19_24['pcs'];
            $total_ver_19_24_m3 += $dia_19_24['m3'];
            $total_ver_25_29_pcs += $dia_25_29['pcs'];
            $total_ver_25_29_m3 += $dia_25_29['m3'];
            $total_ver_30_40_pcs += $dia_30_40['pcs'];
            $total_ver_30_40_m3 += $dia_30_40['m3'];
            $total_ver_leb_40_pcs += $dia_leb_40['pcs'];
            $total_ver_leb_40_m3 += $dia_leb_40['m3'];
            $total_ver_total_pcs += $total_pcs;
            $total_ver_total_m3 += $total_m3;
            
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_kur_19['pcs']).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_kur_19['m3'],3).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_19_24['pcs']).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_19_24['m3'],3).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_25_29['pcs']).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_25_29['m3'],3).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_30_40['pcs']).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_30_40['m3'],3).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_leb_40['pcs']).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.number_format($dia_leb_40['m3'],3).'</td>';
            echo '<td style="text-align: right; padding-right: 5px;"><b>'.number_format($total_pcs).'</b></td>';
            echo '<td style="text-align: right; padding-right: 5px;"><b>'.number_format($total_m3,3).'</b></td>';
            ?>
        </tr>
        <?php
            }
        }
        ?>		
        <tr>
            <td style="text-align: right; padding-right: 5px;" colspan="2"> <b>TOTAL &nbsp; </b></td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_kur_19_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_kur_19_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_19_24_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_19_24_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_25_29_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_25_29_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_30_40_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_30_40_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_leb_40_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_leb_40_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_total_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_total_m3,3 )?></b> </td>
        </tr>
    </tbody>
    
</table>

<table class="table table-striped table-bordered table-hover" id="table-laporan" style="width:100%">
    <thead>
        <tr>
            <th colspan="14" style="line-height: 1; padding: 10px; background-color: #FFE5E1" class="font-red-flamingo"><i><b>STOCK LOG JABON KONDISI AFKIR - (GUDANG LOG AFKIR)</b></i></th>
        </tr>
        <tr>
            <th rowspan="2" style="line-height: 1; width: 30px;">No.</th>
            <th rowspan="2" style="line-height: 1;">Panjang Log</th>
            <th colspan="2" style="line-height: 1;">< 19</th>
            <th colspan="2" style="line-height: 1;">19-24</th>
            <th colspan="2" style="line-height: 1;">25-29</th>
            <th colspan="2" style="line-height: 1;">30-40</th>
            <th colspan="2" style="line-height: 1;">> 40</th>
            <th colspan="2" style="line-height: 1;">Total</th>
        </tr>
        <tr>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
            <th style="line-height: 1; width: 80px;">Pcs</th>
            <th style="line-height: 1; width: 80px;">M<sup>3</sup></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $reff_no = explode("-", $model->reff_no);
        $sql = "SELECT  fisik_panjang, 
                    ( (SELECT COALESCE(SUM(fisik_pcs),0) FROM h_persediaan_log AS a 
                            WHERE lokasi = 'GUDANG LOG SENGON' AND status = 'IN' 
                            AND a.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND a.tgl_transaksi <= '{$model->tgl_transaksi}' 
                       ) - 
                      (SELECT COALESCE(SUM(fisik_pcs),0) FROM h_persediaan_log AS b 
                            WHERE lokasi = 'GUDANG LOG SENGON' AND status = 'OUT' 
                            AND b.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND b.tgl_transaksi <= '{$model->tgl_transaksi}' 
                       ) 
                    ) AS stock
                FROM h_persediaan_log GROUP BY 1 ORDER BY 1 ASC";
        $mods = \Yii::$app->db->createCommand($sql)->queryAll();
        $total_ver_kur_19_pcs = 0;
        $total_ver_kur_19_m3 = 0;
        $total_ver_19_24_pcs = 0;
        $total_ver_19_24_m3 = 0;
        $total_ver_25_29_pcs = 0;
        $total_ver_25_29_m3 = 0;
        $total_ver_30_40_pcs = 0;
        $total_ver_30_40_m3 = 0;
        $total_ver_leb_40_pcs = 0;
        $total_ver_leb_40_m3 = 0;
        $total_ver_total_pcs = 0;
        $total_ver_total_m3 = 0;
        if(count($mods)>0){
            foreach($mods as $i => $mod){
        ?>
        <tr>
            <td style="text-align: center;"><?= ($i+1) ?></td>
            <td style="line-height: 1; text-align: left; padding-left: 5px;"><?= $mod['fisik_panjang'] ?> Cm</td>
            <?php
            
            $dia_kur_19 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG JABON AFKIR", 24, $mod['fisik_panjang'], $model->tgl_transaksi, -9999, 18);
            $dia_19_24 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG JABON AFKIR", 24, $mod['fisik_panjang'], $model->tgl_transaksi, 19, 24);
            $dia_25_29 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG JABON AFKIR", 24, $mod['fisik_panjang'], $model->tgl_transaksi, 25, 29);
            $dia_30_40 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG JABON AFKIR", 24, $mod['fisik_panjang'], $model->tgl_transaksi, 30, 40);
            $dia_leb_40 = app\models\HPersediaanLog::getStockSengonRekap("GUDANG LOG JABON AFKIR", 24, $mod['fisik_panjang'], $model->tgl_transaksi, 41, 9999);
            
            $total_pcs = $dia_kur_19['pcs'] + $dia_19_24['pcs'] + $dia_25_29['pcs'] + $dia_30_40['pcs'] + $dia_leb_40['pcs'];
            $total_m3 = $dia_kur_19['m3'] + $dia_19_24['m3'] + $dia_25_29['m3'] + $dia_30_40['m3'] + $dia_leb_40['m3'];
            
            $total_ver_kur_19_pcs += $dia_kur_19['pcs'];
            $total_ver_kur_19_m3 += $dia_kur_19['m3'];
            $total_ver_19_24_pcs += $dia_19_24['pcs'];
            $total_ver_19_24_m3 += $dia_19_24['m3'];
            $total_ver_25_29_pcs += $dia_25_29['pcs'];
            $total_ver_25_29_m3 += $dia_25_29['m3'];
            $total_ver_30_40_pcs += $dia_30_40['pcs'];
            $total_ver_30_40_m3 += $dia_30_40['m3'];
            $total_ver_leb_40_pcs += $dia_leb_40['pcs'];
            $total_ver_leb_40_m3 += $dia_leb_40['m3'];
            $total_ver_total_pcs += $total_pcs;
            $total_ver_total_m3 += $total_m3;
            
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_kur_19['pcs']>0 )? number_format($dia_kur_19['pcs']) :"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_kur_19['m3']>0 )? number_format($dia_kur_19['m3'],3):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_19_24['pcs']>0 )? number_format($dia_19_24['pcs']) :"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_19_24['m3']>0 )? number_format($dia_19_24['m3'],3):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_25_29['pcs']>0 )? number_format($dia_25_29['pcs']):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_25_29['m3']>0 )? number_format($dia_25_29['m3'],3):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_30_40['pcs']>0 )? number_format($dia_30_40['pcs']):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_30_40['m3']>0 )? number_format($dia_30_40['m3'],3):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_leb_40['pcs']>0 )? number_format($dia_leb_40['pcs']):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;">'.(( $dia_leb_40['m3']>0 )? number_format($dia_leb_40['m3'],3):"0").'</td>';
            echo '<td style="text-align: right; padding-right: 5px;"><b>'.(( $total_pcs>0 )? number_format($total_pcs):"0").'</b></td>';
            echo '<td style="text-align: right; padding-right: 5px;"><b>'.(( $total_m3>0 )? number_format($total_m3,3):"0").'</b></td>';
            ?>
        </tr>
        <?php
            }
        }
        ?>
        <tr>
            <td style="text-align: right; padding-right: 5px;" colspan="2"> <b>TOTAL &nbsp; </b></td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_kur_19_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_kur_19_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_19_24_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_19_24_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_25_29_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_25_29_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_30_40_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_30_40_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_leb_40_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_leb_40_m3,3 )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_total_pcs )?></b> </td>
            <td style="text-align: right; padding-right: 5px;"> <b><?= number_format( $total_ver_total_m3,3 )?></b> </td>
        </tr>
    </tbody>
    
</table> */ ?>