<?php


use GlpiPlugin\Test\Superasset;
use GlpiPlugin\Test\Superasset_Item;

use GlpiPlugin\Test\Profile as test_Profile;
use ProfileRight;


function plugin_test_install(): bool
{
    global $DB;

    $default_charset   = DBConnection::getDefaultCharset();
    $default_collation = DBConnection::getDefaultCollation();

    // instantiate migration with version
    $migration = new Migration(PLUGIN_TEST_VERSION);

    // create table only if it does not exist yet!
    $table = Superasset::getTable();
    if (!$DB->tableExists($table)) {
        //table creation query
        $query = "CREATE TABLE `$table` (
                    `id`         int unsigned NOT NULL AUTO_INCREMENT,
                    `is_deleted` TINYINT NOT NULL DEFAULT '0',
                    `name`      VARCHAR(255) NOT NULL,
                    PRIMARY KEY  (`id`)
                 ) ENGINE=InnoDB
                 DEFAULT CHARSET={$default_charset}
                 COLLATE={$default_collation}";
        $DB->doQuery($query);
    }

    $table = Superasset_Item::getTable();
    if (!$DB->tableExists($table)) {
        $query = "CREATE TABLE `$table` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `plugin_test_superassets_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                    `itemtype` VARCHAR(100) NOT NULL,
                    `items_id` INT(11) NOT NULL DEFAULT '0',
                    PRIMARY KEY  (`id`)
                 ) ENGINE=InnoDB
                 DEFAULT CHARSET={$default_charset}
                 COLLATE={$default_collation}";
        $DB->doQuery($query);
    }

    $migration->executeMigration();

    foreach (test_Profile::getAllRights() as $right) {
        ProfileRight::addProfileRights([$right['field']]);
    }

    return true;
}

function plugin_test_uninstall(): bool
{
    global $DB;

    $tables = [
        Superasset::getTable(),
        Superasset_Item::getTable(),
    ];

    foreach ($tables as $table) {
        if ($DB->tableExists($table)) {
            $DB->doQuery(
                "DROP TABLE `$table`"
            );
        }
    }
    $DB->delete(
        'glpi_displaypreferences',
        [
            'itemtype' => 'GlpiPlugin\Test\Superasset'
        ]
    );

    foreach (test_Profile::getAllRights() as $right) {
        ProfileRight::deleteProfileRights([$right['field']]);
    }
    return true;
}



function plugin_test_getAddSearchOptionsNew($itemtype)
{
    $sopt = [];

    if ($itemtype == 'Computer') {
        $sopt[] = [
            'id'           => 1001,
            'table'        => Superasset::getTable(),
            'field'        => 'name',
            'name'         => __('Associated Superassets', 'test'),
            'datatype'     => 'itemlink',
            'forcegroupby' => true,
            'usehaving'    => true,
            'joinparams'   => [
                'beforejoin' => [
                    'table' => Superasset_Item::getTable(),
                    'joinparams' => [
                        'jointype' => 'itemtype_item'
                    ]
                ]
            ]
        ];
    }

    return $sopt;
}
