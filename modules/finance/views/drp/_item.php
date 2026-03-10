<?php 
    $sql =  "SELECT keterangan FROM t_voucher_pengeluarandetail WHERE voucher_pengeluaran_id = {$detail['voucher_pengeluaran_id']}";
    $modes = Yii::$app->db->createCommand($sql)->queryAll(); 
    $ket= '';
    foreach($modes as $m => $mod){ 
        if(count($modes) > 1){
            $ket .= "- ". $mod['keterangan'] ." \n";
        } else {
            $ket .=  $mod['keterangan'] ;
        }
    }  

    $supplier='<center>-</center>';
    if($detail['suplier_nm_company'] !== null){
        $supplier = $detail['suplier_nm_company'];
    }elseif($detail['suplier_nm'] !== null){
        $supplier = $detail['suplier_nm'];
    }else if($detail['gkk_kode'] !== null){
        $supplier= "<a onclick='gkk(".$detail['gkk_id'].")'>".$detail['gkk_kode']."</a>";
    }else if($detail['ppk_kode'] !== null){
        $supplier= "<a onclick='ppk(".$detail['ppk_id'].")'>".$detail['ppk_kode']."</a>";
    }else if($detail['pdg_kode'] !== null){
        $supplier="<a onclick='ajuanDinas(".$detail['ajuandinas_grader_id'].")'>".$detail['pdg_kode']."</a>";
    }else if($detail['pmg_kode'] !== null){
        $supplier="<a onclick='ajuanMakan(".$detail['ajuanmakan_grader_id'].")'>".$detail['pmg_kode']."</a>";
    }else if($detail['kode_dp'] !== null){
        $supplier= "<a onclick='infoAjuanDp(".$detail['log_bayar_dp_id'].")'>".$detail['kode_dp']."</a>";
    }else if($detail['kode_pelunasan'] !== null){
        $supplier= "<a onclick='infoPelunasan(".$detail['log_bayar_muat_id'].")'>".$detail['kode_pelunasan']."</a>";
    }else if($detail['tipe_ov'] !== null){
        if($detail['tipe_ov'] == "PEMBAYARAN ASURANSI LOG SHIPPING"){
            $supplier = $detail['kepada'];
        } else if($detail['tipe_ov'] == "REGULER"){
            $supplier = $detail['nama_penerima'] . " <br>(".$detail['nama_perusahaan'] .")";
        } else {
            if($detail['company_ov'] !==  null){
                $supplier = $detail['company_ov'];
            } else {
                $supplier = $detail['suplier_ov'];
            }
        }
    }

    $modVoucherPengeluaran = app\models\TVoucherPengeluaran::findOne($detail['voucher_pengeluaran_id']);
?>

<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?php echo yii\bootstrap\Html::activeHiddenInput($modDrpDetail, '['.$i.']voucher_pengeluaran_id',['value'=>$detail['voucher_pengeluaran_id'],'disabled'=>'disabled']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle; ">
        <div class="form-control" style="vertical-align: middle; font-size:1.2rem;"><?= $detail['kode'] ?></div>
    </td>
    <td style="vertical-align: middle; ">
        <div class="form-control td-kecil text-align-center" style="<?= ($detail['tipe_ov'] !== null)?'height: auto;':'' ?>"> <!-- style="font-size:1.2rem; text-align: center;" -->
            <?php 
                if($detail['tipe_ov'] == null) {
                    $tipe = $modVoucherPengeluaran->tipe;
                } else {
                    $tipe = $modVoucherPengeluaran->tipe.'<br><b>'.$detail['tipe_ov'].'</b>';
                }
            ?>
            <?= $tipe ?>
        </div>
    </td>
    <td style="vertical-align: middle;">
        <div class="form-control td-kecil text-align-center"><?= $supplier ?></div>
    </td>
    <!-- <td style="vertical-align: middle;">
        <?php
        // if($edit == null){
        //     echo \yii\helpers\Html::activeTextarea($modDrpDetail, '['.$i.']reff_ket', ['class'=>'form-control','rows' => 2, 'style'=>'vertical-align: middle; font-size:1.2rem']); 
        // } else {
        //     echo \yii\helpers\Html::activeTextarea($modDrpDetail, '['.$i.']reff_ket', ['class'=>'form-control','rows' => 2, 'style'=>'vertical-align: middle; font-size:1.2rem', 'value'=>$detail['reff_ket']]); 
        // }
        ?>
    </td> -->
    <td style="vertical-align: middle;">
        <?php 
        $row = (count($modes) == 1)?2:count($modes);
        if($edit == null){
            echo \yii\helpers\Html::activeTextarea($modDrpDetail, '['.$i.']keterangan', ['class'=>'form-control','rows' => $row, 'style'=>'vertical-align: middle; font-size:1.2rem;', 'value'=>$ket]);
        } else {
            echo \yii\helpers\Html::activeTextarea($modDrpDetail, '['.$i.']keterangan', ['class'=>'form-control','rows' => 2, 'style'=>'vertical-align: middle; font-size:1.2rem', 'value'=>$detail['keterangan']]);
        }
        ?>
    </td>
    <td style="vertical-align: middle; text-align: right">
        <div class="form-control" style="vertical-align: middle; font-size:1.2rem"><?= app\components\DeltaFormatter::formatNumberForUserFloat($detail['total_nominal']); ?></div>
        <input type="hidden" value="<?= app\components\DeltaFormatter::formatNumberForUserFloat($detail['total_nominal']); ?>" class="form-control td-kecil text-align-right" id="jumlah" disabled>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?php
        if($edit == null){
            // echo \yii\bootstrap\Html::activeDropDownList($modDrpDetail, '['.$i.']kategori', ['DRP Operational'=>'DRP Operational', 'DRP Log Sengon'=>'DRP Log Sengon', 'DRP Log Alam'=>'DRP Log Alam'], ['prompt'=>'','class'=>'form-control select2']);
            echo \yii\helpers\Html::activeDropDownList($modDrpDetail, '['.$i.']kategori', ['DRP Operational'=>'DRP Operational', 'DRP Log Sengon'=>'DRP Log Sengon', 'DRP Log Alam'=>'DRP Log Alam'], ['prompt'=>'','class'=>'form-control select2', 'style'=>'font-size:1.2rem;']); 
        } else {
            echo \yii\helpers\Html::activeDropDownList($modDrpDetail, '['.$i.']kategori', ['DRP Operational'=>'DRP Operational', 'DRP Log Sengon'=>'DRP Log Sengon', 'DRP Log Alam'=>'DRP Log Alam'], ['class'=>'form-control select2', 'style'=>'font-size:1.2rem;',
                'options' => [
                    'DRP Operational' => ['Selected' => $detail['kategori'] == 'DRP Operational'],
                    'DRP Log Sengon' => ['Selected' => $detail['kategori'] == 'DRP Log Sengon'],
                    'DRP Log Alam' => ['Selected' => $detail['kategori'] == 'DRP Log Alam'],
                ]]); 
        }
        ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <a class="btn btn-xs red" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>

<script>
    
</script>