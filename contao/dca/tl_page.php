<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$dca = &$GLOBALS['TL_DCA']['tl_page'];

PaletteManipulator::create()
    ->addField('ipProtected', 'groups')
    ->applyToSubpalette('protected', 'tl_page');

/**
 * Fields
 */
$dca['fields']['ipProtected'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true, 'tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default ''"
];
$dca['fields']['allowedIps'] = [
    'exclude' => true,
    'inputType' => 'listWizard',
    'eval' => [
        'maxlength' => 255,
        'mandatory' => true,
        'allowHtml' => false,
        'tl_class' => 'w50 clr'
    ],
    'sql' => [
        'type' => 'blob',
        'notnull' => false
    ],
];