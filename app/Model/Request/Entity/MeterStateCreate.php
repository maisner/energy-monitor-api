<?php


namespace App\Model\Request\Entity;


use Apitte\Core\Mapping\Request\BasicEntity;

class MeterStateCreate extends BasicEntity {

	/** @var string */
	public $commodity;

	/** @var string */
	public $datetime;

	/** @var float */
	public $state;

}
