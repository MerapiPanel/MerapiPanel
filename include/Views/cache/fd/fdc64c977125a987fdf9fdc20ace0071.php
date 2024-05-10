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

/* @article/Admin/index.html.twig */
class __TwigTemplate_7d32c572238f4fc99b82f0eccd1170a0 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'header_title' => [$this, 'block_header_title'],
            'stylesheet' => [$this, 'block_stylesheet'],
            'subheader_menu' => [$this, 'block_subheader_menu'],
            'breadcrumb_item' => [$this, 'block_breadcrumb_item'],
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
        // line 25
        $context["articleLen"] = (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "article", [], "any", false, false, false, 25), "fetchAll", [], "any", false, false, false, 25)) - 1);
        // line 1
        $this->parent = $this->loadTemplate("@panel/base.html.twig", "@article/Admin/index.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_header_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "Article";
    }

    // line 7
    public function block_stylesheet($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 8
        $this->displayParentBlock("stylesheet", $context, $blocks);
        echo "
<link rel=\"stylesheet\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@article/assets/dist/index.css"), "html", null, true);
        echo "\">
";
    }

    // line 12
    public function block_subheader_menu($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 13
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Article", [], "any", false, false, false, 13), "getRoles", [], "any", false, false, false, 13), "isAllowed", [1], "method", false, false, false, 13)) {
            // line 14
            echo "<a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("article/create"), "html", null, true);
            echo "\" class=\"menu-item\">
    <i class=\"fa fa-plus\"></i> new article
</a>
";
        }
    }

    // line 20
    public function block_breadcrumb_item($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 21
        echo "<li class=\"breadcrumb-item\"><a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/"), "html", null, true);
        echo "\">Home</a></li>
<li class=\"breadcrumb-item\">Article</li>
";
    }

    // line 27
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 28
        echo "<h2 class=\"ms-3 mt-3\" id=\"total-records\">Total Records: ";
        echo twig_escape_filter($this->env, ($context["articleLen"] ?? null), "html", null, true);
        echo "</h2>

<div class=\"container-article gap-3\">
    <div class=\"d-flex flex-wrap gap-3 flex-grow-1 w-100 p-3 \" id=\"article-wrapper\" onload=\"__.Article.render.call(this)\">

        ";
        // line 33
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(0, ($context["articleLen"] ?? null)));
        foreach ($context['_seq'] as $context["_key"] => $context["x"]) {
            // line 34
            echo "        <div class=\"card border-0 shadow-sm\" aria-hidden=\"true\" style='width: 100%; max-width: 30rem;'>
            <div class=\"card-body\">
                <h5 class=\"card-title placeholder-glow\">
                    <span class=\"placeholder col-6\"></span>
                </h5>
                <p class=\"card-text placeholder-glow\">
                    <span class=\"placeholder col-7\"></span>
                    <span class=\"placeholder col-4\"></span>
                    <span class=\"placeholder col-4\"></span>
                    <span class=\"placeholder col-6\"></span>
                    <span class=\"placeholder col-8\"></span>
                </p>
                <a href=\"#\" tabindex=\"-1\" class=\"btn btn-primary disabled placeholder col-6\"></a>
            </div>
        </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['x'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 50
        echo "    </div>
</div>

<div id=\"pagination\" class=\"pagination justify-content-center mb-5 mt-3\"></div>

";
    }

    // line 58
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 59
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
";
        // line 67
        echo "<script src=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@article/dist/index.js"), "html", null, true);
        echo "\"></script>
";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@article/Admin/index.html.twig";
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
        return array (  158 => 67,  154 => 59,  150 => 58,  141 => 50,  120 => 34,  116 => 33,  107 => 28,  103 => 27,  95 => 21,  91 => 20,  81 => 14,  79 => 13,  75 => 12,  69 => 9,  65 => 8,  61 => 7,  54 => 4,  49 => 1,  47 => 25,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"@panel/base.html.twig\" %}


{% block header_title %}Article{% endblock %}


{% block stylesheet %}
{{ parent() }}
<link rel=\"stylesheet\" href=\"{{ '@article/assets/dist/index.css' | assets }}\">
{% endblock %}

{% block subheader_menu %}
{% if api.Article.getRoles.isAllowed(1) %}
<a href=\"{{ 'article/create' | access_path }}\" class=\"menu-item\">
    <i class=\"fa fa-plus\"></i> new article
</a>
{% endif %}
{% endblock %}

{% block breadcrumb_item %}
<li class=\"breadcrumb-item\"><a href=\"{{ '/' | access_path }}\">Home</a></li>
<li class=\"breadcrumb-item\">Article</li>
{% endblock %}

{% set articleLen = (api.article.fetchAll | length) -1 %}

{% block content %}
<h2 class=\"ms-3 mt-3\" id=\"total-records\">Total Records: {{ articleLen }}</h2>

<div class=\"container-article gap-3\">
    <div class=\"d-flex flex-wrap gap-3 flex-grow-1 w-100 p-3 \" id=\"article-wrapper\" onload=\"__.Article.render.call(this)\">

        {% for x in 0..articleLen %}
        <div class=\"card border-0 shadow-sm\" aria-hidden=\"true\" style='width: 100%; max-width: 30rem;'>
            <div class=\"card-body\">
                <h5 class=\"card-title placeholder-glow\">
                    <span class=\"placeholder col-6\"></span>
                </h5>
                <p class=\"card-text placeholder-glow\">
                    <span class=\"placeholder col-7\"></span>
                    <span class=\"placeholder col-4\"></span>
                    <span class=\"placeholder col-4\"></span>
                    <span class=\"placeholder col-6\"></span>
                    <span class=\"placeholder col-8\"></span>
                </p>
                <a href=\"#\" tabindex=\"-1\" class=\"btn btn-primary disabled placeholder col-6\"></a>
            </div>
        </div>
        {% endfor %}
    </div>
</div>

<div id=\"pagination\" class=\"pagination justify-content-center mb-5 mt-3\"></div>

{% endblock %}


{% block javascript %}
{{ parent() }}
{# <script>
    window.articles = JSON.parse('{{ articles|json_encode|raw }}');
    window.updateEndpoint = \"{{ '/article/endpoint/update' | access_path }}\";
    window.deleteEndpoint = \"{{ '/article/endpoint/delete' | access_path }}\";
    window.editURL = \"{{ '/article/edit' | access_path }}\";
    window.viewEndpoint = \"{{ '/article/view' | access_path }}\";
</script> #}
<script src=\"{{ '@article/dist/index.js' | assets }}\"></script>
{% endblock %}", "@article/Admin/index.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\Article\\Views\\Admin\\index.html.twig");
    }
}
