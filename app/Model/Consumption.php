<?php

namespace App\Model;


use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Nette\Utils\DateTime;

class Consumption extends Entity {

	use SmartObject;

	/** @var int */
	public $id;

	/** @var int */
	public $meterStateId;

	/** @var int */
	public $month;

	/** @var DateTime */
	public $year;

	/** @var float */
	public $averageDayConsumption;

	public static function fromRow(ActiveRow $row): Consumption {
		$consumption = new Consumption();
		$consumption->id = (int)$row->offsetGet('id');
		$consumption->meterStateId = (int)$row->offsetGet('meter_state_id');
		$consumption->month = $row->offsetGet('month');
		$consumption->year = $row->offsetGet('year');
		$consumption->averageDayConsumption = (float)$row->offsetGet('average_day_consumption');

		return $consumption;
	}
}
