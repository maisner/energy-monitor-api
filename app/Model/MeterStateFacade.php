<?php


namespace App\Model;


use App\Model\Exception\EntityNotFoundException;
use Nette\Utils\DateTime;

class MeterStateFacade {

	/** @var MeterStateRepository */
	private $meterStateRepository;

	/** @var ConsumptionRepository */
	private $consumptionRepository;

	public function __construct(
		MeterStateRepository $meterStateRepository,
		ConsumptionRepository $consumptionRepository
	) {
		$this->meterStateRepository = $meterStateRepository;
		$this->consumptionRepository = $consumptionRepository;
	}

	/**
	 * @param string   $commodity
	 * @param DateTime $dateTime
	 * @param float    $state
	 * @return MeterState
	 * @throws Exception\PersistenceException
	 */
	public function addMeterState(string $commodity, DateTime $dateTime, float $state): MeterState {
		$actualMeterState = $this->meterStateRepository->add($commodity, $dateTime, $state);

		try {
			$previousMeterState = $this->meterStateRepository->getPrevious($actualMeterState->id);
		} catch (EntityNotFoundException $e) {
			return $actualMeterState;
		}

		$averageDayConsumption = $this->getAverageDayConsumption($previousMeterState, $actualMeterState);
		$consumptionMonths = $this->getConsumptionMonths($previousMeterState, $actualMeterState);

		foreach ($consumptionMonths as $consumptionMonth) {
			[
				$year,
				$month
			] = explode('-', $consumptionMonth);

			$this->consumptionRepository->add($actualMeterState->id, (int)$month, (int)$year, $averageDayConsumption);
		}

		return $actualMeterState;
	}

	public function getDayDiff(MeterState $previous, MeterState $actual): int {
		$from = clone $previous->datetime;
		$to = clone $actual->datetime;

		$from->setTime(0, 0);
		$to->setTime(0, 0);

		return round(($to->getTimestamp() - $from->getTimestamp()) / 86400);
	}

	public function getAverageDayConsumption(MeterState $previous, MeterState $actual): float {
		$dayDiff = $this->getDayDiff($previous, $actual);
		$stateDiff = $actual->state - $previous->state;

		return (float)($stateDiff / $dayDiff);
	}

	/**
	 * @param MeterState $previous
	 * @param MeterState $actual
	 * @return array|string[]
	 */
	public function getConsumptionMonths(MeterState $previous, MeterState $actual): array {
		$start = clone $previous->datetime;
		$end = clone $actual->datetime;
		$start->modify('first day of this month');
		$end->modify('first day of next month');

		$period = new \DatePeriod(
			$start, \DateInterval::createFromDateString('1 month'), $end
		);

		$res = [];

		/** @var \DateTime $date */
		foreach ($period as $date) {
			$res[] = $date->format('Y-m');
		}

		return $res;
	}

}
