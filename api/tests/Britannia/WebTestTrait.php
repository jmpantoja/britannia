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

namespace Britannia\Tests;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait WebTestTrait
{
    private $client;

    protected function login($roles = ['ROLE_MANAGER']): KernelBrowser
    {
        $client = $this->client();
        $session = self::$kernel->getContainer()->get('session');

        // the firewall context (defaults to the firewall name)
        $firewall = 'main';
        $token = new UsernamePasswordToken('admin', null, $firewall, $roles);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());

        $client->getCookieJar()->set($cookie);

        return $client;
    }

    protected function client(): KernelBrowser
    {
        $client = static::createClient();

        self::bootKernel();

        return $client;
    }


    private function assertFormErrorMessages(Crawler $crawler, array $messages, string $uniqId = 'form_id')
    {
        foreach ($messages as $field => $message) {
            $selector = sprintf('#sonata-ba-field-container-%s_%s', $uniqId, $field);
            $fieldSet = $crawler->filter($selector)
                ->first()
                ->filter('.sonata-ba-field-error-messages');

            $errors = $fieldSet->each(function (Crawler $field) {
                return trim($field->text());
            });

            $this->assertEquals((array)$message, $errors);
        }
    }


    /**
     * @param \Symfony\Component\DomCrawler\Crawler $crawler
     */
    private function assertNumOfRowsInDataList(Crawler $crawler, int $number): void
    {
        $nodes = $crawler->filter('table.table-hover > tbody > tr')
            ->reduce(function ($row) {
                return $row->attr('class') !== 'collapse';
            });

        $this->assertEquals($number, $nodes->count());
    }
}
