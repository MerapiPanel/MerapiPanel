<?php

error_log("Hallo World");

error_log(function_exists("registerBlockType") ? "register" : "no register");

registerBlockType("merapi/group", [
    "title" => "Group",
    "name" => "core/group",
    "description" => "Group",
    "category" => "common",
    "editScript" => "merapi/Module/Editor/component/blocks/group/edit.php",
    "editStyle" => "merapi/Module/Editor/component/b/group/edit.php",
    "save" => "merapi/Module/Editor/component/blocks/group/save.php",
    "style" => "merapi/Module/Editor/component/blocks/group/style.php",
]);
