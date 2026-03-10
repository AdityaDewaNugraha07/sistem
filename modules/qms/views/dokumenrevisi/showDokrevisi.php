<div class="col-md-6">
    <div class="form-group" style="margin-bottom: 5px;">
        <div class="col-md-4 control-label"><label>Nama Dokumen Revisi</label></div>
        <div class="col-md-7">
            <?php
            $modRevisi->nama_dokumen = (!$dok_id)?$model->nama_dokumen:$modRevisi->nama_dokumen;
            echo \yii\bootstrap\Html::activeTextInput($modRevisi, 'nama_dokumen', ['class'=>'form-control','style'=>'width:100%']);
            ?>
        </div>
    </div>
    <div class="form-group" style="margin-bottom: 5px;">
        <div class="col-md-4 control-label"><label>Revisi Ke</label></div>
        <div class="col-md-7">
            <?= \yii\bootstrap\Html::activeTextInput($modRevisi, 'revisi_ke', ['class'=>'form-control','style'=>'width:100%', 'disabled'=>true]) ?>
        </div>
    </div>
    <div class="form-group field-tdokumenrevisi-tanggal_berlaku">
        
        <label class="col-md-4 control-label" for="tdokumenrevisi-tanggal_berlaku">Tanggal Berlaku</label>
        <div class="col-md-4">
            <div class="input-group date date-picker">
                <?= \yii\bootstrap\Html::activeTextInput($modRevisi, 'tanggal_berlaku', ['class'=>'form-control','style'=>'width:100%', 'readonly'=>'readonly']) ?>
                <span class="input-group-btn">
                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button>
                </span>
            </div>
        </div> 
    </div>
</div>
</div>
<div class="col-md-6">
    <div class="form-group" style="margin-bottom: 5px;">
        <div class="col-md-4 control-label"><label>Catatan Revisi</label></div>
        <div class="col-md-7">
            <?= \yii\bootstrap\Html::activeTextarea($modRevisi, 'catatan_revisi', ['class'=>'form-control','style'=>'width:100%']) ?>
        </div>
    </div>
</div>