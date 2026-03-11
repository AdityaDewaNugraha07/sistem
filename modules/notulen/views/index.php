<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'PIC NOTULEN MEETING';
$baseUrl = Yii::$app->request->baseUrl;

// Mengambil data dari provider
$models = $dataProvider->getModels();
?>

<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div id='xxx' class='alert alert-danger'>
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="table-responsive">
                <div class="panel-body">
                    <div id="docform" class="text-right" style="margin-bottom:15px;">
                        <?= Html::a('+Departement/Divisi', ['create'], ['class' => 'btn btn-primary btn-xs']) ?>
                    </div>
                    <hr>
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th width="30"><small style="font-size:12px">No</small></th>
                                <th><small style="font-size:12px">Divisi/Bagian/Departement</small></th>
                                <th><small style="font-size:12px">Nama PIC</small></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($models as $model): ?>
                                <tr>
                                    <td><small style='font-size:12px'><?= $no++ ?></small></td>
                                    
                                    <td style='font-size:12px'>
                                        <?= Html::encode($model->departement ? $model->departement->departement_nama : '(Tidak Ada Departemen)') ?>
                                        
                                        <?= Html::a("<button type='button' class='btn btn-danger btn-xs'><small style='font-size:7px'>x</small></button>", 
                                            ['delete', 'id' => $model->pic_notulen_id], [
                                            'title' => 'Klik untuk lakukan Hapus',
                                            'data' => [
                                                'confirm' => "Apakah anda yakin akan Menghapus data " . ($model->departement ? $model->departement->departement_nama : '') . "?",
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </td>
                                    
                                    <td><small style='font-size:12px'>
                                        <?php foreach ($model->picNotulenPegawais as $picPegawai): ?>
                                            <div style="margin-bottom: 5px;">
                                                <?= Html::encode($picPegawai->pegawai ? $picPegawai->pegawai->pegawai_nama : '(Data Pegawai Tidak Ditemukan)') ?>
                                                
                                                <?= Html::a("<button type='button' class='btn btn-danger btn-xs'><small style='font-size:6px'>x</small></button>", 
                                                    ['m-pic-notulen-pegawai/delete', 'id' => $picPegawai->pic_notulen_pegawai_id], [
                                                    'title' => 'Klik untuk lakukan hapus pegawai',
                                                    'data' => [
                                                        'confirm' => "Apakah anda yakin akan Menghapus data PIC ini?",
                                                        'method' => 'post',
                                                    ],
                                                ]) ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </small></td>
                                    
                                    <td class='hidden-print text-center'>
                                        <?= Html::a("<small><p class='fa fa-plus-square'></p></small>", 
                                            ['m-pic-notulen-pegawai/create', 'pic_notulen_id' => $model->pic_notulen_id], [
                                            'title' => 'Klik untuk menambahkan Petugas'
                                        ]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>