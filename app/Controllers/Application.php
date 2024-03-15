<?php

namespace App\Controllers;

use App\Models\General;
use App\Libraries\Componente;

class Application extends BaseController
{
	public $session;
	public $inst;
	public $inst_ide;
	public function __construct()
	{
		$this->session = \Config\Services::session();
		//parent::__construct();
		if ($this->session->login != md5("L0g¡NS!st3M4")) {
			echo "inactivo";
			exit(0);
			return false;
		}
		$this->inst = General::getData("*", "instituciones", array("inst_ide" => $this->session->inst_ide), "inst_ide");
		$this->inst = $this->inst[0];
		$this->inst_ide = $this->session->inst_ide;
	}
	/************************************************************************/
	public function index()
	{
		$roles = General::getRoles($this->session->perf_ide);
		$roles2 = array();
		$modulos = array();
		foreach ($roles as $reg) {
			$modulos[$reg->modu_ide] = $reg->modu_nombre;
			$roles2[$reg->modu_ide][] = array(
				"icono" => $reg->modu_icono,
				"modulo" => $reg->modu_nombre,
				"rol" => $reg->role_nombre,
				"url" => $reg->role_url,
				"ide" => $reg->role_ide,
				"nombre" => $reg->role_nombre,
				"descripcion" => $reg->role_descripcion,
				"clase" => $reg->modu_clase,
			);
		}
		$data = array(
			"system_name" => "Tramitame_v2.0",
			"session" => $this->session,
			"logo" => $this->inst->inst_logo,
			"roles2" => $roles2,
			"contacto_datos" => "Ing. Víctor Hugo BEJAR GONZALES",
			"contacto_celular" => "958273933",
			"contacto_email" => "victor.bejar.g@gmail.com",
			"base" => base_url("public"),
		);
		$vistas = view('sistema/vheader', $data) .
			view('sistema/vindex') .
			view('sistema/vfooter') .
			view('sistema/vmenu');
		return $vistas;
	}

	public function setpass()
	{
		$ante = $this->request->getPost('anterior');
		$nueva = $this->request->getPost('nueva');
		$repi = $this->request->getPost('repite');
		$usua_ide = $this->session->usua_ide;
		$usua_user = $this->session->usuario;

		if ($ante == "" or $nueva == "" or $repi == "") {
			$clase = "alert alert-danger";
			$icono = "ti-close";
			$mensaje = "Complete todos los campos antes de continuar";
		} else if ($nueva == $repi) {
			$where = array(
				"usua_ide" => $usua_ide,
				"usua_user" => $usua_user,
				"usua_pass" => $ante
			);
			$data = array(
				"usua_pass" => $nueva
			);
			$r = General::actualizar("usuarios", $where, $data);
			if ($r == 0) {
				$clase = "alert alert-warning";
				$icono = "ti-alert";
				$mensaje = "No se pudo cambiar la clave, intentelo nuevamente";
			} else {
				$clase = "alert alert-success";
				$icono = "ti-check";
				$mensaje = "Se realizo el cambio de clave exitosamente";
			}
		} else {
			$clase = "alert alert-danger";
			$icono = "ti-alert";
			$mensaje = "La nueva clave y la clave que se repite tienen que ser las mismas, verifique para continuar";
		}
		$result = array(
			"clase" => $clase,
			"mensaje" => $mensaje,
			"icono" => $icono
		);
		echo json_encode($result);
	}
	public function salir()
	{
		$this->session->destroy();
		return redirect()->to(base_url('/login/index/' . $this->inst->inst_ide));
	}
	public function testing()
	{
	}
}
