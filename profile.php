<?php
    // Only if user is signed in
    session_start();
    if(!isset($_SESSION['loginID'])){
        header("Location: index.php");
    }
	include 'includes/header.php';	
?>

<!-- Page content -->
<div class="w3-content" style="max-width:2000px;margin-top:46px">

  <!-- The User Section -->
  <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:800px" id="user">
  
    <h2 class="w3-wide"><?php echo $_SESSION['loginUsername']; ?></h2>
	<!--how do i echo data from database   -->
    <p class="w3-wide"><i><?php echo $_SESSION['loginEmail']; ?></i></p>
    
	<p class="w3-justify">You are now a Listener! Click here to explore <a href="artist.php">artists!</a> As a listener you can link what music you are listening on our website with the LastFM API</p>
  </div>
    <div class="w3-container w3-content w3-padding-64 w3-center" style="max-width:800px" id="artistRegistration">
        <h2 class="w3-wide">LAST.FM CONNECT</h2>
    <?php if(!isset($_SESSION['loginLastFMToken']) || $_SESSION['loginLastFMToken'] == null){ ?>
        <!-- Last.FM Link Prompt -->
        <p><a href="https://www.last.fm/api/auth/?api_key=c74a96bff24031c6f7a8f677de97a8b3&cb=http://findyourfrequency2.rockinoutrecords.com/includes/lastfmRegister.php?r=0">Link your Last.FM account</a> to share your music!</p>
    <?php }else { ?>
        <!-- Last.FM Unlink Prompt -->
        <p>Your account is linked!  <a href="includes/lastfmUnlink.php">Click here</a> to unlink.</p>
    <?php } ?>
    </div>

    <?php if(!$_SESSION['loginIsArtist']){ ?>
        <!-- The Register Section -->
        <div class="w3-container w3-content w3-padding-64" style="max-width:800px" id="artistRegistration">
            <h2 class="w3-wide w3-center">ARTIST REGISTRATION</h2>
            <p class="w3-opacity w3-center"><i>Please register below, by the way.. you look ravishing today!</i></p>
            <p class="w3-opacity w3-center"><i>P.S. You will be able to upload more info once we get ya all signed up!</i></p>
            <div class="w3-row w3-padding-32">

                <div class="w3-col lg12" >
                    <form name="artistRegistration" id=artistRegistration method ="POST" action="includes/artistRegister.php">
                        <input class="w3-input w3-border" type="text" placeholder="Band/Artist Name" required name="registerArtistName" id="registerArtistName">
                        <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
                            <div class="w3-half">
                                <input class="w3-input w3-border" type="text" placeholder="Genre" required name="registerArtistGenre">
                            </div>
                            <div class="w3-half">
                                <input class="w3-input w3-border" type="text" placeholder="Location" required name="registerArtistLocation">
                            </div>
                        </div>

                        <input class="w3-input w3-border" type="text" placeholder="Tell me about your band?! Don't be shy." required name="registerArtistBio">
                        <button class="w3-button w3-black w3-section w3-right" type="submit" name ="artistRegistration_submit" id="artistRegistration_submit">SEND</button>
                    </form>
                </div>
            </div>
        </div>
    <?php }else{ ?>
        <div class="w3-container w3-content w3-center" style="max-width:800px" id="user">
            <h3 class="w3-wide">Artist Info</h3>
        </div>
        <!-- The Profile Pic Section -->
        <div class="w3-container w3-content w3-center">
            <?php
            require_once 'includes/dbtools.php';
            $artist_info = get_artist_info($_SESSION['loginID']);
            if(isset($artist_info['artistPic']) && $artist_info['artistPic'] !== null){
                ?>
                <!-- Artist profile pic -->
                <img src="<?=substr($artist_info['artistPic'], 3)?>" alt="profile pic"/>
            <?php } ?>
            <p> Please use the button below to add/change your profile pic! </p>
            <form action="includes/picUpload.php" method="post" enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="submit">
            </form>
        </div>
        <div class="w3-container w3-content w3-center">
            <form action="includes/songUpload.php" method="post" enctype="multipart/form-data">
                Select song to upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="text" name="songName" id="songName" placeholder="Song Name">
                <input type="submit" value="Upload Song" name="submit">
            </form>
            <p>Please use the link above to upload songs!</p>
			</div>
			
			<!--Edit form artist-->	
		<div class="w3-container w3-content w3-center" >
			<form name="editArtistProfile" id=editArtistProfile method ="POST" action="includes/artistUpdate.php">
                <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
                  <div class="w3-half">
                    <input class="w3-input w3-border" type="text" placeholder=" Edit Artist Name" name="editArtistName" id="editArtistName" value="<?=$artist_info['artistName']?>">
                  </div>
				  <div class="w3-half">
                    <input class="w3-input w3-border" type="text" placeholder=" Edit Artist Genre" name="editArtistGenre" id="editArtistGenre" value="<?=$artist_info['artistGenre']?>">
                  </div>
				  <div class="w3-half">
                    <input class="w3-input w3-border" type="text" placeholder=" Edit Artist Location" name="editArtistLocation" id="editArtistLocation" value="<?=$artist_info['artistLocation']?>">
                  </div>
                    <div class="w3-section">
                        <textarea class="w3-input w3-border" type="text" placeholder="Edit Artist Bio" name="editArtistBio" id="editArtistBio"><?=$artist_info['artistBio']?></textarea>
                    </div>
                </div>
                <input class="w3-button w3-black w3-section w3-right" type="submit" name="editArtistProfile_submit" id="editArtistProfile_submit"/>
              </form>
            </div>
    <?php } ?>
	<!--Edit form user-->
	<div class="w3-container w3-content w3-center" >
          <form name="editUserProfile" id=editUserProfile method ="POST" action="includes/userUpdate.php">
                <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
                  <div class="w3-half">
                    <input class="w3-input w3-border" type="text" placeholder=" Edit Listener Name" name="editUserName" id="editUserName" value="<?=$_SESSION['loginUsername']?>">
                  </div>
                  <div class="w3-half">
                    <input class="w3-input w3-border" type="email"  placeholder="Edit Email" required name="editUserEmail" id="editUserEmail" value="<?=$_SESSION['loginEmail']?>">
                  </div>
				  <div class="w3-half">
                    <input class="w3-input w3-border" type="password" placeholder="Old Password" required name="editOldUserPassword" id="editOldUserPassword">
                  </div>
                  <div class="w3-half">
                    <input class="w3-input w3-border" type="password" placeholder="Edit Password" required name="editUserPassword" id="editUserPassword">
                  </div>
                  <div class="w3-half">
                    <input class="w3-input w3-border" type="password" placeholder="Confirm Password" required name="editUserConfirmPassword" id="editUserConfirmPassword" >
                  </div>
                </div>
                <input class="w3-button w3-black w3-section w3-right" type="submit" name="editUserProfile_submit" id="editUserProfile_submit"/>
              </form>
			  <form method="POST" action="includes/userDelete.php">
			    <button type="submit" class="w3-button w3-red w3-section w3-left">CLICK HERE TO DELETE ACCOUNT</button>
              </form>
            </div>
			

			  

	
<!-- End Page Content -->
</div>

<?php
	include 'includes/footer.php'
?>