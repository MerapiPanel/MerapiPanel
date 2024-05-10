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

/* __string_template__c9981df5408419616fe6bd1e9c8433ae */
class __TwigTemplate_c8b075992a1e9f43341e36128e791899 extends Template
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
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<script id='article-opts' type='text/javascript'>
    __.Article.endpoints = {
        create: \"";
        // line 3
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/article/create/"), "html", null, true);
        echo "\",
        edit: \"";
        // line 4
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/article/edit/{id}"), "html", null, true);
        echo "\",
        fetchAll: \"";
        // line 5
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Article/fetchAll"), "html", null, true);
        echo "\",
        save: \"";
        // line 6
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Article/save"), "html", null, true);
        echo "\",
        delete: \"";
        // line 7
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Article/delete"), "html", null, true);
        echo "\",
        update: \"";
        // line 8
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Article/update"), "html", null, true);
        echo "\",
        view: \"";
        // line 9
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/article/view/{id}"), "html", null, true);
        echo "\"
    }
    __.Article.config = {
        roles: {\"modify\":false},
        ...{
            payload: {
                page: 1,
                limit: 5
            }
        }
    }
</script>
<script id='contact-opts' type='text/javascript'>
\t__.Contact.config = {
\t\troles: {\"create\":false,\"update\":false,\"delete\":false,\"modifyTemplate\":false}
\t}
</script>
<script id='filemanager-opts' type='text/javascript'>
    __.FileManager.endpoints = {
        fetch: \"";
        // line 28
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/FileManager/fetch"), "html", null, true);
        echo "\",
        upload: \"";
        // line 29
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/FileManager/uploadChunk"), "html", null, true);
        echo "\",
        uploadInfo: \"";
        // line 30
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/FileManager/uploadInfo"), "html", null, true);
        echo "\",
        delete: \"";
        // line 31
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/FileManager/delete"), "html", null, true);
        echo "\",
        rename: \"";
        // line 32
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/FileManager/rename"), "html", null, true);
        echo "\",
        newFolder: \"";
        // line 33
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/FileManager/newFolder"), "html", null, true);
        echo "\",
    }
    __.FileManager.roles = {\"upload\":false,\"modify\":false};
</script>
<script id='filemanager' src='/public/assets/@FileManager/dist/FileManager.js' type='text/javascript'></script>
<script id='product-opts' type='text/javascript'>
\t__.MProduct.endpoints = {
\t\tfetchAll: \t\"";
        // line 40
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Product/fetchAll"), "html", null, true);
        echo "\",
\t\tfetch: \t \t\"";
        // line 41
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Product/fetch"), "html", null, true);
        echo "\",
\t\tdelete: \t\"";
        // line 42
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Product/delete"), "html", null, true);
        echo "\",
\t\tadd: \t\t\"";
        // line 43
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Product/add"), "html", null, true);
        echo "\",
\t\tupdate: \t\"";
        // line 44
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Product/update"), "html", null, true);
        echo "\",

\t\tview: \"";
        // line 46
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/product/view/{id}"), "html", null, true);
        echo "\",
\t\tedit: \"";
        // line 47
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/product/edit/{id}"), "html", null, true);
        echo "\",
\t}
\t__.MProduct.config = {
\t\t...{\"currency\":\"IDR\",\"currency_symbol\":\"\"},
\t\troles: {\"modify\":false},
\t\t...{
\t\t\tpayload: {
\t\t\t\tlimit: 10,
\t\t\t\tpage: 1,
\t\t\t}
\t\t}
\t}
</script>
<script id='setting-opts' type='text/javascript'>
    __.Setting.roles = {\"modifyConfig\":false,\"modifyRule\":false};
</script>
<script id='user-opts' type='text/javascript'>
__.MUser.opts = {
    endpoints: {
        fetch: \"";
        // line 66
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/User/fetch"), "html", null, true);
        echo "\",
        fetchAll: \"";
        // line 67
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/User/fetchAll"), "html", null, true);
        echo "\",
        update: \"";
        // line 68
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/User/update"), "html", null, true);
        echo "\",
        delete: \"";
        // line 69
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/User/delete"), "html", null, true);
        echo "\",
        forceLogout: \"";
        // line 70
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/api/Auth/forceLogout"), "html", null, true);
        echo "\",
        profileURL: \"";
        // line 71
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_access_path("/users/profile/{user_id}"), "html", null, true);
        echo "\",
    },
    session: ";
        // line 73
        echo json_encode(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "Auth", [], "any", false, false, false, 73), "Session", [], "any", false, false, false, 73), "getUser", [], "method", false, false, false, 73));
        echo ",
    roleNames: [\"admin\",\"editor\",\"contributor\",\"manager\",\"moderator\",\"user\"],
    allowModify: ";
        // line 75
        echo json_encode(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "User", [], "any", false, false, false, 75), "getRoles", [], "any", false, false, false, 75), "isAllowed", [1], "method", false, false, false, 75));
        echo ",
    allowVisit: ";
        // line 76
        echo json_encode(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "User", [], "any", false, false, false, 76), "getRoles", [], "any", false, false, false, 76), "isAllowed", [2], "method", false, false, false, 76));
        echo ",
    profilePage: ";
        // line 77
        echo json_encode(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["api"] ?? null), "User", [], "any", false, false, false, 77), "getConfig", [], "any", false, false, false, 77), "get", ["profile"], "method", false, false, false, 77));
        echo ",
}
</script>
";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "__string_template__c9981df5408419616fe6bd1e9c8433ae";
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
        return array (  202 => 77,  198 => 76,  194 => 75,  189 => 73,  184 => 71,  180 => 70,  176 => 69,  172 => 68,  168 => 67,  164 => 66,  142 => 47,  138 => 46,  133 => 44,  129 => 43,  125 => 42,  121 => 41,  117 => 40,  107 => 33,  103 => 32,  99 => 31,  95 => 30,  91 => 29,  87 => 28,  65 => 9,  61 => 8,  57 => 7,  53 => 6,  49 => 5,  45 => 4,  41 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<script id='article-opts' type='text/javascript'>
    __.Article.endpoints = {
        create: \"{{ '/article/create/' | access_path }}\",
        edit: \"{{ '/article/edit/{id}' | access_path }}\",
        fetchAll: \"{{ '/api/Article/fetchAll' | access_path }}\",
        save: \"{{ '/api/Article/save' | access_path }}\",
        delete: \"{{ '/api/Article/delete' | access_path }}\",
        update: \"{{ '/api/Article/update' | access_path }}\",
        view: \"{{ '/article/view/{id}' | access_path }}\"
    }
    __.Article.config = {
        roles: {\"modify\":false},
        ...{
            payload: {
                page: 1,
                limit: 5
            }
        }
    }
</script>
<script id='contact-opts' type='text/javascript'>
\t__.Contact.config = {
\t\troles: {\"create\":false,\"update\":false,\"delete\":false,\"modifyTemplate\":false}
\t}
</script>
<script id='filemanager-opts' type='text/javascript'>
    __.FileManager.endpoints = {
        fetch: \"{{ '/api/FileManager/fetch' | access_path }}\",
        upload: \"{{ '/api/FileManager/uploadChunk' | access_path }}\",
        uploadInfo: \"{{ '/api/FileManager/uploadInfo' | access_path }}\",
        delete: \"{{ '/api/FileManager/delete' | access_path }}\",
        rename: \"{{ '/api/FileManager/rename' | access_path }}\",
        newFolder: \"{{ '/api/FileManager/newFolder' | access_path }}\",
    }
    __.FileManager.roles = {\"upload\":false,\"modify\":false};
</script>
<script id='filemanager' src='/public/assets/@FileManager/dist/FileManager.js' type='text/javascript'></script>
<script id='product-opts' type='text/javascript'>
\t__.MProduct.endpoints = {
\t\tfetchAll: \t\"{{ '/api/Product/fetchAll' | access_path }}\",
\t\tfetch: \t \t\"{{ '/api/Product/fetch' | access_path }}\",
\t\tdelete: \t\"{{ '/api/Product/delete' | access_path }}\",
\t\tadd: \t\t\"{{ '/api/Product/add' | access_path }}\",
\t\tupdate: \t\"{{ '/api/Product/update' | access_path }}\",

\t\tview: \"{{ '/product/view/{id}' | access_path }}\",
\t\tedit: \"{{ '/product/edit/{id}' | access_path }}\",
\t}
\t__.MProduct.config = {
\t\t...{\"currency\":\"IDR\",\"currency_symbol\":\"\"},
\t\troles: {\"modify\":false},
\t\t...{
\t\t\tpayload: {
\t\t\t\tlimit: 10,
\t\t\t\tpage: 1,
\t\t\t}
\t\t}
\t}
</script>
<script id='setting-opts' type='text/javascript'>
    __.Setting.roles = {\"modifyConfig\":false,\"modifyRule\":false};
</script>
<script id='user-opts' type='text/javascript'>
__.MUser.opts = {
    endpoints: {
        fetch: \"{{ '/api/User/fetch' | access_path }}\",
        fetchAll: \"{{ '/api/User/fetchAll' | access_path }}\",
        update: \"{{ '/api/User/update' | access_path }}\",
        delete: \"{{ '/api/User/delete' | access_path }}\",
        forceLogout: \"{{ '/api/Auth/forceLogout' | access_path }}\",
        profileURL: \"{{ '/users/profile/{user_id}' | access_path }}\",
    },
    session: {{ api.Auth.Session.getUser() | json_encode | raw }},
    roleNames: [\"admin\",\"editor\",\"contributor\",\"manager\",\"moderator\",\"user\"],
    allowModify: {{ api.User.getRoles.isAllowed(1) | json_encode | raw }},
    allowVisit: {{ api.User.getRoles.isAllowed(2) | json_encode | raw }},
    profilePage: {{ api.User.getConfig.get('profile') | json_encode | raw }},
}
</script>
", "__string_template__c9981df5408419616fe6bd1e9c8433ae", "");
    }
}
