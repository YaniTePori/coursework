<?php include('functions.php') ?>
<!DOCTYPE html>
<html>
<body>
  <h1>Генерирай заявление</h1>

  <form method="post" action="make_application.php">
    <?php echo display_error(); ?>


    <label for="names">Имена*: </label><br>
    <input type="text" required id="names" name="names" ><br>

    <label for="grade">Клас*:</label><br>
    <input type="text" required id="grade" name="grade"><br><br>

    <label for="topic1">Темa 1*:</label><br>
    <input type="text" required id="topic1" name="topic1"><br>
    <label for="mentor1" required >Ръководител 1*:</label><br>
    <?php echo load_mentors("mentor1", false); ?> <br><br>


    <label for="topic2">Темa 2:</label><br>
    <input type="text" id="topic2" name="topic2"><br>
    <label for="mentor2">Ръководител 2:</label><br>
    <?php echo load_mentors("mentor2", true); ?> <br><br>

    <label for="topic3">Темa 3:</label><br>
    <input type="text" id="topic3" name="topic3"><br>
    <label for="mentor3">Ръководител 3:</label><br>
    <?php echo load_mentors("mentor3", true); ?> <br><br>
    <!-- <input type="text" id="mentor3" name="mentor3"><br> -->

    <label for="avr-grade">*Средният ми успех от техническите дисциплини от 9 до 11 клас включително:</label><br>
    <input type="number" required step="0.01" id="avr-grade" name="avr-grade" min="3" max="6"><br>


    <button type="submit" value="Submit" name="make_application">Submit</button>
  </form>


  </body>
  </html>
