<?php


namespace App\Controllers;


use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\OpenApi\ISchemaBuilder;

/**
 * Class OpenApiController
 * @package App\Controllers
 * @Path("/openapi")
 * @Tag(value="OpenAPI")
 */
class OpenApiController extends BaseV1Controller {

	/** @var ISchemaBuilder @inject */
	public $schemaBuilder;

	/**
	 * @Path("/")
	 * @Method("GET")
	 * @param ApiRequest  $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	public function index(ApiRequest $request, ApiResponse $response): ApiResponse {
		$openApi = $this->schemaBuilder->build();
		$response->withHeader('Access-Control-Allow-Origin', '*');

		return $response->writeJsonBody($openApi->toArray());
	}

}
