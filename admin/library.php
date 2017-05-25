<?php
require __DIR__ . '/header.php';
$db = new Database();
if (!empty($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'add') {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Add Book to Library
                    <div class="pull-right">
                        <a href="library.php" class="btn btn-sm btn-success">All Books</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (!empty($_POST['upload'])) {
                    $title = $_POST['title'];
                    $author = $_POST['author'];
                    $category = $_POST['category'];
                    $body = $_POST['body'];
                    $file = $_FILES['book'];

                    if (empty($file['name'])) {
                        msgBox('error', 'Please choose File');
                    } elseif (empty($title)) {
                        msgBox("error", 'Book Title Required');
                    } elseif (empty($author)) {
                        msgBox('error', 'Book Author Required');
                    } elseif (empty($category)) {
                        msgBox('error', 'book category required');
                    } else {
                        $uploaded = move_uploaded_file($file['tmp_name'],
                            __DIR__ . '/../content/books/' . $file['name']);
                        if (!$uploaded) {
                            msgBox('error', 'Unable to upload file, Please try again later');
                        } else {
                            $category = strtolower(trim($category));
                            $stmt = 'SELECT id FROM book_categories WHERE category_name = ?';
                            $res = $db->query($stmt, [$category])->fetchObject();
                            $category_id = 0;
                            if (!empty($res)) {
                                $category_id = $res->id;
                            } else {
                                $stmt = 'INSERT INTO book_categories VALUES(null,?)';
                                $db->runQuery($stmt, [$category]);
                                $category_id = $db->lastInsertId();
                            }

                            $stmt = 'INSERT INTO books VALUES(null,?,?,?,?,?,?)';
                            $db->runQuery($stmt, [
                                $title,
                                $author,
                                $body,
                                $file['name'],
                                $category_id,
                                date('Y-m-d H:i:s'),
                            ]);
                            msgBox('ok', 'Book Uploaded Successfully');

                        }
                    }
                }
                ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Book Title</label>
                        <input type="text" name="title" id="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="author">Book Author</label>
                        <input type="text" name="author" id="author" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="category">Book Category</label>
                        <input type="text" name="category" id="category" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="book">File</label>
                        <input type="file" name="book" id="book" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="body">Book Description</label>
                        <textarea name="body" id="body" rows="4" class="form-control"></textarea>
                    </div>
                    <input type="submit" name="upload" id="upload" value="Upload" class="btn btn-primary">
                </form>
            </div>
        </div>
        <?php
    }elseif($action == 'delete'){
        $id = (int) $_GET['id'];
        $stmt = 'SELECT * FROM books WHERE id = ?';
        $res = $db->query($stmt,[$id])->fetchObject();
        unlink(__DIR__.'/../content/books/'.$res->file_name);
        $db->runQuery('DELETE FROM books WHERE id = ?',[$id]);
        msgBox('ok', 'Book Deleted');
        header('refresh:1;url=library.php');
    }
} else {
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Manage Library Books
                <div class="pull-right">
                    <a href="library.php?action=add" class="btn btn-sm btn-success">Add Book</a>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="panel-body">
            <table class="table table-bordered mce-item-table-hover table-striped">
                <tr>
                    <th>Book Name</th>
                    <th>Book Author</th>
                    <th>Book Category</th>
                    <th>Uploaded On</th>
                    <th>Action</th>
                </tr>
                <?php
                $stmt = '
                  SELECT books.*,book_categories.category_name FROM books
                  LEFT JOIN book_categories on books.category_id = book_categories.id 
                  ORDER BY books.uploaded_at DESC
                ';
                $res = $db->query($stmt);
                while ($row = $res->fetchObject()) {
                    ?>
                    <tr>
                        <td><?php echo $row->title ?></td>
                        <td><?php echo $row->author ?></td>
                        <td><?php echo $row->category_name ?></td>
                        <td><?php echo date('F j, Y, g:i a',strtotime($row->uploaded_at)) ?></td>
                        <td>
                            <a href="library.php?action=delete&amp;id=<?php echo $row->id ?>" class="btn btn-danger btn-sm">
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
    <?php
}
require __DIR__ . '/footer.php' ?>
