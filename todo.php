<?php
// Start (or resume) the session — must run before any HTML/output,
// so PHP can track this user's data across page loads
session_start();

// The blueprint: describes what every Task object will have and can do
class Tasks {
    var $title;
    var $description;
    var $dueDate;

    // Runs automatically every time we create a new Task object with "new Tasks(...)"
    function __construct($aTitle, $aDescription, $aDate){
        // Store each incoming value onto THIS specific object's own properties
        $this->title = $aTitle;
        $this->description = $aDescription;
        $this->dueDate = $aDate;
    }

    // A method that prints this specific task's own stored data
    function describe(){
        echo "{$this->title} {$this->description} {$this->dueDate}<br>";
    }
}

// Check 1: does our "tasks" list already exist in this session?
// Only true on the very first visit — after that, this block is skipped
if(!isset($_SESSION["tasks"])){
    // First time ever visiting — create an empty list to start collecting tasks into
    $_SESSION["tasks"]= array();
}

// Check 2: did the person fill in all three fields with actual values (not blank)?
if(!empty($_POST["title"]) &&
!empty($_POST["description"]) &&
!empty($_POST["dueDate"])){

    // Double-check: was the "Add task" Submit button specifically clicked?
    if($_POST["submit"]){
        // Build one real Task object using the three submitted values
        $newTask = new Tasks ($_POST["title"], $_POST["description"], $_POST["dueDate"]);
        // Add this new Task object onto the END of the existing list —
        // [] means "add to the list", NOT "replace the whole list"
        $_SESSION["tasks"][] = $newTask;
    }
}

//delete button logic
// Check 3: was a Delete button clicked on one of the existing tasks?
if (isset($_POST["delete"])) {
    // Grab the hidden index value silently carried by whichever Delete button was clicked
    $indexToRemove = $_POST["deleteIndex"];
    // Remove just the one task sitting at that specific position
    unset($_SESSION["tasks"][$indexToRemove]);
    // Renumber the array cleanly (0,1,2...) after removal, so no gaps remain
    // and future deletes still line up correctly with what's displayed
    $_SESSION["tasks"] = array_values($_SESSION["tasks"]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- The form: collects title, description, due date for a NEW task -->
    <form action="index.php" method="post">
        Title: <input type="text" name="title"><br>
         Description: <input type="text" name="description"><br>
          Date: <input type="date" name="dueDate"><br>
        <input type="submit" name="submit">
</form>


<?php
//For each item, give me BOTH its position number 
//(call it $index) AND the actual item itself (call it $task)
foreach ($_SESSION["tasks"] as $index => $task){
    // Print this task's own details
    $task->describe();
    ?>
    <!-- Exit PHP: a small standalone form with just this task's Delete button.
         The hidden field silently carries THIS task's own index along with it -->
    <form action="index.php" method="post">
        <input type="hidden" name="deleteIndex" value="<?php echo $index; ?>">
        <input type="submit" name="delete" value="Delete">
    </form>
    <?php
    // Re-enter PHP just to close the loop opened above
}

?>
</body>
</html>