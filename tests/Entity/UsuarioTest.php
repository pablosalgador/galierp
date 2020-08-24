<?php

namespace App\Tests\Entity;

use App\Entity\Usuario;
use App\Entity\OportunidadVenta;
use PHPUnit\Framework\TestCase;

class UsuarioTest extends TestCase
{
  public function testCreaUsuario()
  {
    $usuario = new Usuario();
    $usuario->setEmail('pablo.salgado@rai.usc.es');
    $usuario->setNombre('Pablo');
    $usuario->setApellidos('Salgado Roo');

    $this->assertEquals($usuario->getUsername(),'pablo.salgado@rai.usc.es');//El username debe ser el email
    $this->assertEquals($usuario->getEmail(),'pablo.salgado@rai.usc.es');
    $this->assertEquals($usuario->getNombre(),'Pablo');
    $this->assertEquals($usuario->getApellidos(),'Salgado Roo');

  }

  public function testUsuarioOportunidadVenta()
  {
    $usuario = new Usuario();
    $usuario->setNombre('Pablo');
    $usuario->setEmail('pablo.salgado@rai.usc.es');
    $oportunidad = new OportunidadVenta();
    $oportunidad->setNombre('Nombre oportunidad');
    $usuario->addOportunidadesVenta($oportunidad);

    $this->assertArraySubset([$oportunidad],$usuario->getOportunidadesVentas());
    $this->assertEquals($usuario, $oportunidad->getResponsable());

  }


}
