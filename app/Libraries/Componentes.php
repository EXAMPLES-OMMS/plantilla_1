<?php

namespace App\Libraries;

class Componentes
{
    public $subComponentes;

    public function __construct()
    {
        $this->subComponentes = array();
    }
    public function agregar($componente)
    {
        $this->subComponentes[] = $componente;
    }
    public function get($etiqueta, $clase, $propiedades)
    {
        $componentes = "";
        foreach ($this->subComponentes as $reg) {
            $componentes .= $reg;
        }
        return "
            <$etiqueta class='$clase' $propiedades>
                $componentes
            </$etiqueta>
        ";
    }

    public static function Tabla($id, $head, $data, $limit, $search, $paginacion, $clase, $botones, $js, $titulo = "")
    {
        $data = array(
            "id" => $id,
            "head" => $head,
            "clase" => $clase,
            "data" => $data,
            "botones" => $botones,
            "limit" => $limit,
            "search" => $search,
            "paginacion" => $paginacion,
            "js" => $js,
            "titulo" => $titulo
        );
        return view('componente/tabla', $data);
    }
    public static function Modal($id, $titulo, $body, $botonok, $size)
    {
        $data = array(
            "id" => $id,
            "titulo" => $titulo,
            "body" => $body,
            "botonok" => $botonok,
            "size" => $size
        );
        return view('componente/modal', $data);
    }
    public static function Boton($id, $type, $clase, $icono, $txt)
    {
        return "
            <button 
                id='$id' 
                name='$id' 
                type='$type' 
                class='btn btn-$clase'
            >
                <i class='$icono'></i>
                $txt
            </button>
        ";
    }
    public static function CheckBox($id, $clase, $txt, $value)
    {
        return "
            <div class='form-check'>
                <input id='$id' class='form-check-input $clase' type='checkbox' value='$value'>
                <label for='_dm-rememberCheck' class='form-check-label'>
                    $txt
                </label>
            </div>
        ";
    }
    public static function Input($id, $tipo, $value, $placeholder, $clase)
    {
        return "
            <div class='form-floating mb-3'>
                <input 
                    type='$tipo' 
                    class='form-control form-control border-$clase'
                    id='$id'
                    name='$id'
                    placeholder='$placeholder'
                    value='$value'
                    autocomplete='off'
                    required='required'
                >
                <label for='$id' class='fs-5 text-$clase'>$placeholder</label>
                <div id='$id-error' class='text text-danger form-errores'></div>
            </div>
        ";
    }
    public static function Hidden($id, $value)
    {
        return "
            <input 
                type='hidden'
                id='$id'
                name='$id'
                value='$value'
                autocomplete='off'
                required='required'
            >
        ";
    }
    public static function Textarea($id, $value, $placeholder, $rows, $clase)
    {
        return "
            <div class='form-floating mb-3'>
                <textarea class='form-control border-$clase' placeholder='$placeholder' id='$id' name='$id' style='height:" . (25 * $rows) . "px' required='required'>$value</textarea>
                <label for='$id' class='fs-5 text-$clase'>$placeholder</label>
                <div id='$id-error' class='text text-danger form-errores'></div>
            </div>
        ";
    }
    public static function Select($id, $label, $data, $clase, $itemVacio, $value = "")
    {
        $data2 = array();
        foreach ($data as $reg) {
            $data2[] = (array)$reg;
        }
        $data = $data2;

        $opciones = "";
        if ($itemVacio == true) {
            $opciones = "<option value=''>Seleccione un item</option>";
        }
        foreach ($data as $reg) {
            $sel = "";
            if ($reg['id'] == $value) {
                $sel = "selected";
            }
            $opciones .= "<option $sel value='" . $reg['id'] . "'>" . $reg['nombre'] . "</option>";
        }
        return "
            <div class='form-floating mb-3'>
                <select 
                    class='form-select border-$clase' 
                    id='$id' 
                    name='$id' 
                    required='required'
                >
                    $opciones
                </select>
                <label for='$id' class='fs-5 text-$clase'>$label</label>
                <div id='$id-error' class='text text-danger form-errores'></div>
            </div>
        ";
    }
    public static function Select2($id, $label, $data, $clase, $itemVacio, $value = "", $contenedor)
    {
        $data2 = array();
        foreach ($data as $reg) {
            $data2[] = (array)$reg;
        }
        $data = $data2;

        $opciones = "";
        if ($itemVacio == true) {
            $opciones = "<option value=''>Seleccione un item</option>";
        }
        foreach ($data as $reg) {
            $sel = "";
            if ($reg['id'] == $value) {
                $sel = "selected";
            }
            $opciones .= "<option $sel value='" . $reg['id'] . "'>" . $reg['nombre'] . "</option>";
        }
        return "
            <div class=''>
                <select
                    class='form-select border-$clase' 
                    id='$id' 
                    name='$id' 
                    required='required'
                >
                    $opciones
                </select>
            </div>
            <style>
                .select2-container--default .select2-selection--single {
                    height: 50px;
                }
                .select2-container--default .select2-selection--single .select2-selection__rendered {
                    line-height: 50px;
                }
                .select2-container {
                    margin-bottom: 15px;
                }
                .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: 50px;
                }
                .select2-container--default .select2-search--dropdown .select2-search__field {
                    outline: 0;
                    background-color: #f5f5f5;
                    border-radius: 5px;
                }
            </style>
            <script>
                $('#$id').select2({
                    dropdownParent: $('#$contenedor'),
                    width: '100%',
                    placeholder: '$label',
                    maximumSelectionSize: 10,
                    containerCssClass: ':form-control:'
                });
            </script>
        ";
    }
    public static function Badge($clase, $txt)
    {
        return "<span class='badge bg-" . $clase . "'>$txt</span>";
    }
    public static function Row($componente, $clase)
    {
        return "<div class='row $clase'>$componente</div>";
    }
    public static function Col($col, $componente)
    {
        return "<div class='$col'>$componente</div>";
    }
    public static function Rol($modulo, $nombre, $descripcion, $clase, $icono, $url)
    {
        return "
            <div class='d-flex align-items-stretch border-$clase' style='border: 1px solid'>
                <div class='d-flex align-items-center justify-content-center flex-shrink-0 bg-$clase px-4 text-white'>
                        <i class='$icono fs-1'></i>
                </div>
                <div class='flex-grow-1 py-3 ms-3 border-$clase'>
                    <div class='h5 mb-0 text-$clase'>
                        <b>Módulo:</b>
                        $modulo
                    </div>
                    <div>
                    <a 
                        class='btn btn-xs btn-link mt-2 text-$clase'
                        onClick='cargarFuncion(\"$url\",\"$modulo\",\"$nombre\",\"$descripcion\")'
                    >Ir a $nombre</a>
                    </div>
                </div>
            </div>
        ";
    }
    public static function Card1($titulo, $body, $clase)
    {
        return "
            <div class='card bg-$clase text-white'>
                <h5 class='card-header'>$titulo</h5>
                <div class='card-body'>
                    $body
                </div>
            </div>
        ";
    }
    public static function H1($body, $clase)
    {
        return "
            <h1 class='$clase'>$body</h1>
        ";
    }
    public static function H2($body, $clase)
    {
        return "
            <h2 class='$clase'>$body</h2>
        ";
    }
    public static function H3($body, $clase)
    {
        return "
            <h3 class='$clase'>$body</h3>
        ";
    }
    public static function H4($body, $clase)
    {
        return "
            <h4 class='$clase'>$body</h4>
        ";
    }
    public static function H5($body, $clase)
    {
        return "
            <h5 class='$clase'>$body</h5>
        ";
    }
    public static function H6($body, $clase)
    {
        return "
            <h6 class='$clase'>$body</h6>
        ";
    }
    public static function Div($body, $clase, $id = "")
    {
        return "
            <div class='$clase' id='$id'>$body</div>
        ";
    }
    public static function Alert($body, $clase)
    {
        return "
            <div class='alert alert-$clase'>$body</div>
        ";
    }
    public static function Js($codigo)
    {
        return "
            <script>$codigo</script>
        ";
    }
    public static function Img($id, $src, $class)
    {
        return "
            <img src='" . base_url($src) . "' id='$id' class='$class'>
        ";
    }
    public static function Br()
    {
        return "<br>";
    }
    public static function Link($href, $target, $clase, $text)
    {
        return "<a href='$href' target='$target' class='btn btn-$clase'>$text</a>";
    }
    public static function TituloFuncion($titulo, $clase = "primary")
    {
        return "
            <div class='row'>
                <div class='col-12'>
                    <h3 class='text-$clase'><b>$titulo</b></h3>
                </div>
            </div>
        ";
    }
    public static function Iframe($id, $height = "400px", $width = "100%")
    {
        return "
            <iframe 
                src=''
                frameborder='0'
                width='$width'
                height='$height'
                id='$id'
                onload='closeCargar();'
            ></iframe>
        ";
    }
    public static function Tabla2($titulo, $listaEncabezado, $bodyTabla, $clase)
    {
        $encabezado = "";
        foreach ($listaEncabezado as $reg) {
            $encabezado .= "<th width='$reg[0]'>$reg[1]</th>";
        }
        $cuerpo = "";
        foreach ($bodyTabla as $filas) {
            $cuerpo .= "<tr class='bgc-h-yellow-l3'>";
            foreach ($filas as $col) {
                $cuerpo .= "<td>$col</td>";
            }
            $cuerpo .= "</tr>";
        }
        return "
            <div class='card border-2 border-$clase'>
                <div class='card-body'>
                    <div class='radius-1 table-responsive'>
                        <h4 class='text-$clase'>$titulo</h4>
                        <div class='gridjs-wrapper' style='height:auto;'>
                            <table class='gridjs-table table table-bordered table-hover table-striped'>
                                <thead class='gridjs-thead bg-light'>
                                    <tr class='gridjs-tr'>
                                        $encabezado
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class='bgc-h-yellow-l3'>
                                        $cuerpo
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            ";
    }
    public static function Icono($icono)
    {
        return "<i class='$icono'></i>";
    }

    public static function Periodo($anio, $mes, $ini, $fin, $estado, $icono, $clase, $peri_ide)
    {
        return "
            <button 
                type='button' 
                class='btn btn-sm btn-$clase' 
                style='width: 100%;'
                peri_ide = '$peri_ide'
            >
                <span class='$icono text-100 display-6'></span><br>
                PERIODO $estado
                <div class='fs-4'>
                    <b>" . Funciones::get_mes($mes) . '<br>' . $anio . "<br></b>
                </div>                
                <span style='font-size: 10px;'>Del " . date('d/m/Y', strtotime($ini)) . "</span><br>
                <span style='font-size: 10px;'>Al " . date('d/m/Y', strtotime($fin)) . "</span><br>
            </button>
        ";
    }

    public static function File($id, $placeholder, $clase, $tamaño = '200000', $extension = '', $required)
    {
        if ($required == 'required') {
            $required = "required='required'";
        } else {
            $required = "";
        }
        return "
            <div class='form-floating mb-3'>
                <input 
                    type='file' 
                    class='form-control border-$clase'
                    id='$id'
                    name='$id'
                    placeholder='$placeholder'
                    $required
                    accept='$extension'
                >
                <label for='$id' class='fs-5 text-$clase'>$placeholder</label>
            </div>
            <embed src='' style='width:100%; height:0px;' type='application/pdf' id='embed_$id' ></iframe>
            <script> $('#$id').on('change', function(){
                var ext = $( this ).val().split('.').pop();
                if ($( this ).val() != '') {
                    if(ext == 'pdf'){
                        /*alert('La extensión es: ' + ext);*/
                        if($(this)[0].files[0].size > " . $tamaño . "){
                            alertar('El documento excede el tamaño máximo, se solicita un archivo no mayor a " . ($tamaño / 1000000) . "MB. Por favor verifica.','alert alert-danger','fa-solid fa-xmark');
                            $(this).val('');
                        }else{
                            var TmpPath = URL.createObjectURL($(this)[0].files[0]);
                            console.log(TmpPath);
                            $('#embed_$id').attr('src',TmpPath);
                            $('#embed_$id').css('height','400px');
                        }
                    }
                    else{
                        $( this ).val('');
                        alertar('Extensión no permitida: ' + ext + '. El archivo debe de ser un documento PDF.','alert alert-danger','fa-solid fa-xmark');
                    }
                }
                else{
                    $('#embed_$id').attr('src','');
                    $('#embed_$id').css('height','0px');
                }
              });
              </script>
        ";
    }
    public static function mostrarErrores($errores)
    {
        $codigo = "$('.form-errores').html('');";
        foreach ($errores as $key => $val) {
            $kv = [$key, $val];
            $codigo = $codigo . "$('#$key-error').html('$val');";
        }
        return "<script>$codigo</script>";
    }
    public function demo($n, $t, $i, $clase)
    {
        return "
            <div class='card bg-$clase text-white mb-3 mb-xl-1'>
                <div class='p-3 text-center'>
                    <span class='display-5'>$n</span>
                    <p>$t</p>
                    <i class='$i text-white text-opacity-50 fs-5'></i>
                </div>
            </div>
        ";
    }
    public function reserva($col, $clase, $icono, $fecha, $descripcion, $estado, $rese_ide)
    {
        return "
            <div class='$col'>
                <div class='card mb-3 bg-white border-$clase'>
                    <div class='position-relative py-5 px-3 text-center bg-$clase text-white rounded-top'>
                        <div class='h1 stretched-link'>$fecha</div>
                        <p class='text-white mb-4'><bb>$descripcion</bb></p>
                    </div>

                    <div class='position-relative p-4'>
                        <div class='position-absolute top-0 start-50 translate-middle text-white'>
                            
                            <div class='flex-shrink-0'>
                                <div class='img-md ratio ratio-1x1 bg-white text-$clase rounded-circle border-$clase' style='border: 2px solid;'>
                                    <i class='d-flex align-items-center justify-content-center $icono fs-1'></i>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class='row text-center pb-2'>
                        <div class='col-sm-12 text-$clase'>
                            <b>$estado</b>
                        </div>
                    </div>

                    <div class='row text-center pb-2'>
                        <div class='col-sm-12'>
                            <div class='btn-group btn-group-sm' role='group'>
                                <button type='button' class='btn btn-$clase btnReservaRegistrar' ide='$rese_ide'>
                                    <i class='ti-user fs-3 mb-2'></i>
                                    <br>
                                    Registrar
                                </button>
                                
                                <button type='button' class='btn btn-$clase btnReservaPagar' ide='$rese_ide'>
                                <i class='ti-money fs-3 mb-2'></i>
                                    <br>
                                    Pagar
                                </button>
                                
                                <button type='button' class='btn btn-$clase btnReservaManifiesto' ide='$rese_ide'>
                                <i class='ti-file fs-3 mb-2'></i>
                                    <br>
                                    Manifiesto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }

    public function usuarioCard($col, $clase, $avatar_id, $nombres, $apellidos, $email, $dni, $cell1, $cell2, $ide)
    {
        $nombre = explode(" ", $nombres)[0];
        $apellido = explode(" ", $apellidos)[0];
        $avatar_id = $avatar_id === ''? '5' : $avatar_id;
        $path_avatar = base_url("./public/assets/img/profile-photos/$avatar_id.png");

        $body_modal = new Componentes;
        $body_modal->agregar($this->Input('usua_nombres', 'text', $nombres, 'Nombres', 'sm'));
        $body_modal->agregar($this->Input('usua_apellidos', 'text', $apellidos, 'Apellidos', 'sm'));
        $body_modal->agregar($this->Input('usua_dni', 'text', $dni, 'DNI', 'sm'));
        $body_modal->agregar($this->Input('usua_email', 'email', $email, 'Correo', 'sm'));
        $body_modal->agregar($this->Input('usua_cell1', 'text', $cell1, 'Celular 1', 'sm'));
        $body_modal->agregar($this->Input('usua_cell2', 'text', $cell2, 'Celular 2', 'sm'));

        $js = $this->Js("
            $('#buttonEditModal$ide').on('click', function(e){
                e.preventDefault();
                $('#editModal$ide').children().modal('toggle');
            })
            $('#guardar$ide').on('click', function() {
                console.log($('#modal$ide form').serialize())
            });
        ");
        return "
        <div class='$col'>
            <div class='card mb-3 border-$clase'>
                <div class='card-body pt-2'>

                    <!-- Favorite button and option dropdown -->
                    <div class='d-flex justify-content-end gap-1'>
                        <button type='button' data-bs-original-title='Add to Favorites' class='btn btn-sm btn-icon btn-hover btn-white shadow-none text-muted add-tooltip'>
                            <i class='demo-psi-star fs-5'></i>
                        </button>
                        <div class='dropdown'>
                            <button class='btn btn-sm btn-icon btn-hover btn-light shadow-none' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='demo-pli-dot-horizontal fs-4'></i>
                                <span class='visually-hidden'>Toggle Dropdown</span>
                            </button>
                            <ul class='dropdown-menu dropdown-menu-end' style=''>
                                <li>
                                    <a href='#' class='dropdown-item' id='buttonEditModal$ide'>
                                        <i class='demo-pli-pen-5 fs-5 me-2'></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a href='#' class='dropdown-item text-danger'>
                                        <i class='demo-pli-recycling fs-5 me-2'></i> Remove
                                    </a>
                                </li>
                                <li>
                                    <hr class='dropdown-divider'>
                                </li>
                                <li>
                                    <a href='#' class='dropdown-item'>
                                        <i class='demo-pli-mail fs-5 me-2'></i> Send a Message
                                    </a>
                                </li>
                                <li>
                                    <a href='#' class='dropdown-item'>
                                        <i class='demo-pli-calendar-4 fs-5 me-2'></i> View Details
                                    </a>
                                </li>
                                <li>
                                    <a href='#' class='dropdown-item'>
                                        <i class='demo-pli-lock-user fs-5 me-2'></i> Lock
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- END : Favorite button and option dropdown -->

                    <!-- Profile picture and short information -->
                    <div class='text-center position-relative'>
                        <div class='pb-3'>
                            <img class='img-lg rounded-circle' src='$path_avatar' alt='Avatar' loading='lazy'>
                        </div>
                        <a href='#' class='h5 stretched-link btn-link'>$nombre $apellido</a>
                        <p class='text-muted'>$email</p>
                        <!--<p class='text-muted'>$email - $dni</p>-->
                    </div>
                    <p class='text-center'>$cell1 - $cell2</p>
                    <!-- END : Profile picture and short information -->

                    <!-- Social media buttons -->
                    <div class='mt-4 pt-3 text-center border-top text-muted'>
                        <a href='#' class='btn btn-icon btn-hover btn-primary text-inherit'>
                            <i class='demo-psi-facebook fs-4'></i>
                        </a>
                        <a href='#' class='btn btn-icon btn-hover btn-info text-inherit'>
                            <i class='demo-psi-twitter fs-4'></i>
                        </a>
                        <a href='#' class='btn btn-icon btn-hover btn-danger text-inherit'>
                            <i class='demo-psi-google-plus fs-4'></i>
                        </a>
                        <a href='#' class='btn btn-icon btn-hover btn-warning text-inherit'>
                            <i class='demo-psi-instagram fs-4'></i>
                        </a>
                    </div>
                    <!-- END : Social media buttons -->

                </div>
            </div>
            <div id='editModal$ide'>".$this->Modal('modal'.$ide, 'Editar', $body_modal->get('form', '', ''), $this->Boton('guardar'.$ide,'','outline-success','','Guardar'), 'md')."</div>
        </div>
        ".$js;
    }
}
