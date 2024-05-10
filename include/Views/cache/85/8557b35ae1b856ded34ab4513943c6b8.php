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

/* @product/Admin/index.html.twig */
class __TwigTemplate_6fe54e583a6bed24eaa2c06c0c5f4aa2 extends Template
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
        // line 21
        $context["productLen"] = (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "product", [], "any", false, false, false, 21), "fetchAll", [], "any", false, false, false, 21)) - 1);
        // line 1
        $this->parent = $this->loadTemplate("@panel/base.html.twig", "@product/Admin/index.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_header_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "Product";
    }

    // line 5
    public function block_breadcrumb_item($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 6
        echo "<li class=\"breadcrumb-item\"><a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/"), "html", null, true);
        echo "\">Home</a></li>
<li class=\"breadcrumb-item active\" aria-current=\"page\">Product</li>
";
    }

    // line 12
    public function block_subheader_menu($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 13
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Product", [], "any", false, false, false, 13), "getRoles", [], "any", false, false, false, 13), "isAllowed", [1], "method", false, false, false, 13)) {
            // line 14
            echo "<a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/product/new"), "html", null, true);
            echo "\" class=\"menu-item\">
    <i class=\"fa fa-plus\"></i> new product
</a>
";
        }
    }

    // line 22
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 23
        echo "
<h2 class=\"ms-3 mt-3\" id=\"total-records\">Total Records: ";
        // line 24
        echo twig_escape_filter($this->env, ($context["productLen"] ?? null), "html", null, true);
        echo "</h2>

<div class=\"d-flex flex-wrap gap-3 p-3\" id=\"product-wrapper\" onload=\"__.MProduct.render.call(this)\">
    ";
        // line 27
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(1, ($context["productLen"] ?? null)));
        foreach ($context['_seq'] as $context["_key"] => $context["x"]) {
            // line 28
            echo "    <div class=\"card\" aria-hidden=\"true\" style=\"width: 100%; max-width: 18rem;opacity: 0.5;\">
        <img src=\"...\" class=\"card-img-top\" alt=\"...\" style=\"height: 150px;\">
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
        </div>
    </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['x'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 44
        echo "</div>

<div id=\"pagination\" class=\"pagination justify-content-center mb-5\"></div>

";
    }

    // line 51
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 52
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
<script src=\"";
        // line 53
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@product/dist/index.js"), "html", null, true);
        echo "\"></script>
";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@product/Admin/index.html.twig";
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
        return array (  142 => 53,  138 => 52,  134 => 51,  126 => 44,  105 => 28,  101 => 27,  95 => 24,  92 => 23,  88 => 22,  78 => 14,  76 => 13,  72 => 12,  64 => 6,  60 => 5,  53 => 3,  48 => 1,  46 => 21,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"@panel/base.html.twig\" %}

{% block header_title %}Product{% endblock %}

{% block breadcrumb_item %}
<li class=\"breadcrumb-item\"><a href=\"{{ '/' | access_path }}\">Home</a></li>
<li class=\"breadcrumb-item active\" aria-current=\"page\">Product</li>
{% endblock %}



{% block subheader_menu %}
{% if api.Product.getRoles.isAllowed(1) %}
<a href=\"{{ '/product/new' | access_path }}\" class=\"menu-item\">
    <i class=\"fa fa-plus\"></i> new product
</a>
{% endif %}
{% endblock %}


{% set productLen = (api.product.fetchAll|length)-1 %}
{% block content %}

<h2 class=\"ms-3 mt-3\" id=\"total-records\">Total Records: {{ productLen }}</h2>

<div class=\"d-flex flex-wrap gap-3 p-3\" id=\"product-wrapper\" onload=\"__.MProduct.render.call(this)\">
    {% for x in 1..productLen %}
    <div class=\"card\" aria-hidden=\"true\" style=\"width: 100%; max-width: 18rem;opacity: 0.5;\">
        <img src=\"...\" class=\"card-img-top\" alt=\"...\" style=\"height: 150px;\">
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
        </div>
    </div>
    {% endfor %}
</div>

<div id=\"pagination\" class=\"pagination justify-content-center mb-5\"></div>

{% endblock %}


{% block javascript %}
{{ parent() }}
<script src=\"{{ '@product/dist/index.js' | assets }}\"></script>
{% endblock %}", "@product/Admin/index.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\Product\\Views\\Admin\\index.html.twig");
    }
}
