<?=$this->extend('layout/admin');?>

<?=$this->section('content');?>


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
                                            <th>Yi</th>
                                            <th>Frekuensi</th>
                                            <th>Fkum</th>
                                            <th>Zi</th>
                                            <th>F(Zi)</th>
                                            <th>S(Zi)</th>
                                            <th>|F(Zi)-S(Zi)|</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $j = 1;?>
                        <?php for ($i = 0; $i < $n1; $i++): ?>
                        <tr>
                            <th> <?=$j++;?> </th>
                            <td> <?php echo $nilai[$i]; ?></td>
                            <td> <?php echo $frekuensi[$i]; ?></td>
                            <td> <?php echo $fkum1[$i]; ?></td>
                            <td> <?php echo $Zi[$i]; ?></td>
                            <td> <?php echo $fZi[$i]; ?> </td>
                            <td> <?php echo $sZi[$i]; ?> </td>
                            <td> <?php echo $lilliefors[$i]; ?></td>
                        </tr>
                        <?php endfor;?>


                                            <tr class="text-bold">
                                                <td>Total:</td>
                                                <td> </td>
                                                <td><?php echo $n; ?> </td>
                                                <td><?php echo $n; ?> </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo $totalLillie; ?></td>
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

