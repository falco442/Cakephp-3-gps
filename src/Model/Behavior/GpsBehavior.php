<?php

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\Datasource\EntityInterface;

class GpsBehavior extends Behavior{

	protected $_defaultConfig = [
        'radius' => 6373044.737,
        'model'=>'Stores',
        'fields'=>[
        	'latitude'	=>'latitude',
        	'longitude'	=>'longitude'
        ]
    ];

	public function findNear(Query $query, array $options){
		return $query;
	}
}