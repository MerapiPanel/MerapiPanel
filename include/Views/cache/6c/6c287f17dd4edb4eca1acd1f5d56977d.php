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

/* @panel/navigation.html.twig */
class __TwigTemplate_7f930f0a17986f80c725b50d425909c2 extends Template
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
        $macros["_self"] = $this->macros["_self"] = $this;
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 22
        echo "
<nav class=\"sidebar\" id=\"sidebar\">
    <div class=\"sidebar-container\">
        <button class=\"sidebar-close\" onclick=\"closeNav()\"></button>
        <div class=\"sidebar-header\">
            <img width=\"110\" height=\"65\" src=\"";
        // line 27
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@panel/assets/logo.svg"), "html", null, true);
        echo "\" alt=\"\">
        </div>

        <ul class=\"nav-menu\">
            ";
        // line 31
        echo twig_call_macro($macros["_self"], "macro_render_menu", [twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Panel", [], "any", false, false, false, 31), "getMenu", [], "any", false, false, false, 31)], 31, $context, $this->getSourceContext());
        echo "
        </ul>
    </div>
</nav>

<script>
    function openNav() {
        document.getElementById(\"sidebar\").classList.add(\"open\");
    }

    function closeNav() {
        document.getElementById(\"sidebar\").classList.remove(\"open\");
    }
</script>";
    }

    // line 1
    public function macro_render_menu($__menu__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "menu" => $__menu__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 2
            echo "
";
            // line 3
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["menu"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 4
                echo "<li class=\"nav-item";
                echo ((twig_get_attribute($this->env, $this->source, $context["item"], "active", [], "any", false, false, false, 4)) ? (" active") : (""));
                echo "\">
    <a ";
                // line 5
                if (twig_get_attribute($this->env, $this->source, $context["item"], "link", [], "any", false, false, false, 5)) {
                    echo "href=\"";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "link", [], "any", false, false, false, 5), "html", null, true);
                    echo "\" ";
                }
                echo ">
        ";
                // line 6
                if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Panel", [], "any", false, false, false, 6), "isFile", [twig_get_attribute($this->env, $this->source, $context["item"], "icon", [], "any", false, false, false, 6)], "method", false, false, false, 6)) {
                    // line 7
                    echo "        <img width=\"14\" height=\"14\" class=\"object-fit-cover\" src=\"";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Panel", [], "any", false, false, false, 7), "getAbsolutePath", [twig_get_attribute($this->env, $this->source, $context["item"], "icon", [], "any", false, false, false, 7)], "method", false, false, false, 7), "html", null, true);
                    echo "\" alt=\"Icon\">
        ";
                } else {
                    // line 9
                    echo "        ";
                    echo twig_get_attribute($this->env, $this->source, $context["item"], "icon", [], "any", false, false, false, 9);
                    echo "
        ";
                }
                // line 11
                echo "        ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, false, 11), "html", null, true);
                echo "
    </a>
    ";
                // line 13
                if (twig_get_attribute($this->env, $this->source, $context["item"], "childs", [], "any", false, false, false, 13)) {
                    // line 14
                    echo "    <ul class=\"nav-menu\">
        ";
                    // line 15
                    echo twig_call_macro($macros["_self"], "macro_render_menu", [twig_get_attribute($this->env, $this->source, $context["item"], "childs", [], "any", false, false, false, 15)], 15, $context, $this->getSourceContext());
                    echo "
    </ul>
    ";
                }
                // line 18
                echo "
</li>
";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;

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
        return "@panel/navigation.html.twig";
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
        return array (  134 => 18,  128 => 15,  125 => 14,  123 => 13,  117 => 11,  111 => 9,  105 => 7,  103 => 6,  95 => 5,  90 => 4,  86 => 3,  83 => 2,  70 => 1,  52 => 31,  45 => 27,  38 => 22,);
    }

    public function getSourceContext()
    {
        return new Source("{% macro render_menu(menu) %}

{% for item in menu %}
<li class=\"nav-item{{ item.active ? ' active' : '' }}\">
    <a {% if item.link %}href=\"{{ item.link }}\" {% endif %}>
        {% if api.Panel.isFile(item.icon) %}
        <img width=\"14\" height=\"14\" class=\"object-fit-cover\" src=\"{{ api.Panel.getAbsolutePath(item.icon) }}\" alt=\"Icon\">
        {% else %}
        {{ item.icon | raw }}
        {% endif %}
        {{item.name}}
    </a>
    {% if item.childs %}
    <ul class=\"nav-menu\">
        {{ _self.render_menu(item.childs) }}
    </ul>
    {% endif %}

</li>
{% endfor %}
{% endmacro %}

<nav class=\"sidebar\" id=\"sidebar\">
    <div class=\"sidebar-container\">
        <button class=\"sidebar-close\" onclick=\"closeNav()\"></button>
        <div class=\"sidebar-header\">
            <img width=\"110\" height=\"65\" src=\"{{ '@panel/assets/logo.svg' | assets }}\" alt=\"\">
        </div>

        <ul class=\"nav-menu\">
            {{ _self.render_menu(api.Panel.getMenu) }}
        </ul>
    </div>
</nav>

<script>
    function openNav() {
        document.getElementById(\"sidebar\").classList.add(\"open\");
    }

    function closeNav() {
        document.getElementById(\"sidebar\").classList.remove(\"open\");
    }
</script>", "@panel/navigation.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\Panel\\Views\\navigation.html.twig");
    }
}
