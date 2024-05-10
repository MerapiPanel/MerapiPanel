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

/* @panel/base.html.twig */
class __TwigTemplate_18ceae1452cab9cd6e27e42b25b939e7 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'stylesheet' => [$this, 'block_stylesheet'],
            'head_javascript' => [$this, 'block_head_javascript'],
            'body' => [$this, 'block_body'],
            'main' => [$this, 'block_main'],
            'header' => [$this, 'block_header'],
            'header_title_outer' => [$this, 'block_header_title_outer'],
            'header_title' => [$this, 'block_header_title'],
            'breadcrumb_outer' => [$this, 'block_breadcrumb_outer'],
            'breadcrumb' => [$this, 'block_breadcrumb'],
            'breadcrumb_item' => [$this, 'block_breadcrumb_item'],
            'container' => [$this, 'block_container'],
            'subheader_outer' => [$this, 'block_subheader_outer'],
            'subheader' => [$this, 'block_subheader'],
            'subheader_search_outer' => [$this, 'block_subheader_search_outer'],
            'subheader_search' => [$this, 'block_subheader_search'],
            'subheader_actions_outer' => [$this, 'block_subheader_actions_outer'],
            'subheader_actions' => [$this, 'block_subheader_actions'],
            'subheader_menu_outer' => [$this, 'block_subheader_menu_outer'],
            'subheader_menu' => [$this, 'block_subheader_menu'],
            'content_outer' => [$this, 'block_content_outer'],
            'content' => [$this, 'block_content'],
            'footer' => [$this, 'block_footer'],
            'javascript' => [$this, 'block_javascript'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("base.html.twig", "@panel/base.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "Admin";
    }

    // line 6
    public function block_stylesheet($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 7
        $this->displayParentBlock("stylesheet", $context, $blocks);
        echo "
<link rel=\"stylesheet\" href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@panel/dist/panel.css"), "html", null, true);
        echo "\">
";
    }

    // line 12
    public function block_head_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 13
        $this->displayParentBlock("head_javascript", $context, $blocks);
        echo "
<script src=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@panel/dist/panel.js"), "html", null, true);
        echo "\"></script>
<!-- @Panel:scripts -->
";
        // line 16
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Module\Panel\ViewExtension']->fn_panel_render($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Panel", [], "any", false, false, false, 16), "Scripts", [], "any", false, false, false, 16), "getScripts", [], "any", false, false, false, 16)), "html", null, true);
        echo "
<!--/ @Panel:scripts -->
";
    }

    // line 21
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 22
        echo "
<body class=\"panel-body\" id=\"panel-body\">

    ";
        // line 25
        $this->loadTemplate("@panel/navigation.html.twig", "@panel/base.html.twig", 25)->display($context);
        // line 26
        echo "
    ";
        // line 27
        $this->displayBlock('main', $context, $blocks);
        // line 121
        echo "
    <script>
        function toggleMenu() {
            \$(\"#subheader-actions\").toggleClass(\"open\");
            \$(document).on('click', function (e) {
                if (!\$(e.target).closest('#subheader-actions').length) {
                    \$(\"#subheader-actions\").removeClass(\"open\");
                }
            })
        }

        if(\$(\"#subheader-menu\").children().length == 0) {
            \$(\"#subheader-actions\").hide();
        }
    </script>

    ";
        // line 137
        $this->displayBlock('javascript', $context, $blocks);
        // line 140
        echo "
</body>

";
    }

    // line 27
    public function block_main($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 28
        echo "    <main class=\"panel-main mb-5\" id=\"panel-main\">

        ";
        // line 30
        $this->displayBlock('header', $context, $blocks);
        // line 43
        echo "
        ";
        // line 44
        $this->displayBlock('breadcrumb_outer', $context, $blocks);
        // line 59
        echo "

        ";
        // line 61
        $this->displayBlock('container', $context, $blocks);
        // line 114
        echo "
        <div>
            ";
        // line 116
        $this->displayBlock('footer', $context, $blocks);
        // line 117
        echo "        </div>

    </main>
    ";
    }

    // line 30
    public function block_header($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 31
        echo "        <header class=\"panel-header\">

            <button class=\"sidebar-toggle\" onclick=\"openNav()\" id=\"sidebar-toggle\"></button>

            ";
        // line 35
        $this->displayBlock('header_title_outer', $context, $blocks);
        // line 40
        echo "
        </header>
        ";
    }

    // line 35
    public function block_header_title_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 36
        echo "            <h1 class=\"panel-title\">";
        $this->displayBlock('header_title', $context, $blocks);
        echo "</h1>
            <small class=\"text-muted float-end ms-auto\">Loadtime: ";
        // line 37
        echo twig_escape_filter($this->env, twig_round(($this->extensions['MerapiPanel\Views\Extension\Bundle']->fn_microtime(true) - twig_get_attribute($this->env, $this->source, ($context["__env__"] ?? null), "__MP_START_TIME__", [], "any", false, false, false, 37)), 3, "floor"), "html", null, true);
        // line 38
        echo " s</small>
            ";
    }

    // line 36
    public function block_header_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "MerapiPanel";
    }

    // line 44
    public function block_breadcrumb_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 45
        echo "        <nav class=\"flex w-full px-3\" aria-label=\"breadcrumb\">

            ";
        // line 47
        $this->displayBlock('breadcrumb', $context, $blocks);
        // line 56
        echo "
        </nav>
        ";
    }

    // line 47
    public function block_breadcrumb($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 48
        echo "            <ol class=\"breadcrumb\">

                ";
        // line 50
        $this->displayBlock('breadcrumb_item', $context, $blocks);
        // line 53
        echo "
            </ol>
            ";
    }

    // line 50
    public function block_breadcrumb_item($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 51
        echo "                <li class=\"breadcrumb-item\"><a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/")), "html", null, true);
        echo "\">Home</a></li>
                ";
    }

    // line 61
    public function block_container($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 62
        echo "        <div class=\"panel-container\">

            ";
        // line 64
        $this->displayBlock('subheader_outer', $context, $blocks);
        // line 98
        echo "
            ";
        // line 99
        $this->displayBlock('content_outer', $context, $blocks);
        // line 111
        echo "
        </div>
        ";
    }

    // line 64
    public function block_subheader_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 65
        echo "            <div class=\"panel-subheader\" id=\"panel-subheader\">
                ";
        // line 66
        $this->displayBlock('subheader', $context, $blocks);
        // line 96
        echo "            </div>
            ";
    }

    // line 66
    public function block_subheader($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 67
        echo "                ";
        $this->displayBlock('subheader_search_outer', $context, $blocks);
        // line 80
        echo "

                ";
        // line 82
        $this->displayBlock('subheader_actions_outer', $context, $blocks);
        // line 95
        echo "                ";
    }

    // line 67
    public function block_subheader_search_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 68
        echo "                <form class=\"panel-search\" id=\"panel-subheader-search\">
                    ";
        // line 69
        $this->displayBlock('subheader_search', $context, $blocks);
        // line 77
        echo "                </form>

                ";
    }

    // line 69
    public function block_subheader_search($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 70
        echo "
                    <input class=\"form-control\" type=\"text\" placeholder=\"Search\" name=\"search\">
                    <button class=\"ms-2 btn\">
                        <i class=\"fa-solid fa-magnifying-glass\"></i>
                    </button>

                    ";
    }

    // line 82
    public function block_subheader_actions_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 83
        echo "                <div id=\"subheader-actions\" class=\"subheader-actions\">
                    ";
        // line 84
        $this->displayBlock('subheader_actions', $context, $blocks);
        // line 93
        echo "                </div>
                ";
    }

    // line 84
    public function block_subheader_actions($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 85
        echo "                    <button class=\"btn menu-toggle\" onclick=\"toggleMenu()\"></button>
                    ";
        // line 86
        $this->displayBlock('subheader_menu_outer', $context, $blocks);
        // line 91
        echo "
                    ";
    }

    // line 86
    public function block_subheader_menu_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 87
        echo "                    <div id=\"subheader-menu\" class=\"subheader-menu\">
                        ";
        // line 88
        $this->displayBlock('subheader_menu', $context, $blocks);
        // line 89
        echo "                    </div>
                    ";
    }

    // line 88
    public function block_subheader_menu($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 99
    public function block_content_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 100
        echo "            <div class=\"panel-content\">
                ";
        // line 101
        $this->displayBlock('content', $context, $blocks);
        // line 109
        echo "            </div>
            ";
    }

    // line 101
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 102
        echo "                <div class=\"flex-grow-1 align-content-center\" id=\"panel-content\">
                    <div class=\"text-center\">
                        <h2 class=\"fs-2 fw-bold\">Coming Soon</h2>
                        <p>this is base template, you should not see this</p>
                    </div>
                </div>
                ";
    }

    // line 116
    public function block_footer($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 137
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 138
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    ";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@panel/base.html.twig";
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
        return array (  438 => 138,  434 => 137,  428 => 116,  418 => 102,  414 => 101,  409 => 109,  407 => 101,  404 => 100,  400 => 99,  394 => 88,  389 => 89,  387 => 88,  384 => 87,  380 => 86,  375 => 91,  373 => 86,  370 => 85,  366 => 84,  361 => 93,  359 => 84,  356 => 83,  352 => 82,  342 => 70,  338 => 69,  332 => 77,  330 => 69,  327 => 68,  323 => 67,  319 => 95,  317 => 82,  313 => 80,  310 => 67,  306 => 66,  301 => 96,  299 => 66,  296 => 65,  292 => 64,  286 => 111,  284 => 99,  281 => 98,  279 => 64,  275 => 62,  271 => 61,  264 => 51,  260 => 50,  254 => 53,  252 => 50,  248 => 48,  244 => 47,  238 => 56,  236 => 47,  232 => 45,  228 => 44,  221 => 36,  216 => 38,  214 => 37,  209 => 36,  205 => 35,  199 => 40,  197 => 35,  191 => 31,  187 => 30,  180 => 117,  178 => 116,  174 => 114,  172 => 61,  168 => 59,  166 => 44,  163 => 43,  161 => 30,  157 => 28,  153 => 27,  146 => 140,  144 => 137,  126 => 121,  124 => 27,  121 => 26,  119 => 25,  114 => 22,  110 => 21,  103 => 16,  98 => 14,  94 => 13,  90 => 12,  84 => 8,  80 => 7,  76 => 6,  69 => 3,  58 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"base.html.twig\" %}

{% block title %}Admin{% endblock %}


{% block stylesheet %}
{{ parent() }}
<link rel=\"stylesheet\" href=\"{{ '@panel/dist/panel.css' | assets }}\">
{% endblock %}


{% block head_javascript %}
{{ parent() }}
<script src=\"{{ '@panel/dist/panel.js' | assets }}\"></script>
<!-- @Panel:scripts -->
{{ panel_render(api.Panel.Scripts.getScripts) }}
<!--/ @Panel:scripts -->
{% endblock %}


{% block body %}

<body class=\"panel-body\" id=\"panel-body\">

    {% include \"@panel/navigation.html.twig\" %}

    {% block main %}
    <main class=\"panel-main mb-5\" id=\"panel-main\">

        {% block header %}
        <header class=\"panel-header\">

            <button class=\"sidebar-toggle\" onclick=\"openNav()\" id=\"sidebar-toggle\"></button>

            {% block header_title_outer %}
            <h1 class=\"panel-title\">{% block header_title %}MerapiPanel{% endblock %}</h1>
            <small class=\"text-muted float-end ms-auto\">Loadtime: {{ (microtime(true) - __env__.__MP_START_TIME__ ) |
                round(3, 'floor') }} s</small>
            {% endblock %}

        </header>
        {% endblock %}

        {% block breadcrumb_outer %}
        <nav class=\"flex w-full px-3\" aria-label=\"breadcrumb\">

            {% block breadcrumb %}
            <ol class=\"breadcrumb\">

                {% block breadcrumb_item %}
                <li class=\"breadcrumb-item\"><a href=\"{{ '/' | access_path | url }}\">Home</a></li>
                {% endblock %}

            </ol>
            {% endblock %}

        </nav>
        {% endblock %}


        {% block container %}
        <div class=\"panel-container\">

            {% block subheader_outer %}
            <div class=\"panel-subheader\" id=\"panel-subheader\">
                {% block subheader %}
                {% block subheader_search_outer %}
                <form class=\"panel-search\" id=\"panel-subheader-search\">
                    {% block subheader_search %}

                    <input class=\"form-control\" type=\"text\" placeholder=\"Search\" name=\"search\">
                    <button class=\"ms-2 btn\">
                        <i class=\"fa-solid fa-magnifying-glass\"></i>
                    </button>

                    {% endblock %}
                </form>

                {% endblock %}


                {% block subheader_actions_outer %}
                <div id=\"subheader-actions\" class=\"subheader-actions\">
                    {% block subheader_actions %}
                    <button class=\"btn menu-toggle\" onclick=\"toggleMenu()\"></button>
                    {% block subheader_menu_outer %}
                    <div id=\"subheader-menu\" class=\"subheader-menu\">
                        {% block subheader_menu %}{% endblock %}
                    </div>
                    {% endblock %}

                    {% endblock %}
                </div>
                {% endblock %}
                {% endblock %}
            </div>
            {% endblock %}

            {% block content_outer %}
            <div class=\"panel-content\">
                {% block content %}
                <div class=\"flex-grow-1 align-content-center\" id=\"panel-content\">
                    <div class=\"text-center\">
                        <h2 class=\"fs-2 fw-bold\">Coming Soon</h2>
                        <p>this is base template, you should not see this</p>
                    </div>
                </div>
                {% endblock %}
            </div>
            {% endblock %}

        </div>
        {% endblock %}

        <div>
            {% block footer %}{% endblock %}
        </div>

    </main>
    {% endblock %}

    <script>
        function toggleMenu() {
            \$(\"#subheader-actions\").toggleClass(\"open\");
            \$(document).on('click', function (e) {
                if (!\$(e.target).closest('#subheader-actions').length) {
                    \$(\"#subheader-actions\").removeClass(\"open\");
                }
            })
        }

        if(\$(\"#subheader-menu\").children().length == 0) {
            \$(\"#subheader-actions\").hide();
        }
    </script>

    {% block javascript %}
    {{ parent() }}
    {% endblock %}

</body>

{% endblock %}
", "@panel/base.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\Panel\\Views\\base.html.twig");
    }
}
