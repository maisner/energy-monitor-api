<?php declare(strict_types = 1);

namespace App\Controllers;

use Apitte\Core\Annotation\Controller\Id;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\UI\Controller\IController;
use Apitte\Negotiation\Http\ArrayEntity;
use App\Model\Entity;

/**
 * @Path("/api")
 * @Id("api")
 */
abstract class BaseController implements IController {

	protected function buildData(Entity $entity): ArrayEntity {
		return ArrayEntity::from([(array)$entity]);
	}

	protected function buildCollectionData(array $collection): ArrayEntity {
		return ArrayEntity::from([$collection]);
	}
}
