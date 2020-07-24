
<tr class="sub-table">
  <td colspan="4">
    <table class="po-table non-clickable">
    <?php
    while ($rows = mysqli_fetch_array($result2)) {
      $jobID = $rows['jobID'];
      $po = $rows['PO-number'];
      $qty = $rows['quantity'];
      $cost = $rows['unit-cost'];
      $unit = $rows['unit'];
      echo '
        <tr>
          <td class="description     po monospace">'.$jobID.'/'.$po                    .'</td>
          <td class="quantity        po monospace">'.number_format($qty)               .'</td>
          <td class="times-purchased po monospace">                                      </td>
          <td class="purchase-price  po monospace">$'.number_format($cost, 2).'/'.$unit.'</td>
        </tr>'
      ;
    }
    ?>
    </table>
  </td> 
</tr>