<?php
namespace GlpiPlugin\Test;

use CommonDBTM;
use Notepad;
use Log;
use Glpi\Application\View\TemplateRenderer;

class Superasset extends CommonDBTM
{
    // right management, we'll change this later
    static $rightname = 'computer';
    public $dohistory = true;

    /**
     *  Name of the itemtype
     */
    static function getTypeName($nb=0)
    {
        return _n('Super-asset', 'Super-assets', $nb);
    }

    function showForm($ID, $options=[])
    {
        $this->initForm($ID, $options);
        // @myplugin is a shortcut to the **templates** directory of your plugin
        TemplateRenderer::getInstance()->display('@test/superasset.form.html.twig', [
            'item'   => $this,
            'params' => $options,
        ]);

        return true;
    }

    static function getMenuName($nb = 0)
    {
        return self::getTypeName($nb);
    }

    static function getMenuContent()
    {
        // Ajoutez un backslash devant Session si vous n'avez pas de "use Session;" en haut
        $title  = self::getMenuName(\Session::getPluralNumber()); 
        $search = self::getSearchURL();
        $form   = self::getFormURL();

        $menu = [
            'title' => __("Test", 'test'),
            'page'  => $search,
            'icon'  => 'fas fa-server', // L'icône est souvent requise pour l'affichage
            'options' => [
                'superasset' => [
                    'title' => $title,
                    'page'  => $search,
                    'links' => [
                        'search' => $search,
                        'add'    => $form
                    ]
                ]
            ]
        ];

        return $menu;
    }

    function defineTabs($options = [])
    {
        $tabs = [];
        $this->addDefaultFormTab($tabs)
            ->addStandardTab(Superasset_Item::class, $tabs, $options)
            ->addStandardTab(Notepad::class, $tabs, $options)
            ->addStandardTab(Log::class, $tabs, $options);

        return $tabs;
    }
}