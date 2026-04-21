<?php
use GlpiPlugin\Test\Superasset;

include ('../../../inc/includes.php');

$supperasset = new Superasset();

if (isset($_POST["add"])) {
    $newID = $supperasset->add($_POST);

    if ($_SESSION['glpibackcreated']) {
        Html::redirect(Superasset::getFormURL()."?id=".$newID);
    }
    Html::back();

} else if (isset($_POST["delete"])) {
    $supperasset->delete($_POST);
    $supperasset->redirectToList();

} else if (isset($_POST["restore"])) {
    $supperasset->restore($_POST);
    $supperasset->redirectToList();

} else if (isset($_POST["purge"])) {
    $supperasset->delete($_POST, 1);
    $supperasset->redirectToList();

} else if (isset($_POST["update"])) {
    $supperasset->update($_POST);
    \Html::back();
    
}else if (isset($_POST["add_item"])) {
    $item_link = new \GlpiPlugin\Test\Superasset_Item();
    if (isset($_POST['items_id'], $_POST['plugin_test_superassets_id']) && $_POST['items_id'] > 0) {
        $item_link->add([
            'plugin_test_superassets_id' => $_POST['plugin_test_superassets_id'],
            'itemtype' => $_POST['itemtype'] ?? 'Computer',
            'items_id' => $_POST['items_id']
        ]);
    } else {
        Session::addMessageAfterRedirect("Please select an item to add.", false, ERROR);
    }
    Html::back();

} else {
    // fill id, if missing
    isset($_GET['id'])
        ? $ID = intval($_GET['id'])
        : $ID = 0;

    // display form
    Html::header(
       Superasset::getTypeName(),
       $_SERVER['PHP_SELF'],
       "plugins",
       Superasset::class,
       "superasset"
    );
    $supperasset->display(['id' => $ID]);
    Html::footer();
}