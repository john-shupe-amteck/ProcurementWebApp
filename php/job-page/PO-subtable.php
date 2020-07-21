
<tr class="sub-table">
  <td colspan="2">
    <table class="po-table non-clickable">
    <?php
    while ($rows = mysqli_fetch_array($result2)) {
      echo '
        <tr>
          <td class="po monospace">'.$rows['jobID'].'/'.$rows['PO-number'].'</td>
          <td class="po monospace" style="text-align:right; padding-right:23px">'.number_format($rows['quantity']).'</td>
        </tr>'
      ;
    }
    ?>
    </table>
  </td> 
</tr>