<?php

namespace tbn\ApiGeneratorTestCaseBundle\Tests\Services;

include '/var/www/zdebug/zdebug.php';

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use tbn\ApiGeneratorBundle\Services\AuthorizationService;
use tbn\ApiGeneratorBundle\Services\ApiService;
use tbn\ApiGeneratorTestCaseBundle\Tests\TestCase;


/**
 *
 * @author Thomas BEAUJEAN
 *
 */
class ApiServiceTest extends TestCase
{
    /**
     *
     */
    public function testTcReference()
    {
        $data['id'] = $this->generatedObjects['TcReference1']->getId();
        $entityClass = 'tbn\ApiGeneratorTestCaseBundle\Entity\TcReference';
        $entity = $this->apiService->retrieveEntity($entityClass, $data);

        $this->assertNotNull($entity);
    }

    /**
     *
     */
    public function testTcOneReference()
    {
        $data['id'] = $this->generatedObjects['TcOneReference1']->getId();
        $entityClass = 'tbn\ApiGeneratorTestCaseBundle\Entity\TcOneReference';
        $entity = $this->apiService->retrieveEntity($entityClass, $data);

        $this->assertNotNull($entity);
    }

    /**
     *
     */
    public function testTcManyReference()
    {
        $data['id'] = $this->generatedObjects['TcManyReference1']->getId();
        $entityClass = 'tbn\ApiGeneratorTestCaseBundle\Entity\TcManyReference';
        $entity = $this->apiService->retrieveEntity($entityClass, $data);

        $this->assertNotNull($entity);
    }
}
