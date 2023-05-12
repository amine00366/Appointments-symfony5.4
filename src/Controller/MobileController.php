<?php

namespace App\Controller;

use App\Entity\Typeappoinment;
use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Form\TypeappoinmentType;
use App\Repository\TypeappoinmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\AppointmentService;
use Symfony\Component\HttpFoundation\JsonResponse;

class MobileController extends AbstractController
{
    private $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }
    #[Route('/mobile', name: 'app_mobile')]
    public function index(): Response
    {
        return $this->render('mobile/index.html.twig', [
            'controller_name' => 'MobileController',
        ]);
    }
     /**
     * @Route("/affichemobile", name="app_mobile_list")
     */
    public function list(TypeappoinmentRepository $repository  , Request $request, NormalizerInterface $normalizer,SerializerInterface $serializer)
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
        $studentsNormalises = $normalizer->normalize($typeappointment, 'json', ['groups' => "Typeappoinment"]);
    
        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        $json = json_encode($studentsNormalises);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }


        ///

      
      

        #[Route('/addpostjs', name: 'Create_postjs', methods: ["GET","POST"])]

    public function ferActionjs(Request $request,NormalizerInterface $normalizer)
    {   
    $stock = new Typeappoinment();
        $nomtype = $request->get("nomtype");
        $description = $request->get("description");
        
        $stock->setNomtype($nomtype);
        $stock->setDescription($description);
           

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stock);
            $entityManager->flush();
            
            $this->addFlash('info', 'Created Successfully !');
        
        $studentsNormalises = $normalizer->normalize($stock, 'json', ['groups' => "Typeappoinment"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        $json = json_encode($studentsNormalises);
        return new Response($json);
 }

//  #[Route('/updatepostjs/{id}', name: 'update_postjs', methods: ["GET","POST"])]

//  public function updatejs(Request $request,NormalizerInterface $normalizer, $id)
//  {   
 
//  $entityManager = $this->getDoctrine()->getManager();
//  $stock = $entityManager->getRepository(Typeappoinment::class)->find($id);
//      $nomtype = $request->request->get("nomtype");
//      $description = $request->request->get("description");
     
//      $stock->setNomtype($nomtype);
//      $stock->setDescription($description);
        

//          $entityManager = $this->getDoctrine()->getManager();
//          $entityManager->persist($stock);
//          $entityManager->flush();
         
         
     
//      $studentsNormalises = $normalizer->normalize($stock, 'json', ['groups' => "Typeappoinment"]);

//      // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
//      $json = json_encode($studentsNormalises);
//      return new Response($json);
// }

//////////////////////
#[Route('/updatepostjs/{id}', name: 'update_postjs', methods: ['GET','POST'])]
public function indexs(Request $request, $id, SerializerInterface $serializer): JsonResponse
{
    $em = $this->getDoctrine()->getManager();
    $categoriesvehicules = $em->getRepository(Typeappoinment::class)->find($id);
    if (!$categoriesvehicules) {
        throw $this->createNotFoundException(
            'pas de type  '.$id
        );
    }
    $typecatv = $request->get('nomtype');
    $typecatv = $request->get('description');  
    $categoriesvehicules->setNomtype($typecatv);
    $categoriesvehicules->setDescription($typecatv);

    $em->flush();

    $jsonContent = $serializer->serialize($categoriesvehicules, 'json', ['groups' => 'Typeappoinment']);
    return new JsonResponse(json_decode($jsonContent));
}










/////////////////////////

 #[Route('/delete_postjson', name: 'delete_postjson', methods: ["GET","POST"])]

 public function deleteupdate(Request $request,NormalizerInterface $normalizer): Response
 {
     $id = $request->get('id');
     $entityManager = $this->getDoctrine()->getManager();
     $post = $entityManager->getRepository(Typeappoinment::class)->find($id);
     $entityManager->remove($post);
     $entityManager->flush();
     $studentsNormalises = $normalizer->normalize($post, 'json', ['groups' => "Typeappoinment"]);
     $json = json_encode($studentsNormalises);
     return new Response($json);


}

 

        
      

    ///////////////////
    
    ///////////
    #[Route("/addnewap", name: "appoin_ajout" ,methods: ["GET","POST"])]
    public function addAppointment( Request $request, EntityManagerInterface $entityManager, NormalizerInterface $normalizer)
{   
    $data = json_decode($request->getContent(), true);
    $appointment = new Appointment();

    
    $catv = $entityManager->getRepository(Typeappoinment::class)->findOneBy(['nomtype' => $data['type']]);
    $appointment->setType($catv);
    $appointment->setCategorie($data['categorie']);
    
    $appointment->setAppointmentDate(new \DateTime($data['appointmentDate']));
    $appointment->setDatefin((clone $appointment->getAppointmentDate())->add(new \DateInterval('PT30M')));
       
       // $appointment = new Appointment();
      
    
       $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appointment);
            $entityManager->flush();

         

        $appointmentNormalised = $normalizer->normalize($appointment, 'json', ['groups' => 'appoinment']);
       $json = json_encode($appointmentNormalised);
       
        return new Response($json);
   
}




   }



