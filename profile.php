<?php
session_start(); 
if(isset($_SESSION['username'])){

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
<title>Profile</title>
<script>
    function printTime(){
        var d = new Date();
        var hours=d.getHours();
        hours = ("0" + hours).slice(-2);
        var mins= d.getMinutes();
        mins = ("0" + mins).slice(-2);
        var secs=d.getSeconds();
        secs = ("0" + secs).slice(-2);
        var time=document.getElementById("time");
        time.style.color = "gray";
        time.style.fontSize = "25px";
        time.style.marginTop = "4px";
        time.style.fontFamily = "Track";
        time.innerHTML=hours+":"+mins+":"+secs;

    }
    setInterval(printTime, 1000);
</script>
</head>
<body onload="get_statistics()">

<!-- Links (sit on top) -->
<div class="w3-top">
  <div class="w3-row w3-padding w3-black">
    <div class="w3-col s4">
      <a href="#" class="w3-button w3-block w3-black">HOME</a>
    </div>
    <div class="w3-col s4">
      <a href="#about" class="w3-button w3-block w3-black">POSTS</a>
    </div>
 
    <div class="w3-col s4">
     <form action="profile.php"  method="post">
     <?php echo "Hello,".$_SESSION['username']."!"; ?>
    <input type="submit" class="w3-button w3-teal" name="log_out" value="LOG OUT">
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

    <h5 class="w3-center w3-padding-48"><span class="w3-tag w3-wide">BEST PLACE FOR SHARING YOUR IDEAS</span></h5>

   <div class="w3-row">
  <div class="w3-col m6 l6">
   <p>Share your ideas with others and be familiar with what other people think and share. 
    <p><strong>Type and post</strong> your message by using above form:</p>
    <form id="submitForm" >
      <p><input class="w3-input w3-padding-16 w3-border" type="text" placeholder="Type your message" id="message" required name="Message"></p>
      <p><button class="w3-button w3-black" type="submit" id="send">SEND MESSAGE</button></p>
    </form>
  </div>
  <div class="w3-col m5 l5">
    <div class="w3-content w3-row m6 l6" id="about" >
    <h5 class="w3-center"><span class="w3-tag w3-wide">TALKPLACE STATISTICS</span></h5>
                <table class="w3-table">
                <tr >
                  <th class=" w3-center">Quontity of users registered at TalkPlace:</th>
                  <td id="users_quantity"></td>
                </tr>
                <tr>
                  <th class=" w3-center">Quontity of posts currently at TalkPlace:</th>
                  <td id="post_quantity"></td>
                </tr>
                 <tr>
                  <th class=" w3-center">Last post was created by:</th>
                  <td id="last_post_user"></td>
                </tr>
                 <tr>
                  <th class=" w3-center">Last post was created at:</th>
                  <td id="last_post_created_at"></td>
                </tr>
                 <tr>
                  <th class=" w3-center">Current time:</th>
                  <td ><span id="time"></span></td>
                </tr>
                </table>	
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
                                
								echo "<div class='post_item'><img width='50' src='data:image;base64,".$row['image']."'/><p >".$row['name']."</p>";
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
            get_statistics();
            $('#message').val('');
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
              $('<div class="post_item">').html("<img width='50' src='data:image;base64,"+data[i]['image']+"'/><p>"+data[i]['name']+"</p><span>"+data[i]['message']+"</span><p>"+data[i]['created_at']+"</p></div><hr>").appendTo('#message_list');
            }
            
        }
    });
        limit+=3;
    }
});
</script>
<script>
function get_statistics(){
      $.ajax({
        type: "POST",
        url: "get_statistics_ajax.php",
        data: { 
      
        },
        success: function(data) {
            console.log(data);
            $('#post_quantity').text(data['post_quantity']['post_quantity']);
            $('#users_quantity').text(data['users_quantity']['users_quantity']);
            $('#last_post_created_at').text(data['last_post_created_at']);
            $('#last_post_user').text(data['last_post_user']);
            }
            
        });
}
</script>
</body>
</html>
<?php
    }else{
    header("Location: login.php");
}