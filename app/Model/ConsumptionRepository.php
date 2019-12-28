<?php


namespace App\Model;


use App\Model\Exception\EntityNotFoundException;
use App\Model\Exception\PersistenceException;
use Nette\Database\Context;
use Nette\Database\DriverException;
use Nette\SmartObject;
use Tracy\ILogger;

class ConsumptionRepository {

	use SmartObject;

	/** @var Context */
	private $database;

	/** @var ILogger */
	private $logger;

	public function __construct(Context $database, ILogger $logger) {
		$this->database = $database;
		$this->logger = $logger;
	}

	/**
	 * @param int $id
	 * @return Consumption
	 * @throws EntityNotFoundException
	 */
	public function getById(int $id): Consumption {
		$row = $this->database->table('consumption')->get($id);

		if ($row === NULL) {
			throw new EntityNotFoundException(Consumption::class, $id);
		}

		return Consumption::fromRow($row);
	}

	/**
	 * @return array|Consumption[]
	 */
	public function findAll(): array {
		$result = [];

		foreach ($this->database->table('consumption')->fetchAll() as $row) {
			$result[] = Consumption::fromRow($row);
		}

		return $result;
	}

	/**
	 * @param int   $meterStateId
	 * @param int   $month
	 * @param int   $year
	 * @param float $averageDayConsumption
	 * @return Consumption
	 * @throws PersistenceException
	 */
	public function add(int $meterStateId, int $month, int $year, float $averageDayConsumption): Consumption {
		$data = [
			'meter_state_id'          => $meterStateId,
			'month'                   => $month,
			'year'                    => $year,
			'average_day_consumption' => $averageDayConsumption
		];

		try {
			$row = $this->database->table('consumption')->insert($data);

			return Consumption::fromRow($row);
		} catch (DriverException $e) {
			$this->logger->log($e, $this->logger::ERROR);

			throw new PersistenceException();
		}
	}
}
