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

namespace Britannia\Infraestructure\Symfony\Importer\Etl;


use Britannia\Infraestructure\Symfony\Importer\Builder\BuilderInterface;
use Britannia\Infraestructure\Symfony\Importer\Builder\StudentBuilder;
use Britannia\Infraestructure\Symfony\Importer\Console;
use Britannia\Infraestructure\Symfony\Importer\Converter\FullNameConverter;
use Britannia\Infraestructure\Symfony\Importer\DataCollector;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\ChildNormalizer;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\NormalizerInterface;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\StudentNormalizer;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

class StudentEtl extends AbstractEtl
{

    public function clean(): void
    {
        $this->truncate('students', 'students_child', 'students_adult', 'tutors', 'student_records');
    }

    public function configureDataLoader(QueryBuilder $builder): void
    {

        $offset = 0;
        $limit = null;
        $id = null;

        $builder->select('*')
            ->from('alumnos')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if (is_int($id)) {
            $builder
                ->andWhere('id > ?')
                ->setParameter(0, $id);
        }
    }

    public function createBuilder(array $input, EntityManagerInterface $entityManager): BuilderInterface
    {
        $builder = StudentBuilder::make($input, $entityManager);

        $builder
            ->withId($input['id'])
            ->withType($input['tipo'])
            ->withFullName([
                'firstName' => $input['nombre'] ?? 'NOMBRE DESCONOCIDO',
                'lastName' => $input['apellidos'] ?? 'APELLIDOS DESCONOCIDOS'
            ])
            ->withBirthDate((string)$input['fecha'])
            ->withDNI((string)$input['dni'])
            ->withEmail((string)$input['correo'], (string)$input['telefono'])
            ->withAddress((string)$input['domicilio'], (string)$input['codigoPostal'])
            ->withPhoneNumbers((string)$input['telefono'], (string)$input['telefono2'], (string)$input['correo'])
            ->withPreferredPartOfDay((string)$input['preferencia'])
            ->withPreferredContactMode((string)$input['tipoContacto'])
            ->withOtherAcademy((string)$input['nombreAcademia'], (string)$input['tiempoAcademia'])
            ->withFirstContact((string)$input['conociste'])
            ->withComments((string)$input['observaciones'], (string)$input['observaciones2'])
            ->withTerms((string)$input['checkAceptoCondicionesAcademia'], (string)$input['checkAceptoCondicionesAlumno'], (string)$input['permisosImagen'])
            ->withJob((string)$input['profesion'], (string)$input['situacion'])
            ->withSchool((string)$input['colegio'], (string)$input['proxCurso'])
            ->withFirstTutor([
                'texto' => $input['textoTutor1'],
                'firstName' => $input['nombreTutor'],
                'lastName' => sprintf('%s %s', $input['apellidoTutor'], $input['apellido2Tutor']),
                'dni' => $input['dniTutor'],
                'phone' => $input['telefonoTutor'],
                'phone2' => $input['telefono2Tutor'],
                'extra' => $input['adicionalTutor'],
                'email' => $input['correoTutor'],
                'jobStatus' => $input['situacionTutor'],
                'jobName' => $input['profesionTutor'],
                'address' => $input['domicilioTutor'],
                'postalCode' => $input['codigoTutor'],
            ])
            ->withSecondTutor([
                'texto' => $input['textoTutor2'],
                'firstName' => $input['nombreTutor2'],
                'lastName' => sprintf('%s %s', $input['apellidoTutor2'], $input['apellido2Tutor2']),
                'dni' => $input['dniTutor2'],
                'phone' => $input['telefonoTutor2'],
                'phone2' => $input['telefono2Tutor2'],
                'extra' => $input['adicionalTutor2'],
                'email' => $input['correoTutor2'],
                'jobStatus' => $input['situacionTutor2'],
                'jobName' => $input['profesionTutor2'],
                'address' => $input['domicilioTutor2'],
                'postalCode' => $input['codigoTutor2'],
            ])
            ->withPayment([
                'mode' => $input['pago'],
                'titular' => $input['titular'],
                'city' => $input['ciudad'],
                'province' => $input['provincia'],
                'iban' => $input['numeroCuenta'],
                'number' => $input['numeroDomiciliado'],
            ])
            ->withCreateAt($input['createdAt']);

        return $builder;
    }

}
