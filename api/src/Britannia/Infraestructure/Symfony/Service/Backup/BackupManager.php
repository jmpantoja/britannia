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

namespace Britannia\Infraestructure\Symfony\Service\Backup;


use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

final class BackupManager
{
    private const LOCAL_SQL_DIRNAME = 'var/backup';
    private const LOCAL_ATTACHMENTS_DIRNAME = 'uploads/attachments';
    private const LOCAL_PHOTOS_DIRNAME = 'uploads/photos';

    private const BACKUP_SQL_DIRNAME = 'database';
    private const BACKUP_ATTACHMENTS_DIRNAME = 'attachments';
    private const BACKUP_PHOTOS_DIRNAME = 'photos';

    private const NUM_OF_SAVED_BACKUPS = 10;

    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
    }

    public function create()
    {
        $this->backupDatabase();
        $this->backupAttachments();
        $this->backupPhotos();
    }

    private function backupDatabase()
    {
        $dumpsDir = $this->directoryPath(self::LOCAL_SQL_DIRNAME);
        $this->dumpDatabase($dumpsDir);
        $this->removeAgeddDumps($dumpsDir);

        $this->upload($dumpsDir, self::BACKUP_SQL_DIRNAME);
    }

    private function backupAttachments()
    {
        $localDirectory = $this->directoryPath(self::LOCAL_ATTACHMENTS_DIRNAME);
        $this->upload($localDirectory, self::BACKUP_ATTACHMENTS_DIRNAME);
    }

    private function backupPhotos()
    {
        $localDirectory = $this->directoryPath(self::LOCAL_PHOTOS_DIRNAME);
        $this->upload($localDirectory, self::BACKUP_PHOTOS_DIRNAME);
    }

    /**
     * @param string $dumpsDir
     * @return string
     */
    private function dumpDatabase(string $dumpsDir)
    {
        $conn = $this->entityManager->getConnection();
        $params = $conn->getParams();
        $dbname = $params['dbname'];

        $backupName = $this->databaseFilename($dbname);

        $commandLine = sprintf('mysqldump --host=%s --user=%s --password=%s %s > %s', ...[
            $params['host'],
            $params['user'],
            $params['password'],
            $params['dbname'],
            sprintf('%s/%s', $dumpsDir, $backupName)
        ]);

        $output = system($commandLine);
        dump("$commandLine:\n $output");
    }

    private function removeAgeddDumps(string $dumpsDir)
    {
        $listOfSavedBackups = glob("$dumpsDir/*.sql");
        $filesystem = new Filesystem();

        collect($listOfSavedBackups)
            ->sort()
            ->reverse()
            ->slice(self::NUM_OF_SAVED_BACKUPS)
            ->each(fn($path) => $filesystem->remove($path));
    }

    /**
     * @param $dbname
     * @return string
     */
    private function databaseFilename($dbname): string
    {
        $today = new CarbonImmutable();

        $backupName = sprintf('%s-%s.sql', ...[
            $dbname,
            $today->format('Y-m-d-U')
        ]);
        return $backupName;
    }

    /**
     * @param string $dirName
     * @return string
     */
    private function directoryPath(string $dirName): string
    {
        $projectDir = $this->parameterBag->get('kernel.project_dir');
        $pathToDirectory = sprintf('%s/%s', $projectDir, $dirName);

        (new Filesystem())->mkdir($pathToDirectory);
        return $pathToDirectory;
    }

    private function upload(string $local, string $target)
    {
        $commandLine = sprintf('rclone copy %s backup:%s', $local, $target);

        $output = system($commandLine);
        dump("$commandLine:\n $output");
    }
}
