<?php
$this->registerCssFile($this->theme->baseUrl."/global/plugins/jquery-nestable/jquery.nestable.css",['depends'=>[yii\web\YiiAsset::className()]]);
$this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-nestable/jquery.nestable.js",['depends'=>[yii\web\YiiAsset::className()]]);
?>
<div class="col-md-12" id="config-content">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <h4><?= Yii::t('app', 'Accessible Menu of '); ?> <strong><?= $modUserGroup->name; ?></strong></h4>
            </div>
            <div class="actions">
                <button onclick="create();" class="btn btn-circle hijau btn-outline ciptana-spin-btn">
                    <i class="fa fa-plus"></i> Add
                </button>
                <?php if((count($modUserAccess)>0)&&(count($modUserGroup)>0)){ ?>
                    
                    <button onclick="openModal('<?= \yii\helpers\Url::toRoute(['/sysadmin/useraccess/deleteAll','id'=> $modUserGroup->user_group_id])?>','modal-delete-record',null,'setUserGroup(<?= $modUserGroup->user_group_id?>)')" class="btn btn-circle red btn-outline ciptana-spin-btn">
                        <i class="fa fa-remove"></i> Remove All
                    </button>
                <?php } ?>
            </div>
        </div>
        <div class="portlet-body">
            <?php
            if((count($modUserGroup)==0)||(count($modUserAccess)==0)){
                echo("<h5>".Yii::t('app', 'Data Tidak Ditemukan')."</h5>");
            }else{
                $select = 'm_menu_group.menu_group_id, m_menu_group.name, m_menu_group.sequence, m_menu_group.icon';
                $query = "
                    SELECT $select
                    FROM m_user_access
                    JOIN m_menu ON m_menu.menu_id=m_user_access.menu_id
                    JOIN m_menu_group ON m_menu_group.menu_group_id=m_menu.menu_group_id
                    WHERE m_user_access.user_group_id = ".$modUserGroup->user_group_id." 
                        AND m_menu_group.active = TRUE
                    GROUP BY $select
                    ORDER BY m_menu_group.sequence ASC
                ";
                $menugroups = Yii::$app->db->createCommand($query)->queryAll();
            ?>
            <div class="dd">
                <ol class="dd-list">
                    <?php foreach($menugroups as $i => $menugroup){ ?>
                    <li class="dd-item dd3-item" data-id="mg-<?= $menugroup['menu_group_id'] ?>" style="margin-top: 20px;">
                        <div class="dd-handle dd3-handle"> </div>
                        <div class="dd3-content"> <?= $menugroup['name']; ?> </div>
                        <?php
                        $select = 'm_menu.menu_id,m_menu.name,m_menu.url';
                        $query = "
                            SELECT $select
                            FROM m_user_access
                            JOIN m_menu ON m_menu.menu_id=m_user_access.menu_id
                            WHERE m_user_access.user_group_id = ".$modUserGroup->user_group_id." 
                                AND m_menu.menu_group_id = ".$menugroup['menu_group_id']."
                                AND m_menu.active = TRUE
                            GROUP BY $select
                            ORDER BY m_menu.sequence ASC
                        ";
                        $menus = Yii::$app->db->createCommand($query)->queryAll(); ?>
                        <?php if(count($menus)>0){ ?>
                            <ol class="dd-list">
                                <?php foreach($menus as $ii => $menu){ ?>
                                <li class="dd-item dd3-item" data-id="m-<?= $menu['menu_id'] ?>">
                                    <div class="dd-handle dd3-handle"> </div>
                                    <div class="dd3-content">
                                        <?= $menu['name'] ?> 
                                        <?php echo \yii\helpers\Html::button( Yii::t('app', '<i class="fa fa-close"></i>'),[
                                            'class'=>'btn btn-xs red btn-outline pull-right ciptana-spin-btn',
                                            'data-style'=>'zoom-in',
                                            'style'=>'margin-top:-2px',
                                            'onclick'=>"openModal('". \yii\helpers\Url::toRoute(['/sysadmin/useraccess/delete','id'=> \app\models\MUserAccess::getFromUserGroupAndMenu($modUserGroup->user_group_id, $menu['menu_id'])])."','modal-delete-record',null,'')"
                                        ])?>
                                    </div>
                                </li>
                                <?php } ?>
                            </ol>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ol>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php
$this->registerJs("
    $('.dd').nestable({ /* config options */ });
    $('.dd').nestable('serialize');
    spinbtn();
", yii\web\View::POS_READY); ?>
<script>
function create(){
	openModal('<?= \yii\helpers\Url::toRoute(['/sysadmin/useraccess/create','id'=>$modUserGroup->user_group_id]) ?>','modal-useraccess-create');
}
</script>