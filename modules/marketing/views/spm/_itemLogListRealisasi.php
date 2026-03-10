<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
		    <?php echo yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_id", ['class'=>'produk_id', 'value'=>$modSpmKoDetail->produk_id]); ?>
        <?php 
        if($modPersediaan){
          echo yii\bootstrap\Html::activeHiddenInput($modPersediaan, "[ii]fisik_pcs", ['class'=>'fisik_pcs']);
        }
        ?>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		  <?= yii\bootstrap\Html::activeHiddenInput($modSpmLog, "[ii]spm_log_id"); ?>
		  <strong><?= $model->no_barcode ?></strong>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		  <?= $modKayu->group_kayu .' <br> '. $modKayu->kayu_nama ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->no_lap ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->no_grade ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->no_btg ?>
    </td>
    <?php if($modPersediaan){ ?>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modPersediaan->fisik_panjang ?>
    </td>
	  <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modPersediaan->diameter_ujung1 ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modPersediaan->diameter_ujung2 ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modPersediaan->diameter_pangkal1 ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modPersediaan->diameter_pangkal2 ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modPersediaan->cacat_panjang ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modPersediaan->cacat_gb ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modPersediaan->cacat_gr ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modPersediaan->fisik_volume ?>
    </td>
    <?php } else { ?>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->panjang ?>
    </td>
	  <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->diameter_ujung1 ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->diameter_ujung2 ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->diameter_pangkal1 ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->diameter_pangkal2 ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->cacat_panjang ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->cacat_gb ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->cacat_gr ?>
    </td>
    <td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog->volume ?>
      <?php echo yii\helpers\Html::activeHiddenInput($modSpmLog, "[ii]volume",['class'=>'form-control float volume','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'disabled'=>'disabled']); ?>
    </td>
    <?php } ?>
    <td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog['panjang'] ?>
    </td>
    <td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog['diameter_ujung1'] ?>
    </td>
    <td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog['diameter_ujung2'] ?>
    </td>
    <td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog['diameter_pangkal1'] ?>
    </td>
    <td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog['diameter_pangkal2'] ?>
    </td>
    <td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog['diameter_rata'] ?>
      <?php echo yii\helpers\Html::activeHiddenInput($modSpmLog, "[ii]diameter_rata",['class'=>'form-control float diameter_rata','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
    </td>
    <td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog['cacat_panjang'] ?>
    </td>
    <td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog['cacat_gb'] ?>
    </td>
    <td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog['cacat_gr'] ?>
    </td>
    <td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
      <?= $modSpmLog['volume'] ?>
      <?php echo yii\helpers\Html::activeHiddenInput($modSpmLog, "[ii]volume",['class'=>'form-control float volume','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
      -
    </td>
</tr>
<script>

</script>
