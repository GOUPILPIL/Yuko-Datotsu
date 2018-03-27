<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
        $userID = $this->getUser()->getId();
        $UserPdo = $this->getDoctrine()->getRepository('AppBundle:User');
        $UserOjbect = $UserPdo->findOneById($userID);


        $event = new Event();

        $form = $this->createFormBuilder($event)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('address', TextType::class)
            ->add('lag', NumberType::class)
            ->add('lng', NumberType::class)
            ->add('dateStart', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Save name'))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $event->setCreatedAt(new \DateTime());
            $event->setPostedBy(0);
            $event->setValidate(1);
            $event->setUser($UserOjbect);
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('/');
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
