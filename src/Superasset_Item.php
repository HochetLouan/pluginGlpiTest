<?php

namespace GlpiPlugin\Test;

use CommonDBRelation;
use CommonGLPI;
use Computer;
use Glpi\Application\View\TemplateRenderer;
use GlpiPlugin\Test\Superasset;

class Superasset_Item extends CommonDBRelation
{

    static public $itemtype_primary = 'plugin_test_superassets_id';
    static public $itemtype_secondary = 'itemtype';
    static public $items_id_secondary = 'items_id';
    /**
     * Tabs title
     */
    function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        switch ($item->getType()) {
            case Superasset::class:
                $nb = countElementsInTable(self::getTable(), [
                    'plugin_test_superassets_id' => $item->getID()
                ]);
                return self::createTabEntry(__('Associated Items', 'test'), $nb);

            case Computer::class:
                $nb = countElementsInTable(self::getTable(), [
                    'itemtype' => Computer::class,
                    'items_id' => $item->getID()
                ]);
                return self::createTabEntry(__('Superassets', 'test'), $nb);
        }
        return '';
    }

    
    static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if ($item->getType() == Superasset::class) {
            self::showForSuperasset($item);
        } else if ($item->getType() == Computer::class) {
            self::showForComputer($item);
        }
        return true;
    }

    /**
     * Specific function for display only items of Superasset
     */
    static function showForSuperasset(Superasset $superasset, $withtemplate = 0)
    {
        TemplateRenderer::getInstance()->display('@test/superasset_item.html.twig', [
            'superasset' => $superasset,
            'items'      => getAllDataFromTable(self::getTable(), ['plugin_test_superassets_id' => $superasset->getID()])
        ]);
    }

    static function showForComputer(Computer $computer) {
        echo "Contenu de l'onglet sur le PC"; 
    }
}