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
        // Escape user-provided task data before displaying it in HTML
// to prevent XSS attacks.
        echo "{htmlspecialchars($this->title)} {htmlspecialchars($this->description)} {htmlspecialchars($this->dueDate)}<br>";
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

if (isset($_POST["edit"])) {
    // Grab the hidden index value silently carried by whichever Edit button was clicked
   // What this does: when someone clicks the Edit button on, say, task #1, 
   // this catches that click and stores 1 into a new session variable called editingIndex. 
   // Think of this as PHP leaving itself a sticky note that says: "we are currently in the 
   // middle of editing task #1 — remember this across the next page load
    $_SESSION["editingIndex"] = $_POST["editIndex"] ;
}


//The first three lines just start with empty defaults 
// if we're not currently editing anything, the form should just show blank boxes, like normal.
$editTitle = "";
$editDescription = "";
$editDueDate = "";


//if (isset($_SESSION["editingIndex"])) — checks: "are we currently in the 
// middle of editing something?" (this is true only right after someone clicked an Edit button)
if(isset($_SESSION["editingIndex"])){

    //this fetches the actual task object sitting at that remembered position
 $indexBeingEdited   = $_SESSION["tasks"][$_SESSION["editingIndex"]];

 //The last three lines then copy that task's current values into our display 
 // variables, replacing the empty defaults.
  $editTitle = $indexBeingEdited->title;
  $editDescription = $indexBeingEdited->description;
  $editDueDate = $indexBeingEdited->dueDate;
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
     <!-- Escape edited task values before inserting them into HTML attributes
     to prevent user input from being interpreted as HTML/JavaScript. -->
    <form action="index.php" method="post">
    Title: <input type="text" name="title" value="<?php echo htmlspecialchars($editTitle); ?>"><br>
    Description: <input type="text" name="description" value="<?php echo htmlspecialchars($editDescription); ?>"><br>
    Date: <input type="date" name="dueDate" value="<?php echo htmlspecialchars($editDueDate); ?>"><br>
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
     <form action="index.php" method="post">
        <input type="hidden" name="editIndex" value="<?php echo $index; ?>">
        <input type="submit" name="edit" value="Edit">
    </form>
    <?php
    // Re-enter PHP just to close the loop opened above
}

?>
</body>
</html>