<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT a FROM AppBundle:Event a WHERE a.dateStart > :today")
            ->setParameter('today', new \DateTime());
        $patate = $query->getResult();
        $return_arr = array();
        foreach ($patate as $point) {
            $row_array['lat'] = $point->getLat();
            $row_array['lng'] = $point->getLng() ;
            $row_array['name'] = $point->getName();
            $row_array['address'] = $point->getAddress();
            $row_array['description'] = $point->getDescription();
            $row_array['id'] = $point->getId();
            array_push($return_arr,$row_array);
        }

        return $this->render('default/index.html.twig', array('marker' => json_encode($return_arr)));
    }

    /**
     *Display a map with All event on it.
     *
     * To do : do something for description, put good name in it, display event qui ne sont pas passee.
     * @Route("/map", name="map")
     */
    public function mapAll(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM AppBundle:Event a";
        $query = $em->createQuery($dql);
        $patate = $query->getResult();
        $return_arr = array();
        foreach ($patate as $point) {
            $row_array['lat'] = $point->getLat();
            $row_array['lng'] = $point->getLng() ;
            $row_array['name'] = $point->getName();
            $row_array['address'] = $point->getAddress();
            $row_array['description'] = $point->getDescription();
            $row_array['id'] = $point->getId();
            array_push($return_arr,$row_array);
        }

        return $this->render('map.html.twig', array('marker' => json_encode($return_arr)));

    }


}
