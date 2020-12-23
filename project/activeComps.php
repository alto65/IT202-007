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
$query = "SELECT count(*) as total FROM Comps WHERE expires > current_timestamp ORDER BY expires ASC";
pageMaker($query, $scoreCount);


$stmt = $db->prepare("SELECT * FROM Comps WHERE expires > current_timestamp ORDER BY expires ASC LIMIT :offset,:count");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST["join"])) {
    $balance = getBalance();
    $stmt = $db->prepare("select fee from Comps where id = :id && expires > current_timestamp && paid_out = 0 LIMIT 10");
    $r = $stmt->execute([":id" => $_POST["cid"]]);//[":id" => $_POST["cid"]]
    if ($r) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $fee = (int)$result["fee"];
            if ($balance >= $fee) {
                
                $stmt = $db->prepare("INSERT INTO UserComps (competition_id, user_id) VALUES(:cid, :uid)");
                $r = $stmt->execute([":cid" => $_POST["cid"], ":uid" => get_user_id()]);
                if ($r) {
                    flash("Competition joined successfully", "success");

			$user_id=get_user_id();
			$points_change = -($fee);
			$reason = "Joined new comp";
			$stmt = $db->prepare("INSERT INTO pointTotals( user_id, points_change, reason) VALUES(:user_id,:points_change,:reason)");
			$params = array( ":user_id" => $user_id, ":points_change" => $points_change, ":reason" => $reason);
			$r = $stmt->execute($params);
            
		    $stmt = $db->prepare("UPDATE Users set points = (SELECT IFNULL(SUM(points_change), 0) FROM pointTotals p where p.user_id = :id) WHERE id = :id");
		    $params = array(":id" => get_user_id());
		    $r = $stmt->execute($params);
            
 
			    $stmt = $db->prepare("SELECT points from Users WHERE id = :id LIMIT 1");
			    $params = array(":id" => get_user_id());
			    $r = $stmt->execute($params);
			    if($r){
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$points = $result["points"];
				$_SESSION["user"]["points"] = $points;
			    }
			$stmt = $db->prepare("UPDATE Comps set participants = participants+1 WHERE id = :id");
            		$params = array(":id" => $_POST["cid"]);
            		$r = $stmt->execute($params);
			
			$increment = (int)max(1, $fee * .5);
			if($fee==0){
				$increment=0;}
			$stmt = $db->prepare("UPDATE Comps set reward = reward + :increment WHERE id = :id");
            		$params = array(":id" => $_POST["cid"], ":increment" => $increment);
            		$r = $stmt->execute($params);//*/
			
			
                    die(header("Location: #"));
                }
                else {
		     flash("You're already a part of this tilly, bud. No need to put your name in there a second time, bud.", "warning");
                }
            }
            else {
                flash("You don't have enough dough to get in this... competition", "warning");
            }
        }
        else {
            flash("Competition is unavailable", "warning");
        }
    }
    else {
        flash("Competition is unavailable", "warning");
    }
}
?>
<div class="container-fluid">
        <h3>Active Competitions</h3>
        <div class="list-group">
            <?php if (isset($results) && count($results)): ?>
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item" style="background-color: #D7C51B">
                        <div class="row">
                            
                            <div class="col">
                                Name: 
                                <?php safer_echo($r["name"]); ?>
                                <?php if ($r["user_id"] == get_user_id()): ?>
                                    (Created)
                                <?php endif; ?>
                            </div>
                            <div class="col">
                                Participants: 
                                <?php safer_echo($r["participants"]); ?>
                            </div>
				
                            <div class="col">
                                Required Score: 
                                <?php safer_echo($r["min_score"]); ?>
                            </div>
                            <div class="col">
                                Reward: 
                                <?php safer_echo($r["reward"]); ?>
                                <!--TODO show payout-->
                            </div>
                            <div class="col">
                                Expires: 
                                <?php safer_echo($r["expires"]); ?>
                            </div>
                            <div class="col">
                                    <form method="POST">
                                        <input type="hidden" name="cid" value="<?php safer_echo($r["id"]); ?>"/>
                                        <input type="submit" name="join" class="btn btn-primary"
                                               value="Join (Cost: <?php safer_echo($r["fee"]); ?>)"/>
                                    </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item">
                    Sorry Sally, looks like there are no games for you to lose today, you poptart.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php require(__DIR__ . "/partials/flash.php");?>
