<?php

namespace GlpiPlugin\Test;

use CommonDBRelation;
use CommonGLPI;
use Computer;
use Glpi\Application\View\TemplateRenderer;

class Superasset_Item extends CommonDBRelation
{
    // Définition précise des relations pour CommonDBRelation
    // static public $itemtype_primary   = Superasset::class;
    // static public $items_id_primary   = 'plugin_test_superassets_id';
    // static public $itemtype_secondary = 'itemtype';
    // static public $items_id_secondary = 'items_id';

    /**
     * Indique à GLPI de vérifier les droits sur l'objet parent
     */
    static public $checkItem_id_primary = 'plugin_test_superassets_id';

    public static function getTypeName($nb = 0)
    {
        return __('Associated Items', 'test');
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        switch ($item->getType()) {
            case Superasset::class:
                $nb = countElementsInTable(self::getTable(), [
                    'plugin_test_superassets_id' => $item->getID()
                ]);
                return self::createTabEntry(self::getTypeName($nb), $nb);

            case Computer::class:
                $nb = countElementsInTable(self::getTable(), [
                    'itemtype' => Computer::class,
                    'items_id' => $item->getID()
                ]);
                return self::createTabEntry(__('Superassets', 'test'), $nb);
        }
        return '';
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if ($item->getType() == Superasset::class) {
            self::showForSuperasset($item);
        } else if ($item->getType() == Computer::class) {
            self::showForComputer($item);
        }
        return true;
    }

    public static function showForSuperasset(Superasset $superasset)
    {
        // global $CFG_GLPI;
        // global $DB;

        // echo "<form action='" . Superasset::getFormURL() . "' method='post'>";
        // echo "<table class='tab_cadre_fixe'>";
        // echo "<tr class='tab_bg_1'><td>";

        // echo __('Add an item', 'test') . "&nbsp;";

        // \Computer::dropdown(['name' => 'items_id',
        //                     'display_emptychoice' => true,
        //                     'emptylabel' => __('Select a computer')]);

        // echo "<input type='hidden' name='itemtype' value='Computer'>";
        // echo "<input type='hidden' name='plugin_test_superassets_id' value='" . $superasset->getID() . "'>";

        // echo "<input type='submit' name='add_item' value='" . _sx('button', 'Add') . "' class='btn btn-primary'>";

        // echo "</td></tr>";
        // echo "</table>";
        // \Html::closeForm();

        
        $iterator = $DB->request(self::getTable(), [
            'WHERE' => ['plugin_test_superassets_id' => $superasset->getID()]
        ]);

        TemplateRenderer::getInstance()->display('@test/superasset_item.html.twig', [
            'superasset' => $superasset,
            'items'      => iterator_to_array($iterator),
        ]);
    }

    public static function showForComputer(Computer $computer)
    {
        echo "<h3>Superassets liés</h3>";
        // Optionnel : Ajoutez ici une logique de liste simple pour tester
    }
}
