<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Typeappoinment;
use App\Entity\User;
use App\Form\AppointmentType;
use App\Form\DoctorType;
use App\Repository\AppointmentRepository;
use App\Repository\TypeappoinmentRepository;
use App\Service\AppointmentService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MercurySeries\FlashyBundle\FlashyNotifier;

use Psr\Log\LoggerInterface;

class DoctorController extends AbstractController
{
    private $appointmentService;
    private $flashy;
    private $logger;


    public function __construct(AppointmentService $appointmentService,LoggerInterface $logger,FlashyNotifier $flashy)
    {
        $this->appointmentService = $appointmentService;
        $this->logger = $logger;
        $this->flashy = $flashy;
    }
    #[Route('/doctor/approve/{id}', name: 'admin_postcomments_approve')]
   
    public function aapprove(Appointment $appointment): RedirectResponse
    {
        $appointment->setApproved(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($appointment);
        $entityManager->flush();

        return $this->redirectToRoute('app_show');
    }



    /////////////////////////////
////////// liste des rendez vous d'un médecin avec barre de recherche 
/**
     * @Route("/listR/{id}", name="app_recherche") 
     */
    public function listrecherche(AppointmentRepository $repository ,Request $request ,$id )
    {
        $reservations= $this->getDoctrine()->getRepository(Appointment::class)->findBy(['doctor' => $id]);

        ////
        $back = null;
      
        if($request->isMethod("POST")){
           
                
                    $type = $request->request->get('optionsearch');
                    $value = $request->request->get('Search');
                    switch ($type){
                        case 'categorie':
                            $reservations = $repository->findBycategorieee($value,$id);//
                            break;
                            case 'appointmentDate':
                            $reservations = $repository->findBydate($value,$id);//
                            break;
                           
                           
                            
                    }
                }
            if ( $reservations){
                $back = "success";
            }else{
                $back = "failure";
            }
        
       
    return $this->render('admin/njareb.html.twig',['reservations'=>$reservations,'back'=>$back]);
    }










    //////////////////////

    //Liste d'un médecin avec tri par date Final
    #[Route('/listmedtri/{id}', name: 'app_store_dateTri')]
    public function liste(Request $request, AppointmentRepository $appointmentRepository, $id)
    {
        $this->logger->info("test");
        // Récupérer tous les produits
        $reservations = $appointmentRepository->findBy(['doctor' => $id]);
        // Traiter la soumission du formulaire de tri
        $triDescendant = false;
        $triAscendant = false;
        if ($request->getMethod() === 'POST') {
            $triDescendant = $request->request->get('tri_descendant');
            $triAscendant = $request->request->get('tri_ascendant');
            $this->logger->info($triAscendant);
            $this->logger->info($triDescendant);
        }

        // Si le tri descendant est sélectionné
        if ($triDescendant) {
           

            $reservations = $appointmentRepository->findBydateDesc($id);
            
            
        }
        // Si le tri ascendant est sélectionné
        else if ($triAscendant) {
            $reservations = $appointmentRepository->findBydateAsc($id);
        }

        return $this->render('doctor/Tridate.html.twig', [
            'reservations' => $reservations,
            'tri_descendant' => $triDescendant,
            'tri_ascendant' => $triAscendant,
        ]);
    

   
 }
// la liste des médecin 
    /**
     * @Route("/doctors", name="app_doctor_list")
     */
    public function list()
    {
        $doctors = $this->getDoctrine()->getRepository(Doctor::class)->findAll();

        return $this->render('doctor/list.html.twig', [
            'doctors' => $doctors,
            
        ]);
    }

 /**
     * @Route("/ajax", name="app_ajax")
     */
   
// ajout d'un rendez vous avec un docteur spécifique 
    /**
     * @Route("/reserve/{id}", name="reserve_appointment", methods={"GET","POST"})
     */

    public function profile(Doctor $doctor, Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy)
    {
        
        // Create the form object
        $form = $this->createForm(AppointmentType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the current user
            $user = $this->getUser();
            
            $typeReservation = $form->get('type')->getData();
            // Get the appointment date from the form data
            $appointmentDate = new \DateTime($form->get('appointmentDate')->getData()->format('Y-m-d H:i:s'));
            $datefin = (clone $appointmentDate)->add(new \DateInterval('PT30M'));
            
            // $appointmentDateString =  $appointmentDate->format('Y-m-d H:i:s');

            if (!$this->appointmentService->checkAppointmentAvailability($appointmentDate, $doctor->getId())) {
               /* throw new \Exception('The appointment date is not available');*/
                return $this->redirectToRoute('appointment_error'); 
                /*$this->addFlash('error', 'cette date est réserée');*/
               /* $this->flashy->success('Le rendez-vous non .', 'http://your-awesome-link.com');*/
                
                
            }

            // Create a new appointment entity
            $appointment = new Appointment();
            $appointment->setCategorie($form->get('categorie')->getData());
            $appointment->setDoctor($doctor);
            $appointment->setUser($user);
            $appointment->setAppointmentDate($appointmentDate);
            $appointment->setDatefin($datefin); 
            $appointment->setType($typeReservation);   
            
           

            // Save the appointment to the database
            $entityManager->persist($appointment);
            $entityManager->flush();
            $flashy>$this->addFlash('success', 'Le formulaire a été soumis avec succès.');
            return $this->redirectToRoute('app_appointments');
           
            

            
        }

        return $this->render('doctor/profile.html.twig', [
            'doctor' => $doctor,
            'form' => $form->createView(),
            'flashy' => $flashy
           
      
        ]);
    }


  /**
     * @Route("/rech", name="rech_appointment", methods={"GET","POST"})
     */
    public function ajax()
    {
       

        return $this->render('appointments/searchajax.html.twig', [
           
        ]);
    }
    /**
     * @Route("/appointment/success", name="appointment_success")
     */
    public function appointmenterror()
    {
        return $this->render('appointment/success.html.twig');
    }
     /**
     * @Route("/appointment/error", name="appointment_error")
     */
    public function appointmentSuccess()
    {
        return $this->render('appointment/error.html.twig');
    }

  
    /**
     * @Route("/appointments", name="app_appointments")
     */
    public function listAppointments(Request $request ,TypeappoinmentRepository $repository )
    {   
      
       
        //$typeappointment= $this->getDoctrine()->getRepository(Typeappoinment::class)->findAll();
        // Get the user's ID
        //once the user is logged in
        $user = $request->get('$user');

        // Get the appointments by the user's ID
        $appointments = $this->getDoctrine()
            ->getRepository(Appointment::class)
            ->findBy(['user' => 3]);

        // Render the template and pass the appointments as a parameter
        return $this->render('appointments/list.html.twig', [
            'appointments' => $appointments,
            //'type' =>$typeappointment

            
        ]);
    }



 /////////// hedhi filtrage by type 
     #[Route('/listbyCat/{id}', name: 'List_By_type')]
     public function show(AppointmentRepository $appoinmentrepo,$id,PaginatorInterface $paginator,Request $request)
     {
        
         $categorie = $this->getDoctrine()->getRepository(Typeappoinment::class)->find($id);
         $reservations= $appoinmentrepo->findBytype($categorie->getId());
         $reservations = $paginator->paginate(
            $reservations, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
         return $this->render('admin/listreservation.html.twig', [
             "type" => $categorie,
             'reservations' => $reservations,
            
             ]);
     }





//// hedhi statistique 
     #[Route('/state', name: 'stat', methods: ['GET', 'POST'])]
     public function statistiques(AppointmentRepository  $commandeRepository){
         // On va chercher toutes les catégories
 
         $commande = $commandeRepository->countByDate();
         $dates = [];
         $commandeCount = [];
         //$categColor = [];
         foreach($commande as $com){
             $dates[] = $com['appointmentDate'];
             $commandeCount[] = $com['count'];
         }
         return $this->render('doctor/statistique.html.twig', [
             'dates' => json_encode($dates),
             'commandeCount' => json_encode($commandeCount),
         ]);
 
 
     }








    //les détail d'une réservation j'ai intégrer la map avec mapbox
    /**
     * @Route("/appointment/{id}", name="app_appointment_detail")
     */
    public function appointmentDetails(Appointment $appointment, Request $request, EntityManagerInterface $entityManager)
    {
        return $this->render('appointments/appointment.html.twig', [
            'appointment' => $appointment,

        ]);
    }
    //liste reservation des rendez-vous d'un médecin
    #[Route('/reservation/{id}', name: 'app_show')]
    public function RES (Doctor $user, AppointmentRepository $repository): Response
    {
        $reservations = $repository->findBy(['doctor' => $user]);
        
        return $this->render('appointments/listmed.html.twig', [
            'reservations' => $reservations,
            /*'user' => $user,*/
        ]);
    }


    ////////////// liste des tous les rendezvous 


    #[Route('/listeall', name: 'listeall')]
    public function listeall (AppointmentRepository $repository,PaginatorInterface $paginator,Request $request): Response
    {
        $typeappointment= $this->getDoctrine()->getRepository(Typeappoinment::class)->findAll();
        $reservations = $repository->findAll();
        $reservations = $paginator->paginate(
            $reservations, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('admin/listreservation.html.twig', [
            'reservations' => $reservations,
            'type' =>$typeappointment
            /*'user' => $user,*/
        ]);
    }

    //delete une reservation pour patient 
    #[Route('/delete_reserv/{id}', name: 'delete_res')]

    public function deletepostAction(Request $request): Response
    {
        $id = $request->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Appointment::class)->find($id);
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute('app_appointments');
    }

    //delete une reservation pour patient 
    #[Route('/delete_reservation/{id}', name: 'delete_reservation')]

    public function deleteReservationAction(Request $request): Response
    {
        $id = $request->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Appointment::class)->find($id);
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute('app_appointments');
    }






    //////////////   calendrier d'un médecin  déja verifier temchi 

    #[Route('/calen/{id}', name: 'app_calen', methods: ['GET'])]
    public function calen(Doctor $user, AppointmentRepository $repository)
    {
        $reservationsapprouve = $repository->findBy(['doctor' => $user,'approved'=>1]);
        $reservationsnonapprouve = $repository->findBy(['doctor' => $user,'approved'=>0]); //,'approved'=>1

       

        $rdvs = [];

        foreach ($reservationsapprouve as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getAppointmentDate()->format('Y-m-d H:i:s'),
                'end' => $event->getDatefin()->format('Y-m-d H:i:s'),
                'title' => $event->getCategorie(),
                'backgroundColor' => 'green',

            ];
        }
        foreach ($reservationsnonapprouve as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getAppointmentDate()->format('Y-m-d H:i:s'),
                'end' => $event->getDatefin()->format('Y-m-d H:i:s'),
                'title' => $event->getCategorie(),
                'backgroundColor' => 'red',

            ];
        }
        
        $data = json_encode($rdvs);
        //dd($data);
        

        return $this->render('doctor/showCalendarperdoctor.html.twig', compact('data'));
    }


    //////////////
   //modifier une réservation 
    /**
     * @Route("/{id}/edit", name="update-res", methods={"GET","POST"})
     */
    public function edit(Request $request, Appointment $auteur): Response
    {
        $form = $this->createForm(AppointmentType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_appointments');
        }

        return $this->render('appointments/edit.html.twig', [
            'auteur' => $auteur,
            'form' => $form->createView(),
        ]);
    }



    /// calendrier 
    #[Route('/cal', name: 'app_cal', methods: ['GET'])]
    public function cal(AppointmentRepository $appointmentRepository)
    {
        $events = $appointmentRepository->findAll();

        $rdvs = [];

        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getAppointmentDate()->format('Y-m-d H:i:s'),
                'end' => $event->getDatefin()->format('Y-m-d H:i:s'),
                'title' => $event->getCategorie(),
            ];
        }

     

        $data = json_encode($rdvs);

        return $this->render('doctor/showCalendar.html.twig', compact('data'));
    }








////// ajout d'un docteur avec un map 
    /**
     * @Route("/doctor/add", name="doctor_add")
     */
    public function add(Request $request): Response
    {
        $doctor = new Doctor();

        $form = $this->createForm(DoctorType::class, $doctor);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($doctor);
            $entityManager->flush();

            return $this->redirectToRoute('app_doctor_list');
        }

        $mapboxAccessToken = 'pk.eyJ1IjoiZmplcmJpIiwiYSI6ImNrdWp6bXJhdTE4MGwyd215bzhpb3c0OGYifQ.jW0ZovMg20DoAaiOtGkPhg';

        return $this->render('doctor/add.html.twig', [
            'form' => $form->createView(),
            'mapbox_access_token' => $mapboxAccessToken,
        ]);
    }

   
}




   


