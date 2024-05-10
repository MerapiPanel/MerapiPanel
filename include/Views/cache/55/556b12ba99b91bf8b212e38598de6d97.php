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

/* @setting/Admin/role.html.twig */
class __TwigTemplate_5c9c59c2d5c177f85cfba0afcd3328c1 extends Template
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
            'stylesheet' => [$this, 'block_stylesheet'],
            'subheader_outer' => [$this, 'block_subheader_outer'],
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
        $this->parent = $this->loadTemplate("@panel/base.html.twig", "@setting/Admin/role.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_header_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "Role Manager";
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
<li class=\"breadcrumb-item active\" aria-current=\"page\">Role</li>
";
    }

    // line 9
    public function block_stylesheet($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 10
        $this->displayParentBlock("stylesheet", $context, $blocks);
        echo "
<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 26px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: \"\";
        height: 22px;
        width: 22px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    input:disabled+.slider {
        background-color: #e6e6e6;
        opacity: 0.6;
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .collapse-helper {
        padding: 5px 10px;
    }

    .collapse-helper:hover {
        cursor: pointer;
        background: #2196F3;
        color: #fff;
    }
</style>
";
    }

    // line 93
    public function block_subheader_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 96
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 97
        echo "<section class=\"p-3\">
    <ul class=\"list-group list-group-flush\">
        ";
        // line 99
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["rolestack"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["x"] => $context["item"]) {
            // line 100
            echo "        <li class=\"list-group-item role-item\" id=\"role-";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, false, 100), "html", null, true);
            echo "\">
            <h4 class=\"mb-2 fs-5 mb-2 collapse-helper\"><i class=\"fa-solid fa-user-tag\"></i> ";
            // line 101
            echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_ucfirst(twig_get_attribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, false, 101)), "html", null, true);
            echo " <i
                    class=\"fa-solid fa-angle-up\"></i></h4>
            <div class=\"collapse ps-3\" id=\"collapse-";
            // line 103
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, false, 103), "html", null, true);
            echo "\">
                ";
            // line 104
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["item"], "roles", [], "any", false, false, false, 104));
            foreach ($context['_seq'] as $context["_key"] => $context["itemstack"]) {
                // line 105
                echo "                <h5 class=\"fs-6\"><i class=\"fa-regular fa-square-full\"></i> ";
                echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_ucfirst((((twig_get_attribute($this->env, $this->source, $context["itemstack"], "name", [], "any", true, true, false, 105) &&  !(null === twig_get_attribute($this->env, $this->source, $context["itemstack"], "name", [], "any", false, false, false, 105)))) ? (twig_get_attribute($this->env, $this->source, $context["itemstack"], "name", [], "any", false, false, false, 105)) : (twig_get_attribute($this->env, $this->source, $context["itemstack"], "module", [], "any", false, false, false, 105)))), "html", null, true);
                // line 106
                echo "</h5>
                <ul class=\"list-group list-group-flush border-start ms-1 mb-4\">
                    ";
                // line 108
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["itemstack"], "roles", [], "any", false, false, false, 108));
                foreach ($context['_seq'] as $context["_key"] => $context["role"]) {
                    // line 109
                    echo "                    <li class=\"list-group-item d-flex align-items-end\">
                        <div>
                            ";
                    // line 111
                    (((twig_get_attribute($this->env, $this->source, $context["role"], "name", [], "any", true, true, false, 111) &&  !(null === twig_get_attribute($this->env, $this->source, $context["role"], "name", [], "any", false, false, false, 111)))) ? (print (twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["role"], "name", [], "any", false, false, false, 111), "html", null, true))) : (print ("No Name")));
                    echo "
                            <small class=\"text-muted d-block\">";
                    // line 112
                    (((twig_get_attribute($this->env, $this->source, $context["role"], "description", [], "any", true, true, false, 112) &&  !(null === twig_get_attribute($this->env, $this->source, $context["role"], "description", [], "any", false, false, false, 112)))) ? (print (twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["role"], "description", [], "any", false, false, false, 112), "html", null, true))) : (print ("No Description")));
                    echo "</small>
                        </div>
                        <div class=\"ms-auto position-relative\">
                            <!-- Rounded switch -->
                            <label class=\"switch\">
                                <input data-role=\"";
                    // line 117
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, false, 117), "html", null, true);
                    echo "\" data-name=\"";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["itemstack"], "module", [], "any", false, false, false, 117), "html", null, true);
                    echo ".";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["role"], "id", [], "any", false, false, false, 117), "html", null, true);
                    echo "\" type=\"checkbox\" ";
                    if ((((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 117), "getRoles", [], "any", false, false, false, 117), "isAllowed", [2], "method", false, false, false, 117) == false) || (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Setting", [], "any", false, false, false, 117), "getRoles", [], "any", false, false, false, 117), "isAllowed", [2], "method", false, false, false, 117) == 0)) || ((twig_get_attribute($this->env, $this->source, $context["role"], "enable", [], "any", false, false, false, 117) == 0) || (twig_get_attribute($this->env, $this->source, $context["role"], "enable", [], "any", false, false, false, 117) == false)))) {
                        echo "disabled";
                    }
                    echo " ";
                    if (twig_get_attribute($this->env, $this->source, $context["role"], "value", [], "any", false, false, false, 117)) {
                        echo "checked";
                    }
                    echo ">
                                <span class=\"slider round\"></span>
                            </label>
                        </div>
                    </li>
                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['role'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 123
                echo "
                </ul>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['itemstack'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 126
            echo "            </div>
        </li>
        ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 129
            echo "        <div>No Role Found</div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['x'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 131
        echo "    </ul>
</section>

";
    }

    // line 137
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 138
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
<script>
    \$('.role-item').each(function (i) {
        const elcoll = \$(this).find('.collapse');
        elcoll[0].addEventListener('show.bs.collapse', function () {
            \$(this).parent().find('.fa-angle-up').removeClass('fa-angle-up').addClass('fa-angle-down');
        });
        elcoll[0].addEventListener('hide.bs.collapse', function () {
            \$(this).parent().find('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-up');
        });
        const collapse = new bootstrap.Collapse(elcoll[0], {
            toggle: i === 0
        });
        \$(this).find('.collapse-helper').on('click', function () {
            collapse.toggle();
        });
    });

    \$('input[type=\"checkbox\"]').on('change', function () {
        const role = \$(this).data('role');
        const name = \$(this).data('name');
        const val = \$(this).is(':checked') ? 1 : 0;

        if (!role || !name) return;

        __.http.post(\"";
        // line 163
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Setting/updateRole"), "html", null, true);
        echo "\", {
            role: role,
            name: name,
            value: val
        })
            .then(function (res) {
                if (res.status) {
                    __.toast.success(res.message);
                } else {
                    __.toast(res.message || 'Failed to update role', 1, 'text-primary');
                }
            })
            .catch(function (err) {
                __.toast(err.message || 'Failed to update role', 5, 'text-danger');
            });
    });
</script>
";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@setting/Admin/role.html.twig";
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
        return array (  309 => 163,  281 => 138,  277 => 137,  270 => 131,  263 => 129,  256 => 126,  248 => 123,  224 => 117,  216 => 112,  212 => 111,  208 => 109,  204 => 108,  200 => 106,  197 => 105,  193 => 104,  189 => 103,  184 => 101,  179 => 100,  174 => 99,  170 => 97,  166 => 96,  160 => 93,  75 => 10,  71 => 9,  62 => 4,  58 => 3,  51 => 2,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"@panel/base.html.twig\" %}
{% block header_title %}Role Manager{% endblock %}
{% block breadcrumb_item %}
<li class=\"breadcrumb-item\"><a href=\"{{ '/' | access_path }}\">Home</a></li>
<li class=\"breadcrumb-item\">Settings</li>
<li class=\"breadcrumb-item active\" aria-current=\"page\">Role</li>
{% endblock %}

{% block stylesheet %}
{{ parent() }}
<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 26px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: \"\";
        height: 22px;
        width: 22px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    input:disabled+.slider {
        background-color: #e6e6e6;
        opacity: 0.6;
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .collapse-helper {
        padding: 5px 10px;
    }

    .collapse-helper:hover {
        cursor: pointer;
        background: #2196F3;
        color: #fff;
    }
</style>
{% endblock %}


{% block subheader_outer %}{% endblock %}


{% block content %}
<section class=\"p-3\">
    <ul class=\"list-group list-group-flush\">
        {% for x, item in rolestack %}
        <li class=\"list-group-item role-item\" id=\"role-{{ item.name }}\">
            <h4 class=\"mb-2 fs-5 mb-2 collapse-helper\"><i class=\"fa-solid fa-user-tag\"></i> {{ item.name | ucfirst }} <i
                    class=\"fa-solid fa-angle-up\"></i></h4>
            <div class=\"collapse ps-3\" id=\"collapse-{{ item.name }}\">
                {% for itemstack in item.roles %}
                <h5 class=\"fs-6\"><i class=\"fa-regular fa-square-full\"></i> {{ (itemstack.name ?? itemstack.module) |
                    ucfirst }}</h5>
                <ul class=\"list-group list-group-flush border-start ms-1 mb-4\">
                    {% for role in itemstack.roles %}
                    <li class=\"list-group-item d-flex align-items-end\">
                        <div>
                            {{ role.name ?? 'No Name' }}
                            <small class=\"text-muted d-block\">{{ role.description ?? 'No Description' }}</small>
                        </div>
                        <div class=\"ms-auto position-relative\">
                            <!-- Rounded switch -->
                            <label class=\"switch\">
                                <input data-role=\"{{ item.name }}\" data-name=\"{{ itemstack.module }}.{{ role.id }}\" type=\"checkbox\" {% if (api.Setting.getRoles.isAllowed(2) == false or api.Setting.getRoles.isAllowed(2) == 0) or (role.enable == 0 or role.enable == false) %}disabled{% endif %} {% if role.value %}checked{% endif %}>
                                <span class=\"slider round\"></span>
                            </label>
                        </div>
                    </li>
                    {% endfor %}

                </ul>
                {% endfor %}
            </div>
        </li>
        {% else %}
        <div>No Role Found</div>
        {% endfor %}
    </ul>
</section>

{% endblock %}


{% block javascript %}
{{ parent() }}
<script>
    \$('.role-item').each(function (i) {
        const elcoll = \$(this).find('.collapse');
        elcoll[0].addEventListener('show.bs.collapse', function () {
            \$(this).parent().find('.fa-angle-up').removeClass('fa-angle-up').addClass('fa-angle-down');
        });
        elcoll[0].addEventListener('hide.bs.collapse', function () {
            \$(this).parent().find('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-up');
        });
        const collapse = new bootstrap.Collapse(elcoll[0], {
            toggle: i === 0
        });
        \$(this).find('.collapse-helper').on('click', function () {
            collapse.toggle();
        });
    });

    \$('input[type=\"checkbox\"]').on('change', function () {
        const role = \$(this).data('role');
        const name = \$(this).data('name');
        const val = \$(this).is(':checked') ? 1 : 0;

        if (!role || !name) return;

        __.http.post(\"{{ '/api/Setting/updateRole' | access_path }}\", {
            role: role,
            name: name,
            value: val
        })
            .then(function (res) {
                if (res.status) {
                    __.toast.success(res.message);
                } else {
                    __.toast(res.message || 'Failed to update role', 1, 'text-primary');
                }
            })
            .catch(function (err) {
                __.toast(err.message || 'Failed to update role', 5, 'text-danger');
            });
    });
</script>
{% endblock %}", "@setting/Admin/role.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\Setting\\Views\\Admin\\role.html.twig");
    }
}
