<?php
  $po = $_GET['po'];

  $select = "SELECT * FROM `eq_tracker_details`";
  $where = "WHERE `PO-number`='${po}'";

  $query = $select.$where;

  $result = mysqli_query($con, $query);  
  ?>
<div class="rental-details-popup active">
  <div class="popup-content active">
    <form method="POST" action="/job-page.php?job=C19KY01007">
      <?php
        while ($row = mysqli_fetch_array($result)) {
          $id = $row[0];
          $eq_num = $row[1];
          $start = $row[3];
          $duration = $row[4];
          $cycle = $row[5];
          $total = $row[6];
          $descrip = $row[8];
          $notes = $row[9];
          echo'

          <div id="po-input-div">
            <label for="po-input">PO Number:</label>
            <input type="text" name="po" id="po-input" placeholder="'.$po.'"><br>
          </div>

          <div id="id-input-div">
            <label id="id-label" for="id-input">Record ID:</label>
            <input type="text" name="id" id="id-input" placeholder="'.$id.'"><br>
          </div>
      
            <label for="eq-num-input">Equipment Number:</label>
            <input type="text" name="eq_num" id="eq-num-input" placeholder="'.$eq_num.'"><br>
      
            <label for="start-date-input">Start Date:</label>
            <input type="date" name="start" id="start-date-input" placeholder="'.$start.'"><br>
      
            <label for="duration-input">Duration:</label>
            <input type="text" name="duration" id="duration-input" placeholder="'.$duration.'"><br>
      
            <label for="cycle-input">Cycle Length:</label>
            <input type="text" name="cycle" id="cycle-input" placeholder="'.$cycle.'"><br>
      
            <label for="total-input">Total Cost:</label>
            <input type="text" name="total" id="total-input" placeholder="'.$total.'"><br>
      
            <label for="descrip-input">Desctiption:</label>
            <input type="text" name="descrip" id="descrip-input" placeholder="'.$descrip.'"> <br>     
            
            <textarea cols="40" rows="5">'.$notes.'</textarea> <br>  
          ';
        }        
      ?>
      <input class="close" type="button" value="Submit">
    </form>
  </div>
</div>