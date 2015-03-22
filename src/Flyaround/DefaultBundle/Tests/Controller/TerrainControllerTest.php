<?php
// src/Flyaround/DefaultBundle/Tests/Controller/TerrainControllerTest.php

namespace Flyaround\DefaultBundle\Tests\Controller;

use Application\Sonata\UserBundle\Tests\WebTestCase;


class TerrainControllerTest extends WebTestCase
{

    /**
     * @param array $user
     *
     * @dataProvider getUsers
     */
    public function testGetTerrains($user)
    {
        $this->loadFixtures(array(
            'Flyaround\DefaultBundle\DataFixtures\ORM\LoadGroupData',
            'Flyaround\DefaultBundle\DataFixtures\ORM\LoadUserData',
            'Flyaround\DefaultBundle\DataFixtures\ORM\LoadTerrainData'
        ));

        $client = $this->createAuthenticatedClient($user);
        $client->request('GET', $this->getUrl('get_terrains'));

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $content);
        $this->assertCount(242, $content);

        $terrain = $content[0];
        $this->assertArrayHasKey('id', $terrain);
        $this->assertArrayHasKey('name', $terrain);
        $this->assertArrayHasKey('latitude', $terrain);
        $this->assertArrayHasKey('longitude', $terrain);
    }

    /**
     * @param array $user
     *
     * @dataProvider getUsers
     */
    public function testGetTerrain($user)
    {
        $client = $this->createAuthenticatedClient($user);
        $client->request('GET', $this->getUrl('get_terrain', array('id' => 1)));

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('latitude', $content);
        $this->assertArrayHasKey('longitude', $content);
    }
}
