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

use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Repository\StaffMemberRepositoryInterface;
use Britannia\Tests\DataProviderTrait;
use Britannia\Tests\WebTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class StaffMemberTest extends WebTestCase
{
    use WebTestTrait;
    use DataProviderTrait;


    /**
     * @dataProvider dataFixtures
     */
    public function test_crud_returns_correct_code_according_to_the_role_and_action($url, $users)
    {
        foreach ($users as $rol => $code) {
            $client = $this->login([$rol]);
            $client->request('GET', $url);
            $this->assertEquals($code, $client->getResponse()->getStatusCode());
        }

        ob_clean();
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

    /**
     * @dataProvider dataFixtures
     */
    public function test_it_is_able_to_edit_a_staff_member(string $id, array $data, array $entity)
    {

        $client = $this->login();
        $repository = static::$container->get(StaffMemberRepositoryInterface::class);

        $url = sprintf('/admin/britannia/domain/staff-staffmember/%s/edit?uniqid=form_id', $id);
        $client->request('GET', $url);

        $client->submitForm('Actualizar', [
            'form_id' => $data
        ]);

        /** @var StaffMember $user */
        $user = $repository->find($id);
        foreach ($entity as $method => $value) {
            $this->assertEqualsCanonicalizing($value, $user->$method());
        }
    }


    /**
     * @dataProvider dataFixtures
     */
    public function test_it_is_able_to_delete_a_staff_member(string $id)
    {
        $client = $this->login();
        $repository = static::$container->get(StaffMemberRepositoryInterface::class);

        $url = sprintf('/admin/britannia/domain/staff-staffmember/%s/delete?', $id);
        $client->request('GET', $url);

        $foundUser = $repository->findOneBy([
            'id' => $id
        ]);

        $this->assertInstanceOf(StaffMember::class, $foundUser);
        $client->submitForm('Sí, borrar');

        $foundUser = $repository->findOneBy([
            'id' => $id
        ]);
        $this->assertNull($foundUser);
    }
}
