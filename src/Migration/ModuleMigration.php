<?php

namespace HeimrichHannot\IpProtectionBundle\Migration;

use Contao\CoreBundle\Migration\MigrationInterface;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

class ModuleMigration implements MigrationInterface
{
    public function __construct(
        protected Connection $connection,
    ) {}

    public function getName(): string
    {
        return 'Ip Protection Module Migration';
    }

    public function shouldRun(): bool
    {
        return $this->migrateIpProtectionModule();

    }

    public function run(): MigrationResult
    {
        $result = $this->migrateIpProtectionModule(true);
        return new MigrationResult($result, 'Ip Protection Module Migration');
    }

    protected function migrateIpProtectionModule(bool $execute = false): bool
    {

        $result = $this->connection->executeQuery("SELECT id,allowedIps FROM tl_page WHERE allowedIps != ''");
        if ($result->rowCount() < 1) {
            return false;
        }

        foreach ($result->fetchAllAssociative() as $page) {
            if (!$this->isSerializedArray($page['allowedIps'])) {
                if (!$execute) {
                    return true;
                } else {
                    $ips = explode(',', $page['allowedIps']);
                    $this->connection->executeQuery(
                        "UPDATE tl_page SET allowedIps = ? WHERE id = ?",
                        [serialize($ips), $page['id']]
                    );
                }
            }
        }

        return false;
    }

    /**
     * Taken from wordpress core
     */
    protected function isSerializedArray(mixed $data, bool $strict = true): bool
    {
        // If it isn't a string, it isn't serialized.
        if ( ! is_string( $data ) ) {
            return false;
        }
        $data = trim( $data );
        if ( 'N;' === $data ) {
            return true;
        }
        if ( strlen( $data ) < 4 ) {
            return false;
        }
        if ( ':' !== $data[1] ) {
            return false;
        }
        if ( $strict ) {
            $lastc = substr( $data, -1 );
            if ( ';' !== $lastc && '}' !== $lastc ) {
                return false;
            }
        } else {
            $semicolon = strpos( $data, ';' );
            $brace     = strpos( $data, '}' );
            // Either ; or } must exist.
            if ( false === $semicolon && false === $brace ) {
                return false;
            }
            // But neither must be in the first X characters.
            if ( false !== $semicolon && $semicolon < 3 ) {
                return false;
            }
            if ( false !== $brace && $brace < 4 ) {
                return false;
            }
        }
        $token = $data[0];
        switch ( $token ) {
            case 's':
                if ( $strict ) {
                    if ( '"' !== substr( $data, -2, 1 ) ) {
                        return false;
                    }
                } elseif ( ! str_contains( $data, '"' ) ) {
                    return false;
                }
            // Or else fall through.
            case 'a':
            case 'O':
            case 'E':
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';
                return (bool) preg_match( "/^{$token}:[0-9.E+-]+;$end/", $data );
        }
        return false;
    }
}