<?=$this->extend('layout/admin');?>
<?=$this->section('content');?>

<div class="content">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nilai</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 1?>
                                    <?php foreach ($users as $r): ?>
                                        <tr>
                                            <th class="" scope="row"><?=$i++;?></th>
                                            <td class=""><?=$r['nilai']?></td>
                                            <td class="">
                                                <a href="/statistik/edit/<?=$r['id'];?>" class="btn btn-warning btn-sm py-2 px-2 ">Edit</a>
                                                <form action="/statistik/delete/<?=$r['id'];?>" method="post" class="d-inline">
                                                    <?=csrf_field();?>
                                                    <button type="submit" class="btn btn-danger btn-sm py-2 px-2 "
                                                        onclick="return confirm('Apakah anda yakin?')"> Hapus</button>
                                                    <input type="hidden" name="_method" value="delete">
                                                </form>
                                            </td>
                                        </tr>
                                      <?php endforeach?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                        <?php echo form_open_multipart('statistik/import') ?>
                        <table class="table">
                            <div class="">
                                <input type="file" name="file_excel" class="form-control" accept=".xls,.xlsx">
                            </div>
                            <button type="submit" class="mt-3 mr-3 btn btn-sm btn-primary  inline">Import</button>
                            <?php if (session()->getFlashdata('msg')): ?>
                            <div class="alert mt-4 alert-success" role="alert">
                                <?=session()->getFlashdata('msg');?>
                            </div>
                        </table>
                        <?php endif;?>
                        <?php echo form_close(); ?>
                        <a class=" mt-3 btn btn-info btn-sm" href="<?php echo base_url('statistik/excel') ?>">Export</a>
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                <form action="/statistik/save" method="post">
                                    <?=csrf_field();?>
                                        <div class="">
                                            <input for="nilai" type="number"class="form-control <?=($validation->hasError('nilai')) ? 'is-invalid' : '';?>" id="nilai" name="nilai" placeholder="Masukan nilai" value="<?=old('nilai');?>">
                                            <div class=" invalid-feedback"><?=$validation->getError('nilai');?></div>
                                        </div><?php if (session()->getFlashdata('pesan')): ?>
                                    <div class="alert mt-4 alert-success" role="alert">
                                        <?=session()->getFlashdata('pesan');?>
                                    </div>
                                    <?php endif;?>
                                    <button type="submit" class="btn mt-3 btn-sm btn-primary btn-success">Input</button>
                                </form>
                                </table>

                                <div class="card">
                                    <div class="card-body">

                                        <table class=" table">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th class="" scope=" col">Nilai maximum</th>
                                                    <th class="" scope="col">Nilai minimum</th>
                                                    <th class="" scope="col">Rata-rata</th>
                                                </tr>
                                            </thead>
                                            <tbody class="">
                                                <tr class="">
                                                    <td class="">
                                                        <?php foreach ($nMax->getResult() as $row) {echo $row->nilai;}?>
                                                    </td>
                                                    <td class="">
                                                        <?php foreach ($nMin->getResult() as $row) {echo $row->nilai;}?>
                                                    </td>
                                                    <td>
                                                        <?php foreach ($nAvg->getResult() as $row) {echo $row->nilai;}?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">

                                        <table class=" table">
                                            <thead>
                                                <tr class="bg-light ">
                                                <th class="" scope=" col">Total Nilai</th>
                                                <th class="" scope="col">Total Frekuensi</th>
                                                </tr>
                                            </thead>
                                        <?php foreach ($nf->getResult() as $r): ?>
                                         <tr class="">
                                        <?php endforeach?>
                                        </td>
                                        <td class=""><?php foreach ($nSum->getResult() as $row) {echo $row->nilai;}?></td>
                                        </td>
                                        <td class=""><?php foreach ($nTotal->getResult() as $row) {echo $row->nilai;}?></td>
                                        </td>
                                        </table>
                                    </div>
                                </div>
                        </tbody>
                    </div>
               </div>
            </div>
        </div>
        <div class="row">
    <div class="col-md-6">
         <div class="card">
              <div class="card-body">
                  <table class="display table table-striped table-hover">
                        <thead>
                            <tr class="">
                                 <th class="" scope=" col">Nilai</th>
                                 <th class="" scope="col">Frekuensi</th>
                            </tr>
                        </thead>
                        <tbody class="">
                             <?php foreach ($nf->getResult() as $r): ?>
                            <tr class="">
                                <td class="">
                                    <?php echo $r->nilai ?>
                                </td>
                                <td class="">
                                    <?php echo $r->count ?>
                                </td>
                                  <?php endforeach?>
                                </td>
                            </tr>
                        </tbody>
                     </table>
                    </div>
                <div class="col-md-6">
                </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
                <div class="card-body">
                <table class="table text-center table-striped ">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Rentangan</th>
                            <th>Frekuensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 1;?>
                        <?php for ($i = 0; $i < $kelas; $i++): ?>
                        <tr>
                            <td> <?=$j++;?> </td>
                            <td>
                                <?php echo $range[$i]; ?>
                            </td>

                            <td>
                                <?php echo $frekuensi[$i]; ?>
                            </td>
                        </tr>
                        <?php endfor?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>








<?=$this->endSection();?>