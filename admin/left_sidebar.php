<aside class="sidebar" id="left_sidebar">
    <div class="box">
        <div class="h_title">&#8250; Manage content</div>
        <ul>
            <li><a href="http://foot/" target="_blank" class="icon on_site">To site</a></li>
            <li><a href="index.php" class="icon on_site">Main</a></li>
            <?php if ($profile->check_permission($_SESSION["admin_id"], "p_edit_own_post")): ?>
                <li><a href="all_post.php" class="icon page">Manage news</a></li>
            <?php endif; ?>
            <?php if ($profile->check_permission($_SESSION["admin_id"], "p_create_own_post")): ?>
                <li><a href="add_post.php" class="icon add_page">Add news</a></li>
            <?php endif; ?>
            <li><a  href="categories.php" class="icon category">Manage categories</a></li>
        </ul>
    </div>
    <?php if ($profile->check_permission($_SESSION["admin_id"], "p_edit_user")): ?>
        <div class="box">
            <div class="h_title">&#8250; Manage users</div>
            <ul>
                <li><a href="users.php" class="icon users">All users</a></li>
            </ul>
        </div>
    <?php endif; ?>
    <div class="box">
        <div class="h_title">&#8250; Manage translate</div>
        <ul>
            <li><a href="add_translate.php" class="icon add_page">Add translate</a></li>
            <li><a href="all_translate.php" class="icon report">All translate</a></li>
        </ul>
    </div>
</aside>