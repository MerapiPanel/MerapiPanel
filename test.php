<?php
function htmlToWhatsappMarkdown($html)
{
    // Replace <i> and <em> tags with _
    $html = preg_replace('/<(i|em)[^>]*>(.*?)<\/\1>/s', '_$2_', $html);

    // Replace <b> and <strong> tags with *
    $html = preg_replace('/<(b|strong)[^>]*>(.*?)<\/\1>/s', '*$2*', $html);
    // Replace <strike>, <s>, and <u> tags with ~
    $html = preg_replace('/<strike[^>]*>(.*?)<\/\1>/s', '~$2~', $html);
    // Replace <del>, <s>, and <u> tags with ~
    $html = preg_replace('/<del[^>]*>(.*?)<\/del>/s', '~$1~', $html);
    // Replace <del>, <s>, and <u> tags with ~
    $html = preg_replace('/<s>(.*?)<\/s>/s', '~$1~', $html);
    // Replace <u> tags with ~
    $html = preg_replace('/<u>(.*?)<\/u>/s', '~$1~', $html);
    // Replace <code> tags with ```
    $html = preg_replace('/<code[^>]*>(.*?)<\/code>/s', '```$1```', $html);
    // Replace <ul> tags with *
    $html = preg_replace_callback('/<ul[^>]*>(.*?)<\/ul>/s', function ($matches) {
        $listItems = preg_replace('/<li[^>]*>(.*?)<\/li>/s', "* $1\n", $matches[1]);
        return $listItems;
    }, $html);
    // Replace <ol> tags with numbered list
    $html = preg_replace_callback('/<ol[^>]*>(.*?)<\/ol>/s', function ($matches) {
        $listItems = preg_replace_callback('/<li[^>]*>(.*?)<\/li>/s', function ($matches) {
            static $count = 1;
            return $count++ . ". $matches[1]\n";
        }, $matches[1]);
        return $listItems;
    }, $html);
    // Replace <blockquote> tags with >
    $html = preg_replace('/<blockquote[^>]*>(.*?)<\/blockquote>/s', "> $1\n", $html);
    // Replace <p> tags with \n
    $html = preg_replace('/<p[^>]*>(.*?)<\/p>/s', "$1\n", $html);
    // Replace <br> tags with \n
    $html = preg_replace('/<br\s*\/?>/s', "\n", $html);
    // Replace <hr> tags with \n
    $html = preg_replace('/<hr\s*\/?>/s', "\n", $html);
    // Replace <h1> to <h6> tags with *
    $html = preg_replace('/<h([1-6])[^>]*>(.*?)<\/h\1>/s', "*$2*\n", $html);
    // Remove all remaining HTML tags
    $html = strip_tags($html);
    return $html;
}

// Example usage:
$htmlText = '<p><strong>Hallo Selamat Malam!!!</strong></p><p>Saya ingin Konsultasi bisnis...</p><ol><li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>Number 1</li><li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>Number 2</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Bullet 1</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Bullet 2</li></ol><p><s>Hallo</s></p><p><u>Hallo</u></p><p><em>Hallo</em></p><p><strong>Hallo</strong></p>';
$whatsappMarkdownText = htmlToWhatsappMarkdown($htmlText);
echo $whatsappMarkdownText;
