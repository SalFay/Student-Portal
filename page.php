<?php require 'header.php'; ?>
<?php
$db = new Database();
$title = "Error 404 - Page not found";
$body = "The page, you are looking for, does not exists on this server";
try {
    if (isset($_GET["id"])) {
        $id = intval($_GET["id"]);
        $db->query("SELECT * FROM pages WHERE page_id = ?", $id);
        if ($db->rowCount() > 0) {
            $r = $db->fetchObject();
            $title =  $r->page_title;
            $body = $r->page_body;
        }
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
<!-- Page Title -->
<div class="section section-breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    <?php echo $title ?>
                </h1>
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="container">
        <div class="row">
            <!-- Blog Post -->
            <div class="col-sm-8">
                <div class="blog-post blog-single-post">
                    <div class="single-post-title">
                        <h3><?php echo $title ?></h3>
                    </div>
                    <div class="single-post-content">
                        <?php echo $body ?>
                    </div>
                </div>
            </div>
            <!-- End Blog Post -->
            <?php require 'sidebar.php' ?>
        </div>
    </div>
</div>
<?php
require 'footer.php';
