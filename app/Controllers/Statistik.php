<?php

namespace App\Controllers;

use App\Models\ZtabelModel;
use CodeIgniter\HTTP\Request;

class Statistik extends BaseController
{

    protected $orangModel;
    public function __construct()
    {
        helper('form');
        $this->ztabelModel = new \App\Models\ZtabelModel();
        $this->orangModel = new \App\Models\UserModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        {
            $builder = $db->table('user');

            $builder->selectMax('nilai');
            $nMax = $builder->get();
            foreach ($nMax->getResult() as $a) {
                $xMax = $a->nilai;
            }
            $zMax = intval($xMax);

            $builder->selectMin('nilai');
            $nMin = $builder->get();
            foreach ($nMin->getResult() as $b) {
                $xMin = $b->nilai;
                $xMin;
            }
            $zMin = intval($xMin);

            $builder->selectAvg('nilai');
            $nAvg = $builder->get();
            foreach ($nAvg->getResult() as $c) {
                $xAvg = $c->nilai;
            }
            $zAvg = intval($xAvg);

            $builder->selectCount('nilai');
            $nTotal = $builder->get();
            foreach ($nTotal->getResult() as $d) {
                $xTotal = $d->nilai;
            }
            $zTotal = intval($xTotal);

            $builder->selectSum('nilai');
            $nSum = $builder->get();
            foreach ($nSum->getResult() as $e) {
                $xSum = $e->nilai;
            }

            $zSum = intval($xSum);

            $nf = $db->query('SELECT nilai, COUNT(*) as count FROM USER GROUP BY nilai');

            $n = $zTotal;
            $rentangan = $zMax - $zMin;
            $kelas = ceil(1 + 3.3 * log10($n));
            $interval = ceil($rentangan / $kelas);
            $batasBawah = $zMin;
            $batasAtas = 0;

            for ($i = 0; $i < $kelas; $i++) {
                $batasAtas = $batasBawah + $interval - 1;
                $frekuensi[$i] = $db->query('select count(nilai) as frek from user where nilai >= ' . $batasBawah . ' and nilai <= ' . $batasAtas . '');
                foreach ($frekuensi[$i]->getResult() as $f) {
                    $xFrek[$i] = $f->frek;

                }
                $frekready[$i] = intval($xFrek[$i]);
                $range[$i] = $batasBawah . " - " . $batasAtas;
                $batasBawah = $batasAtas + 1;
            }
        }

        $data = [
            'nf' => $nf,
            'nSum' => $nSum,
            'nTotal' => $nTotal,
            'nAvg' => $nAvg,
            'nMin' => $nMin,
            'nMax' => $nMax,
            'range' => $range,
            'frekuensi' => $frekready,
            'batasAtas' => $batasAtas,
            'batasBawah' => $batasBawah,
            'kelas' => $kelas,
            'interval' => $interval,
            'rentangan' => $rentangan,
            'title' => 'Statistik Deskriptif | Statistik',
            'users' => $this->orangModel->getUser(),
            'validation' => \Config\Services::validation(),
        ];

        return view('statistik/index', $data);

    }

    public function save()
    {
        // validasi input

        if (!$this->validate([

            'nilai' => [

                'rules' => 'required|integer|is_natural|less_than[101]|decimal',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'integer' => '{field} harus desimal',
                    'less_than' => '{field} maximal 100',
                    'decimal' => '{field} harus desimal',
                    'is_natural' => '{field} minimum 0',
                ],
            ],

        ])) {

            $validation = \Config\Services::validation();

            return redirect()->to('/statistik/index')->withInput()->with('validation', $validation);
        }

        $request = service('request');
        $request->getVar();

        $this->orangModel->save([

            'nilai' => $request->getVar('nilai'),

        ]);

        session()->setFlashdata('pesan', 'Nilai berhasil ditambahkan.');

        return redirect()->to('/statistik');
    }

    public function delete($id)
    {

        $this->orangModel->delete($id);

        session()->setFlashdata('pesan', 'Nilai berhasil dihapus.');

        return redirect()->to('/statistik');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Nilai | Statistik',

            'validation' => \Config\Services::validation(),

            'user' => $this->orangModel->getUser($id),

        ];

        return view('/statistik/edit', $data);
    }

    public function update($id)
    {
        $request = service('request');
        $request->getVar();

        if (!$this->validate([

            'nilai' => [

                'rules' => 'required|integer|is_natural|less_than[101]|decimal',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'integer' => '{field} harus desimal',
                    'less_than' => '{field} maximal 100',
                    'decimal' => '{field} harus desimal',
                    'is_natural' => '{field} minimum 0',
                ],
            ],

        ])) {

            $validation = \Config\Services::validation();

            return redirect()->to('/statistik/edit/' . $request->getVar('id'))->withInput()->with('validation', $validation);
        }

        $this->orangModel->save([
            'id' => $id,
            'nilai' => $request->getVar('nilai'),

        ]);

        session()->setFlashdata('pesan', 'Nilai berhasil diubah.');

        return redirect()->to('/statistik');
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');

        $ext = $file->guessExtension();

        if ($ext == 'xls') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }

        $spreadsheet = $reader->load($file);
        $sheet = $spreadsheet->getActiveSheet()->toArray();

        foreach ($sheet as $x => $excel) {
            if ($x == 0) {
                continue;
            }

            $data = [
                'nilai' => $excel['0'],
            ];

            $this->orangModel->add($data);
            session()->setFlashdata('msg', 'Data Berhasil di Import!!');

            return redirect()->to(base_url('statistik'));
        }

    }

    public function excel()
    {

        $data = [
            'nilai' => $this->orangModel->getUser(),
        ];

        return view('excel/excel', $data);
    }

    public function chisabi()
    {
        $db = \Config\Database::connect();
        {
            $builder = $db->table('user');

            $builder->selectMax('nilai');
            $nMax = $builder->get();
            foreach ($nMax->getResult() as $a) {
                $xMax = $a->nilai;
            }
            $zMax = intval($xMax);

            $builder->selectMin('nilai');
            $nMin = $builder->get();
            foreach ($nMin->getResult() as $b) {
                $xMin = $b->nilai;
                $xMin;
            }
            $zMin = intval($xMin);

            $builder->selectAvg('nilai');
            $nAvg = $builder->get();
            foreach ($nAvg->getResult() as $c) {
                $xAvg = $c->nilai;
            }
            $zAvg = intval($xAvg);

            $builder->selectCount('nilai');
            $nTotal = $builder->get();
            foreach ($nTotal->getResult() as $d) {
                $xTotal = $d->nilai;
            }
            $zTotal = intval($xTotal);

            $builder->selectSum('nilai');
            $nSum = $builder->get();
            foreach ($nSum->getResult() as $e) {
                $xSum = $e->nilai;
            }
            $zSum = intval($xSum);

            $nf = $db->query('SELECT nilai, COUNT(*) as count FROM user GROUP BY nilai');

            $rata2 = number_format($zAvg, 2);

            $n = $zTotal;

            function std_deviation($arrku)
            {
                $no_element = count($arrku);
                $var = 0.0;
                $avg = array_sum($arrku) / $no_element;
                // dd($avg);
                foreach ($arrku as $i) {
                    $var += pow((intval($i['nilai']) - $avg), 2);
                }
                return (float) sqrt($var / $no_element);
            }

            function desimal($nilai)
            {
                if ($nilai < 0) {
                    $des = substr($nilai, 0, 4);
                } else {
                    $des = substr($nilai, 0, 3);
                }
                return $des;
            }

            function label($nilai)
            {
                if ($nilai < 0) {
                    $str1 = substr($nilai, 4, 1);
                } else {
                    $str1 = substr($nilai, 3, 1);
                }

                switch ($str1) {
                    case '0':
                        $sLabel = 'nol';
                        break;
                    case '1':
                        $sLabel = 'satu';
                        break;
                    case '2':
                        $sLabel = 'dua';
                        break;
                    case '3':
                        $sLabel = 'tiga';
                        break;
                    case '4':
                        $sLabel = 'empat';
                        break;
                    case '5':
                        $sLabel = 'lima';
                        break;
                    case '6':
                        $sLabel = 'enam';
                        break;
                    case '7':
                        $sLabel = 'tujuh';
                        break;
                    case '8':
                        $sLabel = 'delapan';
                        break;
                    case '9':
                        $sLabel = 'sembilan';
                        break;
                    default:$sLabel = 'Tidak ada field';
                }

                return $sLabel;
            }

            $test = $this->orangModel->select('nilai')->findAll();
            // $builder->select('nilai');
            // $ncomrade = $builder->get();
            // $xcomrade = $ncomrade->getResult()->nilai;
            // $zcomrade = intval($xcomrade);
            // dd($zcomrade);
            $i = 0;
            foreach ($test as $h) {
                $arraySkor[$i] = $h;
                intval($arraySkor[$i]);
                $i++;
            }

            $SD = number_format(std_deviation($arraySkor), 2);

            //cari rentangan
            $rentangan = $zMax - $zMin;

            //cari kelas
            $kelas = ceil(1 + 3.3 * log10($n));

            //hitung interval
            $interval = ceil($rentangan / $kelas);

            //tentukan batas bawah dan batas atas
            $batasBawah = $zMin;
            $batasAtas = 0;

            $totalchi = 0;
            for ($i = 0; $i <= $kelas; $i++) {
                //menghitung batas bawah
                $batasBawahBaru[$i] = $batasBawah - 0.5;

                $batasAtas = $batasBawah + $interval - 1;

                //menghitung batas atas
                $batasAtasBaru[$i] = $batasAtas + 0.5;

                //menghitung atas dan bawah z
                $zBawah[$i] = number_format(($batasBawahBaru[$i] - $rata2) / $SD, 2);
                $zAtas[$i] = number_format(($batasAtasBaru[$i] - $rata2) / $SD, 2);

                //menghitung z tabel atas dan bawah
                $cariDesimalBawah = desimal($zBawah[$i]);
                $cariDesimalAtas = desimal($zAtas[$i]);

                $labelDesimalBawah = label($zBawah[$i]);
                $labelDesimalAtas = label($zAtas[$i]);

                $zTabelBawah = $this->ztabelModel->where('z', '=', $cariDesimalBawah)->findAll();
                $zTabelAtas = $this->ztabelModel->where('z', '=', $cariDesimalAtas)->findAll();
                $zTabelBawahFix[$i] = $zTabelBawah[0][$labelDesimalBawah];
                $zTabelAtasFix[$i] = $zTabelAtas[0][$labelDesimalAtas];

                //menghitung l/proporsi
                $lprop[$i] = abs($zTabelBawahFix[$i] - $zTabelAtasFix[$i]);

                //menghitung fe(L*N)
                $fe[$i] = $lprop[$i] * $n;

                $frekuensi[$i] = $db->query('select count(nilai) as frek from user where nilai >= ' . $batasBawah . ' and nilai <= ' . $batasAtas . '');
                foreach ($frekuensi[$i]->getResult() as $f) {
                    $xFrek[$i] = $f->frek;
                }
                $frekready[$i] = intval($xFrek[$i]);
                $range[$i] = $batasBawah . " - " . $batasAtas;
                $batasBawah = $batasAtas + 1;

                $kai[$i] = number_format(pow(($frekready[$i] - $fe[$i]), 2) / $fe[$i], 7);

                $totalchi += intval($kai[$i]);
            }
        }
        $data = [
            'nf' => $nf,
            'nSum' => $nSum,
            'nTotal' => $nTotal,
            'nAvg' => $nAvg,
            'nMin' => $nMin,
            'nMax' => $nMax,
            'range' => $range,
            'frekuensi' => $frekready,
            'batasAtas' => $batasAtas,
            'batasBawah' => $batasBawah,
            'kelas' => $kelas,
            'interval' => $interval,
            'rentangan' => $rentangan,
            'batasBawahBaru' => $batasBawahBaru,
            'batasAtasBaru' => $batasAtasBaru,
            'zBawah' => $zBawah,
            'zAtas' => $zAtas,
            'zTabelBawahFix' => $zTabelBawahFix,
            'zTabelAtasFix' => $zTabelAtasFix,
            'lprop' => $lprop,
            'fe' => $fe,
            'kai' => $kai,
            'totalchi' => $totalchi,
            'title' => 'Statistik Deskriptif | Statistik',
            'users' => $this->orangModel->getUser(),
            'validation' => \Config\Services::validation(),

        ];

        return view('Statistik/chiui', $data);

    }
    public function lilifors()
    {
        $db = \Config\Database::connect();{
            $builder = $db->table('user');

            $builder->selectAvg('nilai');
            $nAvg = $builder->get();
            foreach ($nAvg->getResult() as $c) {
                $xAvg = $c->nilai;
            }
            $zAvg = intval($xAvg);

            $builder->selectCount('nilai');
            $nTotal = $builder->get();
            foreach ($nTotal->getResult() as $d) {
                $xTotal = $d->nilai;
            }
            $zTotal = intval($xTotal);
            $n = $zTotal;

            $ng = $db->query('SELECT nilai, COUNT(*) as frekuensi FROM user');
            foreach ($ng->getResult() as $m) {
                $xng = $m->frekuensi;
            }
            $zng = intval($xng);
            // dd($zng);

            $zTotal = intval($xTotal);

            $rata2 = number_format($zAvg, 2);

            function std_deviation($arrku)
            {
                $no_element = count($arrku);
                $var = 0.0;
                $avg = array_sum($arrku) / $no_element;
                // dd($avg);
                foreach ($arrku as $i) {
                    $var += pow((intval($i['nilai']) - $avg), 2);
                }
                return (float) sqrt($var / $no_element);
            }

            function desimal($nilai)
            {
                if ($nilai < 0) {
                    $des = substr($nilai, 0, 4);
                } else {
                    $des = substr($nilai, 0, 3);
                }
                return $des;
            }

            function label($nilai)
            {
                if ($nilai < 0) {
                    $str1 = substr($nilai, 4, 1);
                } else {
                    $str1 = substr($nilai, 3, 1);
                }

                switch ($str1) {
                    case '0':
                        $sLabel = 'nol';
                        break;
                    case '1':
                        $sLabel = 'satu';
                        break;
                    case '2':
                        $sLabel = 'dua';
                        break;
                    case '3':
                        $sLabel = 'tiga';
                        break;
                    case '4':
                        $sLabel = 'empat';
                        break;
                    case '5':
                        $sLabel = 'lima';
                        break;
                    case '6':
                        $sLabel = 'enam';
                        break;
                    case '7':
                        $sLabel = 'tujuh';
                        break;
                    case '8':
                        $sLabel = 'delapan';
                        break;
                    case '9':
                        $sLabel = 'sembilan';
                        break;
                    default:$sLabel = 'Tidak ada field';
                }

                return $sLabel;
            }

            $test = $this->orangModel->select('nilai')->findAll();
            $i = 0;
            foreach ($test as $h) {
                $arraySkor[$i] = $h;
                intval($arraySkor[$i]);
                $i++;
            }

            $SD = number_format(std_deviation($arraySkor), 2);

            $nf = $db->query('SELECT nilai, COUNT(*) as frekuensi FROM user GROUP BY nilai');
            // dd($nf->getResult());
            $i = 0;
            $n1 = 0;
            $fkum = 0;
            $totalLillie = 0;
            foreach ($nf->getResult() as $k) {
                $xFrek[$i] = intval($k->frekuensi);
                $xNilai[$i] = intval($k->nilai);

                $fkum += intval($k->frekuensi);
                $fkumfix[$i] = $fkum;

                //mencari nilai Zi
                $Zi[$i] = number_format(($xNilai[$i] - $rata2) / $SD, 2);
                $cariDesimalZi = desimal($Zi[$i]);
                $labelZi = label($Zi[$i]);
                $zTabel = $this->ztabelModel->where('z', '=', $cariDesimalZi)->findAll();

                //mencari F(zi)dari tabel z
                $fZi[$i] = $zTabel[0][$labelZi];

                // mencari S(Zi)
                $sZi[$i] = $fkumfix[$i] / $n;

                //mencari |F(Zi)-S(Zi)|
                $lilliefors[$i] = abs($fZi[$i] - $sZi[$i]);

                //total
                $totalLillie += $lilliefors[$i];

                $n1++;
                $i++;

            }

            $data = [
                'frekuensi' => $xFrek,
                'nilai' => $xNilai,
                'banyakData' => $n,
                'fkum1' => $fkumfix,
                'Zi' => $Zi,
                'fZi' => $fZi,
                'sZi' => $sZi,
                'lilliefors' => $lilliefors,
                'totalLillie' => $totalLillie,
                'n1' => $n1,
                'n' => $n,
            ];

            return view('Statistik/lilifors', $data);

        }

    }

}
