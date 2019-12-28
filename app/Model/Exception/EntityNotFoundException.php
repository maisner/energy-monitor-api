<?php

namespace App\Model\Exception;

use Throwable;

class EntityNotFoundException extends AppException {

	public function __construct(string $entityName, int $entityId, Throwable $previous = NULL) {
		parent::__construct(\sprintf('%s entity with id %s not found', $entityName, $entityId), 404, $previous);
	}

}
