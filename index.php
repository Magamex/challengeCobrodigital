<?php


//Pago Directo
$url = "assets/archivos/REND.COB-COBC8496.COB-20191125.TXT";
// $url = "assets/archivos/REND.REV-REVC8496.REV-20191125.TXT";

//Pago Plus
//$url = "assets/archivos/888ENTES5723_308.txt";

//$nomArchivo = basename('assets/archivos/REND.REV-REVC8496.REV-20191125.TXT');

/**
 * Funcion que devuelve la descripcion del tipo de Documento
 * @param string $codigo
 * @return string
 */
function tipoDocumento($codigo){
    $tipo = array(
        '0000' => 'C.l. Policia Federal',
        '0001' => 'C.l. Buenos Aires',
        '0002' => 'C.l. Catamarca',
        '0003' => 'C.l. Cordoba',
        '0004' => 'C.l. Corrientes',
        '0005' => 'C.l. Entre Rios',
        '0006' => 'C.l. Jujuy',
        '0007' => 'C.l. Mendoza',
        '0008' => 'C.l. La Rioja',
        '0009' => 'C.l. Salta',
        '0010' => 'C.l. San Juan',
        '0011' => 'C.l. San Luis',
        '0012' => 'C.l. Santa Fe',
        '0013' => 'C.l. Stgo del Estero',
        '0014' => 'C.l. Tucuman',
        '0016' => 'C.l. Chaco',
        '0017' => 'C.l. Chubut',
        '0018' => 'C.l. Formosa',
        '0019' => 'C.l. Misiones',
        '0020' => 'C.l. Neuquen',
        '0021' => 'C.l. La Pampa',
        '0022' => 'C.l. Rio Negro',
        '0023' => 'C.l. Santa Cruz',
        '0024' => 'C.l. Tierra del Fuego',
        '0080' => 'C.U.I.T',
        '0086' => 'C.U.I.L',
        '0089' => 'L.E',
        '0090' => 'L.C',
        '0094' => 'Pasaporte',
        '0096' => 'D.N.I'
    );
    return (isset($tipo[$codigo]))?$tipo[$codigo]:$codigo;
}

/**
 * Permite visualizar la descripcion de acuerdo al tipo de registro
 * @param string $codigo //Codigo de tipo de registro
 * @param string $tipo //BANCO = Banco->Empresa || EMPRESA = Empresa->Banco
 * @return string
 */
function tipoRegistro($codigo,$origen){
    if($origen == 'EMPRESA'){
        //EMPRESA -> BANCO
        $reg = array(
            '0370' => 'Orden de Debito enviada por la empresa',
            '0320' => 'Reversion/anulacion de debito',
            '0361' => 'Rechazo de revision',
            '0382' => 'Adhesion',
            '0363' => 'Rechazo de adhesion',
            '0385' => 'Cambio de identificacion'
        );
    }else{
        //BANCO -> EMPRESA
        $reg = array(
            '0370' => 'Debitos Efectuados - Rendicion Cobranza',
            '0360' => 'Debitos Rechazados - Rendicion Cobranza',
            '0371' => 'Reversion Solicitada en el Banco de la cuenta',
            '0310' => 'Rechazo de reversion solicitada en el banco',
            '0383' => 'Alta de adhesion efectuada en el banco',
            '0362' => 'Rechazo de adhesion',
            '0384' => 'Baja de adhesion',
            '0365' => 'Rechazo de la modificacion de identificacion',
            '0386' => 'Modificacion de la cuenta debito'
        );
    }
    return (isset($reg[$codigo]))?$reg[$codigo]:$codigo;
}

/**
 * Visualiza la descripcion del motivo de rechazo
 *
 * @param string $codigo
 * @param string $tipo
 * @return string
 */
function motivoRechazo($codigo,$origen){
    if($origen == 'EMPRESA'){
        //Empresa->Banco
        $motivo = array(
            'R17' => 'Rechazo de Reversion (0361)',
            'R89' => 'Rechazo de Adhesion (0363)',
        );
    }else{
        //Banco->Empresa
        $motivo = array(
            'R02' => 'Cuenta cerrada o suspendida',
            'R03' => 'Cuenta inexistente',
            'R04' => 'Numero de cuenta invalido',
            'R05' => 'Orden de diferimiento',
            'R06' => 'Defectos formales',
            'R07' => 'Solicitud de la entidad originante',
            'R08' => 'Orden de no pagar',
            'R10' => 'Falta de fondos',
            'R13' => 'Entidad destino inexistente',
            'R14' => 'Identificacion del cliente de la empresa erronea',
            'R15' => 'Baja de servicio',
            'R17' => 'Error de formato',
            'R19' => 'Importe erroneo',
            'R20' => 'Moneda distinta a la cuenta de debito',
            'R23' => 'Sucursal no habilitada',
            'R24' => 'Transaccion duplicada',
            'R25' => 'Error en registro adicional',
            'R26' => 'Error por campo mandatario',
            'R28' => 'Rechazo por vencimiento',
            'R29' => 'Reversion ya efectuada',
            'R75' => 'Fecha invalida',
            'R81' => 'Fuerza mayor',
            'R87' => 'Moneda invalida',
            'R89' => 'Errores en adhesiones',
            'R91' => 'Codigo de Banco incompatible con moneda de transaccion',
            'R95' => 'Reversion receptora presentada fuera de termino'
        );
    }
    return (isset($motivo[$codigo]))?$motivo[$codigo]:$codigo;
}

/**
 * Funcion que procesa los archivos de pagoDirecto
 * @param string $url
 * @return array
 */
function pagoDirecto($url){
    $archivo = fopen($url, "r");
    if($archivo){
        $indiceDetalle = 0;
        $registros = [];
        while (($linea = fgets($archivo)) !== false){
            if(substr($linea,0,4) == '0000'){
                //Cabecera
                $registros['header']['tReg'] = substr($linea,0,4);              //Tipo de Registro
                $registros['header']['nroPres'] = (int)substr($linea,4,4);      //Nro de Prestacion
                $registros['header']['servicio'] = substr($linea,8,1);          //Servicio
                $registros['header']['fechaGen'] = (int)substr($linea,9,8);     //Fecha de Generacion
                $registros['header']['idArchivo'] = substr($linea,17,1);        //Identificacion de Archivo
                $registros['header']['origen'] = trim(substr($linea,18));       //Origen
            }else if(substr($linea,0,4) == '9999'){
                //Pie
                $registros['footer']['tReg'] = substr($linea,0,4);              //Tipo de Registro
                $registros['footer']['nroPres'] = (int)substr($linea,4,4);      //Nro de Prestacion
                $registros['footer']['servicio'] = substr($linea,8,1);          //Servicio
                $registros['footer']['fechaGen'] = (int)substr($linea,9,8);     //Fecha de Generacion
                $registros['footer']['idArchivo'] = substr($linea,17,1);        //Identificacion de Archivo
                $registros['footer']['origen'] = trim(substr($linea,18,7));     //Origen
                $registros['footer']['importeTotal'] = substr($linea,25,14);    //!Importe total
                $registros['footer']['cantReg'] = substr($linea,39,7);          //!Cantidad Registros
            }else{
                //Detalles
                $registros['detalle'][$indiceDetalle]['tReg'] = tipoRegistro(substr($linea,0,4),$registros['header']['origen']);    //Tipo de Registro
                $registros['detalle'][$indiceDetalle]['idCliente'] = trim(substr($linea,4,22));                                     //*Identificador del Cliente
                $registros['detalle'][$indiceDetalle]['CBU'] = trim(substr($linea,26,26));                                          //CBU
                $registros['detalle'][$indiceDetalle]['refUnivoca'] = trim(substr($linea,52,15));                                   //*Referencia Univoca - tabla
                $registros['detalle'][$indiceDetalle]['1erVenc'] = trim(substr($linea,67,8));                                       //1er Vencimiento
                $registros['detalle'][$indiceDetalle]['1erImporte'] = trim((string)substr($linea,75,12).','.substr($linea,87,2));   //1er Importe
                $registros['detalle'][$indiceDetalle]['2doVenc'] = trim(substr($linea,89,8));                                       //2do Vencimiento
                $registros['detalle'][$indiceDetalle]['2doImporte'] = trim((string)substr($linea,98,12).','.substr($linea,110,2));  //2do Importe
                $registros['detalle'][$indiceDetalle]['3erVenc'] = trim(substr($linea,112,8));                                      //3er Vencimiento
                $registros['detalle'][$indiceDetalle]['3erImporte'] = trim((string)substr($linea,120,12).','.substr($linea,132,2)); //3er Importe
                $registros['detalle'][$indiceDetalle]['moneda'] = trim(substr($linea,133,1));                                       //Moneda de Factura
                $registros['detalle'][$indiceDetalle]['motivoRechazo'] = motivoRechazo(trim(substr($linea,134,3)),$registros['header']['origen']);  //Motivo de Rechazo
                $registros['detalle'][$indiceDetalle]['tipoDoc'] = trim(substr($linea,137,4));                                      //Tipo Documento
                $registros['detalle'][$indiceDetalle]['nroDoc'] = trim(substr($linea,141,11));                                      //Nro Documento
                $registros['detalle'][$indiceDetalle]['newIdCliente'] = trim(substr($linea,152,22));                                //Nuevo ID Cliente
                $registros['detalle'][$indiceDetalle]['newCBU'] = trim(substr($linea,174,26));                                      //Nuevo CBU
                $registros['detalle'][$indiceDetalle]['importeMin'] = trim(substr($linea,200,14));                                  //Importe Minimo
                $registros['detalle'][$indiceDetalle]['fechaProxVec'] = trim(substr($linea,214,8));                                 //Fecha de Proximo Vencimiento
                $registros['detalle'][$indiceDetalle]['idClienteAnt'] = trim(substr($linea,222,22));                                //ID cliente Anterior
                $registros['detalle'][$indiceDetalle]['mensajeATM'] = trim(substr($linea,244,40));                                  //Mensaje ATM
                $registros['detalle'][$indiceDetalle]['concepFact'] = trim(substr($linea,284,10));                                  //Concepto de Factura
                $registros['detalle'][$indiceDetalle]['fechaCobro'] = trim(substr($linea,294,8));                                   //*Fecha de Cobro
                $registros['detalle'][$indiceDetalle]['importeCobrado'] = trim(substr($linea,302,12).','.substr($linea,314,2));     //Importe Cobrado
                $registros['detalle'][$indiceDetalle]['fechaAcred'] = trim(substr($linea,294,8));                                   //Fecha de Acreditamiento
            }
            $indiceDetalle++;
        }
    }
    else{
        echo "Archivo no encontrado";
    }
    return $registros;
}

/**
 * Muestra descripcion del codigo de operacion
 * @param string  $codigo
 * @return string
 */
function codOperacion($codigo){
    $operacion = array(
        'A2' => 'Cheque v.imp',
        'A3' => 'Efectivo',
        'A5' => 'Cheque comun'
    );
    return (isset($operacion[$codigo]))?$operacion[$codigo]:$codigo;
}

/**
 * Muestra descripcion del codigo de moneda
 * @param string $codigo
 * @return string
 */
function tipoMoneda($codigo){
    $moneda = array(
        '0' => 'Pesos',
        '1' => 'Dolares'
    );
    return (isset($moneda[$codigo]))?$moneda[$codigo]:$codigo;
}

function formaPago($codigo){
    $pago = array(
        '00' => 'Efectivo',
        '90' => 'Tarjeta Debito',
        '99' => 'Tarjeta Credito'
    );
    return (isset($pago[$codigo]))?$pago[$codigo]:$codigo;
}

/**
 * Funcion que procesa los archivos de pagoPlus
 * @param string $url
 * @return array
 */
function pagoPlus($url){
    $archivo = fopen($url, "r");
    if($archivo){
        $indiceDetalle = 0;
        $registros = [];
        while (($linea = fgets($archivo)) !== false){
            if(substr($linea,0,6) == 'HEADER'){
                //Cabecera
                $registros['header']['tReg'] = substr($linea,0,6);              //Tipo de Registro
                $registros['header']['codigoBCRA'] = substr($linea,6,3);        //Codigo BCRA
                $registros['header']['fechaProceso'] = substr($linea,9,8);      //Fecha de Proceso
                $registros['header']['fechaAplanado'] = substr($linea,17,8);    //Fecha Aplanado
                $registros['header']['nroLote'] = substr($linea,25,5);          //Nro de Lote
            }else if(substr($linea,0,7) == 'TRAILER'){
                //Pie
                $registros['footer']['tReg'] = substr($linea,0,7);              //Tipo de Registro
                $registros['footer']['cantReg'] = substr($linea,7,8);           //!Cantidad de Registros
                $registros['footer']['importe'] = substr($linea,15,13);         //!Importe Total
                $registros['footer']['cantTRX'] = substr($linea,28,8);          //Cantidad de TRX
            }else{
                //Detalles
                $registros['detalles'][$indiceDetalle]['tReg'] = trim(substr($linea,0,8));  //Tipo de Registro
                $registros['detalles'][$indiceDetalle]['codBCRA'] = substr($linea,8,4);     //Codigo ante BCRA
                $registros['detalles'][$indiceDetalle]['R'] = substr($linea,12,1);          //R
                $registros['detalles'][$indiceDetalle]['codTerminal'] = substr($linea,13,5);//Codigo Terminal
                $registros['detalles'][$indiceDetalle]['ParsubCod'] = substr($linea,18,10); //ParsubCod
                $registros['detalles'][$indiceDetalle]['codBoca'] = substr($linea,28,4);    //Codigo Boca
                $registros['detalles'][$indiceDetalle]['nroSecuencia'] = substr($linea,32,8);//Nro SecuenciaOn
                $registros['detalles'][$indiceDetalle]['transaccion'] = substr($linea,40,8);//*Transaccion - tabla
                $registros['detalles'][$indiceDetalle]['codOperacion'] = substr($linea,48,2);//Codigo Operacion
                $registros['detalles'][$indiceDetalle]['codEnte'] = substr($linea,54,4);    //Codigo Ente
                $registros['detalles'][$indiceDetalle]['codServicio'] = trim(substr($linea,58,19));//*Codigo de Servicio/Identificacion - tabla
                $registros['detalles'][$indiceDetalle]['importe'] = substr($linea,77,12);   //*Importe de Transaccion - tabla
                $registros['detalles'][$indiceDetalle]['moneda'] = substr($linea,110,1);    //0=Pesos 1=Dolares
                $registros['detalles'][$indiceDetalle]['codCajero'] = substr($linea,111,4); //Codigo de Cajero
                $registros['detalles'][$indiceDetalle]['codSeguridad'] = substr($linea,120,3); //Codigo de Seguridad
                $registros['detalles'][$indiceDetalle]['1erVenc'] = substr($linea,123,6); //Fecha de Vencimiento
                $registros['detalles'][$indiceDetalle]['bancoCheque'] = substr($linea,135,3); //Banco del Cheque
                $registros['detalles'][$indiceDetalle]['sucursal'] = substr($linea,138,3); //Sucursal del Cheque
                $registros['detalles'][$indiceDetalle]['codPostal'] = substr($linea,141,4); //Codigo Postal
                $registros['detalles'][$indiceDetalle]['nroCheque'] = substr($linea,145,8); //Numero de Cheque
                $registros['detalles'][$indiceDetalle]['plazo'] = substr($linea,161,3); //Plazo de Cheque
                $registros['detalles'][$indiceDetalle]['codBarra'] = trim(substr($linea,164,60)); //Codigo de Barra
                $registros['detalles'][$indiceDetalle]['fechaPago'] = substr($linea,224,6); //*Fecha de Pago - tabla
                $registros['detalles'][$indiceDetalle]['modoPago'] = '';                    //Modo de Pago
                $registros['detalles'][$indiceDetalle]['formaPago'] = substr($linea,247,2); //*Forma de Pago - tabla
                $registros['detalles'][$indiceDetalle]['autorizacion'] = trim(substr($linea,256,15));   //Autorizacion
            }
            $indiceDetalle++;
        }
    }else{
        echo "Archivo no encontrado";
    }
    return $registros;
}

//Pago directo
var_dump(pagoDirecto($url));

//Pago Plus
// var_dump(pagoPlus($url));



?>