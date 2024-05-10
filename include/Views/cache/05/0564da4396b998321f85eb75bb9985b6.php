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

/* @dashboard/Admin/index.html.twig */
class __TwigTemplate_499105c58491e2c418a545903bbd25c0 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'header_title' => [$this, 'block_header_title'],
            'head_javascript' => [$this, 'block_head_javascript'],
            'stylesheet' => [$this, 'block_stylesheet'],
            'subheader_outer' => [$this, 'block_subheader_outer'],
            'subheader_search_outer' => [$this, 'block_subheader_search_outer'],
            'subheader_menu' => [$this, 'block_subheader_menu'],
            'javascript' => [$this, 'block_javascript'],
            'content' => [$this, 'block_content'],
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
        $this->parent = $this->loadTemplate("@panel/base.html.twig", "@dashboard/Admin/index.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_header_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "Dashboard";
    }

    // line 4
    public function block_head_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 5
        $this->displayParentBlock("head_javascript", $context, $blocks);
        echo "
<script>
    var widgetPayload = {
        endpoint_edit: \"";
        // line 8
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Dashboard/Widget/edit")), "html", null, true);
        echo "\",
        endpoint_save: \"";
        // line 9
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Dashboard/Widget/save")), "html", null, true);
        echo "\",
        endpoint_fetch: \"";
        // line 10
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Dashboard/Widget/fetch")), "html", null, true);
        echo "\",
        endpoint_load: \"";
        // line 11
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/widget/load")), "html", null, true);
        echo "\"
    }
</script>
";
    }

    // line 17
    public function block_stylesheet($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 18
        $this->displayParentBlock("stylesheet", $context, $blocks);
        echo "
<link rel=\"stylesheet\" href=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@dashboard/dist/index.css")), "html", null, true);
        echo "\">
";
    }

    // line 24
    public function block_subheader_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 25
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Dashboard", [], "any", false, false, false, 25), "getRoles", [], "any", false, false, false, 25), "isAllowed", [0], "method", false, false, false, 25)) {
            // line 26
            $this->displayParentBlock("subheader_outer", $context, $blocks);
            echo "
";
        }
    }

    // line 30
    public function block_subheader_search_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 32
    public function block_subheader_menu($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 33
        echo "<button class=\"menu-item\" id=\"edit-widget-button\"><i class=\"fa-regular fa-pen-to-square\"></i> Edit Widget</button>
";
    }

    // line 39
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 40
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
<script src=\"";
        // line 41
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@dashboard/dist/index.js")), "html", null, true);
        echo "\"></script>
";
    }

    // line 47
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 48
        echo "<div class=\"pb-5\" id=\"root\"></div>
";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@dashboard/Admin/index.html.twig";
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
        return array (  150 => 48,  146 => 47,  140 => 41,  136 => 40,  132 => 39,  127 => 33,  123 => 32,  117 => 30,  110 => 26,  108 => 25,  104 => 24,  98 => 19,  94 => 18,  90 => 17,  82 => 11,  78 => 10,  74 => 9,  70 => 8,  64 => 5,  60 => 4,  53 => 2,  42 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"@panel/base.html.twig\" %}
{% block header_title %}Dashboard{% endblock %}

{% block head_javascript %}
{{ parent() }}
<script>
    var widgetPayload = {
        endpoint_edit: \"{{ '/api/Dashboard/Widget/edit' | access_path | url }}\",
        endpoint_save: \"{{ '/api/Dashboard/Widget/save' | access_path | url }}\",
        endpoint_fetch: \"{{ '/api/Dashboard/Widget/fetch' | access_path | url }}\",
        endpoint_load: \"{{ '/widget/load' | access_path | url }}\"
    }
</script>
{% endblock %}


{% block stylesheet %}
{{ parent() }}
<link rel=\"stylesheet\" href=\"{{ '@dashboard/dist/index.css' | assets | url }}\">
{% endblock %}



{% block subheader_outer %}
{% if api.Dashboard.getRoles.isAllowed(0) %}
{{ parent() }}
{% endif %}
{% endblock %}

{% block subheader_search_outer %}{% endblock %}

{% block subheader_menu %}
<button class=\"menu-item\" id=\"edit-widget-button\"><i class=\"fa-regular fa-pen-to-square\"></i> Edit Widget</button>
{% endblock %}




{% block javascript %}
{{ parent() }}
<script src=\"{{ '@dashboard/dist/index.js' | assets | url }}\"></script>
{% endblock %}




{% block content %}
<div class=\"pb-5\" id=\"root\"></div>
{% endblock %}", "@dashboard/Admin/index.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\Dashboard\\Views\\Admin\\index.html.twig");
    }
}
