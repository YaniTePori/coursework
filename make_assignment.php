<?php include('functions.php') ?>
<!DOCTYPE html>
<html>
<head>
  <script type='text/javascript'>
      function addFields() {
        // Number of inputs to create
        var number = document.getElementById("option").value;
        // Container <div> where dynamic content will be placed
        var container = document.getElementById("container");
        // Clear previous contents of the container
        while (container.hasChildNodes()) {
          container.removeChild(container.lastChild);
        }
        for (i=0;i<number;i++){
          // Append a node with a random text
          container.appendChild(document.createTextNode("Изискване " + (i+1)));
          // Create an <input> element, set its type and name attributes
          var input = document.createElement("input");
          input.type = "text";
          input.name = "requirement" + i;
          container.appendChild(input);
          // Append a line break
          container.appendChild(document.createElement("br"));
        }
      }
    </script>
</head>

<body>
  <h1>Генерирай задание</h1>

  <form method="post" action="make_assignment.php">
    <?php echo display_error(); ?>

    <label for="names" required >Имена:</label><br>
    <input type="text" id="names" name="names" required ><br>

    <label for="grade">Клас:</label><br>
    <input type="text" id="grade" name="grade" required ><br>

  <!-- //split zaqvlenie i zadanie -->
    <label for="topic">Тема:</label><br>
    <input type="text" id="topic" name="topic" required ><br>


    <p>Колко изисквания имаш?</p>
    <input type="text" id="option" name="count" value="" required >
    <a href="#" id="filldetails" name="requirements" onclick="addFields()">Генерирай полета</a> <br><br>
    <div id="container"> </div>

  <label for="mentor">Ръководител :</label><br>
  <?php echo load_mentors("mentor", false); ?> <br><br>

    <button type="submit" value="Submit" name="make_assignment">Submit</button>
  </form>


  </body>
  </html>
