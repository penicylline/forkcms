<?php

namespace Frontend\Modules\Blog\Tests\Action;

use Common\WebTestCase;

class IndexTest extends WebTestCase
{
    public function testIndexContainsBlogPosts()
    {
        $client = static::createClient();

        $this->loadFixtures(
            $client,
            array(
                'Backend\Modules\Blog\DataFixtures\LoadBlogCategories',
                'Backend\Modules\Blog\DataFixtures\LoadBlogPosts',
            )
        );

        $client->request('GET', '/en/blog');
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        $this->assertContains(
            'Lorem ipsum',
            $client->getResponse()->getContent()
        );
    }

    public function testNonExistingPageGives404()
    {
        $client = static::createClient();

        $client->request('GET', '/en/blog', array('page' => 34));
        $client->followRedirect();
        $this->assertEquals(
            404,
            $client->getResponse()->getStatusCode()
        );
        $this->assertStringEndsWith(
            '404',
            $client->getHistory()->current()->getUri()
        );
    }
}
