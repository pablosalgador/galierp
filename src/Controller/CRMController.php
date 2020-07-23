<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
/*use Symfony\Component\Serializer\SerializerInterface;*/

use Symfony\Contracts\Translation\TranslatorInterface;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

//Entidades
use App\Entity\ColumnaKanban;
use App\Entity\OportunidadVenta;
use App\Entity\Usuario;
use App\Entity\Cliente;
use App\Entity\Presupuesto;

//Formularios
use App\Form\OportunidadVentaType;
use App\Form\ColumnaKanbanType;
use App\Form\PresupuestoType;
use App\Form\LineaPresupuestoType;

//Servicios
use App\Service\PDFGenerator;

/**
 * @IsGranted("ROLE_CRM")
 */
class CRMController extends AbstractController
{

  /**
  * @Route("/{_locale}/kanban", name="kanban")
  */
  public function tableroKanban(Request $request)
  {

    $columnaRepository = $this->getDoctrine()->getRepository(ColumnaKanban::class);
    $usersRepository = $this->getDoctrine()->getRepository(Usuario::class);
    $clientesRepository = $this->getDoctrine()->getRepository(Cliente::class);

    $columnas = $columnaRepository->findAll();
    $usuarios = $usersRepository->findAll();
    $clientes = $clientesRepository->findAll();

    $oportunidadVenta = new OportunidadVenta();
    $columnaKanban = new ColumnaKanban();

    $formoportunidad = $this->createForm(OportunidadVentaType::class, $oportunidadVenta, ['label_guardar'=>'Crear oportunidad de venta']);
    $formcolumna = $this->createForm(ColumnaKanbanType::class, $columnaKanban, ['label_guardar'=>'Crear nueva columna']);

    //Formulario Nueva Oportunidad de Venta Enviado
    $formoportunidad->handleRequest($request);
    if ($formoportunidad->isSubmitted() && $formoportunidad->isValid()) {
      $oportunidadVenta = $formoportunidad->getData();
      $oportunidadVenta->setFechaCreacion(new \DateTime());
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($oportunidadVenta);
      $entityManager->flush();
      return $this->redirectToRoute('kanban');
    }

    //Formulario Nueva Columna Kanban Enviado
    $formcolumna->handleRequest($request);
    if($formcolumna->isSubmitted() && $formcolumna->isValid()){
      $columnaKanban = $formcolumna->getData();
      $lastposicion = $columnaRepository->findLastPosicion();
      if($lastposicion == null){
        $columnaKanban->setPosicion(0);
      }else{
        $columnaKanban->setPosicion($lastposicion['posicion']+1);
      }
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($columnaKanban);
      $entityManager->flush();
      return $this->redirectToRoute('kanban');
    }

    return $this->render('crm/kanban.html.twig',array('formoportunidad'=>$formoportunidad->createView(), 'formcolumna'=>$formcolumna->createView()));
  }

  /**
  * @Route("/{_locale}/columna/{id}", name="ver_editar_columna_kanban")
  */
  public function verEditarColumnaKanban($id, Request $request)
  {
    $repositoryColumnaKanban = $this->getDoctrine()->getRepository(ColumnaKanban::class);
    $columnaKanban = $repositoryColumnaKanban->findOneById($id);
    $smessage = null;
    if($columnaKanban == null){
      //TODO: Return error
      die("Error");
    }

    $form = $this->createForm(ColumnaKanbanType::class, $columnaKanban);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
      $columnaKanban = $form->getData();
      $em = $this->getDoctrine()->getManager();
      $em->persist($columnaKanban);
      $em->flush();
      $smessage = "Datos actualizados correctamente!";
    }
    //$form = $this->createForm()

    return $this->render('crm/vereditarcolumnakanban.html.twig',['form'=>$form->createView(),'smessage'=>$smessage]);
  }

  /**
  * @Route("/{_locale}/columna/{id}/eliminar", name="eliminar_columna_kanban")
  */
  public function eliminarColumnaKanban($id)
  {

    $columnaKanbanRepository = $this->getDoctrine()->getRepository(ColumnaKanban::class);
    $columna = $columnaKanbanRepository->findOneById($id);

    if($columna==null){
      //TODO: Error
      die("error");
    }

    if($columna->getOportunidades()->count()>0)
    {
      //TODO: Error
      die("error");
    }

    $em = $this->getDoctrine()->getManager();
    $em->remove($columna);
    $em->flush();
    return $this->redirectToRoute('kanban');

  }

  /**
  * @Route("/{_locale}/oportunidad/{id}/ganada", name="oportunidad_venta_ganada")
  */
  public function oportunidadVentaGanada($id)
  {

    $oportunidadRepository = $this->getDoctrine()->getRepository(OportunidadVenta::class);
    $oportunidad = $oportunidadRepository->findOneById($id);
    if($oportunidad==null){
      //TODO: Return no error
      die("error");
    }
    $oportunidad->setGanada(true);
    $oportunidad->setperdida(false);
    $oportunidad->getColumnaKanban()->removeOportunidade($oportunidad);
    $oportunidad->setColumnaKanban(null);
    $entityManager = $this->getdoctrine()->getManager();
    $entityManager->persist($oportunidad);
    $entityManager->flush();
    return $this->redirectToRoute("kanban");

  }

  /**
  * @Route("/{_locale}/oportunidad/{id}/perdida",  methods={"POST"}, name="oportunidad_venta_perdida")
  */
  public function oportunidadVentaPerdida(Request $request, $id)
  {
    $oportunidadRepository = $this->getDoctrine()->getRepository(OportunidadVenta::class);
    $oportunidad = $oportunidadRepository->findOneById($id);
    if($oportunidad==null){
      //TODO: Return no error
      die("Error");
    }
    $motivo = $request->request->get('motivo-perdida');
    $oportunidad->setGanada(false);
    $oportunidad->setPerdida(true);
    $oportunidad->setMotivoPerdida($motivo);
    $oportunidad->getColumnaKanban()->removeOportunidade($oportunidad);
    $oportunidad->setColumnaKanban(null);
    $entityManager = $this->getdoctrine()->getManager();
    $entityManager->persist($oportunidad);
    $entityManager->flush();
    return $this->redirectToRoute("kanban");

  }

  /**
  * @Route("/{_locale}/oportunidad/{id}/eliminar", name="eliminar_oportunidad_venta")
  */
  public function eliminarOportunidadVenta($id, Request $request)
  {
    $oportunidadRepository = $this->getDoctrine()->getRepository(OportunidadVenta::class);
    $oportunidad = $oportunidadRepository->findOneById($id);
    if($oportunidad==null){
      //TODO: Return no error
      die("ERror");
    }
    $entityManager = $this->getdoctrine()->getManager();
    if($oportunidad->getPresupuesto()!=null)$oportunidad->getPresupuesto()->setOportunidad(null);
    $entityManager->remove($oportunidad);
    $entityManager->flush();
    return $this->redirectToRoute("kanban");
  }

  /**
  * @Route("/{_locale}/oportunidad/{id}", name="ver_editar_oportunidad_venta")
  */
  public function verEditarOportunidadVenta($id, Request $request)
  {
    $oportunidadRepository = $this->getDoctrine()->getRepository(OportunidadVenta::class);
    $oportunidad = $oportunidadRepository->findOneById($id);
    if($oportunidad==null){
      //TODO: Return no error
    }

    $form = $this->createForm(OportunidadVentaType::class, $oportunidad);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $oportunidadVenta = $form->getData();
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($oportunidadVenta);
      $entityManager->flush();
      return $this->redirectToRoute('kanban');
    }

    return $this->render('crm/vereditaroportunidadventa.html.twig',array('form'=>$form->createView()));
  }

  /**
  * @Route("/{_locale}/oportunidades", name="ver_oportunidades")
  */
  public function verOportunidades(TranslatorInterface $translator)
  {
    $repositoryOportunidades = $this->getDoctrine()->getRepository(OportunidadVenta::class);
    $oportunidades = $repositoryOportunidades->findAll();
    $title = $translator->trans('Oportunidades de Venta');
    return $this->render('crm/oportunidades.html.twig',array('title'=>$title,'oportunidades'=>$oportunidades));
  }

  /**
  * @Route("/{_locale}/oportunidades/ganadas", name="ver_oportunidades_ganadas")
  */
  public function verOportunidadesGanadas(TranslatorInterface $translator)
  {
    $repositoryOportunidades = $this->getDoctrine()->getRepository(OportunidadVenta::class);
    $oportunidades = $repositoryOportunidades->findByGanada(true);
    $title = $translator->trans('Oportunidades Ganadas');
    return $this->render('crm/oportunidades.html.twig',array('title'=>$title,'oportunidades'=>$oportunidades));
  }

  /**
  * @Route("/{_locale}/oportunidades/perdidas", name="ver_oportunidades_perdidas")
  */
  public function verOportunidadesPerdidas(TranslatorInterface $translator)
  {
    $repositoryOportunidades = $this->getDoctrine()->getRepository(OportunidadVenta::class);
    $oportunidades = $repositoryOportunidades->findByPerdida(true);
    $title = $translator->trans('Oportunidades Perdidas');
    return $this->render('crm/oportunidades.html.twig',array('title'=>$title,'oportunidades'=>$oportunidades));
  }


 /**
  * @Route("{_locale}/presupuestos", name="lista_presupuestos")
  */
  public function listaPresupuestos()
  {
    $presupuestoRepository = $this->getDoctrine()->getRepository(Presupuesto::class);
    $presupuestos = $presupuestoRepository->findAll();
    return $this->render('crm/presupuestos.html.twig',['presupuestos'=>$presupuestos]);
  }


  /**
  * @Route("{_locale}/presupuesto/nuevo", name="nuevo_presupuesto")
  */
  public function nuevoPresupuesto(Request $request)
  {

    $prefijo_presu = $this->getParameter('app.prefijo_presupuesto');//Prefijo para presupuestos
    $max_presu = $this->getParameter('app.max_presu');//Números maximos para el presupuesto
    $dias_validez = $this->getParameter('app.dias_validez_defecto'); //Dias validez por defecto

    $oportunidadesRepository = $this->getDoctrine()->getRepository(OportunidadVenta::class);
    $presupuestoRepository = $this->getDoctrine()->getRepository(Presupuesto::class);
    $oportunidadesTablero = $oportunidadesRepository->findOnKanban(); //Oportunidades del tablero solo

    $numero_presupuesto = null;
    $oportunidad = null;

    //Si id_oportunidad buscamos oportunidad y la asociamos
    $id_oportunidad = $request->get('id_oportunidad');
    if($id_oportunidad!=null)$oportunidad = $oportunidadesRepository->findOneById($id_oportunidad);

    //Obtenemos el número del ultimo presupuesto
    $ultimo_numero = $presupuestoRepository->findLastNumero();

    //Existe algun presupuesto con la serie prefijo
    if($ultimo_numero != null && $ultimo_numero["numero"]!=null && strpos($ultimo_numero["numero"],$prefijo_presu) !== false){
      $numero_presupuesto = intval(str_replace($prefijo_presu,"",$ultimo_numero['numero'])) + 1;
      $j = strlen($numero_presupuesto);
      //Rellenamos con 0
      for($i=0;$i<= $max_presu - $j;$i++)$numero_presupuesto = '0' . strval($numero_presupuesto);
      $numero_presupuesto = $prefijo_presu . $numero_presupuesto;
    }else{
      $numero_presupuesto = $prefijo_presu;
      //Rellenamos con 0
      for($i=0;$i<$max_presu;$i++)$numero_presupuesto .=0;
      $numero_presupuesto.='1';
    }

    //Seteamos campos en presupuesto
    $presupuesto = new Presupuesto();
    $presupuesto->setNumeroPresupuesto($numero_presupuesto);
    if($oportunidad!=null){
      $presupuesto->setOportunidadVenta($oportunidad);
      $presupuesto->setCliente($oportunidad->getCliente());
    }
    $presupuesto->setFechaEmision(new \DateTime());
    $presupuesto->setDiasValidez($dias_validez);

    //Creamos formulario
    $form = $this->createForm(PresupuestoType::class, $presupuesto, ['oportunidades'=>$oportunidadesTablero]);

    //Formulario enviado?
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $entityManager = $this->getDoctrine()->getManager();
      $presupuesto = $form->getData();

      //Persistimos las lineas
      foreach($presupuesto->getLineas() as $linea){
        $linea->setPresupuesto($presupuesto);
        $entityManager->persist($linea);
      }

      //Guardamos presupuesto
      $entityManager->persist($presupuesto);
      $entityManager->flush();
      return $this->redirectToRoute('lista_presupuestos');
    }

    return $this->render('crm/presupuesto.html.twig',array('presupuesto'=>$presupuesto,'form'=>$form->createView()));
  }


  /**
  * @Route("/{_locale}/presupuesto/{id}", name="ver_editar_presupuesto")
  */
  public function verEditarPresupuesto($id, Request $request)
  {

    $presupuestoRepository = $this->getDoctrine()->getRepository(Presupuesto::class);
    $oportunidadesRepository = $this->getDoctrine()->getRepository(OportunidadVenta::class);

    $presupuesto = $presupuestoRepository->findOneById($id);
    if($presupuesto==null){
      //TODO: return error
      die("Error");
    }

    $oportunidadesTablero = $oportunidadesRepository->findOnKanban();
    $form = $this->createForm(PresupuestoType::class, $presupuesto, ['oportunidades'=>$oportunidadesTablero]);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $entityManager = $this->getDoctrine()->getManager();
      $presupuesto = $form->getData();

      foreach($presupuesto->getLineas() as $linea){
        $linea->setPresupuesto($presupuesto);
        $entityManager->persist($linea);
      }

      $entityManager->persist($presupuesto);
      $entityManager->flush();
      //return $this->redirectToRoute('kanban');
    }

    return $this->render('crm/presupuesto.html.twig',array('presupuesto'=>$presupuesto,'form'=>$form->createView()));

  }

  /**
  * @route("/{_locale}/presupuesto/{id}/pdf", name="ver_presupuesto_pdf")
  */
  public function verPresupuestoPDF($id, PDFGenerator $pdfGenerator)
  {
    $presupuestoRepository = $this->getDoctrine()->getRepository(Presupuesto::class);
    $presupuesto = $presupuestoRepository->findOneById($id);
    if($presupuesto==null){
      //TODO: Return error
      die("Error");
    }
    // Retrieve the HTML generated in our twig file
    $html = $this->renderView('pdf/presupuesto.html.twig', [
        'presupuesto'=>$presupuesto
    ]);
    $pdfGenerator->presupuestoAPDF($html);
  }

  /**
  * @Route("/ajax/kanban_columns", name="ajax_kanban_columns")
  */
  public function ajaxKanbanColumns()
  {

    $columnaRepository = $this->getDoctrine()->getRepository(ColumnaKanban::class);
    $columnas = $columnaRepository->findAll();

    $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
    $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);

    // Serialize your object in Json
    $jsonObject = $serializer->serialize($columnas, 'json', [
      'circular_reference_handler' => function ($object) {
        return $object->getId();
      }
    ]);


    $response = new Response(
      $jsonObject,
      Response::HTTP_OK,
      ['Content-type' => 'application/json']
    );

    return $response;

  }

  /**
  *  @Route("/ajax/kanban_columns/order", methods={"POST"}, name="ajax_kanban_columns_order")
  */
  public function ajaxKanbanColumnsOrder(Request $request)
  {

    $columnaRepository = $this->getDoctrine()->getRepository(ColumnaKanban::class);
    $entityManager = $this->getDoctrine()->getManager();

    $columnas = $columnaRepository->findAll();
    $neworder = array();

    foreach($request->request->all() as $columna => $posicion){
      $neworder[intval(str_replace("column-","",$columna))]=$posicion;
    }

    foreach($columnas as $columna){
      if($columna->getPosicion()!=$neworder[$columna->getId()]){
        $columna->setPosicion($neworder[$columna->getId()]);
        $entityManager->persist($columna);
      }
    }

    $entityManager->flush();
    return new Response("{'success':'ok'}",Response::HTTP_OK,['Content-type' => 'application/json']);
  }


  /**
  * @Route("/ajax/kanban_columns/move_item", methods={"POST"}, name="ajax_kanban_columns_move_item")
  */
  public function ajaxKanbanColumnsMoveItem(Request $request)
  {

    $columnaRepository = $this->getDoctrine()->getRepository(ColumnaKanban::class);
    $oportunidadRepository = $this->getDoctrine()->getRepository(OportunidadVenta::class);
    $entityManager = $this->getDoctrine()->getManager();

    $columnas = $columnaRepository->findAll();

    foreach($columnas as $columna){
      $items = $request->request->get('column-' . $columna->getId());
      //Vaciamos Todos

      foreach($columna->getOportunidades() as $oportunidad)$columna->removeOportunidade($oportunidad);

      if($items!=null){
        foreach($items as $order=>$item){
          $id = str_replace("item-","",$item);
          $columna->addOportunidade($oportunidadRepository->findOneById($id));
        }
      }

      $entityManager->persist($columna);
      $entityManager->flush();
    }

    return $this->redirectToRoute('ajax_kanban_columns');
    //return new Response('[{"success":"ok"}]',Response::HTTP_OK,['Content-type' => 'application/json']);

  }

}
