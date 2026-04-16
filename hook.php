<?php


use DBConnection;
use GlpiPlugin\Test\Superasset;
use Migration;


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

    //execute the whole migration
    $migration->executeMigration();

    return true;
}

function plugin_test_uninstall(): bool
{
    global $DB;

    $tables = [
        Superasset::getTable(),
    ];

    foreach ($tables as $table) {
        if ($DB->tableExists($table)) {
            $DB->doQuery(
                "DROP TABLE `$table`"
            );
        }
    }
    return true;
}