<?php 
if ($jenis_log=="LA") { 
    $ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log');
    if((!empty($model->pmr_id)) && ($edit=="false")){
        $disabled = true;
        $kayu_id = $model->kayu_id;
        $group_kayu = Yii::$app->db->createCommand("select group_kayu from m_kayu where kayu_id = ".$kayu_id." ")->queryScalar();
        $kayu_nama = Yii::$app->db->createCommand("select kayu_nama from m_kayu where kayu_id = ".$kayu_id." ")->queryScalar();
        $keterangan = $model->keterangan;
    } else if((!empty($model->pmr_id)) && ($edit == "true")){
        $disabled = false;
        $kayu_id = $model->kayu_id;
        $group_kayu = Yii::$app->db->createCommand("select group_kayu from m_kayu where kayu_id = ".$kayu_id." ")->queryScalar();
        $kayu_nama = Yii::$app->db->createCommand("select kayu_nama from m_kayu where kayu_id = ".$kayu_id." ")->queryScalar();
        $keterangan = $model->keterangan;
    }else{
        $disabled = false;
        $kayu_id = "";
        $keterangan = "";
    }
    (empty($x)) ? $x = "" : $x = $x;
    ?>
    <tr>
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
            <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pmr_detail_id',[]); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pmr_id',[]); ?>
            <span class="no_urut"><?php echo $x;?></span>
        </td>

        <?php
        // kolom group_kayu di halaman create 
        if (empty($model->pmr_id)) {
        ?>
            <td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
                <?php
                echo yii\helpers\Html::activeDropDownList($model, '[ii]pmr_id',app\models\MKayu::getOptionListGroupKayu(),
                    ['class'=>'form-control select2',
                        'prompt'=>'',
                        'style'=>'width:100%; padding: 1px; height:25px;',
                        'disabled'=>$disabled,
                        'onchange'=>'sikat(this);',
                    ]);
                ?>
            </td>

            <?php // kolom nama_kayu ?>
            <td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
                <?php 
                echo yii\helpers\Html::activeDropDownList($model, '[ii]kayu_id',app\models\MKayu::getOptionListNamaKayu(),
                    ['class'=>'form-control select2',
                        'prompt'=>'',
                        'style'=>'width:100%; padding: 1px; height:25px;',
                        'val'=>$kayu_id,
                        "disabled"=>$disabled
                    ]); 
                ?>
            </td>

        <?php
        // kolom group_kayu di halaman after save dan edit
        } else {
            (empty($x)) ? $x = "" : $x = $x-1;
            ($_POST['edit'] == 'false') ? $edit = '' : $edit = 1;

            // jika after save
            if ($edit == 0) {
            ?>
                <td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
                    <?php
                    echo yii\helpers\Html::activeDropDownList($model, '[ii]pmr_id',app\models\MKayu::getOptionListGroupKayu(),
                        ['class'=>'form-control select2',
                            'prompt'=>$group_kayu,
                            'style'=>'width:100%; padding: 1px; height:25px;',
                            'disabled'=>$disabled,
                        ]);
                    ?>
                </td>

            <?php
            // jika edit
            } else if ($edit == 1) {
            ?>
                <td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
                    <?php
                    echo yii\helpers\Html::activeDropDownList($model, '['.$x.']pmr_id',app\models\MKayu::getOptionListGroupKayu(),
                        ['class'=>'form-control select2',
                            'prompt'=>$group_kayu,
                            'style'=>'width:100%; padding: 1px; height:25px;',
                            'disabled'=>$disabled,
                            'onchange'=>'xikat(this);',
                        ]);
                    ?>
                </td>

            <?php
            // jika bukan after save atau edit
            } else {
                ?>
                <td></td>
                <?php
                }
            ?>

            <?php // kolom nama_kayu ?>
            <?php
            if ($edit == 0) {
            ?>
                <td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
                    <?php 
                    echo yii\helpers\Html::activeDropDownList($model, '['.$i.']kayu_id',app\models\MKayu::getOptionListNamaKayu(),
                        ['class'=>'form-control select2',
                            'prompt'=>'',
                            'style'=>'width:100%; padding: 1px; height:25px;',
                            'val'=>$kayu_id,
                            "disabled"=>$disabled
                        ]); 
                    ?>
                </td>
            <?php
            } else {
            ?>
                <td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
                    <?php
                    $sql_list = "select kayu_id, kayu_nama from m_kayu where group_kayu = '".$group_kayu."' order by kayu_nama asc ";
                    $query_list = Yii::$app->db->createCommand($sql_list)->queryAll();
                    ?>
                    <select id="TPmrDetail_<?php echo $i;?>_kayu_id" name="TPmrDetail[<?php echo $i;?>][kayu_id]" class="form-control select2" style="width:100%; padding: 1px; height:25px;">
                        <?php
                        foreach ($query_list as $kolom) {
                            $kayu_idx = $kolom['kayu_id'];
                            $kayu_namax = $kolom['kayu_nama'];
                            $kayu_id == $kayu_idx ? $selected = "selected" : $selected = "";
                        ?>
                        <option value="<?php echo $kayu_id;?>" <?php echo $selected;?>><?php echo $kayu_namax;?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            <?php
            }
            ?>

        <?php
        }
        ?>


        <!-- hide -->
            <?php 
            foreach($ukuranganrange as $i => $range){
                if((!empty($model->pmr_id))){
                    $sql = "SELECT SUM(qty_m3) AS qty_m3 FROM t_pmr_detail 
                            WHERE pmr_id = {$model->pmr_id} AND kayu_id = {$model->kayu_id} AND diameter_range = '{$range}'";
                    $modQty = Yii::$app->db->createCommand($sql)->queryOne();
            ?>
                <td class="td-kecil" style="vertical-align: middle; text-align: center;">
                    <?= \yii\bootstrap\Html::activeTextInput($model, '['.$x.']['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=>\app\components\DeltaFormatter::formatNumberForUserFloat($modQty['qty_m3']),'onblur'=>"total();","disabled"=>$disabled]); ?>
                </td>
            <?php }else{ ?>
                <td class="td-kecil" style="vertical-align: middle; text-align: center;">
                    <?= \yii\bootstrap\Html::activeTextInput($model, '['.$x.']['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=>0,'onblur'=>"total();","disabled"=>$disabled]); ?>
                </td>
            <?php } ?>
            <?php } ?>
            <td class="td-kecil" style="vertical-align: middle; text-align: center;">
                <?= \yii\bootstrap\Html::activeTextInput($model, '['.$x.'][total]qty_m3',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','disabled'=>true]); ?>
            </td>
            <td class="td-kecil" style="vertical-align: middle; text-align: center;">
                <?= \yii\bootstrap\Html::activeTextInput($model, '['.$x.']keterangan',['class'=>'form-control','style'=>'width:100%; padding: 1px; height:25px; font-size:1.1rem;',"disabled"=>$disabled]); ?>
            </td>
            <td class="td-kecil" style="vertical-align: middle; text-align: center;">
                <a class="btn btn-xs red" onclick="cancelItem(this,'total()');"><i class="fa fa-remove"></i></a>
            </td>
    </tr>
<?php  
} else if ($jenis_log=="LS") { 
    $model->kayu_id = 29; // RC - Sengon
    $ukuranganrange = \app\models\MDefaultValue::getOptionList('log-sengon-panjang');
    if((!empty($model->pmr_id)) && ($edit=="false")){
        $disabled = true;
    }else{
        $disabled = false;
    }
    ?>
    <tr style="">
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
            <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pmr_detail_id',[]); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pmr_id',[]); ?>
            <span class="no_urut"></span>
        </td>
        <td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
            <?php echo yii\helpers\Html::activeDropDownList($model, '[ii]kayu_id',app\models\MKayu::getOptionListPlusGroup(),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%; padding: 1px; height:25px;',"disabled"=>true]); ?>
        </td>
        <?php 
        foreach($ukuranganrange as $i => $range){
            if((!empty($model->pmr_id))){
                $sql = "SELECT SUM(qty_m3) AS qty_m3 FROM t_pmr_detail 
                        WHERE pmr_id = {$model->pmr_id} AND kayu_id = {$model->kayu_id} AND panjang = '{$range}'";
                $modQty = Yii::$app->db->createCommand($sql)->queryOne();
        ?>
            <td class="td-kecil" style="vertical-align: middle; text-align: center;">
                <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=>$modQty['qty_m3'],'onblur'=>"total();","disabled"=>$disabled]); ?>
            </td>
        <?php }else{ ?>
            <td class="td-kecil" style="vertical-align: middle; text-align: center;">
                <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=>0,'onblur'=>"total();","disabled"=>$disabled]); ?>
            </td>
        <?php } ?>
        <?php } ?>
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii][total]qty_m3',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','disabled'=>true]); ?>
        </td>
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]keterangan',['class'=>'form-control','style'=>'width:100%; padding: 1px; height:25px; font-size:1.1rem;',"disabled"=>$disabled]); ?>
        </td>
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
            <a class="btn btn-xs red" onclick="cancelItem(this,'total()');"><i class="fa fa-remove"></i></a>
        </td>
    </tr>
<?php 
} else if ($jenis_log=="LJ") {    
    $model->kayu_id = 24; // RC - jabon  
        $ukuranganrange = \app\models\MDefaultValue::getOptionList('log-sengon-panjang');
    if((!empty($model->pmr_id)) && ($edit=="false")){
        $disabled = true;
    }else{
        $disabled = false;
    }
    ?>
    <tr style="">
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
            <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pmr_detail_id',[]); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pmr_id',[]); ?>
            <span class="no_urut"></span>
        </td>
        <td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
            <?php echo yii\helpers\Html::activeDropDownList($model, '[ii]kayu_id',app\models\MKayu::getOptionListPlusGroup(),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%; padding: 1px; height:25px;',"disabled"=>true]); ?>
        </td>
        <?php 
        foreach($ukuranganrange as $i => $range){
            if((!empty($model->pmr_id))){
                $sql = "SELECT SUM(qty_m3) AS qty_m3 FROM t_pmr_detail 
                        WHERE pmr_id = {$model->pmr_id} AND kayu_id = {$model->kayu_id} AND panjang = '{$range}'";
                $modQty = Yii::$app->db->createCommand($sql)->queryOne();
        ?>
            <td class="td-kecil" style="vertical-align: middle; text-align: center;">
                <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=>$modQty['qty_m3'],'onblur'=>"total();","disabled"=>$disabled]); ?>
            </td>
        <?php }else{ ?>
            <td class="td-kecil" style="vertical-align: middle; text-align: center;">
                <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=>0,'onblur'=>"total();","disabled"=>$disabled]); ?>
            </td>
        <?php } ?>
        <?php } ?>
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii][total]qty_m3',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','disabled'=>true]); ?>
        </td>
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]keterangan',['class'=>'form-control','style'=>'width:100%; padding: 1px; height:25px; font-size:1.1rem;',"disabled"=>$disabled]); ?>
        </td>
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
            <a class="btn btn-xs red" onclick="cancelItem(this,'total()');"><i class="fa fa-remove"></i></a>
        </td>
    </tr>
<?php 
}
?>

<?php $this->registerJs(" 
    sikat();
", yii\web\View::POS_READY); ?>

<script>
    function sikat(el){
        var group_kayu = $(el).val();
        var id = $(el).attr('id');
        var pecah_id = id.split('_');
        var baris = pecah_id[1];
        var select_baru = '#TPmrDetail_'+baris+'_kayu_id';
        console.log('id='+ +id+ '\npecah='+pecah_id+ '\nbaris='+baris+ '\nselect_baru='+select_baru);
        $.ajax({
            type: "POST",
            data: {group_kayu:group_kayu, baris:baris},
            url: '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pmr/getKayu']); ?>',
            success: function (data) {
                if(data){
                    $("#TPmrDetail_"+baris+"_kayu_id").html(data);
			    } else {
                    alert(0);
                }                
            }
        });
    }

    function xikat(el){
        var group_kayu = $(el).val();
        var id = $(el).attr('id');
        var pecah_id = id.split('-');
        var baris = pecah_id[1];
        var select_baru = '#tpmrdetail_'+baris+'_kayu_id';
        console.log('id='+ +id+ '\npecah='+pecah_id+ '\nbaris='+baris+ '\nselect_baru='+select_baru);
        $.ajax({
            type: "POST",
            data: {group_kayu:group_kayu, baris:baris},
            url: '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pmr/getKayu']); ?>',
            success: function (data) {
                if(data){
                    $("#tpmrdetail-"+baris+"-kayu_id").html(data);
			    } else {
                    alert(0);
                }                
            }
        });
    }
</script>