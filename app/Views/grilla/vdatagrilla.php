<?php

use App\Libraries\Componentes;
?>
<?php if (count($data) == 0) { ?>
    <tr>
        <td colspan="<?php echo count($campos); ?>">No se encuentran registros para mostrar</td>
    </tr>
<?php } ?>
<?php foreach ($data as $reg) {
    $tmp = (array)$reg; ?>
    <tr <!--onclick="alert(123)" -->>
        <?php foreach ($campos as $r) { ?>
            <td title="<?php echo $tmp[$r["Field"]]; ?>">
                <?php
                $format = $r["Format"];
                if ($format == "no") {
                    echo $tmp[$r["Field"]];
                } else if ($format == "archivos") {
                    $server = $tmp["expe_server"];
                    $archivos = $tmp[$r["Field"]];
                    $archivos = explode(",", $archivos);
                    $ccc = 1;
                    foreach ($archivos as $arch) {
                        if ($arch != "") {
                            $base = "";
                            if ($server == "this") {
                                $base = "http://xura-inc.com/tramitameuc/";
                            } else if ($server == "consophi") {
                                $base = "http://consorciophi.com/tramitameuc1/";
                            } else {
                                $base = base_url();
                            }
                            echo Componentes::Link($base . "/archivos/" . $arch, "_black", "secondary btn-xs mb-1 mr-1", "Adjunto $ccc");
                            $ccc++;
                        }
                    }
                } else if ($format == "ocultar") {
                } else if ($format == "check") {
                    echo Componentes::CheckBox(
                        $grilla_ide . "_" . $r["Field"] . "_" . $tmp[$r["Field"]],
                        "seleccionados",
                        "",
                        $tmp[$r["Field"]]
                    );
                } else if (is_array($format)) {
                    if ($format[0] == "btn") {
                        echo Componentes::Boton(
                            $grilla_ide . "_" . $r["Field"] . "_" . $tmp[$r["Field"]],
                            "button",
                            $format[1] . " btn-xs",
                            $format[2],
                            $format[3]
                        );
                    }
                } else if ($format == "semaforo") {
                    echo "123";
                }
                ?>
            </td>
        <?php } ?>
        <td>
            <?php echo Componentes::Boton($tmp[$tabla_ide] . '_btn_editar', 'button', 'primary editar_' . $grilla_ide, 'fa fa-pencil', '') ?>
            <?php echo Componentes::Boton($tmp[$tabla_ide] . '_btn_eliminar', 'button', 'danger eliminar_' . $grilla_ide, 'fa fa-trash', '') ?>
        </td>
    </tr>
<?php } ?>

<script>
    function getPaginasGrilla() {
        opciones = "";
        for (i = 1; i <= <?php echo $pgs; ?>; i++) {
            opciones += "<option value='" + i + "'>" + i + " de " + <?php echo $pgs; ?> + "</option>";
        }
        $("<?php echo "#" . $grilla_ide . "_p" ?>").html(opciones);
        $("<?php echo "#" . $grilla_ide . "_p" ?>").val("<?php echo $pag; ?>");
    }
    getPaginasGrilla();

    function fillModal(modalSelector, data) {
        const modal = $(modalSelector)
        modal.modal('show');
        modal.attr('data-id', data.usua_ide);
        inputs = [...$('#<?php echo $grilla_ide ?>_editar input')];
        inputs.forEach(input => {
            $(input).val(data[$(input).attr('id')]);
        })
        selects = [...$('#<?php echo $grilla_ide ?>_editar select')];
        selects.forEach(select => {
            $(select).val(data[$(select).attr('id')]);
        })
    }

    $('.editar_<?php echo $grilla_ide ?>').click(function() {
        // alert($(this).attr('id'))
        const btn = $(this)
        const param = {
            ide: btn.attr('id'),
            campo_ide: '<?php echo $tabla_ide ?>',
            tabla: '<?php echo $tabla ?>',
        }
        ajax('/grilla/getData', param, function(data) {
            fillModal(
                $('#<?php echo $grilla_ide ?>_editar'),
                JSON.parse(data)[0]
            )
            closeCargar();
        })
    })
    $('#editar_guardar').click(function() {
        const modal = $('#<?php echo $grilla_ide ?>_editar');
        const form = $('#<?php echo $grilla_ide ?>_editar form');
        const param = {
            ide: modal.data('id'),
            campo_ide: '<?php echo $tabla_ide ?>',
            tabla: '<?php echo $tabla ?>',
            data: form.serialize(),
        }
        ajax('/grilla/actualizar', param, function(data) {
            // console.log(data);
            getDataGrilla();
            closeCargar();
            modal.modal('hide');
        });
    })
    $('.eliminar_<?php echo $grilla_ide ?>').click(function() {
        const btn = $(this);
        param = {
            ide: btn.attr('id'),
            campo_ide: '<?php echo $tabla_ide ?>',
            tabla: '<?php echo $tabla ?>',
        }
        ajax('/grilla/eliminar', param, function(data) {
            getDataGrilla();
            closeCargar();
        })
    })
</script>