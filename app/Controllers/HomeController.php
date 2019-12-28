<?php declare(strict_types = 1);

namespace App\Controllers;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Negotiation\Http\ArrayEntity;

/**
 * @Path("/")
 * @Tag(value="Home API")
 */
class HomeController extends BaseV1Controller {

	/**
	 * @Path("/")
	 * @Method("GET")
	 */
	public function index(ApiRequest $request, ApiResponse $response): ApiResponse {
		$data = [
			'api_version' => '0.1',
			'api_name'    => 'Energy Monitor PHP API'
		];

		return $response->withEntity(ArrayEntity::from($data));
	}
}
