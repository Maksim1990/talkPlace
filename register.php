<?php
session_start(); 
  if (isset($_POST['submit']))
  {
      
         if(getimagesize($_FILES['image']['tmp_name'])==FALSE)
            {
            
             echo  "<script>alert('Please select an image!')</script>";
             
            }  else {
                 $size=  $_FILES['image']['size'];
                 if($size> 2097152)
            {  echo  "<script>alert('Please select an image less than 2 MB! Your picture size is $size bytes')</script>"; }
            else{
         
                $image=  addslashes($_FILES['image']['tmp_name']);
                $name=  addslashes($_FILES['image']['name']);
                $image=  file_get_contents($image);
                $image=  base64_encode($image);
              
            
      
      
      
      $username= $_POST['name'];
    $email =$_POST['email']; 
    if (preg_match("/[0-9a-z]+[0-9a-z_\.\-]*@[0-9a-z_\-\.]+\.[a-z]{2,6}/i",$email)){   
    $password =$_POST['pass'];
    $password2 =$_POST['pass2'];
   if ($password==$password2)
   {   
  $password= md5($password);

if (empty($username)|| empty($email) || empty($password)){
    
}
else{
  $dbconn = pg_connect("host=localhost dbname=users user=postgres password=")
    or die('Could not connect: ' . pg_last_error());
      

    //   before registering we need to check email
   $check_email_sql="SELECT email FROM users WHERE email='$email' LIMIT 1";
   $emailcheck=  pg_query($check_email_sql);
   if (pg_num_rows($emailcheck)>0){
       echo "<script>alert ('Email address".$email." is already registered')</script>";
   }
   else{
       
   $created_at=  date('Y-m-d H:iS');
   $query= pg_query("INSERT INTO users ( name, email, password,created_at, imname,image) VALUES('$username','$email','$password','$created_at','$name','$image')");
 
     // Login of the user
  $logins = "SELECT * FROM users WHERE email='$email' AND password='$password' AND name='$username'  LIMIT 1";
   $result= pg_query($logins);
   
   //check the password and email
   if (pg_num_rows($result)> 0)
   {$row = pg_fetch_assoc($result);
       // create seesion for login in
       $_SESSION['is_login_in'] = true;
             $_SESSION['username'] =$row['name'];
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
} else{
       echo "<script>alert('Password does not match. Please try again.')</script>";
   }
    }else{
       echo "<script>alert('Please enter valid email.')</script>";
   }
  }
  }
}

      ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<title>Register</title>

<link rel="stylesheet" type="text/css" href="styles.css" />

</head>

<body>
<div class="w3-row w3-padding-64">
  <div class="w3-col m3  w3-center"><p></p></div>
  <div class="w3-col m6  w3-center">
     <h1>QUICK REGISTRATION</h1>

        <form action="register.php" method="post" id="signupForm" enctype="multipart/form-data">

    <div class="fieldContainer">

        <div class="formRow">
            <div class="field">
                <input type="text" name="name" class="w3-input" maxlength="10" id="name" placeholder="Name..." required/>
            </div>
        </div>
          
        <div class="formRow">
            <div class="field">
                <input type="text" name="email" class="w3-input" id="email" placeholder="Email..." required/>
            </div>
        </div>

        
        <div class="formRow">
            <div class="field">
                <input type="password" name="pass" class="w3-input" id="pass" placeholder="Password..." required/>
            </div>
        </div>
        
         <div class="formRow">
            <div class="field">
                <input type="password" name="pass2" class="w3-input" id="pass" placeholder="Repeat password..." required/>
            </div>
        </div>
       
         <div class="formRow">
            <div class="label">
                <label for="image">Profile image</label> <span class="redAt">*</span>
            </div>
            
            <div class="field1 ">
                <input  type="file" name="image" id="image"/>
            </div>
         
             <p class="attention"> <span class="redAt">*</span>Attention: your picture must be less than 2 MB </p>   
        </div>
        
    </div> 
    
    <div class="signupButton">
        <button class="w3-button w3-teal" type="submit" name="submit" id="submit" value="Send_Mail" >Register</button>
      
    </div>
    
    </form> 
      
  </div>
  <div class="w3-col m3  w3-center"></div>
</div>
    
	
        

  
 </body>
</html>
