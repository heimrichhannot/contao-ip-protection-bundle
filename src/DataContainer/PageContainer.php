<?php

namespace HeimrichHannot\IpProtectionBundle\DataContainer;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Contao\Input;
use Contao\PageModel;

class PageContainer
{
    /**
     * @Callback(table="tl_page", target="config.onload")
     */
    public function onConfigLoadCallback(DataContainer $dc): void
    {
        if (!$dc || 'edit' !== Input::get('act')) {
            return;
        }

        $pageModel = PageModel::findByPk($dc->id);
        if (!$pageModel) {
            return;
        }

        if ($pageModel->protected && $pageModel->ipProtected) {
            PaletteManipulator::create()
                ->addField('allowedIps', 'ipProtected')
                ->applyToSubpalette('protected', 'tl_page');
        }
    }
}