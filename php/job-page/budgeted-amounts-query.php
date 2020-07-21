<?php
  if (isset($_GET['code'])) {
    $code = $_GET['code'];
  }
  if (isset($_GET['description'])) {
    $description = $_GET['description'];
  }

  $select = "SELECT
    A.description                                                AS description,
    sum(quantity)                                                AS qty,
    avg(`unit-cost`)                                             AS 'unit-cost',
    `cost-unitID`                                                AS 'cost-unitID',
    `cost-units`.`conversion`                                    AS conversion,
    sum(quantity) * avg(`unit-cost`) * `cost-units`.`conversion` AS total
    ";
  $from = "FROM `budget-details` A ";
  $left_join = "LEFT JOIN `cost-units` on `cost-units`.ID = A.`cost-unitID`";
  $where = "
    WHERE
      A.budgetID IN (
        SELECT 
          B.ID 
        FROM 
          budgets B
        WHERE 
          B.jobID='".$job."'
    )"
  ;  
  $group_by = "GROUP BY itemID ";
  $order_by = "ORDER BY itemID ASC ";
  $limit = "LIMIT 50";

  if (isset($code) && $code != "") {
    $where = $where." AND A.`sort-codeID` = ".$code." ";
    $limit = "";
  }

  if (isset($description) && $description != "") {
    $where = $where." AND description LIKE '%".$description."%' ";
    $limit = "";
  }

  $query = $select.$from.$left_join.$where.$group_by.$order_by.$limit;
  $result = mysqli_query($con, $query);
  $query = $select.$from.$left_join.$where;
  $result2 = mysqli_query($con, $query);
?>

<div id="main-data">
  <table>
    <thead>
      <tr>
        <th>Description</th>
        <th class='budgeted-amount-quantity'>Quantity   </th>
        <th class='budgeted-amount-cost'    >Cost       </th>
        <th class='budgeted-amount-total'   >Total      </th>
      </tr>
    </thead>
    <tbody class="non-clickable">
  
    <?php
      while ($row = mysqli_fetch_array($result)) {
        echo '
          <tr>
          <td class="monospace">'.                          $row["description"]             .'</td>
          <td class="budgeted-amount-quantity monospace" style="text-align:right">'. number_format($row["qty"])      .'</td>
          <td class="budgeted-amount-cost monospace" style="text-align:right">$'. number_format($row["unit-cost"], 2).'/'.$row["cost-unitID"].'</td>
          <td class="budgeted-amount-total monospace" style="text-align:right">'. number_format($row["total"], 2).'</td>
          </tr>'
        ;
      }
    ?>
    </tbody>
  </table>
</div>
<div id="totals-row">
  <table>
    <tbody>
      <?php
        while ($row = mysqli_fetch_array($result2)) {
          echo '
            <tr>
            <td class="monospace"></td>
            <td id="total-quantity" class="monospace" style="text-align:right">'. number_format($row["qty"])      .'</td>
            <td id="total-cost" class="monospace" style="text-align:right"></td>
            <td id="total-total" class="monospace" style="text-align:right">'. number_format($row["total"], 2).'</td>
            </tr>
            '
          ;
        }
      ?>
    </tbody>
  </table>
</div>