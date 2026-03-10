<?php app\assets\FileUploadAsset::register($this); ?>
<div class="modal fade" id="modal-addattch" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Attachment Monitoring'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-addattch',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2">
                        <?php 
						$file = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>1]);
						$file1 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>2]);
						$file2 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>3]);
						$file3 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>4]);
						$file4 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>5]);
						$file5 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>6]);
						$file6 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>7]);
						$file7 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>8]);
						$file8 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>9]);
						$file9 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>10]);
						$file10 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>11]);
						$file11 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>12]);
						$file12 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>13]);
						$file13 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>14]);
						$file14 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>15]);
						$file15 = \app\models\TAttachment::findOne(['reff_no'=>$modelMonitoring->kode,'seq'=>16]);
						$model->file = (!empty($file)?$file->file_name:"");
						$model->file1 = (!empty($file1)?$file1->file_name:"");
						$model->file2 = (!empty($file2)?$file2->file_name:"");
						$model->file3 = (!empty($file3)?$file3->file_name:"");
						$model->file4 = (!empty($file4)?$file4->file_name:"");
						$model->file5 = (!empty($file5)?$file5->file_name:"");
						$model->file6 = (!empty($file6)?$file6->file_name:"");
						$model->file7 = (!empty($file7)?$file7->file_name:"");
						$model->file8 = (!empty($file8)?$file8->file_name:"");
						$model->file9 = (!empty($file9)?$file9->file_name:"");
						$model->file10 = (!empty($file10)?$file10->file_name:"");
						$model->file11 = (!empty($file11)?$file11->file_name:"");
						$model->file12 = (!empty($file12)?$file12->file_name:"");
						$model->file13 = (!empty($file13)?$file13->file_name:"");
						$model->file14 = (!empty($file14)?$file14->file_name:"");
						$model->file15 = (!empty($file15)?$file15->file_name:"");
						echo $form->field($model, 'file',[
							'template'=>'
								<div class="col-md-12">
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
											<img src="'.(!empty($model->file)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
										<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
										<div>
											<span class="btn btn-xs blue-hoki btn-outline btn-file">
												<span class="fileinput-new"> Select Image </span>
												<span class="fileinput-exists"> Change </span>
												{input} 
											</span> 
											<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
											{error}
										</div>
									</div>
								</div>'
						])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file1)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file1',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file1)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file1 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file2)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file2',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file2)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file2 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file3)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file3',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file3)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file3 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file4)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file4',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file4)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file4 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file5)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file5',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file5)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file5 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file6)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file6',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file6)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file6 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file7)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file7',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file7)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file7 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file8)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file8',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file8)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file8 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file9)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file9',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file9)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file9 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file10)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file10',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file10)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file10 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file11)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file11',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file11)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file11 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file12)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file12',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file12)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file12 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file13)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file13',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file13)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file13 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file14)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file14',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file14)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file14 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
                    <div class="col-md-2 <?= !empty($model->file15)?"":"hidden" ?>">
                        <?php 
                        echo $form->field($model, 'file15',[
                            'template'=>'
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                            <img src="'.(!empty($model->file15)? yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$model->file15 : Yii::$app->view->theme->baseUrl."/cis/img/no-image.png").'" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                        <div>
                                            <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                <span class="fileinput-new"> Select Image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {input} 
                                            </span> 
                                            <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            {error}
                                        </div>
                                    </div>
                                </div>'
                        ])->fileInput();
                        ?>
                    </div>
					<div class="col-md-2">
						<div class="thumbnail add-more" style="width: 150px; height: 115px; cursor: pointer;" onclick="addMoreAttch();">
							<img src="<?= Yii::$app->view->theme->baseUrl ?>/cis/img/add-more.png" alt="" /> 
						</div>
					</div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-addattch\').modal(\'hide\'); getMonitoring();")'
                    ]);
                        ?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    formconfig();
", yii\web\View::POS_READY); ?>
<script type="text/javascript">

</script>