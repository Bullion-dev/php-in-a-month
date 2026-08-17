<?php
class Task{
    var $title;
    var $description;
    var $priority;
    var $completed;

    function __construct($aTitle, $aDescription, $aPriority, $aCompleted){
$this->title = $aTitle;
$this->description = $aDescription;
$this->priority = $aPriority;
$this->completed = $aCompleted;
    }

 function completeTask(){
       $this->completed =  !$this->completed;
 }
}
$task1 = new Task("Learn PHP","Review OOP", "High", false);
$task2 = new Task("Build React project","wowrk on dashboard", "medium", true);
$task3 = new Task("Review Javascript","Practice functions", "Low", false);

 $task1->completeTask();


 var_dump($task1->completed);
?>
