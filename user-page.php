<!-- PHP  -->
  <?php
    include('php/config.php'); // app wide config settings

    if (isset($_GET['description'])) {
      if ($_GET['description'] == "") [
        $_GET['description'] = "Partial Description"
      ];
    };
    if (isset($_GET['code'])) {
      if ($_GET['code'] == "") [
        $_GET['code'] = "Select Sort Code"
      ];
    }
  ?>
<!-- HTML -->
  <!DOCTYPE html>
  <html lang="en">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <?php
          if ($_SESSION['theme'] == "light") { // light styling
            echo '<link rel="stylesheet" href="css/light-theme/main.css">';
            echo '<link rel="stylesheet" href="css/light-theme/user-page.css">';
          } elseif ($_SESSION['theme'] == "dark") { // dark styling
            echo '<link rel="stylesheet" href="css/dark-theme/main.css">';
            echo '<link rel="stylesheet" href="css/dark-theme/user-page.css">';
          }
        ?>
      <link rel="icon" href="img/amtecklogo.PNG">
      <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

      <title>Amteck Procurement</title>
    </head>


    <body>
    <?php include('php/header.php'); ?> <!-- header bar at top of page with nav settings -->

      <main class="main-area">
        <div id="options-panel">
          <div class="content-container" id="jobs">
            <?php
              $con = mysqli_connect("localhost", "root", "", "procurement-web-app");

              $query = "SELECT A.* FROM jobs A WHERE A.ID in (SELECT B.jobID FROM permissions B WHERE B.userID=". $_SESSION['user_id'] .") ORDER BY A.ID";
              $result = mysqli_query($con, $query);
            ?>
            <table>
              <thead>
                <tr>
                  <th>Job ID</th>
                  <th>Job Name</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  while ($row = mysqli_fetch_array($result))
                  {
                    echo '
                      <tr id="'. $row["ID"] .'">
                        <td id="'. $row["ID"] .'" class="job-button" style="padding-left:10px">'. $row["ID"] . '</td>
                        <td>
                          <form action="user-page.php" method="get">
                            <input type="hidden"    name="job"         value="'.$row["ID"].'">
                            <input type="hidden"    name="job_name"    value="'.$row['name'].'">
                            <input type="hidden"    name="code"        value="Select Sort Code">
                            <input type="hidden"    name="description" value="Partial Description">
                            <input type="submit" id="'.$row["ID"] .'"  value="'. $row["name"] .'">
                          </form>
                        </td>
                      </tr>'
                    ;
                  }
                ?>
              </tbody>
            </table>
          </div>

          <div class="content-container" id="options">
          <form action="user-page.php" method="get">

              <?php
                if (isset($_GET['job'])){
                  echo '
                  <input type="hidden" name="job" value="'.$_GET["job"].'">
                  <input type="hidden" name="job_name" value="'.$_GET["job_name"].'">
                  ';
                }
                if (isset($_GET['code'])) {
                  echo '
                  Code: <input type="text" autofocus="autofocus" onfocus="this.select()" name="code" placeholder="'. $_GET['code']. '"><br>
                  ';
                } else {
                  echo '
                  Code: <input type="text" autofocus="autofocus" onfocus="this.select()" name="code" placeholder="Select Sort Code"><br>
                  ';
                }

                if (isset($_GET['description']) && is_null($_GET['description'])) {
                  echo '
                  Description: <input type="text" onfocus="this.select()" name="description" placeholder="Partial Description"><br>
                  ';
                } elseif (isset($_GET['description']) and !is_null($_GET['description'])) {
                  echo '
                  Description: <input type="text" onfocus="this.select()" name="description" placeholder="'.$_GET['description'].'"><br>
                  ';
                } else {
                  echo '
                  Description: <input type="text" onfocus="this.select()" name="description" placeholder="Partial Description"><br>
                  ';
                }

                ?>
              <input type="submit" value="Filter" id="submit">
            </form>
          </div>
        </div>


        <div id="data-panel">
          <div id="data" class="content-container">
            <div id="table-header">
              <?php
                $job_name = (isset($_GET['job']))?$_GET['job_name']:null;

                echo (isset($job_name))?"<h1 style='text-align:left;'>".$job_name."</h1>":"<h1 style='text-align:left;'>Select A Job</h1>";
              ?>
              <table>
                <thead>
                  <tr>
                    <th></th>
                    <th colspan="5">Budgeted</th>
                    <th colspan="5">Purchased</th>
                    <th colspan="3">Variance</th>
                  </tr>
                  <tr>
                    <th id="name" class='sort-header item-name'>Item</th>
                    <th id="bud-quantity" class='sort-header bud-quantity'>Quantity</th>                
                    <th class="dollars"></th>
                    <th id="bud-cost" class='sort-header bud-cost'>Cost</th>
                    <th class="dollars"></th>
                    <th id="bud-total" class='sort-header bud-total'>Total</th>
                    <th id="po-quantity" class='sort-header po-quantity'>Quantity</th>
                    <th class="dollars"></th>
                    <th id="po-cost" class='sort-header po-cost'>Cost</th>
                    <th class="dollars"></th>
                    <th id="po-total" class='sort-header po-total'>Total</th>
                    <th id="var-quantity" class='sort-header var-quantity'>Quantity</th>
                    <th class="dollars"></th>
                    <th id="var-total" class="sort-header var-total">Total</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div id="table-data">
              <table>
                <tbody>
                  <?php
                    if (isset($_GET['job'])) {include('php/user-page-data-query.php');};
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>


      <?php include('php/footer.php') ?>
      <script>
        elem = document.getElementById("<?php echo $_GET["job"] ?>");
        elem.style.backgroundColor = "gray";
      </script>
      <script>
        $('.sort-header').click(function(e) {
          var sort_value = $(this).attr('id');
          var job = "<?php echo $_GET['job'] ?>";
          var job_name = "<?php echo $_GET['job_name'] ?>";
          var code = "<?php echo $_GET['code'] ?>";
          var description ="<?php echo $_GET['description'] ?>";
          parent.location = "user-page.php?job="+ job +"&job_name="+ job_name +"&code="+ code +"&description="+ description +"&order="+ sort_value;
        })
      </script>
      <script>
        $(document).ready(function () {
          $("#<?php echo $_GET['order'] ?>").html($("#<?php echo $_GET['order'] ?>").html() + "^");
        });
      </script>
    </body>
  </html>