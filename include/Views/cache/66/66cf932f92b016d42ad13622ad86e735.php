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

/* @user/Admin/index.html.twig */
class __TwigTemplate_47a419ef5e8810ed97a1087dd2a4ea36 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'header_title' => [$this, 'block_header_title'],
            'breadcrumb_item' => [$this, 'block_breadcrumb_item'],
            'subheader_menu' => [$this, 'block_subheader_menu'],
            'stylesheet' => [$this, 'block_stylesheet'],
            'content' => [$this, 'block_content'],
            'javascript' => [$this, 'block_javascript'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "@panel/base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 30
        $context["usersLen"] = (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "User", [], "any", false, false, false, 30), "fetchAll", [], "any", false, false, false, 30)) - 1);
        // line 1
        $this->parent = $this->loadTemplate("@panel/base.html.twig", "@user/Admin/index.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_header_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "Users";
    }

    // line 5
    public function block_breadcrumb_item($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 6
        echo "<li class=\"breadcrumb-item\"><a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/"), "html", null, true);
        echo "\">Home</a></li>
<li class=\"breadcrumb-item active\" aria-current=\"page\">Users</li>
";
    }

    // line 11
    public function block_subheader_menu($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 12
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "User", [], "any", false, false, false, 12), "getRoles", [], "any", false, false, false, 12), "isAllowed", [1], "method", false, false, false, 12)) {
            // line 13
            echo "<a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/users/add"), "html", null, true);
            echo "\" class=\"menu-item\">
    <i class=\"fa fa-plus\"></i> add user
</a>
";
        }
    }

    // line 19
    public function block_stylesheet($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 20
        $this->displayParentBlock("stylesheet", $context, $blocks);
        echo "
<style>
    .list-group-item img {
        object-fit: cover;
    }
</style>
";
    }

    // line 33
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 34
        echo "<h2 class=\"ms-3 mt-3\" id=\"total-records\">Total Records: ";
        echo twig_escape_filter($this->env, ($context["usersLen"] ?? null), "html", null, true);
        echo "</h2>

<div class=\"p-3\">
    <ol class=\"list-group list-group-flush gap-2\" style=\"max-width: 800px;\" onload=\"__.MUser.render.bind(this)()\">
        ";
        // line 38
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(1, ($context["usersLen"] ?? null)));
        foreach ($context['_seq'] as $context["_key"] => $context["x"]) {
            // line 39
            echo "        <li class=\"list-group-item d-flex align-items-start position-relative placeholder-glow\">
            <img class=\"w-full h-full object-cover rounded-2 placeholder\" width=\"45\" height=\"45\" alt=\"\">
            <div class=\"ms-2 w-100\">
                <span class=\"placeholder col-6\"></span>
                <span class=\"placeholder w-75\"></span>
            </div>
        </li>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['x'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 47
        echo "    </ol>
</div>

<div id=\"pagination\" class=\"pagination justify-content-center mb-5 mt-3\"></div>


";
    }

    // line 55
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 56
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
<script src=\"";
        // line 57
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@user/dist/index.js"), "html", null, true);
        echo "\"></script>
";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@user/Admin/index.html.twig";
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
        return array (  151 => 57,  147 => 56,  143 => 55,  133 => 47,  120 => 39,  116 => 38,  108 => 34,  104 => 33,  93 => 20,  89 => 19,  79 => 13,  77 => 12,  73 => 11,  65 => 6,  61 => 5,  54 => 2,  49 => 1,  47 => 30,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"@panel/base.html.twig\" %}
{% block header_title %}Users{% endblock %}


{% block breadcrumb_item %}
<li class=\"breadcrumb-item\"><a href=\"{{ '/' | access_path }}\">Home</a></li>
<li class=\"breadcrumb-item active\" aria-current=\"page\">Users</li>
{% endblock %}


{% block subheader_menu %}
{% if api.User.getRoles.isAllowed(1) %}
<a href=\"{{ '/users/add' | access_path }}\" class=\"menu-item\">
    <i class=\"fa fa-plus\"></i> add user
</a>
{% endif %}
{% endblock %}

{% block stylesheet %}
{{ parent() }}
<style>
    .list-group-item img {
        object-fit: cover;
    }
</style>
{% endblock %}



{% set usersLen = (api.User.fetchAll|length ) -1 %}


{% block content %}
<h2 class=\"ms-3 mt-3\" id=\"total-records\">Total Records: {{ usersLen }}</h2>

<div class=\"p-3\">
    <ol class=\"list-group list-group-flush gap-2\" style=\"max-width: 800px;\" onload=\"__.MUser.render.bind(this)()\">
        {% for x in 1..usersLen %}
        <li class=\"list-group-item d-flex align-items-start position-relative placeholder-glow\">
            <img class=\"w-full h-full object-cover rounded-2 placeholder\" width=\"45\" height=\"45\" alt=\"\">
            <div class=\"ms-2 w-100\">
                <span class=\"placeholder col-6\"></span>
                <span class=\"placeholder w-75\"></span>
            </div>
        </li>
        {% endfor %}
    </ol>
</div>

<div id=\"pagination\" class=\"pagination justify-content-center mb-5 mt-3\"></div>


{% endblock %}

{% block javascript %}
{{ parent() }}
<script src=\"{{ '@user/dist/index.js' | assets }}\"></script>
{% endblock %}", "@user/Admin/index.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\User\\Views\\Admin\\index.html.twig");
    }
}
