<?php
        // session_start();
        $message = '';
        $class = '';
        if (!empty($_SESSION['success'])) {
            $message = $_SESSION['success'];
            //xóa phần tử trong array có key là success
            unset($_SESSION['success']);
            $class = 'success';
        } else if (!empty($_SESSION['error'])) {
            $message = $_SESSION['error'];
            //xóa phần tử trong array có key là success
            unset($_SESSION['error']);
            $class = 'danger';
        }
        ?>
        <?php if ($message) : ?>
        <div class="alert alert-<?= $class ?> mt-3"><?= $message ?></div>
        <?php endif ?>