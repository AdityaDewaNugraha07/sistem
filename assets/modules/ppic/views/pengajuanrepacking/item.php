<?php
if(!empty($model->pengajuan_repacking_detail_id)){
    if(!empty($edit)){
        $disabled = false;
    }else{
        $disabled = true;
    }
}else{
    $disabled = false;
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
        <span class="no_urut"></span>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]pengajuan_repacking_detail_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]pengajuan_repacking_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]produk_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]qty_stock") ?>
    </td>
    <td style="text-align: left;" class="td-kecil">
        <b><?= $model->produk_nama ?></b><br>
        <?= $model->produk_dimensi ?>
    </td>
    <td style="text-align: center;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($model, '[ii]qty_besar',['class'=>'form-control','style'=>'padding:2px; text-align:right; width:100%;','onblur'=>'total();','disabled'=>$disabled]) ?>
    </td>
    <td style="text-align: center;" class="td-kecil">
        <?php 
        if($disabled==false){
          echo "<b>".$model->qty_stock." Palet</b><br>(". number_format($modStock['kubikasi'],4)." m<sup>3</sup>)";
        }else{
            echo "-";
        }
        ?>
    </td>
    <td style="text-align: center;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($model, '[ii]keterangan',['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem;','disabled'=>$disabled]) ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php
        if($disabled==true){
            echo '<center><a class="btn btn-xs grey"><i class="fa fa-remove"></i></a></center>';
        }else{
            echo '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'total()\');"><i class="fa fa-remove"></i></a></center>';
        }
        ?>
    </td>
</tr>
<script>

</script>
