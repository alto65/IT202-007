<link rel="stylesheet" href="static/css/styles.css">
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<nav>
<ul class="nav">
    <li><a href="home.php">Home</a></li>
    <li><a href="riseyBlock.php">Game</a></li>
    <li><a href="scoreboards.php">Scoreboards</a></li>
    
    <?php if (!is_logged_in()): ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="reg.php">Register</a></li>
    <?php endif; ?>
    <?php if (has_role("Admin")): ?>
            <li><a href="admin.php">Admin Page</a></li>
        <?php endif; ?>
    <?php if (is_logged_in()): ?>
        <li><a href="compCreate.php">Create a Competition</a></li>
        <li><a href="activeComps.php">Active Competitions</a></li>
        <li><a href="scoreHist.php">Your Score History</a></li>
        <li><a href="compHist.php">Your Competition History</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    <?php endif; ?>
</ul>
</nav>
