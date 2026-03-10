<?php if($jenis_produk == "Log"){ 
    $data = [];
    foreach($notadetail as $n => $row){
        $produk_id = $row['produk_id'];
        $kode = $row['kode'];
        $kode_nota = '<b>'.$kode . '</b> - ' . \app\components\DeltaFormatter::formatDateTimeForUser2($row['tanggal']);
        $nopol = $row['kendaraan_nopol'] .'/'. $row['kendaraan_supir'];
        $tgl_spm = $row['tgl_spm'];
        $key = $produk_id .'_'.$row['harga_jual'];
        if (!isset($data[$key])) {
            $data[$key] = [
                    'nota' => $kode_nota,
                    'produk' => $row['log_kelompok'] .' - '. $row['kayu_nama'] . ' (' . $row['range_awal'] .'-'.$row['range_akhir'] .')',
                    'tgl_spm' => \app\components\DeltaFormatter::formatDateTimeForUser2($tgl_spm),
                    'nopol' => $nopol,
                    'qty' => $row['qty_kecil'],
                    'kubikasi' => $row['kubikasi'],
                    'harga_nota' => $row['harga_jual'],
                    'nota_kode' => [$kode]
            ];
        } else {
            $data[$key]['qty'] += $row['qty_kecil'];
            $data[$key]['kubikasi'] += $row['kubikasi'];

            if (!in_array($kode, $data[$key]['nota_kode'])) {
                $data[$key]['nota'] .= '<br>' . $kode_nota;
                $data[$key]['nopol'] .= '<br>' . $nopol;
                $data[$key]['tgl_spm'] .= '<br>' . \app\components\DeltaFormatter::formatDateTimeForUser2($tgl_spm);
                $data[$key]['nota_kode'][] = $kode;
            }
        }
    } 

    foreach ($data as $d){ ?>
        <tr>
            <td style="vertical-align: middle; text-align: center;" class="td-kecil">
                <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
                <span class="no_urut"></span>
            </td>
            <td style="vertical-align: middle;" class="td-kecil">
                <?php echo $d['nota'] ?>
                <?php echo yii\helpers\Html::activeHiddenInput($modDetail, "[$i]produk_id") ?>
            </td>
            <td style="vertical-align: middle;" class="td-kecil"><?php echo $d['produk'] ?></td>
            <td style="vertical-align: middle;" class="td-kecil"><?php echo $d['tgl_spm'] ?></td>
            <td style="vertical-align: middle;" class="td-kecil"><?php echo $d['nopol'] ?></td>
            <td style="vertical-align: middle;" class="td-kecil">
                <?php $modDetail->qty_kecil = $d['qty']; ?>
                <?= \yii\helpers\Html::activeTextInput($modDetail, "[$i]qty_kecil",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
            </td>
            <td style="vertical-align: middle;" class="td-kecil">
                <?php $modDetail->kubikasi = $d['kubikasi']; ?>
                <?= \yii\helpers\Html::activeTextInput($modDetail, "[$i]kubikasi",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
            </td>
            <td style="vertical-align: middle;" class="td-kecil text-align-right" id="place-detail-total">
                <?php $modDetail->harga_nota = $d['harga_nota']; ?>
                <?= \yii\helpers\Html::activeTextInput($modDetail, "[$i]harga_invoice",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
                <?php echo yii\helpers\Html::activeHiddenInput($modDetail, "[$i]harga_nota") ?>
            </td>
            <td style="vertical-align: middle;" class="td-kecil text-align-right" id="place-detail-total">
                <?= \yii\helpers\Html::activeTextInput($modDetail, "[$i]subtotal",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
            </td>
        </tr>
<?php }
} else { 
    $modNota = app\models\TNotaPenjualan::findOne($nota_penjualan_id);
    $modSpm = app\models\TSpmKo::findOne($modNota->spm_ko_id);
    $modJasa = app\models\MProdukJasa::findOne($notadetail['produk_id']);
    $nama = $modJasa->nama;
?>
    <tr>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
            <span class="no_urut"></span>
        </td>
        <td style="vertical-align: middle;" class="td-kecil">
            <b><?php echo $modNota->kode ?></b> - <?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modNota->tanggal) ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil" id="place-detail-deskripsi">
            <?= $nama ?>
            <?php echo yii\helpers\Html::activeHiddenInput($modDetail, "[$i]produk_id") ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center" id="place-detail-kirim-tanggal">
            <?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modSpm->tanggal);?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-left" id="place-detail-kirim-nopolsupir">
        <?php echo $modSpm->kendaraan_nopol." / ".$modSpm->kendaraan_supir; ?>
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
    </tr>
<?php } ?>