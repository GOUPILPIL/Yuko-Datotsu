<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Event');

        $event = $repository->findAll();
        $event = new Event();
        $form = $this->createFormBuilder($event)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save name'))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $userID = $this->getUser()->getId();
            $event->setCreatedAt(new \DateTime());
            $event->setPostedBy($userID);
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('posts.index');
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
