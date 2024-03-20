<style>
    .table-hover>tbody>tr:hover {
        /* --bs-table-accent-bg: #2f5fa9; */
        color: var(--bs-primary);
        border-left: 3px solid var(--bs-primary);
        /*font-weight:bold;*/
        font-style: italic;
        cursor: pointer;
    }
</style>
<?php

use App\Libraries\Componentes;
?>
<div class="row">
    <div class="col-md-12 mb-3">
        <div class="card border-2 border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title">
                        <?php echo $tituloTabla; ?>
                    </h5>

                    <?php
                    $c = new Componentes;
                    $body = new Componentes;
                    $select_reg = [];
                    foreach ($campos as $campo) {
                        if ($campo['Extra']['Edit']) {
                            if ($campo['Type1']['tipo'] == 'enum') {
                                foreach ($campo['Type1']['param'] as $param) {
                                    $select_reg[] = [
                                        'id' => $param,
                                        'nombre' => $param,
                                    ];
                                }
                                $body->agregar($c->Select($campo['Extra']['Id'], $campo['Label'], $select_reg, '', '', ''));
                            } else if ($campo['Extra']['Type'] == 'selectDB') {
                                $body->agregar($c->Select($campo['Extra']['Id'], $campo['Label'], $campo['Extra']['Data'],'',true,''));
                            } else
                                $body->agregar($c->Input($campo['Extra']['Id'], $campo['Extra']['Type'], '', $campo['Label'], 'primary'));
                        }
                    }
                    echo $c->Modal($grilla_ide . '_insertar', 'Crear', $body->get('form', 'row', ''), $c->Boton('insertar_guardar', '', 'outline-success', '', 'Insertar'), 'modal-lg');
                    echo $c->Boton($grilla_ide . '_btn_insertar', 'button', 'primary', false, 'insertar');
                    echo $c->Modal($grilla_ide . '_editar', 'Crear', $body->get('form', 'row', ''), $c->Boton('editar_guardar', '', 'outline-success', '', 'Guardar'), 'modal-lg');
                    ?>
                </div>
                <div class="table-responsive">
                    <table id="<?php echo $grilla_ide . "_tabla"; ?>" class="gridjs-table table table-bordered table-hover table-striped" style="max-width:<?php echo $widthTabla; ?>;width:<?php echo $widthTabla; ?>!important;">
                        <thead class="gridjs-thead bg-light">
                            <tr class="gridjs-tr text-center">
                                <?php foreach ($campos as $reg) { ?>
                                    <th width="<?php echo $reg["Width"] . "%"; ?>"><?php echo $reg["Label"]; ?> </th>
                                <?php } ?>
                                <th width="10%">Opciones</th>
                            </tr>
                        </thead>

                        <tbody id="<?php echo $grilla_ide . "_data"; ?>">
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <input type="text" id="<?php echo $grilla_ide . "_b"; ?>" placeholder="Buscar..." class="form-control form-control-sm border-primary mb-1" onchange="getDataGrilla()">
                    </div>
                    <div class="col-sm-1 d-grid">
                        <button class="btn btn-sm btn-secondary mb-1" onclick="getDataGrilla()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="col-sm-1 text-end">
                        Número de registros
                    </div>
                    <div class="col-sm-2">
                        <select id="<?php echo $grilla_ide . "_m"; ?>" class="form-select form-select-sm border-primary mb-1" onchange="getDataGrilla()">
                            <?php foreach ($listaMostrar as $lis) { ?>
                                <option value="<?php echo $lis; ?>"><?php echo $lis; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-1 text-end">
                        Número de Páginas
                    </div>
                    <!--<div class="col-sm-2" id="<?php echo $grilla_ide . "_p"; ?>">
                    </div>-->
                    <div class="col-sm-2">
                        <select id="<?php echo $grilla_ide . "_p"; ?>" class="form-select form-select-sm border-primary mb-1" onchange="getDataGrilla()">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php foreach ($componentes as $reg) {
                            echo $reg;
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function getDataGrilla() {
        openCargar();
        param = {
            load: true,
            m: $("<?php echo "#" . $grilla_ide . "_m"; ?>").val(),
            p: $("<?php echo "#" . $grilla_ide . "_p"; ?>").val(),
            b: $("<?php echo "#" . $grilla_ide . "_b"; ?>").val(),
        };
        p = "?" + "m=" + param.m + "&";
        p += "p=" + param.p + "&";
        p += "b=" + param.b;
        $.post("<?php echo $url; ?>" + p, param, function(data) {
            $("<?php echo "#" . $grilla_ide . "_data"; ?>").html(data);
            closeCargar();
        });
    }
    getDataGrilla();

    $('#<?php echo $grilla_ide ?>_btn_insertar').click(function() {
        $('#<?php echo $grilla_ide ?>_insertar').modal('show');
    })
    $('#insertar_guardar').click(function() {
        const modal = $('#<?php echo $grilla_ide ?>_insertar');
        const form = $('#<?php echo $grilla_ide ?>_insertar form');
        const param = {
            tabla: '<?php echo $tabla ?>',
            data: form.serialize(),
        }
        ajax('/grilla/insertar', param, function(data) {
            // console.log(data);
            getDataGrilla();
            closeCargar();
            modal.modal('hide');
        });
    })
</script>