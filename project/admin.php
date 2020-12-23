<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    //changing location to Home because doesn't make sense to go to login if you're signed in
    flash("You don't have permission to access this page");
    die(header("Location: home.php"));
}
?>

<h3>Only the admin should be able to see this page.<h3>



<?php require(__DIR__ . "/partials/flash.php");
