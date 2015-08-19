<?php

namespace tbn\ApiGeneratorBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use tbn\ApiGeneratorBundle\Services\AuthorizationService;
use tbn\ApiGeneratorBundle\Services\ApiService;
use tbn\ApiGeneratorTestCaseBundle\Tests\TestCase;

/**
 *
 * @author Thomas BEAUJEAN
 *
 */
class ApiControllerTest extends TestCase
{
    /**
     *
     */
    public function __construct()
    {
        $this->rights = array(
            'create',
            'update',
            'delete',
            'get_one',
            'get_one_deep',
            'get_all',
            'get_all_deep');
    }

    /**
     * Get a client
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getClient()
    {
        $client = static::createClient(array(), array(
            'HTTP_HOST' => 'api-generator-test.dev'
        ));

        return $client;
    }

    /**
     *
     * @param string $itemNamespace
     * @param int $expectedNumber
     */
    protected function assertGetAll($itemNamespace, $expectedNumber)
    {
        $client = $this->getClient();
        $router = $this->getRouter();

        //get all entries
        $url = $router->generate('api_generator_all', array('itemNamespace' => $itemNamespace));

        $client->request('GET', $url);

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);
        $dataNumber = count($data['data']);

        $this->assertEquals($expectedNumber, $dataNumber);

        //get all entries deep normalized
        $url = $router->generate('api_generator_all_deep', array('itemNamespace' => $itemNamespace));

        $client->request('GET', $url);

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);
        $dataNumber = count($data['data']);

        $this->assertEquals($expectedNumber, $dataNumber);
    }

    /**
     *
     */
    public function testGetAll()
    {
        $entities = array('-tbn--api-generator-test-case-bundle--entity--tc-many-reference' => 1,
            '-tbn--api-generator-test-case-bundle--entity--tc-reference' => 20,
            '-tbn--api-generator-test-case-bundle--entity--tc-one-reference' => 10);

        foreach($entities as $entity => $expectedCount) {
            $this->assertGetAll($entity, $expectedCount);
        }
    }

    /**
     *
     */
    public function testCreateTcReference()
    {
        $client = $this->getClient();
        $router = $this->getRouter();

        //get all entries
        $itemNamespace = '-tbn--api-generator-test-case-bundle--entity--tc-reference';
        $url = $router->generate('api_generator_save', array('itemNamespace' => $itemNamespace));

        $parameters = array(
            array(
                'name' => 'Some name'
            )
        );

        $client->request('POST', $url, $parameters);

        $response = $client->getResponse();
        $content = $client->getResponse()->getContent();
        $this->assertEquals(200, $response->getStatusCode(), $content);

        $data = json_decode($content, true);
        $dataNumber = count($data['data']);

        //check that the new entry is sent back
        $this->assertEquals(1, $dataNumber);

        //check that there is 20+1 items, it did create a new entry
        $this->assertGetAll($itemNamespace, 21);

        $id = $data['data'][0]['id'];

        $parameters = array(
            array(
                'id' => $id,
                'name' => 'nameUpdated'
            )
        );

        $client->request('POST', $url, $parameters);

        $response = $client->getResponse();
        $content = $client->getResponse()->getContent();

        $this->assertEquals(200, $response->getStatusCode(), $content);

        $data = json_decode($content, true);
        $dataNumber = count($data['data']);

        //check that there is 20+1 items, update did not create a new entry
        $this->assertGetAll($itemNamespace, 21);

        //test delete
        $url = $router->generate('api_generator_delete', array('itemNamespace' => $itemNamespace, 'id' => $id));
        $client->request('DELETE', $url, $parameters);
        $this->assertEquals(200, $response->getStatusCode(), $content);

        $this->assertGetAll($itemNamespace, 20);
    }
}
