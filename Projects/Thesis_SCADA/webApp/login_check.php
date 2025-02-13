<?php

if(isset($_POST['uname']) && isset($_POST['pw'])){
  include './users.php';
  session_start();

  if(password_verify($_POST['pw'],$users[$_POST['uname']]['password'])){
    $_SESSION['sid'] = session_id();
    $_SESSION['uname'] = $users[$_POST['uname']]['fullname'];
    header('location: index.php');
  }
  else{
  header('location:./login.php');
  }
}
else{
 header('location:./login.php');
}

 ?>
