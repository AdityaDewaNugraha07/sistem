<?php 

if($jenis_log=="LA") { 
?>
    <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
        <thead>
            <?php $ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log'); ?>
            <tr>
                <th style="width: 30px;" rowspan="2" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                <th style="width: 120px;" rowspan="2"><?= Yii::t('app', 'Group Kayu'); ?></th>
                <th style="width: 120px;" rowspan="2"><?= Yii::t('app', 'Nama Kayu'); ?></th>
                <th colspan="<?= count($ukuranganrange)+1 ?>"><?= Yii::t('app', 'Qty M<sup>3</sup> By Diameter Range'); ?></th>
                <th style="" rowspan="2"><?= Yii::t('app', 'Keterangan'); ?></th>
                <th rowspan="2" style="width: 30px;"></th>
            </tr>
            <tr>
                <?php foreach($ukuranganrange as $i => $range){ ?>
                <th style="width: 110px;"><?= $range ?> cm</th>
                <?php } ?>
                <th style="width: 120px;">Total M<sup>3</sup></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <td colspan="<?= (count($ukuranganrange)+3) ?>" style="text-align: right;">&nbsp; </td>
                <td><?= yii\helpers\Html::textInput("TPmr[total][total_m3]",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
            </tr>
        </tfoot>
    </table>
    <?php
    if (empty($success) && empty($pmr_id) || ($success < 1 || $pmr_id < 1)) {
    ?>
    <a class="btn btn-xs blue" id="btn-add-item" onclick="addItem()"><i class="fa fa-plus"></i> Add Item</a>
    <?php
    }
    ?>
<?php 
} else { 
?>
    <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
        <thead>
            <?php $ukuranganrange = \app\models\MDefaultValue::getOptionList('log-sengon-panjang'); ?>
            <tr>
                <th style="width: 30px;" rowspan="2" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                <th style="width: 240px;" rowspan="2"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                <th colspan="<?= count($ukuranganrange) ?>"><?= Yii::t('app', 'Qty M<sup>3</sup> By Panjang Log'); ?></th>
                <th style="width: 120px;" rowspan="2"><?= Yii::t('app', 'Total  M<sup>3</sup>'); ?></th>
                <th style="" rowspan="2"><?= Yii::t('app', 'Keterangan'); ?></th>
                <th rowspan="2" style="width: 30px;"></th>
            </tr>
            <tr>
                <?php foreach($ukuranganrange as $i => $range){ ?>
                <th style="width: 110px;"><?= $range ?> cm</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <td colspan="<?= (count($ukuranganrange)+3) ?>" style="text-align: right;">&nbsp; </td>
                <td><?= yii\helpers\Html::textInput("TPmr[total][total_m3]",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
            </tr>
        </tfoot>
    </table>
<?php 
} 
?>