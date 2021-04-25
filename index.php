<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/index.css">
    <link rel="stylesheet" id="bootstrap-css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/jquery.dataTables.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <title>challengeCobroDigital</title>
</head>
<body>
<div class="container-fluid">
    <div class="row content">
        <div class="col-sm-12">
            <div class="text-center">
                <h2>Procesamiento de Datos</h2>
                <div class="" style="width:100%">
                    <button class="btn btn-primary" id="btnModal" onclick="mostrarEstadisticas()" disabled>Estadisticas</button>
                    <button class="btn btn-warning" onclick="mostrarAyuda()">Ayuda</button>
                </div>
                <table id="tablaDatos" class="display table" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nro Transacción</th>
                            <th>Feach de Pago</th>
                            <th>Medio de Pago</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles -->
<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="detalles_modal">
    <div style="width:50rem" id="divModalVista" class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div id="detalles_cuerpo" class="modal-body">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div id="ayudaContenido" hidden>
    <ul>
        <li>Dentro de este proyecto se utiliza <b>PHP,Javascript,Jquery,Boostrap,DataTables</b></li>
        <li>Los archivos para procesar se encuentran en <b>/assets/archivos/</b></li>
        <li>Son procesados por el archivo <b>/assets/php/acciones.php</b></li>
        <li>Una vez procesados son enviados como un json</li>
        <li>En la tabla se visualiza <b>id</b>, <b>nro de transaccion</b>, <b>fecha de pago</b>, <b>metodo de pago</b> y el <b>importe</b></li>
        <li>El boton <b>estadistica</b> es el que permite ver los valores procesados del <b>punto 3</b></li>
        <li>Valores en <span class="label label-danger">Rojo</span> son los que fueron <b>rechazados o no fueron acreditados</b></p></li>
        <li>Valores en <span class="label label-success">Verde</span> son los que fueron <b>aceptados</b></li>
        <li>Valores <b>procesados</b> son: <ul>
                <li><span class="label label-primary">Total Registros Cobrados</span></li>
                <li><span class="label label-primary">Total Importe Cobrado</span></li>
                <li><span class="label label-primary">Promedio de Transaccion por Forma de Pago</span></li>
            </ul>
        </li>
    </ul>
</div>

</body>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.dataTables.js"></script>
    <script src="assets/js/index.js"></script>
</html>