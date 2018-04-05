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
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/map", name="map")
     */
    public function mapAll(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM AppBundle:Event a";
        $query = $em->createQuery($dql);
        $patate = $query->getResult();
        $return_arr = array();
        $return_arr[0] = "coucou";
        foreach ($patate as $point) {
            $row_array['lat'] = $point->getLat();
            $row_array['lng'] = $point->getLng() ;
            $row_array['name'] = $point->getName();
            $row_array['address'] = $point->getAddress();
            $row_array['description'] = $point->getDescription();
            $row_array['id'] = $point->getId();
            array_push($return_arr,$row_array);
        }

        return $this->render('events/map.html.twig', array('marker' => json_encode($return_arr)));

    }


}
