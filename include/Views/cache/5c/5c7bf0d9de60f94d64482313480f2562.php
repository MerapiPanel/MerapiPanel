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

/* @contact/Admin/template.html.twig */
class __TwigTemplate_f0aa5931dcdee87dc70353401335414d extends Template
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
            'subheader_search' => [$this, 'block_subheader_search'],
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
        // line 33
        $context["total"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Contact", [], "any", false, false, false, 33), "template", [], "any", false, false, false, 33), "count", [], "any", false, false, false, 33);
        // line 1
        $this->parent = $this->loadTemplate("@panel/base.html.twig", "@contact/Admin/template.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_header_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "Contact Template";
    }

    // line 8
    public function block_breadcrumb_item($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 9
        echo "<li class=\"breadcrumb-item\"><a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/"), "html", null, true);
        echo "\">Home</a></li>
<li class=\"breadcrumb-item\"><a href=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/contact"), "html", null, true);
        echo "\">Contact</a></li>
<li class=\"breadcrumb-item active\" aria-current=\"page\">Template</li>
";
    }

    // line 15
    public function block_subheader_search($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 16
        echo "<select class=\"form-select border-0\" name=\"filter\" id=\"filter-contact\" style=\"box-shadow: none;\">
\t<option value=\"\">-- Filter --</option>
\t<option value=\"phone\">Phone</option>
\t<option value=\"email\">Email</option>
\t<option value=\"whatsapp\">Whatsapp</option>
</select>
";
    }

    // line 26
    public function block_subheader_menu($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 27
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Contact", [], "any", false, false, false, 27), "getRoles", [], "any", false, false, false, 27), "isAllowed", [4], "method", false, false, false, 27)) {
            // line 28
            echo "<a class=\"menu-item\" href=\"";
            echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/contact/template/add"), "html", null, true);
            echo "\">Add Template</a>
";
        }
    }

    // line 35
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 36
        echo "
<div class=\"row\">
\t<div class=\"col-12 col-lg-7\">
\t\t<ul class=\"list-group list-group-flush px-2 py-1\" id=\"template-list\">
\t\t\t";
        // line 40
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(1, ($context["total"] ?? null)));
        foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
            // line 41
            echo "\t\t\t<li class=\"list-group-item placeholder-glow\">
\t\t\t\t<span class=\"placeholder bg-primary bg-opacity-75 col-4\"></span>
\t\t\t\t<div class=\"placeholder bg-secondary bg-opacity-75 col-8\"></div>
\t\t\t</li>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 46
        echo "\t\t</ul>
\t</div>
</div>
";
    }

    // line 52
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 53
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
<script>
\t__.Contact.Template = \"CreateProperty\";
\t__.Contact.on(\"property:Template\", function () {
\t\t__.Contact.Template.fetchURL = \"";
        // line 57
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Contact/Template/fetchAll"), "html", null, true);
        echo "\";
\t})
\t
</script>
<script src=\"";
        // line 61
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@contact/dist/template.js"), "html", null, true);
        echo "?v=";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fn_time(), "html", null, true);
        echo "\"></script>
<script>
\tconst template =__.Contact.Template;
\ttemplate.el = \$('#template-list');
\ttemplate.render.call(\$('#template-list')[0]);
\ttemplate.on('edit', function (e, item) {
\t\tif (!item.id) {
\t\t\t__.toast('Invalid template', 5, 'text-danger');
\t\t\treturn;
\t\t}
\t\twindow.location.href = \"";
        // line 71
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/contact/template/edit"), "html", null, true);
        echo "/\" + item.id
\t\te.preventDefault();
\t\te.stopImmediatePropagation();
\t});
\ttemplate.on('delete', function (e, item) {
\t\tif (!item.id) {
\t\t\t__.toast('Invalid template', 5, 'text-danger');
\t\t\treturn;
\t\t}
\t\treturn new Promise(function (resolve, reject) {
\t\t\t__.dialog.confirm('Are you sure?', `Are you sure you want to delete <b>\${item.name}</b>?`)
\t\t\t\t.then(function () {
\t\t\t\t\t__.http.post(\"";
        // line 83
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Contact/Template/delete"), "html", null, true);
        echo "\", { id: item.id })
\t\t\t\t\t\t.then(function (response) {
\t\t\t\t\t\t\tresolve();
\t\t\t\t\t\t})
\t\t\t\t\t\t.catch(function (error) {
\t\t\t\t\t\t\treject();
\t\t\t\t\t\t});
\t\t\t\t})
\t\t\t\t.catch(function () {
\t\t\t\t\treject();
\t\t\t\t});
\t\t});
\t});
\t\$('#filter-contact').on('change', function () {
\t\ttemplate.filter = \$(this).val();
\t\ttemplate.render();
\t})
</script>
";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@contact/Admin/template.html.twig";
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
        return array (  184 => 83,  169 => 71,  154 => 61,  147 => 57,  140 => 53,  136 => 52,  129 => 46,  119 => 41,  115 => 40,  109 => 36,  105 => 35,  97 => 28,  95 => 27,  91 => 26,  81 => 16,  77 => 15,  70 => 10,  65 => 9,  61 => 8,  54 => 5,  49 => 1,  47 => 33,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"@panel/base.html.twig\" %}



{% block header_title %}Contact Template{% endblock %}


{% block breadcrumb_item %}
<li class=\"breadcrumb-item\"><a href=\"{{ '/' | access_path }}\">Home</a></li>
<li class=\"breadcrumb-item\"><a href=\"{{ '/contact' | access_path }}\">Contact</a></li>
<li class=\"breadcrumb-item active\" aria-current=\"page\">Template</li>
{% endblock %}


{% block subheader_search %}
<select class=\"form-select border-0\" name=\"filter\" id=\"filter-contact\" style=\"box-shadow: none;\">
\t<option value=\"\">-- Filter --</option>
\t<option value=\"phone\">Phone</option>
\t<option value=\"email\">Email</option>
\t<option value=\"whatsapp\">Whatsapp</option>
</select>
{% endblock %}



{% block subheader_menu %}
{% if api.Contact.getRoles.isAllowed(4) %}
<a class=\"menu-item\" href=\"{{ '/contact/template/add' | access_path }}\">Add Template</a>
{% endif %}
{% endblock %}


{% set total = api.Contact.template.count %}

{% block content %}

<div class=\"row\">
\t<div class=\"col-12 col-lg-7\">
\t\t<ul class=\"list-group list-group-flush px-2 py-1\" id=\"template-list\">
\t\t\t{% for i in 1..total %}
\t\t\t<li class=\"list-group-item placeholder-glow\">
\t\t\t\t<span class=\"placeholder bg-primary bg-opacity-75 col-4\"></span>
\t\t\t\t<div class=\"placeholder bg-secondary bg-opacity-75 col-8\"></div>
\t\t\t</li>
\t\t\t{% endfor %}
\t\t</ul>
\t</div>
</div>
{% endblock %}


{% block javascript %}
{{ parent() }}
<script>
\t__.Contact.Template = \"CreateProperty\";
\t__.Contact.on(\"property:Template\", function () {
\t\t__.Contact.Template.fetchURL = \"{{ '/api/Contact/Template/fetchAll' | access_path }}\";
\t})
\t
</script>
<script src=\"{{ '@contact/dist/template.js' | assets }}?v={{ time() }}\"></script>
<script>
\tconst template =__.Contact.Template;
\ttemplate.el = \$('#template-list');
\ttemplate.render.call(\$('#template-list')[0]);
\ttemplate.on('edit', function (e, item) {
\t\tif (!item.id) {
\t\t\t__.toast('Invalid template', 5, 'text-danger');
\t\t\treturn;
\t\t}
\t\twindow.location.href = \"{{ '/contact/template/edit' | access_path }}/\" + item.id
\t\te.preventDefault();
\t\te.stopImmediatePropagation();
\t});
\ttemplate.on('delete', function (e, item) {
\t\tif (!item.id) {
\t\t\t__.toast('Invalid template', 5, 'text-danger');
\t\t\treturn;
\t\t}
\t\treturn new Promise(function (resolve, reject) {
\t\t\t__.dialog.confirm('Are you sure?', `Are you sure you want to delete <b>\${item.name}</b>?`)
\t\t\t\t.then(function () {
\t\t\t\t\t__.http.post(\"{{ '/api/Contact/Template/delete' | access_path }}\", { id: item.id })
\t\t\t\t\t\t.then(function (response) {
\t\t\t\t\t\t\tresolve();
\t\t\t\t\t\t})
\t\t\t\t\t\t.catch(function (error) {
\t\t\t\t\t\t\treject();
\t\t\t\t\t\t});
\t\t\t\t})
\t\t\t\t.catch(function () {
\t\t\t\t\treject();
\t\t\t\t});
\t\t});
\t});
\t\$('#filter-contact').on('change', function () {
\t\ttemplate.filter = \$(this).val();
\t\ttemplate.render();
\t})
</script>
{% endblock %}", "@contact/Admin/template.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\Contact\\Views\\Admin\\template.html.twig");
    }
}
