<?php
use MerapiPanel\Box;
$user = Box::module("Auth")->Session->getUser();

// write logic here
?>
<div class="widget-user-hallo d-flex flex-justify-start w-100">
    <?= isset($user['avatar']) ? '<img class="widget-user-avatar" src="' . $user['avatar'] . '">' : ' <i class="fa-solid fa-user fa-4x"></i>' ?>
    <div class="ms-3">
        <h1 class="fw-bold">Hello, <?= $user['name'] ?></h1>
        <small class="text-muted d-block">Email: <?= $user['email'] ?></small>
        <small class="text-muted d-block">Role: <?= $user['role'] ?></small>
    </div>
</div>
