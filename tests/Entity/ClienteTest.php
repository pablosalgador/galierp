<?php

namespace App\Tests\Entity;

use App\Entity\Cliente;

use PHPUnit\Framework\TestCase;

class ClienteTest extends TestCase
{

  public function testCreaCliente()
  {
    $cliente = new Cliente();
    $cliente->setNif('11111111A');
    $cliente->setNombreComercial('Cliente 1');
    $cliente->setRazonSocial("Razon Social - Cliente 1");
    $cliente->setEmail("cliente@cliente.com");

    $this->assertEquals('11111111A',$cliente->getNif());
    $this->assertEquals('Cliente 1',$cliente->getNombreComercial());
    $this->assertEquals('Razon Social - Cliente 1',$cliente->getRazonSocial());
    $this->assertEquals('cliente@cliente.com',$cliente->getEmail());
  }



}
