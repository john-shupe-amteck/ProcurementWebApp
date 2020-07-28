
<?php

$jobid = $_GET['job'];

$select = 'SELECT
    equipmentID description,
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
$where = 'WHERE jobID = "'.$jobid.'" AND complete = 0 ';
$order_by = 'ORDER BY `PO-number`';

$query = $select.$from.$where.$order_by;

$result = mysqli_query($con, $query);

?>

<div id="main-data">
  <table class="non-clickable">
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
        $startdate   = date_create($row['start-date' ]);
        $finishdate  = date_create($row['finish date']);
        $duration    = $row['duration'        ];
        $cyclelength = $row['cycle-length'    ];
        $totalcost   = $row['total-cost'      ];
        $cyclerent   = $row['cycle rent'      ];
        echo"
        <tr id='".$ponum."' class='rental-line'>
          <td class='monospace' style='padding-left: 10px'>".$description.                                   "</td>
          <td class='monospace' style='text-align: center'>".$eqnum      .                                   "</td>
          <td class='monospace' style='text-align: center'>".$ponum      .                                   "</td>
          <td class='monospace' style='text-align: center'>".date_format($startdate,  "M d, Y") .            "</td>
          <td class='finish-date monospace' style='text-align: center'>".date_format($finishdate, "M d, Y") ."</td>
          <td class='monospace' style='text-align: center'>".$duration   .                                   "</td>
          <td class='monospace' style='text-align: center'>".$cyclelength.                                   "</td>
          <td class='monospace' style='text-align: center'>$".number_format($totalcost).                     "</td>
          <td class='monospace' style='text-align: center'>$".number_format($cyclerent).                     "</td>
        </tr>
        ";
      }
      ?>
    </tbody>
  </table>
</div>

