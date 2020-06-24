
<?php 

  include('config/db_connect.php');
  $sql = 'SELECT thesisST, reasonST, ruleST, supportMeans, subject, targetP, claimID FROM claimsdb';
  // get the result set (set of rows)
  $result = mysqli_query($conn, $sql);
  // fetch the resulting rows as an array // was $result
  $claimsdb = mysqli_fetch_all($result, MYSQLI_ASSOC);
  
  // close connection
 

?>

  <link rel="stylesheet" href="./style.css">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<div class="wrapper">
    <ul>
      <li class="noline">

<!-- --------------------------------------------------- -->
<div class="header">
  <a href="#default" class="logo">Vada Claims</a>
  <div class="header-right">
    <a class="active" href="#home">Home</a>
    <a href="#contact">Contact</a>
    <a href="#about">About</a>
  </div>
</div>


<!-- --------------------------------------------------- -->




<BR><BR>
         CLAIMS
          <br>
<a class="brand-text" href="add.php" style=" color : #fff;">Add New Claim</a><br><br>
Claims displayed as a <font color = "seagreen"> green font </font> mean that they are currently active. <br> Claims displayed as a <font color = "#FFFF99"> yellow font </font> mean that the are currently inactive. <br>




         <!-- partial:index.partial.html -->

  <br>
  </center>

<center>
      <?php   //changing the centers above is a fun change

        //this code finds ALL claims that are not flaggers (all root claims)
$root1 = "SELECT DISTINCT claimID
        from claimsdb, flagsdb 
            WHERE claimID NOT IN (SELECT DISTINCT claimIDFlagger FROM flagsdb)
        "; // SQL with parameters
$stmt5 = $conn->prepare($root1); 
$stmt5->execute();
$rootresult1 = $stmt5->get_result(); // get the mysqli result
$numhitsroot = mysqli_num_rows($rootresult1);

 while($root = $rootresult1->fetch_assoc())
  {
         sortclaims($root['claimID']);
  }




$root2 = "SELECT DISTINCT claimIDFlagger
        from flagsdb 
            WHERE isRootRival = 1;
        "; // SQL with parameters
$stmt12 = $conn->prepare($root2); 
$stmt12->execute();
$rootresult2 = $stmt12->get_result(); // get the mysqli result
$numhitsroot2 = mysqli_num_rows($rootresult2);

while($root2 = $rootresult2->fetch_assoc())
  { 
         sortclaimsRIVAL($root2['claimIDFlagger']);
       //  echo "RIVAL";
  }






function sortclaimsRIVAL($claimid)
{
 restoreactivity($claimid);
include('config/db_connect.php');
  $sql1 = "SELECT DISTINCT claimIDFlagger
        from claimsdb, flagsdb
        where ? = claimIDFlagged AND flagType NOT LIKE 'thesisRival'
         
        "; // SQL with parameters
$stmt1 = $conn->prepare($sql1); 
$stmt1->bind_param("i", $claimid);
$stmt1->execute();
$result1 = $stmt1->get_result(); // get the mysqli result
$numhits1 = mysqli_num_rows($result1);
//echo $numhits1;
// THIS IS SIMPLY FOR DISPLAY OF SUBJECT/TARGETP BELOW

$dis = "SELECT DISTINCT subject, targetP, active
        from claimsdb
        where ? = claimID
         
        "; // SQL with parameters
$st = $conn->prepare($dis); 
$st->bind_param("i", $claimid);
$st->execute();
$disp = $st->get_result(); // get the mysqli result


// SIMPLY FOR DISPLAY ABOVE THIS POINT

?>


  <li> <label for="<?php echo $claimid; ?>"><?php 
while($d = $disp->fetch_assoc())
{
  // FONT CHANGING
 if($d['active'] == 1)
{ $font = 'seagreen';

 }
else {
  $font = '#FFFF99'; } ?>

<font color = "<?php echo $font; ?>"> 
<?php    // END FONT CHANGING


/*?><h2>Let the borders collapse:</h2>

<table>
  <tr>
    <th>Firstname</th>
  </tr> </table>
<?php
*/
 echo $claimid;
 echo "RIVALS";
echo nl2br("\r\n");      
  ?>  <div class='a'> <?php

  echo $d['subject'] . ' ';
// echo nl2br("\r\n");
  echo $d['targetP'];


/*  $subject = wordwrap($d['subject'], 8, "\n", true);
$targetP = wordwrap($d['targetP'], 8, "\n", true);
  echo $subject;
  echo $targetP;*/
/// ------------------------------------------------------------------- BELOW is modal code
  ?> </div> <?php
}


 ?>

<!-- <a class="brand-text" style=" color : #fff;" href ="add.php">Link</a> -->
 </label><input id="<?php echo $claimid; ?>" type="checkbox">
      <ul> <span class="more">&hellip;</span>

<?php

while($user = $result1->fetch_assoc())
{

 if($numhits1 == 0)
  { }
   else { sortclaims($user['claimIDFlagger']); }
    

} // end while loop

?></ul><?php

} // end of rivalfunction









function restoreactivity ($claimid)
{
include('config/db_connect.php');
$act = "SELECT DISTINCT claimIDFlagger
        from flagsdb
        WHERE claimIDFlagged = ?"; // SQL with parameters
$s = $conn->prepare($act); 
$s->bind_param("i", $claimid);
$s->execute();
$activity = $s->get_result(); // get the mysqli result



while($activestatus = $activity->fetch_assoc())
  { 
    $h = "SELECT DISTINCT active
        from claimsdb
        WHERE ? = claimID"; // SQL with parameters
      $noce = $conn->prepare($h); 
      $noce->bind_param("i", $activestatus['claimIDFlagger']);
      $noce->execute();
      $res = $noce->get_result(); // get the mysqli result
      $numh = mysqli_num_rows($res);


    
  //    echo nl2br("\r\n");
    //  echo $activestatus['claimIDFlagger'];
      //echo nl2br("\r\n");

$everyInactive = 'true';
//echo $everyInactive;
  while($r = $res->fetch_assoc())
  {  
    
    if($r['active'] == 1)
    {
      global $everyInactive;
      $everyInactive = 'false';
  //    echo $everyInactive;
      $act = "UPDATE claimsdb 
SET active = 0
WHERE claimID = ? 
"; // SQL with parameters
$upd = $conn->prepare($act); 
$upd->bind_param("i", $claimid);
$upd->execute();
    }
  }


  if($everyInactive == 'true')
  {
  
//echo "ANSWER" . $everyInactive;
// BELOW CHANGES THE ACTIVE STATE OF OTHER CLAIMS
$act = "UPDATE claimsdb 
SET active = 1
WHERE claimID = ? 
"; // SQL with parameters
$upd = $conn->prepare($act); 
$upd->bind_param("i", $claimid);
$upd->execute();
 } // end of second if statement
     } //end first while loop

//THIS ABOVE CHANGES THE ACTIVE STATE OF OTHER CLAIMS
  
  }  // end function

function createModal($claimid)
{
?>
<br><button class="button" id="myBtn<?php echo $claimid ?>">DETAILS</button>

<!-- The Modal -->
<div id="myModal<?php echo $claimid ?>" class="modal">
  <!-- Modal content -->
  <div class="modal-content">

    <span class="close" id="<?php echo $claimid ?>">&times;</span>
    <center>
<font color ="black">
<?php 

include('config/db_connect.php');
echo 'Below are details for this specific claim.';
echo nl2br("\r\n");

//$claimid = '336';

$c = "SELECT DISTINCT * FROM claimsdb WHERE claimID = ?       
        "; // SQL with parameters
$ste = $conn->prepare($c); 
$ste->bind_param("i", $claimid);
$ste->execute();
$ru = $ste->get_result(); // get the mysqli result

while($r = $ru->fetch_assoc())
{
  echo $r['claimID'];
  echo nl2br("\r\n");
  echo $r['supportMeans'];
}

?>




<script>

//var modal = document.getElementById('myModal<?php echo $claimid ?>');
var modal = this.document.querySelectorAll('.myModal<?php echo $claimid ?>.modal'); 
var btns = this.document.querySelectorAll('.button.button'); 
var span = this.document.getElementsByClassName("close")[0];

[].forEach.call(btns, function(el) {
  el.onclick = function() {
      modal.style.display = "block";
  }
})

span.onclick = function() {
    modal.style.display = "none";
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
} 


</script>
</font>
</div>
</div>
<?php

}

function sortclaims($claimid)
{
 restoreactivity($claimid);

include('config/db_connect.php');
  $sql1 = "SELECT DISTINCT claimIDFlagger
        from claimsdb, flagsdb
        WHERE ? = claimIDFlagged AND flagType NOT LIKE 'thesisRival'"; // SQL with parameters
$stmt1 = $conn->prepare($sql1); 
$stmt1->bind_param("i", $claimid);
$stmt1->execute();
$result1 = $stmt1->get_result(); // get the mysqli result
$numhits1 = mysqli_num_rows($result1);
//echo $numhits1;

$flag = "SELECT DISTINCT flagType, claimIDFlagger, claimIDFlagged
        from flagsdb
        WHERE ? = claimIDFlagger"; // SQL with parameters
$stmt4 = $conn->prepare($flag); 
$stmt4->bind_param("i", $claimid);
$stmt4->execute();
$result2 = $stmt4->get_result(); // get the mysqli result
$numhitsflag = mysqli_num_rows($result2);

// THIS IS SIMPLY FOR DISPLAY OF SUBJECT/TARGETP BELOW

$dis = "SELECT DISTINCT subject, targetP, active
        from claimsdb
        where ? = claimID
         
        "; // SQL with parameters
$st = $conn->prepare($dis); 
$st->bind_param("i", $claimid);
$st->execute();
$disp = $st->get_result(); // get the mysqli result


// SIMPLY FOR DISPLAY ABOVE THIS POINT

?>

  <li> <label for="<?php echo $claimid; ?>"><?php 
while($d = $disp->fetch_assoc())
{
  // FONT CHANGING
 if($d['active'] == 1)
{ $font = 'seagreen';

 }
else {
  $font = '#FFFF99'; } ?>

<font color = "<?php echo $font; ?>"> 
<?php    // END FONT CHANGING


/*?><h2>Let the borders collapse:</h2>

<table>
  <tr>
    <th>Firstname</th>
  </tr> </table>
<?php
*/
 echo $claimid;

echo nl2br("\r\n");      
  ?>  <div class='a'> <?php

  echo $d['subject'] . ' ';
// echo nl2br("\r\n");
  echo $d['targetP'];


/*  $subject = wordwrap($d['subject'], 8, "\n", true);
$targetP = wordwrap($d['targetP'], 8, "\n", true);
  echo $subject;
  echo $targetP;*/
/// ------------------------------------------------------------------- BELOW is modal code
  ?> </div> <?php 
createModal($claimid);
  /// ------------------------- ABOVE is modal code

}


 ?>

<!-- <a class="brand-text" style=" color : #fff;" href ="add.php">Link</a> -->
 </label><input id="<?php echo $claimid; ?>" type="checkbox">
      <ul> <span class="more">&hellip;</span>



<?php

  while($flagge = $result2->fetch_assoc())
  {
 if($flagge['flagType'] == "thesisRival")
      {
      echo nl2br("\r\n");
      echo "RIVAL";
      echo nl2br("\r\n");
      sortclaimsRival($flagge['claimIDFlagger']);
      // for THIS claimid - check for flaggers that aren't rival .. sort claim those
      sortclaimsRival($flagge['claimIDFlagged']);
      // for the CORRESPONDING claimid - check for flaggers that aren't rival .. sort claim those.
      }
  }


while($user = $result1->fetch_assoc())
{
 if($numhits1 == 0)
  { }
   
   else { sortclaims($user['claimIDFlagger']); }
    
} // end while loop
restoreactivity($claimid);
?></ul><?php
} // end of function
//account for null instances in the active/inactive setting and updating algorithm 
// reimpleplement recursion independently for active/inactive
// reexamine root rivals and troubleshoot multiple instances of it
?>





<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
  <script  src="./script.js"></script>
 </body>
</html>
<?php mysqli_close($conn); ?>
