<!-- TODO: condense function data into callable with changing query instead of if cases -->
<?php
function get_data_table($jobid, $connection1, $code, $description) {

  

  // Query where code = TRUE but description = FALSE
  if (is_numeric($code) && ($description=="Partial Description" || $description=="")) {

    // Update budgeted view
    $query = "CREATE or REPLACE VIEW budgeted AS
      SELECT
        `procurement-web-app`.`budget-details`.`itemID` AS `itemID`,
        max(`procurement-web-app`.`budget-details`.`description`) AS `descrip`,
        sum(`procurement-web-app`.`budget-details`.`quantity`) AS `quantity`,
        max(`procurement-web-app`.`budget-details`.`cost-unitID`) AS `unit`,
        avg(`procurement-web-app`.`budget-details`.`unit-cost`) AS `cost`
      from `procurement-web-app`.`budget-details` 
      where `procurement-web-app`.`budget-details`.`budgetID` in (
        select `procurement-web-app`.`budgets`.`ID` 
        from `procurement-web-app`.`budgets` 
        where `procurement-web-app`.`budgets`.`jobID` = '".$jobid."' and
          `budget-details`.`sort-codeID`=".$code."
      ) 
      group by `procurement-web-app`.`budget-details`.`itemID` 
      order by sum(`procurement-web-app`.`budget-details`.`quantity`) desc";
    mysqli_query($connection1, $query);

    // Update purchased view
    $query = "CREATE or REPLACE VIEW purchased AS
      select `procurement-web-app`.`purchase-details`.`itemID` AS `itemID`,
        sum(`procurement-web-app`.`purchase-details`.`quantity`) AS `quantity`,
        max(`procurement-web-app`.`purchase-details`.`cost-unitID`) AS `unit`,
        avg(`procurement-web-app`.`purchase-details`.`unit-cost`) AS `cost` 
      from `procurement-web-app`.`purchase-details` 
      where `procurement-web-app`.`purchase-details`.`jobID` = '".$jobid."' and
        `purchase-details`.`sort-codeID` = ".$code."
      group by `procurement-web-app`.`purchase-details`.`itemID` 
      order by sum(`procurement-web-app`.`purchase-details`.`quantity`) desc";
    mysqli_query($connection1, $query);

    // Pull procurementreport view
    $query = "SELECT * FROM procurementreport order by name";



  // Query where code = FALSE but description = TRUE 
  } elseif ($code=="Select Sort Code" && (is_string($description) && $description != "Partial Description")) {

    // Update budgeted view
    $query = "CREATE or REPLACE VIEW budgeted AS
      SELECT
        `procurement-web-app`.`budget-details`.`itemID` AS `itemID`,
        max(`procurement-web-app`.`budget-details`.`description`) AS `descrip`,
        sum(`procurement-web-app`.`budget-details`.`quantity`) AS `quantity`,
        max(`procurement-web-app`.`budget-details`.`cost-unitID`) AS `unit`,
        avg(`procurement-web-app`.`budget-details`.`unit-cost`) AS `cost`
      from `procurement-web-app`.`budget-details` 
      where `procurement-web-app`.`budget-details`.`budgetID` in (
        select `procurement-web-app`.`budgets`.`ID` 
        from `procurement-web-app`.`budgets` 
        where `procurement-web-app`.`budgets`.`jobID` = '".$jobid."'
      ) 
      group by `procurement-web-app`.`budget-details`.`itemID` 
      order by sum(`procurement-web-app`.`budget-details`.`quantity`) desc";
    mysqli_query($connection1, $query);

    // Update purchased view
    $query = "CREATE or REPLACE VIEW purchased AS
      select `procurement-web-app`.`purchase-details`.`itemID` AS `itemID`,
        sum(`procurement-web-app`.`purchase-details`.`quantity`) AS `quantity`,
        max(`procurement-web-app`.`purchase-details`.`cost-unitID`) AS `unit`,
        avg(`procurement-web-app`.`purchase-details`.`unit-cost`) AS `cost` 
      from `procurement-web-app`.`purchase-details` 
      where `procurement-web-app`.`purchase-details`.`jobID` = '".$jobid."'
      group by `procurement-web-app`.`purchase-details`.`itemID` 
      order by sum(`procurement-web-app`.`purchase-details`.`quantity`) desc";
    mysqli_query($connection1, $query);

    // Pull procurementreport view
    $query = "SELECT * FROM procurementreport WHERE name like '%".$description."%' order by name";


  // Query where code = TRUE and description = TRUE
  } elseif (is_numeric($code) && is_string($description)) {
    // Update budgeted view
    $query = "CREATE or REPLACE VIEW budgeted AS
      SELECT
        `procurement-web-app`.`budget-details`.`itemID` AS `itemID`,
        max(`procurement-web-app`.`budget-details`.`description`) AS `descrip`,
        sum(`procurement-web-app`.`budget-details`.`quantity`) AS `quantity`,
        max(`procurement-web-app`.`budget-details`.`cost-unitID`) AS `unit`,
        avg(`procurement-web-app`.`budget-details`.`unit-cost`) AS `cost`
      from `procurement-web-app`.`budget-details` 
      where `procurement-web-app`.`budget-details`.`budgetID` in (
        select `procurement-web-app`.`budgets`.`ID` 
        from `procurement-web-app`.`budgets` 
        where `procurement-web-app`.`budgets`.`jobID` = '".$jobid."' and
          `budget-details`.`sort-codeID`=".$code."
      ) 
      group by `procurement-web-app`.`budget-details`.`itemID` 
      order by sum(`procurement-web-app`.`budget-details`.`quantity`) desc";
    mysqli_query($connection1, $query);

    // Update purchased view
    $query = "CREATE or REPLACE VIEW purchased AS
      select `procurement-web-app`.`purchase-details`.`itemID` AS `itemID`,
        sum(`procurement-web-app`.`purchase-details`.`quantity`) AS `quantity`,
        max(`procurement-web-app`.`purchase-details`.`cost-unitID`) AS `unit`,
        avg(`procurement-web-app`.`purchase-details`.`unit-cost`) AS `cost` 
      from `procurement-web-app`.`purchase-details` 
      where `procurement-web-app`.`purchase-details`.`jobID` = '".$jobid."' and
        `purchase-details`.`sort-codeID` = ".$code."
      group by `procurement-web-app`.`purchase-details`.`itemID` 
      order by sum(`procurement-web-app`.`purchase-details`.`quantity`) desc";
    mysqli_query($connection1, $query);

    // Pull procurementreport view
    $query = "SELECT * FROM procurementreport WHERE name like '%".$description."%' order by name";


  // Query where code = FALSE and description = FALSE
  } else {
    // Update budgeted view
    $query = "CREATE or REPLACE VIEW budgeted AS
      SELECT
        `procurement-web-app`.`budget-details`.`itemID` AS `itemID`,
        max(`procurement-web-app`.`budget-details`.`description`) AS `descrip`,
        sum(`procurement-web-app`.`budget-details`.`quantity`) AS `quantity`,
        max(`procurement-web-app`.`budget-details`.`cost-unitID`) AS `unit`,
        avg(`procurement-web-app`.`budget-details`.`unit-cost`) AS `cost`
      from `procurement-web-app`.`budget-details` 
      where `procurement-web-app`.`budget-details`.`budgetID` in (
        select `procurement-web-app`.`budgets`.`ID` 
        from `procurement-web-app`.`budgets` 
        where `procurement-web-app`.`budgets`.`jobID` = '".$jobid."'
      ) 
      group by `procurement-web-app`.`budget-details`.`itemID` 
      order by sum(`procurement-web-app`.`budget-details`.`quantity`) desc";
    mysqli_query($connection1, $query);

    // Update purchased view
    $query = "CREATE or REPLACE VIEW purchased AS
      select `procurement-web-app`.`purchase-details`.`itemID` AS `itemID`,
        sum(`procurement-web-app`.`purchase-details`.`quantity`) AS `quantity`,
        max(`procurement-web-app`.`purchase-details`.`cost-unitID`) AS `unit`,
        avg(`procurement-web-app`.`purchase-details`.`unit-cost`) AS `cost` 
      from `procurement-web-app`.`purchase-details` 
      where `procurement-web-app`.`purchase-details`.`jobID` = '".$jobid."'
      group by `procurement-web-app`.`purchase-details`.`itemID` 
      order by sum(`procurement-web-app`.`purchase-details`.`quantity`) desc";
    mysqli_query($connection1, $query);

    // Pull procurementreport view
    $query = "SELECT * FROM procurementreport LIMIT 50";
  }


  $result = mysqli_query($connection1, $query);
  return $result;
}