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
class TcManyReference
{
    use Traits\IdTrait;
    use Traits\NameTrait;

    /**
     * @ORM\OneToMany(targetEntity="TcOneReference", mappedBy="tcManyReference")
     **/
    protected $tcOneReferences;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tcOneReferences = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tcOneReferences
     *
     * @param \tbn\ApiGeneratorTestCaseBundle\Entity\TcOneReference $tcOneReferences
     * @return TcManyReference
     */
    public function addTcOneReference(\tbn\ApiGeneratorTestCaseBundle\Entity\TcOneReference $tcOneReferences)
    {
        $this->tcOneReferences[] = $tcOneReferences;

        return $this;
    }

    /**
     * Remove tcOneReferences
     *
     * @param \tbn\ApiGeneratorTestCaseBundle\Entity\TcOneReference $tcOneReferences
     */
    public function removeTcOneReference(\tbn\ApiGeneratorTestCaseBundle\Entity\TcOneReference $tcOneReferences)
    {
        $this->tcOneReferences->removeElement($tcOneReferences);
    }

    /**
     * Get tcOneReferences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTcOneReferences()
    {
        return $this->tcOneReferences;
    }
}
