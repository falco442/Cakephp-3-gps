<?php

namespace Gps\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\Datasource\EntityInterface;
use Gps\Exception\MissingCoordinateException;


class GpsBehavior extends Behavior{

	protected $_defaultConfig = [
        'radius' => 6373044.737,
        'fields'=>[
        	'latitude'	=>'latitude',
        	'longitude'	=>'longitude'
        ]
    ];

	public function findNear(Query $query, array $options){

        if(!isset($options['latitude']))
            throw new MissingCoordinateException("Latitude not present");
        if(!isset($options['longitude']))
            throw new MissingCoordinateException("Longitude not present");
        if(!isset($options['radius']))
            throw new MissingCoordinateException("Radius not present");

        extract($this->config());

        list($minLat,$maxLat) = $this->_getMinMax($options['latitude'],$options['radius']);
        list($minLon,$maxLon) = $this->_getMinMax($options['longitude'],$options['radius']);

        $query->where(function ($exp, $q) use($fields,$minLat,$maxLat,$minLon,$maxLon) {
            $exp->between($fields['latitude'], $minLat, $maxLat);
            $exp->between($fields['longitude'], $minLon, $maxLon);
            return $exp;
        });

        $query->where([$fields['latitude'].' IS NOT'=>null]);
        $query->where([$fields['longitude'].' IS NOT'=>null]);

		return $query;
	}

    protected function _getMinMax($coordinate,$choosenRadius = 0){
        extract($this->config());
        $return[] = $coordinate - $choosenRadius / $radius;
        $return[] = $coordinate + $choosenRadius / $radius;
    }
}