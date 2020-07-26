<?php

namespace App\Service;

use App\Entity\Presupuesto;
use App\Entity\Factura;
use App\Entity\LineaFactura;

class FacturaUtils{

  public function siguienteNumeroFactura($numero_factura)
  {
    $nuevo_numero;
    if($numero_factura!=null && $numero_factura["numero"]!=null){
      $nuevo_numero = $numero_factura["numero"] + 1;
    }else{
      $nuevo_numero = 1;
    }
    return $nuevo_numero;
  }

  public function facturaDesdePresupuesto($presupuesto)
  {
      $factura = new Factura();
      foreach($presupuesto->getLineas() as $lineaPresu){
        $linea = new LineaFactura();
        $linea->setCantidad($lineaPresu->getCantidad());
        $linea->setConcepto($lineaPresu->getConcepto());
        $linea->setPrecio($lineaPresu->getPrecio());
        $linea->setDescuento($lineaPresu->getDescuento());
        $linea->setIva($lineaPresu->getIva());
        $factura->addLinea($linea);
      }
      $factura->setEmpresa($presupuesto->getEmpresa());
      $factura->setCliente($presupuesto->getCliente());
      //Solo asignamos la oportunidad si existe y no tiene asociada ya una factura
      if($presupuesto->getOportunidadVenta() && $presupuesto->getOportunidadVenta()->getFactura()==null){
          $factura->setOportunidadVenta($presupuesto->getOportunidadVenta());
      }
      return $factura;
  }

  public function generaDatosModelos($facturas, $gastos, $ano, $trimestre)
  {
    $res['subtotal_trimestre']=0;
    $res['iva_trimestre']=0;
    $res['subtotal_acumulado']=0;
    $res['iva_acumulado']=0;
    $res['gastos_trimestre']=0;
    $res['iva_gastos_trimestre']=0;
    $res['gastos_acumulado']=0;
    $res['iva_gastos_acumulado']=0;
    $res['irpf_trimestre']=0;
    $res['irpf_acumulado']=0;

    $fecha_inicio_trimestre = $this->fechaInicioTrimestre($trimestre, $ano);
    $fecha_inicio_ano = $this->fechaInicioTrimestre(1,$ano);
    $fecha_fin = $this->fechaFinTrimestre($trimestre, $ano);

    foreach($facturas as $factura){
      if($factura->getFechaEmision()>=$fecha_inicio_ano && $factura->getFechaEmision()<=$fecha_fin)
      {
        if($factura->getFechaEmision()>=$fecha_inicio_trimestre)
        {
          $res['subtotal_trimestre']+=$factura->getSubtotal();
          $res['iva_trimestre']+=$factura->getIva();
          $res['irpf_trimestre']+=($factura->getSubtotal()*$factura->getIrpf());
        }
        $res['subtotal_acumulado']+=$factura->getSubtotal();
        $res['iva_acumulado']+=$factura->getIva();
        $res['irpf_acumulado']+=($factura->getSubtotal()*$factura->getIrpf());
      }
    }

    foreach($gastos as $gasto){
      if($gasto->getFecha()>=$fecha_inicio_ano && $gasto->getFecha()<=$fecha_fin)
      {
        if($gasto->getFecha()>=$fecha_inicio_trimestre)
        {
          $res['gastos_trimestre']+=$gasto->getBase();
          $res['iva_gastos_trimestre']+=$gasto->getIva();
        }
        $res['gastos_acumulado']+=$gasto->getBase();
        $res['iva_gastos_acumulado']+=$gasto->getIva();
      }
    }
    return $res;
  }


  private function fechaInicioTrimestre($trimestre, $ano)
  {
    switch($trimestre)
    {
      case 1:
        return new \DateTime($ano . '-01-01');
        break;
      case 2:
        return new \DateTime($ano . '-04-01');
        break;
      case 3:
        return new \DateTime($ano . '-07-01');
        break;
      case 4:
        return new \DateTime($ano . '-10-01');
        break;
    }
    return new \DateTime($ano . '-01-01');
  }

  private function fechaFinTrimestre($trimestre, $ano)
  {
    switch($trimestre)
    {
      case 1:
        return new \DateTime($ano . '-03-31');
        break;
      case 2:
        return new \DateTime($ano . '-06-30');
        break;
      case 3:
        return new \DateTime($ano . '-09-30');
        break;
      case 4:
        return new \DateTime($ano . '-12-31');
        break;
    }
    return new \DateTime($ano . '-12-31');
  }


}
