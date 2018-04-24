<?php
/**
 * Created by PhpStorm.
 * User: Zephor
 * Date: 4/5/18
 * Time: 11:11 AM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\GeoHelper;
use AppBundle\Form\EventFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EventController extends Controller
{
    /**
     * Display a event creation form, when form is submitted by user, isValid check if everything is ok with user input
     * then we put information into new Event Object and persist the Object into the database via Orm.
     *
     * GeoHelper object give you latitute and longitude of an address.
     *
     * To do : Redirect to /event/{newEventId}
     *
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
        return $this->render('events/event.create.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * Display 5 event (basic information and link to full view of the event) in a page with a link to another page with 5 next event.
     * Order by date
     *
     * To do : Dql into Repository
     *
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
     * Display event with description, name, map.
     * isAlive variable contain 1 if user is the event owner (the view will display a edit & delete button), 0 in other case
     *
     * @Route("/event/{event}",options = { "expose" = true }, name="eventView", requirements={"event" = "\d+"})
     */
    public function eventView(request $request,Event $event)
    {
        $isAlive = ($this->getUser() == $event->getUser()) ? 1 : 0;
        return $this->render('events/event.view.html.twig', array('event' => $event, 'isAlive' => $isAlive ));
    }

    /**
     * Delete event if you are the club owner then give a response with delete,
     * if you are not the owner, return a response with message
     *
     * @Route("/event/delete/{event}", name="deleteView", requirements={"event" = "\d+"})
     */
    public function deleteView(request $request,Event $event)
    {

        if($this->getUser() == $event->getUser())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
            return new Response("deleted");
        }

        return new Response("You are not the club owner !");
    }

    /**
     * You can edit an event if you are the club owner, work like eventCreation without new Object,
     * editAction take Event object (by id) in parameter.
     *
     * if you are not the owner, return a response with message
     * @route("/event/edit/{event}", name="editView", requirements={"event" = "\d+"}))
     */
    public function editAction(Request $request, Event $event, GeoHelper $geoHelper)
    {

        if($this->getUser() != $event->getUser())
        {
            return new Response("You are not the club owner !");
        }

        $form = $this->createForm(EventFormType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form["address"]->getData();

            $latLong = $geoHelper->getLatLong($data);
            $latitude = $latLong['latitude'] ? $latLong['latitude'] : '0';
            $longitude = $latLong['longitude'] ? $latLong['longitude'] : '0';

            $event->setLat($latitude);
            $event->setLng($longitude);
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('eventView', array('event'=> $event->getId()));
        }

        return $this->render('events/event.create.html.twig', [
            'form' => $form->createView()
        ]);

    }
    /**
     * Display Search form and redirect to event.search.arg with the search string
     *
     * @route("/event/search", name="event.search")
     */
    public function searchAction(Request $request)
    {
        $searchform = $this->createFormBuilder()
            ->add('search', TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $searchform->handleRequest($request);

        if($searchform->isSubmitted())
        {
            $searchstring = $searchform->getData();
            return $this->redirectToRoute('event.search.arg', $searchstring );
        }

        return $this->render('events/events.search.html.twig', [
            'searchform' => $searchform->createView(),
            'events' => null
        ]);

    }
    /**
     * Search into database by name in description and name event look at findByWildStarName for more information.
     *
     * To Do : Order by date, create a form.
     *
     * @route("/event/search/{search}", name="event.search.arg")
     * @Method({"POST", "GET"})
     */
    public function searchArg(Request $request, $search)
    {
        $searchform = $this->createFormBuilder()
            ->add('search', TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $searchform->handleRequest($request);

        if($searchform->isSubmitted())
        {
            $searchstring = $searchform->getData();
            return $this->redirectToRoute('event.search.arg', $searchstring );
        }

        $response = $this->getDoctrine()->getRepository('AppBundle:Event')->findByWildStarName($search);

        return $this->render('events/events.search.html.twig', [
            'searchform' => $searchform->createView(),
            'events' => $response
        ]);

    }
}