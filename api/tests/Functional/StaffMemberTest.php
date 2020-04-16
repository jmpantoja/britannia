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

namespace Britannia\Tests\Functional;

use Britannia\Domain\Entity\Course\Course;
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
    public function test_every_action_returns_the_correct_http_code_according_the_user_role($url, $users)
    {
        foreach ($users as $rol => $expected) {

            $code = $this->player()
                ->login($rol)
                ->get($url)
                ->response()
                ->getStatusCode();

            $this->assertEquals($expected, $code);
        }
    }

    /**
     * @dataProvider dataFixtures
     */
    public function test_it_is_able_to_list_staff_members(int $totalExpected, array $query)
    {
        $url = $this->buildUrl('/admin/britannia/domain/staff-staffmember/list')
            ->query($query)
            ->build();

        $nodes = $this->player()
            ->login()
            ->get($url)
            ->gridRows();

        $this->assertEquals($totalExpected, $nodes->count());
    }

    /**
     * @dataProvider dataFixtures
     */
    public function test_creating_a_new_staffmember_fails_when_form_data_is_invalid(array $formData, array $messages)
    {

        $url = $this->buildUrl('/admin/britannia/domain/staff-staffmember/create')
            ->uniqId()
            ->build();

        $errors = $this->player()
            ->login()
            ->get($url)
            ->submit('Crear y editar', $formData)
            ->formErrorMessages();


        foreach ($messages as $name => $value) {
            $expected = implode("\n", (array) $value);
            $this->assertEquals($expected, $errors[$name] ?? null);
        }
    }

    /**
     * @dataProvider dataFixtures
     */
    public function test_creating_a_new_staffmember_fails_when_username_already_exists(array $formData)
    {

        $repository = $this->service(StaffMemberRepositoryInterface::class);
        $countBeforeSubmit = $repository->count([]);

        $url = $this->buildUrl('/admin/britannia/domain/staff-staffmember/create')
            ->uniqId()
            ->build();

        $errors = $this->player()
            ->login()
            ->get($url)
            ->submit('Crear y editar', $formData)
            ->formErrorMessages();

        $countAfterSubmit = $repository->count([]);
        $this->assertEquals($countBeforeSubmit, $countAfterSubmit);

        $this->assertEquals('Este valor ya se ha utilizado.', $errors['userName']);

    }

    /**
     * @dataProvider dataFixtures
     */
    public function test_it_is_able_to_create_a_new_staff_member(array $formData)
    {
        $repository = $this->service(StaffMemberRepositoryInterface::class);
        $countBeforeSubmit = $repository->count([]);

        $url = $this->buildUrl('/admin/britannia/domain/staff-staffmember/create')
            ->uniqId()
            ->build();

        $this->player()
            ->login()
            ->get($url)
            ->submit('Crear y editar', $formData);

        $countAfterSubmit = $repository->count([]);
        $this->assertEquals($countBeforeSubmit + 1, $countAfterSubmit, 'No se ha creado el usuario');;
    }

    /**
     * @dataProvider dataFixtures
     */
    public function test_it_is_able_to_edit_a_staff_member(string $id, array $formData, array $expected)
    {
        $repository = $this->service(StaffMemberRepositoryInterface::class);

        $url = $this->buildUrl('/admin/britannia/domain/staff-staffmember/%s/edit', $id)
            ->uniqId()
            ->build();

        $this->player()
            ->login()
            ->get($url)
            ->submit('Actualizar', $formData);

        $user = $repository->find($id);

        $this->assertEquals($expected['fullName'], $user->fullName());
        $this->assertEquals($expected['address'], $user->address());
        $this->assertEqualsCanonicalizing($expected['roles'], $user->getRoles());

        $this->assertContainsOnlyInstancesOf(Course::class, $user->courses());
        $this->assertCount($expected['courses'], $user->courses());
    }


    /**
     * @dataProvider dataFixtures
     */
    public function test_it_is_able_to_delete_a_staff_member(string $id)
    {
        $repository = $this->service(StaffMemberRepositoryInterface::class);
        $url = $this->buildUrl('/admin/britannia/domain/staff-staffmember/%s/delete', $id)
            ->build();


        $user = $repository->findOneById($id);
        $this->assertInstanceOf(StaffMember::class, $user);


        $this->player()
            ->login()
            ->get($url)
            ->submit('Sí, borrar');

        $user = $repository->findOneById($id);
        $this->assertNull($user);
    }


}
