<?php

namespace App\Controllers;

use App\Models\General;
use App\Models\Generaldb;
use App\Libraries\Componentes;
use App\Libraries\Funciones;
use App\Libraries\Lgrilla;
use App\Libraries\SuperComponente;

class Alumno extends BaseController
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
    public function inscribir()
    {
        $form = new Componentes();
        $compo = new Componentes();
        $dni = $compo->Input("dni", "number", "", "DNI", "info");
        $nombres = $compo->Input("nombres", "text", "", "NOMBRES", "info");
        $paterno = $compo->Input("paterno", "text", "", "AP. PATERNO", "info");
        $materno = $compo->Input("materno", "text", "", "AP. MATERNO", "info");

        $form->agregar($compo->Col("col-sm-2", $dni));
        $form->agregar($compo->Col("col-sm-3", $nombres));
        $form->agregar($compo->Col("col-sm-3", $paterno));
        $form->agregar($compo->Col("col-sm-3", $materno));
        $form->agregar($compo->Col("col-sm-6", $compo->demo("1", "abc", "fas fa-user", "dark")));
        $form->agregar($compo->Col("col-sm-12 d-grid", $compo->Boton("btnGuardar", "submit", "danger", "fas fa-check", "Guardar")));

        echo $form->get("form", "row", "id='form_inscribir'");

        echo $compo->Js("
            $('#form_inscribir').submit(function(e){
                e.preventDefault();
                openCargar();
                ajax('alumno/guardar',$(this).serialize(),function(data){
                    alertar(data);
                    closeCargar();
                });
            });
        ");
    }
    public function guardar()
    {
        $data = $this->request->getPost();
        $set = array(
            "insc_dni" => $data["dni"],
            "insc_nombres" => $data["nombres"],
            "insc_paterno" => $data["paterno"],
            "insc_materno" => $data["materno"],
            "insc_codigo" => Funciones::getCadena(8),
            "insc_create_at" => Funciones::get_ahora(),
        );
        General::insertar("inscritos", $set);
        echo "Guardado correctamente...";
    }
    public function prueba()
    {
        $db = new Generaldb;
        $comp = new Componentes;
        $lista = new Componentes;
        $usuarios = $db->selectSomeDataJoin('*', 'usuarios');
        foreach ($usuarios as $idx => $usuario) {
            $lista->agregar($comp->usuarioCard(
                'col-xl-3 col-md-4 col-sm-6',
                $usuario->usua_estado == 'ACTIVO' ? 'primary' : 'danger',
                '',
                $usuario->usua_nombres,
                $usuario->usua_apellidos,
                $usuario->usua_email,
                $usuario->usua_dni,
                $usuario->usua_cell1,
                $usuario->usua_cell2,
                $usuario->usua_ide,
            ));
        }
        echo $lista->get('div', 'row', '');
    }
    public function grillausua()
    {
        $m = $this->request->getVar("m");
        $p = $this->request->getVar("p");
        $b = $this->request->getVar("b");

        $lgrilla = new Lgrilla;
        $lgrilla->setURL("alumno/grillausua");
        // $expe = "expedientes_" . $this->inst->inst_sufijo;
        $lgrilla->setTablaPrincipal('usuarios');
        $lgrilla->setTablaSecundaria('perfiles', 'usua_perf_ide = perf_ide', 'left');
        // $lgrilla->setTablaSecundaria("tipos_expedientes", "expe_tiex_ide = tiex_ide");
        // $lgrilla->setTablaSecundaria("remitentes", "remi_ide = expe_remi_ide");
        $lgrilla->getCampos();
        $lgrilla->setWhere(
            array(
                "usua_inst_ide" => $this->session->inst_ide,
            )
        );
        $lgrilla->setIde('usua_ide');
        $lgrilla->setOrden("usua_ide");
        //TODO: $edit to arr [edit = true, type = 'text', value = '']
        $lgrilla->setColumna("usua_ide","Nº","10",['Edit' => false],"no");
        $lgrilla->setColumna("usua_nombres","Nombres","10",['Edit' => true] ,"no");
        $lgrilla->setColumna("usua_apellidos","Apellidos","10",['Edit' => true],"no");
        $lgrilla->setColumna("usua_email","Email","10",['Edit' => true, 'Type' => 'email'],"no");
        $lgrilla->setColumna("usua_estado","Estado","10",['Edit' => true],"no");
        $lgrilla->setColumna("perf_nombre","Perfil","10",['Edit' => true],"no");

        $lgrilla->setTituloTabla("Lista de usuarios");
        $lgrilla->setWidthTabla("100%");
        $lgrilla->setlistaMostrar(array("10", "20", "30", "40", "50"));

        if (!$this->request->getVar("load")) {
            // $anios = General::GetData("anio_nombre as id, anio_nombre as nombre", "anios", [], "anio_nombre desc");
            // $bodyModal = new SuperComponente;
            // $bodyModal->add(Componentes::Hidden("expe_ide", ""));
            // $bodyModal->add(Componentes::Select("expe_anio", "Año", $anios, "primary", true, ""));
            // $bodyModal->add(Componentes::Input("expe_numero", "number", "", "Numero de expediente", "primary"));
            // $bodyModal->add(Componentes::Boton("btn_asignar_an", "submit", "primary", "fas fa-check", "Asignar número de expediente"));
            // $bodyModal = $bodyModal->get("form", "was-validated row", "id='form_asignar_anio_numero'");
            // echo Componentes::Modal("modal_asignar_anio_numero", "ASIGNAR NUMERO DE EXPEDIENTE", $bodyModal, "", "modal-lg");

            // $lgrilla->addComponente(Componentes::Js("
            //     $('#form_asignar_anio_numero').submit(function(e){
            //         e.preventDefault();
            //         $('#modal_asignar_anio_numero').modal('hide');
            //         ajax('procesos/asignar_anio_numero',$(this).serialize(),function(data){
            //             data=JSON.parse(data);
            //             alertar2(data);
            //             getDataGrilla();
            //             closeCargar();
            //         });
            //     });
            // "));
            $lgrilla->mostrarGrilla("grilla_usuarios");
        } else {
            $lgrilla->mostrarDataGrilla($m, $p, $b, "grilla_usuarios");
            // echo Componentes::Js("
            //     $('.btn-asignar-an').click(function(){
            //         $('#expe_ide').val($(this).attr('id'));
            //         $('#modal_asignar_anio_numero').modal('show');
            //     });
            // ");
        }
    }
}
