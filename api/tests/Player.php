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
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class Player
{

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;
    /**
     * @var Crawler
     */
    private Crawler $crawler;
    /**
     * @var Container
     */
    private Container $container;

    public static function make(KernelBrowser $client, Container $container): self
    {
        return new self($client, $container);
    }

    private function __construct(KernelBrowser $client, Container $container)
    {
        $this->client = $client;
        $this->container = $container->has('test.service_container')
            ? $container->get('test.service_container')
            : $container;

    }

    public function login(string $role = 'ROLE_MANAGER', $firewall = 'main'): self
    {
        $session = $this->container->get('session');

        $token = new UsernamePasswordToken('admin', null, $firewall, [$role]);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());

        $this->client->getCookieJar()->set($cookie);

        return $this;
    }

    public function get(string $url): self
    {
        $this->crawler = $this->client->request('GET', $url);
        ob_clean();
        return $this;
    }

    public function submit(string $buttonName, array $values = [], string $uniqId = UrlBuilder::UNIQID): self
    {
        $form = $this->crawler()->selectButton($buttonName)->form();

        foreach ($values as $name => $value) {
            $key = sprintf('%s[%s]', $uniqId, $name);
            if (!is_array($form[$key])) {
                $form[$key]->setValue($value);
                unset($values[$name]);
            }
        }

        if(empty($values)){
            $this->crawler = $this->client->submit($form);
            return $this;
        }

        $form->setValues([$uniqId => $values]);

        $this->crawler = $this->client->submit($form);
        return $this;
    }


    /**
     * @return Crawler
     */
    public function crawler(): Crawler
    {
        return $this->crawler;
    }

    /**
     * @return Response
     */
    public function response(): Response
    {
        return $this->client->getResponse();
    }

    public function gridRows(): Crawler
    {
        return $this->crawler()->filter('table.sonata-ba-list > tbody > tr[class!=collapse]');
    }


    public function formErrorMessages($uniqId = UrlBuilder::UNIQID): array
    {
        $selector = sprintf('sonata-ba-field-container-%s_', $uniqId);
        $fields = $this->crawler()->filter("[id^=$selector]");

        $messages = $fields->each(function ($field) use ($selector) {
            $name = str_replace($selector, '', $field->attr('id'));
            $errors = $field
                ->filter('.sonata-ba-field-error-messages')
                ->each(function (Crawler $field) {
                    return trim($field->text());
                });

            $errors = implode("\n", $errors);
            return [$name => $errors];
        });

        return array_filter(array_merge(...$messages));
    }
}
