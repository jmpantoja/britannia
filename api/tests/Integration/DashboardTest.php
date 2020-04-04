<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Britannia\Tests\Integration;

use Britannia\Tests\DataProviderTrait;
use Britannia\Tests\WebTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class DashboardTest extends WebTestCase
{
    use WebTestTrait;
    use DataProviderTrait;

    /**
     * @dataProvider dataFixtures
     */
    public function test_dashboard_returns_correct_code_according_to_the_role($rol, $code)
    {
        $client = $this->login([$rol]);
        $client->request('GET', '/admin/dashboard');
        $this->assertEquals($code, $client->getResponse()->getStatusCode());
    }

    public function test_dashboard_has_widgets()
    {

        $client = $this->login();
        $crawler = $client->request('GET', '/admin/dashboard');

        $numOfStudents = $crawler->filter('.bg-green > div:nth-child(1) > h3:nth-child(1)')->text();
        $this->assertEquals(655, $numOfStudents);

        $numOfCourses = $crawler->filter('.bg-blue > div:nth-child(1) > h3:nth-child(1)')->text();
        $this->assertEquals(63, $numOfCourses);

        $numOfTeachers = $crawler->filter('.bg-aqua > div:nth-child(1) > h3:nth-child(1)')->text();
        $this->assertEquals(17, $numOfTeachers);

    }

    /**
     * @dataProvider dataFixtures
     */
    public function test_dashboard_has_menu(array $menu)
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/admin/dashboard');
        $nodes = $crawler->filter('.sidebar-menu')
            ->filter('a');

        foreach ($menu as $item) {
            $index = $item['index'];
            $label = $item['label'];
            $url = $item['url'];

            $node = $nodes->eq($index);
            $this->assertEquals($label, $node->text());
            $this->assertEquals($url, $node->attr('href'));
        }
    }
}
