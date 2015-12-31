      <hr>
      <footer>
      <span>&copy; <a href='index.php'><?php echo APP_NAME ."</a> ";
      echo date('Y'); ?></span>
        <p class="pull-right">
          <?php
          if (DEBUG){
            $time = explode(' ',microtime());
            $finish = $time[1] + $time[0];
            $total = round(($finish - $start), 4);
            echo "Page generated in ".$total." seconds.";
          }
          ?>
        </p>
        <?php
        if(DEBUG) {
        echo "<hr><p><strong>Debug info</strong>";
          echo "<div class='row'><div class='col-md-4'>GET";
          var_dump($_GET);
          echo "</div><div class='col-md-4'>POST";
          var_dump($_POST);
          echo "</div><div class='col-md-4'>SESSION";
          var_dump($_SESSION);
          echo "</div></div>";
        echo "</p>";
        }
        ?>
      </footer>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <?php if (isset($changeURL) && $changeURL) :?>
    <script>
      window.history.pushState("", "Index", "<?php echo APP_URL;?>/index.php");
    </script>
    <?php endif;?>
  </body>
</html>
