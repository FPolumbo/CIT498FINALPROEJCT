<?php
    session_start();
    if(!isset($_SESSION['loginID'])){
        header("Location: index.php");
    }

	include 'includes/header.php';
?>

<!-- Page content -->
<div class="w3-content" style="max-width:2000px;margin-top:46px">

    <!-- The Band List Section -->
    <!-- i have to display these in the list form -->
    <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:800px" id="artistList">
        <div class="w3-row w3-padding-32">
            <div class="w3-col md6">
                <div class="card-deck">
                    <?php
                    require_once 'includes/dbtools.php';
                    $artists = get_artists();
                    foreach($artists as $artist){
                    ?>
                    <div class="card-columns">
                        <div class="card">
                            <?php if($artist['artistPic'] !== null){ ?>
                            <img src="<?=substr($artist['artistPic'], 3)?>" class="w3-round" alt="Profile Pic" id="profilePic">
                            <?php } ?>
                            <div class="card-body">
                                <h4 class="card-title"><?=$artist['artistName']?></h4>
                                <i><?=$artist['artistLocation']?> - <?=$artist['artistGenre']?></i>
                                <br/>
                                <a href="artist.php?id=<?=$artist['userID']?>">View Artist</a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
	include 'includes/footer.php';
?>