<?php require 'header.php' ?>
<?php
$db = new Database();
if (isset($_GET["action"])) {
    $a = cleanString($_GET["action"]);
    if ($a === "add") {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Add Page
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["publish"])) {
                    $title = cleanString($_POST["title"]);
                    $body = $_POST["body"];
                    if (empty($title)) {
                        msgBox("error", "Page title Required");
                    } else {
                        
                        try {
                            $stmt = "INSERT INTO pages(page_title,page_body) VALUES(?,?)";
                            $args = array($title, $body);
                            $db->runQuery($stmt, $args);
                            msgBox("ok", "Page Published");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                ?>
                <form method="post" action="pages.php?action=add">
                    <div class="form-group">
                        <label>Page Title</label>
                        <input type="text" name="title" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Page Body</label>
                        <textarea class="form-control editor" name="body" rows="10"></textarea>
                    </div>
                    <input type="submit" name="publish" value="Publish" class="btn btn-primary" />
                </form>
            </div>
        </div>
        <?php
    } else if ($a === "edit") {
        if (isset($_GET["id"])) {
            $id = intval(cleanString($_GET["id"]));
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        Edit Page
                        <div class="pull-right">
                            <a href="pages.php" class="btn btn-sm btn-success">Back</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    if (isset($_POST["publish"])) {
                        $title = cleanString($_POST["title"]);
                        $body = $_POST["body"];
                        if (empty($title)) {
                            msgBox("error", "Page title Required");
                        } else {
                            $db = new Database();
                            try {
                                $stmt = "UPDATE pages SET page_title = ?,"
                                        . "page_body = ? WHERE page_id = ?";
                                $args = array($title, $body,$id);
                                $db->runQuery($stmt, $args);
                                msgBox("ok", "Page Updated");
                            } catch (PDOException $e) {
                                msgBox("error", $e->getMessage());
                            }
                        }
                    }
                    $db->query("SELECT * FROM pages WHERE page_id = ?",array($id));
                    $r = $db->fetchObject();
                    ?>
                    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"] ?>">
                        <div class="form-group">
                            <label>Page Title</label>
                            <input type="text" name="title" value="<?php echo $r->page_title ?>" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>Page Body</label>
                            <textarea class="form-control editor" name="body" rows="10"><?php echo $r->page_body ?></textarea>
                        </div>
                        <input type="submit" name="publish" value="Update" class="btn btn-primary" />
                    </form>
                </div>
            </div>
            <?php
        }
    } else if ($a === "delete") {
        if(isset($_GET["id"])){
            $id = intval(cleanString($_GET["id"]));
            try{
                $db->runQuery("DELETE FROM pages WHERE page_id = ?",array($id));
                msgBox("ok", "Page Deleted");
                header("refresh:1;url=pages.php");
            }catch(PDOException $e){
                msgBox("erorr", $e->getMessage());
                header("refresh:3;url=pages.php");
            }
        }
    } else {
        echo "Nothing to do";
    }
} else {
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Manage Pages
                <div class="pull-right">
                    <a href="pages.php?action=add" class="btn btn-sm btn-success">Add Page</a>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Title</th>
                    <th>Actions</th>
                </tr>
                <?php
                try {
                    $db->query("SELECT * FROM pages");
                    while ($r = $db->fetchObject()) {
                        echo "
                            <tr>
                                <td>$r->page_title</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href='../page.php?id=$r->page_id' class='btn btn-success'>View</a>
                                        <a href='pages.php?action=edit&amp;id=$r->page_id' class='btn btn-primary'>Edit</a>
                                        <a href='pages.php?action=delete&amp;id=$r->page_id' class='btn btn-danger'>Delete</a>
                                    </div>
                                </td>
                            </tr>
                        ";
                    }
                } catch (PDOException $e) {
                    msgBox("error", $e->getMessage());
                }
                ?>
            </table>
        </div>
    </div>
    <?php
}
?>

<?php require 'footer.php' ?>
