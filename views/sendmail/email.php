<span class="preheader">Email</span>
<table role="presentation" class="main" style="border: #dadce0 solid 1px;">
    <!-- START MAIN CONTENT AREA -->
    <tr>
        <td><center><img src="http://103.255.240.80/cis/web/themes/metronic/cis/img/logo-login.png" style="width: 160px; margin-top: 10px; margin-bottom: -10px;"></center></td>
    </tr>
    <tr>
        <td class="wrapper">
            <center style="font-size: 1.2rem; color: #7C7C7C"><b>Email</b></center><br>
        </td>
    </tr>
        <td>
            <table>
                <tr>
                    <th>Nama</th>
                    <th>Department</th>
                    <th>Jabatan</th>
                    <th>Username</th>
                </tr>
                <?php
                foreach ($model as $key => $value) {
                echo "<tr><td class='col-md-3'>".$value['pegawai_nama']."</td><td class='col-md-3'>".$value['departement_nama']."</td><td class='col-md-3'>".$value['jabatan_nama']."</td><td class='col-md-3'>".$value['username']."</td></tr>";
                }
                ?>
            </table>
        </td>
    </tr>
</table>
