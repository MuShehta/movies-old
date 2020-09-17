<?php

session_start();
$page_title = "Index";
include "include/ini.php";
login();
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col col-md-3">
            <a href="admin.php">
                <div class="item" style="background: #3498db;">
                    <h3>Admins</h3>
                    <span> <?php echo count_db("admin"); ?></span>
                </div>
            </a>
        </div>
        <div class="col col-md-3">
            <a href="items.php">
                <div class="item" style="background: #c0392b;">
                    <h3>Items</h3>
                    <span><?php echo count_db("movie"); ?></span>
                </div>
            </a>
        </div>
    </div>

</div>


<?php

include "include/footer.php";
?>