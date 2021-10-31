<?php

namespace App\Controllers;

use App\Models\OrangModel;
use CodeIgniter\HTTP\Request;

class Orang extends BaseController
{
    protected $orangModel;
    public function __construct()
    {
        $this->orangModel = new OrangModel();
    }
    
    public function index()
    {
        $currentPage = $this->request->getVar('page_orang') ? $this->request->getVar('page_orang') : 1;

        $keyword = $this->request->getVar('keyword');
        // d($keyword);
        if($keyword) {
            $orang = $this->orangModel->search($keyword);
        } else {
            $orang = $this->orangModel;
        }
        $data = [
            'title' => 'Daftar Orang',
            'orang' => $orang->paginate(10, 'orang'),
            'pager' =>  $this->orangModel->pager,
            'currentPage' =>  $currentPage
        ];

        return view('orang/index', $data);
    }
}