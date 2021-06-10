<?=$this->extend('layout/admin');?>

<?=$this->Section('content');?>

<div class="content">
            <div class="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="basic-datatables" class="table table-striped">
                                        <thead>
                                            <tr>
                                            <th>No</th>
                                            <th>Rentangan</th>
                                            <th>f0</th>
                                            <th>Batas Bawah Kelas</th>
                                            <th>Batas Atas Kelas</th>
                                            <th>Batas Bawah Z</th>
                                            <th>Batas Atas Z</th>
                                            <th>Z Tabel Bawah</th>
                                            <th>Z Tabel Atas</th>
                                            <th>L/Proporsi</th>
                                            <th>L*N (fe)</th>
                                            <th>(f0-fe)^2/fe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $j = 1;?>
                                            <?php for ($i = 0; $i < $kelas; $i++): ?>
                                            <tr>
                                                <th> <?=$j++;?> </th>
                                                <td> <?php echo $range[$i]; ?></td>
                                                <td> <?php echo $frekuensi[$i]; ?></td>
                                                <td> <?php echo $batasBawahBaru[$i]; ?></td>
                                                <td> <?php echo $batasAtasBaru[$i]; ?></td>
                                                <td> <?php echo $zBawah[$i]; ?></td>
                                                <td> <?php echo $zAtas[$i]; ?></td>
                                                <td> <?php echo $zTabelBawahFix[$i]; ?></td>
                                                <td> <?php echo $zTabelAtasFix[$i]; ?></td>
                                                <td> <?php echo $lprop[$i]; ?></td>
                                                <td> <?php echo $fe[$i]; ?></td>
                                                <td> <?php echo $kai[$i]; ?></td>
                                            </tr>
                                            <?php endfor?>
                                            <tr>
                                            <th> Total: </th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th><?php echo $totalchi; ?></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>

<?=$this->endSection();?>