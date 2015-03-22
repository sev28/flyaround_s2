<?php
// src/Flyaround/DefaultBundle/Tests/Controller/FlyControllerTest.php

namespace Flyaround\DefaultBundle\Tests\Controller;

use Application\Sonata\UserBundle\Tests\WebTestCase;

class FlyControllerTest extends WebTestCase
{
    /**
     * @param array $user
     *
     * @dataProvider getUsers
     */
    public function testGetFlies($user)
    {
        $this->loadFixtures(array(
            'Flyaround\DefaultBundle\DataFixtures\ORM\LoadGroupData',
            'Flyaround\DefaultBundle\DataFixtures\ORM\LoadUserData',
            'Flyaround\DefaultBundle\DataFixtures\ORM\LoadFlyData'
        ));

        $client = $this->createAuthenticatedClient($user);
        $client->request('GET', $this->getUrl('get_flies'));

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $content);
        $this->assertCount(21, $content);

        $fly = $content[0];
        $this->assertArrayHasKey('id', $fly);
        $this->assertArrayHasKey('name', $fly);
        $this->assertArrayHasKey('latitude', $fly);
        $this->assertArrayHasKey('longitude', $fly);
        $this->assertArrayHasKey('description', $fly);
        $this->assertArrayHasKey('category', $fly);
    }

    /**
     * @param array $user
     *
     * @dataProvider getUsers
     */
    public function testGetFly($user)
    {
        $client = $this->createAuthenticatedClient($user);
        $client->request('GET', $this->getUrl('get_fly', array('id' => 1)));

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('latitude', $content);
        $this->assertArrayHasKey('longitude', $content);
        $this->assertArrayHasKey('description', $content);
        $this->assertArrayHasKey('category', $content);
    }

    /**
     * @param array $user
     *
     * @dataProvider getUsers
     */
    /*public function testPostFly($user)
    {
        $client = $this->createAuthenticatedClient($user);
        $data = array(
            'flyaround_defaultbundle_fly[name]' => 'salut',
            'flyaround_defaultbundle_fly[latitude]' => '5',
            'flyaround_defaultbundle_fly[longitude]' => '3'
        );

        $client->request('POST', $this->getUrl('post_fly'), $data);

        $response = $client->getResponse();

        if ($user === 'admin') {
            $this->assertJsonResponse($response, 201, false);
        } else {
            $this->assertJsonResponse($response, 403);
        }
    }*/
}
