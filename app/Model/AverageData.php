<?php


namespace App\Model;


use Nette\Database\Table\ActiveRow;

class AverageData {

	/** @var float */
	public $day_consumption;

	/** @var float */
	public $month_consumption;

	/** @var float */
	public $year_consumption;

	public static function fromRow(ActiveRow $row): AverageData {
		$data = new self();
		$data->day_consumption = (float)$row->offsetGet('day_consumption');
		$data->month_consumption = (float)$row->offsetGet('month_consumption');
		$data->year_consumption = (float)$row->offsetGet('year_consumption');

		return $data;
	}
}
