<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Typeappoinment;
use App\Form\TypeappoinmentType;
use App\Repository\TypeappoinmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class TypeapoinmentController extends AbstractController
{
    #[Route('/typeapoinment', name: 'app_typeapoinment')]
    public function index(): Response
    {
        return $this->render('typeapoinment/index.html.twig', [
            'controller_name' => 'TypeapoinmentController',
        ]);
    }

    /**
     * @Route("/listetype", name="app_type_list")
     */
    public function list(TypeappoinmentRepository $repository ,Request $request ,PaginatorInterface $paginator)
    {
        $typeappointment= $this->getDoctrine()->getRepository(Typeappoinment::class)->findAll();

        ////
        $back = null;
            
        if($request->isMethod("POST")){
            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                    case 'nomtype':
                        $typeappointment = $repository->SortBynomtype();
                        break;
         
                }} else
                {
                    $type = $request->request->get('optionsearch');
                    $value = $request->request->get('Search');
                    switch ($type){
                        case 'nomtype':
                            $typeappointment = $repository->findBynomtype($value);
                            break;
    
                        
    
                    }
                }
            if ( $typeappointment){
                $back = "success";
            }else{
                $back = "failure";
            }
        }
        $typeappointment = $paginator->paginate(
            $typeappointment, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/
        );
    return $this->render('admin/list.html.twig',['types'=>$typeappointment,'back'=>$back]);
    }



     /**
     * @Route("/listetypepatient", name="app_type_patient")
     */
    public function listpourpatient(TypeappoinmentRepository $repository ,Request $request ,PaginatorInterface $paginator)
    {
        $typeappointment= $this->getDoctrine()->getRepository(Typeappoinment::class)->findAll();

    return $this->render('appointments/listypepourpatient.html.twig',['types'=>$typeappointment]);
    }

    

  
///////////////////
    #[Route('/addappoinment', name: 'app_addappoinment')]
    public function new(Request $request): Response
    {
        $typeappoinment = new Typeappoinment();
        $form = $this->createForm(TypeappoinmentType::class, $typeappoinment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($typeappoinment);
            // exécute réellement les requêtes (c'est-à-dire la requête INSERT)
            $entityManager->flush();
            // redirection vers la page d'accueil
            return $this->redirectToRoute('app_typeapoinment');
        }

        return $this->render('admin/new.html.twig', [
            'auteur' => $typeappoinment,
            'form' => $form->createView(),
        ]);
    }

      //delete une reservation 
      #[Route('/delete_type/{id}', name: 'delete_type')]

      public function deletetype(Request $request): Response
      {
          $id = $request->get('id');
          $entityManager = $this->getDoctrine()->getManager();
          $post = $entityManager->getRepository(Typeappoinment::class)->find($id);
          $entityManager->remove($post);
          $entityManager->flush();
          return $this->redirectToRoute('app_addappoinment');
      }


      #[Route('/edittype/{id}', name: 'edit_type')]
      public function edit(Request $request, Typeappoinment $auteur): Response
      {
          $form = $this->createForm(TypeappoinmentType::class, $auteur);
          $form->handleRequest($request);
  
          if ($form->isSubmitted() && $form->isValid()) {
              $this->getDoctrine()->getManager()->flush();
  
              return $this->redirectToRoute('app_addappoinment');
          }
  
          return $this->render('admin/new.html.twig', [
              'auteur' => $auteur,
              'form' => $form->createView(),
          ]);
      }
      
      /////

      #[Route('/searchAJ', name: 'searchAJ')]

      public function searchAJ(Request $request)
      {
          $em = $this->getDoctrine()->getManager();
          $requestString = $request->get('q');
          $typeappointment = $em->getRepository(Typeappoinment::class)->findEntitiesByString($requestString);
          if (!$typeappointment) {
              $result['typeappointment']['error'] = "stock not found 🙁";
          } else {
                   $result['typeappointment'] = $this->getRealEntities($typeappointment);
                 }
          return new Response(json_encode($result));
      }
        
      public function getRealEntities($typeappointment)
      {
          foreach ($typeappointment as $typeappointment) {
              $realEntities[$typeappointment->getId()] = [$typeappointment->getNomtype(), $typeappointment->getDescription()];
          }
      return $realEntities;
      }

      #[Route('/afficheuntype/{id}', name: 'app_stock_show' )]
      public function show($id)
      {   
          $entityManageree=$this->getDoctrine()->getManager();
          $stock = $entityManageree->getRepository(Typeappoinment::class)->find($id);
          return $this->render('admin/show.html.twig', [
              'id' => $stock->getId(),
              'nom' => $stock->getNomtype(),
              'description' => $stock->getDescription(),
              
          ]);
      }

   
    ///////////////

    
        ///////////
}
