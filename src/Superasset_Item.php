<?php

namespace GlpiPlugin\Test;

use CommonDBTM;
use CommonGLPI;
use Computer;
use Glpi\Application\View\TemplateRenderer;

class Superasset_Item extends CommonDBTM
{
    static public $itemtype_2 = "itemtype_computer";

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
        global $DB;

        $iterator = $DB->request(self::getTable(), [
            'WHERE' => ['plugin_test_superassets_id' => $superasset->getID()]
        ]);

        TemplateRenderer::getInstance()->display('@test/superasset_item.html.twig', [
            'superasset' => $superasset,
            'items'      => iterator_to_array($iterator),
        ]);
    }

    public static function addInDB($data)
    {
        global $DB;
        $table = self::getTable();
        $DB->insert(
            $table,
            [
                'plugin_test_superassets_id' => $data["items_id_1"],
                'itemtype' => $data["itemtype_2"],
                'items_id' => $data["items_id_2"],
            ]
        );
    }

    public static function showForComputer(Computer $computer)
    {
        echo "<h3>Superassets liés</h3>";
        // Optionnel : Ajoutez ici une logique de liste simple pour tester
    }
}
