<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>  
<?php 
/* class is basically just a blueprint
a spec of what a book is.
we're basically defining a new data type */
class Book {
var $title;
var $author;
var $pages;
}

/* for here we're creating objects.
it's an instance of a class. 
The arrow operator -> (dash + greater-than,
 no space between them) is what PHP uses to access 
 an object's properties or methods*/

$book1 = new Book;
$book1->title = "How to stop worrying and start living";
$book1->author = "Dale Carnegie";
$book1->pages = 400;

$book2 = new Book;
$book2->title = "How to win friends and influence people";
$book2->author = "Dale Carnegie";
$book2->pages = 700;

echo $book2->pages;
 ?>
</body>
</html>