<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishMFAContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($isAdmin){
  if (isset($_POST["submit"])) {

    $info_header = mysqli_real_escape_string($db, (trim($_POST["info_header"])));
    $info_body = mysqli_real_escape_string($db, (trim($_POST["info_body"])));

    try {
      $sql = "UPDATE tbl_configuration SET info_header='$info_header', info_body='$info_body' WHERE id=1";
      if ($db->query($sql) === true) {
          unset($_POST["submit"]);
      } else {
        db_fatal_error("contest information update issue", $db->error, $sql, $login_name);
        exit($user_err_message);
      }
    } catch (Exception $e) {
        $result[] = $e->getMessage();
        unset($_POST["submit"]);
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en-US">

<?php include("_head.php"); ?>

<body>
  <div class='container'>
    <?php if ($isAdmin){ ?>
    <h1>Enter the header information below: </h1>
    <form id="formHeaderInfo" action="#" method="post">
      <div class="form-group">
        <label for="infoHeader">Information Header</label>
        <textarea class="form-control" id="infoHeader" aria-describedby="infoHeader" name="info_header"> <?php echo $info_header; ?></textarea>
        <small id="infoHeaderHelp" class="form-text text-muted">This is the large text at the top</small>
      </div>
      <div class="form-group">
        <label for="infoBody">Information Body</label>
        <input name="info_body" type="hidden">
          <div id="editor-container">
             <?php echo $info_body; ?>
          </div>
        <small id="infoBodyHelp" class="form-text text-muted">This is the smaller, descriptive text at the top</small>
      </div>
      <div class="row clearfix justify-content-center">
        <div class="col-4 p-1 mb-2">
            <div class="btn-group" role="group">
              <button type="submit" name="submit" class='btn btn-success applyBtn'>Submit</button>
              <a id="exit" role="button" class="btn btn-light" href="index.php">Exit</a>
            </div>
        </div>
      </div>
    </form>
</div>
<?php } else { redirect_to(); }

include("_footer.php"); ?>
<!-- Include the Quill library -->
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>

<!-- Initialize Quill editor -->
<script>
var toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike','blockquote'],        // toggled buttons

  [{ 'header': 1 }, { 'header': 2 }],               // custom button values
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
  [{ 'direction': 'rtl' }],                         // text direction

  [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

  [{ 'background': [] }],          // dropdown with defaults from theme
  [{ 'font': [] }],
  [{ 'align': [] }],

  ['clean']                                         // remove formatting button
];

  var quill = new Quill('#editor-container', {
    modules: {
      toolbar: toolbarOptions
    },
    theme: 'snow'
  });

  var form = document.querySelector('form');
  form.onsubmit = function() {
    // Populate hidden form on submit
    var about = document.querySelector('input[name=info_body]');
    about.value = quill.container.firstChild.innerHTML;
    // send data to be saved in the database
    $.post('#', about.value).done(function( data ) {
      alert( "The information was updated" );
  });
}
</script>

</body>
</html>
