<?php require 'header.php' ?>
<?php
$db = new Database();
if (isset($_GET["do"])) {
    $do = cleanString($_GET["do"]);
    if ($do == "add") {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Add Menu item
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["add"])) {
                    $label = cleanString($_POST["label"]);
                    $link = cleanString($_POST["link"]);
                    $order = cleanString($_POST["order"]);
                    $title = cleanString($_POST["title"]);

                    if (empty($label) || empty($order) || empty($link)) {
                        msgBox("erorr", "all fields are required");
                    } else {
                        $stmt = "INSERT INTO menus VALUES(null,?,?,?,?)";
                        $args = array($label, $link, $title, $order);
                        try {
                            $db->runQuery($stmt, $args);
                            msgBox("ok", "Menu Added");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                ?>
                <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                    <div class="form-group">
                        <label>Label</label>
                        <input type="text" name="label" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Link</label>
                        <input type="text" name="link" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Order</label>
                        <input type="number" name="order" class="form-control" value="1" required="" />
                    </div>
                    <input type="submit" name="add" class="btn btn-primary" value="Add" />
                </form>
            </div>
        </div>
        <?php
    } else if ($do == "edit") {
        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        Add Menu item
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    if (isset($_POST["add"])) {
                        $label = cleanString($_POST["label"]);
                        $link = cleanString($_POST["link"]);
                        $order = cleanString($_POST["order"]);
                        $title = cleanString($_POST["title"]);

                        if (empty($label) || empty($order) || empty($link)) {
                            msgBox("erorr", "all fields are required");
                        } else {
                            $stmt = "UPDATE menus SET menu_label = ?, menu_link = ?,"
                                    . "menu_title = ?, menu_order = ? "
                                    . "WHERE menu_id = ?";
                            $args = array($label, $link, $title, $order,$id);
                            try {
                                $db->runQuery($stmt, $args);
                                msgBox("ok", "Menu Updated");
                            } catch (PDOException $e) {
                                msgBox("error", $e->getMessage());
                            }
                        }
                    }
                    $db->query("SELECT * FROM menus WHERE menu_id = ?",$id);
                    $r = $db->fetchObject();
                    ?>
                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                        <div class="form-group">
                            <label>Label</label>
                            <input type="text" name="label" class="form-control" value="<?php echo $r->menu_label ?>" required="" />
                        </div>
                        <div class="form-group">
                            <label>Link</label>
                            <input type="text" name="link" class="form-control" value="<?php echo $r->menu_link ?>" required="" />
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $r->menu_title ?>" />
                        </div>
                        <div class="form-group">
                            <label>Order</label>
                            <input type="number" name="order" class="form-control" value="<?php echo $r->menu_order ?>" required="" />
                        </div>
                        <input type="submit" name="add" class="btn btn-primary" value="Update" />
                    </form>
                </div>
            </div>
            <?php
        } else {
            header("location:menus.php");
            exit();
        }
    } else if ($do == "delete") {
        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
            try{
                $db->runQuery("DELETE FROM menus WHERE menu_id = ?",$id);
                msgBox("ok", "Menu Deleted");
            }catch(PDOException $e){
                msgBox("error", $e->getMessage());
            }
            header("refresh:1;url=menus.php");
        } else {
            header("location:menus.php");
            exit();
        }
    }
} else {
    ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">
            Manage Menus
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th>Label</th>
                <th>Link</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
            <?php
            $db->query("SELECT * FROM menus ORDER BY menu_order ASC");
            while($r = $db->fetchObject()){
                echo "
                    <tr>
                        <td>$r->menu_label</td>
                        <td>$r->menu_link</td>
                        <td>$r->menu_order</td>
                        <td>
                            <div class='btn-group'>
                                <a href='menus.php?do=edit&amp;id=$r->menu_id' class='btn btn-primary'>Edit</a>
                                <a href='menus.php?do=delete&amp;id=$r->menu_id' class='btn btn-danger'>Delete</a>
                            </div>
                        </td>
                    </tr>
                ";
            }
            ?>
        </table>
    </div>
</div>
<?php
}
?>
<?php require 'footer.php' ?>
