$(document).ready(function() {
    mostrarDatos();
} );

visualizarDatos = '';

/**
 * Visualiza las estadisticas de los datos procesados
 */
const mostrarEstadisticas = () =>{
    $('#detalles_cuerpo').html(visualizarDatos);
    $('#detalles_modal').modal("show");
}


const mostrarAyuda= () =>{
    $('#detalles_cuerpo').html($('#ayudaContenido').html());
    $('#detalles_modal').modal("show");
}

/**
 * Verificar si los valores son 0 devolver 0 para evitar errores de NaN y calcular Promedio
 * @param int cant
 * @param float total
 * @returns int
 */
const verifyCeroProm = (total,cantidad) =>{
    let valor = 0;
    if(cantidad != 0){
        valor = Math.round(total/cantidad)
    }
    return valor
}

const mostrarDatos = () =>{
    var debito = 0,
        credito = 0,
        efectivo = 0;
    var cantDebito = 0,
        cantCredito = 0,
        cantEfectivo = 0;

    var tabla = $('#tablaDatos').DataTable( {
        "destroy": true,
        "ajax": {
            "url":"assets/php/acciones.php",
            "type": "POST",
            "data": {"accion":"todosArchivos"},
            //Procesando para visualizar los datos necesarios
            "dataSrc": function (json) {
                        var objeto = $.map(json, function(objeto, i) {
                            var datos=$.map(objeto.body, function(dato, indice) {
                                if(dato.codServicio){
                                    //Pago Plus
                                    var info = {
                                        "id":dato.codServicio,
                                        "transaccion":dato.transaccion,
                                        "fechaPago": dato.fechaPago.replace(/(\d{2})(\d{2})(\d{2})/g, '20$1-$2-$3'),
                                        "medioPago":dato.formaPago,
                                        "importe":dato.importe
                                    }
                                }else{
                                    //Pago Directo
                                    var info = {
                                        "id":dato.idCliente,
                                        "transaccion":dato.refUnivoca,
                                        "fechaPago":dato.fechaCobro.replace(/(\d{4})(\d{2})(\d{2})/g, '$1-$2-$3'),
                                        "medioPago":dato.tReg,
                                        "importe":dato.importeCobrado
                                    }
                                }
                                return info;
                            })
                            return datos;
                        })
                        return objeto;
                    },
        },
        //Agregando estilos de acuerdo si fue debitado o rechazado.
        //Sumando el total de los debitos, creditos y efectivo.
        //Contando cada uno el total.
        "createdRow": function(row, data, dataIndex, cells) {
            if (data.medioPago == "Debitos Efectuados"){
                $(row).addClass('transDebitado success');
                debito += data.importe;
                cantDebito++;
            }else if(data.medioPago == "Efectivo"){
                $(row).addClass('transDebitado success');
                efectivo += data.importe;
                cantEfectivo++;
            }else if(data.medioPago == "Tarjeta Debito"){
                $(row).addClass('transDebitado success');
                debito += data.importe;
                cantDebito++;
            }else if(data.medioPago == "Tarjeta Credito"){
                $(row).addClass('transDebitado success');
                credito += data.importe;
                cantCredito++;
            }else{
                $(row).addClass('transFallido danger');
            }
        },
        //Asignado a cada columna un dato
        "columns": [
            { "data":"id" },
            { "data":"transaccion" },
            { "data":"fechaPago" },
            { "data":"medioPago" },
            { "data":"importe" }
        ],
        //Una vez completado el cargado de los datos realizar los calculos
        "initComplete": function( settings, json ) {
            //Habilitar Boton para mostrar las estadisticas una vez finalizado el cargado de los datos
            $('#btnModal').prop('disabled',false)
            //Calculo de los valores del punto 3
            var totalRegistros = cantCredito+cantDebito+cantEfectivo
            var totalCobrado = Math.round(debito+credito+efectivo);
            var promCredito = verifyCeroProm(credito,cantCredito)
            var promDebito = verifyCeroProm(debito,cantDebito);
            var promEfectivo = verifyCeroProm(efectivo,cantEfectivo);
            visualizarDatos = `<table class="table table-striped">
                                    <tr>
                                        <th>Descripcion</th>
                                        <th>Valor</th>
                                    </tr>
                                    </tr>
                                        <td>Total Registros Cobrados:</td>
                                        <td><b>${totalRegistros}</b></td>
                                    </tr>
                                    <tr>
                                        <td>Importe Total Cobrado:</td>
                                        <td><b>${totalCobrado}$</b></td>
                                    </tr>
                                    <tr class="danger">
                                        <td>Promedio Debito:</td>
                                        <td><b>${promDebito}$</b></td>
                                    </tr>
                                    <tr class="success">
                                        <td>Promedio Efectivo:</td>
                                        <td><b>${promEfectivo}$</b></td>
                                    </tr>
                                    <tr class="warning">
                                        <td>Promedio Credito:</td>
                                        <td><b>${promCredito}$</b></td>
                                    </tr>
                                    </table>`;
        },
        //Traduccion al español de la tabla
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "sInfoThousands": ",",
            "sLoadingRecords": "<img style='max-width: 50%;height: auto;' src='assets/images/cargando.gif'><h3>Cargando...</h3>",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "buttons": {
                "copy": "Copiar",
                "colvis": "Visibilidad"
            }
        }
    })
}