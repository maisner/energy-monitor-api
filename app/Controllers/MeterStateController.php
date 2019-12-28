<?php


namespace App\Controllers;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestBody;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\RequestParameters;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Model\Exception\EntityNotFoundException;
use App\Model\Exception\PersistenceException;
use App\Model\MeterStateFacade;
use App\Model\MeterStateRepository;
use App\Model\Request\Entity\MeterStateCreate;
use Nette\Utils\DateTime;

/**
 * @Path("/meter-state")
 * @Tag(value="Meter State")
 */
class MeterStateController extends BaseV1Controller {

	/** @var MeterStateRepository @inject */
	public $meterStateRepository;

	/** @var MeterStateFacade @inject */
	public $meterStateFacade;

	/**
	 * @Path("/{id}")
	 * @Method("GET")
	 * @RequestParameters({
	 *     @RequestParameter(name="id", type="int", description="Meter state ID")
	 * })
	 * @param ApiRequest  $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 * @throws EntityNotFoundException
	 */
	public function getOne(ApiRequest $request, ApiResponse $response): ApiResponse {
		$id = $request->getParameter('id');

		return $response->withEntity($this->buildData($this->meterStateRepository->getById((int)$id)));
	}

	/**
	 * @Path("/all")
	 * @Method("GET")
	 * @RequestParameters({
	 *     @RequestParameter(name="commodity", type="string", description="Filter by commodity (electric/gas)",
	 *                                         in="query", required=true)
	 * })
	 * @param ApiRequest  $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	public function getAll(ApiRequest $request, ApiResponse $response): ApiResponse {
		$commodity = $request->getParameter('commodity');

		return $response->withEntity(
			$this->buildCollectionData($this->meterStateRepository->findAllByCommodity($commodity))
		);
	}

	/**
	 * @Path("/add")
	 * @Method("POST")
	 * @RequestBody(entity="\App\Model\Request\Entity\MeterStateCreate")
	 * @param ApiRequest  $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 * @throws PersistenceException
	 * @throws \Exception
	 */
	public function insert(ApiRequest $request, ApiResponse $response): ApiResponse {
		/** @var MeterStateCreate $entity */
		$entity = $request->getEntity();

		return $response->withEntity(
			$this->buildData(
				$this->meterStateFacade->addMeterState(
					$entity->commodity,
					new DateTime($entity->datetime),
					$entity->state
				)
			)
		);
	}
}
