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

/* base.html.twig */
class __TwigTemplate_aae0260cecdebf3ed50146e256c35e25 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'lang' => [$this, 'block_lang'],
            'html' => [$this, 'block_html'],
            'head' => [$this, 'block_head'],
            'meta' => [$this, 'block_meta'],
            'meta_title' => [$this, 'block_meta_title'],
            'stylesheet' => [$this, 'block_stylesheet'],
            'head_javascript' => [$this, 'block_head_javascript'],
            'body' => [$this, 'block_body'],
            'content' => [$this, 'block_content'],
            'javascript' => [$this, 'block_javascript'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>

<html lang=\"";
        // line 3
        $this->displayBlock('lang', $context, $blocks);
        echo "\">
";
        // line 4
        $this->displayBlock('html', $context, $blocks);
        // line 52
        echo "
</html>";
    }

    // line 3
    public function block_lang($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "en";
    }

    // line 4
    public function block_html($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 5
        echo "
<head>
\t";
        // line 7
        $this->displayBlock('head', $context, $blocks);
        // line 27
        echo "</head>


<body>
\t";
        // line 31
        $this->displayBlock('body', $context, $blocks);
        // line 49
        echo "</body>

";
    }

    // line 7
    public function block_head($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 8
        echo "
\t";
        // line 9
        $this->displayBlock('meta', $context, $blocks);
        // line 14
        echo "

\t";
        // line 16
        $this->displayBlock('stylesheet', $context, $blocks);
        // line 20
        echo "

\t";
        // line 22
        $this->displayBlock('head_javascript', $context, $blocks);
        // line 25
        echo "
\t";
    }

    // line 9
    public function block_meta($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 10
        echo "\t<meta charset=\"utf-8\">
\t<title>";
        // line 11
        $this->displayBlock('meta_title', $context, $blocks);
        echo "</title>
\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
\t";
    }

    public function block_meta_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "Merapi panel";
    }

    // line 16
    public function block_stylesheet($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 17
        echo "\t<link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("/dist/main.css"), "html", null, true);
        echo "\">
\t<link rel=\"stylesheet\" href=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("/vendor/fontawesome/css/all.min.css"), "html", null, true);
        echo "\">
\t";
    }

    // line 22
    public function block_head_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 23
        echo "\t<script src=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("/dist/main.js"), "html", null, true);
        echo "\"></script>
\t";
    }

    // line 31
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 32
        echo "\t<main>
\t\t";
        // line 33
        $this->displayBlock('content', $context, $blocks);
        // line 42
        echo "\t</main>


\t";
        // line 45
        $this->displayBlock('javascript', $context, $blocks);
        // line 48
        echo "\t";
    }

    // line 33
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 34
        echo "\t\t<div clas=\"text-center\">
\t\t\t<h1 class=\"text-6xl font-bold mb-4\">This is the base template</h1>
\t\t\t<p class=\"text-lg text-center\">You need to replace this view by add
\t\t\t\t<span class=\"font-bold\">";
        // line 37
        echo "{%";
        echo " <span class=\"text-purple-300\">block</span> <span
\t\t\t\t\t\tclass=\"text-blue-300\">content</span> ";
        // line 38
        echo "%}";
        echo "</span> in you template
\t\t\t</p>
\t\t</div>
\t\t";
    }

    // line 45
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 46
        echo "\t<script src=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("/vendor/bootstrap/js/bootstrap.bundle.min.js"), "html", null, true);
        echo "\"></script>
\t";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "base.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  210 => 46,  206 => 45,  198 => 38,  194 => 37,  189 => 34,  185 => 33,  181 => 48,  179 => 45,  174 => 42,  172 => 33,  169 => 32,  165 => 31,  158 => 23,  154 => 22,  148 => 18,  143 => 17,  139 => 16,  126 => 11,  123 => 10,  119 => 9,  114 => 25,  112 => 22,  108 => 20,  106 => 16,  102 => 14,  100 => 9,  97 => 8,  93 => 7,  87 => 49,  85 => 31,  79 => 27,  77 => 7,  73 => 5,  69 => 4,  62 => 3,  57 => 52,  55 => 4,  51 => 3,  47 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>

<html lang=\"{% block lang %}en{% endblock %}\">
{% block html %}

<head>
\t{% block head %}

\t{% block meta %}
\t<meta charset=\"utf-8\">
\t<title>{% block meta_title %}Merapi panel{% endblock %}</title>
\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
\t{% endblock %}


\t{% block stylesheet %}
\t<link rel=\"stylesheet\" href=\"{{ '/dist/main.css' | assets }}\">
\t<link rel=\"stylesheet\" href=\"{{ '/vendor/fontawesome/css/all.min.css' | assets }}\">
\t{% endblock %}


\t{% block head_javascript %}
\t<script src=\"{{ '/dist/main.js' | assets }}\"></script>
\t{% endblock %}

\t{% endblock %}
</head>


<body>
\t{% block body %}
\t<main>
\t\t{% block content %}
\t\t<div clas=\"text-center\">
\t\t\t<h1 class=\"text-6xl font-bold mb-4\">This is the base template</h1>
\t\t\t<p class=\"text-lg text-center\">You need to replace this view by add
\t\t\t\t<span class=\"font-bold\">{{\"{%\"}} <span class=\"text-purple-300\">block</span> <span
\t\t\t\t\t\tclass=\"text-blue-300\">content</span> {{ \"%}\" }}</span> in you template
\t\t\t</p>
\t\t</div>
\t\t{% endblock %}
\t</main>


\t{% block javascript %}
\t<script src=\"{{ '/vendor/bootstrap/js/bootstrap.bundle.min.js' | assets }}\"></script>
\t{% endblock %}
\t{% endblock %}
</body>

{% endblock %}

</html>", "base.html.twig", "F:\\web\\MerapiPanel\\include\\Buildin\\Views\\base.html.twig");
    }
}
