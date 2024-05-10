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

/* @user/Admin/profile.html.twig */
class __TwigTemplate_91c9959b1322014d5bf212dc7d942a96 extends Template
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
        // line 15
        if ((null === ($context["user"] ?? null))) {
            // line 16
            $context["user"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Auth", [], "any", false, false, false, 16), "Session", [], "any", false, false, false, 16), "getUser", [], "any", false, false, false, 16);
            // line 17
            $context["user_sessions"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Auth", [], "any", false, false, false, 17), "Session", [], "any", false, false, false, 17), "getUserSessions", [], "any", false, false, false, 17);
        } else {
            // line 19
            $context["user_sessions"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Auth", [], "any", false, false, false, 19), "Session", [], "any", false, false, false, 19), "getUserSessions", [twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "id", [], "any", false, false, false, 19)], "method", false, false, false, 19);
        }
        // line 1
        $this->parent = $this->loadTemplate("@panel/base.html.twig", "@user/Admin/profile.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_header_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "Profile";
    }

    // line 5
    public function block_breadcrumb_item($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 6
        echo "<li class=\"breadcrumb-item\"><a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/"), "html", null, true);
        echo "\">Home</a></li>
";
        // line 7
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "User", [], "any", false, false, false, 7), "getRoles", [], "any", false, false, false, 7), "isAllowed", [1], "method", false, false, false, 7)) {
            echo "<li class=\"breadcrumb-item\"><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/users"), "html", null, true);
            echo "\">Users</a></li>
";
        }
        // line 9
        echo "<li class=\"breadcrumb-item active\" aria-current=\"page\">Profile</li>
";
    }

    // line 12
    public function block_subheader_outer($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 23
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 24
        echo "
";
        // line 25
        $context["device_icons"] = ["Windows" => "fa-desktop", "iPad" => "fa-tablet-screen-button", "iPhone" => "fa-mobile-screen-button", "Android Device" => "fa-mobile", "Linux" => "fa-laptop", "Macintosh" => "fa-laptop", "Unknown" => "fa-question"];
        // line 34
        echo "

<div class=\"row pb-5\">
    <div class=\"col-12\">
        <div class=\"bg-dark text-white position-relative\" style=\"min-height: 300px;\">
            <div class=\"position-absolute top-50 start-50 translate-middle text-center mt-4\">
                <img src=\"";
        // line 40
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "avatar", [], "any", false, false, false, 40), "html", null, true);
        echo "\" alt=\"\" class=\"rounded-circle object-fit-cover bg-light\"
                    style=\"width: 100px; height: 100px;box-shadow: 0 0 8px rgb(255 255 255 / 60%);\" id=\"avatar\">
                <h1 class=\"mt-3 fs-2\">";
        // line 42
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "name", [], "any", false, false, false, 42), "html", null, true);
        echo "</h1>
                <small class=\"d-block\">";
        // line 43
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "email", [], "any", false, false, false, 43), "html", null, true);
        echo "</small>
                <i>";
        // line 44
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_ucfirst(twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "role", [], "any", false, false, false, 44)), "html", null, true);
        echo " </i>
                ";
        // line 45
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "User", [], "any", false, false, false, 45), "getConfig", [], "any", false, false, false, 45), "get", ["profile.change_avatar"], "method", false, false, false, 45) && (twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "id", [], "any", false, false, false, 45) == twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Auth", [], "any", false, false, false, 45), "Session", [], "any", false, false, false, 45), "getUser", [], "any", false, false, false, 45), "id", [], "any", false, false, false, 45)))) {
            // line 46
            echo "                <button class=\"btn position-absolute end-0 top-0 text-primary\" type=\"button\" id=\"change-avatar\"><i class=\"fa-solid fa-pen\"></i></button>
                ";
        }
        // line 48
        echo "
            </div>
        </div>
    </div>
    <div class=\"px-3 px-lg-5 pt-3\">
        <h4 class=\"mt-3 fs-5 mb-1\">Sessions</h4>
        <ul class=\"list-group list-group-flush\">
            ";
        // line 55
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["user_sessions"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["session"]) {
            // line 56
            echo "            <li class=\"list-group-item ";
            if (twig_get_attribute($this->env, $this->source, $context["session"], "is_current", [], "any", false, false, false, 56)) {
                echo "bg-primary bg-opacity-10";
            }
            echo "\">
                <div>
                    <h3 class=\"mb-2\"><i class=\"fa fa-clock\"></i> ";
            // line 58
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["session"], "post_date", [], "any", false, false, false, 58), "d M Y H:i:s"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["session"], "time_ago", [], "any", false, false, false, 58), "html", null, true);
            echo "</h3>
                    <div class=\"ms-2\">
                        <p class=\"mb-0\"><i class=\"fa-solid ";
            // line 60
            echo twig_escape_filter($this->env, (($__internal_compile_0 = ($context["device_icons"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0[twig_get_attribute($this->env, $this->source, $context["session"], "device", [], "any", false, false, false, 60)] ?? null) : null), "html", null, true);
            echo "\"></i> ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["session"], "device", [], "any", false, false, false, 60), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["session"], "browser", [], "any", false, false, false, 60), "html", null, true);
            echo "</p>
                        ";
            // line 61
            if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Auth", [], "any", false, false, false, 61), "getConfig", [], "any", false, false, false, 61), "get", ["geo"], "method", false, false, false, 61)) {
                // line 62
                echo "                        <small class=\"text-muted\"><i class=\"fa fa-map-marker\"></i> ";
                (((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["session"], "data", [], "any", false, true, false, 62), "display_name", [], "any", true, true, false, 62) &&  !(null === twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["session"], "data", [], "any", false, true, false, 62), "display_name", [], "any", false, false, false, 62)))) ? (print (twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["session"], "data", [], "any", false, true, false, 62), "display_name", [], "any", false, false, false, 62), "html", null, true))) : (print ("Unknown")));
                echo "</small>
                        ";
            }
            // line 64
            echo "                    </div>

                </div>
            </li>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['session'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 69
        echo "        </ul>
    </div>
</div>

";
    }

    // line 75
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 76
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
";
        // line 77
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "User", [], "any", false, false, false, 77), "getConfig", [], "any", false, false, false, 77), "get", ["profile.change_avatar"], "method", false, false, false, 77) && (twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "id", [], "any", false, false, false, 77) == twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Auth", [], "any", false, false, false, 77), "Session", [], "any", false, false, false, 77), "getUser", [], "any", false, false, false, 77), "id", [], "any", false, false, false, 77)))) {
            // line 78
            echo "<script>
    \$(\"#change-avatar\").click(function () {
        \$(\"<input type='file' accept='image/*' />\").on(\"change\", function () {

            var file = this.files[0];
            // max size 1mb
            if (file.size > 1048576) {
                __.toast(\"Please select a file smaller than 1mb\", 5, 'text-danger');
                return;
            }
            __.http.post(\"";
            // line 88
            echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/User/uploadAvatar"), "html", null, true);
            echo "\", {
                avatar: file
            }).then((result) => {
                \$(\"#avatar\").attr('src', result.data);
            }).catch((err) => {
                __.toast(err.message || \"Something went wrong\", 5, 'text-danger');
            });
        }).click();
    });
    \$('#avatar').click(function () {
        let \$this = \$(this);
        const modal = __.modal.create(\"Avatar\", \$(`<img src=\"\${\$this.attr('src')}\" class=\"w-100\" />`));
        modal.el.find(\".modal-footer\").remove();
        modal.el.find(\".modal-header\").remove();
        modal.on(\"modal:hide\", function () {
            modal.el.remove();
        })
        if (!\$this.attr('src').startsWith('http')) {
            modal.el.find(\".modal-body\").append(
                \$(\"<button class='btn text-danger btn-sm position-absolute end-0 top-0'><i class='fa fa-trash'></i> Delete</button>\")
                    .click(function () {
                        __.http.get(\"";
            // line 109
            echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/User/deleteAvatar"), "html", null, true);
            echo "\")
                        .then((result) => {
                            \$(\"#avatar\").attr('src', result.data);
                            modal.hide();
                        }).catch((err) => {
                            __.toast(err.message || \"Something went wrong\", 5, 'text-danger');
                        });
                    })
            )
        }
        modal.show();
    });
</script>
";
        } else {
            // line 123
            echo "<script>
    \$('#avatar').click(function () {
        let \$this = \$(this);
        const modal = __.modal.create(\"Avatar\", \$(`<img src=\"\${\$this.attr('src')}\" class=\"w-100\" />`));
        modal.el.find(\".modal-footer\").remove();
        modal.el.find(\".modal-header\").remove();
        modal.on(\"modal:hide\", function () {
            modal.el.remove();
        })
        modal.show();
    });
</script>
";
        }
        // line 136
        echo "
";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@user/Admin/profile.html.twig";
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
        return array (  275 => 136,  260 => 123,  243 => 109,  219 => 88,  207 => 78,  205 => 77,  201 => 76,  197 => 75,  189 => 69,  179 => 64,  173 => 62,  171 => 61,  163 => 60,  156 => 58,  148 => 56,  144 => 55,  135 => 48,  131 => 46,  129 => 45,  125 => 44,  121 => 43,  117 => 42,  112 => 40,  104 => 34,  102 => 25,  99 => 24,  95 => 23,  89 => 12,  84 => 9,  77 => 7,  72 => 6,  68 => 5,  61 => 3,  56 => 1,  53 => 19,  50 => 17,  48 => 16,  46 => 15,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"@panel/base.html.twig\" %}

{% block header_title %}Profile{% endblock %}

{% block breadcrumb_item %}
<li class=\"breadcrumb-item\"><a href=\"{{ '/' | access_path }}\">Home</a></li>
{% if api.User.getRoles.isAllowed(1) %}<li class=\"breadcrumb-item\"><a href=\"{{ '/users' | access_path }}\">Users</a></li>
{% endif %}
<li class=\"breadcrumb-item active\" aria-current=\"page\">Profile</li>
{% endblock %}

{% block subheader_outer %}{% endblock %}


{% if user is null %}
{% set user = api.Auth.Session.getUser %}
{% set user_sessions = api.Auth.Session.getUserSessions %}
{% else %}
{% set user_sessions = api.Auth.Session.getUserSessions(user.id) %}
{% endif %}


{% block content %}

{% set device_icons = {
    Windows: \"fa-desktop\",
    iPad: \"fa-tablet-screen-button\",
    iPhone: \"fa-mobile-screen-button\",
    'Android Device': \"fa-mobile\",
    Linux: \"fa-laptop\",
    Macintosh: \"fa-laptop\",
    Unknown: \"fa-question\"
} %}


<div class=\"row pb-5\">
    <div class=\"col-12\">
        <div class=\"bg-dark text-white position-relative\" style=\"min-height: 300px;\">
            <div class=\"position-absolute top-50 start-50 translate-middle text-center mt-4\">
                <img src=\"{{ user.avatar }}\" alt=\"\" class=\"rounded-circle object-fit-cover bg-light\"
                    style=\"width: 100px; height: 100px;box-shadow: 0 0 8px rgb(255 255 255 / 60%);\" id=\"avatar\">
                <h1 class=\"mt-3 fs-2\">{{ user.name }}</h1>
                <small class=\"d-block\">{{ user.email }}</small>
                <i>{{ user.role | ucfirst }} </i>
                {% if api.User.getConfig.get(\"profile.change_avatar\") and user.id == api.Auth.Session.getUser.id %}
                <button class=\"btn position-absolute end-0 top-0 text-primary\" type=\"button\" id=\"change-avatar\"><i class=\"fa-solid fa-pen\"></i></button>
                {% endif %}

            </div>
        </div>
    </div>
    <div class=\"px-3 px-lg-5 pt-3\">
        <h4 class=\"mt-3 fs-5 mb-1\">Sessions</h4>
        <ul class=\"list-group list-group-flush\">
            {% for session in user_sessions %}
            <li class=\"list-group-item {% if session.is_current %}bg-primary bg-opacity-10{% endif %}\">
                <div>
                    <h3 class=\"mb-2\"><i class=\"fa fa-clock\"></i> {{ session.post_date | date('d M Y H:i:s') }} - {{ session.time_ago }}</h3>
                    <div class=\"ms-2\">
                        <p class=\"mb-0\"><i class=\"fa-solid {{ device_icons[session.device] }}\"></i> {{ session.device }} - {{ session.browser }}</p>
                        {% if api.Auth.getConfig.get(\"geo\") %}
                        <small class=\"text-muted\"><i class=\"fa fa-map-marker\"></i> {{ session.data.display_name ?? \"Unknown\" }}</small>
                        {% endif %}
                    </div>

                </div>
            </li>
            {% endfor %}
        </ul>
    </div>
</div>

{% endblock %}

{% block javascript %}
{{ parent() }}
{% if api.User.getConfig.get(\"profile.change_avatar\") and user.id == api.Auth.Session.getUser.id %}
<script>
    \$(\"#change-avatar\").click(function () {
        \$(\"<input type='file' accept='image/*' />\").on(\"change\", function () {

            var file = this.files[0];
            // max size 1mb
            if (file.size > 1048576) {
                __.toast(\"Please select a file smaller than 1mb\", 5, 'text-danger');
                return;
            }
            __.http.post(\"{{ '/api/User/uploadAvatar' | access_path }}\", {
                avatar: file
            }).then((result) => {
                \$(\"#avatar\").attr('src', result.data);
            }).catch((err) => {
                __.toast(err.message || \"Something went wrong\", 5, 'text-danger');
            });
        }).click();
    });
    \$('#avatar').click(function () {
        let \$this = \$(this);
        const modal = __.modal.create(\"Avatar\", \$(`<img src=\"\${\$this.attr('src')}\" class=\"w-100\" />`));
        modal.el.find(\".modal-footer\").remove();
        modal.el.find(\".modal-header\").remove();
        modal.on(\"modal:hide\", function () {
            modal.el.remove();
        })
        if (!\$this.attr('src').startsWith('http')) {
            modal.el.find(\".modal-body\").append(
                \$(\"<button class='btn text-danger btn-sm position-absolute end-0 top-0'><i class='fa fa-trash'></i> Delete</button>\")
                    .click(function () {
                        __.http.get(\"{{ '/api/User/deleteAvatar' | access_path }}\")
                        .then((result) => {
                            \$(\"#avatar\").attr('src', result.data);
                            modal.hide();
                        }).catch((err) => {
                            __.toast(err.message || \"Something went wrong\", 5, 'text-danger');
                        });
                    })
            )
        }
        modal.show();
    });
</script>
{% else %}
<script>
    \$('#avatar').click(function () {
        let \$this = \$(this);
        const modal = __.modal.create(\"Avatar\", \$(`<img src=\"\${\$this.attr('src')}\" class=\"w-100\" />`));
        modal.el.find(\".modal-footer\").remove();
        modal.el.find(\".modal-header\").remove();
        modal.on(\"modal:hide\", function () {
            modal.el.remove();
        })
        modal.show();
    });
</script>
{% endif %}

{% endblock %}", "@user/Admin/profile.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\User\\Views\\Admin\\profile.html.twig");
    }
}
