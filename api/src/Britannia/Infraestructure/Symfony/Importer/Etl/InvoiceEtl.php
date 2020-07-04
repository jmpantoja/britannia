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


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Service\Course\TimeTableUpdater;
use Britannia\Infraestructure\Symfony\Importer\Builder\BuilderInterface;
use Britannia\Infraestructure\Symfony\Importer\Builder\InvoiceBuilder;
use Britannia\Infraestructure\Symfony\Importer\Console;
use Britannia\Infraestructure\Symfony\Importer\Converter\FullNameConverter;
use Britannia\Infraestructure\Symfony\Importer\DataCollector;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\ChildNormalizer;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\NormalizerInterface;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\StudentNormalizer;
use Carbon\CarbonImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class InvoiceEtl extends AbstractEtl
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Connection $original, EntityManagerInterface $entityManager, DataPersisterInterface $dataPersister, Security $security)
    {
        parent::__construct($original, $entityManager, $dataPersister);
        $this->security = $security;
    }


    public function clean(): void
    {
        $this->truncate('invoices', 'invoice_details');
    }

    public function configureDataLoader(QueryBuilder $builder): void
    {
        // TODO: Implement configureDataLoader() method.
    }

    protected function extract(QueryBuilder $builder): array
    {
        $sql = <<<eof
(SELECT id, idAlumno as student, '' as subject, fechaCreacion as created_at, fechaVencimiento as expired_at, null as paid_at, formaPago as type, total, estado as status,
    null as concepto1, null as unidades1, null as descuento1, null as importe1, null as total1,
    null as concepto2, null as unidades2, null as descuento2, null as importe2, null as total2,
    null as concepto3, null as unidades3, null as descuento3, null as importe3, null as total3,
    null as concepto4, null as unidades4, null as descuento4, null as importe4, null as total4
    FROM academia_mysql.Pagos
    )
 UNION
  (
  SELECT id, idAlumno as student, title as subject, fecha as created_at, null as expired_at, payedAt as paid_at, 'efectivo' as type, total, pagado as status,
  concepto1, unidades1, descuento1, importe1, total1,
  concepto2, unidades2, descuento2, importe2, total2,
  concepto3, unidades3, descuento3, importe3, total3,
  concepto4, unidades4, descuento4, importe4, total4
  FROM academia_mysql.pagosEfectivo
  )
eof;
        $query = $builder->getConnection()->executeQuery($sql);
        return $query->fetchAll();
    }

    public function createBuilder(array $input, EntityManagerInterface $entityManager): BuilderInterface
    {
        $builder = InvoiceBuilder::make($input, $entityManager);
        $subject = $this->calculeSubject($input);

        $builder
            ->withStudent((int)$input['student'])
            ->withSubject($subject)
            ->withCreatedAt($input['created_at'], $input['paid_at'])
            ->withExpiredAt($input['expired_at'])
            ->withType($input['type'])
            ->withTotal($input['total'])
            ->withStatus($input['status'])
            ->withDetail($input["concepto1"], $input["unidades1"], $input["descuento1"], $input["importe1"], $input["total1"])
            ->withDetail($input["concepto2"], $input["unidades2"], $input["descuento2"], $input["importe2"], $input["total2"])
            ->withDetail($input["concepto3"], $input["unidades3"], $input["descuento3"], $input["importe3"], $input["total3"])
            ->withDetail($input["concepto4"], $input["unidades4"], $input["descuento4"], $input["importe4"], $input["total4"]);

        return $builder;
    }

    /**
     * @param array $input
     * @return string
     */
    private function calculeSubject(array $input): string
    {
        $candidates = [
            $input['subject'],
            $input['concepto1'],
            $input['concepto2'],
            $input['concepto3'],
            $input['concepto4'],
        ];

        if (!is_null($input['expired_at'])) {
            $date = CarbonImmutable::make($input['expired_at']);
            $candidates[] = sprintf('Mensualidad %s', date_to_string($date, -1, -1, "MMMM 'de' YYYY"));
        }

        $subject = (string)collect($candidates)
            ->filter()
            ->first();

        return $subject;
    }

}
