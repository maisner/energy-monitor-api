<?php


namespace App\Decorator;


use Apitte\Core\Decorator\IResponseDecorator;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;

class CorsResponseDecorator implements IResponseDecorator {

	public function decorateResponse(ApiRequest $request, ApiResponse $response): ApiResponse {
		return $response->withHeader('Access-Control-Allow-Origin', '*');
	}
}
