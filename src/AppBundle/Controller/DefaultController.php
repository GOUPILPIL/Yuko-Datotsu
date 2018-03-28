<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/event/create", name="eventCreation")
     */
    public function eventCreation(Request $request)
    {
        $event = new Event();

        $form = $this->createFormBuilder($event)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('address', TextType::class)
            ->add('lag', HiddenType::class)
            ->add('lng', HiddenType::class)
            ->add('dateStart', DateType::class, array(
                'widget' => 'single_text'
            ))
            ->add('save', SubmitType::class, array('label' => 'Save name'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $userID = $this->getUser()->getId();
            $UserPdo = $this->getDoctrine()->getRepository('AppBundle:User');
            $UserOjbect = $UserPdo->findOneById($userID);

            $em = $this->getDoctrine()->getManager();
            $data = $form["address"]->getData();

            $event->setCreatedAt(new \DateTime());
            $event->setPostedBy(0);
            $event->setValidate(1);

            $latLong = getLatLong($data);
            $latitude = $latLong['latitude']?$latLong['latitude']:'0';
            $longitude = $latLong['longitude']?$latLong['longitude']:'0';
            $event->setLag($latitude);

            $event->setLng($longitude);
            $event->setUser($UserOjbect);

            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('events/index.html.twig', [
            'form' => $form->createView()
        ]);

    }
    /**
     * @Route("/test", name="test")
     */
    public function test()
    {
         $userID = $this->getUser()->getId();


        return new Response(
            '<html><body>Id is = '.$userID.'</body></html>'
        );
    }
}

function getLatLong($address){
    if(!empty($address)){
        //Formatted address
        $formattedAddr = str_replace(' ','+',$address);
        //Send request and receive json data by address
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=true_or_false&key=AIzaSyChgkCStI_CzqTWxuteujDUeEBF90it_h8');
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        $data['latitude']  = $output->results[0]->geometry->location->lat;
        $data['longitude'] = $output->results[0]->geometry->location->lng;
        //Return latitude and longitude of the given address
        if(!empty($data)){
            return $data;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
