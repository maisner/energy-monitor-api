<?php


namespace App\Model;


use App\Model\Exception\EntityNotFoundException;
use App\Model\Exception\PersistenceException;
use Nette\Database\Context;
use Nette\Database\DriverException;
use Nette\SmartObject;
use Nette\Utils\DateTime;
use Tracy\ILogger;

class MeterStateRepository {

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
	 * @return MeterState
	 * @throws EntityNotFoundException
	 */
	public function getById(int $id): MeterState {
		$row = $this->database->table('meter_state')->get($id);

		if ($row === NULL) {
			throw new EntityNotFoundException(MeterState::class, $id);
		}

		return MeterState::fromRow($row);
	}

	/**
	 * @return array|MeterState[]
	 */
	public function findAll(): array {
		$result = [];

		foreach ($this->database->table('meter_state')->fetchAll() as $row) {
			$result[] = MeterState::fromRow($row);
		}

		return $result;
	}

	/**
	 * @param string $commodity
	 * @return array|MeterState[]
	 */
	public function findAllByCommodity(string $commodity): array {
		$result = [];

		foreach ($this->database->table('meter_state')->where('commodity', $commodity)->fetchAll() as $row) {
			$result[] = MeterState::fromRow($row);
		}

		return $result;
	}

	/**
	 * @param string   $commodity
	 * @param DateTime $dateTime
	 * @param float    $state
	 * @return MeterState
	 * @throws PersistenceException
	 */
	public function add(string $commodity, DateTime $dateTime, float $state): MeterState {
		$data = [
			'commodity' => $commodity,
			'datetime'  => $dateTime,
			'state'     => $state
		];

		try {
			$row = $this->database->table('meter_state')->insert($data);

			return MeterState::fromRow($row);
		} catch (DriverException $e) {
			$this->logger->log($e, $this->logger::ERROR);

			throw new PersistenceException();
		}
	}

	/**
	 * @param int $id
	 * @return MeterState
	 * @throws EntityNotFoundException
	 */
	public function getPrevious(int $id): MeterState {
		$meterState = $this->getById($id);

		$by = [
			'commodity' => $meterState->commodity,
			'state <'   => $meterState->state
		];
		$row = $this->database->table('meter_state')
			->where($by)
			->order('state DESC')
			->limit(1)
			->fetch();

		if ($row === NULL) {
			throw new EntityNotFoundException(MeterState::class, $id);
		}

		return MeterState::fromRow($row);
	}
}
