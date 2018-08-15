<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

?>

<!DOCTYPE html>
<html lang="en">

<?php include("_head.php"); ?>

<body>

<?php include("_navbar.php");?>

    <div class="container"><!--Container of all things -->
    <div class="row clearfix">
        <div class="col-md-6 col-md-offset-3 jumbotron">

<?php if(isset($_POST['submit'])){
    $to = "um_ricks+p8lizbzwi3rwjww5f8vh@boards.trello.com, hopwoodcontestnotify@umich.edu"; // this is your Email address
    $from = htmlspecialchars($_POST['email']); // this is the sender's Email address
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $subject = "Hopwood WritingContest - " . htmlspecialchars($_POST['topic']) . " email=> " . $from . " " . date("Y/m/d h:i:sa");
    $subject2 = "Copy of your Hopwood WritingContest comment or question form submission";
    $messageFooter = "-- Please do not reply to this email. If you requested a reply or if we need more information, we will contact you at the email address you provided. --";
    $message = "logged in as=> " . $login_name . " " . $first_name . " " . $last_name . " email=> " . $from . " wrote the following:" . "\n\n" . htmlspecialchars($_POST['message']);
    $message2 = "Here is a copy of your message " . $first_name . ":\n\n" . htmlspecialchars($_POST['message']) . "\n\n" . $messageFooter;


    $headers = "From:" . $from;
    $headers2 = "From:" . $to;
    mail($to,$subject,$message,$headers);
    mail($from,$subject2,$message2, "From:english.department@umich.edu"); // sends a copy of the message to the sender
    echo "<h4>Mail Sent.</h4> <p>Thank you " . $first_name . " for getting in touch! We’ve sent you a copy of this message at the email address you provided.<br>
Have a great day!</p>";
    echo "<a class='btn btn-info' href='index.php'>Return to Hopwood Writing Contest</a>";
    // You can also use header('Location: thank_you.php'); to redirect to another page.
    unset($_POST['submit']);
    } else {
        ?>
<h4 class='text-primary'>Please describe your comment or question in the message box below. Your
input will help to make this a better resource!</h4>
<small>If you would like us to contact you please specify that in your message.</small>
<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
<div class="form-group">
<label for="first_name">First Name:</label><input type="text" class="form-control" name="first_name">
</div>
<div class="form-group">
<label for="last_name">Last Name:</label><input type="text" class="form-control" name="last_name">
</div>
<div class="form-group">
<label for="email">Email:</label><input type="email" class="form-control" name="email">
</div>
<div class="form-group">
<label for="topic">This is about a:</label>
<select class="form-control" name="topic">
  <option value="">--- Select a topic ---</option>
  <option value="Question">Question</option>
  <option value="Comment">Comment</option>
  <option value="Feature">Feature Request</option>
  <option value="Clarification">Clarification</option>
  <option value="Other">Other</option>
</select>

<div class="form-group">
<label for="message">Message:</label><textarea rows="5" class="form-control" name="message" cols="30"></textarea>
</div>
<input type="submit" name="submit" value="Submit">
</form>

<?php
}
?>
</div>
</div>
</div> <!-- END of container of all things -->
<?php
include("_footer.php");
?>
</body>
</html>
