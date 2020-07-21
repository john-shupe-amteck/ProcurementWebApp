<div id="main-data">
  <?php
    // Changing the purchased view to show appropriate data for job
    // Setting the SELECT statement
    $select = "CREATE or REPLACE VIEW  purchased as
      SELECT `procurement-web-app`.`purchase-details`.`itemID`         AS `itemID`,
        sum(`procurement-web-app`.`purchase-details`.`quantity`)       AS `quantity`,
        max(`procurement-web-app`.`purchase-details`.`cost-unitID`)    AS `unit`,
        avg(`procurement-web-app`.`purchase-details`.`unit-cost`)      AS `cost`,
        count(`procurement-web-app`.`purchase-details`.`itemID`)       AS `times_purchased`";

    // Setting the FROM statement
    $from = "
      from
        `procurement-web-app`.`purchase-details`";

    // Setting the WHERE statement, including filter if it has been set
    if (isset($_GET['code']) && $_GET['code'] != "") {
      $where = "
      where
        `procurement-web-app`.`purchase-details`.`jobID` = '".$_GET['job']."' and
        `procurement-web-app`.`purchase-details`.`sort-codeID` = '".$_GET['code']."'";
    } else {
      $where = "
      where
        `procurement-web-app`.`purchase-details`.`jobID` = '".$_GET['job']."'";
    }

    // Setting the GROUP BY statement
    $group_by = "
      group by
        `procurement-web-app`.`purchase-details`.`itemID`";

    // Setting the ORDER BY statement
    $order_by = "
      order by itemID "
    ;

    // Combining individual parts into the full query
    $query = $select.$from.$where.$group_by.$order_by;

    // Run the setup query
    mysqli_query($con, $query);

    // setup for the Query that Actually gets run

    // Setting the SELECT statement
    $select = "SELECT
        items.description as description,
        purchased.quantity as quantity,
        purchased.times_purchased as times_purchased,
        purchased.itemID as itemID"
    ;

    $from = "
      FROM
        `purchased`
      left join items on purchased.itemID = items.ID"
    ;

    if (isset($_GET['description']) && $_GET['description'] != "") {
      $where = "
      WHERE
        description like '%".$_GET['description']."%' and
        not description = ''"
      ;
    } else {
      $where = "
        WHERE not
          description = ''"
      ;
    }

    if (isset($_GET['times']) && $_GET['times'] != "") {
      $where = $where." and times_purchased > ".$_GET['times'];
    }

    $query = $select.$from.$where;
    $result = mysqli_query($con, $query);
    
  ?>
  <table>
    <thead>
      <tr>
        <th>                        Description     </th>
        <th class='quantity'>       Quantity        </th>
        <th class='times-purchased'>Times Purchased </th>
      </tr>
    </thead>
    <tbody class="non-clickable">
      <?php
        // Iterates through results and populates to table
        while ($row = mysqli_fetch_array($result)) {

          $id = $row['itemID'];

          echo '
            <tr class="main-info">
              <td class="                monospace">'.$row["description"].'</td>
              <td class="quantity        monospace" style="text-align:right">'. number_format($row["quantity"])       .'</td>
              <td class="times-purchased monospace" style="text-align:right">'. number_format($row["times_purchased"]).'</td>
            </tr>'
          ;
          // query with specific item details
          $query2 = 'SELECT jobID, `PO-number`, quantity, `unit-cost` FROM `purchase-details` WHERE itemID = "'.$id.'" and jobID = "'.$_GET['job'].'" ORDER BY `PO-number`';
          $result2 = mysqli_query($con, $query2);

          // adds in the po table
          include('PO-subtable.php');          
        }
      ?>
    </tbody>
  </table>
</div>