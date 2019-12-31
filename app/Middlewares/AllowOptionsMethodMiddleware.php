<?php


namespace App\Middlewares;


use Contributte\Middlewares\IMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AllowOptionsMethodMiddleware implements IMiddleware {

	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response,
		callable $next
	): ResponseInterface {
		if ($request->getMethod() === 'OPTIONS') {
			$response->withHeader('Access-Control-Allow-Origin', '*');
			$response->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, OPTIONS, DELETE');
			$response->withHeader('Access-Control-Allow-Headers', 'origin, content-type, accept');

			return $response;
		}

		return $next($request, $response);
	}
}
