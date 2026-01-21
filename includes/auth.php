<?php
declare(strict_types=1);

if (empty($_SESSION['user'])) {
    redirect('login');
}
