<table class="table table-bordered" id="table-koreksi">
    <thead>
    <tr>
        <th>Alamat Bongkar Lama</th>
        <th>Alamat Bongkar Baru</th>
    </tr>
    </thead>
    <tbody>
    <?php

    use app\models\TPengajuanManipulasi;
    use yii\helpers\Json;

    /** @var TPengajuanManipulasi $model */
    $modDetailAjuan = Json::decode($model->datadetail1);
    ?>
    <tr>
        <td style="text-align: center; font-size: 1.2rem"><?= $modDetailAjuan['old'] ?></td>
        <td style="text-align: center; font-size: 1.2rem"><?= $modDetailAjuan['new'] ?></td>
    </tr>
    </tbody>
</table>