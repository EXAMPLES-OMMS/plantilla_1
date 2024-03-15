<?php

namespace App\Controllers;

use App\Models\General;
use App\Libraries\Funciones;
use App\Libraries\Componentes;

class Login extends BaseController
{
    public function index($inst_ide = 0)
    {
        if ($inst_ide == 0) {
            echo "Inicio";
            return false;
        }
        $inst = General::getData("*", "instituciones", array("inst_ide" => $inst_ide), "inst_ide");
        if (count($inst) == 0) {
            return false;
        }
        $data = array(
            "inst_ide" => $inst_ide,
            "inst" => $inst,
            "logo" => $inst[0]->inst_logo,
            "fondo" => $inst[0]->inst_fondo,
            "sigla" => $inst[0]->inst_nombre,
            "ver" => $inst[0]->inst_ver_horario,
            "ini" => $inst[0]->inst_hora_ini,
            "fin" => $inst[0]->inst_hora_fin,
            "error" => $this->request->getVar('error')
        );
        return view('login/vlogin', $data);
    }
    public function verificar()
    {
        $where = array(
            "usua_inst_ide" => $this->request->getPost('inst'),
            "usua_user" => $this->request->getPost('user'),
            "usua_pass" => $this->request->getPost('pass'),
            "usua_estado" => 'ACTIVO',
        );
        $usuario = General::getData("*", "usuarios", $where, "usua_ide");
        if (count($usuario) == 1) {
            $fecha = Funciones::get_ahora_fecha();
            $login = array(
                "logi_inst_ide" => $usuario[0]->usua_inst_ide,
                "logi_usua_ide" => $usuario[0]->usua_ide,
                "logi_user" => $this->request->getPost('user'),
                "logi_pass" => $this->request->getPost('pass'),
                "logi_accedio" => "SI",
                "logi_datos" => $usuario[0]->usua_nombres . " " . $usuario[0]->usua_apellidos,
                "logi_freg" => Funciones::get_ahora()
            );
            General::insertar("logins", $login);
            $data_session = array(
                "login" => md5("L0g¡NS!st3M4"),
                "inst_ide" => $usuario[0]->usua_inst_ide,
                "usua_ide" => $usuario[0]->usua_ide,
                "perf_ide" => $usuario[0]->usua_perf_ide,
                "datos" => $usuario[0]->usua_apellidos . ", " . $usuario[0]->usua_nombres,
                "usuario" => $usuario[0]->usua_user,
                "dni" => $usuario[0]->usua_dni,
                "siglas" => "Tramitame_v2.0",
                "icono" => "fa-solid fa-file-import",
                "ini_fecha" => Funciones::get_fecha_letras($fecha),
                "ini_hora" => Funciones::get_ahora_hora(),

            );
            $session = \Config\Services::session();
            $session->set($data_session);
            //print_r($data_session);
            return redirect()->to(base_url('/application'));
        } else {
            $fecha = Funciones::get_ahora();
            $login = array(
                "logi_user" => $this->request->getPost('user'),
                "logi_pass" => $this->request->getPost('pass'),
                "logi_accedio" => "NO",
                "logi_freg" => $fecha
            );
            General::insertar("logins", $login);
            return redirect()->to(base_url("/login/index/" . $this->request->getPost('inst') . "?error=true"));
        }
    }
}
