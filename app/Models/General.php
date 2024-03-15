<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Componente;

class General extends Model
{
	/*public function __construct(){
        $this->session = \Config\Services::session();
    }*/
	public static function getRoles($perf_ide)
	{
		$db = \Config\Database::connect();
		$builder = $db->table('accesos a');
		$builder->select("
			m.modu_ide,
			r.role_ide,
			m.modu_orden,
			r.role_orden,
			m.modu_icono,
			m.modu_nombre,
			r.role_nombre,
			r.role_url,
			r.role_descripcion,
			m.modu_clase
		");
		$builder->from("
			roles r,
			modulos m,
			perfiles p
		");

		$builder->where("a.acce_perf_ide", $perf_ide);
		$builder->where("a.acce_perf_ide = p.perf_ide");
		$builder->where("a.acce_role_ide = r.role_ide");
		$builder->where("r.role_modu_ide = m.modu_ide");
		$builder->where("r.role_esta_nombre = 'ACTIVO'");
		$builder->where("a.acce_esta_ide = 1");
		$builder->orderBy("m.modu_orden, r.role_orden");

		$query = $builder->get();
		return $query->getResult();
	}
	public static function getRolesAsignados($usua_ide)
	{
		$db = \Config\Database::connect();
		$builder = $db->table('accesos a');
		$builder->select("
			if(a.acce_ide is NULL,'','ASIGNADO') as estado,
			a.acce_ide,
			r.role_ide,
			a.acce_usua_ide,
			m.modu_nombre,
			r.role_nombre,
			r.role_descripcion
		");
		$builder->join(
			"roles r",
			"a.acce_role_ide = r.role_ide AND a.acce_usua_ide = $usua_ide  AND a.acce_esta_ide = 1",
			"right"
		);
		$builder->join("modulos m", "r.role_modu_ide = m.modu_ide", "right");
		$builder->orderBy("m.modu_orden,r.role_orden");

		$query = $builder->get();
		return $query->getResult();
	}

	public static function getData($campos, $tablaPrincipal, $where, $order)
	{
		$db = \Config\Database::connect();
		$builder = $db->table($tablaPrincipal);
		$builder->select($campos);
		$builder->where($where);
		$builder->orderBy($order);
		$query = $builder->get();
		return $query->getResult();
	}
	public static function getData2($campos, $tablaPrincipal, $tablasSecundarias, $where, $order)
	{
		$db = \Config\Database::connect();
		$builder = $db->table($tablaPrincipal);
		$builder->select($campos);
		if ($tablasSecundarias != false) {
			$builder->from($tablasSecundarias);
		}
		foreach ($where as $w) {
			$builder->where($w);
		}
		$builder->orderBy($order);
		$query = $builder->get();
		return $query->getResult();
	}
	public static function actualizar($tabla, $where, $data)
	{
		$db = \Config\Database::connect();
		$builder = $db->table($tabla);
		$builder->where($where);
		$builder->update($data);
		return $db->affectedRows();
	}
	public static function insertar($tabla, $data)
	{
		$db = \Config\Database::connect();
		$builder = $db->table($tabla);
		$builder->insert($data);
		return $db->affectedRows();
	}
	public static function eliminar($tabla, $where)
	{
		$db = \Config\Database::connect();
		$builder = $db->table($tabla);
		$builder->delete($where);
		return $db->affectedRows();
	}
	public static function ultimoId()
	{
		$db = \Config\Database::connect();
		$sql = "
			SELECT
                last_insert_id() as id
        ";
		$query = $db->query($sql);
		return $query->getResult();
	}
	public static function get_expediente($tabla, $inst, $expe_ide)
	{
		$db = \Config\Database::connect();
		$builder = $db->table($tabla);
		$builder->select("
			e.expe_ide,
			concat(e.expe_anio,'-',e.expe_numero) AS expe,
			e.expe_folios AS folios,
			concat(e.expe_gestor,' (DNI. ',e.expe_dni,')') AS gestor,
			concat(t.tiex_nombre,' ',e.expe_siglas) AS documento,
			e.expe_asunto as asunto
		", false);
		$builder->from("
			tipos_expedientes t
		");
		$builder->where("e.expe_tiex_ide = t.tiex_ide");
		$builder->where("e.expe_inst_ide", $inst);
		$builder->where("e.expe_ide", $expe_ide);
		//$builder->where("e.expe_tipo",$tipo);
		//$builder->where("e.expe_anio",$anio);
		//$builder->where("e.expe_numero",$numero);
		$query = $builder->get();
		return $query->getResult();
	}
	public static function get_busqueda_expe($tabla, $inst, $expe_ide)
	{
		$db = \Config\Database::connect();
		$builder = $db->table($tabla);
		$builder->select("
			e.esta_nombre_repo,
			e.esta_nombre,
			o.ofic_nombre,
			s.segu_freg,
			s.segu_proveido,
			s.segu_observaciones,
			s.segu_adjuntos,
			s.segu_server
		", false);
		$builder->from("
			estados e,
			oficinas o
		");
		$builder->where("s.segu_esta_ide = e.esta_ide");
		$builder->where("s.segu_inst_ide", $inst);
		$builder->where("s.segu_expe_ide", $expe_ide);
		$builder->where("s.segu_ofic_destino = o.ofic_ide");
		$builder->orderBy("s.segu_ide asc");
		$query = $builder->get();
		return $query->getResult();
	}
}
