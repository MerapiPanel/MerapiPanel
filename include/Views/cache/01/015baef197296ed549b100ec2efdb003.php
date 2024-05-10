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

/* @setting/base.html.twig */
class __TwigTemplate_cf307675514f9b96359ad27776cde77c extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'header_title' => [$this, 'block_header_title'],
            'subheader_outer' => [$this, 'block_subheader_outer'],
            'breadcrumb_item' => [$this, 'block_breadcrumb_item'],
            'content' => [$this, 'block_content'],
            'form' => [$this, 'block_form'],
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
        $this->parent = $this->loadTemplate("@panel/base.html.twig", "@setting/base.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_header_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "General Settings";
    }

    // line 4
    public function block_subheader_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 7
    public function block_breadcrumb_item($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 8
        echo "<li class=\"breadcrumb-item\"><a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/"), "html", null, true);
        echo "\">Home</a></li>
<li class=\"breadcrumb-item active\" aria-current=\"page\">Settings</li>
";
    }

    // line 22
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 23
        echo "<div class=\"container d-flex flex-column flex-grow-1\">
    <form class=\"d-flex flex-column pb-3 flex-grow-1 needs-validation\" id=\"form-setting\" method=\"post\" enctype=\"multipart/form-data\"
        action=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Setting/saveConfig")), "html", null, true);
        echo "\">

        <div class=\"flex-grow-1\">
            ";
        // line 28
        $this->displayBlock('form', $context, $blocks);
        // line 33
        echo "        </div>
        <div class=\"d-flex mt-5\">
            <button type=\"submit\" class=\"ms-auto btn ";
        // line 35
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 35), "getRoles", [], "any", false, false, false, 35), "isAllowed", [1], "method", false, false, false, 35) == false)) {
            echo "btn-outline-secondary";
        } else {
            echo "btn-outline-primary";
        }
        echo " px-5\" ";
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 35), "getRoles", [], "any", false, false, false, 35), "isAllowed", [1], "method", false, false, false, 35) == false)) {
            echo "disabled";
        }
        echo ">
                save
            </button>
        </div>
    </form>
</div>
";
    }

    // line 28
    public function block_form($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 29
        echo "            <div class=\"w-100 h-100 d-flex justify-content-center align-items-center\" style=\"min-height: 200px;\">
                EMPTY TEMPLATE
            </div>
            ";
    }

    // line 42
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 43
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "

<script src=\"";
        // line 45
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@setting/dist/main.js")), "html", null, true);
        echo "\"></script>

";
    }

    // line 13
    public function macro_process($__content__ = null, $__moduleName__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "content" => $__content__,
            "moduleName" => $__moduleName__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 15
            echo ($context["content"] ?? null);
            echo "
<div class=\"d-none\">
    <input type=\"hidden\" name=\"setting_token\" value=\"";
            // line 17
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "setting", [], "any", false, false, false, 17), "token", [($context["content"] ?? null), ($context["moduleName"] ?? null)], "method", false, false, false, 17), "html", null, true);
            echo "\">
</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@setting/base.html.twig";
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
        return array (  161 => 17,  156 => 15,  142 => 13,  135 => 45,  130 => 43,  126 => 42,  119 => 29,  115 => 28,  96 => 35,  92 => 33,  90 => 28,  84 => 25,  80 => 23,  76 => 22,  68 => 8,  64 => 7,  58 => 4,  51 => 2,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"@panel/base.html.twig\" %}
{% block header_title %}General Settings{% endblock %}

{% block subheader_outer %}{% endblock %}


{% block breadcrumb_item %}
<li class=\"breadcrumb-item\"><a href=\"{{ '/' | access_path }}\">Home</a></li>
<li class=\"breadcrumb-item active\" aria-current=\"page\">Settings</li>
{% endblock %}


{% macro process(content, moduleName = null) %}
{# Manipulate or display the captured block content here #}
{{ content | raw }}
<div class=\"d-none\">
    <input type=\"hidden\" name=\"setting_token\" value=\"{{ api.setting.token(content, moduleName) }}\">
</div>
{% endmacro %}


{% block content %}
<div class=\"container d-flex flex-column flex-grow-1\">
    <form class=\"d-flex flex-column pb-3 flex-grow-1 needs-validation\" id=\"form-setting\" method=\"post\" enctype=\"multipart/form-data\"
        action=\"{{ '/api/Setting/saveConfig' | access_path | url }}\">

        <div class=\"flex-grow-1\">
            {% block form %}
            <div class=\"w-100 h-100 d-flex justify-content-center align-items-center\" style=\"min-height: 200px;\">
                EMPTY TEMPLATE
            </div>
            {% endblock %}
        </div>
        <div class=\"d-flex mt-5\">
            <button type=\"submit\" class=\"ms-auto btn {% if api.Setting.getRoles.isAllowed(1) == false %}btn-outline-secondary{% else %}btn-outline-primary{% endif %} px-5\" {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %}>
                save
            </button>
        </div>
    </form>
</div>
{% endblock %}
{% block javascript %}
{{ parent() }}

<script src=\"{{ '@setting/dist/main.js' | assets | url }}\"></script>

{% endblock %}", "@setting/base.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\Setting\\Views\\base.html.twig");
    }
}
