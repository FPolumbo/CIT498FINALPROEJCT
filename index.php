<?php
    // Only if user is not signed in
    session_start();
    if(isset($_SESSION['loginID'])){
        header("Location: artists.php");
    }
    include 'includes/header.php';
?>
<!-- Page content -->
<div class="w3-content" style="max-width:2000px;margin-top:46px">

  <!-- The About Section -->
  <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:800px" id="about">
    <h1 id="find-freq" style="font-size:300%;">Find Your Frequency</h1>
    <p class="we-love-music" class="w3-opacity"><span class="note">♪</span> <em>WE LOVE MUSIC</em> <span class="note">♫</span></p>
    <p class="w3-justify">The goals of Find Your Frequency are to create a web-based platform that musicians can utilize to showcase recordings, recent releases, and can also be used to create channels of communication with fans.  By applying these goals artist will have easier access to fans. The ease of access provides fans with a more surreal experience. </p>
    <div class="w3-row w3-padding-32">
      <div>
        <h5>Founder / Owner / Jack Of All Trades</h5>
        <p class="frankie-polumbo">Frankie Polumbo</p>
        <img id="your-presenter-frank" src="img/your-presenter-frank.png" class="w3-round w3-margin-bottom" alt="Owner" style="width:25%">
      </div>
      </div>
  </div>

  <!-- The User Registration Section -->
  <div class="w3-black" id="userRegistrationSection">
    <div class="w3-container w3-content w3-padding-64 w3-black" style="max-width:800px">
      <h2 class="w3-wide w3-center">LISTENER REGISTRATION</h2>
      <p class="w3-opacity w3-center"><i>Please register below, by the way.. you're doing great!</i></p>
	  <p class="w3-opacity w3-center"><i>By Registering, We assume you've read our <a href="terms.php"></a></i></p>
      <div class="w3-row w3-padding-32">
        
        <div class="w3-col lg12" > 
          <form name="userRegistration" id=userRegistration method ="POST" action="includes/userRegister.php">
              
                <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
                  <div class="w3-half">
                    <input class="w3-input w3-border" type="text" placeholder="Listener Name" name="registerUserName" id="registerUserName">
                  </div>
                  <div class="w3-half">
                    <input class="w3-input w3-border" type="email"  placeholder="Email" required name="registerUserEmail" id="registerUserEmail">
                  </div>
                  <div class="w3-half">
                    <input class="w3-input w3-border" type="password" placeholder="Password" required name="registerUserPassword" id="registerUserPassword">
                  </div>
                  <div class="w3-half">
                    <input class="w3-input w3-border" type="password" placeholder="Confirm Password" required name="registerUserConfirmPassword" id="registerUserConfirmPassword" >
                  </div>
                </div>
                <input class="w3-button w3-black w3-section w3-right" type="submit" name="userRegistration_submit" id="userRegistration_submit"/>
              </form>
            </div>
          </div>
      </div>
    </div>
  
<!-- End Page Content -->
</div>

<?php
    include 'includes/footer.php';
    ?>
