<?php


namespace App\Model;


use Nette\Database\Table\ActiveRow;

class Data {

	/** @var int */
	public $month;

	/** @var int */
	public $year;

	/** @var float */
	public $day_consumption;

	/** @var float */
	public $month_consumption;

	public static function fromRow(ActiveRow $row): Data {
		$data = new Data();
		$data->month = (int)$row->offsetGet('month');
		$data->year = (int)$row->offsetGet('year');
		$data->day_consumption = (float)$row->offsetGet('day_consumption');
		$data->month_consumption = (float)$row->offsetGet('month_consumption');

		return $data;
	}
}
