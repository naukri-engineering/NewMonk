/* Set the defaults for DataTables initialisation */
$.extend(true, $.fn.dataTable.defaults, {
    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'p i>>",
    "sPaginationType": "bootstrap",
    "oLanguage": {
        "sLengthMenu": "_MENU_"
    }
});


/* Default class modification */
$.extend($.fn.dataTableExt.oStdClasses, {
    "sWrapper": "dataTables_wrapper form-inline"
});


/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
    return {
        "iStart": oSettings._iDisplayStart,
        "iEnd": oSettings.fnDisplayEnd(),
        "iLength": oSettings._iDisplayLength,
        "iTotal": oSettings.fnRecordsTotal(),
        "iFilteredTotal": oSettings.fnRecordsDisplay(),
        "iPage": oSettings._iDisplayLength === -1 ?
            0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
        "iTotalPages": oSettings._iDisplayLength === -1 ?
            0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
    };
};


/* Bootstrap style pagination control */
$.extend($.fn.dataTableExt.oPagination, {
    "bootstrap": {
        "fnInit": function(oSettings, nPaging, fnDraw) {
            var oLang = oSettings.oLanguage.oPaginate;
            var fnClickHandler = function(e) {
                e.preventDefault();
                if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
                    fnDraw(oSettings);
                }
            };

            $(nPaging).addClass('pagination').append(
                '<ul>' +
                '<li class="prev disabled"><a href="#"><i class="icon-chevron-left"></i></a></li>' +
                '<li class="next disabled"><a href="#"><i class="icon-chevron-right"></i></a></li>' +
                '</ul>'
            );
            var els = $('a', nPaging);
            $(els[0]).bind('click.DT', {
                action: "previous"
            }, fnClickHandler);
            $(els[1]).bind('click.DT', {
                action: "next"
            }, fnClickHandler);
        },

        "fnUpdate": function(oSettings, fnDraw) {
            var iListLength = 5;
            var oPaging = oSettings.oInstance.fnPagingInfo();
            var an = oSettings.aanFeatures.p;
            var i, ien, j, sClass, iStart, iEnd, iHalf = Math.floor(iListLength / 2);

            if (oPaging.iTotalPages < iListLength) {
                iStart = 1;
                iEnd = oPaging.iTotalPages;
            } else if (oPaging.iPage <= iHalf) {
                iStart = 1;
                iEnd = iListLength;
            } else if (oPaging.iPage >= (oPaging.iTotalPages - iHalf)) {
                iStart = oPaging.iTotalPages - iListLength + 1;
                iEnd = oPaging.iTotalPages;
            } else {
                iStart = oPaging.iPage - iHalf + 1;
                iEnd = iStart + iListLength - 1;
            }

            for (i = 0, ien = an.length; i < ien; i++) {
                // Remove the middle elements
                $('li:gt(0)', an[i]).filter(':not(:last)').remove();

                // Add the new list items and their event handlers
                for (j = iStart; j <= iEnd; j++) {
                    sClass = (j == oPaging.iPage + 1) ? 'class="active"' : '';
                    $('<li ' + sClass + '><a href="#">' + j + '</a></li>')
                        .insertBefore($('li:last', an[i])[0])
                        .bind('click', function(e) {
                            e.preventDefault();
                            oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
                            fnDraw(oSettings);
                        });
                }

                // Add / remove disabled classes from the static elements
                if (oPaging.iPage === 0) {
                    $('li:first', an[i]).addClass('disabled');
                } else {
                    $('li:first', an[i]).removeClass('disabled');
                }

                if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
                    $('li:last', an[i]).addClass('disabled');
                } else {
                    $('li:last', an[i]).removeClass('disabled');
                }
            }
        }
    }
});


/*
 * TableTools Bootstrap compatibility
 * Required TableTools 2.1+
 */

// Set the classes that TableTools uses to something suitable for Bootstrap
$.extend(true, $.fn.DataTable.TableTools.classes, {
    "container": "DTTT ",
    "buttons": {
        "normal": "btn btn-white",
        "disabled": "disabled"
    },
    "collection": {
        "container": "DTTT_dropdown dropdown-menu",
        "buttons": {
            "normal": "",
            "disabled": "disabled"
        }
    },
    "print": {
        "info": "DTTT_print_info "
    },
    "select": {
        "row": "active"
    }
});

// Have the collection use a bootstrap compatible dropdown
$.extend(true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
    "collection": {
        "container": "ul",
        "button": "li",
        "liner": "a"
    }
});

/* Table initialisation */
$(document).ready(function() {
    var responsiveHelper = undefined;
    var breakpointDefinition = {
        tablet: 1024,
        phone: 480
    };
    var tableElement = $('#example');

    tableElement.dataTable({
        "sDom": "<'row-fluid'<'span6'l T><'span6'f>r>t<'row-fluid'<'span12'p i>>",
        "oTableTools": {
            "aButtons": [{
                "sExtends": "collection",
                "sButtonText": "<i class='icon-cloud-download'></i>",
                "aButtons": ["csv", "xls", "pdf", "copy"]
            }]
        },
        "sPaginationType": "bootstrap",
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
        }],
        "aaSorting": [
            [1, "asc"]
        ],
        "oLanguage": {
            "sLengthMenu": "_MENU_ ",
            "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
        },
        bAutoWidth: false,
        fnPreDrawCallback: function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper) {
                responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition);
            }
        },
        fnRowCallback: function(nRow) {
            responsiveHelper.createExpandIcon(nRow);
        },
        fnDrawCallback: function(oSettings) {
            responsiveHelper.respond();
        }
    });

    $('#example_wrapper .dataTables_filter input').addClass("input-medium "); // modify table search input
    $('#example_wrapper .dataTables_length select').addClass("select2-wrapper span12"); // modify table per page dropdown



    $('#example input').click(function() {
        $(this).parent().parent().parent().toggleClass('row_selected');
    });


    $('#quick-access .btn-cancel').click(function() {
        $("#quick-access").css("bottom", "-115px");
    });
    $('#quick-access .btn-add').click(function() {
        fnClickAddRow();
        $("#quick-access").css("bottom", "-115px");
    });

    /*
     * Insert a 'details' column to the table
     */
    var nCloneTh = document.createElement('th');
    var nCloneTd = document.createElement('td');
    nCloneTd.innerHTML = '<i class="icon-plus-sign"></i>';
    nCloneTd.className = "center";

    $('#example2 thead tr').each(function() {
        this.insertBefore(nCloneTh, this.childNodes[0]);
    });

    $('#example2 tbody tr').each(function() {
        this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
    });

    /*
     * Initialse DataTables, with no sorting on the 'details' column
     */

    jQuery.fn.dataTableExt.oSort['string-case-asc'] = function(a, b) {
        return parseFloat(a) - parseFloat(b);
    };

    jQuery.fn.dataTableExt.oSort['string-case-desc'] = function(a, b) {
        return parseFloat(b) - parseFloat(a);
    };
    var oTable = $('#example2').dataTable({
        //"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'p i>>",
        "sDom": "<'row-fluid'<'span12'l >r>t<'row-fluid'<'span12'p i>>", // MIS
        "oLanguage": {
            "sLengthMenu": "_MENU_ ",
            "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
        },
        "aoColumns": [{
            "sTitle": "Browser"
        }, {
            "sTitle": "Total Visit",
            sType: 'string-case'
        }, {
            "sTitle": "Avg Load time (sec)"
        }],
        "aaSorting": [
            [1, "desc"]
        ]
    });


    browserTable = $('#browserTable').dataTable({

        //"sDom": "<'H'>t<'F'<'row-fluid'<'cust_pagination fr'>>>", // MIS
        "sDom": "<'row-fluid'<'span3 pull-right'l >r>t<'row-fluid'<'span12'p i>>", // MIS
        "oLanguage": {
            "sLengthMenu": "_MENU_ ",
            "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
        },
        "aoColumns": [{
                "sTitle": "Browser",
                "sWidth": "25%"
            }, {
                "sTitle": "Total Visit",
                sType: 'string-case',
                "sWidth": "25%"
            }, {
                "sTitle": "Avg Load time (sec)",
                "sWidth": "35%"
            }, {
                "sTitle": " ",
                "sWidth": "10%"
            }

        ],
        "aaSorting": [
            [1, "desc"]
        ]
    });

    deviceTable = $('#deviceTable').dataTable({

        //"sDom": "<'H'>t<'F'<'row-fluid'<'cust_pagination fr'>>>", // MIS
        "sDom": "<'row-fluid'<'span3 pull-right'l >r>t<'row-fluid'<'span12'p i>>", // MIS
        "oLanguage": {
            "sLengthMenu": "_MENU_ ",
            "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
        },
        "aoColumns": [{
                "sTitle": "Device",
                "sWidth": "25%"
            }, {
                "sTitle": "Total Visit",
                sType: 'string-case',
                "sWidth": "25%"
            }, {
                "sTitle": "Avg Load time (sec)",
                "sWidth": "35%"
            }, {
                "sTitle": " ",
                "sWidth": "10%"
            }

        ],
        "aaSorting": [
            [1, "desc"]
        ]
    });


    var oTable3 = $('#example3').dataTable({
        "sDom": "<'row-fluid'<'span6'l <'toolbar'>><'span6'f>r>t<'row-fluid'<'span12'p i>>",
        "oTableTools": {
            "aButtons": [{
                "sExtends": "collection",
                "sButtonText": "<i class='icon-cloud-download'></i>",
                "aButtons": ["csv", "xls", "pdf", "copy"]
            }]
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [0]
        }],
        "aaSorting": [
            [3, "desc"]
        ],
        "oLanguage": {
            "sLengthMenu": "_MENU_ ",
            "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
        },
    });
    //$("div.toolbar").html('<div class="table-tools-actions"><button class="btn btn-primary" style="margin-left:12px" id="test2">Add</button></div>');
    $("div.toolbar").html('<div class="table-tolos-actions"><button class="btn btn-primary pull-right" style="margin-left:12px" id="test2">Filter</button></div>'); // MIS
    $('#test2').on("click", function() {
        $("#quick-access").css("bottom", "0px");
    });

    $('#example2_wrapper .dataTables_filter input').addClass("input-medium ");
    $('#example2_wrapper .dataTables_length select').addClass("select2-wrapper span12");

    $('#example3_wrapper .dataTables_filter input').addClass("input-medium ");
    $('#example3_wrapper .dataTables_length select').addClass("select2-wrapper span12");


    /* Add event listener for opening and closing details
     * Note that the indicator for showing which row is open is not controlled by DataTables,
     * rather it is done here
     */
    $('#example2 tbody td i').live('click', function() {
        var nTr = $(this).parents('tr')[0];
        if (oTable.fnIsOpen(nTr)) {
            /* This row is already open - close it */
            this.removeClass = "icon-plus-sign";
            this.addClass = "icon-minus-sign";
            oTable.fnClose(nTr);
        } else {
            /* Open this row */
            this.removeClass = "icon-minus-sign";
            this.addClass = "icon-plus-sign";
            oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), 'details');
        }
    });

    $(".select2-wrapper").select2({
        minimumResultsForSearch: -1
    });

    function fnClickAddRow() {
        $('#example3').dataTable().fnAddData([
            $("#val1 option:selected").text(),
            $("#val2 option:selected").text(),
            "Windows",
            "789.", "A"
        ]);
    }

});


/* Formating function for row details */
function fnFormatDetails(oTable, nTr) {
    var aData = oTable.fnGetData(nTr);
    var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" class="inner-table">';
    sOut += '<tr><td>Rendering engine:</td><td>' + aData[1] + ' ' + aData[4] + '</td></tr>';
    sOut += '<tr><td>Link to source:</td><td>Could provide a link here</td></tr>';
    sOut += '<tr><td>Extra info:</td><td>And any further details here (images etc)</td></tr>';
    sOut += '</table>';

    return sOut;
}

Tables = (function() {

    var tb_visitor = $('#visitor_table').dataTable({
        "aoColumns": [{
            "sTitle": ''
        }, {
            "sTitle": "LoadTime"
        }, {
            "sTitle": "PageViews"
        }]
    })
    var tb_resourceTimer = $('#resourceTimerTable').dataTable({
        "sDom": 't',
        "aoColumns": [{
            "sTitle": "Name"
        }, {
            "sTitle": "Time (sec)"
        }]
    });

    function visitorTable(interval, timestamp, pageViews, loadTime) {
        $('#visitor_table thead th').eq(0).html(interval)
        tb_visitor.fnClearTable();
        for (key in timestamp) {
            if (timestamp.hasOwnProperty(key))
                tb_visitor.fnAddData([new Date(timestamp[key]).toLocaleString(), loadTime[key], pageViews[key]])
        }
    }

    function resourceTimeTable(data) {
        tb_resourceTimer.fnClearTable();
        for (key in data) {
            if (data.hasOwnProperty(key)) {
                var obj = data[key];
                var loadTime = Utility.milliToSec(obj['loadTime']);
                tb_resourceTimer.fnAddData([obj['label'], loadTime]);
            }
        }
        Utility.unblockUI($('#resourceTimerTable_wrapper'));
    }

    return {
        visitorTable: visitorTable,
        resourceTimeTable: resourceTimeTable
    }

}());

/*
 Data plugin for Highcharts
*/
(function(h){var k=h.each,m=function(b,a){this.init(b,a)};h.extend(m.prototype,{init:function(b,a){this.options=b;this.chartOptions=a;this.columns=b.columns||this.rowsToColumns(b.rows)||[];this.columns.length?this.dataFound():(this.parseCSV(),this.parseTable(),this.parseGoogleSpreadsheet())},getColumnDistribution:function(){var b=this.chartOptions,a=b&&b.chart&&b.chart.type,c=[];k(b&&b.series||[],function(b){c.push((h.seriesTypes[b.type||a||"line"].prototype.pointArrayMap||[0]).length)});this.valueCount=
{global:(h.seriesTypes[a||"line"].prototype.pointArrayMap||[0]).length,individual:c}},dataFound:function(){this.parseTypes();this.findHeaderRow();this.parsed();this.complete()},parseCSV:function(){var b=this,a=this.options,c=a.csv,d=this.columns,f=a.startRow||0,i=a.endRow||Number.MAX_VALUE,j=a.startColumn||0,e=a.endColumn||Number.MAX_VALUE,g=0;c&&(c=c.replace(/\r\n/g,"\n").replace(/\r/g,"\n").split(a.lineDelimiter||"\n"),k(c,function(c,h){var n=b.trim(c),p=n.indexOf("#")===0;h>=f&&h<=i&&!p&&n!==""&&
(n=c.split(a.itemDelimiter||","),k(n,function(b,a){a>=j&&a<=e&&(d[a-j]||(d[a-j]=[]),d[a-j][g]=b)}),g+=1)}),this.dataFound())},parseTable:function(){var b=this.options,a=b.table,c=this.columns,d=b.startRow||0,f=b.endRow||Number.MAX_VALUE,i=b.startColumn||0,j=b.endColumn||Number.MAX_VALUE,e;a&&(typeof a==="string"&&(a=document.getElementById(a)),k(a.getElementsByTagName("tr"),function(a,b){e=0;b>=d&&b<=f&&k(a.childNodes,function(a){if((a.tagName==="TD"||a.tagName==="TH")&&e>=i&&e<=j)c[e]||(c[e]=[]),
c[e][b-d]=a.innerHTML,e+=1})}),this.dataFound())},parseGoogleSpreadsheet:function(){var b=this,a=this.options,c=a.googleSpreadsheetKey,d=this.columns,f=a.startRow||0,i=a.endRow||Number.MAX_VALUE,j=a.startColumn||0,e=a.endColumn||Number.MAX_VALUE,g,h;c&&jQuery.getJSON("https://spreadsheets.google.com/feeds/cells/"+c+"/"+(a.googleSpreadsheetWorksheet||"od6")+"/public/values?alt=json-in-script&callback=?",function(a){var a=a.feed.entry,c,k=a.length,m=0,o=0,l;for(l=0;l<k;l++)c=a[l],m=Math.max(m,c.gs$cell.col),
o=Math.max(o,c.gs$cell.row);for(l=0;l<m;l++)if(l>=j&&l<=e)d[l-j]=[],d[l-j].length=Math.min(o,i-f);for(l=0;l<k;l++)if(c=a[l],g=c.gs$cell.row-1,h=c.gs$cell.col-1,h>=j&&h<=e&&g>=f&&g<=i)d[h-j][g-f]=c.content.$t;b.dataFound()})},findHeaderRow:function(){k(this.columns,function(){});this.headerRow=0},trim:function(b){return typeof b==="string"?b.replace(/^\s+|\s+$/g,""):b},parseTypes:function(){for(var b=this.columns,a=b.length,c,d,f,i;a--;)for(c=b[a].length;c--;)d=b[a][c],f=parseFloat(d),i=this.trim(d),
i==f?(b[a][c]=f,f>31536E6?b[a].isDatetime=!0:b[a].isNumeric=!0):(d=this.parseDate(d),a===0&&typeof d==="number"&&!isNaN(d)?(b[a][c]=d,b[a].isDatetime=!0):b[a][c]=i===""?null:i)},dateFormats:{"YYYY-mm-dd":{regex:"^([0-9]{4})-([0-9]{2})-([0-9]{2})$",parser:function(b){return Date.UTC(+b[1],b[2]-1,+b[3])}}},parseDate:function(b){var a=this.options.parseDate,c,d,f;a&&(c=a(b));if(typeof b==="string")for(d in this.dateFormats)a=this.dateFormats[d],(f=b.match(a.regex))&&(c=a.parser(f));return c},rowsToColumns:function(b){var a,
c,d,f,i;if(b){i=[];c=b.length;for(a=0;a<c;a++){f=b[a].length;for(d=0;d<f;d++)i[d]||(i[d]=[]),i[d][a]=b[a][d]}}return i},parsed:function(){this.options.parsed&&this.options.parsed.call(this,this.columns)},complete:function(){var b=this.columns,a,c,d=this.options,f,i,j,e,g,k;if(d.complete){this.getColumnDistribution();b.length>1&&(a=b.shift(),this.headerRow===0&&a.shift(),a.isDatetime?c="datetime":a.isNumeric||(c="category"));for(e=0;e<b.length;e++)if(this.headerRow===0)b[e].name=b[e].shift();i=[];
for(e=0,k=0;e<b.length;k++){f=h.pick(this.valueCount.individual[k],this.valueCount.global);j=[];for(g=0;g<b[e].length;g++)j[g]=[a[g],b[e][g]!==void 0?b[e][g]:null],f>1&&j[g].push(b[e+1][g]!==void 0?b[e+1][g]:null),f>2&&j[g].push(b[e+2][g]!==void 0?b[e+2][g]:null),f>3&&j[g].push(b[e+3][g]!==void 0?b[e+3][g]:null),f>4&&j[g].push(b[e+4][g]!==void 0?b[e+4][g]:null);i[k]={name:b[e].name,data:j};e+=f}d.complete({xAxis:{type:c},series:i})}}});h.Data=m;h.data=function(b,a){return new m(b,a)};h.wrap(h.Chart.prototype,
"init",function(b,a,c){var d=this;a&&a.data?h.data(h.extend(a.data,{complete:function(f){a.series&&k(a.series,function(b,c){a.series[c]=h.merge(b,f.series[c])});a=h.merge(f,a);b.call(d,a,c)}}),a):b.call(d,a,c)})})(Highcharts);

(function(f) {
    function A(a, b, c) {
        var d;
        !b.rgba.length || !a.rgba.length ? a = b.raw || "none" : (a = a.rgba, b = b.rgba, d = b[3] !== 1 || a[3] !== 1, a = (d ? "rgba(" : "rgb(") + Math.round(b[0] + (a[0] - b[0]) * (1 - c)) + "," + Math.round(b[1] + (a[1] - b[1]) * (1 - c)) + "," + Math.round(b[2] + (a[2] - b[2]) * (1 - c)) + (d ? "," + (b[3] + (a[3] - b[3]) * (1 - c)) : "") + ")");
        return a
    }
    var t = function() {},
        q = f.getOptions(),
        h = f.each,
        l = f.extend,
        B = f.format,
        u = f.pick,
        r = f.wrap,
        m = f.Chart,
        p = f.seriesTypes,
        v = p.pie,
        n = p.column,
        w = f.Tick,
        x = HighchartsAdapter.fireEvent,
        y = HighchartsAdapter.inArray,
        z = 1;
    /*h(["fill", "stroke"], function(a) {
        HighchartsAdapter.addAnimSetter(a, function(b) {
            b.elem.attr(a, A(f.Color(b.start), f.Color(b.end), b.pos))
        })
    });*/
    l(q.lang, {
        drillUpText: "â— Back to {series.name}"
    });
    q.drilldown = {
        activeAxisLabelStyle: {
            cursor: "pointer",
            color: "#0d233a",
            fontWeight: "bold",
            textDecoration: "underline"
        },
        activeDataLabelStyle: {
            cursor: "pointer",
            color: "#0d233a",
            fontWeight: "bold",
            textDecoration: "underline"
        },
        animation: {
            duration: 500
        },
        drillUpButton: {
            position: {
                align: "right",
                x: -10,
                y: 10
            }
        }
    };
    f.SVGRenderer.prototype.Element.prototype.fadeIn =
        function(a) {
            this.attr({
                opacity: 0.1,
                visibility: "inherit"
            }).animate({
                opacity: u(this.newOpacity, 1)
            }, a || {
                duration: 250
            })
        };
    m.prototype.addSeriesAsDrilldown = function(a, b) {
        this.addSingleSeriesAsDrilldown(a, b);
        this.applyDrilldown()
    };
    m.prototype.addSingleSeriesAsDrilldown = function(a, b) {
        var c = a.series,
            d = c.xAxis,
            g = c.yAxis,
            e;
        e = a.color || c.color;
        var i, f = [],
            j = [],
            k, o;
        if (!this.drilldownLevels) this.drilldownLevels = [];
        k = c.options._levelNumber || 0;
        (o = this.drilldownLevels[this.drilldownLevels.length - 1]) && o.levelNumber !==
            k && (o = void 0);
        b = l({
            color: e,
            _ddSeriesId: z++
        }, b);
        i = y(a, c.points);
        h(c.chart.series, function(a) {
            if (a.xAxis === d && !a.isDrilling) a.options._ddSeriesId = a.options._ddSeriesId || z++, a.options._colorIndex = a.userOptions._colorIndex, a.options._levelNumber = a.options._levelNumber || k, o ? (f = o.levelSeries, j = o.levelSeriesOptions) : (f.push(a), j.push(a.options))
        });
        e = {
            levelNumber: k,
            seriesOptions: c.options,
            levelSeriesOptions: j,
            levelSeries: f,
            shapeArgs: a.shapeArgs,
            bBox: a.graphic ? a.graphic.getBBox() : {},
            color: e,
            lowerSeriesOptions: b,
            pointOptions: c.options.data[i],
            pointIndex: i,
            oldExtremes: {
                xMin: d && d.userMin,
                xMax: d && d.userMax,
                yMin: g && g.userMin,
                yMax: g && g.userMax
            }
        };
        this.drilldownLevels.push(e);
        e = e.lowerSeries = this.addSeries(b, !1);
        e.options._levelNumber = k + 1;
        if (d) d.oldPos = d.pos, d.userMin = d.userMax = null, g.userMin = g.userMax = null;
        if (c.type === e.type) e.animate = e.animateDrilldown || t, e.options.animation = !0
    };
    m.prototype.applyDrilldown = function() {
        var a = this.drilldownLevels,
            b;
        if (a && a.length > 0) b = a[a.length - 1].levelNumber, h(this.drilldownLevels,
            function(a) {
                a.levelNumber === b && h(a.levelSeries, function(a) {
                    a.options && a.options._levelNumber === b && a.remove(!1)
                })
            });
        this.redraw();
        this.showDrillUpButton()
    };
    m.prototype.getDrilldownBackText = function() {
        var a = this.drilldownLevels;
        if (a && a.length > 0) return a = a[a.length - 1], a.series = a.seriesOptions, B(this.options.lang.drillUpText, a)
    };
    m.prototype.showDrillUpButton = function() {
        var a = this,
            b = this.getDrilldownBackText(),
            c = a.options.drilldown.drillUpButton,
            d, g;
        this.drillUpButton ? this.drillUpButton.attr({
                text: b
            }).align() :
            (g = (d = c.theme) && d.states, this.drillUpButton = this.renderer.button(b, null, null, function() {
                a.drillUp()
            }, d, g && g.hover, g && g.select).attr({
                align: c.position.align,
                zIndex: 9
            }).add().align(c.position, !1, c.relativeTo || "plotBox"))
    };
    m.prototype.drillUp = function() {
        for (var a = this, b = a.drilldownLevels, c = b[b.length - 1].levelNumber, d = b.length, g = a.series, e, i, f, j, k = function(b) {
                var c;
                h(g, function(a) {
                    a.options._ddSeriesId === b._ddSeriesId && (c = a)
                });
                c = c || a.addSeries(b, !1);
                if (c.type === f.type && c.animateDrillupTo) c.animate = c.animateDrillupTo;
                b === i.seriesOptions && (j = c)
            }; d--;)
            if (i = b[d], i.levelNumber === c) {
                b.pop();
                f = i.lowerSeries;
                if (!f.chart)
                    for (e = g.length; e--;)
                        if (g[e].options.id === i.lowerSeriesOptions.id && g[e].options._levelNumber === c + 1) {
                            f = g[e];
                            break
                        }
                f.xData = [];
                h(i.levelSeriesOptions, k);
                x(a, "drillup", {
                    seriesOptions: i.seriesOptions
                });
                if (j.type === f.type) j.drilldownLevel = i, j.options.animation = a.options.drilldown.animation, f.animateDrillupFrom && f.chart && f.animateDrillupFrom(i);
                j.options._levelNumber = c;
                f.remove(!1);
                if (j.xAxis) e = i.oldExtremes,
                    j.xAxis.setExtremes(e.xMin, e.xMax, !1), j.yAxis.setExtremes(e.yMin, e.yMax, !1)
            }
        this.redraw();
        this.drilldownLevels.length === 0 ? this.drillUpButton = this.drillUpButton.destroy() : this.drillUpButton.attr({
            text: this.getDrilldownBackText()
        }).align();
        this.ddDupes.length = []
    };
    n.prototype.supportsDrilldown = !0;
    n.prototype.animateDrillupTo = function(a) {
        if (!a) {
            var b = this,
                c = b.drilldownLevel;
            h(this.points, function(a) {
                a.graphic && a.graphic.hide();
                a.dataLabel && a.dataLabel.hide();
                a.connector && a.connector.hide()
            });
            setTimeout(function() {
                b.points &&
                    h(b.points, function(a, b) {
                        var e = b === (c && c.pointIndex) ? "show" : "fadeIn",
                            f = e === "show" ? !0 : void 0;
                        if (a.graphic) a.graphic[e](f);
                        if (a.dataLabel) a.dataLabel[e](f);
                        if (a.connector) a.connector[e](f)
                    })
            }, Math.max(this.chart.options.drilldown.animation.duration - 50, 0));
            this.animate = t
        }
    };
    n.prototype.animateDrilldown = function(a) {
        var b = this,
            c = this.chart.drilldownLevels,
            d, g = this.chart.options.drilldown.animation,
            e = this.xAxis;
        if (!a) h(c, function(a) {
            if (b.options._ddSeriesId === a.lowerSeriesOptions._ddSeriesId) d = a.shapeArgs,
                d.fill = a.color
        }), d.x += u(e.oldPos, e.pos) - e.pos, h(this.points, function(a) {
            a.graphic && a.graphic.attr(d).animate(l(a.shapeArgs, {
                fill: a.color
            }), g);
            a.dataLabel && a.dataLabel.fadeIn(g)
        }), this.animate = null
    };
    n.prototype.animateDrillupFrom = function(a) {
        var b = this.chart.options.drilldown.animation,
            c = this.group,
            d = this;
        h(d.trackerGroups, function(a) {
            if (d[a]) d[a].on("mouseover")
        });
        delete this.group;
        h(this.points, function(d) {
            var e = d.graphic,
                i = function() {
                    e.destroy();
                    c && (c = c.destroy())
                };
            e && (delete d.graphic, b ? e.animate(l(a.shapeArgs, {
                fill: a.color
            }), f.merge(b, {
                complete: i
            })) : (e.attr(a.shapeArgs), i()))
        })
    };
    v && l(v.prototype, {
        supportsDrilldown: !0,
        animateDrillupTo: n.prototype.animateDrillupTo,
        animateDrillupFrom: n.prototype.animateDrillupFrom,
        animateDrilldown: function(a) {
            var b = this.chart.drilldownLevels[this.chart.drilldownLevels.length - 1],
                c = this.chart.options.drilldown.animation,
                d = b.shapeArgs,
                g = d.start,
                e = (d.end - g) / this.points.length;
            if (!a) h(this.points, function(a, h) {
                a.graphic.attr(f.merge(d, {
                    start: g + h * e,
                    end: g + (h + 1) * e,
                    fill: b.color
                }))[c ?
                    "animate" : "attr"](l(a.shapeArgs, {
                    fill: a.color
                }), c)
            }), this.animate = null
        }
    });
    f.Point.prototype.doDrilldown = function(a, b) {
        var c = this.series.chart,
            d = c.options.drilldown,
            f = (d.series || []).length,
            e;
        if (!c.ddDupes) c.ddDupes = [];
        for (; f-- && !e;) d.series[f].id === this.drilldown && y(this.drilldown, c.ddDupes) === -1 && (e = d.series[f], c.ddDupes.push(this.drilldown));
        x(c, "drilldown", {
            point: this,
            seriesOptions: e,
            category: b,
            points: b !== void 0 && this.series.xAxis.ddPoints[b].slice(0)
        });
        e && (a ? c.addSingleSeriesAsDrilldown(this, e) :
            c.addSeriesAsDrilldown(this, e))
    };
    f.Axis.prototype.drilldownCategory = function(a) {
        var b, c, d = this.ddPoints[a];
        for (b in d)(c = d[b]) && c.series && c.series.visible && c.doDrilldown && c.doDrilldown(!0, a);
        this.chart.applyDrilldown()
    };
    f.Axis.prototype.getDDPoints = function(a, b) {
        var c = this.ddPoints;
        if (!c) this.ddPoints = c = {};
        c[a] || (c[a] = []);
        if (c[a].levelNumber !== b) c[a].length = 0;
        return c[a]
    };
    w.prototype.drillable = function() {
        var a = this.pos,
            b = this.label,
            c = this.axis,
            d = c.ddPoints && c.ddPoints[a];
        if (b && d && d.length) {
            if (!b.basicStyles) b.basicStyles =
                f.merge(b.styles);
            b.addClass("highcharts-drilldown-axis-label").css(c.chart.options.drilldown.activeAxisLabelStyle).on("click", function() {
                c.drilldownCategory(a)
            })
        } else if (b && b.basicStyles) b.styles = {}, b.css(b.basicStyles), b.on("click", null)
    };
    r(w.prototype, "addLabel", function(a) {
        a.call(this);
        this.drillable()
    });
    r(f.Point.prototype, "init", function(a, b, c, d) {
        var g = a.call(this, b, c, d),
            a = (c = b.xAxis) && c.ticks[d],
            d = c && c.getDDPoints(d, b.options._levelNumber);
        if (g.drilldown && (f.addEvent(g, "click", function() {
                    g.doDrilldown()
                }),
                d)) d.push(g), d.levelNumber = b.options._levelNumber;
        a && a.drillable();
        return g
    });
    r(f.Series.prototype, "drawDataLabels", function(a) {
        var b = this.chart.options.drilldown.activeDataLabelStyle;
        a.call(this);
        h(this.points, function(a) {
            a.drilldown && a.dataLabel && a.dataLabel.attr({
                "class": "highcharts-drilldown-data-label"
            }).css(b)
        })
    });
    var s, q = function(a) {
        a.call(this);
        h(this.points, function(a) {
            a.drilldown && a.graphic && a.graphic.attr({
                "class": "highcharts-drilldown-point"
            }).css({
                cursor: "pointer"
            })
        })
    };
    for (s in p) p[s].prototype.supportsDrilldown &&
        r(p[s].prototype, "drawTracker", q)
})(Highcharts);

Graph = {
	
//Load Time Graph Call
	loadTimePageViewGraph : function(data,xAxis,yAxis1,yAxis2,medianLoadTime,deploymentObj){
		_yAxis1 = yAxis1.slice(0);
		_yAxis2 = yAxis2.slice(0);
		for(key in _yAxis1){
			if(_yAxis1.hasOwnProperty(key))
			_yAxis1[key] = [xAxis[key],_yAxis1[key]]
			_yAxis2[key] = [xAxis[key],_yAxis2[key]]
		}		
		$('#visitor').highcharts({
				chart: {
					zoomType: 'x'
				},
				title: {
					text: ''
				},
				xAxis: [{
					minRange: 3600 * 1000,
					type: 'datetime',
					plotLines : deploymentObj
				}],
				yAxis: [
					{ // Primary yAxis
					labels: {
						format: '{value}',
						style: {
							color: '#89A54E'
						}
					},
					title: {
						text: 'No of Visitor',
						style: {
							color: '#89A54E'
						}
					}
				}, { // Secondary yAxis
					title: {
						text: 'Avg Time',
						style: {
							color: '#4572A7'
						}
					},
					plotLines : [{
					value : medianLoadTime,
					color : 'brown',
					dashStyle : 'shortdash',
					width : 2,
					label : {
						text : 'Median Load Time'
					}
				}],
					labels: {
						format: '{value} sec',
						style: {
							color: '#4572A7'
						}
					},
					opposite: true					
				}],
				tooltip: {
					shared: true
				},
				legend: {              
					backgroundColor: '#FFFFFF'
				},
				series: [{
					name: 'No of Visitor',
					color: '#89A54E',
					type: 'column',
					data: _yAxis1,//[1, 1.1, 1.7, 1, 2.5, 10, 2, 3.5, 2.5, 1.8, 2, 3,2.5, 1.8, 2, 3],
					pointStart: xAxis[0],
					tooltip: {
						valueSuffix: ''
						}
					},
					{
					name: 'Avg Time',
					color: '#4572A7',
					type: 'spline',
					yAxis: 1,
					zIndex:1,
					data: _yAxis2,//[1, 1.1, 1.7, 1, 2.5, 10, 2, 3.5, 2.5, 1.8, 2, 3,2.5, 1.8, 2, 3],
					pointStart: xAxis[0],
					tooltip: {
						valueSuffix: 'sec'
						}
					},
				],

			});
	},	
	threshold : [2,5,Number.MAX_VALUE],//'' signify values ahead
	vistorBucket : [0,0,0],
	visitorSum : 0,
	colors : ['#06c27a','#14a4d2','#f1764b'],
	fillVisitorBucket : function(time,visitor){
		for(var i=0;i<this.threshold.length;i++)
		{
			if(parseInt(time)<=this.threshold[i])
			{				
				this.vistorBucket[i]+=parseInt(visitor);  
				this.visitorSum+=parseInt(visitor);
				return this.colors[i];
			}
		}		
	},
	percentage : function(){
		var a = [];
		for(var i=0;i<this.vistorBucket.length;i++){
			a[i] = parseInt(((this.vistorBucket[i]/this.visitorSum)*100).toFixed(0));
		}
		return a;
	},
//timeChartPerSecond
	timeChart :function(data,arr,colors,color){
		
		arr = [],sec = [];

		for(key in data){			
			if(data.hasOwnProperty(key)){
				sec.push(arr.length) 
//				color = Graph.fillVisitorBucket((arr.length+1),data[key]);			
				color = Graph.fillVisitorBucket((arr.length),data[key]);			
				arr.push({y:parseInt(data[key]),color:color}); 	

			}
		}		

		$('#timeChart').highcharts({
			title: {
				text: ''
			},
			chart: {
				type: 'column'
			},
			xAxis: {
				categories: sec,//['1 Sec', '2 Sec', '3 Sec', '4 Sec', '5 Sec', '6 Sec', '7 Sec', '8 Sec', '9 Sec', '10 Sec', '11 Sec', '12 Sec']
				title: {
					text: 'Seconds took'
				},
			},
			yAxis: {
			title: {
				text: 'Number of Visitors'
			}},
			legend: {
				enabled:0
			},
			credits: {
				enabled:0
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{point.x} second</span><br>',
				pointFormat: '<span style="color:{point.color}"><b>{point.y} visitors</b><br/>'
			},
			series: [{
				name: 'Visitors',
				data: arr//[{y:280.9,color:"#06c27a"}, {y:71.5,color:"#06c27a"}, {y:144.0,color:"#06c27a"}, {y:176.0,color:"#06c27a"}, {y:135.6,color:"#14a4d2"}, {y:148.5,color:"#14a4d2"}, {y: 216.4,color: '#14a4d2'}, {y: 50,color: '#f1764b'} , {y: 20,color: '#f1764b'}]
			}]
		});	
		Graph.userExperience();
	},//user exp chart
	userExperience : function(){
		var arr = Graph.percentage();	
		$('#userExp').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: 0,
				plotShadow: false
			},
			title: {
				text: 'User<br>Experience',
				align: 'center',
				verticalAlign: 'middle',
				y: 10,
				style: {
							fontSize: '14px'
						}
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					dataLabels: {
						enabled: true,
						distance: -35,
						style: {
							fontWeight: 'bold',
							color: 'white',
							textShadow: '0px 1px 2px black'
						}
					},
					startAngle: -120,
					endAngle: 120,
					center: ['50%', '75%'],
					size: 260
				}
			},area: {
					fillColor: '#000000',
				},
				credits: {
				enabled:0
			},
			series: [{
				type: 'pie',
				name: 'Browser share',
				innerSize: '90%',
				data: [{name:"Happy",y:arr[0],color:this.colors[0]},{name:"Satisfied",y:arr[1],color:this.colors[1]},{name:"Unhappy",y:arr[2],color:this.colors[2]}]
			}]
		});
	},
	platformData:function(data){		
		var obj1 = {name:'Desktop',y:0,drilldown:'desktop'}
		var obj2 = {name:'Mobile',y:0,drilldown:'mobile'}
		var obj3 = {name:'Tablet',y:0,drilldown:'tablet'}		
		
		var series1 = [obj1,obj2,obj3]
				
		var dobj1 = {id:'desktop',name: 'Desktop Os',data:[]}
		var dobj2 = {id:'mobile',name: 'Mobile Os',data:[]}
		var dobj3 = {id:'tablet',name: 'Tablet Os',data:[]}		
		
		var dseries1 = [dobj1,dobj2,dobj3];		
		
		var fillData = function(arr,name,value){			
				arr.push([name||'Unidentified',value]);
		}		
		
		
		//Filling OS data				
		for(key in data){
			if(data.hasOwnProperty(key)){
				var val = parseInt(data[key].pageViews);
				if(data[key].type=='desktop'){
					obj1.y+= val;
					fillData(dobj1.data,data[key].label,val);
					regArr = data[key].label.match(/(^.*?)([0-9.]*) ?$/);
					//dskTopOs.push(regArr[1]);
				}
				else if(data[key].type=='mobile'){
					obj2.y+= val;					
					fillData(dobj2.data,data[key].label,val);
					regArr = data[key].label.match(/(^.*?)([0-9.]*) ?$/);
					//mobOsArr.push(regArr[1]);
				}				
				else if(data[key].type=='tablet'){
					obj3.y+= val;
					fillData(dobj3.data,data[key].label,val);
					regArr = data[key].label.match(/(^.*?)([0-9.]*) ?$/);
					//tbltOsArr.push(regArr[1]);
				}
				
			}
		}
		
		var osArr = {desktop:[{os:"windows",count:0},{os:"linux",count:0},{os:"ubuntu",count:0},{os:"mac",count:0}],
					mobile:[{os:"android",count:0},{os:"blackberry",count:0},{os:"windows",count:0}],
					tablet:[{os:"android",count:0}]
					};
		var obj1 = {name:'Desktop',y:0};
		var obj2 = {name:'Mobile',y:0};
		var obj3 = {name:'Tablet',y:0};
		
		var dskTop=[],mobile=[],tablet=[];
		
		var dskTopOs = [],
		mobOsArr = [],
		tbltOsArr=[];
		var osReg = /(^.*?)([0-9.]*) ?$/;
		var regArr;
		for(key in data){
			if(data.hasOwnProperty(key)){
				if(data[key].type=="desktop"){
				dskTop.push(data[key]);
				obj1.y+= parseInt(data[key].pageViews);
				regArr = data[key].label.match(osReg);
				dskTopOs.push(regArr[1]);
				}
				else if(data[key].type=="mobile"){
				mobile.push(data[key]);
				obj2.y+= parseInt(data[key].pageViews);
				regArr = data[key].label.match(osReg);
				mobOsArr.push(regArr[1]);
				}
				else if(data[key].type=="tablet"){
				tablet.push(data[key]);
				obj3.y+=  parseInt(data[key].pageViews);
				regArr = data[key].label.match(osReg);
				tbltOsArr.push(regArr[1]);
				}
				
			}
		}
		dskTopOs = $.grep(dskTopOs, function(el, index) {
        return index == $.inArray(el, dskTopOs)  ;
		});
		 mobOsArr = $.grep(mobOsArr, function(el, index) {
        return index == $.inArray(el, mobOsArr);
		});
		 tbltOsArr = $.grep(tbltOsArr, function(el, index) {
        return index == $.inArray(el, tbltOsArr);
		});
		var osNames = [dskTopOs,mobOsArr,tbltOsArr];
		var dskObj={},mobObj={},tabObj={};
		$.each(osNames,function(idx,arr){
			if(idx==0){
				$.each(arr,function(index,val){
					dskObj[(val.split(" ")[0]).toLowerCase()] = []
				})
			
			}
			else if(idx==1){
				$.each(arr,function(index,val){
					mobObj[(val.split(" ")[0]).toLowerCase()] = []
				})
			
			}
			else if(idx==2){
				$.each(arr,function(index,val){
					tabObj[(val.split(" ")[0]).toLowerCase()] = []
				})
			
			}
		});
		var totObj=[dskTop,mobile,tablet];
		for (var i=0;i < totObj.length;i++){
			if(i==0){
			var deskObj=totObj[i];
			var drill1 = {level: 1,data:[]};
			var deskObj2 = dskObj;//{"windows":[],"linux":[],"ubuntu":[],"mac":[]};
				for(var j =0 ;j < deskObj.length ; j++){
					var tmpObj = deskObj[j];
					for (var k in deskObj2){
						if(tmpObj.label.toLowerCase().indexOf(k) > -1){
						deskObj2[k].push(tmpObj);
						}
					}
					/*if(tmpObj.label.toLowerCase().indexOf("windows") > -1){
					deskObj2.windows.push(tmpObj);
					}
					else if(tmpObj.label.toLowerCase().indexOf("linux") > -1){
					deskObj2.linux.push(tmpObj);
					}
					else if(tmpObj.label.toLowerCase().indexOf("ubuntu") > -1){
					deskObj2.ubuntu.push(tmpObj);
					}
					else if(tmpObj.label.toLowerCase().indexOf("mac") > -1){
					deskObj2.mac.push(tmpObj);
					}*/
				}
			for (k in deskObj2){
			 var ob = deskObj2[k];
			 var data = {y:0,name:k};
			 var pgViews=0;
			 var dtArr =[];
			 var drill2 = {level:2}
				for(var ii= 0 ; ii < ob.length; ii++){				
				 dtArr.push([ob[ii].label,parseInt(ob[ii].pageViews)]);
				 data.y+=  parseInt(ob[ii].pageViews);
				
				}
				drill2.data = dtArr;
				data.drilldown = drill2;
				drill1.data.push(data);
			}
			obj1.drilldown=drill1;
			}
			if(i==1){
			var deskObj=totObj[i];
			var drill1 = {level: 1,data:[]};
			var deskObj2 = mobObj;//{"android":[],"linux":[],"windows":[],"blackberry":[],"ios":[],"others":[]};
				for(var j =0 ;j < deskObj.length ; j++){
					var tmpObj = deskObj[j];
					for (var k in deskObj2){
						if(tmpObj.label.toLowerCase().indexOf(k) > -1){
						deskObj2[k].push(tmpObj);
						}
					}
					/*if(tmpObj.label.toLowerCase().indexOf("android") > -1){
					deskObj2.android.push(tmpObj);
					}
					else if(tmpObj.label.toLowerCase().indexOf("windows") > -1){
					deskObj2.windows.push(tmpObj);
					}
					else if(tmpObj.label.toLowerCase().indexOf("linux") > -1){
					deskObj2.linux.push(tmpObj);
					}
					else if(tmpObj.label.toLowerCase().indexOf("blackberry") > -1){
					deskObj2.blackberry.push(tmpObj);
					}
					else if(tmpObj.label.toLowerCase().indexOf("ios") > -1){
					deskObj2.ios.push(tmpObj);
					}
					else if(tmpObj.label.toLowerCase().indexOf("other") > -1){
					deskObj2.others.push(tmpObj);
					}*/
				}
			for (k in deskObj2){
			 var ob = deskObj2[k];
			 var data = {y:0,name:k};
			 var pgViews=0;
			 var dtArr =[];
			 var drill2 = {level:2}
				for(var ii= 0 ; ii < ob.length; ii++){				
				 dtArr.push([ob[ii].label,parseInt(ob[ii].pageViews)]);
				 data.y+=  parseInt(ob[ii].pageViews);
				
				}
				drill2.data = dtArr;
				data.drilldown = drill2;
				drill1.data.push(data);
			}
			obj2.drilldown=drill1;
			}
			if(i==2){
			var deskObj=totObj[i];
			var drill1 = {level: 1,data:[]};
			var deskObj2 = {"windows":[],"linux":[],"ubuntu":[],"mac":[]};
				for(var j =0 ;j < deskObj.length ; j++){
					var tmpObj = deskObj[j];
					if(tmpObj.label.toLowerCase().indexOf("windows") > -1){
					deskObj2.windows.push(tmpObj);
					}
					else if(tmpObj.label.toLowerCase().indexOf("linux") > -1){
					deskObj2.linux.push(tmpObj);
					}
					else if(tmpObj.label.toLowerCase().indexOf("ubuntu") > -1){
					deskObj2.ubuntu.push(tmpObj);
					}
					else if(tmpObj.label.toLowerCase().indexOf("mac") > -1){
					deskObj2.mac.push(tmpObj);
					}
				}
			for (k in deskObj2){
			 var ob = deskObj2[k];
			 var data = {y:0,name:k};
			 var pgViews=0;
			 var dtArr =[];
			 var drill2 = {level:2}
				for(var ii= 0 ; ii < ob.length; ii++){				
				 dtArr.push([ob[ii].label,parseInt(ob[ii].pageViews)]);
				 data.y+=  parseInt(ob[ii].pageViews);
				
				}
				drill2.data = dtArr;
				data.drilldown = drill2;
				drill1.data.push(data);
			}
			obj3.drilldown=drill1;
			}
			
		
		}
		var data=[obj1,obj2,obj3];
		/*var data = [{
        name: 'A-1',
        y: 55,
       
        drilldown: {
            //begin alcohol
            name: 'A-1',
            categories: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
            data: [122252, 3516, 4036, 3557, 4021, 3624, 3847],
           
            data: [{
                y: 33.06,
                name: 'A',
                drilldown: {
                    name: 'Budweiser',
                    categories: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
                    data: [10838, 11349, 11894, 11846, 11878, 11662, 11652, 11438, 11833, 12252],
                    
                }},
            {
                y: 10.85,
                name: 'B',
                drilldown: {
                    name: 'Heinekein',
                    categories: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
                    data: [2266, 2396, 2431, 2380, 2357, 3516],
                    
                }},
            {
                y: 7.35,
                name: 'C',
                drilldown: {
                    name: 'Jack Daniels',
                    categories: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
                    data: [1583, 1580, 1612, 4036],
                    
                }},
            {
                y: 2.41,
                name: 'D',
                drilldown: {
                    name: 'Johnnie Walker',
                    categories: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
                    data: [1649, 1654, 1724, 3557],
                    
                }},
            {
                y: 2.41,
                name: 'E',
                drilldown: {
                    name: 'Moet & Chandon',
                    categories: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
                    data: [2470, 2445, 2524, 2861, 2991, 3257, 3739, 3951, 3754, 4021],
                   
                }},
            {
                y: 2.41,
                name: 'F',
                drilldown: {
                    name: 'Smirnoff',
                    categories: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
                    data: [2594, 2723, 5600, 2975, 3097, 3032, 3379, 3590, 7350, 3624],
                   
                }},
            {
                y: 2.41,
                name: 'G',
                drilldown: {
                    name: 'Corona',
                    categories: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
                    data: [3847],
                    
                }}],
        }},
    { 
        name: 'B-1',
        y: 11.94,
       
        drilldown: {
            name: 'B',
            categories: ['A-2', 'B-2', 'C-2'],
            
            data: [{
                y: 33.06,
                name: 'A',
                drilldown: {
                    name: 'A',
                    categories: ['A', 'B'],
                    data: [4444, 6666],
                    
                },
                },
            {
                name: 'B',
                y: 10.85,
                drilldown: {
                    name: 'B',
                    categories: ['A', 'B'],
                    data: [22222, 6005],
                    
                },
                },
            {
                name: 'C',
                y: 7.35,
                drilldown: {
                    name: 'C',
                    categories: ['2011'],
                    data: [3605],
                    
                }}],
        }},
    ];*/
	var chartInst;
		var chart = $('#browserShare').highcharts({
			chart: {
				type: 'pie'
			},
			title: {
				text: 'OS wise Visitor Distribution'
			},
			subtitle: {
				text: 'Click the slices to view versions.'
			},
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						formatter: function() {
                        return '<b>'+this.point.name+'</b>:'+Math.round(this.percentage*100)/100 + '%';
                    	}
					},
					allowPointSelect: true					
					
				},pie: {
					size: '50%',
					allowPointSelect: true,
					point: {
                      events: {
                          click: function () {
										  var chart = this.series.chart,
                                  drilldown = this.drilldown;
                              if (drilldown) { // drill down
                                  if(drilldown.level > 0)
									$('#resetChart').parent().css("display","block");
								  chart.setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color, drilldown.level);
								  //chart.showDrillUpButton();

                              } else { // restore
                                  //chart.setChart(name, categories, data, null, level);
								  return false;

                              }
                          }
                      }
                  }
					
				
				}
				
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b>'
			},
			series: [{
					name: 'OS Type',
					colorByPoint: true,
					data: data,
					level:0
				}]
		}
		,
      /*Remember to rename chart functions for pie chart so it is unique from bar*/
      function (chart) {
	  chartInst = chart;
          chart.upper = [];

          var up = false;

          chart.setChart = function (name, categories, data, color, level) {
              //chart.xAxis[0].setCategories(categories);
              if (name === true && chart.upper.length) {

              }

              if (up === false) {
                  //chart.toolbar.add('up', 'Level up', 'Level up', function(){ chart.setChart(true); });
                  up = true;
              }

              chart.upper.push(chart.series[0].options);
              chart.series[0].remove();
              chart.addSeries({
                  name: name,
                  data: data,
                  level: level,
                  color: color || 'white'
              });




          }



      });
		$('#resetChart').on("click", function (e) {
          chartInst.setChart('OS Type', null, data, null, 0);
      });
	},
	
	browserPieData:function(data){
		var distinctBrowser=new Array();
		var series2=[];
		var dseries2=[];
		var mainObj=[];	
		var digObj=[];	
		for(key in data){
			if(data.hasOwnProperty(key)){
 			if ($.inArray(data[key].label, distinctBrowser) == -1)
                    distinctBrowser.push(data[key].label);
			}
		}

		
		for(var x in distinctBrowser){
		mainObj[x]={name:distinctBrowser[x],y:0,drilldown:distinctBrowser[x]+' Version'};
		series2.push(mainObj[x]);
		digObj[x]={id:distinctBrowser[x]+" Version",name:distinctBrowser[x]+' Version',data:[]}
		dseries2.push(digObj[x]);
		}

		var fillData = function(arr,name,value){			
				arr.push([name||'Unidentified',value]);
		}		
		
		//Filling OS data				
		for(key in data){
			if(data.hasOwnProperty(key)){
 			for(var x in distinctBrowser){
				if(data[key].label==distinctBrowser[x]){
				mainObj[x].y++;
				fillData(digObj[x].data,distinctBrowser[x]+" "+data[key].version,parseInt(data[key].pageViews))	
					}
			}}
		}
		
		$('#browser-pie').highcharts({
			chart: {
				type: 'pie'
			},
			title: {
				text: 'Browser wise Visitor Distribution'
			},
			subtitle: {
				text: 'Click the slices to view versions.'
			},
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						formatter: function() {
                        return '<b>'+this.point.name+'</b>:'+Math.round(this.percentage*100)/100 + '%';
                    	}
					}
				},pie: {
					size: '50%',
					//allowPointSelect: true
				}
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
			},
			series: [{
					name: 'Browser Type',
					colorByPoint: true,
					data: series2
				}],
			drilldown: {
				series: dseries2
				}
		})	
	},
	
	navTime : function(data,map){
		var series = [];
		var color = this.colors;
		map = {'frontendTime':'Page Render','backendTime':'First Byte','networkTime':'DNS Lookup Time'}

		for(key in map){
			var value = Utility.milliToSec(data[key],true);		
			var obj = {name:map[key],data:[value],color:color[(series.length)%color.length]}
			series.push(obj);
		} 
		$('#container').highcharts({
			chart: {
				type: 'bar',
				renderTo: 'container',

			},
			title: {
				text: 'Navigation Time'
			},
			xAxis: {
				type: 'datetime',
				categories: ['Time'],

			},
			legend: {
				backgroundColor: '#FFFFFF',
				reversed:true,
			},
			tooltip: {
				borderRadius: '0px'
			},
			plotOptions: {
				area: {
					fillColor: '#000000',
				},
				series: {
					stacking: 'normal'
				}
			},
			 series:series
	
		});
	}
}
// JavaScript Document

$(document).ready(function(){

	Utility.blockUI($('#container, #visitor, #userExp, #browserTable, #deviceTab, #resourceTimerTable_wrapper'));



	calls.init({name:'loadStatesData',url:serviceBaseUrl+'/boomGetBreakupTimes.php',responseVarName:'loadStatesData',callback:callback.loadStatesData}).trigger({el:$('#loadStatesData')});
	calls.init({name:'browserData',url:serviceBaseUrl+'/boomGetBrowserWiseLoadTimesAndPageViews.php',responseVarName:'browserData',callback:callback.browserData}).trigger({el:''});
	calls.init({name:'loadTimePageView',url:serviceBaseUrl+'/boomGetLoadTimesAndPageViews.php',responseVarName:'loadTimePageView',callback:callback.loadTimePageViewData}).trigger({el:$('#visitor')});
	calls.init({name:'loadTimeRanges',url:serviceBaseUrl+'/boomGetLoadTimeRanges.php',responseVarName:'loadTimeRanges',callback:Graph.timeChart}).trigger({el:$('#timeChart')});
	calls.init({name:'platformData',url:serviceBaseUrl+'/boomGetOSWiseLoadTimesAndPageViews.php',responseVarName:'platformData',callback:callback.platformData}).trigger({el:$('#browserShare')});
	calls.init({name:'resourceTimer',url:serviceBaseUrl+'/boomGetCustomTimes.php',responseVarName:'resourceTimer',callback:Tables.resourceTimeTable}).trigger({el:$('#_')});

	//expanding browser list
	$('#browserTable tbody td i').live('click', function () {
        var nTr = $(this).parents('tr')[0];
		var name=$(this).data('name');
        if ( browserTable.fnIsOpen(nTr) )
        {
			$(this).removeClass("icon-minus-sign").addClass("icon-plus-sign");
            browserTable.fnClose( nTr );
        }
        else
        {
            $(this).removeClass("icon-plus-sign").addClass("icon-minus-sign");
            browserTable.fnOpen( nTr, fnBrowserDetails( browserTable, nTr,name), 'details' );
        }
    });
	
	$('#deviceTable tbody td i').live('click', function () {
        var nTr = $(this).parents('tr')[0];
		var name=$(this).data('name');
        if ( deviceTable.fnIsOpen(nTr) )
        {
			$(this).removeClass("icon-minus-sign").addClass("icon-plus-sign");
            deviceTable.fnClose( nTr );
        }
        else
        {
            $(this).removeClass("icon-plus-sign").addClass("icon-minus-sign");
            deviceTable.fnOpen( nTr, fnplatformDetails( deviceTable, nTr,name), 'details' );
        }
    });

})

/** Hold callback related Function */
callback = {
	loadStatesData : function(data){
		Graph.navTime(data)
		map = {networkTime:'#t_dns',backendTime:'#t_resp',frontendTime:'#t_page',domReadyTime:'#t_domLoaded'}
		for(key in map){
			var value = Utility.milliToSec(data[key]);
			$(map[key]+' strong value').text(value);
		}
	},
	browserData :function(data){
		total_pageViews=0,total_loadTime=0;distinctBr=[];
		for(key in data){
 			if(!distinctBr[data[key].label]){
			distinctBr[data[key].label]={};
			}
		}
		for(var i in data){
			total_pageViews+=parseInt(data[i].pageViews);
			total_loadTime+=parseInt(data[i].loadTime);
		}
		for(var i in distinctBr){
			var pgView=0; var loadTime=0,wloadTime=0;
			var total_version=0;
			for(var x in data){
				if(i==data[x].label){
					pgView+=parseInt(data[x].pageViews);
					wloadTime += data[x].loadTime * data[x].pageViews;
					loadTime+=parseInt(data[x].loadTime);
					total_version+=1;
				}
			}
			if(distinctBr[i].pageViews==null){
				distinctBr[i].pageViews=Utility.toFixed(100*(pgView/total_pageViews));
			}
			if(distinctBr[i].avgLoadTime==null){
				distinctBr[i].avgLoadTime=Utility.milliToSec(wloadTime/pgView);
			}
		}
		browserTable.fnClearTable();
		for(var i in distinctBr){
			var add = '<i class="icon-plus-sign" data-name="'+i+'"></i>';
			browserTable.fnAddData([i||'Unidentified',distinctBr[i].pageViews+'%',distinctBr[i].avgLoadTime,add]);
		}

		Utility.unblockUI($('#browserTable'));

		Graph.browserPieData(data);
	},
		platformData :function(data){
		total_pageViews=0,total_loadTime=0;distinctBr=[];
		for(key in data){
 			if(!distinctBr[data[key].type]){
			distinctBr[data[key].type]={};
			}
		}
		for(var i in data){
			total_pageViews+=parseInt(data[i].pageViews);
			total_loadTime+=parseInt(data[i].loadTime);
		}
		for(var i in distinctBr){
			var pgView=0; var loadTime=0,wloadTime=0;
			var total_version=0;
			for(var x in data){
				if(i==data[x].type){
					pgView+=parseInt(data[x].pageViews);
					wloadTime += data[x].loadTime * data[x].pageViews;
					loadTime+=parseInt(data[x].loadTime);
					total_version+=1;
				}
			}
			if(distinctBr[i].pageViews==null){
				distinctBr[i].pageViews=Utility.toFixed(100*(pgView/total_pageViews));
			}
			if(distinctBr[i].avgLoadTime==null){
				distinctBr[i].avgLoadTime=Utility.milliToSec(wloadTime/pgView);
			}
		}
		deviceTable.fnClearTable();
		for(var i in distinctBr){
			var add = '<i class="icon-plus-sign" data-name="'+i+'"></i>';
			deviceTable.fnAddData([i||'Unidentified',distinctBr[i].pageViews+'%',distinctBr[i].avgLoadTime,add]);
		}

		Utility.unblockUI($('#deviceTab'));

		Graph.platformData(data);
	},
	loadTimePageViewData : function(data,medianLoadTime){
		callback.plotLineArray = [];
		xAxis = [];yAxis1=[];yAxis2=[];

		for(key in data.pageViews){

			var arr = key.split(/-|\s|:/);
			arr[1] = parseInt(arr[1])-1;
			xAxis[xAxis.length] = Date.UTC.apply(Date.UTC,arr);
			/** pageviews can be null */
			yAxis1[yAxis1.length] = parseFloat(data.pageViews[key]||0);
		}
		for(key in data.loadTimes)
		{
			yAxis2[yAxis2.length] = Utility.milliToSec(data.loadTimes[key],true);
		}
		medianLoadTime = Utility.getMedian(yAxis2)
		$('#lt_median value').html(Utility.toFixed(medianLoadTime));
		$('#lt_average value').html(Utility.toFixed(Utility.getAverage(yAxis2,yAxis1)));
		$('#pgv_total value').html(Utility.getTotal(yAxis1));
		
		deploymentLineGraphCall(data,xAxis,yAxis1,yAxis2,medianLoadTime,callback.plotLineArray);
//		Graph.loadTimePageViewGraph(data,xAxis,yAxis1,yAxis2,medianLoadTime,this.plotLineArray);
		Tables.visitorTable(data.interval,xAxis,yAxis1,yAxis2);
	}
}

	/* deployemnt line on loadTimePageView graph */
function deploymentLineGraphCall(data,xAxis,yAxis1,yAxis2,medianLoadTime,plotLineArray){	
	var startDate = Utility.getUrlParam('startDate');
	var endDate = Utility.getUrlParam('endDate');
	var appId = Utility.getUrlParam('appId');

	calls.init({name:'deploymentLine',url:serviceBaseUrl+'/newMonkGetDeployments.php?startDate='+startDate+'&endDate='+endDate+'&appId='+appId+'&callback=deployemntCB&offset=0&count=50',callback:'callback.cb'}).trigger({el:''});	
	
	
	deployemntCB=function(data){
		$.each(data.deployments,function(date,dData){
			var arr = date.split(/-|\s|:/);
			arr[1] = parseInt(arr[1])-1;
			var deploymentDate = Date.UTC.apply(Date.UTC,arr);
			callback.plotLineArray.push({
				'value' :deploymentDate ,
				'color' : 'brown',
				'dashStyle' : 'solid',
				'width' : 2,
				'zIndex':1,
				'label': {
					'text':'<span style="font-weight:bold;">'+dData.tag+'</span>'
        		}
			});
		});
	Graph.loadTimePageViewGraph(data,xAxis,yAxis1,yAxis2,medianLoadTime,plotLineArray);
	}
}

/* function for expanded table view*/
function fnBrowserDetails (table, nTr,name)
{
	var name = name;
	var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="width:100%" class="inner-table browser">';
	//sOut+='<th><td>Version</td><td>Page Views</td><td>Load Time(Sec)</td></th>';l
	for(var x in browserData){
		if(browserData[x].label==name){
			var pgView=Utility.toFixed(100*(browserData[x].pageViews/total_pageViews));
			sOut+='<tr><td style="width:26%;">'+ browserData[x].label+" "+ browserData[x].version+'</td><td style="width:27%">'+pgView+'%'+'</td><td>'+Utility.milliToSec(browserData[x].loadTime)+'</td><td></td></tr>';
		}
	}
	sOut += '</table>';
    return sOut;
}

function fnplatformDetails (table, nTr,name)
{
	var name = name;
	var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="width:100%" class="inner-table browser">';
	//sOut+='<th><td>Version</td><td>Page Views</td><td>Load Time(Sec)</td></th>';l
	for(var x in platformData){
		if(platformData[x].type==name){
			var pgView=Utility.toFixed(100*(platformData[x].pageViews/total_pageViews));
			sOut+='<tr><td style="width:26%;">'+ platformData[x].label+" "+'</td><td style="width:27%">'+pgView+'%'+'</td><td>'+Utility.milliToSec(platformData[x].loadTime)+'</td><td></td></tr>';
		}
	}
	sOut += '</table>';
    return sOut;
}
