var mainApp = angular.module('MainApp', [
    'ngSanitize',
    'ngAnimate',
    'ngLodash',
    'angular-button-spinner',
    'datatables',
    'angularMoment',
    'ADM-dateTimePicker',
    'ngSweetAlert',
    'ui.select',
    'angular-tag',
    'codertyLoading',
    'ng.ckeditor'
]);

mainApp.config(['uiSelectConfig', function (uiSelectConfig) {
    uiSelectConfig.theme = 'selectize';
    uiSelectConfig.appendToBody = true;
    uiSelectConfig.resetSearchInput = true;
    uiSelectConfig.spinnerEnabled = false;
}]);

mainApp.run(function($rootScope) {
    $rootScope.printElem = function(elem) {
        return $('#'+elem).printThis({
            importCss: false
        });
    };
});


mainApp.directive('datePicker', function(){
    return{
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, elm, attr, ctrl){

            // Format date on load
            ctrl.$formatters.unshift(function(value) {
                if(value && moment(value).isValid()){
                    return moment(new Date(value)).format('MM/DD/YYYY');
                }
                return value;
            });

            //Disable Calendar
            scope.$watch(attr.ngDisabled, function (newVal) {
                if(newVal === true)
                    $(elm).datepicker("disable");
                else
                    $(elm).datepicker("enable");
            });

            // Datepicker Settings
            elm.datepicker({
                autoSize: true,
                changeYear: true,
                changeMonth: true,
                dateFormat: attr["dateformat"] || 'mm/dd/yy',
                showOn: 'button',
                buttonText: '<i class="glyphicon glyphicon-calendar"></i>',
                onSelect: function (valu) {
                    scope.$apply(function () {
                        ctrl.$setViewValue(valu);
                    });
                    elm.focus();
                },

                beforeShow: function(){
                    debugger;
                    if(attr["minDate"] != null)
                        $(elm).datepicker('option', 'minDate', attr["minDate"]);

                    if(attr["maxDate"] != null )
                        $(elm).datepicker('option', 'maxDate', attr["maxDate"]);
                }


            });
        }
    };

});


Highcharts.createElement('link', {
    href: 'https://fonts.googleapis.com/css?family=Signika:400,700',
    rel: 'stylesheet',
    type: 'text/css'
}, null, document.getElementsByTagName('head')[0]);

// Add the background image to the container
Highcharts.addEvent(Highcharts.Chart, 'afterGetContainer', function () {
    this.container.style.background =
        'url(https://www.highcharts.com/samples/graphics/sand.png)';
});

Highcharts.theme = {
    colors: ['#f45b5b', '#8085e9', '#8d4654', '#7798BF', '#aaeeee',
        '#ff0066', '#eeaaee', '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],
    chart: {
        backgroundColor: null,
        style: {
            fontFamily: 'Signika, serif'
        }
    },
    title: {
        style: {
            color: 'black',
            fontSize: '16px',
            fontWeight: 'bold'
        }
    },
    subtitle: {
        style: {
            color: 'black'
        }
    },
    tooltip: {
        borderWidth: 0
    },
    legend: {
        itemStyle: {
            fontWeight: 'bold',
            fontSize: '13px'
        }
    },
    xAxis: {
        labels: {
            style: {
                color: '#6e6e70'
            }
        }
    },
    yAxis: {
        labels: {
            style: {
                color: '#6e6e70'
            }
        }
    },
    plotOptions: {
        series: {
            shadow: true
        },
        candlestick: {
            lineColor: '#404048'
        },
        map: {
            shadow: false
        }
    },

    // Highstock specific
    navigator: {
        xAxis: {
            gridLineColor: '#D0D0D8'
        }
    },
    rangeSelector: {
        buttonTheme: {
            fill: 'white',
            stroke: '#C0C0C8',
            'stroke-width': 1,
            states: {
                select: {
                    fill: '#D0D0D8'
                }
            }
        }
    },
    scrollbar: {
        trackBorderColor: '#C0C0C8'
    },

    // General
    background2: '#E0E0E8'

};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);

mainApp.directive('stBarChart', function() {

    return {
        restrict: 'E',
        template: '<div></div>',
        scope: {
            title: '@',
            data: '=',
            child: '='
        },
        link: function (scope, element) {
//                //console.log("HighChart: ",scope)
            Highcharts.chart(element[0], {

                chart: {
                    type: 'column'
                },
                title: {
                    text: scope.title
                },
                xAxis: {
                    categories: scope.child,
                    crosshair: true
                },yAxis: {
                    min: 0,
                    title: {
                        text: 'Total count'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                exporting: { enabled: true },
                credits: {
                    enabled: false
                },
                series: scope.data
            });
        }
    }});