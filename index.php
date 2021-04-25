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
<div class="container">
    <div class="col-xs-12 col-md-12 col-lg-12 main-content">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h2>Procesamiento de Datos</h2>
                <div>
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

</body>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.dataTables.js"></script>
    <script src="assets/js/index.js"></script>
</html>