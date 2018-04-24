<?php
/**
 * Created by PhpStorm.
 * User: Zephor
 * Date: 4/5/18
 * Time: 11:11 AM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Club;
use AppBundle\Entity\User;use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\GeoHelper;
use AppBundle\Form\ClubFormType;

class ClubController extends Controller
{
    /**
     * @Route("/club/create", name="clubCreation")
     */
    public function clubCreation(Request $request, GeoHelper $geoHelper)
    {
        $club = new Club();

        $form = $this->createForm(ClubFormType::class, $club); // SOMETHING TO DO

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

            $club->setLat($latitude);
            $club->setLng($longitude);

            $club->setUser($UserOjbect);

            $em->persist($club);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('clubs/club.create.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/club", name="club")
     */
    public function clubList (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM AppBundle:club a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        // parameters to template
        return $this->render('clubs/clubs.paginator.html.twig', array('pagination' => $pagination));
    }



    /**
     * @Route("/club/{club}",options = { "expose" = true }, name="clubView", requirements={"club" = "\d+"})
     */
    public function clubView(request $request,Club $club)
    {

        $isAlive = ($this->getUser() == $club->getUser()) ? 1 : 0;

        return $this->render('clubs/club.view.html.twig', array('club' => $club, 'isAlive' => $isAlive ));
    }

    /**
     * @Route("/club/delete/{club}", name="clubDelete", requirements={"club" = "\d+"})
     */
    public function clubDelete(request $request,Club $club)
    {

        $userID = $this->getUser();
        $userFetched = $club->getUser();

        if($userID == $userFetched)
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($club);
            $em->flush();
            return new Response("deleted");
        }

        return new Response($club->getUser(). " + ". $this->getUser());
    }

    /**
     * @route("/club/edit/{club}", name="clubEdit", requirements={"club" = "\d+"}))
     */
    public function clubEdit(Request $request, Club $club)
    {
        $userID = $this->getUser();
        $userFetched = $club->getUser();

        if($userID != $userFetched)
        {
            return new Response("GTFO");
        }

        $form = $this->createForm(clubFormType::class, $club);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();
            return $this->redirectToRoute('clubView', array('club'=> $club->getId()));
        }

        return $this->render('clubs/club.create.html.twig', [
            'form' => $form->createView()
        ]);

    }
}