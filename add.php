<?php
	include('config/db_connect.php');
	$inferenceID = $temp = $result = $array = $claim_fk = $claimID = $IclaimID = $thesisST = $reasonST = $ruleST = $NewOld = $oldClaim = $subject = $targetP = $supportMeans = $supportforID = $supportID = $example = $URL = $reason = '';

	$errors = array('subject' => '', 'thesis' => '', 'reason' => '','example' => '');
	if(isset($_POST['submit']))
	{	
		$supportMeans = $_POST['supportMeans'];

		
		if(array_filter($errors)){
			//echo 'errors in form';
		} else {
			// escape sql chars
			$subject = mysqli_real_escape_string($conn, $_POST['subject']);
			$thesis = mysqli_real_escape_string($conn, $_POST['thesis']);
			$reason = mysqli_real_escape_string($conn, $_POST['reason']);
			$example = mysqli_real_escape_string($conn, $_POST['example']);
 
			$NewOld = mysqli_real_escape_string($conn, $_POST['NewOld']);
			$oldClaim = mysqli_real_escape_string($conn, $_POST['oldClaim']);
			$targetP = mysqli_real_escape_string($conn, $_POST['targetP']);
			$supportMeans = mysqli_real_escape_string($conn, $_POST['supportMeans']);

			$supportforID = mysqli_real_escape_string($conn, $_POST['supportforID']);
		
			$URL = mysqli_real_escape_string($conn, $_POST['URL']);
			// for base data

// $inferenceID= ; Fx -- If [[<SupportMeans> = "Inference"] AND SupportMeansID = Lowest] Then <SupportMEANSID$>

// $claimID= 





if ($SupportMeansID == $InferenceID && $SupportMeansID == $claimID)
{
$thesisST= $subject ." " . $targetP. ".";
}
//else
//{
//}

//if ($SupportForID == $InferenceID && $SupportMeansID != $claimID)
//{
$reasonST= "Because " . $subject . " " . $reason. ".";
//}


if ($SupportMeansID == $SupportForID)
{
$ruleST= "Whomever " . $reason . " " . $targetP. " as in the case of " . $example. ".";
}

$c = uniqid (rand (),true);

$supportID =  $c;


//			$thesisSt = $subject . $thesis;
//			$reasonSt = $subject . $reason;
//			$ruleSt = "Whatever " . $reason . $thesis . " as in the case of" . $example;
		//	echo $thesisSt;
		//	echo htmlspecialchars($reasonSt);
		//	$thesisSt = mysqli_real_escape_string($conn, $_POST['thesisSt']);
		//	$reasonSt = mysqli_real_escape_string($conn, $_POST['reasonSt']);
		//	$ruleSt = mysqli_real_escape_string($conn, $_POST['ruleSt']);
		//		INSERT INTO statements
		// VALUES ("A001","Jodi","London","075-1248798");


			
// thesis st and reason st differentiation 
		
			$sql1 = "INSERT INTO claimsdb(oldClaim, subject, targetP, supportMeans, supportID, example, URL, reason) VALUES('$oldClaim','$subject', '$targetP', '$supportMeans', '$supportID','$example','$URL','$reason')";

			$sql2 = "INSERT INTO inferencedb(inferenceID, thesisST, reasonST,ruleST, claimID) VALUES('$inferenceID', '$thesisST','$reasonST','$ruleST', '$claimID')";


			$temp = $targetP;
			$targetP = $reason; 
			$reason = $temp;
			

			if ($SupportMeansID == $InferenceID && $SupportMeansID == $claimID)
{
$thesisST= $subject ." " . $targetP. ".";
}
//else
//{
//}

//if ($SupportForID == $InferenceID && $SupportMeansID != $claimID)
//{
$reasonST= "Because " . $subject . " " . $reason. ".";
//}


if ($SupportMeansID == $SupportForID)
{
$ruleST= "Whomever " . $reason . " " . $targetP. " as in the case of " . $example. ".";
}



		$sql3 = "INSERT INTO claimsdb(oldClaim, subject, targetP, supportMeans, supportID, example, URL, reason) VALUES('$oldClaim','$subject', '$targetP', '$supportMeans', '$supportID','$example','$URL','$reason')";


			$sql4 = "INSERT INTO inferencedb(inferenceID, thesisST, reasonST, ruleST, claimID) VALUES('$inferenceID', '$thesisST','$reasonST','$ruleST', '$claimID')";



			// save to db and check
			if(mysqli_query($conn, $sql1)){
				// success
				header('Location: add.php');
			} else {			echo 'query error: '. mysqli_error($conn);}
			


			if(mysqli_query($conn, $sql2)){
				// success
				header('Location: add.php');
			} else {
			echo 'query error: '. mysqli_error($conn); }




			if(mysqli_query($conn, $sql3)){
				// success
				header('Location: add.php');
			} else {
				echo 'query error: '. mysqli_error($conn);	}


			if(mysqli_query($conn, $sql4)){
				// success
				header('Location: add.php');
			} else {
				echo 'query error: '. mysqli_error($conn); }
		}
	} // end POST check
// Close connection
mysqli_close($conn);




?>


<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>
<div class="content">
<center>

  <section class="container grey-text">
		<h4 class="center">Add Claim</h4>
		<form class="white" action="add.php" method="POST">
<label>Subject</label><br>
			<input type="text" name="subject" value="<?php echo htmlspecialchars($subject) ?>">
			<div class="red-text"><?php echo $errors['subject']; ?></div>

			<label>Target Property</label><br>
			<input type="text" name="targetP" value="<?php echo htmlspecialchars($targetP) ?>"> <br>



<label>Support Means</label><br>

<select name="union" id="union">
<option value="choose">Choose One</option>
<option value="inference">Inference</option>
<option value="testimony">Testimony</option>
<option value="perception">Perception</option>
</select>
<br>

<textarea id="otherUnion" name = "reason" value="<?php echo htmlspecialchars($reason) ?>">Enter Reason Statement</textarea><br>
<textarea id="example" name = "example" value="<?php echo htmlspecialchars($example) ?>">Enter Example Statement</textarea><br>
<textarea id="url" name = "URL" value="<?php echo htmlspecialchars($URL) ?>">Enter URL</textarea><br>
<textarea id="rd" name = "rd" value="<?php echo htmlspecialchars($rd) ?>">Enter Speech/Research Document</textarea><br>

<script type="text/javascript">

var union = document.getElementById('union');
union.onchange = checkOtherUnion;
union.onchange();

function checkOtherUnion() {
    var union = this;
    var otherUnion = document.getElementById('otherUnion');
    

    if (union.options[union.selectedIndex].value === 'inference') {
        otherUnion.style.display = '';
        example.style.display = '';
    } else {
        otherUnion.style.display = 'none';
        example.style.display = 'none';
    }


if (union.options[union.selectedIndex].value === 'perception') {
        url.style.display = '';
    } else {
        url.style.display = 'none';
      
    }



if (union.options[union.selectedIndex].value === 'testimony') {
        rd.style.display = '';
    } else {
        rd.style.display = 'none';
      
    }

}
</script>


<br>


			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>

</center>
</div>
	

	<?php include('templates/footer.php'); ?>

</html>
