<?php


namespace App\Model;


use Nette\Database\Context;

class DataFacade {

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	/**
	 * @param string $commodity
	 * @return array|Data[]
	 */
	public function getData(string $commodity): array {
		//		$sql = "SELECT `month`, `year`,
		//					AVG(`average_day_consumption`) AS `day_consumption`,
		//					(AVG(`average_day_consumption`) * DAY(LAST_DAY(CONCAT(`year`, '-', `month`, '-01')))) AS `month_consumption`
		//					FROM `consumption`
		//					#WHERE `year` = 2019  AND `month` >= 9
		//					GROUP BY `month`, `year`";
		//
		//		$params = [];
		//		$this->database->query($sql, $params);

		$rows = $this->database->table('consumption')
			->select('month')
			->select('year')
			->select('AVG(`average_day_consumption`) AS `day_consumption`')
			->select(
				"(AVG(`average_day_consumption`) * DAY(LAST_DAY(CONCAT(`year`, '-', `month`, '-01')))) AS `month_consumption`"
			)
			->where('meter_state.commodity', $commodity)
			//			->where('year', 2019)
			//			->where('month >=', 9)
			->group('month, year')
			->fetchAll();

		$data = [];

		foreach ($rows as $row) {
			$data[] = Data::fromRow($row);
		}

		return $data;
	}

}
