<?php

namespace App\Tests\Entity;

use App\Entity\Factura;
use App\Entity\LineaFactura;
use App\Entity\OportunidadVenta;
use App\Entity\Empresa;


use PHPUnit\Framework\TestCase;

class FacturaTest extends TestCase
{

  /**
   * @dataProvider provideFacturas
   */
  public function testGetSubtotal($factura)
  {
    $fac = new Factura();
    foreach($factura["lineas"] as $linea){
      $lin = new LineaFactura();
      $lin->setCantidad($linea["cantidad"]);
      $lin->setPrecio($linea["precio"]);
      $lin->setIva($linea["iva"]);
      $lin->setConcepto($linea["concepto"]);
      $lin->setDescuento($linea["descuento"]);
      $fac->addLinea($lin);
    }
    $this->assertEquals($fac->getSubtotal(),$factura["subtotal"]);
  }

  /**
   * @dataProvider provideFacturas
   */
  public function testGetIva($factura)
  {
    $fac = new Factura();
    foreach($factura["lineas"] as $linea){
      $lin = new LineaFactura();
      $lin->setCantidad($linea["cantidad"]);
      $lin->setPrecio($linea["precio"]);
      $lin->setIva($linea["iva"]);
      $lin->setConcepto($linea["concepto"]);
      $lin->setDescuento($linea["descuento"]);
      $fac->addLinea($lin);
    }
    $this->assertEquals($fac->getIva(),$factura["iva"]);
  }

  /**
   * @dataProvider provideFacturas
   */
  public function testGetTotal($factura)
  {
    $fac = new Factura();
    foreach($factura["lineas"] as $linea){
      $lin = new LineaFactura();
      $lin->setCantidad($linea["cantidad"]);
      $lin->setPrecio($linea["precio"]);
      $lin->setIva($linea["iva"]);
      $lin->setConcepto($linea["concepto"]);
      $lin->setDescuento($linea["descuento"]);
      $fac->addLinea($lin);
    }
    $this->assertEquals($fac->getTotal(),$factura["total"]);
  }

  public function testSetOportunidadVenta()
  {
    $oportunidad = new OportunidadVenta();
    $factura = new Factura();

    $factura->setOportunidadVenta($oportunidad);
    $this->assertEquals($oportunidad, $factura->getOportunidadVenta());

  }

  public function testUnsetOportunidadVenta()
  {
    $oportunidad = new OportunidadVenta();
    $factura = new Factura();
    $factura->setOportunidadVenta($oportunidad);
    //Comprobación en test superior
    //$this->assertEquals($oportunidad->getFactura(), $factura);//Comprobamos que setee bien

    $factura->setOportunidadVenta(null);
    $this->assertNull($oportunidad->getFactura());
  }

  public function provideFacturas()
  {

    return [
    /*  [["lineas"=>array(
        ["cantidad"=>1,"precio"=>10.95,"iva"=>0.21,"concepto"=>"Producto PR001","descuento"=>0],
        ["cantidad"=>10,"precio"=>1.25,"iva"=>0.8,"concepto"=>"Producto PR002","descuento"=>0],
        ["cantidad"=>5,"precio"=>5.5,"iva"=>0.4,"concepto"=>"Producto PR003","descuento"=>0],
        ["cantidad"=>1,"precio"=>120,"iva"=>0.21,"concepto"=>"Producto PR004","descuento"=>20],
      ),"subtotal"=>150.95,"iva"=>44.2995,"total"=>195.2495]],*/
    [["lineas"=>array(
        ["cantidad"=>1,"precio"=>10,"iva"=>0.21,"concepto"=>"Producto PR001","descuento"=>0],
      ),"subtotal"=>10,"iva"=>2.1,"total"=>12.1]]
    ];

  }



}
