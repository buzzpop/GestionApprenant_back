<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CmRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CmRepository::class)
 *  *@ApiResource(
 *     normalizationContext={"groups"={"get_profil_by_id"}},
 *     collectionOperations={
 *     "get",
 *     },
 *     itemOperations={
 *     "get"
 * }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isArchived"})
 */

class Cm extends User
{

}
