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
          <td class="description     monospace">'.$jobID.'/'.$po                    .'</td>
          <td class="quantity        monospace text-right">'.number_format($qty)               .'</td>
          <td class="times-purchased monospace text-right"></td>
          <td class="purchase-price  monospace text-right">$'.number_format($cost, 2).'/'.$unit.'</td>
        </tr>'
      ;
    }
    ?>
    </table>
  </td> 
</tr>