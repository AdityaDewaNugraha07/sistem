<table class="table table-bordered" id="table-koreksi">
    <thead>
    <tr>
        <?php

        use app\models\TPengajuanManipulasi;
        use yii\helpers\Json;

        /** @var TPengajuanManipulasi $model */

        $modDetailAjuan = Json::decode($model->datadetail1);

        if (!empty($model->pengajuan_manipulasi_id)):
            if ($model->tanggal > '2021-05-03') : ?>
                <th>Nama Sopir Lama</th>
                <th>Nopol Lama</th>
                <th>Nama Sopir Baru</th>
                <th>Nopol Baru</th>
            <?php else: ?>
                <th>Nopol Lama</th>
                <th>Nopol Baru</th>
            <?php endif; else: ?>
            <th>Nama Sopir Lama</th>
            <th>Nopol Lama</th>
            <th>Nama Sopir Baru</th>
            <th>Nopol Baru</th>
        <?php endif ?>
    </tr>
    </thead>
    <tbody>
    <tr>
        <?php if ($model->tanggal > '2021-05-03') : ?>
        <td style='text-align:center;font-size:1.2rem;'><?= $modDetailAjuan['supir_old'] ?></td>
        <td style='text-align:center;font-size:1.2rem;'><?= $modDetailAjuan['old'] ?>
        <td style='text-align:center;font-size:1.2rem;'><?= $modDetailAjuan['supir_new'] ?>
            <?php else: ?>
        <td style='text-align:center;font-size:1.2rem;'><?= $modDetailAjuan['old'] ?>
            <?php endif ?>
        <td style='text-align:center;font-size:1.2rem;'><?= $modDetailAjuan['new'] ?>
    </tr>
    </tbody>
</table>