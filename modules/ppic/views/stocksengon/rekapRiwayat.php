<table class="table table-striped table-bordered table-hover" id="table-detail-riwayat" style="width: 100%;">
    <thead>
        <tr>
            <th rowspan="2" style="text-align: center; width: 35px;"><?= Yii::t('app', 'No.'); ?></th>
            <th rowspan="2" style="text-align: center; width: 120px; line-height: 1" class=""><?= Yii::t('app', 'Tanggal<br>Transaksi'); ?></th>
            <th rowspan="2" style="text-align: center; width: 130px; line-height: 1"><?= Yii::t('app', 'Reff Number'); ?></th>
            <th rowspan="2" style="text-align: center; "><?= Yii::t('app', 'Deskripsi'); ?></th>
            <th rowspan="2" style="text-align: center; "><?= Yii::t('app', 'Lokasi'); ?></th>
            <th rowspan="2" style="text-align: center; "><?= Yii::t('app', 'Status'); ?></th>
            <th colspan="2" style="text-align: center; "><?= Yii::t('app', 'Amount'); ?></th>
        </tr>
        <tr>
            <th style="text-align: center; width: 80px;"><?= Yii::t('app', 'Pcs'); ?></th>
            <th style="text-align: center; width: 100px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT tgl_transaksi, substring(reff_no,1,12) AS reff_no, status, lokasi, keterangan FROM h_persediaan_log WHERE tgl_transaksi BETWEEN '".$model->tgl_awal."' AND '".$model->tgl_akhir."' GROUP BY 1,2,3,4,5 ORDER BY tgl_transaksi ASC, reff_no ASC, status DESC";
        $mods = Yii::$app->db->createCommand($sql)->queryAll();
        if(count($mods)>0){
            foreach($mods as $i => $mod){
        ?>
        <tr style="" class="">
            <td class="td-kecil text-align-center" style="font-size: 1.2rem;"><?= ($i+1) ?></td>
            <td class="td-kecil text-align-center" style=""><?= \app\components\DeltaFormatter::formatDateTimeForUser($mod['tgl_transaksi']) ?></td>
            <td class="td-kecil text-align-center" style=""><?= $mod['reff_no'] ?></td>
            <td class="td-kecil text-align-left" style="">
                <?php
                $ket = substr($mod['reff_no'],0,3);
                if($ket == "TSG"){
                    $modTerima = app\models\TTerimaSengon::findOne(['kode'=>$mod['reff_no']]);
                    $ket = "PENERIMAAN LOG SENGON ".(!empty($modTerima->kode)?$modTerima->kode:'')." NOPOL TRUCK : ".(!empty($modTerima->nopol)?$modTerima->nopol:"");
                }else if($ket == "MLS"){
                    if($mod['status']=="IN"){
                        $ket = "MUTASI MASUK KE ".$mod['lokasi'];
                    }
                    if($mod['status']=="OUT"){
                        $ket = "MUTASI KELUAR DARI ".$mod['lokasi'];
                    }
                    
                }else if($ket == "TJB"){
                    $modTerima = app\models\TTerimaSengon::findOne(['kode'=>$mod['reff_no']]);
                    $ket = "PENERIMAAN LOG JABON ".(!empty($modTerima->kode)?$modTerima->kode:'')." NOPOL TRUCK : ".(!empty($modTerima->nopol)?$modTerima->nopol:"");
                }else if($ket == "MLJ"){
                    if($mod['status']=="IN"){
                        $ket = "MUTASI MASUK KE ".$mod['lokasi'];
                    }
                    if($mod['status']=="OUT"){
                        $ket = "MUTASI KELUAR DARI ".$mod['lokasi'];
                    }
                                   
                    
                }else{
                    $ket = $mod['keterangan'];
                }
                echo $ket;
                ?>
            </td>
            <td class="td-kecil text-align-center" style=""><?= $mod['lokasi'] ?></td>
            <td class="td-kecil text-align-center" style=""><?= $mod['status'] ?></td>
            <?php
            $qwe = substr($mod['reff_no'],0,3);
            $stocks = Yii::$app->db->createCommand("SELECT SUM(fisik_pcs) AS pcs, SUM(fisik_volume) AS m3 FROM h_persediaan_log WHERE tgl_transaksi BETWEEN '".$model->tgl_awal."' AND '".$model->tgl_akhir."' AND substring(reff_no,1,12) = '".$mod['reff_no']."' AND lokasi = '".$mod['lokasi']."' AND status='".$mod['status']."'")->queryOne();
            ?>
            <td class="td-kecil text-align-right" style=""><?= !empty($stocks['pcs'])?number_format($stocks['pcs']):0 ?></td>
            <td class="td-kecil text-align-right" style=""><?= !empty($stocks['m3'])?number_format($stocks['m3'],3):0 ?></td>
        </tr>
        <?php
            }
        }
        ?>
    </tbody>
    <tfoot>

    </tfoot>
</table>