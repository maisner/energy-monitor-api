<?php


namespace App\Model;


use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Nette\Utils\DateTime;

class MeterState extends Entity {

	use SmartObject;

	/** @var int */
	public $id;

	/** @var string */
	public $commodity;

	/** @var DateTime */
	public $datetime;

	/** @var float */
	public $state;

	public static function fromRow(ActiveRow $row): MeterState {
		$meterState = new MeterState();
		$meterState->id = (int)$row->offsetGet('id');
		$meterState->commodity = $row->offsetGet('commodity');
		$meterState->datetime = $row->offsetGet('datetime');
		$meterState->state = (float)$row->offsetGet('state');

		return $meterState;
	}
}
