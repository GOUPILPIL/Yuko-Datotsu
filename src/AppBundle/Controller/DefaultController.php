<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\GeoHelper;
use AppBundle\Form\EventFormType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/event/create", name="eventCreation")
     */
    public function eventCreation(Request $request, GeoHelper $geoHelper)
    {
        $event = new Event();

        $form = $this->createForm(EventFormType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userID = $this->getUser()->getId();
            $UserPdo = $this->getDoctrine()->getRepository('AppBundle:User');
            $UserOjbect = $UserPdo->findOneById($userID);

            $em = $this->getDoctrine()->getManager();
            $data = $form["address"]->getData();

            $latLong = $geoHelper->getLatLong($data);
            $latitude = $latLong['latitude'] ? $latLong['latitude'] : '0';
            $longitude = $latLong['longitude'] ? $latLong['longitude'] : '0';

            $event->setLat($latitude);
            $event->setLng($longitude);

            $event->setUser($UserOjbect);

            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('events/create.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/event", name="event")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM AppBundle:Event a ORDER BY a.dateStart ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        // parameters to template
        return $this->render('events/events.paginator.html.twig', array('pagination' => $pagination));
    }


    /**
     * @Route("/event/{event}", name="eventView")
     */
    public function eventView(request $request,Event $event)
    {

        $userID = $this->getUser();
        $userFetched = $event->getUser();

        if($userID == $userFetched)
        {
            $isAlive = 1;
        }else {
            $isAlive = 0;
        }

        return $this->render('events/event.view.html.twig', array('event' => $event, 'isAlive' => $isAlive ));
    }

    /**
     * @Route("/event/delete/{id}", name="deleteView")
     */
    public function deleteView(request $request,Event $event)
    {

        $userID = $this->getUser();
        $userFetched = $event->getUser();

        if($userID == $userFetched)
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
            return new Response("deleted");
        }

        return new Response($event->getUser(). " + ". $this->getUser());
    }

    /**
     * @route("/event/edit/{event}", name="posts.edit", requirements={"id" = "\d+"}))
     */
    public function editAction(Request $request, Event $event)
    {
        $userID = $this->getUser();
        $userFetched = $event->getUser();

        if($userID != $userFetched)
        {
            return new Response("GTFO");
        }

        $form = $this->createForm(EventFormType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('eventView', array('event'=> $event->getId()));
        }

        return $this->render('events/create.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
