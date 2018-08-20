<?php

   include("Connection.php");
   session_start();

   //Do this if you receive a request from a form
   if($_SERVER["REQUEST_METHOD"] == "POST") {

       //Username and password sent from form
       $myusername = $_POST['user'];
       $mypassword = $_POST['pass'];

       //Check the user and password in the database
       $sql = "SELECT id FROM ost_user_account WHERE username = '$myusername' and passwd = '$mypassword'";
       $result = mysqli_query($con,$sql);

       $row=mysqli_fetch_row($result);
       $count = mysqli_num_rows($result);

       //If result matched $myusername and $mypassword, table row must be 1 row
       //The user has to be the designed for the page, change it here AND in the database
       if($count == 1 and $myusername='Statistics') {

           //Insert the value in the session to check it later and redirect to the webpage
           $_SESSION['login_user'] = $myusername;
           header("location: Statistics.php");

       }else {

           //If there is an error return to login
           $error = "Your Login Name or Password is invalid";
           header("location: index.html");
       }
   }
?>