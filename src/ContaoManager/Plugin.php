<?php

namespace HeimrichHannot\IpProtectionBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use HeimrichHannot\IpProtectionBundle\HeimrichHannotIpProtectionBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(HeimrichHannotIpProtectionBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}