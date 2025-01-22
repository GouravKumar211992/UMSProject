$(function () {
    'use strict';
    var chartWrapper = $('.chartjs'),
        lineChartEx4 = $('.line-chart-ex4'),
        lineChartEx5 = $('.line-chart-ex5');

    // Color Variables
    var primaryColorShade = '#836AF9',
        yellowColor = '#ffe800',
        successColorShade = '#28dac6',
        warningColorShade = '#ffe802',
        warningLightColor = '#FDAC34',
        infoColorShade = '#299AFF',
        greyColor = '#4F5D70',
        blueColor = '#2c9aff',
        blueLightColor = '#84D0FF',
        greyLightColor = '#EDF1F4',
        tooltipShadow = 'rgba(0, 0, 0, 0.25)',
        lineChartPrimary = '#666ee8',
        lineChartDanger = '#ff4961',
        labelColor = '#6e6b7b',
        grid_line_color = 'rgba(200, 200, 200, 0.2)'; // RGBA color helps in dark layout

    var chartColors = {
        column: {
            series1: '#826af9',
            series2: '#d2b0ff',
            bg: '#f8d3ff'
        },
        success: {
            shade_100: '#7eefc7',
            shade_200: '#06774f'
        },
        donut: {
            series1: '#00d4bd',
            series2: '#ffe700',
            series3: '#826bf8',
            series4: '#2b9bf4',
            series5: '#FFA1A1',
            series6: '#DC1B54',
            series7: '#2372B5',
            series8: '#F07C00',
            series9: '#C6178A',
            series10: '#ea5455',
            series11: '#28c76f',
            series12: '#06d0e9',
            series13: '#ff9f43',
            series14: '#6b12b7',

        },
        area: {
            series3: '#a4f8cd',
            series2: '#60f2ca',
            series1: '#2bdac7'
        }
    };

    var donutChartCust = document.querySelector('#donut-chart-customer'),
        donutChartConfig = {
            chart: {
                height: 350,
                type: 'donut'
            },
            legend: {
                show: true,
                position: 'bottom'
            },
            labels: (typeof top5CustomerCategories !== 'undefined' && top5CustomerCategories !== null) ? top5CustomerCategories : [],
            series: (typeof top5CustomerSalesPrc !== 'undefined' && top5CustomerSalesPrc !== null) ? top5CustomerSalesPrc : [],
            colors: (typeof top5CustomerColorCode !== 'undefined' && top5CustomerColorCode !== null) ? top5CustomerColorCode : [],
            dataLabels: {
                enabled: true,
                formatter: function (val, opt) {
                    return parseInt(val) + '%';
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            name: {
                                fontSize: '1rem',
                                fontFamily: 'Montserrat'
                            },
                            value: {
                                fontSize: '1rem',
                                fontFamily: 'Montserrat',
                                formatter: function (val) {
                                    return parseInt(val) + '%';
                                }
                            },
                            total: {
                                show: true,
                                fontSize: '1rem',
                                label: 'Total',

                            }
                        }
                    }
                }
            },
            responsive: [
                {
                    breakpoint: 992,
                    options: {
                        chart: {
                            height: 380
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 320
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    labels: {
                                        show: true,
                                        name: {
                                            fontSize: '1.5rem'
                                        },
                                        value: {
                                            fontSize: '1rem'
                                        },
                                        total: {
                                            fontSize: '1.5rem'
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            ]
        };



    if (typeof donutChartCust !== undefined && donutChartCust !== null) {
        var donutChart = new ApexCharts(donutChartCust, donutChartConfig);
        donutChart.render();
    }

    if (lineChartEx4.length) {
        var maxCustomerTargetValue = Math.max(...salesCustomerTarget);
        var maxSalesOrderValue = Math.max(...salesOrderSummary);

        var maxDataValue = Math.max(maxCustomerTargetValue, maxSalesOrderValue);
        var steps = 5;
        var stepSize, maxYValue;

        function calculateSalesChartStepSize(maxValue, steps) {
            let initialStepSize = maxValue / steps;
            if (maxValue >= 10000000) {
                return Math.ceil(initialStepSize / 1000) * 1000;
            } else if (maxValue >= 1000000) {
                return Math.ceil(initialStepSize / 100) * 100;
            } else if (maxValue >= 100000) {
                return Math.ceil(initialStepSize / 100) * 100;
            } else if (maxValue >= 10000) {
                return Math.ceil(initialStepSize / 100) * 100;
            } else if (maxValue >= 1000) {
                return Math.ceil(initialStepSize / 10) * 10;
            } else if (maxValue >= 100) {
                return Math.ceil(initialStepSize / 10) * 10;
            } else {
                return Math.ceil(initialStepSize);
            }
        }

        if (maxDataValue === 0) {
            stepSize = 5;
        } else {
            stepSize = calculateSalesChartStepSize(maxDataValue, steps);
        }

        if (maxDataValue === 0) {
            var totalSteps = steps;
            maxYValue = stepSize * totalSteps;
        } else {
            var totalSteps = Math.ceil(maxDataValue / stepSize);
            maxYValue = stepSize * steps;
        }

        var lineExample = new Chart(lineChartEx4, {
            type: 'line',
            plugins: [
                // to add spacing between legends and chart
                {
                    beforeInit: function (chart) {
                        chart.legend.afterFit = function () {
                            this.height += 0;
                        };
                    }
                }
            ],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                backgroundColor: false,
                hover: {
                    mode: 'label'
                },
                tooltips: {
                    // Updated default tooltip UI
                    shadowOffsetX: 1,
                    shadowOffsetY: 1,
                    shadowBlur: 8,
                    shadowColor: tooltipShadow,
                    backgroundColor: window.colors.solid.white,
                    titleFontColor: window.colors.solid.black,
                    bodyFontColor: window.colors.solid.black
                },
                layout: {
                    padding: {
                        top: 5,
                        bottom: 5,
                        left: -15
                    }
                },
                scales: {
                    xAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true
                            },
                            gridLines: {
                                display: true,
                                color: grid_line_color,
                                zeroLineColor: grid_line_color
                            },
                            ticks: {
                                fontColor: labelColor
                            }
                        }
                    ],
                    yAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true
                            },
                            ticks: {
                                stepSize: 200,
                                min: 0,
                                max: 800,
                                fontColor: labelColor
                            },

                            gridLines: {
                                display: true,
                                color: grid_line_color,
                                zeroLineColor: grid_line_color
                            }
                        }
                    ]
                },
                legend: {
                    position: 'bottom',
                    align: 'center',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        boxWidth: 7
                    }
                }
            },
            data: {
                labels: salesLabels,
                datasets: [
                    {
                        data: salesOrderSummary,
                        label: 'Sales',
                        borderColor: lineChartDanger,
                        lineTension: 0.5,
                        pointStyle: 'circle',
                        backgroundColor: lineChartDanger,
                        fill: false,
                        pointRadius: 1,
                        pointHoverRadius: 5,
                        pointHoverBorderWidth: 5,
                        pointBorderColor: 'transparent',
                        pointHoverBorderColor: window.colors.solid.white,
                        pointHoverBackgroundColor: lineChartDanger,
                        pointShadowOffsetX: 1,
                        pointShadowOffsetY: 1,
                        pointShadowBlur: 5,
                        pointShadowColor: tooltipShadow
                    },
                    {
                        data: salesCustomerTarget,
                        label: 'Budget',
                        borderColor: lineChartPrimary,
                        lineTension: 0.5,
                        pointStyle: 'circle',
                        backgroundColor: lineChartPrimary,
                        fill: false,
                        pointRadius: 1,
                        pointHoverRadius: 5,
                        pointHoverBorderWidth: 5,
                        pointBorderColor: 'transparent',
                        pointHoverBorderColor: window.colors.solid.white,
                        pointHoverBackgroundColor: lineChartPrimary,
                        pointShadowOffsetX: 1,
                        pointShadowOffsetY: 1,
                        pointShadowBlur: 5,
                        pointShadowColor: tooltipShadow
                    },

                ]
            }
        });
    }

    if (lineChartEx5.length) {
        var lineExample = new Chart(lineChartEx5, {
            type: 'line',
            plugins: [
                // to add spacing between legends and chart
                {
                    beforeInit: function (chart) {
                        chart.legend.afterFit = function () {
                            this.height += 0;
                        };
                    }
                }
            ],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                backgroundColor: false,
                hover: {
                    mode: 'label'
                },
                tooltips: {
                    // Updated default tooltip UI
                    shadowOffsetX: 1,
                    shadowOffsetY: 1,
                    shadowBlur: 8,
                    shadowColor: tooltipShadow,
                    backgroundColor: window.colors.solid.white,
                    titleFontColor: window.colors.solid.black,
                    bodyFontColor: window.colors.solid.black
                },
                layout: {
                    padding: {
                        top: 5,
                        bottom: 5,
                        left: -15
                    }
                },
                scales: {
                    xAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true
                            },
                            gridLines: {
                                display: true,
                                color: grid_line_color,
                                zeroLineColor: grid_line_color
                            },
                            ticks: {
                                fontColor: labelColor
                            }
                        }
                    ],
                    yAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true
                            },
                            ticks: {
                                stepSize: 200,
                                min: 0,
                                max: 800,
                                fontColor: labelColor
                            },

                            gridLines: {
                                display: true,
                                color: grid_line_color,
                                zeroLineColor: grid_line_color
                            }
                        }
                    ]
                },
                legend: {
                    position: 'bottom',
                    align: 'center',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        boxWidth: 7
                    }
                }
            },
            data: {
                labels: prospectsLabels,
                datasets: [
                    {
                        data: prospectsData,
                        label: 'Purchase',
                        borderColor: lineChartDanger,
                        lineTension: 0.5,
                        pointStyle: 'circle',
                        backgroundColor: lineChartDanger,
                        fill: false,
                        pointRadius: 1,
                        pointHoverRadius: 5,
                        pointHoverBorderWidth: 5,
                        pointBorderColor: 'transparent',
                        pointHoverBorderColor: window.colors.solid.white,
                        pointHoverBackgroundColor: lineChartDanger,
                        pointShadowOffsetX: 1,
                        pointShadowOffsetY: 1,
                        pointShadowBlur: 5,
                        pointShadowColor: tooltipShadow
                    }

                ]
            }
        });
    }
});