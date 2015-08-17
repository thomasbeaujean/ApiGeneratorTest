<?php

namespace tbn\ApiGeneratorTestCaseBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use Application\FaxServerBundle\DataFixtures\ORM\NetworkConfigurationData;
use tbn\ApiGeneratorBundle\Services\AuthorizationService;
use tbn\ApiGeneratorBundle\Services\ApiService;

/**
 *
 * @author Thomas BEAUJEAN
 *
 */
class TestCase extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();

        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $allRights = array(
            'create' => true,
            'update' => true,
            'delete' => true,
            'get_one' => true,
            'get_one_deep' => true,
            'get_all' => true,
            'get_all_deep' => true
        );

        $specifiedEntities = array();
        $entityRights = array();

        $authorizationService = new AuthorizationService($allRights, $entityRights, $specifiedEntities);
        $doctrine = static::$kernel->getContainer()->get('doctrine');
        $validator = static::$kernel->getContainer()->get('validator');

        $this->apiService = new ApiService($authorizationService, $doctrine, $validator);

        $this->rights = array(
            'create',
            'update',
            'delete',
            'get_one',
            'get_one_deep',
            'get_all',
            'get_all_deep');

        $em = $doctrine->getManager();
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($em);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($em, $purger);
        $executor->execute(array());
        $this->generatedObjects = \Nelmio\Alice\Fixtures::load(__DIR__.'/fixtures.yml', $em);
    }

    /**
     *
     */
    public function getEm()
    {
        return $this->getService( 'doctrine.orm.entity_manager' );
    }

    /**
     * Get the router service
     *
     * @return Router
     */
    public function getRouter()
    {
        return $this->getService('router');
    }

    public function getNetworkConfigurationRepository()
    {
        return $this->getEm()->getRepository( 'Application\FaxServerBundle\Entity\NetworkConfiguration' );
    }

    public function loadFixtures()
    {
        $loader = new Loader();
        $loader->addFixture( new NetworkConfigurationData() );

        $this->loadFixtures( $loader );
    }

//     public function loadFixtures( $loader )
//     {
//         $purger     = new ORMPurger();
//         $executor   = new ORMExecutor( $this->getEm(), $purger );
//         $executor->execute( $loader->getFixtures() );
//     }

    protected function getService( $name, $kernel = null )
    {
        return static::$kernel->getContainer()->get( $name );
    }

    protected function hasService( $name, $kernel = null )
    {

        return $this->getBootedKernel()->getContainer()->has( $name );
    }

    protected function getBootedKernel()
    {
        $this->kernel = $this->createKernel();

        if ( !$this->kernel->isBooted() )
        {
            $this->kernel->boot();
        }

        return $this->kernel;
    }

    public function generateUrl( $client, $route, $parameters = array() )
    {
        return $client->getContainer()->get( 'router' )->generate( $route, $parameters );
    }
}
