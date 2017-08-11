<?php
session_start(); 


if(isset($_POST['submit']));
{

    $email =  htmlentities($_POST['email']);
    $password =  htmlentities($_POST['pass']);
    $password= md5($password);
    $dbconn = pg_connect("host=localhost dbname=users user=postgres password=")
    or die('Could not connect: ' . pg_last_error());
   
    if ( empty($email) || empty($password)){
    
    }
    else{
   // Login of the user
    $logins = "SELECT * FROM users WHERE email='$email' AND password='$password'   LIMIT 1";
    $result= pg_query($logins);
   
   //check the password and email
   if (pg_num_rows($result)>0)
   { $row = pg_fetch_assoc($result);
       // create seesion for login in
      $_SESSION['is_login_in'] = true;
      $_SESSION['username']=$row['name'];
      $_SESSION['id']=$row['id'];
      $_SESSION['image']="data:image;base64,".$row['image'];
      $_SESSION['user_email'] = $email;
      header("Location: profile.php");
   }
   
   else{
       echo "<script>alert('Provided email or password is invalid!!!')</script>";
        }

   }
} 
include_once 'head.php';
?>
<title>Login</title>
</head>
<body id="login">
<div class="w3-row w3-padding-64">
  <div class="w3-col s4  w3-center"><p></p></div>
  <div class="w3-col s4 w3-center backLogin">
      <h1 class="w3-text-white">LOGIN</h1>

        <form action="login.php" method="post" id="signupForm">
        <div class="formRow">
            <div class="field">
                <input type="text" name="email" class="w3-input" id="email" placeholder="Email..." required/>
            </div>
        </div>    
        <br/> 
        <div class="formRow">
 
            <div class="field">
                <input type="password" class="w3-input" name="pass" id="pass" placeholder="Password..." required/>
            </div>
        </div>
        <br/>
    <div class="signupButton">
          <button class="w3-button w3-teal" type="submit" name="submit" id="submit" value="Send_Mail" >Login</button>
    </div> 
    </form>
   <p style="display:inline-block; float: left;" class="w3-text-white"><a href="register.php">Register</a></p>
  </div>
  <div class="w3-col s4 w3-center"></div>
</div>
</body>
</html>