<?php

namespace Gps\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\Datasource\EntityInterface;
use Gps\Exception\MissingCoordinateException;
use Gps\Exception\InvalidTypeException;


class GpsBehavior extends Behavior{

	protected $_defaultConfig = [
        'radius' => 6373044.737,
        'fields'=>[
        	'latitude'	=>'latitude',
        	'longitude'	=>'longitude'
        ]
    ];

	public function findNear(Query $query, array $options){
        if(isset($options['gps']['latitude']) && isset($options['gps']['longitude']) && isset($options['gps']['radius'])){

            $optionFields = ['latitude','longitude','radius'];
            foreach($optionFields as $fieldName){
                if(!is_numeric($options['gps'][$fieldName])){
                    throw new InvalidTypeException(__("Search field %s must be numeric"),$fieldName);
                }
            }

            extract($this->config());

            list($minLat,$maxLat) = $this->_getMinMax($options['gps']['latitude'],$options['gps']['radius']);
            list($minLon,$maxLon) = $this->_getMinMax($options['gps']['longitude'],$options['gps']['radius']);

            $query->where(function ($exp, $q) use($fields,$minLat,$maxLat,$minLon,$maxLon) {
                $exp->between($fields['latitude'], $minLat, $maxLat);
                $exp->between($fields['longitude'], $minLon, $maxLon);
                return $exp;
            });

            $query->where([$fields['latitude'].' IS NOT'=>null]);
            $query->where([$fields['longitude'].' IS NOT'=>null]);
        }
        unset($options['gps']['radius']);
        unset($options['gps']['latitude']);
        unset($options['gps']['longitude']);

		return $query;
	}

    protected function _getMinMax($coordinate,$choosenRadius = 0){
        extract($this->config());
        $return[] = $coordinate - $choosenRadius / $radius;
        $return[] = $coordinate + $choosenRadius / $radius;
        return $return;
    }
}