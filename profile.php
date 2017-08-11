<?php
session_start(); 
// Connecting, selecting database
$dbconn = pg_connect("host=localhost dbname=users user=postgres password=")
    or die('Could not connect: ' . pg_last_error());
if(isset($_POST['log_out'])){
  session_start(); 

//Unset Session
unset($_SESSION['is_login_in']);
unset($_SESSION['user_email']);
unset($_SESSION['username']);
unset($_SESSION['id']);
header("Location:login.php");
}
include_once 'head.php';
?>

<body>

<!-- Links (sit on top) -->
<div class="w3-top">
  <div class="w3-row w3-padding w3-black">
    <div class="w3-col s3">
      <a href="#" class="w3-button w3-block w3-black">HOME</a>
    </div>
    <div class="w3-col s3">
      <a href="#about" class="w3-button w3-block w3-black">ABOUT</a>
    </div>
    <div class="w3-col s3">
      <a href="#menu" class="w3-button w3-block w3-black">MENU</a>
    </div>
    <div class="w3-col s3">
     <form action="profile.php"  method="post">
     <?php echo "Hello,".$_SESSION['username']."!"; ?>
    <input type="submit" name="log_out" value="LOG OUT">
    </form>
    </div>
  </div>
</div>

<!-- Header with image -->
<header class="bgimg w3-display-container w3-grayscale-min" id="home">
  <div class="w3-display-bottomleft w3-center w3-padding-large w3-hide-small">
    <span class="w3-tag">Open from 6am to 5pm</span>
  </div>
  <div class="w3-display-middle w3-center">
    <span class="w3-text-white" style="font-size:90px">TALK<br>PLACE</span>
  </div>
  <div class="w3-display-bottomright w3-center w3-padding-large">
    <span class="w3-text-white">15 Adr street, 5015</span>
  </div>
</header>


<div class="w3-container" id="where" style="padding-bottom:32px;">

    <h5 class="w3-center w3-padding-48"><span class="w3-tag w3-wide">WHERE TO FIND US</span></h5>
    <p>Find us at some address at some place.</p>

   <div class="w3-row">
  <div class="w3-col m6 l6">
   <p><span class="w3-tag">FYI!</span> We offer full-service catering for any event, large or small. We understand your needs and we will cater the food to satisfy the biggerst criteria of them all, both look and taste.</p>
    <p><strong>Reserve</strong> a table, ask for today's special or just send us a message:</p>
    <form id="submitForm" >
      <p><input class="w3-input w3-padding-16 w3-border" type="text" placeholder="Type your message" id="message" required name="Message"></p>
      <p><button class="w3-button w3-black" type="submit" id="send">SEND MESSAGE</button></p>
    </form>
  </div>
  <div class="w3-col m6 l6">
    <div class="w3-content w3-row m6 l6" id="about" >
    <h5 class="w3-center w3-padding-48"><span class="w3-tag w3-wide">PEOPLE LEFT THE OPINION</span></h5>
			

</div>
  </div>
</div>


</div>
<div class="w3-row w3-center">
  <div class="w3-col m12 l12">
   <div id="message_list">
      
       <?php
							$query = "SELECT p.name,p.message, u.image,p.created_at FROM posts AS p
                            INNER JOIN users AS u ON u.id=p.user_id
                            order BY p.id DESC LIMIT 3";
                            $result = pg_query($query) or die('Query failed: ' . pg_last_error());
							while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                                
								echo "<div class='post_item'><img width='40' src='data:image;base64,".$row['image']."'/><p >".$row['name']."</p>";
                                echo "<span>".$row['message']."</span><p>".$row['created_at']."</p></div><hr>";
						
							}
							// Free resultset
							pg_free_result($result);

								?>
   </div>
    </div>
    </div>
<footer class="w3-center w3-light-grey w3-padding-48 w3-large">

</footer>




<script>
    var limit=3;
    var offset=3;
 $(document).ready(function () {
      $("#send").click(function(e) {
    e.preventDefault();
      var name='<?php echo $_SESSION['username'] ?>';
      var message=$('#message').val();
      var user_id='<?php echo $_SESSION['id'] ?>';
          
    $.ajax({
        type: "POST",
        url: "register_ajax.php",
        data: { 
           name:name,message:message,user_id:user_id
        },
        success: function(data) {
            $('<div class="post_item">').html("<img width='40' src='"+"<?php echo $_SESSION['image']?>"+"'/><p>"+name+"</p><span>"+message+"</span><p>"+data['created_at']+"</p></div><hr>").prependTo('#message_list');
            limit+=1;
        },
        error: function(result) {
            alert('error');
        }
    });
});
        });
$(window).scroll(function() {
    if($(window).scrollTop() == $(document).height() - $(window).height()) {
        $.ajax({
        type: "POST",
        url: "load_post_ajax.php",
        data: { 
           numberOfPosts:limit,
            offsetPosts:offset
        },
        success: function(data) {
            console.log(data);
            for(var i=0;i<data.length;i++){
              $('<div class="post_item">').html("<img width='40' src='data:image;base64,"+data[i]['image']+"'/><p>"+data[i]['name']+"</p><span>"+data[i]['message']+"</span><p>"+data[i]['created_at']+"</p></div><hr>").appendTo('#message_list');
            }
            
        }
    });
        limit+=3;
    }
});
</script>
</body>
</html>
