<?php
function get_data_table($jobid, $connection1, $code) {
  if (is_numeric($code)) {
    // Query with sort code filter
    $query = "SELECT
    budgeted.descrip   as name,
    budgeted.quantity  as budqty,
    budgeted.unit      as budunit,
    budgeted.cost      as budcost,
    purchased.quantity as poqty,
    purchased.unit     as pounit,
    purchased.cost     as pocost,
    budgeted.quantity - purchased.quantity as variance
    FROM (
      SELECT
        itemID,
        max(description) as descrip,
        sum(quantity) as quantity,
        max(`cost-unitID`) as unit,
        avg(`unit-cost`) as cost
      FROM 
        `budget-details`
      WHERE
        `budget-details`.`budgetID` IN(
          SELECT
            budgets.ID
          FROM
            budgets
          WHERE
            budgets.jobID = '". $jobid ."'
      ) and `budget-details`.`sort-codeID`=".$code."
      GROUP BY
        itemID
      ) as budgeted, (
      SELECT
        itemID,
        sum(quantity) as quantity,
        max(`cost-unitID`) as unit,
        avg(`unit-cost`) as cost
      FROM
        `purchase-details`
      WHERE
        jobID = '".$jobid."' and `purchase-details`.`sort-codeID`=".$code."
      GROUP BY 
        itemID
      ) as purchased
    WHERE budgeted.itemID = purchased.itemID
    ORDER BY budgeted.quantity DESC
  ";
  } else {
    // Query without sort code filter
    $query = "SELECT
        budgeted.descrip   as name,
        budgeted.quantity  as budqty,
        budgeted.unit      as budunit,
        budgeted.cost      as budcost,
        purchased.quantity as poqty,
        purchased.unit     as pounit,
        purchased.cost     as pocost,
        budgeted.quantity - purchased.quantity as variance
      FROM (
        SELECT
          itemID,
          max(description) as descrip,
          sum(quantity) as quantity,
          max(`cost-unitID`) as unit,
          avg(`unit-cost`) as cost
        FROM 
          `budget-details`
        WHERE
          `budget-details`.`budgetID` IN(
            SELECT
              budgets.ID
            FROM
              budgets
            WHERE
              budgets.jobID = '". $jobid ."'
        )
        GROUP BY
          itemID
        ) as budgeted, (
            SELECT
              itemID,
              sum(quantity) as quantity,
              max(`cost-unitID`) as unit,
              avg(`unit-cost`) as cost
            FROM
              `purchase-details`
            WHERE
              jobID = '".$jobid."'
              GROUP BY 
              itemID
        ) as purchased
      WHERE 
        budgeted.itemID = purchased.itemID
      ORDER BY budgeted.quantity DESC
      LIMIT 50"
    ;
  }
  $result = mysqli_query($connection1, $query);
  return $result;
}