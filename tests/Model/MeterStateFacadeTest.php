<?php

namespace Tests\Unit\Model;

use App\Model\ConsumptionRepository;
use App\Model\MeterState;
use App\Model\MeterStateFacade;
use App\Model\MeterStateRepository;
use Codeception\Test\Unit;
use Exception;
use Nette\Utils\DateTime;
use Tests\Unit\UnitTester;

class MeterStateFacadeTest extends Unit {
	/**
	 * @var UnitTester
	 */
	protected $tester;

	/** @var MeterStateFacade */
	protected $meterStateFacade;

	/**
	 * @throws Exception
	 */
	protected function _before() {
		$this->meterStateFacade = new MeterStateFacade($this->createMeterStateRepositoryMock(), $this->createConsumptionRepositoryMock());
	}

	protected function _after() {
	}

	/**
	 * @throws Exception
	 */
	public function testGetDayDiff(): void {
		$meterStatePrevious = $this->createMeterStateMock(1, new DateTime('2019-09-10'));
		$meterStateActual = $this->createMeterStateMock(2, new DateTime('2019-09-15'));
		$this->assertSame(5, $this->meterStateFacade->getDayDiff($meterStatePrevious, $meterStateActual));

		//----
		$meterStatePrevious = $this->createMeterStateMock(1, new DateTime('2019-09-29'));
		$meterStateActual = $this->createMeterStateMock(2, new DateTime('2019-10-10'));
		$this->assertSame(11, $this->meterStateFacade->getDayDiff($meterStatePrevious, $meterStateActual));

		//----
		$meterStatePrevious = $this->createMeterStateMock(1, new DateTime('2019-04-01'));
		$meterStateActual = $this->createMeterStateMock(2, new DateTime('2019-05-05'));
		$this->assertSame(34, $this->meterStateFacade->getDayDiff($meterStatePrevious, $meterStateActual));

		//----
		$meterStatePrevious = $this->createMeterStateMock(1, new DateTime('2019-07-01 22:11:31'));
		$meterStateActual = $this->createMeterStateMock(2, new DateTime('2019-11-21 21:15:00'));
		$this->assertSame(143, $this->meterStateFacade->getDayDiff($meterStatePrevious, $meterStateActual));
	}

	/**
	 * @throws Exception
	 */
	public function testGetAverageDayConsumption(): void {
		$meterStatePrevious = $this->createMeterStateMock(1, new DateTime('2019-09-10'), 'gas', 200.0);
		$meterStateActual = $this->createMeterStateMock(2, new DateTime('2019-09-15'), 'gas', 300.0);
		$this->assertSame(
			20.0,
			$this->meterStateFacade->getAverageDayConsumption($meterStatePrevious, $meterStateActual)
		);

		//----
		$meterStatePrevious = $this->createMeterStateMock(1, new DateTime('2019-09-10'), 'gas', 233.0);
		$meterStateActual = $this->createMeterStateMock(2, new DateTime('2019-09-15'), 'gas', 369.0);
		$this->assertSame(
			27.2,
			$this->meterStateFacade->getAverageDayConsumption($meterStatePrevious, $meterStateActual)
		);

		//----
		$meterStatePrevious = $this->createMeterStateMock(1, new DateTime('2019-09-29'), 'gas', 0.0);
		$meterStateActual = $this->createMeterStateMock(2, new DateTime('2019-10-10'), 'gas', 159.6);
		$this->assertSame(
			14.509090909,
			$this->meterStateFacade->getAverageDayConsumption($meterStatePrevious, $meterStateActual)
		);
	}

	/**
	 * @throws Exception
	 */
	public function testGetConsumptionMonths(): void {
		$meterStatePrevious = $this->createMeterStateMock(1, new DateTime('2019-09-10'));
		$meterStateActual = $this->createMeterStateMock(2, new DateTime('2019-09-15'));
		$expected = [
			'2019-09'
		];
		$this->assertSame(
			$expected,
			$this->meterStateFacade->getConsumptionMonths($meterStatePrevious, $meterStateActual)
		);

		//----
		$meterStatePrevious = $this->createMeterStateMock(1, new DateTime('2019-07-01'));
		$meterStateActual = $this->createMeterStateMock(2, new DateTime('2019-11-15'));
		$expected = [
			'2019-07',
			'2019-08',
			'2019-09',
			'2019-10',
			'2019-11',
		];
		$this->assertSame(
			$expected,
			$this->meterStateFacade->getConsumptionMonths($meterStatePrevious, $meterStateActual)
		);

		//----
		$meterStatePrevious = $this->createMeterStateMock(1, new DateTime('2019-10-30'));
		$meterStateActual = $this->createMeterStateMock(2, new DateTime('2020-02-15'));
		$expected = [
			'2019-10',
			'2019-11',
			'2019-12',
			'2020-01',
			'2020-02',
		];
		$this->assertSame(
			$expected,
			$this->meterStateFacade->getConsumptionMonths($meterStatePrevious, $meterStateActual)
		);
	}

	/**
	 * @return object|MeterStateRepository
	 * @throws Exception
	 */
	protected function createMeterStateRepositoryMock() {
		return $this->make(MeterStateRepository::class);
	}

	/**
	 * @return object|ConsumptionRepository
	 * @throws Exception
	 */
	protected function createConsumptionRepositoryMock() {
		return $this->make(ConsumptionRepository::class);
	}

	/**
	 * @param int      $id
	 * @param string   $commodity
	 * @param DateTime $dateTime
	 * @param float    $state
	 * @return object|MeterState
	 * @throws Exception
	 */
	protected function createMeterStateMock(
		int $id,
		DateTime $dateTime,
		string $commodity = 'gas',
		float $state = 100.0
	) {
		$meterState = $this->make(
			MeterState::class,
			[
				'id'        => $id,
				'commodity' => $commodity,
				'datetime'  => $dateTime,
				'state'     => $state
			]
		);

		return $meterState;
	}
}
