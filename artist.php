<?php
    session_start();
    if(!isset($_SESSION['loginID'])){
        header("Location: index.php");
    }

    include 'includes/header.php';
?>

<!-- Page content -->
<div class="w3-content" style="max-width:2000px;margin-top:46px">
    <?php
        if(isset($_GET['id'])) {
            require_once 'includes/dbtools.php';
            $artist = get_artist_info($_GET['id']);
        }
        if(isset($artist) && $artist !== null){
        ?>

    <!-- The Band Section -->
    <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:800px" id="band">
        <?php if($artist['artistPic'] !== null){ ?>
        <img src="<?=substr($artist['artistPic'],3)?>" class="w3-round" alt="Profile Pic" id="profilePic">
        <?php } ?>
        <h2 class="w3-wide"><?=$artist['artistName']?></h2>
        <p class="w3-opacity"><i><?=$artist['artistGenre']?></i></p>
        <p class="w3-opacity"><i><?=$artist['artistLocation']?></i></p>
        <p class="w3-justify"><?=$artist['artistBio']?></p>
    </div>

    <!-- this is the music player spot -->
    <div class="w3-container w3-content w3-padding-64" style="max-width:800px" id="contact">
        <h2 class="w3-wide w3-center">Stay a while, and listen!</h2>
        <audio id="player" controls></audio>
        <script>
            function play(url,song){
                (function(p){
                    p.setAttribute("src", url);
                    p.play();
                })(document.getElementById("player"));
                <?php if(isset($_SESSION['loginLastFMToken']) && $_SESSION['loginLastFMToken'] !== null){ ?>
                let xhr = new XMLHttpRequest();
                xhr.open("GET", "includes/scrobble.php?artist=<?=urlencode($artist['artistName'])?>&track="+song);
                xhr.send();
                <?php } ?>
            }
        </script>
        <ul>
            <?php
            $songs = get_tracks($_GET['id']);
            foreach($songs as $song){
            ?>
            <li><a href="javascript:play('<?=substr($song['url'],3)?>','<?=$song['name']?>')"><?=$song['name']?></a></li>
            <?php } ?>
        </ul>
    </div>

    <!-- The Contact Section -->
    <div class="w3-black" id="contact">
        <h2 class="w3-wide w3-center">CONTACT</h2>
        <p class="w3-opacity w3-center"><i>Chat with the BAND!</i></p>
        <div class="w3-row w3-padding-32">
            <div class="w3-col lg12">
                <form action="mailto:<?=$artist['email']?>" method="GET" enctype="text/plain">
                    <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
                        <div class="w3-half">
                            <input class="w3-input w3-border" type="text" placeholder="Subject" required name="Subject">
                        </div>
                    </div>
                    <input class="w3-input w3-border" type="text" placeholder="Message" required name="body">
                    <button class="w3-button w3-black w3-section w3-right" type="submit">SEND</button>
                </form>
            </div>
        </div>
    </div>
<?php }else{ ?>
    <div><b>Error:</b> No artist selected!  Click <a href="artists.php">here</a> to choose one!</div>
<?php } ?>
  
<!-- End Page Content -->
</div>
<?php
	include 'includes/footer.php';
?>