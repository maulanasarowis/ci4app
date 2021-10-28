<?php

namespace App\Controllers;

use App\Models\BukuModel;

class Buku extends BaseController
{
    protected $bukuModel;
    public function __construct()
    {
        $this->bukuModel = new BukuModel();
    }
    
    public function index()
    {
        // $buku = $this->bukuModel->findAll();
        $data = [
            'title' => 'Daftar Buku',
            'buku' => $this->bukuModel->getBuku()
        ];

        return view('buku/index', $data);
    }

    public function detail($slug)
    {
        $data = [
            'title' => 'Detail Buku',
            'buku' => $this->bukuModel->getBuku($slug)
        ];

        // jika buku tidak ada ditabel
        if(empty($data['buku'])){
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul buku ' . $slug . ' tidak ditemukan.');
        }

        return view('buku/detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Form Tambah Data Buku',
            'validation' => \Config\Services::validation()
        ];

        return view('buku/create', $data);
    }

    public function save() 
    {
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[buku.judul]',
                'errors' => [
                    'required' => '{field} buku harus diisi',
                    'is_unique' => '{field} buku sudah terdaftar'
                ]
                ],
                'sampul' => [
                    'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                    'errors' => [
                        // 'uploaded' => 'gambar harus dipilih',
                        'max_size' => 'ukuran gambar terlalu besar',
                        'is_image' => 'yang anda pilih bukan gambar',
                        'mime_in' => 'yang anda pilih bukan gambar',
                    ]
                ]
        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('/buku/create')->withInput()->with('validation', $validation);
            return redirect()->to('/buku/create')->withInput();
        }
        $fileSampul = $this->request->getFile('sampul');
        // apakah tidak ada file yang diupload
        if ($fileSampul->getError() == 4) {
            $namaSampul = 'default.png';
        } else {
            // $namaSampul = $fileSampul->getName();
            // generate nama sampul(gambar) random
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan file folder img
            $fileSampul->move('img', $namaSampul);
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->bukuModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' =>$namaSampul
        ]);

        session()->setFlashData('pesan', 'Data berhasil di tambahkan!');
        return redirect()->to('/buku');
    }

    public function delete($id)
    {
        // cari gambar berdasarkan id
        $buku = $this->bukuModel->find($id);
        // cek jika file gambarnya default.png
        if ($buku['sampul'] != 'default.png') {
            // hapus gambar
            unlink('img/' . $buku['sampul']);
        }
        $this->bukuModel->delete($id);
        session()->setFlashData('pesan', 'Data berhasil dihapus.');
        return redirect()->to('/buku');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Ubah Data',
            'validation' => \Config\Services::validation(),
            'buku' => $this->bukuModel->getBuku($slug)
        ];

        return view('/buku/edit', $data);
    }

    public function update($id)
    {
        // pengecekan judul
        $bukuLama = $this->bukuModel->getBuku($this->request->getVar('slug'));
        if ($bukuLama['judul'] == $this->request->getVar('judul')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[buku.judul]';
        }
        if (!$this->validate([
            'judul' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} buku harus diisi',
                    'is_unique' => '{field} buku sudah terdaftar'
                ]
            ]
        ])) {
            $validation = \Config\Services::validation();
            return redirect()->to('/buku/edit/'. $this->request->getVar('slug'))->withInput()->with('validation', $validation);
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->bukuModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul')
        ]);

        session()->setFlashData('pesan', 'Data berhasil diubah!');
        return redirect()->to('/buku');
    }
}
