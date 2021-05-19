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
use Britannia\Domain\Repository\StudentRepositoryInterface;
use Britannia\Tests\DataProviderTrait;
use Britannia\Tests\Mock\UserSingleton;
use Britannia\Tests\WebTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class StudentTest extends WebTestCase
{
    use WebTestTrait;
    use DataProviderTrait;

    /**
     * @dataProvider dataFixtures
     */
    public function test_crud_returns_correct_code_according_to_the_role_and_action($url, $users)
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
    public function test_it_is_able_to_list_students(int $totalExpected, array $query)
    {
        $url = $this->buildUrl('/admin/britannia/domain/student-student/list')
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
    public function test_create_student_fails_when_form_data_is_invalid(string $type, array $formData, array $messages)
    {
        $url = $this->buildUrl('/admin/britannia/domain/student-student/create')
            ->uniqId()
            ->query([
                'subclass' => $type
            ])
            ->build();

        $errors = $this->player()
            ->login()
            ->get($url)
            ->submit('Crear y editar', $formData)
            ->formErrorMessages();

        foreach ($messages as $name => $value) {
            $expected = implode("\n", (array)$value);
            $this->assertEquals($expected, $errors[$name] ?? null);
        }
    }


    /**
     * @dataProvider dataFixtures
     * @param string $type
     * @param array $formData
     */
    public function test_it_is_able_to_create_a_new_student(string $type, array $formData)
    {
        $repository = $this->service(StudentRepositoryInterface::class);
        $countBeforeSubmit = $repository->count([]);

        $url = $this->buildUrl('/admin/britannia/domain/student-student/create')
            ->uniqId()
            ->query([
                'subclass' => $type
            ])
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
    public function test_it_is_able_to_edit_a_student(string $id, array $formData, array $expected)
    {
        $repository = $this->service(StudentRepositoryInterface::class);

        $url = $this->buildUrl('/admin/britannia/domain/student-student/%s/edit', $id)
            ->uniqId()
            ->build();


        $this->player()
            ->login()
            ->get($url)
            ->submit('Actualizar', $formData);

        /** @var Student $user */
        $user = $repository->find($id);

        $this->assertEquals($expected['fullName'], $user->fullName());
        $this->assertEquals($expected['address'], $user->address());
        $this->assertContainsOnlyInstancesOf(Course::class, $user->activeCourses());
        $this->assertCount($expected['courses'], $user->activeCourses());
    }
}
