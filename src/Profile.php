<?php
namespace GlpiPlugin\Test;

use CommonDBTM;
use CommonGLPI;
use Html;
use Profile as Glpi_Profile;

class Profile extends CommonDBTM
{
    public static $rightname = 'profile';

    static function getTypeName($nb = 0)
    {
        return __("Test", 'test');
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if (
            $item instanceof Glpi_Profile
            && $item->getField('id')
        ) {
            return self::createTabEntry(self::getTypeName());
        }
        return '';
    }

    static function displayTabContentForItem(
        CommonGLPI $item,
        $tabnum = 1,
        $withtemplate = 0
    ) {
        if (
            $item instanceof Glpi_Profile
            && $item->getField('id')
        ) {
            return self::showForProfile($item->getID());
        }

        return true;
    }

    static function getAllRights($all = false)
    {
        $rights = [
            [
                'itemtype' => Superasset::class,
                'label'    => Superasset::getTypeName(),
                'field'    => 'test::superasset'
            ]
        ];

        return $rights;
    }


    static function showForProfile($profiles_id = 0)
    {
        $profile = new Glpi_Profile();
        $profile->getFromDB($profiles_id);

        TemplateRenderer::getInstance()->display('@test/profile.html.twig', [
            'can_edit' => self::canUpdate(),
            'profile'  => $profile,
            'rights'   => self::getAllRights()
        ]);
    }
}