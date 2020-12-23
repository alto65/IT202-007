<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>


<?php

$db = getDB();

$scoreCount = 10;
$theID = get_user_id();
$query = "SELECT count(*) as total FROM UserComps WHERE user_id = $theID ORDER BY created DESC";
pageMaker($query, $scoreCount);


$stmt = $db->prepare("SELECT u.*,c.name FROM UserComps u LEFT JOIN Comps c ON c.id=u.competition_id WHERE u.user_id = :id ORDER BY u.created DESC LIMIT :offset,:count");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", get_user_id(), PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container-fluid">
        <h3>Your Competition History</h3>
        <div class="list-group">
            <?php if (isset($results) && count($results)): ?>
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item" style="background-color: #713b6">
                        <div class="row">
				
                            <div class="col">
                                You joined: 
                                <?php safer_echo($r["name"]); ?>
                            </div>
                            <div class="col">
                                You joined this competition on: 
                                <?php safer_echo($r["created"]); ?>
                            </div>
			    
			    <div class="col">
                                <form method="POST">
				</form>
                            </div>
                             
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item">
                    Why don't go net some stats no stats?
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php require(__DIR__ . "/partials/flash.php");?>