<tr class="<?= $isFirst ? 'first-row-nobandsaw' : '' ?>" id="tr-<?= $n; ?>-<?= $i; ?>">
    <td style="display: none;">
        <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[$n]nomor_bandsaw", ['class'=>'form-control', 'value'=>$noban]); ?>
    </td>

    <?php if($isFirst){ ?>
    <!-- <td rowspan="<?php //echo $rowspan ?>" style="vertical-align: top; text-align: center;">
        <?php //echo yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td> -->
    <td rowspan="<?= $rowspan ?>">
        <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '['.$n.']nomor_bandsaw', \app\models\MDefaultValue::getOptionList('nomor-bandsaw'),['class'=>'form-control','style'=>'font-size: 1.2rem; width: 55px;', 'prompt'=>'','disabled'=>'', 'value'=>$noban]); ?>
        <?= '<a class="btn btn-xs blue-hoki btn-add-size" style="margin-top: 5px;" onclick="addSize(this, '.$n.', '.$i.');"><i class="fa fa-plus"></i> Add Size</a>';?>
    </td>
    <?php } ?>
    <td>
        <div class="place-size-<?= $n ?>-<?= $i ?>" style="display: flex; flex-direction: column; gap: 5px; margin-bottom: 3px;">
            <div style="display: flex; align-items: center; gap: 5px;">
                <?php 
                echo \yii\bootstrap\Html::activeDropDownList($modDetail, '['.$n.']['.$i.']size', \app\models\MDefaultValue::getOptionList('size-sawmill'),['class'=>'form-control select2','style'=>'font-size: 1.2rem; width: 90px;', 'prompt'=>'','disabled'=>false]);
                echo '<a class="btn btn-xs hijau" style="margin-top: 5px;" id="button-save-size" onclick="saveSize(this, '.$n.', '.$i.')">
                        <i class="fa fa-check"></i>
                    </a>';  
                ?>
            </div>
            <div>
                <a class="btn btn-xs green btn-outline" style="margin-top: 5px;" onclick="addListSize(this);"><i class="fa fa-plus"></i> Add List Size</a>
            </div>
        </div>
    </td>
    <td>
        <div class="place-panjang-<?= $n ?>-<?= $i ?>-<?= $p ?>" style="display: flex; align-items: center; gap: 5px; margin-bottom: 3px;">
            <?php
            echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$n.']['.$i.']['.$p.']panjang', ['class' => 'form-control float input-pjg', 'style' => 'width:55px; font-size:1.2rem;', 'readonly' => false]); 
            // echo '<a class="btn btn-xs hijau" style="margin-top: 5px; pointer-events: none;" id="button-save-pjg" onclick="savePjg(this, '.$n.', '.$i.', '.$p.')" disabled>
            //         <i class="fa fa-check"></i>
            //     </a>';
            echo yii\helpers\Html::activeHiddenInput($modDetail, '['.$n.']['.$i.']['.$p.']bandsaw_detail_id');
            ?>
        </div>
        <div id = 'place-btn-pjg' style="display: none;">
            <?php
            echo '<a class="btn btn-xs blue-hoki btn-outline btn-add-pjg" style="margin-top: 5px;" onclick="addPjg(this, '.$n.', '.$i.', '.$p.');"><i class="fa fa-plus"></i></a>';
            ?>
        </div>
    </td>
    <td>
        <div class="place-jml-<?= $n ?>-<?= $i ?>-<?= $p ?>" style="display: flex; align-items: center; gap: 5px; margin-bottom: 3px;">
            <?php
            echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$n.']['.$i.']['.$p.']jml', ['class' => 'form-control', 'style' => 'width:60px; font-size:1.2rem; color: blue;', 'disabled' => '']);
            echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$n.']['.$i.']['.$p.']qty', ['class' => 'form-control', 'style' => 'width:60px; font-size:1.2rem; text-align: right; color: green;', 'disabled' => '']);
            echo '<center><a class="btn btn-xs red btn-remove" onclick="remove(this, '.$n.', '.$i.', '.$p.');"><i class="fa fa-minus"></i></a></center>';
            echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$n.']['.$i.']['.$p.']qty2', ['class' => 'form-control', 'style' => 'width:60px; font-size:1.2rem; text-align: right; color: red; font-weight: bold;', 'disabled' => '']);
            echo '<center><a class="btn btn-xs red btn-remove btn-hapus-add-size" onclick="removeSizes(this, '.$n.', '.$i.');"><i class="fa fa-times"></i></a></center>';
            ?>
        </div>
    </td>
</tr>
