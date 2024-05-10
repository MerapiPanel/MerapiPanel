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

/* __string_template__857efe5dee782ee130cbcbbaab68f7dc */
class __TwigTemplate_1fbebe592a9c3b10a52622f153d79503 extends Template
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
        <title>Widget | @website/analytic-visitor</title>
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

canvas {
    width: 100%;
    height: 100% !important;
}</style>
    </head>
    <body>
        <script>
    const data_visitor = {\"labels\":[\"May 04\",\"May 05\",\"May 06\",\"May 07\",\"May 08\",\"May 09\",\"May 10\",\"May 11\"],\"values\":{\"May 04\":0,\"May 05\":0,\"May 06\":162,\"May 07\":6,\"May 08\":4,\"May 09\":21,\"May 10\":11,\"May 11\":26}};
</script>
<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>
<div class=\"w-100 h-100 d-flex flex-column pb-5 text-start\">
    <h4 class=\"fs-2 fw-semibold\">Visitor Analytics</h4>
    <canvas id=\"chart-diagram-visitor\"></canvas>
</div>

        <script type=\"text/javascript\">
const chartDiagramVisitor = document.querySelector('#chart-diagram-visitor');

const chartVisitor = new Chart(chartDiagramVisitor.getContext('2d'), {
    type: 'bar',
    data: {
        labels: data_visitor.labels,
        datasets: [{
            fill: true,
            label: 'Visitor',
            data: data_visitor.values,
            backgroundColor: [
                'rgb(219, 39, 126)',
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
});
</script>
    </body>
</html>";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "__string_template__857efe5dee782ee130cbcbbaab68f7dc";
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
        <title>Widget | @website/analytic-visitor</title>
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

canvas {
    width: 100%;
    height: 100% !important;
}</style>
    </head>
    <body>
        <script>
    const data_visitor = {\"labels\":[\"May 04\",\"May 05\",\"May 06\",\"May 07\",\"May 08\",\"May 09\",\"May 10\",\"May 11\"],\"values\":{\"May 04\":0,\"May 05\":0,\"May 06\":162,\"May 07\":6,\"May 08\":4,\"May 09\":21,\"May 10\":11,\"May 11\":26}};
</script>
<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>
<div class=\"w-100 h-100 d-flex flex-column pb-5 text-start\">
    <h4 class=\"fs-2 fw-semibold\">Visitor Analytics</h4>
    <canvas id=\"chart-diagram-visitor\"></canvas>
</div>

        <script type=\"text/javascript\">
const chartDiagramVisitor = document.querySelector('#chart-diagram-visitor');

const chartVisitor = new Chart(chartDiagramVisitor.getContext('2d'), {
    type: 'bar',
    data: {
        labels: data_visitor.labels,
        datasets: [{
            fill: true,
            label: 'Visitor',
            data: data_visitor.values,
            backgroundColor: [
                'rgb(219, 39, 126)',
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
});
</script>
    </body>
</html>", "__string_template__857efe5dee782ee130cbcbbaab68f7dc", "");
    }
}
