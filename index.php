<?php
require ("config.php");
include "template\header.php";

//check for defaults
session_start();
 if(empty($_SESSION['$firstrun'])){
   //$_SESSION['$qstn_no'] =  rand (1,3);
   $_SESSION['$qstn_no'] = 1;
   $_SESSION['$quiz_no'] = 2;
   $_SESSION['$qstn_count'] = 1;
   $_SESSION['$score'] = 0;
   $_SESSION['$firstrun'] = "false";
 }

   $qstn_no   = $_SESSION['$qstn_no'];
   $quiz_no   = $_SESSION['$quiz_no'];
   $qstn_count =  $_SESSION['$qstn_count'];
   $score     = $_SESSION['$score'];
   $firstrun  = $_SESSION['$firstrun'];


?>

<?php
/*
  $qry = "SELECT COUNT(*) FROM `question` WHERE `isActive` = :qstn_id";
  $stmt = $conn->prepare($qry);
  $stmt->execute(array(':qstn_id' => $qstn_no));
  $row = $stmt->fetch();
    echo "<span class='badge'>Number of quiz: " . $row[0] ."</span>";
*/
?>

<div class="container">
<?php
echo "<div class='pull-right'><span>Score: " . $score . "</span> | Time left: <span id='timer'>0:00</span></div>"

?>
<form method='post' id='quiz' onSubmit='validate();'>
  <div>
  <?php
    $qry = "SELECT COUNT(*) FROM `question` WHERE `quiz_id` = :quiz_id AND `isActive` = 1 ";
    $stmt = $conn->prepare($qry);
    $stmt->execute(array(':quiz_id' => $quiz_no));
    $row = $stmt->fetch();
      echo "<span style='border-bottom: 1px solid #ccc; padding-bottom: 3px'>Question " . $qstn_count . " of " . $row[0] ."</span>";

    $qry = "SELECT t.hint from question_type t join question q on t.question_type_id=q.question_type_id where q.question_id = :qstn_id";
    $stmt = $conn->prepare($qry);
    $stmt->execute(array(':qstn_id' => $qstn_no));
    $row = $stmt->fetch();
    echo "<span class='pull-right badge' data-toggle='tooltip' data-placement='bottom' title='" . $row[0] . "'>?</span>";
  ?>

  </div>
<?php

try {
  $qry1 = "SELECT question FROM question where quiz_id = :quiz_id AND isActive = 1";
  $stmt = $conn->prepare($qry1);

  //Question
  $stmt->execute(array(':quiz_id' => $quiz_no));
  $row = $stmt->fetch();
  echo "<div class='form-group'>";
  echo "<h4>" . $row[0] . "</h4>";
  echo "</div>";

// MULTIPLE CHOICE (RADIO BUTTONS)
  //Choices
  $qry2 = "SELECT `text`, `choice_id`, `isCorrect` FROM `question_choices` WHERE `question_id` = :qstn_id ORDER BY RAND()";
  //$qry2 = "SELECT COUNT(*) FROM `question_choices` WHERE `question_id` = :qstn_id;"
  $stmt = $conn->prepare($qry2);
  //print $stmt;
  $stmt->execute(array(':qstn_id' => $qstn_no));
  //print $stmt;
  //foreach ($qry2 as $row) {
  foreach ($stmt as $row) {
    $i = 0;
    echo "<div class='radio'>";
    echo "<label>";
    echo "<input type='radio' name='option' value='" . $row[1] . "' >";
    echo "<span>" . $row[0] . "</span>";
    echo "</label>";
    echo "</div>";
  }


  //Sequence
  /*
  echo "<ul class='sortable list'>";
  $qry2 = "SELECT `text`, `choice_id`, `isCorrect` FROM `question_choices` WHERE `question_id` = :qstn_id ORDER BY RAND()";
  //$qry2 = "SELECT COUNT(*) FROM `question_choices` WHERE `question_id` = :qstn_id;"
  $stmt = $conn->prepare($qry2);
  //print $stmt;
  $stmt->execute(array(':qstn_id' => $qstn_no));
  //print $stmt;
  //foreach ($qry2 as $row) {
  foreach ($stmt as $row) {
    $i = 0;
    echo "<li id=" . $row[1] . ">" . $row[0] . "</li>";
  }
  echo "</ul>";
*/

  //Submit
  echo "<a href='#' class='btn btn-primary' onclick='validate()'>Submit</a> ";
  //echo "<button class='btn btn-primary' id='myModal'>Submit</button> ";
  echo "<input type='submit' value='Skip this question' class='btn btn-default' data-toggle='modal' data-target='.bs-example-modal-lg' />";

}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

</form>

<?php
   echo "firstrun: " . $_SESSION['$firstrun'];
?>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
<?php
$qry2 = "SELECT `choice_id` FROM `question_choices` WHERE `question_id` = :qstn_id ORDER BY isCorrect";
$stmt = $conn->prepare($qry2);
$stmt->execute(array(':qstn_id' => $qstn_no));
//print $stmt;
//foreach ($qry2 as $row) {
$i = '';
foreach ($stmt as $row) {
  $i = $i . $row[0] . ',';
}
$i = rtrim($i, ',');
echo "Right answer: " . $i . " (as retrieved from the db)";
?>
<div id="test"></div>
<script>


//$('.sortable.list li').map(function(i,n) {
  //  return $(n).attr('id');
//}).get().join()
</script>
        <h3 class="modal-title">uhh</h3>
      </div>
      <div class="modal-body">
        <p>asdasdasdasdasd</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Proeed to next question</button>
      </div>
    </div>

  </div>
</div>

<!--
<script type="text/javascript">
$(document).ready(function(){
    $("#myBtn").click(function(){
      var x = $('input[name=option]:checked', '#quiz').val();
        $("#myModal").modal();
        //alert(x);
    });
});
</script>
-->
<script>
  function validate(){
    //event.preventDefault();
    //$_SESSION['qstn_count'] += 1;
    $("#myModal").modal();
  }
  $(function() {
    $('.sortable').sortable();
    $('.handles').sortable({
      handle: 'span'
    });
    $('.connected').sortable({
      connectWith: '.connected'
    });
    $('.exclude').sortable({
      items: ':not(.disabled)'
    });
    $('.sortable').sortable().bind('sortupdate', function() {
      //Triggered when the user stopped sorting and the DOM position has changed.
      var div = document.getElementById('test');
      var x = $('.sortable.list li').map(function(i,n){
         return $(n).attr('id'); // This is your rel value
      }).get().join();
      div.innerHTML = "Your answer: " + x;
    });
    $('[data-toggle="tooltip"]').tooltip()

  });
  var count=20;
  var counter=setInterval(timer, 1000); //1000 will  run it every 1 second

  function timer()
  {
    count=count-1;
    if (count <= 0)
    {
       clearInterval(counter);
       //counter ended, do something here
       return;
    }

    //Do code for showing the number of seconds here
  }
</script>

<?php include 'template\footer.php'; ?>
