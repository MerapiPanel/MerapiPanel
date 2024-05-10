<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* __string_template__00e08a6f77a4c072ee3835eb5261bd6e */
class __TwigTemplate_91f575c7fb777f68910898e3aca16834 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\">
        <title>Widget | @user/hallo</title>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <link rel=\"stylesheet\" href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("dist/main.css")), "html", null, true);
        echo "\" type=\"text/css\">
        <link rel=\"stylesheet\" href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("/vendor/fontawesome/css/all.min.css")), "html", null, true);
        echo "\" type=\"text/css\">
        <style>body, html {height: 100vh;display: flex;align-items: center;justify-content: center;}</style>
        <style type=\"text/css\">html,body{height:100vh;width:100vw;margin:0;padding:0;display:flex;justify-content:center;align-items:center}.widget-product-hallo{height:100vh;color:red;font-weight:700;font-size:25px;display:flex;justify-content:center;align-items:center}.widget-user-avatar{width:75px;height:75px;object-fit:cover;object-position:center;border-radius:.3rem}
</style>
    </head>
    <body>
        <div class=\"widget-user-hallo d-flex flex-justify-start w-100\">
    <img class=\"widget-user-avatar\" src=\"/include/Module/User/avatars/000001.webp\">    <div class=\"ms-3\">
        <h1 class=\"fw-bold\">Hello, ilham B</h1>
        <small class=\"text-muted d-block\">Email: durianbohong@gmail.com</small>
        <small class=\"text-muted d-block\">Role: admin</small>
    </div>
</div>

        <script type=\"text/javascript\">(()=>{\"use strict\";console.log(\"hello world from module Product with widget hallo\")})();</script>
    </body>
</html>";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "__string_template__00e08a6f77a4c072ee3835eb5261bd6e";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  49 => 8,  45 => 7,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\">
        <title>Widget | @user/hallo</title>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <link rel=\"stylesheet\" href=\"{{ 'dist/main.css' | assets | url }}\" type=\"text/css\">
        <link rel=\"stylesheet\" href=\"{{ '/vendor/fontawesome/css/all.min.css' | assets | url }}\" type=\"text/css\">
        <style>body, html {height: 100vh;display: flex;align-items: center;justify-content: center;}</style>
        <style type=\"text/css\">html,body{height:100vh;width:100vw;margin:0;padding:0;display:flex;justify-content:center;align-items:center}.widget-product-hallo{height:100vh;color:red;font-weight:700;font-size:25px;display:flex;justify-content:center;align-items:center}.widget-user-avatar{width:75px;height:75px;object-fit:cover;object-position:center;border-radius:.3rem}
</style>
    </head>
    <body>
        <div class=\"widget-user-hallo d-flex flex-justify-start w-100\">
    <img class=\"widget-user-avatar\" src=\"/include/Module/User/avatars/000001.webp\">    <div class=\"ms-3\">
        <h1 class=\"fw-bold\">Hello, ilham B</h1>
        <small class=\"text-muted d-block\">Email: durianbohong@gmail.com</small>
        <small class=\"text-muted d-block\">Role: admin</small>
    </div>
</div>

        <script type=\"text/javascript\">(()=>{\"use strict\";console.log(\"hello world from module Product with widget hallo\")})();</script>
    </body>
</html>", "__string_template__00e08a6f77a4c072ee3835eb5261bd6e", "");
    }
}
