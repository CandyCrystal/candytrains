<?php
   session_start();
   
   if(session_destroy()) {
      header("Location: https://trains.candycryst.com");
   }
?>