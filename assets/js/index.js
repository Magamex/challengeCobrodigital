var jsonArchivos;

$(document).ready(function() {
    mostrarDatos();
} );

function mostrarDatos(){
    // $.ajax({
    //     type: "post",
    //     url: "assets/php/acciones.php",
    //     data: {"accion":"todosArchivos"},
    //     success: function (response) {
    //         jsonArchivos = JSON.parse(response);
    //         console.log(jsonArchivos);
    //         $.each(jsonArchivos, function (indice, valor) {
    //             console.log(valor);
    //         });
    //     }
    // });

    var tabla = $('#tablaDatos').DataTable( {
        "destroy": true,
        "ajax": {
            "url":"assets/php/acciones.php",
            "type": "POST",
            "data": {"accion":"todosArchivos"},
            "dataSrc": function (json) {
                        var objeto = $.map(json, function(objeto, i) {
                            var datos=$.map(objeto.body, function(dato, indice) {
                                if(dato.codServicio){
                                    var info = {
                                        "id":dato.codServicio,
                                        "transaccion":dato.transaccion,
                                        "fechaPago": dato.fechaPago.replace(/(\d{2})(\d{2})(\d{2})/g, '20$1-$2-$3'),
                                        "medioPago":dato.formaPago,
                                        "importe":dato.importe
                                    }
                                }else{
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
                        return objeto
                    },
        },
        "columns": [
            { "data":"id" },
            { "data":"transaccion" },
            { "data":"fechaPago" },
            { "data":"medioPago" },
            { "data":"importe" },

            // { targets: 1, data: "body.tReg"}
            // {
            //     "targets": 1,
            //     "data": null,
            //     "className": "center",
            //     "render": function ( data, type, full, meta ) {
            //         return data.body.tReg
            //     }
            // }
        ]
        })
}