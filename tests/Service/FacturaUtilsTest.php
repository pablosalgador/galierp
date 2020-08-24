<?php

namespace App\Tests\Service;

use App\Service\FacturaUtils;
use PHPUnit\Framework\TestCase;

class FacturaUtilsTest extends TestCase
{

  /**
   * @dataProvider provideFechas
   */
  public function testFechaInicioTrimestre($fecha)
  {
    $facturaUtils = new FacturaUtils();
    $fcalc = $facturaUtils->fechaInicioTrimestre($fecha["trimestre"],$fecha["ano"]);
    $this->assertEquals($fecha["fecha_inicio"],$fcalc);
  }

  /**
   * @dataProvider provideFechas
   */
  public function testFechaFinTrimestre($fecha)
  {
    $facturaUtils = new FacturaUtils();
    $fcalc = $facturaUtils->fechaFinTrimestre($fecha["trimestre"],$fecha["ano"]);
    $this->assertEquals($fecha["fecha_fin"],$fcalc);
    $this->assertEquals(1,1);
  }


    public function provideFechas()
    {
      return[
        [["trimestre"=>1,"ano"=>2020,"fecha_inicio"=>new \DateTime('2020-01-01'),"fecha_fin"=>new \DateTime('2020-03-31')]],
        [["trimestre"=>2,"ano"=>2015,"fecha_inicio"=>new \DateTime('2015-04-01'),"fecha_fin"=>new \DateTime('2015-06-30')]],
        [["trimestre"=>3,"ano"=>2019,"fecha_inicio"=>new \DateTime('2019-07-01'),"fecha_fin"=>new \DateTime('2019-09-30')]],
        [["trimestre"=>4,"ano"=>2020,"fecha_inicio"=>new \DateTime('2020-10-01'),"fecha_fin"=>new \DateTime('2020-12-31')]],
        [["trimestre"=>5,"ano"=>2020,"fecha_inicio"=>new \DateTime('2020-01-01'),"fecha_fin"=>new \DateTime('2020-12-31')]],
        [["trimestre"=>6,"ano"=>2020,"fecha_inicio"=>new \DateTime('2020-01-01'),"fecha_fin"=>new \DateTime('2020-12-31')]]
      ];

    }

}
