<?php
$nama = '';
if($jenis_produk == "Log"){
    $modNota = app\models\TNotaPenjualan::findOne($nota_penjualan_id);
    $modLog = app\models\MBrgLog::findOne($notadetail['produk_id']);
    $modKayu = app\models\MKayu::findOne($modLog->kayu_id);
    $nama = $modLog->log_kelompok .' - '. $modKayu->kayu_nama. ' ('. $modLog->range_awal.'cm - '. $modLog->range_akhir. 'cm)';
    $nota_id = implode(', ', $nota_penjualan_id);
    $modInfo = Yii::$app->db->createCommand("
                    SELECT t_nota_penjualan.kode, t_nota_penjualan.tanggal, t_spm_ko.tanggal_kirim as tgl_spm, t_spm_ko.kendaraan_nopol, t_spm_ko.kendaraan_supir
                    FROM t_nota_penjualan 
                    JOIN t_nota_penjualan_detail on t_nota_penjualan_detail.nota_penjualan_id = t_nota_penjualan.nota_penjualan_id
                    LEFT JOIN t_spm_ko ON t_spm_ko.spm_ko_id = t_nota_penjualan.spm_ko_id
                    WHERE t_nota_penjualan.nota_penjualan_id in ({$nota_id}) and jenis_produk = 'Log'
                    GROUP BY t_nota_penjualan.nota_penjualan_id, t_nota_penjualan.kode, t_nota_penjualan.tanggal, t_spm_ko.tanggal_kirim, t_spm_ko.kendaraan_nopol, 
                        t_spm_ko.kendaraan_supir
                    ORDER BY t_nota_penjualan.nota_penjualan_id
                ")->queryAll();
} else { //else if($jenis_produk == "JasaGesek")
    $modNota = app\models\TNotaPenjualan::findOne($nota_penjualan_id);
    $modSpm = app\models\TSpmKo::findOne($modNota->spm_ko_id);
    $modJasa = app\models\MProdukJasa::findOne($notadetail['produk_id']);
    $nama = $modJasa->nama;
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?php 
        if($jenis_produk == "Log"){
            foreach ($modInfo as $a => $info){
                echo '<b>'.$info['kode'].'</b> - '. \app\components\DeltaFormatter::formatDateTimeForUser2($info['tanggal']).'<br>';
            }
        } else {?>
            <b><?php echo $modNota->kode ?></b> - <?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modNota->tanggal) ?>
        <?php } ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil" id="place-detail-deskripsi">
		<?= $nama ?>
        <?php echo yii\helpers\Html::activeHiddenInput($modDetail, "[$i]produk_id") ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center" id="place-detail-kirim-tanggal">
        <?php 
        if($jenis_produk == "Log"){
            foreach ($modInfo as $a => $info){
                echo \app\components\DeltaFormatter::formatDateTimeForUser2($info['tgl_spm']).'<br>';
            }
        } else {
            echo \app\components\DeltaFormatter::formatDateTimeForUser2($modSpm->tanggal);
        } ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-left" id="place-detail-kirim-nopolsupir">
    <?php 
        if($jenis_produk == "Log"){
            foreach ($modInfo as $a => $info){
                echo $info['kendaraan_nopol']." / ".$info['kendaraan_supir'].'<br>';
            }
        } else {
            echo $modSpm->kendaraan_nopol." / ".$modSpm->kendaraan_supir;
        } ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right" id="place-detail-qty-pcs">
        <?= \yii\helpers\Html::activeTextInput($modDetail, "[$i]qty_kecil",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right" id="place-detail-qty-m3">
        <?= \yii\helpers\Html::activeTextInput($modDetail, "[$i]kubikasi",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right" id="place-detail-total">
        <?= \yii\helpers\Html::activeTextInput($modDetail, "[$i]harga_invoice",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
        <?php echo yii\helpers\Html::activeHiddenInput($modDetail, "[$i]harga_nota") ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right" id="place-detail-total">
        <?= \yii\helpers\Html::activeTextInput($modDetail, "[$i]subtotal",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
    </td>
    <!-- <td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?php //echo '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'total()\');"><i class="fa fa-remove"></i></a></center>'; ?>
    </td> -->
</tr>
<script>
</script>