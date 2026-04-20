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

    public function display($options = []) {
        global $DB;

        // 1. On récupère les données de l'objet actuel s'il existe
        $this->getFromDB($options['id']);

        // 2. On récupère la liste des ordinateurs déjà associés 
        // (Exemple : si ta table a un champ 'superassets_id')
        $iterator = $DB->request([
            'SELECT'    => ['glpi_computers.name', 'glpi_computers.id'],
            'FROM'      => 'glpi_computers',
            'INNER JOIN' => [
                'glpi_plugin_test_superassets_items' => [ // Ta table de liaison
                    'ON' => [
                        'glpi_plugin_test_superassets_items' => 'items_id',
                        'glpi_computers'                      => 'id'
                    ]
                ]
            ],
            'WHERE'     => ['glpi_plugin_test_superassets_items.plugin_test_superassets_id' => $options['id']]
        ]);

        $associated_computers = iterator_to_array($iterator);

        // 3. On envoie tout au template Twig
        TemplateRenderer::getInstance()->display('@test/superasset_form.html.twig', [
            'item'   => $this,
            'computers' => $associated_computers
        ]);
    }


    function rawSearchOptions()
    {
        $options = [];

        $options[] = [
            'id'   => 'common',
            'name' => __('Characteristics')
        ];

        $options[] = [
            'id'    => 1,
            'table' => self::getTable(),
            'field' => 'name',
            'name'  => __('Name'),
            'datatype' => 'itemlink'
        ];

        $options[] = [
            'id'    => 2,
            'table' => self::getTable(),
            'field' => 'id',
            'name'  => __('ID')
        ];

        $options[] = [
            'id'           => 3,
            'table'        => Superasset_Item::getTable(),
            'field'        => 'id',
            'name'         => __('Number of associated assets', 'test'),
            'datatype'     => 'count',
            'forcegroupby' => true,
            'usehaving'    => true,
            'joinparams'   => [
                'jointype' => 'child',
            ]
        ];

        return $options;
    }
}