
<?php

$jobid = $_GET['job'];

$select = 'SELECT
    equipment.description AS description,
    `equipment-number`,
    `PO-number`,
    `start-date`,
    DATE_ADD( 
      `start-date`,
      INTERVAL (duration * `cycle-length`) DAY
    ) AS "finish date",
    `duration`,
    `cycle-length`,
    `total-cost`,
    `total-cost` / duration AS "cycle rent" ';
$from = 'FROM `eq_tracker_details` A ';
$left_join = 'LEFT JOIN equipment ON A.equipmentID = equipment.equipmentID ';
$where = 'WHERE jobID = "'.$jobid.'"';
$order_by = 'ORDER BY `PO-number`';

$query = $select.$from.$left_join.$where.$order_by;

$result = mysqli_query($con, $query);

?>

<div id="main-data">
  <table>
    <thead>
      <tr>
        <th>Equipment Description</th>
        <th>Equipment #</th>
        <th>PO Number</th>
        <th>Start Date</th>
        <th>Finish Date</th>
        <th>Duration</th>
        <th>Cycle Length</th>
        <th>Total Cost</th>
        <th>Cycle Rent</th>
      </tr>
    </thead>
    <tbody>
      <?php
      while ($row = mysqli_fetch_array($result)) {
        $description = $row['description'     ];
        $eqnum       = $row['equipment-number'];
        $ponum       = $row['PO-number'       ];
        $startdate   = $row['start-date'      ];
        $finishdate  = $row['finish date'     ];
        $duration    = $row['duration'        ];
        $cyclelength = $row['cycle-length'    ];
        $totalcost   = $row['total-cost'      ];
        $cyclerent   = $row['cycle rent'      ];
        echo"
        <tr onclick=rentalEdit(e)>
          <td style='padding-left: 10px'>".$description."</td>
          <td style='text-align: center'>".$eqnum      ."</td>
          <td style='text-align: center'>".$ponum      ."</td>
          <td style='text-align: center'>".$startdate  ."</td>
          <td style='text-align: center'>".$finishdate ."</td>
          <td style='text-align: center'>".$duration   ."</td>
          <td style='text-align: center'>".$cyclelength."</td>
          <td style='text-align: center'>$".number_format($totalcost)."</td>
          <td style='text-align: center'>$".number_format($cyclerent)."</td>
        </tr>
        ";
      }
      ?>
    </tbody>
  </table>
</div>
