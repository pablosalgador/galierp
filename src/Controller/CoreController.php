<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


use App\Entity\Cliente;
use App\Entity\Usuario;
use App\Form\ClienteFormType;
use App\Form\UsuarioFormType;
use App\Form\UsuarioFormEditType;


class CoreController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
      //Posible ampliación: Crear una "Landing Page"
      return $this->redirectToRoute("listaclientes");
    }

    /**
     * @Route("/{_locale}/dashboard", name="dashboard")
     * @IsGranted({"ROLE_USER"})
     */
    public function dashboard(Request $request)
    {
      /*if ($this->isGranted('ROLE_ADMIN')) {
          $clirepo = $this->getDoctrine()->getRepository(Cliente::class);
          $nclientes = count($clirepo->findAllActive());
          return $this->render('core/dashboard_admin.html.twig',
            array("nclientes"=>$nclientes)
          );
      }else {
          return $this->render('admin.html.twig');
      }*/
      return $this->redirectToRoute('listaclientes');
    }


    /**
     * @Route("/{_locale}/clientes", name="listaclientes")
     * @IsGranted({"ROLE_USER"})
     */
    public function listaClientes()
    {
      $cli_repository = $this->getDoctrine()->getRepository(Cliente::class);
      $clientes = $cli_repository->findAllIDDesc();
      return $this->render('core/clientes.html.twig', array('clientes'=>$clientes));
    }

    /**
     * @Route("/{_locale}/cliente/nuevo", name="nuevocliente")
     * @IsGranted({"ROLE_USER"})
     */
    public function nuevoCliente(Request $request)
    {
      $msg_success=array();
      $cli_repository = $this->getDoctrine()->getRepository(Cliente::class);
      $cliente=new Cliente();
      $form = $this->createForm(ClienteFormType::class,$cliente);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cliente);
        $entityManager->flush();
        $msg_success[]="Se ha registrado correctamente al cliente";
      }
      return $this->render('core/clientenuevo.html.twig', [
        "form"=>$form->createView(),
        "msg_success"=>$msg_success
      ]);
    }

    /**
     * @Route("/{_locale}/cliente/{id}", name="vereditarcliente")
     * @IsGranted({"ROLE_USER"})
     */
    public function vereditarCliente($id, Request $request)
    {
      $msg_success=array();
      $cli_repository = $this->getDoctrine()->getRepository(Cliente::class);
      $cliente = $cli_repository->findOneById($id);
      if($cliente==null)return $this->redirectToRoute("listaclientes");

      $form = $this->createForm(ClienteFormType::class,$cliente);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cliente);
        $entityManager->flush();
        $msg_success[]="Se han actualizado correctamente los datos del cliente";
      }

      return $this->render('core/cliente.html.twig', [
        "form"=>$form->createView(),
        "msg_success"=>$msg_success
      ]);
    }

    /**
     * @Route("/{_locale}/cliente/{id}/baja", name="bajacliente")
     * @IsGranted({"ROLE_USER"})
     */
    public function bajaCliente($id)
    {
      $cli_repository = $this->getDoctrine()->getRepository(Cliente::class);
      $cliente = $cli_repository->findOneById($id);
      if($cliente==null)return $this->redirectToRoute("listaclientes");
      $cliente->setFechaBaja(new \DateTime());
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($cliente);
      $entityManager->flush();
      return $this->redirectToRoute("listaclientes");
    }

    /**
     * @Route("/{_locale}/cliente/{id}/alta", name="altacliente")
     * @IsGranted({"ROLE_USER"})
     */
    public function altaCliente($id)
    {
      $cli_repository = $this->getDoctrine()->getRepository(Cliente::class);
      $cliente = $cli_repository->findOneById($id);
      if($cliente==null)return $this->redirectToRoute("listaclientes");
      $cliente->setFechaBaja(null);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($cliente);
      $entityManager->flush();
      return $this->redirectToRoute("listaclientes");
    }


    /**
     * @Route("/{_locale}/cliente/{id}/eliminar", name="eliminar_cliente")
     * @IsGranted({"ROLE_USER"})
     */
    public function eliminarCliente($id)
    {

      $cli_repository = $this->getDoctrine()->getRepository(Cliente::class);
      $cliente = $cli_repository->findOneById($id);
      if($cliente==null)
        throw $this->createNotFoundException('No existe el cliente con ese identificador');

      //Oportunidades de ventas?
      if($cliente->getOportunidadesVentas()->count()>0)
        throw $this->createNotFoundException('El cliente que se intenta eliminar aún tiene oportunidades de venta asociadas');

      //Presupuestos?
      if($cliente->getPresupuestos()->count()>0)
        throw $this->createNotFoundException('El cliente que se intenta eliminar aún tiene presupuestos asociados');

      //Facturas?
      if($cliente->getFacturas()->count()>0)
        throw $this->createNotFoundException('El cliente que se intenta eliminar tiene facturas asociadas');

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($cliente);
      $entityManager->flush();
      return $this->redirectToRoute("listaclientes");


    }


    /**
     * @Route("/{_locale}/usuarios", name="listausuarios")
     * @IsGranted({"ROLE_ADMIN"})
     */
     public function listaUsuarios()
     {
       $usu_repository = $this->getDoctrine()->getRepository(Usuario::class);
       $usuarios = $usu_repository->findAll();
       return $this->render('core/usuarios.html.twig', array('usuarios'=>$usuarios));
     }

     /**
      * @Route("/{_locale}/usuario/nuevo", name="nuevousuario")
      * @IsGranted({"ROLE_ADMIN"})
      */
     public function nuevoUsuario(Request $request, UserPasswordEncoderInterface $passwordEncoder)
     {
       $usu_repository = $this->getDoctrine()->getRepository(Usuario::class);
       $usuario=new Usuario();
       $form = $this->createForm(UsuarioFormType::class,$usuario);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
         //Contraseña
         $usuario->setPassword(
           $passwordEncoder->encodePassword(
             $usuario,
             $form->get('plainPassword')->getData()
             )
           );
         //End Contraseña
         //Roles
          $roles = array("ROLE_USER"); //ROLE_USER: Todos los usuarios
          if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            //Solo Super admin puede añadir rol admin
            if($request->get('rol_admin') != null){
              $roles[]="ROLE_ADMIN";
            }
          }
          if($request->get('rol_crm') != null){
            $roles[]="ROLE_CRM";
          }
          if($request->get('rol_fiscal') != null)
          {
            $roles[]="ROLE_FISCAL";
          }
          $usuario->setRoles($roles);
          //End Roles
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($usuario);
         $entityManager->flush();
         return $this->redirectToRoute("listausuarios");
       }
       return $this->render('core/usuarionuevo.html.twig', [
         "form"=>$form->createView()
       ]);
     }

     /**
      * @Route("/{_locale}/usuario/{id}", name="vereditarusuario")
      * @IsGranted({"ROLE_ADMIN"})
      */
     public function verEditarUsuario($id, Request $request)
     {
       $msg_success=array();
       $usu_repository = $this->getDoctrine()->getRepository(Usuario::class);
       $usuario = $usu_repository->findOneById($id);
       if($usuario==null)return $this->redirectToRoute("listausuarios");

       $form = $this->createForm(UsuarioFormEditType::class,$usuario);

       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
         //Roles
         $roles = $usuario->getRoles();
         if ($this->isGranted('ROLE_SUPER_ADMIN')) {
           //Solo Super admin puede cambiar rol admin
           if($request->get('rol_admin') != null && !in_array('ROLE_ADMIN',$roles)){
             $roles[]="ROLE_ADMIN";
           }else if($request->get('rol_admin') == null && in_array('ROLE_ADMIN',$roles)){
              $roles = array_diff($roles,array("ROLE_ADMIN"));
           }
         }
         if($request->get('rol_crm') != null){
           $roles[]="ROLE_CRM";
         }else{
           $roles = array_diff($roles,array("ROLE_CRM"));
         }

         if($request->get('rol_fiscal') != null){
           $roles[]="ROLE_FISCAL";
         }else{
           $roles = array_diff($roles,array("ROLE_FISCAL"));
         }

         $usuario->setRoles($roles);
         //END: Roles
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($usuario);
         $entityManager->flush();
         $msg_success[]="Se han actualizado correctamente los datos del usuario";
       }

       return $this->render('core/usuario.html.twig', [
         "form"=>$form->createView(),
         "roles"=>$usuario->getRoles(),
         "msg_success"=>$msg_success,
         "userid"=>$usuario->getId()
       ]);
     }

     /**
      * @Route("/{_locale}/usuario/{id}/cambiarpassword", name="cambiarpassword")
      * @IsGranted({"ROLE_ADMIN"})
      */
      public function cambiarPassword($id, Request $request, UserPasswordEncoderInterface $passwordEncoder)
      {
        if ($this->isGranted('ROLE_ADMIN') || $this->getUser()->getId() == $id) {
          $msg_success = array();
          $msg_error = array();
          if($request->get("password1")!=null && $request->get("password2")!=null){
            $p1 = $request->get("password1");
            $p2 = $request->get("password2");
            //TODO: Implementar validación de password
            if(strlen($p1)<6){
              $msg_error[]="La contraseña no puede ser menor de 6 carácteres";
            }else if($p1 != $p2){
              $msg_error[]="Las contraseñas no coinciden";
            }else{
              $usurepo = $this->getDoctrine()->getRepository(Usuario::class);
              $usuario = $usurepo->findOneById($id);
              $usuario->setPassword(
                $passwordEncoder->encodePassword(
                  $usuario,
                  $p1
                )
              );
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($usuario);
              $entityManager->flush();
              $msg_success[]="Contraseña cambiada correctamente!";
            }

          }
          return $this->render('core/cambiarpassword.html.twig',array("msg_success"=>$msg_success,"msg_error"=>$msg_error));
        }else{
          return $this->redirectToRoute("dashboard");
        }
      }

      /**
       * @Route("/{_locale}/usuario/{id}/eliminar", name="eliminar_usuario")
       * @IsGranted({"ROLE_ADMIN"})
       */
      public function eliminarUsuario($id)
      {

          $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);
          $usuario = $usuarioRepository->findOneById($id);

          if($usuario==null)
            throw $this->createNotFoundException('No existe el usuario con el id especificado');

          if(in_array("ROLE_SUPER_ADMIN",$usuario->getRoles())){
            throw $this->createNotFoundException('No se puede eliminar el usuario superadministrador');
          }

          if($usuario->getOportunidadesVentas()->count()>0)
            throw $this->createNotFoundException('El usuario tiene oportunidades de venta asociadas, no se puede eliminar');

          $em = $this->getDoctrine()->getManager();
          $em->remove($usuario);
          $em->flush();

          return $this->redirectToRoute("listausuarios");

      }



}
