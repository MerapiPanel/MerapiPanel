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

/* __string_template__bbd297714a5a083b457da780ebf58b6e */
class __TwigTemplate_8218ebcd272f2f6b3fe4a120ffd7f0c5 extends Template
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
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\">
        <title>Widget | @website/analytic-unique-visitor</title>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <link rel=\"stylesheet\" href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("dist/main.css")), "html", null, true);
        echo "\" type=\"text/css\">
        <link rel=\"stylesheet\" href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->extensions['MerapiPanel\Views\Extension\Bundle']->fl_url($this->extensions['MerapiPanel\Views\Extension\Assets']->fl_assets("/vendor/fontawesome/css/all.min.css")), "html", null, true);
        echo "\" type=\"text/css\">
        <style>body, html {height: 100vh;display: flex;align-items: center;justify-content: center;}</style>
        <style type=\"text/css\">/*!***************************************************************************************************************************************************************************************************!*\\
  !*** css ./node_modules/css-loader/dist/cjs.js!./node_modules/postcss-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./include/Module/website/Widgets/analytic-visitor/src/style.scss ***!
  \\***************************************************************************************************************************************************************************************************/
html, body {
  height: 100vh;
  width: 100vw;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}

.widget-website-analytic-visitor {
  height: 100vh;
  color: red;
  font-weight: 700;
  font-size: 25px;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
    </head>
    <body>
        <script>
    const data_unique_visitor = {\"labels\":[\"Yesterday\",\"Today\"],\"values\":[2,2]};
</script>
<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>
<div class=\"w-100 h-100 d-flex flex-column pb-5 text-start\">
    <h4 class=\"fs-2 fw-semibold\">Unique Visitor</h4>
    <canvas height=\"100%\"  id=\"chart-unique-visitor\"></canvas>
</div>

        <script type=\"text/javascript\">
const chartDiagramUnique = document.querySelector('#chart-unique-visitor');


const chartUnique = new Chart(chartDiagramUnique.getContext('2d'), {
    type: 'bar',
    data: {
        labels: data_unique_visitor.labels,
        datasets: [{
            fill: true,
            label: 'Unique Visitor',
            data: data_unique_visitor.values,
            backgroundColor: [
                'rgb(219, 39, 126)',
                'rgb(0, 157, 255)'
            ]
        }]
    },
    options: {
        animation: false,
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
            callbacks: {
                label: function (tooltipItem) {
                    return tooltipItem.yLabel;
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            yAxes: [{
                gridLines: {
                    drawBorder: false,
                    display: false
                }
            }],
            y: {
                beginAtZero: true,
                suggestedMax: 10,
                min: 0,
                grid: {
                    //drawOnChartArea:false,
                    drawBorder: false,
                    //drawTicks: false
                }
            },
            x: {
                grid: {
                    drawOnChartArea: false,
                    drawBorder: false
                }
            }

        }
    }
});</script>
    </body>
</html>";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "__string_template__bbd297714a5a083b457da780ebf58b6e";
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
        return array (  49 => 8,  45 => 7,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\">
        <title>Widget | @website/analytic-unique-visitor</title>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <link rel=\"stylesheet\" href=\"{{ 'dist/main.css' | assets | url }}\" type=\"text/css\">
        <link rel=\"stylesheet\" href=\"{{ '/vendor/fontawesome/css/all.min.css' | assets | url }}\" type=\"text/css\">
        <style>body, html {height: 100vh;display: flex;align-items: center;justify-content: center;}</style>
        <style type=\"text/css\">/*!***************************************************************************************************************************************************************************************************!*\\
  !*** css ./node_modules/css-loader/dist/cjs.js!./node_modules/postcss-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./include/Module/website/Widgets/analytic-visitor/src/style.scss ***!
  \\***************************************************************************************************************************************************************************************************/
html, body {
  height: 100vh;
  width: 100vw;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}

.widget-website-analytic-visitor {
  height: 100vh;
  color: red;
  font-weight: 700;
  font-size: 25px;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
    </head>
    <body>
        <script>
    const data_unique_visitor = {\"labels\":[\"Yesterday\",\"Today\"],\"values\":[2,2]};
</script>
<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>
<div class=\"w-100 h-100 d-flex flex-column pb-5 text-start\">
    <h4 class=\"fs-2 fw-semibold\">Unique Visitor</h4>
    <canvas height=\"100%\"  id=\"chart-unique-visitor\"></canvas>
</div>

        <script type=\"text/javascript\">
const chartDiagramUnique = document.querySelector('#chart-unique-visitor');


const chartUnique = new Chart(chartDiagramUnique.getContext('2d'), {
    type: 'bar',
    data: {
        labels: data_unique_visitor.labels,
        datasets: [{
            fill: true,
            label: 'Unique Visitor',
            data: data_unique_visitor.values,
            backgroundColor: [
                'rgb(219, 39, 126)',
                'rgb(0, 157, 255)'
            ]
        }]
    },
    options: {
        animation: false,
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
            callbacks: {
                label: function (tooltipItem) {
                    return tooltipItem.yLabel;
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            yAxes: [{
                gridLines: {
                    drawBorder: false,
                    display: false
                }
            }],
            y: {
                beginAtZero: true,
                suggestedMax: 10,
                min: 0,
                grid: {
                    //drawOnChartArea:false,
                    drawBorder: false,
                    //drawTicks: false
                }
            },
            x: {
                grid: {
                    drawOnChartArea: false,
                    drawBorder: false
                }
            }

        }
    }
});</script>
    </body>
</html>", "__string_template__bbd297714a5a083b457da780ebf58b6e", "");
    }
}
