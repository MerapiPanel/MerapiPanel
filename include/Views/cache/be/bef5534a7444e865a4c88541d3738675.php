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

/* @auth/login.html.twig */
class __TwigTemplate_ee5c6e392fea808fb387ed4984bce5d7 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'javascript_header' => [$this, 'block_javascript_header'],
            'content' => [$this, 'block_content'],
            'javascript' => [$this, 'block_javascript'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("base.html.twig", "@auth/login.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_javascript_header($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 5
        $this->displayParentBlock("javascript_header", $context, $blocks);
        echo "

";
    }

    // line 9
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 10
        echo "
<section class=\"container\">
    <div class=\"d-flex flex-column vh-100\">
        <div class=\"flex-grow-1 mt-5\">

            <header class=\"text-center my-5\">
                <div> <img width=\"150\" height=\"55\" class=\"object-fit-cover\" src=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("img/logo.png"), "html", null, true);
        echo "\"
                        alt=\"Merapi logo\"></div>
                <h1 class=\"fs-3 my-1\">Login to your account</h1>
            </header>

            <div class=\"row pt-3\">

                <div class=\"col-12 col-lg-6 mx-auto\">

                    <form action=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url("/public/api/Auth/login"), "html", null, true);
        echo "\" method=\"post\" id=\"login-form\"
                        class=\"needs-validation  card card-body shadow-sm border-0\" novalidate>

                        <div class=\"form-group mb-3\">
                            <label for=\"email\">Email</label>
                            <input class=\"form-control\" placeholder=\"Enter email address\" type=\"email\" name=\"email\"
                                pattern=\"[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}\$\" id=\"email\">
                            <div class=\"invalid-feedback\">
                                Please provide a valid email.
                            </div>
                            <div class=\"valid-feedback\">
                                Looks good!
                            </div>
                        </div>

                        <div class=\"form-group mb-3\">
                            <label for=\"password\">Password</label>
                            <input class=\"form-control\" placeholder=\"Enter you password\" type=\"password\" name=\"password\"
                                minlength=\"6\" id=\"password\">
                        </div>

                        <div class=\"form-group row align-items-end\">

                            <div class=\"col-12 text-center d-none\" id=\"loading\">
                                <i class=\"fa-solid fa-slash fa-spin\"></i><div class=\"d-inline-block ms-2\">Loading...</div>
                            </div>
                            <div class=\"col-10 col-lg-8 mx-auto text-danger\">
                                <div class=\"w-100\" id=\"error\"></div>
                            </div>
                            <div class=\"col-12 mt-4\">
                                <div class=\"d-flex justify-content-center align-items-end flex-wrap gap-3\">
                                    ";
        // line 56
        if (( !twig_test_empty(twig_get_attribute($this->env, $this->source, ($context["setting"] ?? null), "google_oauth_id", [], "any", false, false, false, 56)) &&  !twig_test_empty(twig_get_attribute($this->env, $this->source, ($context["setting"] ?? null), "google_oauth_secret", [], "any", false, false, false, 56)))) {
            // line 57
            echo "                                    <div class=\"d-none\" id=\"g_id_onload\" data-client_id=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["setting"] ?? null), "google_oauth_id", [], "any", false, false, false, 57), "html", null, true);
            echo "\" data-login_uri=\"";
            echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url(($context["login_api_endpoint"] ?? null)), "html", null, true);
            echo "\" data-auto_prompt=\"false\">
                                    </div>

                                    <div class=\"d-inline-block g_id_signin vertical-align-middle\" data-type=\"standard\"
                                        data-size=\"large\" data-theme=\"outline\" data-text=\"sign_in_with\"
                                        data-shape=\"rectangular\" data-logo_alignment=\"left\">
                                    </div>
                                    ";
        }
        // line 65
        echo "
                                    <button class=\"btn btn-primary px-5 rounded-1\" type=\"submit\">Login</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <footer class=\"mt-auto py-3\">
            <div class=\"row\">
                <div class=\"col-12 col-lg-6 d-flex justify-content-start\">
                    <div class=\"border-end d-inline-block mb-2 me-1 pe-3\">
                        <img width=\"90\" height=\"40\" src=\"";
        // line 78
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("assets/img/logo.png"), "html", null, true);
        echo "\" alt=\"\">
                    </div>
                    <div class=\"mt-3\">
                        <a class=\"btn btn-link\" href=\"https://www.npmjs.com/package/@il4mb/merapipanel\">
                            <i class=\"fa-brands fa-npm fa-xl\"></i>
                        </a>
                        <a class=\"btn btn-link\" href=\"https://github.com/MerapiPanel/MerapiPanel\" target=\"_blank\">
                            <i class=\"fa-brands fa-github fa-xl\"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class=\"text-center mt-2\">Copyright © ";
        // line 90
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, "now", "Y"), "html", null, true);
        echo " Merapi. All rights reserved.</div>
        </footer>
    </div>
</section>

";
    }

    // line 101
    public function block_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 102
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
<script>
    __.Auth.config = ";
        // line 104
        echo json_encode(($context["config"] ?? null));
        echo "
</script>

<script type=\"text/javascript\" src=\"";
        // line 107
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("@auth/dist/login.js"), "html", null, true);
        echo "\"></script>
";
        // line 108
        if (( !twig_test_empty(twig_get_attribute($this->env, $this->source, ($context["setting"] ?? null), "google_oauth_id", [], "any", false, false, false, 108)) &&  !twig_test_empty(twig_get_attribute($this->env, $this->source, ($context["setting"] ?? null), "google_oauth_secret", [], "any", false, false, false, 108)))) {
            // line 109
            echo "<script src=\"https://accounts.google.com/gsi/client\" async defer></script>
";
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@auth/login.html.twig";
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
        return array (  194 => 109,  192 => 108,  188 => 107,  182 => 104,  177 => 102,  173 => 101,  163 => 90,  148 => 78,  133 => 65,  119 => 57,  117 => 56,  83 => 25,  71 => 16,  63 => 10,  59 => 9,  52 => 5,  48 => 4,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends 'base.html.twig' %}


{% block javascript_header %}
{{ parent() }}

{% endblock %}

{% block content %}

<section class=\"container\">
    <div class=\"d-flex flex-column vh-100\">
        <div class=\"flex-grow-1 mt-5\">

            <header class=\"text-center my-5\">
                <div> <img width=\"150\" height=\"55\" class=\"object-fit-cover\" src=\"{{ 'img/logo.png' | assets }}\"
                        alt=\"Merapi logo\"></div>
                <h1 class=\"fs-3 my-1\">Login to your account</h1>
            </header>

            <div class=\"row pt-3\">

                <div class=\"col-12 col-lg-6 mx-auto\">

                    <form action=\"{{ '/public/api/Auth/login' | url }}\" method=\"post\" id=\"login-form\"
                        class=\"needs-validation  card card-body shadow-sm border-0\" novalidate>

                        <div class=\"form-group mb-3\">
                            <label for=\"email\">Email</label>
                            <input class=\"form-control\" placeholder=\"Enter email address\" type=\"email\" name=\"email\"
                                pattern=\"[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}\$\" id=\"email\">
                            <div class=\"invalid-feedback\">
                                Please provide a valid email.
                            </div>
                            <div class=\"valid-feedback\">
                                Looks good!
                            </div>
                        </div>

                        <div class=\"form-group mb-3\">
                            <label for=\"password\">Password</label>
                            <input class=\"form-control\" placeholder=\"Enter you password\" type=\"password\" name=\"password\"
                                minlength=\"6\" id=\"password\">
                        </div>

                        <div class=\"form-group row align-items-end\">

                            <div class=\"col-12 text-center d-none\" id=\"loading\">
                                <i class=\"fa-solid fa-slash fa-spin\"></i><div class=\"d-inline-block ms-2\">Loading...</div>
                            </div>
                            <div class=\"col-10 col-lg-8 mx-auto text-danger\">
                                <div class=\"w-100\" id=\"error\"></div>
                            </div>
                            <div class=\"col-12 mt-4\">
                                <div class=\"d-flex justify-content-center align-items-end flex-wrap gap-3\">
                                    {% if setting.google_oauth_id is not empty and setting.google_oauth_secret is not empty %}
                                    <div class=\"d-none\" id=\"g_id_onload\" data-client_id=\"{{ setting.google_oauth_id }}\" data-login_uri=\"{{ login_api_endpoint | url }}\" data-auto_prompt=\"false\">
                                    </div>

                                    <div class=\"d-inline-block g_id_signin vertical-align-middle\" data-type=\"standard\"
                                        data-size=\"large\" data-theme=\"outline\" data-text=\"sign_in_with\"
                                        data-shape=\"rectangular\" data-logo_alignment=\"left\">
                                    </div>
                                    {% endif %}

                                    <button class=\"btn btn-primary px-5 rounded-1\" type=\"submit\">Login</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <footer class=\"mt-auto py-3\">
            <div class=\"row\">
                <div class=\"col-12 col-lg-6 d-flex justify-content-start\">
                    <div class=\"border-end d-inline-block mb-2 me-1 pe-3\">
                        <img width=\"90\" height=\"40\" src=\"{{ 'assets/img/logo.png' | assets }}\" alt=\"\">
                    </div>
                    <div class=\"mt-3\">
                        <a class=\"btn btn-link\" href=\"https://www.npmjs.com/package/@il4mb/merapipanel\">
                            <i class=\"fa-brands fa-npm fa-xl\"></i>
                        </a>
                        <a class=\"btn btn-link\" href=\"https://github.com/MerapiPanel/MerapiPanel\" target=\"_blank\">
                            <i class=\"fa-brands fa-github fa-xl\"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class=\"text-center mt-2\">Copyright © {{ 'now' | date('Y') }} Merapi. All rights reserved.</div>
        </footer>
    </div>
</section>

{% endblock %}





{% block javascript %}
{{ parent() }}
<script>
    __.Auth.config = {{ config|json_encode|raw }}
</script>

<script type=\"text/javascript\" src=\"{{ '@auth/dist/login.js' | assets }}\"></script>
{% if setting.google_oauth_id is not empty and setting.google_oauth_secret is not empty %}
<script src=\"https://accounts.google.com/gsi/client\" async defer></script>
{% endif %}
{% endblock %}", "@auth/login.html.twig", "F:\\web\\MerapiPanel\\include\\Module\\Auth\\Views\\login.html.twig");
    }
}
