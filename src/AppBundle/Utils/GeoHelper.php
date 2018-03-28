<?php
/**
 * Created by PhpStorm.
 * User: Zephor
 * Date: 3/28/18
 * Time: 11:45 AM
 */

namespace AppBundle\Utils;

class GeoHelper
{

    public function getLatLong($address){
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

}