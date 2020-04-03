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

namespace Britannia\Tests\Infraestructure\Symfony\Controller;

use Britannia\Domain\Repository\StaffMemberRepositoryInterface;
use Britannia\Tests\DataProviderTrait;
use Britannia\Tests\WebTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class StaffMemberControllerTest extends WebTestCase
{
    use WebTestTrait;
    use DataProviderTrait;


    /**
     * @dataProvider dataFixtures
     */
    public function test_staff_member_list_returns_correct_code_according_to_the_role($rol, $code)
    {
        $client = $this->login([$rol]);
        $client->request('GET', '/admin/britannia/domain/staff-staffmember/list');
        $this->assertEquals($code, $client->getResponse()->getStatusCode());
    }

    public function test_list_contains_all_staff_members()
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/admin/britannia/domain/staff-staffmember/list');

        $this->assertNumOfRowsInDataList($crawler, 17);

    }

    /**
     * @dataProvider dataFixtures
     */
    public function test_it_is_able_to_filter_by_name(string $name, int $expected)
    {
        $client = $this->login();

        $url = "/admin/britannia/domain/staff-staffmember/list?filter[fullName][value]=$name&filter[_page]=1";
        $crawler = $client->request('GET', $url);

        $this->assertNumOfRowsInDataList($crawler, $expected);
    }

    public function test_it_is_able_to_reset_filters()
    {

        $client = $this->login();
        $crawler = $client->request('GET', '/admin/britannia/domain/staff-staffmember/list?filters=reset');

        $this->assertNumOfRowsInDataList($crawler, 17);
    }

    /**
     * @dataProvider dataFixtures
     */
    public function test_submit_form_fails_when_form_data_is_invalid($data, $messages)
    {
        $client = $this->login();
        $client->request('GET', '/admin/britannia/domain/staff-staffmember/create?uniqid=form_id');

        $crawler = $client->submitForm('Crear y editar', [
            'form_id' => $data
        ]);

        $this->assertFormErrorMessages($crawler, $messages);
    }

    /**
     * @dataProvider dataFixtures
     */
    public function test_submit_form_fails_when_username_already_exists($data, $messages)
    {
        $client = $this->login();
        $client->request('GET', '/admin/britannia/domain/staff-staffmember/create?uniqid=form_id');

        $repository = static::$container->get(StaffMemberRepositoryInterface::class);
        $beforeTotal = $repository->count([]);

        $crawler = $client->submitForm('Crear y editar', [
            'form_id' => $data
        ]);

        $this->assertFormErrorMessages($crawler, $messages);
        $this->assertEquals($beforeTotal, $repository->count([]));
    }

    /**
     * @dataProvider dataFixtures
     */
    public function test_it_is_able_to_create_a_new_staff_member($data)
    {
        $client = $this->login();
        $client->request('GET', '/admin/britannia/domain/staff-staffmember/create?uniqid=form_id');
        $repository = static::$container->get(StaffMemberRepositoryInterface::class);

        $beforeTotal = $repository->count([]);

        $client->submitForm('Crear y editar', [
            'form_id' => $data
        ]);

        $this->assertEquals($beforeTotal + 1, $repository->count([]), 'No se ha creado el usuario');
    }

//    public function test_it_is_able_to_edit_a_staff_member()
//    {
//        $client = $this->login();
//       // /admin/britannia/domain/staff-staffmember/30a39a43-ca02-4519-b361-e906f48bc263/edit?_tab=tab_s5e86308c16e02_431061910_1
//        $client->request('GET', '/admin/britannia/domain/staff-staffmember/create?uniqid=form_id');
//        $repository = static::$container->get(StaffMemberRepositoryInterface::class);
//
//        $beforeTotal = $repository->count([]);
//
//        $client->submitForm('Crear y editar', [
//            'form_id' => $data
//        ]);
//
//        $this->assertEquals($beforeTotal + 1, $repository->count([]), 'No se ha creado el usuario');
//    }


}
