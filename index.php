<?php

$archivo = fopen("assets/archivos/REND.COB-COBC8496.COB-20191125.TXT", "r");
//$archivo = fopen("assets/archivos/REND.REV-REVC8496.REV-20191125.TXT", "r");
$nomArchivo = basename('assets/archivos/REND.REV-REVC8496.REV-20191125.TXT');

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
 * Funcion que procesa los archivos de pago directo
 * @param string $archivo
 * @return array
 */
function pagoDirecto($archivo){
    if($archivo){
        $indiceDetalle = 0;
        $registros = [];
        while (($linea = fgets($archivo)) !== false){
            if(substr($linea,0,4) == '0000'){
                $registros['header']['tReg'] = substr($linea,0,4);              //Tipo de Registro
                $registros['header']['nroPres'] = (int)substr($linea,4,4);      //Nro de Prestacion
                $registros['header']['servicio'] = substr($linea,8,1);          //Servicio
                $registros['header']['fechaGen'] = (int)substr($linea,9,8);     //Fecha de Generacion
                $registros['header']['idArchivo'] = substr($linea,17,1);        //Identificacion de Archivo
                $registros['header']['origen'] = trim(substr($linea,18));       //Origen
            }else if(substr($linea,0,4) == '9999'){
                $registros['footer']['tReg'] = substr($linea,0,4);              //Tipo de Registro
                $registros['footer']['nroPres'] = (int)substr($linea,4,4);      //Nro de Prestacion
                $registros['footer']['servicio'] = substr($linea,8,1);          //Servicio
                $registros['footer']['fechaGen'] = (int)substr($linea,9,8);     //Fecha de Generacion
                $registros['footer']['idArchivo'] = substr($linea,17,1);        //Identificacion de Archivo
                $registros['footer']['origen'] = trim(substr($linea,18,7));     //Origen
                $registros['footer']['importeTotal'] = substr($linea,25,14);    //Importe total
                $registros['footer']['cantReg'] = substr($linea,39,7);          //Cantidad Registros
            }else{
                $registros['detalle'][$indiceDetalle]['tReg'] = tipoRegistro(substr($linea,0,4),$registros['header']['origen']);
                $registros['detalle'][$indiceDetalle]['idCliente'] = trim(substr($linea,4,22));
                $registros['detalle'][$indiceDetalle]['CBU'] = trim(substr($linea,26,26));
                $registros['detalle'][$indiceDetalle]['refUnivoca'] = trim(substr($linea,52,15));
                $registros['detalle'][$indiceDetalle]['1erVenc'] = trim(substr($linea,67,8));
                $registros['detalle'][$indiceDetalle]['1erImporte'] = trim((string)substr($linea,75,12).','.substr($linea,87,2));
                $registros['detalle'][$indiceDetalle]['2doVenc'] = trim(substr($linea,89,8));
                $registros['detalle'][$indiceDetalle]['2doImporte'] = trim((string)substr($linea,98,12).','.substr($linea,110,2));
                $registros['detalle'][$indiceDetalle]['3erVenc'] = trim(substr($linea,112,8));
                $registros['detalle'][$indiceDetalle]['3erImporte'] = trim((string)substr($linea,120,12).','.substr($linea,132,2));
                $registros['detalle'][$indiceDetalle]['motivoRechazo'] = motivoRechazo(trim(substr($linea,134,3)),$registros['header']['origen']);
                $registros['detalle'][$indiceDetalle]['importeMin'] = trim(substr($linea,200,14));
                $registros['detalle'][$indiceDetalle]['fechaProxVec'] = trim(substr($linea,214,8));
                $registros['detalle'][$indiceDetalle]['concepFact'] = trim(substr($linea,284,10));
                $registros['detalle'][$indiceDetalle]['fechaCobro'] = trim(substr($linea,294,8));
                $registros['detalle'][$indiceDetalle]['importeCobrado'] = trim(substr($linea,302,12).','.substr($linea,314,2));
            }
            $indiceDetalle++;
        }
    }
    else{
        echo "Archivo no Disponible";
    }
    return $registros;
}
//Pago directo
var_dump(pagoDirecto($archivo));




?>