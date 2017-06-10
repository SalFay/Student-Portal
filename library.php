<?php
require __DIR__ . '/header.php';
$db = new Database();
if (empty($_SESSION['std_id'])) {
    msgBox('error', 'You are not logged-in');
    require __DIR__ . '/footer.php';
    exit();
}
$user_id = $_SESSION['std_id'];
$db->query('SELECT * FROM admission WHERE user_id = ?', $_SESSION['std_id']);
$r = $db->fetchObject();
$department = $r->department_id;
$semester = $r->semester_id;
$courses = implode(',', getCourses($department, $semester));
?>
    <!-- Page Title -->
    <div class="section section-breadcrumbs hidden-print">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>
                        Library
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <table class="table table-hover table-striped table-bordered">
                        <?php
                        $cat_stmt = 'SELECT * FROM book_categories';
                        $resources = $db->query($cat_stmt);
                        while ($cat = $resources->fetchObject()) {
                            ?>
                            <tr>
                                <th class="text-center">
                                    <h3><?php echo $cat->category_name ?></h3>
                                </th>
                            </tr>
                            <?php
                            $books_stmt = 'SELECT * FROM books WHERE category_id = ?';
                            $res = $db->query($books_stmt, [$cat->id]);
                            while ($book = $res->fetchObject()) {
                                ?>
                                <tr>
                                    <td>
                                        <h4><?php echo $book->title ?>
                                            <small><em>by:</em> <?php echo $book->author ?></small>
                                        </h4>
                                        <p><?php echo $book->body ?></p>
                                        <div class="text-center">
                                            <a href="<?php echo SITE_URL ?>/content/books/<?php echo $book->file_name ?>"
                                                class="btn btn-primary"
                                            >
                                                Download
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </div>
                <?php require __DIR__ . '/sidebar-user.php'; ?>
            </div>
        </div>
    </div>
<?php

require __DIR__ . '/footer.php';
