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
            ]
        ])) {
            $validation = \Config\Services::validation();
            return redirect()->to('/buku/create')->withInput()->with('validation', $validation);
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->bukuModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul')
        ]);

        session()->setFlashData('pesan', 'Data berhasil di tambahkan!');
        return redirect()->to('/buku');
    }

    public function delete($id)
    {
        $this->bukuModel->delete($id);
        session()->setFlashData('pesan', 'Data berhasil dihapus.');
        return redirect()->to('/buku');
    }
}
