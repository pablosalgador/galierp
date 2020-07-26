<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Form\EmpresaFormType;
use App\Form\ProveedorFormType;
use App\Form\FacturaFormType;
use App\Form\GastoFormType;
use App\Form\IngresoFormType;

use App\Entity\Empresa;
use App\Entity\Presupuesto;
use App\Entity\Proveedor;
use App\Entity\Ingreso;
use App\Entity\Gasto;
use App\Entity\Factura;
use App\Entity\OportunidadVenta;


//Servicios
use App\Service\PDFGenerator;
use App\Service\FacturaUtils;

/**
 * @IsGranted("ROLE_FISCAL")
 */
class FiscalController extends AbstractController
{

  /**
  * @Route("/{_locale}/empresas", name="listaempresas")
  */
  public function listaEmpresas()
  {
    $empresa_repository = $this->getDoctrine()->getRepository(Empresa::class);
    $empresas = $empresa_repository->findAll();
    return $this->render('fiscal/empresas.html.twig', array('empresas'=>$empresas));
  }

  /**
  * @Route("/{_locale}/empresa/nueva", name="nuevaempresa")
  */
  public function nuevaEmpresa(Request $request)
  {
    $msg_success=array();
    $empresa_repository = $this->getDoctrine()->getRepository(Empresa::class);
    $empresa=new Empresa();
    $form = $this->createForm(EmpresaFormType::class,$empresa);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($empresa);
      $entityManager->flush();
      $msg_success[]="Se ha registrado correctamente la empresa";
    }
    return $this->render('fiscal/empresanueva.html.twig', [
      "form"=>$form->createView(),
      "msg_success"=>$msg_success
    ]);

  }

  /**
  * @Route("/{_locale}/empresa/{id}", name="vereditarempresa")
  */
  public function vereditarEmpresa($id, Request $request)
  {
    $msg_success=array();
    $empresa_repository = $this->getDoctrine()->getRepository(Empresa::class);
    $empresa = $empresa_repository->findOneById($id);
    if($empresa==null)return $this->redirectToRoute("listaempresas");

    $form = $this->createForm(EmpresaFormType::class,$empresa);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($empresa);
      $entityManager->flush();
      $msg_success[]="Se han actualizado correctamente los datos de la empresa";
    }

    return $this->render('fiscal/empresa.html.twig', [
      "form"=>$form->createView(),
      "msg_success"=>$msg_success
    ]);
  }

  /**
  * @Route("/{_locale}/empresa/{id}/eliminar", name="eliminar_empresa")
  */
  public function eliminarEmpresa($id)
  {

    $empresaRepository = $this->getDoctrine()->getRepository(Empresa::class);
    $empresa = $empresaRepository->findOneById($id);
    if($empresa==null)
    throw $this->createNotFoundException('No exista la empresa con el id especificado');

    //No tiene presupuestos
    if($empresa->getPresupuestos()->count()>0){
      throw $this->createNotFoundException('No se puede eliminar la empresa ya que tiene presupuestos asociados. Elimine primero los presupuestos o asígnelos a otra empresa');
    }

    //No tiene facturas
    if($empresa->getFacturas()->count()>0){
      throw $this->createNotFoundException('No se puede eliminar la empresa ya que tiene facturas asociadas. Elimine primero las facturas');
    }

    //No tiene gastos
    if($empresa->getGastos()->count()>0){
      throw $this->createNotFoundException('No se puede eliminar la empresa ya que tiene gastos asociados. Elimine primero los gastos o asígnelos a otra empresa');
    }

    //TODO: No tiene ingresos

    $em = $this->getDoctrine()->getManager();
    $em->remove($empresa);
    $em->flush();
    return $this->redirectToRoute('listaempresas');


  }

  /**
  * @Route("/{_locale}/proveedores", name="lista_proveedores")
  */
  public function listaProveedores()
  {
    $prov_repository = $this->getDoctrine()->getRepository(Proveedor::class);
    $proveedores = $prov_repository->findAll();
    return $this->render('fiscal/proveedores.html.twig', array('proveedores'=>$proveedores));
  }

  /**
  * @Route("/{_locale}/proveedor/nuevo", name="nuevo_proveedor")
  */
  public function nuevoProveedor(Request $request)
  {
    $msg_success=array();
    $proveedor=new Proveedor();
    $form = $this->createForm(ProveedorFormType::class,$proveedor);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($proveedor);
      $entityManager->flush();
      $msg_success[]="Se ha registrado correctamente la empresa";
    }
    return $this->render('fiscal/proveedornuevo.html.twig', [
      "form"=>$form->createView(),
      "msg_success"=>$msg_success
    ]);

  }

  /**
  * @Route("/{_locale}/proveedor/{id}", name="ver_editar_proveedor")
  */
  public function vereditarProveedor($id, Request $request)
  {
    $msg_success=array();
    $prov_repository = $this->getDoctrine()->getRepository(Proveedor::class);
    $proveedor = $prov_repository->findOneById($id);
    if($proveedor==null)return $this->redirectToRoute("listaproveedores");

    $form = $this->createForm(ProveedorFormType::class,$proveedor);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($proveedor);
      $entityManager->flush();
      $msg_success[]="Se han actualizado correctamente los datos del proveedor";
    }

    return $this->render('fiscal/proveedor.html.twig', [
      "form"=>$form->createView(),
      "msg_success"=>$msg_success
    ]);
  }

  /**
  * @Route("/{_locale}/proveedor/{id}/eliminar", name="eliminar_proveedor")
  */
  public function eliminarProveedor($id)
  {

    $proovedorRepository = $this->getDoctrine()->getRepository(Proveedor::class);
    $proveedor = $proovedorRepository->findOneById($id);
    if($proveedor==null)
    throw $this->createNotFoundException('No existe el proveedor para el id especificado');

    //Tiene gastos
    if($proveedor->getGastos()->count()>0)
    throw $this->createNotFoundException('No se puede eliminar ya que el proveedor tiene gastos asociados');

    //eliminar
    $em = $this->getDoctrine()->getManager();
    $em->remove($proveedor);
    $em->flush();
    return $this->redirectToRoute('lista_proveedores');

  }

  /**
  * @Route("/{_locale}/gastos", name="lista_gastos")
  */
  public function listaGastos()
  {
    $gastos_repository = $this->getDoctrine()->getRepository(Gasto::class);
    $gastos = $gastos_repository->findAll();
    return $this->render('fiscal/gastos.html.twig', array('gastos'=>$gastos, 'title_h1'=>'Lista de gastos', 'nuevo_url'=>$this->generateUrl('nuevo_gasto')));
  }

  /**
  * @Route("/{_locale}/gastos/proveedor/{id}", name="lista_gastos_proveedor")
  */
  public function listaGastosProveedor($id)
  {
    $gastos_repository = $this->getDoctrine()->getRepository(Gasto::class);
    $proveedorRepository = $this->getDoctrine()->getRepository(Proveedor::class);
    $proveedor = $proveedorRepository->findOneById($id);
    if($proveedor==null)
    throw $this->createNotFoundException('No existe ningún proveedor con el id especificado');
    $gastos = $gastos_repository->findByProveedor($proveedor);
    return $this->render('fiscal/gastos.html.twig', array('gastos'=>$gastos, 'title_h1'=>'Gastos del proveedor: ' . $proveedor->getNombreComercial(), 'nuevo_url'=>$this->generateUrl('nuevo_gasto_desde_proveedor',['id'=>$proveedor->getId()])));
  }

  /**
  * @Route("/{_locale}/gastos/empresa/{id}", name="lista_gastos_empresa")
  */
  public function listaGastosEmpresa($id)
  {
    $empresaRepository = $this->getDoctrine()->getRepository(Empresa::class);
    $empresa = $empresaRepository->findOneById($id);
    if($empresa==null)
    return $this->createNotFoundException('No existe la empresa con el id especificado');
    return $this->render('fiscal/gastos.html.twig', array('gastos'=>$empresa->getGastos(), 'title_h1'=>'Gastos de la empresa: ' . $empresa->getNombreComercial(), 'nuevo_url'=>$this->generateUrl('nuevo_gasto_desde_empresa',['id'=>$empresa->getId()])));
  }

  /**
  * @Route("/{_locale}/gasto/nuevo", name="nuevo_gasto")
  */
  public function nuevoGasto(Request $request)
  {
    $msg_success=array();
    $gasto=new Gasto();
    $gasto->setFecha(new \DateTime());
    $form = $this->createForm(GastoFormType::class,$gasto);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($gasto);
      $entityManager->flush();
      $msg_success[]="Se ha registrado correctamente el gasto";
      return $this->redirectToRoute('lista_gastos');
    }
    return $this->render('fiscal/gastonuevo.html.twig', [
      "form"=>$form->createView(),
      "msg_success"=>$msg_success
    ]);

  }

  /**
  * @Route("/{_locale}/gasto/{id}", name="ver_editar_gasto")
  */
  public function verEditarGasto($id, Request $request)
  {
    $gastoRepository = $this->getDoctrine()->getRepository(Gasto::class);
    $gasto = $gastoRepository->findOneById($id);
    $msg_success=array();
    if($gasto==null)
      throw $this->createNotFoundException('No existe el gasto para el id especificado');

      $form = $this->createForm(GastoFormType::class,$gasto);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($gasto);
        $entityManager->flush();
        $msg_success[]="Se ha actualizado correctamente el gasto";
      }

      return $this->render('fiscal/gastonuevo.html.twig', [
        "form"=>$form->createView(),
        "msg_success"=>$msg_success
      ]);

  }

  /**
  * @Route("/{_locale}/gasto/nuevo/proveedor/{id}", name="nuevo_gasto_desde_proveedor")
  */
  public function nuevoGastoDesdeProveedor($id, Request $request)
  {
    $proveedorRepository = $this->getDoctrine()->getRepository(Proveedor::class);
    $proveedor = $proveedorRepository->findOneById($id);
    if($proveedor==null)
    throw $this->createNotFoundException('No existe proveedor para el id especificado');
    $gasto = new Gasto();
    $gasto->setFecha(new \DateTime());
    $gasto->setProveedor($proveedor);
    $form = $this->createForm(GastoFormType::class,$gasto,['proveedor_disabled'=>true]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($gasto);
      $entityManager->flush();
      return $this->redirectToRoute('lista_gastos');
    }
    return $this->render('fiscal/gastonuevo.html.twig', [
      "form"=>$form->createView(),
      "msg_success"=>[]
    ]);
  }

  /**
  * @Route("/{_locale}/gasto/nuevo/empresa/{id}", name="nuevo_gasto_desde_empresa")
  */
  public function nuevoGastoDesdeEmpresa($id, Request $request)
  {
    $empresaRepository = $this->getDoctrine()->getRepository(Empresa::class);
    $empresa = $empresaRepository->findOneById($id);
    if($empresa==null)
    throw $this->createNotFoundException('No existe empresa con el id especificado');
    $gasto = new Gasto();
    $gasto->setFecha(new \DateTime());
    $gasto->setEmpresa($empresa);
    $form = $this->createForm(GastoFormType::class,$gasto,['empresa_disabled'=>true]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($gasto);
      $entityManager->flush();
      return $this->redirectToRoute('lista_gastos');
    }
    return $this->render('fiscal/gastonuevo.html.twig', [
      "form"=>$form->createView(),
      "msg_success"=>[]
    ]);
  }


  /**
  * @Route("/{_locale}/gasto/{id}/eliminar", name="eliminar_gasto")
  */
  public function eliminarGasto($id, Request $request)
  {
    $gastoRepository = $this->getDoctrine()->getRepository(Gasto::class);
    $gasto = $gastoRepository->findOneById($id);
    if($gasto==null)
      throw $this->createNotFoundException('No existe el gasto para el id especificado');

    $em=$this->getDoctrine()->getManager();
    $em->remove($gasto);
    $em->flush();
    return $this->redirectToRoute('lista_gastos');

  }


  /**
  * @Route("/{_locale}/ingresos", name="lista_ingresos")
  */
  public function listaIngresos()
  {
    $ingresosRepository = $this->getDoctrine()->getRepository(Ingreso::class);
    $ingresos = $ingresosRepository->findAll();
    return $this->render('fiscal/ingresos.html.twig', array('ingresos'=>$ingresos, 'title_h1'=>'Lista de todos los ingresos','url_nuevo'=>$this->generateUrl('nuevo_ingreso')));
  }

  /**
  * @Route("/{_locale}/ingresos/empresa/{id}", name="lista_ingresos_empresa")
  */
  public function listaIngresosEmpresa($id)
  {
    $empresaRepository = $this->getDoctrine()->getRepository(Empresa::class);
    $empresa = $empresaRepository->findOneById($id);
    if($empresa==null)
    return $this->createNotFoundException('No existe la empresa con el id especificado');
    return $this->render('fiscal/ingresos.html.twig', array('ingresos'=>$empresa->getIngresos(),'title_h1'=>'Lista de ingresos para la empresa ' . $empresa->getNombreComercial(), 'url_nuevo'=>$this->generateUrl('nuevo_ingreso_desde_empresa',['id'=>$empresa->getId()])));
  }

  /**
  * @Route("/{_locale}/ingreso/nuevo", name="nuevo_ingreso")
  */
  public function nuevoIngreso(Request $request)
  {
    //Selector empresa
    $empresaRepository = $this->getDoctrine()->getRepository(Empresa::class);
    $id_empresa = $request->get('id_empresa');
    if($id_empresa==null){
      return $this->render('fiscal/ingresoempresa.html.twig',['empresas'=>$empresaRepository->findAll(), 'action'=>$this->generateUrl('nuevo_ingreso')]);
    }else{
      return $this->redirectToRoute('nuevo_ingreso_desde_empresa',['id'=>$id_empresa]);
    }
  }

  /**
  * @Route("/{_locale}/ingreso/{id}", name="ver_editar_ingreso")
  */
  public function verEditarIngreso($id, Request $request)
  {
    $ingresosRepository = $this->getDoctrine()->getRepository(Ingreso::class);
    $ingreso = $ingresosRepository->findOneById($id);
    if($ingreso==null)
    return $this->createNotFoundException('No existe nigún ingreso para el id especifiado');

    $form = $this->createForm(IngresoFormType::class, $ingreso, ['facturas'=>$ingreso->getEmpresa()->getFacturas()]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($ingreso);
      $entityManager->flush();
      $msg_success[]="Se ha registrado correctamente el ingreso";
      return $this->redirectToRoute('lista_ingresos');
    }
    return $this->render('fiscal/vereditaringreso.html.twig', [
      "form"=>$form->createView(),
      "msg_success"=>array()
    ]);

  }

  /**
  * @Route("/{_locale}/ingreso/nuevo/empresa/{id}", name="nuevo_ingreso_desde_empresa")
  */
  public function nuevoIngresoDesdeEmpresa($id, Request $request)
  {
    $empresaRepository = $this->getDoctrine()->getRepository(Empresa::class);
    $empresa = $empresaRepository->findOneById($id);
    if($empresa==null)
    throw $this->createNotFoundException('No existe la empresa con el id especificado');

    $ingreso = new Ingreso();
    $ingreso->setFecha(new \DateTime());
    $ingreso->setEmpresa($empresa);

    $form = $this->createForm(IngresoFormType::class, $ingreso, ['facturas'=>$empresa->getFacturas()]);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($ingreso);
      $entityManager->flush();
      $msg_success[]="Se ha registrado correctamente el ingreso";
      return $this->redirectToRoute('lista_ingresos');
    }
    return $this->render('fiscal/ingresonuevo.html.twig', [
      "form"=>$form->createView(),
      "msg_success"=>array()
    ]);

  }

  /**
  * @Route("/{_locale}/ingreso/nuevo/factura/{id}", name="nuevo_ingreso_desde_factura")
  */
  public function nuevoIngresoDesdeFactura($id, Request $request)
  {
    $facturaRepository = $this->getDoctrine()->getRepository(Factura::class);
    $factura = $facturaRepository->findOneById($id);
    if($factura==null)
    throw $this->createNotFoundException('No existe la factura con el id especificado');

    $ingreso = new Ingreso();
    $ingreso->setFecha(new \DateTime());
    $ingreso->setEmpresa($factura->getEmpresa());
    $ingreso->setfactura($factura);
    $form = $this->createForm(IngresoFormType::class, $ingreso, ['facturas'=>array($factura), 'factura_disabled'=>true]);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($ingreso);
      $entityManager->flush();
      $msg_success[]="Se ha registrado correctamente el gasto";
      return $this->redirectToRoute('lista_ingresos');
    }
    return $this->render('fiscal/ingresonuevo.html.twig', [
      "form"=>$form->createView(),
      "msg_success"=>array()
    ]);

  }


  /**
  * @Route("/{_locale}/ingreso/{id}/eliminar", name="eliminar_ingreso")
  */
  public function eliminarIngreso($id)
  {
    $ingresosRepository = $this->getDoctrine()->getRepository(Ingreso::class);
    $ingreso = $ingresosRepository->findOneById($id);
    if($ingreso==null)
    throw $this->createNotFoundException('No existe un ingreso para el id especificado');

    $em = $this->getDoctrine()->getManager();
    $em->remove($ingreso);
    $em->flush();
    return $this->redirectToRoute('lista_ingresos');

  }

  /**
  * @Route("{_locale}/facturas", name="lista_facturas")
  */
  public function listaFacturas()
  {
    $factura_repository = $this->getDoctrine()->getRepository(Factura::class);
    $facturas = $factura_repository->findAll();
    return $this->render('fiscal/facturas.html.twig',['facturas'=>$facturas, 'title_h1' => 'Lista de todas las facturas', 'nueva_url'=>$this->generateUrl('nueva_factura')]);
  }

  /**
  * @Route("{_locale}/facturas/empresa/{id}", name="lista_facturas_empresa")
  */
  public function listaFacturasEmpresa($id)
  {
    $empresaRepository =  $this->getDoctrine()->getRepository(Empresa::class);
    $empresa = $empresaRepository->findOneById($id);
    if($empresa==null)
    throw $this->createNotFoundException('No existe empresa con el id especificado');
    return $this->render('fiscal/facturas.html.twig',['facturas'=>$empresa->getFacturas(), 'title_h1'=>'Facturas de la empresa ' . $empresa->getNombreComercial(),'nueva_url'=>($this->generateUrl('nueva_factura') . '?id_empresa=' . $empresa->getId())]);
  }

  /**
  * @Route("{_locale}/factura/nueva/presupuesto/{id}", name="nueva_factura_desde_presupuesto")
  * Factura desde presupuesto
  */
  public function nuevaFacturaDesdePresupuesto($id, Request $request, FacturaUtils $facturaUtils){

    $serie_factura = $this->getParameter('app.serie_factura');//Prefijo para la factura

    $presupuestoRepository = $this->getDoctrine()->getRepository(Presupuesto::class);
    $facturaRepository = $this->getDoctrine()->getRepository(Factura::class);

    $presupuesto = $presupuestoRepository->findOneById($id);
    if($presupuesto==null){
      die("Error, no existe el presupuesto para el id especificado");
      //TODO: Mensaje de error
    }

    $factura = new Factura();
    $factura = $facturaUtils->facturaDesdePresupuesto($presupuesto); //Generamos factura desde presupuesto
    $ultimoNumeroFactura = $facturaRepository->findLastNumeroEmpresa($serie_factura, $presupuesto->getEmpresa()); //Buscamos el último numero
    $nuevoNumeroFactura = $facturaUtils->siguienteNumeroFactura($ultimoNumeroFactura); //Obtenemos el numero nuevo de factura

    //Seteamos campos
    $factura->setSerie($serie_factura);
    $factura->setNumeroFactura($nuevoNumeroFactura);
    $factura->setFechaEmision(new \DateTime());

    //Creamos Formulario
    $form = $this->createForm(FacturaFormType::class, $factura, ['oportunidad_disabled'=>true, 'cliente_disabled'=>true]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $factura = $form->getData();
      //Persistimos las lineas
      foreach($factura->getLineas() as $linea){
        $linea->setFactura($factura);
        $entityManager->persist($linea);
      }
      if($factura->getOportunidadVenta())$factura->getOportunidadVenta()->setFactura($factura);
      //Guardamos factura
      $entityManager->persist($factura);
      $entityManager->flush();
      return $this->redirectToRoute('lista_facturas');
    }

    return $this->render('fiscal/factura.html.twig', array('factura'=>$factura, 'form'=>$form->createView()));
  }

  /**
  * @Route("{_locale}/factura/nueva/oportunidad/{id}", name="nueva_factura_desde_oportunidad")
  * Factura desde oportunidad de venta
  */
  public function nuevaFacturaDesdeOportunidad($id, Request $request, FacturaUtils $facturaUtils){


    $serie_factura = $this->getParameter('app.serie_factura');//Prefijo para la factura

    $oportunidadesRepository = $this->getDoctrine()->getRepository(OportunidadVenta::class);
    $facturaRepository = $this->getDoctrine()->getRepository(Factura::class);
    $empresaRepository = $this->getDoctrine()->getRepository(Empresa::class);

    $oportunidad  = $oportunidadesRepository->findOneById($id);
    if($oportunidad==null){
      die("Error, no existe la oportunidad con el id especificado");
      //TODO: Mensaje de error
    }

    $factura = new Factura();
    if($oportunidad->getPresupuesto()==null){
      //Si no hay presupuesto, hay que especificar el id de empresa
      $id_empresa = $request->get('id_empresa');
      if($id_empresa==null){
        return $this->render('fiscal/facturaempresa.html.twig',['empresas'=>$empresaRepository->findAll(), 'action'=> $this->generateUrl('nueva_factura_desde_oportunidad',['id'=>$oportunidad->getId()])]);
      }
      $empresa = $empresaRepository->findOneById($id_empresa);
      if($empresa==null){
        die("Error crítico, hay un presupuesto sin empresa");
        //TODO: Mensaje de error
      }
      $factura->setEmpresa($empresa);
      $factura->setOportunidadVenta($oportunidad);
      $factura->setCliente($oportunidad->getCliente());
    }else{
      //Factura desde presupuesto
      $factura = $facturaUtils->facturaDesdePresupuesto($oportunidad->getPresupuesto()); //Generamos factura desde presupuesto
    }

    $ultimoNumeroFactura = $facturaRepository->findLastNumeroEmpresa($serie_factura, $factura->getEmpresa()); //Buscamos el último numero
    $nuevoNumeroFactura = $facturaUtils->siguienteNumeroFactura($ultimoNumeroFactura); //Obtenemos el numero nuevo de factura

    //Seteamos campos
    $factura->setSerie($serie_factura);
    $factura->setNumeroFactura($nuevoNumeroFactura);
    $factura->setFechaEmision(new \DateTime());

    //Creamos Formulario
    $form = $this->createForm(FacturaFormType::class, $factura , ['oportunidad_disabled'=>true, 'cliente_disabled'=>true]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $factura = $form->getData();
      //Persistimos las lineas
      foreach($factura->getLineas() as $linea){
        $linea->setFactura($factura);
        $entityManager->persist($linea);
      }
      if($factura->getOportunidadVenta())$factura->getOportunidadVenta()->setFactura($factura);
      //Guardamos factura
      $entityManager->persist($factura);
      $entityManager->flush();
      return $this->redirectToRoute('lista_facturas');
    }

    return $this->render('fiscal/factura.html.twig', array('factura'=>$factura, 'form'=>$form->createView()));


  }


  /**
  * @Route("{_locale}/factura/nueva", name="nueva_factura")
  * Nueva factura
  */
  public function nuevaFactura(Request $request, FacturaUtils $facturaUtils)
  {

    $serie_factura = $this->getParameter('app.serie_factura');//Prefijo para la factura
    $facturaRepository = $this->getDoctrine()->getRepository(Factura::class);
    $empresaRepository = $this->getDoctrine()->getRepository(Empresa::class);

    $id_empresa = $request->get('id_empresa');

    if($id_empresa==null){
      return $this->render('fiscal/facturaempresa.html.twig',['empresas'=>$empresaRepository->findAll(), 'action'=>$this->generateUrl('nueva_factura')]);
    }
    $empresa = $empresaRepository->findOneById($id_empresa);
    if($empresa==null){
      die("Error, el id de empresa es incorrecto");
      //TODO: Mensaje de error
    }

    $factura = new Factura();
    $factura->setEmpresa($empresa);
    $ultimoNumeroFactura = $facturaRepository->findLastNumeroEmpresa($serie_factura, $factura->getEmpresa()); //Buscamos el último numero
    $nuevoNumeroFactura = $facturaUtils->siguienteNumeroFactura($ultimoNumeroFactura); //Obtenemos el numero nuevo de factura

    $factura->setSerie($serie_factura);
    $factura->setNumeroFactura($nuevoNumeroFactura);
    $factura->setFechaEmision(new \DateTime());

    //Creamos Formulario
    $form = $this->createForm(FacturaFormType::class, $factura /*, ['oportunidades'=>$oportunidades_tablero]*/);

    //Formulario Enviado?
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $entityManager = $this->getDoctrine()->getManager();
      $factura = $form->getData();

      //Persistimos las lineas
      foreach($factura->getLineas() as $linea){
        $linea->setFactura($factura);
        $entityManager->persist($linea);
      }
      if($factura->getOportunidadVenta())$factura->getOportunidadVenta()->setFactura($factura);
      //Guardamos factura
      $entityManager->persist($factura);
      $entityManager->flush();

      return $this->redirectToRoute('lista_facturas');
    }

    return $this->render('fiscal/factura.html.twig', array('factura'=>$factura, 'form'=>$form->createView()));

  }

  /**
  * @Route("{_locale}/factura/{id}/editar", name="ver_editar_factura")
  */
  public function verEditarFactura($id, Request $request)
  {

    $facturaRepository = $this->getDoctrine()->getRepository(Factura::class);
    $factura = $facturaRepository->findOneById($id);
    if($factura==null){
      //TODO: return error
      die("Error");
    }
    $form = $this->createForm(FacturaFormType::class, $factura);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $entityManager = $this->getDoctrine()->getManager();
      $factura = $form->getData();
      foreach($factura->getLineas() as $linea){
        $linea->setFactura($factura);
        $entityManager->persist($linea);
      }

      $entityManager->persist($factura);
      $entityManager->flush();

    }

    return $this->render('fiscal/factura.html.twig',array('factura'=>$factura, 'form'=>$form->createView()));

  }

  /**
  * @Route("{_locale}/factura/{id}/eliminar", name="eliminar_factura")
  */
  public function eliminarFactura($id)
  {
    $facturaRepository = $this->getDoctrine()->getRepository(Factura::class);
    $factura = $facturaRepository->findOneById($id);
    if($factura==null){
      throw $this->createNotFoundException('No existe ninguna factura con el id especificad');
    }
    if($factura->getOportunidadVenta()){
      $factura->getOportunidadVenta()->setFactura(null);
    }
    foreach($factura->getIngresos() as $ingreso){
      $ingreso->setFactura(null);
    }
    $factura->setOportunidadVenta(null);
    $em = $this->getDoctrine()->getManager();
    $em->remove($factura);
    $em->flush();
    return $this->redirectToRoute('lista_facturas');

  }

  /**
  * @Route("{_locale}/factura/{id}", name="ver_factura")
  */
  public function verFactura($id)
  {
    $facturaRepository = $this->getDoctrine()->getRepository(Factura::class);
    $factura = $facturaRepository->findOneById($id);
    if($factura==null){
      //TODO: return error
      die("Error, no existe esa factura con ese id");
    }
    return $this->render('fiscal/verfactura.html.twig',array('factura'=>$factura));

  }

  /**
  * @route("/{_locale}/factura/{id}/pdf", name="ver_factura_pdf")
  */
  public function verFacturaPDF($id, PDFGenerator $pdfGenerator)
  {
    $factura_repository = $this->getDoctrine()->getRepository(Factura::class);
    $factura = $factura_repository->findOneById($id);
    if($factura==null){
      //TODO: Return error
      die("Error");
    }
    $path = 'img/logo.jpg';
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    // Retrieve the HTML generated in our twig file
    $html = $this->renderView('pdf/factura.html.twig', [
      'factura'=>$factura,
      'logo'=>$base64
    ]);
    $pdfGenerator->presupuestoAPDF($html);
  }

  /**
  * @Route("/{_locale}/modelos_fiscales", name="resumen_fiscal")
  */
  public function resumenFiscal(Request $request, FacturaUtils $facturaUtils)
  {

    $empresaRepository = $this->getDoctrine()->getRepository(Empresa::class);

    $id_empresa = $request->get('id_empresa');
    $ano = $request->get('ano');
    $trimestre = $request->get('trimestre');

    if($id_empresa==null || $ano==null || $trimestre == null){
      return $this->render('fiscal/modelosfiscalesselector.html.twig',['empresas'=>$empresaRepository->findAll(),'current_year'=>date('Y')]);
    }

    $empresa = $empresaRepository->findOneById($id_empresa);
    if($empresa==null)
      throw $this->createNotFoundException('No existe la empresa con ese id');

    $facturas = $empresa->getFacturas();
    $gastos = $empresa->getGastos();

    $datosfiscales = $facturaUtils->generaDatosModelos($facturas, $gastos, $ano, $trimestre);


    return $this->render('fiscal/modelosfiscales.html.twig',['datosfiscales'=>$datosfiscales,'ano'=>$ano, 'trimestre'=>$trimestre, 'nombre_empresa'=>$empresa->getNombreComercial()]);

  }


}
