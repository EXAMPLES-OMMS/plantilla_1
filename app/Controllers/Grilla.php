<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Mgrilla;

class Grilla extends BaseController
{
    public function index()
    {
        //
    }
    public function getData()
    {
        $ide = $this->request->getPost('ide');
        $campo_ide = $this->request->getPost('campo_ide');
        $tabla = $this->request->getPost('tabla');
        $g = new Mgrilla;
        return json_encode($g->getData2($ide, $campo_ide, $tabla));
    }
    public function insertar()
    {
        $session = \Config\Services::session();
        $tabla = $this->request->getPost('tabla');
        $data = [];
        parse_str("" . $this->request->getPost('data'), $data);
        $data = array_slice($data, 0, count($data) - 1);
        $data['usua_inst_ide'] = $session->inst_ide;
        $g = new Mgrilla;
        $g->insertar($tabla, $data);
        return json_encode(['status' => 202, 'message' => 'User inserted successfully']);
    }
    public function actualizar()
    {
        $ide = $this->request->getPost('ide');
        $campo_ide = $this->request->getPost('campo_ide');
        $tabla = $this->request->getPost('tabla');
        $data = [];
        parse_str("" . $this->request->getPost('data'), $data);
        $data = array_slice($data, 0, count($data) - 1);
        $g = new Mgrilla;
        // print_r($data);
        $g->actualizarData($ide, $campo_ide, $tabla, $data);
        return json_encode($g->getData2($ide, $campo_ide, $tabla));
    }
    public function eliminar()
    {
        $ide = $this->request->getPost('ide');
        $campo_ide = $this->request->getPost('campo_ide');
        $tabla = $this->request->getPost('tabla');
        $g = new Mgrilla;
        $g->eliminar($ide, $campo_ide, $tabla);
        return json_encode(['status' => 204, 'message' => 'User deleted successfully']);
    }
}
