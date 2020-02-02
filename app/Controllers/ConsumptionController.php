<?php


namespace App\Controllers;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\RequestParameters;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Model\DataFacade;
use App\Model\Exception\EntityNotFoundException;

/**
 * @Path("/consumption")
 * @Tag(value="Consumption")
 */
class ConsumptionController extends BaseV1Controller {

	/** @var DataFacade @inject */
	public $dataFacade;


	/**
	 * @Path("/")
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
			$this->buildCollectionData($this->dataFacade->getData($commodity))
		);
	}

	/**
	 * @Path("/average")
	 * @Method("GET")
	 * @RequestParameters({
	 *     @RequestParameter(name="commodity", type="string", description="Filter by commodity (electric/gas)",
	 *                                         in="query", required=true)
	 * })
	 * @param ApiRequest  $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 * @throws EntityNotFoundException
	 */
	public function getAverageData(ApiRequest $request, ApiResponse $response): ApiResponse {
		$commodity = $request->getParameter('commodity');


		return $response->withEntity(
			$this->buildData($this->dataFacade->getAverageData($commodity))
		);
	}
}
