<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$dca = &$GLOBALS['TL_DCA']['tl_page'];

PaletteManipulator::create()
    ->addField('ipProtected', 'protected_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('regular', 'tl_page')
    ->applyToPalette('forward', 'tl_page')
    ->applyToPalette('redirect', 'tl_page')
    ->applyToPalette('root', 'tl_page')
    ->applyToPalette('rootfallback', 'tl_page')
    ->applyToPalette('logout', 'tl_page');

$dca['palettes']['__selector__'][] = 'ipProtected';
$dca['subpalettes']['ipProtected'] = 'allowedIps';

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