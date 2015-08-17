<?php
namespace tbn\ApiGeneratorTestCaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use tbn\ApiGeneratorTestCaseBundle\Entity\Traits;

/**
 *
 * @author Thomas BEAUJEAN
 *
 * @ORM\Entity()
 *
 */
class TcReference
{
    use Traits\IdTrait;
    use Traits\NameTrait;
}
