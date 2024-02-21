<?php

namespace MerapiPanel\Module\Article\Views;

class Widget
{
    /**
     * @title new shortcut
     * @description new Article shortcut
     * @icon fa-solid fa-newspaper
     */
    public final function newArticleShortcut()
    {
        return [
            "css" => [
            ],
            "content" => "<a>Create Article</a><script>window.onload = () => { document.body.classList.add('loaded'); }</script>",
        ];
    }
}