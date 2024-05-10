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

/* @setting/Admin/config.html.twig */
class __TwigTemplate_d8c4a3bcd09940ee922e4a8400ae55df extends Template
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
            'form' => [$this, 'block_form'],
            'javascript' => [$this, 'block_javascript'],
        ];
        $macros["_self"] = $this->macros["_self"] = $this;
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "@setting/base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 175
        ob_start();
        // line 176
        echo twig_call_macro($macros["_self"], "macro_render_item", [($context["configs"] ?? null)], 176, $context, $this->getSourceContext());
        echo "
";
        $context["form_setting"] = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 1
        $this->parent = $this->loadTemplate("@setting/base.html.twig", "@setting/Admin/config.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_header_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "Configuration";
    }

    // line 3
    public function block_breadcrumb_item($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "<li class=\"breadcrumb-item\"><a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/"), "html", null, true);
        echo "\">Home</a></li>
<li class=\"breadcrumb-item\">Settings</li>
<li class=\"breadcrumb-item\">Configuration</li>
<li class=\"breadcrumb-item active\" aria-current=\"page\">";
        // line 7
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_ucfirst(($context["module_name"] ?? null)), "html", null, true);
        echo "</li>
";
    }

    // line 181
    public function block_form($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 182
        echo "<div class=\"row p-2 p-lg-4\">
    <div class=\"col-12 col-lg-7\">
        ";
        // line 184
        echo twig_call_macro($macros["_self"], "macro_process", [($context["form_setting"] ?? null), ($context["module_name"] ?? null)], 184, $context, $this->getSourceContext());
        echo "
    </div>
</div>
";
    }

    // line 191
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 192
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
<script>

    \$('.config-checkbox').each(function() {

        const \$input = \$(this).find('input[type=\"checkbox\"]').first();
        const \$children = \$(this).children('.config-item-children');
        if(!\$input.is(':checked')) {
            \$children.hide();
        }
        \$input.on('change', function() {
            if (\$(this).is(':checked')) {
                \$children.fadeIn(300);
            } else {
                \$children.fadeOut(300);
            }
        });

    });
</script>
";
    }

    // line 10
    public function macro_text_item($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 11
            echo "<div class=\"py-2 config-item config-text\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">";
            // line 12
            echo twig_escape_filter($this->env, (((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "label", [], "any", true, true, false, 12) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "label", [], "any", false, false, false, 12)))) ? (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "label", [], "any", false, false, false, 12)) : (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 12))), "html", null, true);
            echo " :</span>
        <input type=\"text\" class=\"form-control\" name=\"";
            // line 13
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 13), "html", null, true);
            echo "\"value=\"";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 13)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 13), "html", null, true);
            } else {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "default", [], "any", false, false, false, 13), "html", null, true);
            }
            echo "\" ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "readonly", [], "any", false, false, false, 13)) {
                echo "readonly";
            }
            echo " ";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 13), "getRoles", [], "any", false, false, false, 13), "isAllowed", [1], "method", false, false, false, 13) == false)) {
                echo "disabled";
            }
            echo " />
        ";
            // line 14
            if ((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 14) || twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "description", [], "any", false, false, false, 14))) {
                echo "<small class=\"form-text text-muted\">";
                echo twig_escape_filter($this->env, (((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "description", [], "any", true, true, false, 14) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "description", [], "any", false, false, false, 14)))) ? (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "description", [], "any", false, false, false, 14)) : (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 14))), "html", null, true);
                echo "</small>";
            }
            // line 15
            echo "    </label>
</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 19
    public function macro_number_item($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 20
            echo "<div class=\"py-2 config-item config-number\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">";
            // line 21
            echo twig_escape_filter($this->env, (((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "label", [], "any", true, true, false, 21) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "label", [], "any", false, false, false, 21)))) ? (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "label", [], "any", false, false, false, 21)) : (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 21))), "html", null, true);
            echo " :</span>
        <input type=\"number\" class=\"form-control\" name=\"";
            // line 22
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 22), "html", null, true);
            echo "\"value=\"";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 22)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 22), "html", null, true);
            } else {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "default", [], "any", false, false, false, 22), "html", null, true);
            }
            echo "\" ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "readonly", [], "any", false, false, false, 22)) {
                echo "readonly";
            }
            echo " ";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 22), "getRoles", [], "any", false, false, false, 22), "isAllowed", [1], "method", false, false, false, 22) == false)) {
                echo "disabled";
            }
            echo "/>
        ";
            // line 23
            if ((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 23) || twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "description", [], "any", false, false, false, 23))) {
                echo "<small class=\"form-text text-muted\">";
                echo twig_escape_filter($this->env, (((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "description", [], "any", true, true, false, 23) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "description", [], "any", false, false, false, 23)))) ? (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "description", [], "any", false, false, false, 23)) : (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 23))), "html", null, true);
                echo "</small>";
            }
            // line 24
            echo "    </label>
</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 29
    public function macro_select_item($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 30
            echo "<div class=\"py-2 config-item config-select\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">";
            // line 31
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 31), "html", null, true);
            echo " :</span>
        <select class=\"form-select\" name=\"";
            // line 32
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 32), "html", null, true);
            echo "\" ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "readonly", [], "any", false, false, false, 32)) {
                echo "readonly";
            }
            echo " ";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 32), "getRoles", [], "any", false, false, false, 32), "isAllowed", [1], "method", false, false, false, 32) == false)) {
                echo "disabled";
            }
            echo ">
            ";
            // line 33
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "options", [], "any", false, false, false, 33));
            foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
                // line 34
                echo "            <option value=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["option"], "value", [], "any", false, false, false, 34), "html", null, true);
                echo "\" ";
                if (((((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", true, true, false, 34) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 34)))) ? (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 34)) : (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "default", [], "any", false, false, false, 34))) == twig_get_attribute($this->env, $this->source, $context["option"], "value", [], "any", false, false, false, 34))) {
                    echo "selected";
                }
                echo ">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source,                 // line 35
$context["option"], "label", [], "any", false, false, false, 35), "html", null, true);
                echo "</option>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 37
            echo "        </select>
        ";
            // line 38
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 38)) {
                echo "<small class=\"form-text text-muted\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 38), "html", null, true);
                echo "</small>";
            }
            // line 39
            echo "    </label>
</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 45
    public function macro_textarea_item($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 46
            echo "<div class=\"py-2 config-item config-textarea\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">";
            // line 47
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 47), "html", null, true);
            echo " :</span>
        <textarea class=\"form-control\" name=\"";
            // line 48
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 48), "html", null, true);
            echo "\" ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "readonly", [], "any", false, false, false, 48)) {
                echo "readonly";
            }
            echo " ";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 48), "getRoles", [], "any", false, false, false, 48), "isAllowed", [1], "method", false, false, false, 48) == false)) {
                echo "disabled";
            }
            echo ">";
            echo twig_escape_filter($this->env, (((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", true, true, false, 48) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 48)))) ? (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 48)) : (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "default", [], "any", false, false, false, 48))), "html", null, true);
            echo "</textarea>
        ";
            // line 49
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 49)) {
                echo "<small class=\"form-text text-muted\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 49), "html", null, true);
                echo "</small>";
            }
            // line 50
            echo "    </label>
</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 56
    public function macro_checkbox_item($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 57
            echo "<div class=\"py-2 config-item config-checkbox\">
    <label class=\"form-check-label w-100\">
        <input type=\"checkbox\" class=\"form-check-input\" name=\"";
            // line 59
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 59), "html", null, true);
            echo "\" value=\"1\" ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 59)) {
                echo "checked";
            }
            echo " ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "readonly", [], "any", false, false, false, 59)) {
                echo "readonly";
            }
            echo " ";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 59), "getRoles", [], "any", false, false, false, 59), "isAllowed", [1], "method", false, false, false, 59) == false)) {
                echo "disabled";
            }
            echo "/>
        <span class=\"fw-semibold ms-2\">";
            // line 60
            echo twig_escape_filter($this->env, (((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "label", [], "any", true, true, false, 60) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "label", [], "any", false, false, false, 60)))) ? (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "label", [], "any", false, false, false, 60)) : (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 60))), "html", null, true);
            echo " </span>
        ";
            // line 61
            if ((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 61) || twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "description", [], "any", false, false, false, 61))) {
                echo "<small class=\"form-text text-muted d-block\">";
                echo twig_escape_filter($this->env, (((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", true, true, false, 61) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 61)))) ? (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 61)) : (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "description", [], "any", false, false, false, 61))), "html", null, true);
                echo "</small>";
            }
            // line 62
            echo "    </label>
    ";
            // line 63
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "children", [], "any", false, false, false, 63)) {
                // line 64
                echo "    <div class=\"collapse show ps-3 pt-2 ms-1 border-start config-item-children\" id=\"collapse-";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 64), "html", null, true);
                echo "\">
        ";
                // line 65
                echo twig_call_macro($macros["_self"], "macro_render_item", [twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "children", [], "any", false, false, false, 65)], 65, $context, $this->getSourceContext());
                echo "
    </div>
    ";
            }
            // line 68
            echo "</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 73
    public function macro_radio_item($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 74
            echo "<div class=\"py-2 config-item config-radio\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">";
            // line 75
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 75), "html", null, true);
            echo " :</span>
        ";
            // line 76
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "options", [], "any", false, false, false, 76));
            foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
                // line 77
                echo "        <div class=\"form-check\">
            <input type=\"radio\" class=\"form-check-input\" name=\"";
                // line 78
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 78), "html", null, true);
                echo "\" value=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["option"], "value", [], "any", false, false, false, 78), "html", null, true);
                echo "\" ";
                if ((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 78) == twig_get_attribute($this->env, $this->source, $context["option"], "value", [], "any", false, false, false, 78))) {
                    echo "checked";
                }
                echo " ";
                if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "readonly", [], "any", false, false, false, 78)) {
                    echo "readonly";
                }
                echo " ";
                if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 78), "getRoles", [], "any", false, false, false, 78), "isAllowed", [1], "method", false, false, false, 78) == false)) {
                    echo "disabled";
                }
                echo "/>
            <label class=\"form-check-label\">";
                // line 79
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["option"], "label", [], "any", false, false, false, 79), "html", null, true);
                echo "</label>
        </div>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 82
            echo "        ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 82)) {
                echo "<small class=\"form-text text-muted\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 82), "html", null, true);
                echo "</small>";
            }
            // line 83
            echo "    </label>
</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 89
    public function macro_color_item($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 90
            echo "<div class=\"py-2 config-item config-color\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">";
            // line 91
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 91), "html", null, true);
            echo " :</span>
        <input style=\"width: 50px; height: 50px; outline: none; border: none;\" type=\"color\" class=\"form-control p-0\" name=\"";
            // line 92
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 92), "html", null, true);
            echo "\" value=\"";
            echo twig_escape_filter($this->env, (((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", true, true, false, 92) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 92)))) ? (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 92)) : (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "default", [], "any", false, false, false, 92))), "html", null, true);
            echo "\" ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "readonly", [], "any", false, false, false, 92)) {
                echo "readonly";
            }
            echo " ";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 92), "getRoles", [], "any", false, false, false, 92), "isAllowed", [1], "method", false, false, false, 92) == false)) {
                echo "disabled";
            }
            echo "/>
        ";
            // line 93
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 93)) {
                echo "<small class=\"form-text text-muted\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 93), "html", null, true);
                echo "</small>";
            }
            // line 94
            echo "    </label>
</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 100
    public function macro_file_item($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 101
            echo "<div class=\"py-2 config-item config-file\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">";
            // line 102
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 102), "html", null, true);
            echo " :</span>
        <input type=\"file\" class=\"form-control\" name=\"";
            // line 103
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 103), "html", null, true);
            echo "\" ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "readonly", [], "any", false, false, false, 103)) {
                echo "readonly";
            }
            echo " ";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 103), "getRoles", [], "any", false, false, false, 103), "isAllowed", [1], "method", false, false, false, 103) == false)) {
                echo "disabled";
            }
            echo "/>
        ";
            // line 104
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 104)) {
                echo "<small class=\"form-text text-muted\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 104), "html", null, true);
                echo "</small>";
            }
            // line 105
            echo "    </label>
</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 111
    public function macro_image_item($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 112
            echo "<div class=\"py-2 config-item config-image\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">";
            // line 113
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 113), "html", null, true);
            echo " :</span>
        <input type=\"file\" class=\"form-control\" name=\"";
            // line 114
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 114), "html", null, true);
            echo "\" ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "readonly", [], "any", false, false, false, 114)) {
                echo "readonly";
            }
            echo " ";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 114), "getRoles", [], "any", false, false, false, 114), "isAllowed", [1], "method", false, false, false, 114) == false)) {
                echo "disabled";
            }
            echo "/>
        ";
            // line 115
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 115)) {
                echo "<small class=\"form-text text-muted\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 115), "html", null, true);
                echo "</small>";
            }
            // line 116
            echo "    </label>
</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 122
    public function macro_wysiwyg_item($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 123
            echo "<div class=\"py-2 config-item config-wysiwyg\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">";
            // line 124
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 124), "html", null, true);
            echo " :</span>
        <textarea class=\"form-control\" name=\"";
            // line 125
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 125), "html", null, true);
            echo "\" ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "readonly", [], "any", false, false, false, 125)) {
                echo "readonly";
            }
            echo " ";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 125), "getRoles", [], "any", false, false, false, 125), "isAllowed", [1], "method", false, false, false, 125) == false)) {
                echo "disabled";
            }
            echo ">";
            echo twig_escape_filter($this->env, (((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", true, true, false, 125) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 125)))) ? (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 125)) : (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "default", [], "any", false, false, false, 125))), "html", null, true);
            echo "</textarea>
        ";
            // line 126
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 126)) {
                echo "<small class=\"form-text text-muted\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 126), "html", null, true);
                echo "</small>";
            }
            // line 127
            echo "    </label>
</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 133
    public function macro_datetime_item($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 134
            echo "<div class=\"py-2 config-item config-datetime\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">";
            // line 135
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 135), "html", null, true);
            echo " :</span>
        <input type=\"datetime-local\" class=\"form-control\" name=\"";
            // line 136
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 136), "html", null, true);
            echo "\" value=\"";
            echo twig_escape_filter($this->env, (((twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", true, true, false, 136) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 136)))) ? (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "value", [], "any", false, false, false, 136)) : (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "default", [], "any", false, false, false, 136))), "html", null, true);
            echo "\" ";
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "readonly", [], "any", false, false, false, 136)) {
                echo "readonly";
            }
            echo " ";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 136), "getRoles", [], "any", false, false, false, 136), "isAllowed", [1], "method", false, false, false, 136) == false)) {
                echo "disabled";
            }
            echo "/>
        ";
            // line 137
            if (twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 137)) {
                echo "<small class=\"form-text text-muted\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["item"] ?? null), "help", [], "any", false, false, false, 137), "html", null, true);
                echo "</small>";
            }
            // line 138
            echo "    </label>
</div>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 143
    public function macro_render_item($__configs__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "configs" => $__configs__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 144
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["configs"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 145
                if ((twig_get_attribute($this->env, $this->source, $context["item"], "type", [], "any", false, false, false, 145) == "text")) {
                    // line 146
                    echo twig_call_macro($macros["_self"], "macro_text_item", [$context["item"]], 146, $context, $this->getSourceContext());
                    echo "
";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 147
$context["item"], "type", [], "any", false, false, false, 147) == "select")) {
                    // line 148
                    echo twig_call_macro($macros["_self"], "macro_select_item", [$context["item"]], 148, $context, $this->getSourceContext());
                    echo "
";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 149
$context["item"], "type", [], "any", false, false, false, 149) == "textarea")) {
                    // line 150
                    echo twig_call_macro($macros["_self"], "macro_textarea_item", [$context["item"]], 150, $context, $this->getSourceContext());
                    echo "
";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 151
$context["item"], "type", [], "any", false, false, false, 151) == "checkbox")) {
                    // line 152
                    echo twig_call_macro($macros["_self"], "macro_checkbox_item", [$context["item"]], 152, $context, $this->getSourceContext());
                    echo "
";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 153
$context["item"], "type", [], "any", false, false, false, 153) == "radio")) {
                    // line 154
                    echo twig_call_macro($macros["_self"], "macro_radio_item", [$context["item"]], 154, $context, $this->getSourceContext());
                    echo "
";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 155
$context["item"], "type", [], "any", false, false, false, 155) == "color")) {
                    // line 156
                    echo twig_call_macro($macros["_self"], "macro_color_item", [$context["item"]], 156, $context, $this->getSourceContext());
                    echo "
";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 157
$context["item"], "type", [], "any", false, false, false, 157) == "file")) {
                    // line 158
                    echo twig_call_macro($macros["_self"], "macro_file_item", [$context["item"]], 158, $context, $this->getSourceContext());
                    echo "
";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 159
$context["item"], "type", [], "any", false, false, false, 159) == "image")) {
                    // line 160
                    echo twig_call_macro($macros["_self"], "macro_image_item", [$context["item"]], 160, $context, $this->getSourceContext());
                    echo "
";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 161
$context["item"], "type", [], "any", false, false, false, 161) == "wysiwyg")) {
                    // line 162
                    echo twig_call_macro($macros["_self"], "macro_wysiwyg_item", [$context["item"]], 162, $context, $this->getSourceContext());
                    echo "
";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 163
$context["item"], "type", [], "any", false, false, false, 163) == "datetime")) {
                    // line 164
                    echo twig_call_macro($macros["_self"], "macro_datetime_item", [$context["item"]], 164, $context, $this->getSourceContext());
                    echo "
";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 165
$context["item"], "type", [], "any", false, false, false, 165) == "number")) {
                    // line 166
                    echo twig_call_macro($macros["_self"], "macro_number_item", [$context["item"]], 166, $context, $this->getSourceContext());
                    echo "
";
                } else {
                    // line 168
                    echo "<p>";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "type", [], "any", false, false, false, 168), "html", null, true);
                    echo " type not found</p>
";
                }
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
        return "@setting/Admin/config.html.twig";
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
        return array (  836 => 168,  831 => 166,  829 => 165,  825 => 164,  823 => 163,  819 => 162,  817 => 161,  813 => 160,  811 => 159,  807 => 158,  805 => 157,  801 => 156,  799 => 155,  795 => 154,  793 => 153,  789 => 152,  787 => 151,  783 => 150,  781 => 149,  777 => 148,  775 => 147,  771 => 146,  769 => 145,  765 => 144,  752 => 143,  741 => 138,  735 => 137,  721 => 136,  717 => 135,  714 => 134,  701 => 133,  690 => 127,  684 => 126,  670 => 125,  666 => 124,  663 => 123,  650 => 122,  639 => 116,  633 => 115,  621 => 114,  617 => 113,  614 => 112,  601 => 111,  590 => 105,  584 => 104,  572 => 103,  568 => 102,  565 => 101,  552 => 100,  541 => 94,  535 => 93,  521 => 92,  517 => 91,  514 => 90,  501 => 89,  490 => 83,  483 => 82,  474 => 79,  456 => 78,  453 => 77,  449 => 76,  445 => 75,  442 => 74,  429 => 73,  419 => 68,  413 => 65,  408 => 64,  406 => 63,  403 => 62,  397 => 61,  393 => 60,  377 => 59,  373 => 57,  360 => 56,  349 => 50,  343 => 49,  329 => 48,  325 => 47,  322 => 46,  309 => 45,  298 => 39,  292 => 38,  289 => 37,  281 => 35,  273 => 34,  269 => 33,  257 => 32,  253 => 31,  250 => 30,  237 => 29,  226 => 24,  220 => 23,  202 => 22,  198 => 21,  195 => 20,  182 => 19,  171 => 15,  165 => 14,  147 => 13,  143 => 12,  140 => 11,  127 => 10,  102 => 192,  98 => 191,  90 => 184,  86 => 182,  82 => 181,  76 => 7,  69 => 4,  65 => 3,  58 => 2,  53 => 1,  48 => 176,  46 => 175,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"@setting/base.html.twig\" %}
{% block header_title %}Configuration{% endblock %}
{% block breadcrumb_item %}
<li class=\"breadcrumb-item\"><a href=\"{{ '/' | access_path }}\">Home</a></li>
<li class=\"breadcrumb-item\">Settings</li>
<li class=\"breadcrumb-item\">Configuration</li>
<li class=\"breadcrumb-item active\" aria-current=\"page\">{{ module_name | ucfirst }}</li>
{% endblock %}

{% macro text_item(item) %}
<div class=\"py-2 config-item config-text\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">{{ item.label ?? item.name }} :</span>
        <input type=\"text\" class=\"form-control\" name=\"{{ item.name }}\"value=\"{% if item.value %}{{ item.value }}{% else %}{{ item.default }}{% endif %}\" {% if item.readonly %}readonly{% endif %} {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %} />
        {% if item.help or item.description %}<small class=\"form-text text-muted\">{{ item.description ?? item.help }}</small>{% endif %}
    </label>
</div>
{% endmacro %}

{% macro number_item(item) %}
<div class=\"py-2 config-item config-number\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">{{ item.label ?? item.name }} :</span>
        <input type=\"number\" class=\"form-control\" name=\"{{ item.name }}\"value=\"{% if item.value %}{{ item.value }}{% else %}{{ item.default }}{% endif %}\" {% if item.readonly %}readonly{% endif %} {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %}/>
        {% if item.help or item.description %}<small class=\"form-text text-muted\">{{ item.description ?? item.help }}</small>{% endif %}
    </label>
</div>
{% endmacro %}


{% macro select_item(item) %}
<div class=\"py-2 config-item config-select\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">{{ item.name }} :</span>
        <select class=\"form-select\" name=\"{{ item.name }}\" {% if item.readonly %}readonly{% endif %} {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %}>
            {% for option in item.options %}
            <option value=\"{{ option.value }}\" {% if (item.value ?? item.default)==option.value %}selected{% endif %}>{{
                option.label }}</option>
            {% endfor %}
        </select>
        {% if item.help %}<small class=\"form-text text-muted\">{{ item.help }}</small>{% endif %}
    </label>
</div>
{% endmacro %}



{% macro textarea_item(item) %}
<div class=\"py-2 config-item config-textarea\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">{{ item.name }} :</span>
        <textarea class=\"form-control\" name=\"{{ item.name }}\" {% if item.readonly %}readonly{% endif %} {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %}>{{ item.value ?? item.default }}</textarea>
        {% if item.help %}<small class=\"form-text text-muted\">{{ item.help }}</small>{% endif %}
    </label>
</div>
{% endmacro %}



{% macro checkbox_item(item) %}
<div class=\"py-2 config-item config-checkbox\">
    <label class=\"form-check-label w-100\">
        <input type=\"checkbox\" class=\"form-check-input\" name=\"{{ item.name }}\" value=\"1\" {% if item.value %}checked{% endif %} {% if item.readonly %}readonly{% endif %} {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %}/>
        <span class=\"fw-semibold ms-2\">{{ item.label ?? item.name }} </span>
        {% if item.help or item.description %}<small class=\"form-text text-muted d-block\">{{ item.help ?? item.description }}</small>{% endif %}
    </label>
    {% if item.children %}
    <div class=\"collapse show ps-3 pt-2 ms-1 border-start config-item-children\" id=\"collapse-{{ item.name }}\">
        {{ _self.render_item(item.children) }}
    </div>
    {% endif %}
</div>
{% endmacro %}



{% macro radio_item(item) %}
<div class=\"py-2 config-item config-radio\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">{{ item.name }} :</span>
        {% for option in item.options %}
        <div class=\"form-check\">
            <input type=\"radio\" class=\"form-check-input\" name=\"{{ item.name }}\" value=\"{{ option.value }}\" {% if item.value==option.value %}checked{% endif %} {% if item.readonly %}readonly{% endif %} {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %}/>
            <label class=\"form-check-label\">{{ option.label }}</label>
        </div>
        {% endfor %}
        {% if item.help %}<small class=\"form-text text-muted\">{{ item.help }}</small>{% endif %}
    </label>
</div>
{% endmacro %}



{% macro color_item(item) %}
<div class=\"py-2 config-item config-color\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">{{ item.name }} :</span>
        <input style=\"width: 50px; height: 50px; outline: none; border: none;\" type=\"color\" class=\"form-control p-0\" name=\"{{ item.name }}\" value=\"{{ item.value ?? item.default }}\" {% if item.readonly %}readonly{% endif %} {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %}/>
        {% if item.help %}<small class=\"form-text text-muted\">{{ item.help }}</small>{% endif %}
    </label>
</div>
{% endmacro %}



{% macro file_item(item) %}
<div class=\"py-2 config-item config-file\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">{{ item.name }} :</span>
        <input type=\"file\" class=\"form-control\" name=\"{{ item.name }}\" {% if item.readonly %}readonly{% endif %} {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %}/>
        {% if item.help %}<small class=\"form-text text-muted\">{{ item.help }}</small>{% endif %}
    </label>
</div>
{% endmacro %}



{% macro image_item(item) %}
<div class=\"py-2 config-item config-image\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">{{ item.name }} :</span>
        <input type=\"file\" class=\"form-control\" name=\"{{ item.name }}\" {% if item.readonly %}readonly{% endif %} {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %}/>
        {% if item.help %}<small class=\"form-text text-muted\">{{ item.help }}</small>{% endif %}
    </label>
</div>
{% endmacro %}



{% macro wysiwyg_item(item) %}
<div class=\"py-2 config-item config-wysiwyg\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">{{ item.name }} :</span>
        <textarea class=\"form-control\" name=\"{{ item.name }}\" {% if item.readonly %}readonly{% endif %} {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %}>{{ item.value ?? item.default }}</textarea>
        {% if item.help %}<small class=\"form-text text-muted\">{{ item.help }}</small>{% endif %}
    </label>
</div>
{% endmacro %}



{% macro datetime_item(item) %}
<div class=\"py-2 config-item config-datetime\">
    <label class=\"form-label w-100\"><span class=\"fw-semibold\">{{ item.name }} :</span>
        <input type=\"datetime-local\" class=\"form-control\" name=\"{{ item.name }}\" value=\"{{ item.value ?? item.default }}\" {% if item.readonly %}readonly{% endif %} {% if api.Setting.getRoles.isAllowed(1) == false %}disabled{% endif %}/>
        {% if item.help %}<small class=\"form-text text-muted\">{{ item.help }}</small>{% endif %}
    </label>
</div>
{% endmacro %}


{% macro render_item(configs) %}
{% for item in configs %}
{% if item.type == 'text' %}
{{ _self.text_item(item) }}
{% elseif item.type == 'select' %}
{{ _self.select_item(item) }}
{% elseif item.type == 'textarea' %}
{{ _self.textarea_item(item) }}
{% elseif item.type == 'checkbox' %}
{{ _self.checkbox_item(item) }}
{% elseif item.type == 'radio' %}
{{ _self.radio_item(item) }}
{% elseif item.type == 'color' %}
{{ _self.color_item(item) }}
{% elseif item.type == 'file' %}
{{ _self.file_item(item) }}
{% elseif item.type == 'image' %}
{{ _self.image_item(item) }}
{% elseif item.type == 'wysiwyg' %}
{{ _self.wysiwyg_item(item) }}
{% elseif item.type == 'datetime' %}
{{ _self.datetime_item(item) }}
{% elseif item.type == 'number' %}
{{ _self.number_item(item) }}
{% else %}
<p>{{ item.type }} type not found</p>
{% endif %}
{% endfor %}
{% endmacro %}



{% set form_setting %}
{{ _self.render_item(configs) }}
{% endset %}



{% block form %}
<div class=\"row p-2 p-lg-4\">
    <div class=\"col-12 col-lg-7\">
        {{ _self.process(form_setting, module_name) }}
    </div>
</div>
{% endblock %}



{% block javascript %}
{{ parent() }}
<script>

    \$('.config-checkbox').each(function() {

        const \$input = \$(this).find('input[type=\"checkbox\"]').first();
        const \$children = \$(this).children('.config-item-children');
        if(!\$input.is(':checked')) {
            \$children.hide();
        }
        \$input.on('change', function() {
            if (\$(this).is(':checked')) {
                \$children.fadeIn(300);
            } else {
                \$children.fadeOut(300);
            }
        });

    });
</script>
{% endblock %}", "@setting/Admin/config.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\Setting\\Views\\Admin\\config.html.twig");
    }
}
