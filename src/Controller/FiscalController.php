<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Form\EmpresaFormType;

use App\Entity\Empresa;

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

}
