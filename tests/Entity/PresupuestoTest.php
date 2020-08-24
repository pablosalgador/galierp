<?php

namespace App\Tests\Entity;

use App\Entity\Presupuesto;
use App\Entity\LineaPresupuesto;
use App\Entity\OportunidadVenta;
use App\Entity\Empresa;

use PHPUnit\Framework\TestCase;

class PresupuestoTest extends TestCase
{

  /**
   * @dataProvider providePresupuestos
   */
  public function testGetSubtotal($presupuesto)
  {
    $pre = new Presupuesto();
    foreach($presupuesto["lineas"] as $linea){
      $lin = new LineaPresupuesto();
      $lin->setCantidad($linea["cantidad"]);
      $lin->setPrecio($linea["precio"]);
      $lin->setIva($linea["iva"]);
      $lin->setConcepto($linea["concepto"]);
      $lin->setDescuento($linea["descuento"]);
      $pre->addLinea($lin);
    }
    $this->assertEquals($pre->getSubtotal(),$presupuesto["subtotal"]);
  }

  /**
   * @dataProvider providePresupuestos
   */
  public function testGetIva($presupuesto)
  {
    $pre = new Presupuesto();
    foreach($presupuesto["lineas"] as $linea){
      $lin = new LineaPresupuesto();
      $lin->setCantidad($linea["cantidad"]);
      $lin->setPrecio($linea["precio"]);
      $lin->setIva($linea["iva"]);
      $lin->setConcepto($linea["concepto"]);
      $lin->setDescuento($linea["descuento"]);
      $pre->addLinea($lin);
    }
    $this->assertEquals($pre->getIva(),$presupuesto["iva"]);
  }

  /**
   * @dataProvider providePresupuestos
   */
  public function testGetTotal($presupuesto)
  {
    $pre = new Presupuesto();
    foreach($presupuesto["lineas"] as $linea){
      $lin = new LineaPresupuesto();
      $lin->setCantidad($linea["cantidad"]);
      $lin->setPrecio($linea["precio"]);
      $lin->setIva($linea["iva"]);
      $lin->setConcepto($linea["concepto"]);
      $lin->setDescuento($linea["descuento"]);
      $pre->addLinea($lin);
    }
    $this->assertEquals($pre->getTotal(),$presupuesto["total"]);
  }

  public function testSetOportunidadVenta()
  {
    $oportunidad = new OportunidadVenta();
    $presupuesto = new Presupuesto();

    $presupuesto->setOportunidadVenta($oportunidad);
    $this->assertEquals($oportunidad, $presupuesto->getOportunidadVenta());

  }

  public function testUnsetOportunidadVenta()
  {
    $oportunidad = new OportunidadVenta();
    $presupuesto = new Presupuesto();
    $presupuesto->setOportunidadVenta($oportunidad);
    //Comprobación en test superior
    //$this->assertEquals($oportunidad->getPresupuesto(), $presupuesto);//Comprobamos que setee bien

    $presupuesto->setOportunidadVenta(null);
    $this->assertNull($oportunidad->getPresupuesto());
  }

  public function providePresupuestos()
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
