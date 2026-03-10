<?php
if(!empty($model->spk_sawmill_id) && empty($edit)){
    $disabled = true;
    $removebtn = '<center><a class="btn btn-xs grey" disabled="disabled"><i class="fa fa-remove"></i></a></center>';
    $addpjg = '<a class="btn btn-xs grey" style="margin-top: 5px; display: none;"><i class="fa fa-plus"></i></a>';
    $removepjg = '<a class="btn btn-xs grey" style="margin-top: 5px; display: none;"><i class="fa fa-trash-o"></i></a>';
    $addsize = '<a class="btn btn-xs grey" style="margin-top: 5px; display: none;"><i class="fa fa-plus"></i></a>';
}else{
    $disabled = false;
    $removebtn = '<center><a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a></center>';
    $addpjg = '<a class="btn btn-xs blue-hoki btn-outline" style="margin-top: 5px;" onclick="addPjg(this);"><i class="fa fa-plus"></i></a>';
    $removepjg = '<a class="btn btn-xs red" style="margin-top: 5px;" onclick="removePjg(this);"><i class="fa fa-trash-o"></i></a>';
    $addsize = '<a class="btn btn-xs green btn-outline" style="margin-top: 5px;" onclick="addListSize(this);"><i class="fa fa-plus"></i> Add List Size</a>';
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]spk_sawmill_detail_id") ?>
        <span class="no_urut"></span>
    </td>
    <td>
        <?php 
        if($model->spk_sawmill_id){
            $modDetail->size = $modDetail->produk_t . 'x' . $modDetail->produk_l;
        }
        echo \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]size', \app\models\MDefaultValue::getOptionList('size-sawmill'),['class'=>'form-control select2', 'prompt'=>'','disabled'=>$disabled]); 
        echo $addsize;
        ?>
    </td>
    <td>
        <?php
        if($model->spk_sawmill_id){
            $modPanjang = Yii::$app->db->createCommand("SELECT produk_p FROM t_spk_sawmill_detail 
                                                        WHERE spk_sawmill_id = $model->spk_sawmill_id AND produk_t = $modDetail->produk_t 
                                                        AND produk_l = $modDetail->produk_l AND kategori_ukuran = '$modDetail->kategori_ukuran' 
                                                      ")->queryAll();
            $listPanjang = [];
            foreach ($modPanjang as $row) {
                $listPanjang[] = $row['produk_p'];
            } ?>
            <div class="place-panjang" style="display: flex; flex-wrap: wrap; gap: 3px;">
                <?php 
                foreach ($listPanjang as $pjg){ ?>
                    <?= yii\bootstrap\Html::activeTextInput($modDetail, '[ii]panjang[]', ['value' => $pjg,
                        'class' => 'form-control float', 'style' => 'width:70px; font-size:1.2rem; display:inline-block;', 'disabled' => $disabled
                    ]);
                } 
                ?>
            </div>
        <?php } else { ?>
            <div class="place-panjang" style="display: flex; flex-wrap: wrap; gap: 3px;">
                <input type="text" class="form-control float" name="TSpkSawmillDetail[ii][panjang][]" style="display: inline-block; font-size: 1.2rem; width: 70px; margin-right: 3px;">
            </div>
        <?php } ?>
        <?php echo $removepjg; ?>
        <?php echo $addpjg; ?>
    </td>
    <td>
        <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]kategori_ukuran', \app\models\MDefaultValue::getOptionList('kategori-ukuran'),['class'=>'form-control','style'=>'font-size: 1.2rem;', 'prompt'=>'','disabled'=>$disabled]); ?>
    </td>
    <td>
        <?php echo $removebtn; ?>
    </td>
</tr>