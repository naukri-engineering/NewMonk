/*
 * heatmap.js 1.0 -    JavaScript Heatmap Library
 *
 * Copyright (c) 2011, Patrick Wied (http://www.patrick-wied.at)
 * Dual-licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and the Beerware (http://en.wikipedia.org/wiki/Beerware) license.
 */

(function(w){
    // the heatmapFactory creates heatmap instances
    var heatmapFactory = (function(){

    // store object constructor
    // a heatmap contains a store
    // the store has to know about the heatmap in order to trigger heatmap updates when datapoints get added
    var store = function store(hmap){

        var _ = {
            // data is a two dimensional array
            // a datapoint gets saved as data[point-x-value][point-y-value]
            // the value at [point-x-value][point-y-value] is the occurrence of the datapoint
            data: [],
            // tight coupling of the heatmap object
            heatmap: hmap
        };
        // the max occurrence - the heatmaps radial gradient alpha transition is based on it
        this.max = 1;

        this.get = function(key){
            return _[key];
        };
        this.set = function(key, value){
            _[key] = value;
        };
    }

    store.prototype = {
        // function for adding datapoints to the store
        // datapoints are usually defined by x and y but could also contain a third parameter which represents the occurrence
        addDataPoint: function(x, y){
            if(x < 0 || y < 0)
                return;

            var me = this,
                heatmap = me.get("heatmap"),
                data = me.get("data");

            if(!data[x])
                data[x] = [];

            if(!data[x][y])
                data[x][y] = 0;

            // if count parameter is set increment by count otherwise by 1
            data[x][y]+=(arguments.length<3)?1:arguments[2];
            
            me.set("data", data);
            // do we have a new maximum?
            if(me.max < data[x][y]){
                // max changed, we need to redraw all existing(lower) datapoints
                heatmap.get("actx").clearRect(0,0,heatmap.get("width"),heatmap.get("height"));
                me.setDataSet({ max: data[x][y], data: data }, true);
                return;
            }
            heatmap.drawAlpha(x, y, data[x][y], true);
        },
        setDataSet: function(obj, internal){
            var me = this,
                heatmap = me.get("heatmap"),
                data = [],
                d = obj.data,
                dlen = d.length;
            // clear the heatmap before the data set gets drawn
            heatmap.clear();
            this.max = obj.max;
            // if a legend is set, update it
            heatmap.get("legend") && heatmap.get("legend").update(obj.max);
            
            if(internal != null && internal){
                for(var one in d){
                    // jump over undefined indexes
                    if(one === undefined)
                        continue;
                    for(var two in d[one]){
                        if(two === undefined)
                            continue;
                        // if both indexes are defined, push the values into the array
                        heatmap.drawAlpha(one, two, d[one][two], false);   
                    }
                }
            }else{
                while(dlen--){
                    var point = d[dlen];
                    heatmap.drawAlpha(point.x, point.y, point.count, false);
                    if(!data[point.x])
                        data[point.x] = [];

                    if(!data[point.x][point.y])
                        data[point.x][point.y] = 0;

                    data[point.x][point.y] = point.count;
                }
            }
            heatmap.colorize();
            this.set("data", d);
        },
        exportDataSet: function(){
            var me = this,
                data = me.get("data"),
                exportData = [];

            for(var one in data){
                // jump over undefined indexes
                if(one === undefined)
                    continue;
                for(var two in data[one]){
                    if(two === undefined)
                        continue;
                    // if both indexes are defined, push the values into the array
                    exportData.push({x: parseInt(one, 10), y: parseInt(two, 10), count: data[one][two]});
                }
            }

            return { max: me.max, data: exportData };
        },
        generateRandomDataSet: function(points){
            var heatmap = this.get("heatmap"),
            w = heatmap.get("width"),
            h = heatmap.get("height");
            var randomset = {},
            max = Math.floor(Math.random()*1000+1);
            randomset.max = max;
            var data = [];
            while(points--){
                data.push({x: Math.floor(Math.random()*w+1), y: Math.floor(Math.random()*h+1), count: Math.floor(Math.random()*max+1)});
            }
            randomset.data = data;
            this.setDataSet(randomset);
        }
    };

    var legend = function legend(config){
        this.config = config;

        var _ = {
            element: null,
            labelsEl: null,
            gradientCfg: null,
            ctx: null
        };
        this.get = function(key){
            return _[key];
        };
        this.set = function(key, value){
            _[key] = value;
        };
        this.init();
    };
    legend.prototype = {
        init: function(){
            var me = this,
                config = me.config,
                title = config.title || "Legend",
                position = config.position,
                offset = config.offset || 10,
                gconfig = config.gradient,
                labelsEl = document.createElement("ul"),
                labelsHtml = "",
                grad, element, gradient, positionCss = "";
 
            me.processGradientObject();
            
            // Positioning

            // top or bottom
            if(position.indexOf('t') > -1){
                positionCss += 'top:'+offset+'px;';
            }else{
                positionCss += 'bottom:'+offset+'px;';
            }

            // left or right
            if(position.indexOf('l') > -1){
                positionCss += 'left:'+offset+'px;';
            }else{
                positionCss += 'right:'+offset+'px;';
            }

            element = document.createElement("div");
            element.style.cssText = "border-radius:5px;position:absolute;"+positionCss+"font-family:Helvetica; width:256px;z-index:10000000000; background:rgba(255,255,255,1);padding:10px;border:1px solid black;margin:0;";
            element.innerHTML = "<h3 style='padding:0;margin:0;text-align:center;font-size:16px;'>"+title+"</h3>";
            // create gradient in canvas
            labelsEl.style.cssText = "position:relative;font-size:12px;display:block;list-style:none;list-style-type:none;margin:0;height:15px;";
            

            // create gradient element
            gradient = document.createElement("div");
            gradient.style.cssText = ["position:relative;display:block;width:256px;height:15px;border-bottom:1px solid black; background-image:url(",me.createGradientImage(),");"].join("");

            element.appendChild(labelsEl);
            element.appendChild(gradient);
            
            me.set("element", element);
            me.set("labelsEl", labelsEl);

            me.update(1);
        },
        processGradientObject: function(){
            // create array and sort it
            var me = this,
                gradientConfig = this.config.gradient,
                gradientArr = [];

            for(var key in gradientConfig){
                if(gradientConfig.hasOwnProperty(key)){
                    gradientArr.push({ stop: key, value: gradientConfig[key] });
                }
            }
            gradientArr.sort(function(a, b){
                return (a.stop - b.stop);
            });
            gradientArr.unshift({ stop: 0, value: 'rgba(0,0,0,0)' });

            me.set("gradientArr", gradientArr);
        },
        createGradientImage: function(){
            var me = this,
                gradArr = me.get("gradientArr"),
                length = gradArr.length,
                canvas = document.createElement("canvas"),
                ctx = canvas.getContext("2d"),
                grad;
            // the gradient in the legend including the ticks will be 256x15px
            canvas.width = "256";
            canvas.height = "15";

            grad = ctx.createLinearGradient(0,5,256,10);

            for(var i = 0; i < length; i++){
                grad.addColorStop(1/(length-1) * i, gradArr[i].value);
            }

            ctx.fillStyle = grad;
            ctx.fillRect(0,5,256,10);
            ctx.strokeStyle = "black";
            ctx.beginPath();
 
            for(var i = 0; i < length; i++){
                ctx.moveTo(((1/(length-1)*i*256) >> 0)+.5, 0);
                ctx.lineTo(((1/(length-1)*i*256) >> 0)+.5, (i==0)?15:5);
            }
            ctx.moveTo(255.5, 0);
            ctx.lineTo(255.5, 15);
            ctx.moveTo(255.5, 4.5);
            ctx.lineTo(0, 4.5);
            
            ctx.stroke();

            // we re-use the context for measuring the legends label widths
            me.set("ctx", ctx);

            return canvas.toDataURL();
        },
        getElement: function(){
            return this.get("element");
        },
        update: function(max){
            var me = this,
                gradient = me.get("gradientArr"),
                ctx = me.get("ctx"),
                labels = me.get("labelsEl"),
                labelText, labelsHtml = "", offset;

            for(var i = 0; i < gradient.length; i++){

                labelText = max*gradient[i].stop >> 0;
                offset = (ctx.measureText(labelText).width/2) >> 0;

                if(i == 0){
                    offset = 0;
                }
                if(i == gradient.length-1){
                    offset *= 2;
                }
                labelsHtml += '<li style="position:absolute;left:'+(((((1/(gradient.length-1)*i*256) || 0)) >> 0)-offset+.5)+'px">'+labelText+'</li>';
            }       
            labels.innerHTML = labelsHtml;
        }
    };

    // heatmap object constructor
    var heatmap = function heatmap(config){
        // private variables
        var _ = {
            radius : 40,
            element : {},
            canvas : {},
            acanvas: {},
            ctx : {},
            actx : {},
            legend: null,
            visible : true,
            width : 0,
            height : 0,
            max : false,
            gradient : false,
            opacity: 180,
            premultiplyAlpha: false,
            bounds: {
                l: 1000,
                r: 0,
                t: 1000,
                b: 0
            },
            debug: false
        };
        // heatmap store containing the datapoints and information about the maximum
        // accessible via instance.store
        this.store = new store(this);

        this.get = function(key){
            return _[key];
        };
        this.set = function(key, value){
            _[key] = value;
        };
        // configure the heatmap when an instance gets created
        this.configure(config);
        // and initialize it
        this.init();
    };

    // public functions
    heatmap.prototype = {
        configure: function(config){
                var me = this,
                    rout, rin;

                me.set("radius", config["radius"] || 40);
                me.set("element", (config.element instanceof Object)?config.element:document.getElementById(config.element));
                me.set("visible", (config.visible != null)?config.visible:true);
                me.set("max", config.max || false);
                me.set("gradient", config.gradient || { 0.45: "rgb(0,0,255)", 0.55: "rgb(0,255,255)", 0.65: "rgb(0,255,0)", 0.95: "yellow", 1.0: "rgb(255,0,0)"});    // default is the common blue to red gradient
                me.set("opacity", parseInt(255/(100/config.opacity), 10) || 180);
                me.set("width", config.width || 0);
                me.set("height", config.height || 0);
                me.set("debug", config.debug);

                if(config.legend){
                    var legendCfg = config.legend;
                    legendCfg.gradient = me.get("gradient");
                    me.set("legend", new legend(legendCfg));
                }
                
        },
        resize: function () {
                var me = this,
                    element = me.get("element"),
                    canvas = me.get("canvas"),
                    acanvas = me.get("acanvas");
                canvas.width = acanvas.width = me.get("width") || element.style.width.replace(/px/, "") || me.getWidth(element);
                this.set("width", canvas.width);
                canvas.height = acanvas.height = me.get("height") || element.style.height.replace(/px/, "") || me.getHeight(element);
                this.set("height", canvas.height);
        },

        init: function(){
                var me = this,
                    canvas = document.createElement("canvas"),
                    acanvas = document.createElement("canvas"),
                    ctx = canvas.getContext("2d"),
                    actx = acanvas.getContext("2d"),
                    element = me.get("element");

                
                me.initColorPalette();

                me.set("canvas", canvas);
                me.set("ctx", ctx);
                me.set("acanvas", acanvas);
                me.set("actx", actx);

                me.resize();
                canvas.style.cssText = acanvas.style.cssText = "position:absolute;top:0;left:0;z-index:10000000;";
                
                if(!me.get("visible"))
                    canvas.style.display = "none";

                element.appendChild(canvas);
                if(me.get("legend")){
                    element.appendChild(me.get("legend").getElement());
                }
                
                // debugging purposes only
                if(me.get("debug"))
                    document.body.appendChild(acanvas);
                
                actx.shadowOffsetX = 15000; 
                actx.shadowOffsetY = 15000; 
                actx.shadowBlur = 15; 
        },
        initColorPalette: function(){

            var me = this,
                canvas = document.createElement("canvas"),
                gradient = me.get("gradient"),
                ctx, grad, testData;

            canvas.width = "1";
            canvas.height = "256";
            ctx = canvas.getContext("2d");
            grad = ctx.createLinearGradient(0,0,1,256);

            // Test how the browser renders alpha by setting a partially transparent pixel
            // and reading the result.  A good browser will return a value reasonably close
            // to what was set.  Some browsers (e.g. on Android) will return a ridiculously wrong value.
            testData = ctx.getImageData(0,0,1,1);
            testData.data[0] = testData.data[3] = 64; // 25% red & alpha
            testData.data[1] = testData.data[2] = 0; // 0% blue & green
            ctx.putImageData(testData, 0, 0);
            testData = ctx.getImageData(0,0,1,1);
            me.set("premultiplyAlpha", (testData.data[0] < 60 || testData.data[0] > 70));
            
            for(var x in gradient){
                grad.addColorStop(x, gradient[x]);
            }

            ctx.fillStyle = grad;
            ctx.fillRect(0,0,1,256);

            me.set("gradient", ctx.getImageData(0,0,1,256).data);
        },
        getWidth: function(element){
            var width = element.offsetWidth;
            if(element.style.paddingLeft){
                width+=element.style.paddingLeft;
            }
            if(element.style.paddingRight){
                width+=element.style.paddingRight;
            }

            return width;
        },
        getHeight: function(element){
            var height = element.offsetHeight;
            if(element.style.paddingTop){
                height+=element.style.paddingTop;
            }
            if(element.style.paddingBottom){
                height+=element.style.paddingBottom;
            }

            return height;
        },
        colorize: function(x, y){
                // get the private variables
                var me = this,
                    width = me.get("width"),
                    radius = me.get("radius"),
                    height = me.get("height"),
                    actx = me.get("actx"),
                    ctx = me.get("ctx"),
                    x2 = radius * 3,
                    premultiplyAlpha = me.get("premultiplyAlpha"),
                    palette = me.get("gradient"),
                    opacity = me.get("opacity"),
                    bounds = me.get("bounds"),
                    left, top, bottom, right, 
                    image, imageData, length, alpha, offset, finalAlpha;
                
                if(x != null && y != null){
                    if(x+x2>width){
                        x=width-x2;
                    }
                    if(x<0){
                        x=0;
                    }
                    if(y<0){
                        y=0;
                    }
                    if(y+x2>height){
                        y=height-x2;
                    }
                    left = x;
                    top = y;
                    right = x + x2;
                    bottom = y + x2;

                }else{
                    if(bounds['l'] < 0){
                        left = 0;
                    }else{
                        left = bounds['l'];
                    }
                    if(bounds['r'] > width){
                        right = width;
                    }else{
                        right = bounds['r'];
                    }
                    if(bounds['t'] < 0){
                        top = 0;
                    }else{
                        top = bounds['t'];
                    }
                    if(bounds['b'] > height){
                        bottom = height;
                    }else{
                        bottom = bounds['b'];
                    }    
                }

                image = actx.getImageData(left, top, right-left, bottom-top);
                imageData = image.data;
                length = imageData.length;
                // loop thru the area
                for(var i=3; i < length; i+=4){

                    // [0] -> r, [1] -> g, [2] -> b, [3] -> alpha
                    alpha = imageData[i],
                    offset = alpha*4;

                    if(!offset)
                        continue;

                    // we ve started with i=3
                    // set the new r, g and b values
                    finalAlpha = (alpha < opacity)?alpha:opacity;
                    imageData[i-3]=palette[offset];
                    imageData[i-2]=palette[offset+1];
                    imageData[i-1]=palette[offset+2];
                    
                    if (premultiplyAlpha) {
                    	// To fix browsers that premultiply incorrectly, we'll pass in a value scaled
                    	// appropriately so when the multiplication happens the correct value will result.
                    	imageData[i-3] /= 255/finalAlpha;
                    	imageData[i-2] /= 255/finalAlpha;
                    	imageData[i-1] /= 255/finalAlpha;
                    }
                    
                    // we want the heatmap to have a gradient from transparent to the colors
                    // as long as alpha is lower than the defined opacity (maximum), we'll use the alpha value
                    imageData[i] = finalAlpha;
                }
                // the rgb data manipulation didn't affect the ImageData object(defined on the top)
                // after the manipulation process we have to set the manipulated data to the ImageData object
                image.data = imageData;
                ctx.putImageData(image, left, top);
        },
        drawAlpha: function(x, y, count, colorize){
                // storing the variables because they will be often used
                var me = this,
                    radius = me.get("radius"),
                    ctx = me.get("actx"),
                    max = me.get("max"),
                    bounds = me.get("bounds"),
                    xb = x - (1.5 * radius) >> 0, yb = y - (1.5 * radius) >> 0,
                    xc = x + (1.5 * radius) >> 0, yc = y + (1.5 * radius) >> 0;

                ctx.shadowColor = ('rgba(0,0,0,'+((count)?(count/me.store.max):'0.1')+')');

                ctx.shadowOffsetX = 15000; 
                ctx.shadowOffsetY = 15000; 
                ctx.shadowBlur = 15; 

                ctx.beginPath();
                ctx.arc(x - 15000, y - 15000, radius, 0, Math.PI * 2, true);
                ctx.closePath();
                ctx.fill();
                if(colorize){
                    // finally colorize the area
                    me.colorize(xb,yb);
                }else{
                    // or update the boundaries for the area that then should be colorized
                    if(xb < bounds["l"]){
                        bounds["l"] = xb;
                    }
                    if(yb < bounds["t"]){
                        bounds["t"] = yb;
                    }
                    if(xc > bounds['r']){
                        bounds['r'] = xc;
                    }
                    if(yc > bounds['b']){
                        bounds['b'] = yc;
                    }
                }
        },
        toggleDisplay: function(){
                var me = this,
                    visible = me.get("visible"),
                canvas = me.get("canvas");

                if(!visible)
                    canvas.style.display = "block";
                else
                    canvas.style.display = "none";

                me.set("visible", !visible);
        },
        // dataURL export
        getImageData: function(){
                return this.get("canvas").toDataURL();
        },
        clear: function(){
            var me = this,
                w = me.get("width"),
                h = me.get("height");

            me.store.set("data",[]);
            // @TODO: reset stores max to 1
            //me.store.max = 1;
            me.get("ctx").clearRect(0,0,w,h);
            me.get("actx").clearRect(0,0,w,h);
        },
        cleanup: function(){
            var me = this;
            me.get("element").removeChild(me.get("canvas"));
        }
    };

    return {
            create: function(config){
                return new heatmap(config);
            }, 
            util: {
                mousePosition: function(ev){
                    // this doesn't work right
                    // rather use
                    /*
                        // this = element to observe
                        var x = ev.pageX - this.offsetLeft;
                        var y = ev.pageY - this.offsetTop;

                    */
                    var x, y;

                    if (ev.layerX) { // Firefox
                        x = ev.layerX;
                        y = ev.layerY;
                    } else if (ev.offsetX) { // Opera
                        x = ev.offsetX;
                        y = ev.offsetY;
                    }
                    if(typeof(x)=='undefined')
                        return;

                    return [x,y];
                }
            }
        };
    })();
    w.h337 = w.heatmapFactory = heatmapFactory;
})(window);

/**
 * urllist_heatmap_v3.js (new version 3) for changes to remove filter in Heat Map & to provide the loader when page loads.
 */

/** Offset is dependent on count and search change, so always get it after getting the later params */
var param1 = {
    offset:0,
    count:10,
    callBack:'callback.urlList'
}
var paramFil = {
    callBack:'filterResult'
}

var selectedFilter = {};

tb_urlList = {
    reset_pagein : function(){
        this.offset=0;
    },
    setQuery : function(val){
        this.reset_pagein();
        this.query = val;
        return this.query;
    },
    getQuery : function(){
        return this.query || '';
    },
    offset:0, // Row Number
    count:10, //Number of Rows any time
    total_num_page:function(){
        return Math.ceil(this.totalUrls/this.count)||1
    }
}
var oTable = $('#urlList').dataTable({
    //"sDom": '<"H"<"cus_search pull-right">l>t<"F"<"cust_pagination fr">>',
    "sDom": "<'H'<'row-fluid'<'span12'l <'toolbar '>>r>>t<'F'<'row-fluid'<'cust_pagination fr'>>>", // MIS
    "aoColumns": [
    {
        "sTitle": "Name",
        "sClass": "center"
    },

    {
        "sTitle": "UrlId",
        "sClass": "center"
    },
    {
        "sTitle": "tag",
        "sClass": "center"
    }
    ],
    aoColumnDefs: [
    {
        "mRender": function ( data,type,row,_obj,_url ) {

            _obj = {
                'urlName':data,
                'urlId': row[1]
            }
			$.extend(_obj,selectedFilter);
            _url = Utility.updateQueryString(_obj)
            return '<a target="_blank" href="heatMapPlot.php'+_url+'">'+(row[2].length?row[2]:data)+'</a>';


        },
        "aTargets": [ 0 ]
    },
    {
        "bVisible": false,
        "aTargets": [ 1 ]
     },
     {
        "bVisible": false,
        "aTargets": [ 2 ]
     },
    ]
});
$(document).ready(function(){

Utility.blockUI($('#urlList'));

	// link to show all results
	$('#all-result').click(function(){
		location.reload();
	});

    $("#urlList_wrapper div.toolbar").html('<div class="table-tools-actions"><div id="test2"></div></div>	');
    var strSearch='<div class="cus_search pull-right"><input type="text" style="float:right" placeholder="Press enter to search"><h6 style="margin:0">Enter more than 3 Characters</h6></div>';
    $(strSearch).insertAfter($('#test2'));
    $('#test2').on( "click",function() {
        $("#quick-access").css("bottom","0px");
    });
    $('#quick-access .btn-cancel').click( function() {
        $("#quick-access").css("bottom","-300px");
    });
    /*Initializing length dropdown*/
    $('[name=urlList_length]').change(function(){
        tb_urlList.count = parseInt($(this).val())
        tb_urlList.reset_pagein();
        var obj = {
            offset : tb_urlList.offset,
            count:tb_urlList.count,
            query:tb_urlList.getQuery(),
            callBack:'callback.urlList'
            };
        calls.urlList.trigger({
            el:$('#urlListGrid')
            },obj);
    })


    /** Pagination */
    $("div.cust_pagination").html("<span id=\"page_detail\"></span><button id=\"page_prev\" value=\"prev\">Prev</button><button id=\"page_next\" value=\"next\">Next</button>");

    /** Event Binding Start*/
    $('#page_next').click(function(){

        var total_num_pages = tb_urlList.total_num_page();
        /** When page requested is less than total number of pages */
        var next_page_index = (tb_urlList.offset/tb_urlList.count)+1;
        if(next_page_index <total_num_pages){
            tb_urlList.offset+= tb_urlList.count;
            var obj = {
                offset : tb_urlList.offset,
                count:tb_urlList.count,
                query:tb_urlList.getQuery(),
	            callBack:'callback.urlList'
                };
            calls.urlList.trigger({
                el:$('#urlListGrid')
                },obj);
        }
    })
    $('#page_prev').click(function(){

        /** When page requested is greater than or equal to 0 */
        var prev_page_index = tb_urlList.offset/tb_urlList.count-1;
        if(prev_page_index>-1){
            //			var query = tb_urlList.query();
            tb_urlList.offset-= tb_urlList.count;
            var obj = {
                offset : tb_urlList.offset,
                count:tb_urlList.count,
                query:tb_urlList.getQuery(),
	            callBack:'callback.urlList'
                };
            calls.urlList.trigger({
                el:$('#urlListGrid')
                },obj);
        }
    })
    /**Event Binding End */

    /*Custom Search*/
    $("div.cus_search").html("<input type=\"text\" style=\"float:right\" placeholder=\"Press enter to search\" /><h6 style=\"margin:0\" >Enter more than 3 Characters</h6>");
    $("div.cus_search input").keypress(function(ev){
        if(ev.which==13){
            var query = tb_urlList.setQuery($(this).val());
            var obj = {
                offset : tb_urlList.offset,
                count:tb_urlList.count,
                query:query,
                callBack:'callback.urlList'
            };
            calls.urlList.trigger({
                el:$('#urlListGrid')
                },obj);
        }
    })
    var param2 = {
        offset:0,
        count:10,
        callBack:'callback.urlList'
    }
    $('#filterSearch').click(function(e){
        var obj = selectedFilter = getFilter();

        /**Adding filters data */
        for(key in obj){
            param2[key] = obj[key];
            paramFil[key] = obj[key];
        }
        calls.urlList.trigger({
            el:$('#loadStatesData')
            },param2);
        calls.filterData.trigger({
            el:null
        },paramFil);
        });
    $('.selectpicker').selectpicker();

})



function abc(nodeList){
    var arr = [];
    for(var i=0;i<nodeList.length;i++){
       arr.push({id : nodeList[i].value,value:nodeList[i].innerHTML});
    }
    return arr;
}

function getFilter(){
    var obj = {};
    var s4,s5,s6;
    if($('#select-os option:selected').length!= 0){
        var a4 = $('#select-os option:selected');
        s4=a4[0].value;
        if(a4.length>1){
            for(var i=1;i< a4.length;i++)
            {

                s4+="&osId[]="+a4[i].value;
            }
        }
        obj['osId[]']= s4;

    }
    if($('#select-browser option:selected').length!= 0){

        var a5 = $('#select-browser option:selected');
        s5=a5[0].value;
        if(a5.length>1){
            for(var i=1;i< a5.length;i++)
            {
                s5+="&browserId[]="+a5[i].value;
            }
        }
        obj['browserId[]']= s5;

    }
    if($('#select-res option:selected').length!= 0){
        var a6 = $('#select-res option:selected');
        s6=a6[0].value;
        if(a6.length>1){
            for(var i=1;i< a6.length;i++)
            {
                s6+="&resolutionId[]="+a6[i].value;
            }
        }
        obj['resolutionId[]']= s6;
    }

    return obj;
}
callback = {
    urlList : function(data){
        // data= {"urls":[{"urlId":"1","url":"http:\/\/my.naukri.com\/manager\/createacc2.php"},{"urlId":"3","url":"hello_world"}],"totalUrls":"3"}
        /** Clear Canvas
      *  Plot Data
      *  */

        tb_urlList.totalUrls = data['totalUrls'];
        var oTable = $('#urlList').dataTable();
        oTable.fnClearTable()
        var _data =  data['urls'];
        //var loadTime  = null
        for(key in _data){
            if(_data.hasOwnProperty(key))
            {
                var obj = _data[key];
                oTable.fnAddData([obj['url'],obj['urlId'],obj['tag']||'']);


            }
        }
        $('#page_detail').html('Showing'+((tb_urlList.offset/tb_urlList.count)+1)+' of '+tb_urlList.total_num_page()+' pages ');
        Utility.unblockUI($('#urlList'));
    }
}

//var param = {appId:110,startdate:'2014-03-11',enddate:'2014-03-12',offset:0,count:10,callback:'callback.urlList',"os_id[]":'4&os_id[]=5'}
//&os_id[]=4&os_id[]=5
calls.init({name:'urlList',url:serviceBaseUrl+'/heatmapGetUrls.php'}).trigger({el:$('#loadStatesData')},param1);
calls.init({name:'filterData',url:serviceBaseUrl+'/heatmapGetFilters.php'}).trigger({el:null},paramFil);
var totalResults,totalResults2;
function resourceTimeTable(data){
    totalResults=data.meta;
    tb_nLogger.fnClearTable();
    tb_urlList.hiddenColumnArr=[];
    x=data.data;
    for(key in x){
        if(x.hasOwnProperty(key)){
            var obj = x[key]
            var newObj={
                'os':obj['os'],
                'Browser':obj['Browser'],
                'screen_resolution':obj['screen_resolution']
                };
            tb_urlList.hiddenColumnArr.push(newObj);
            var add = '<i class="icon-plus-sign" data-num='+(tb_urlList.hiddenColumnArr.length-1)+'></i>';
            var strOrigin='<a href="'+ obj['Origin']+'" target="_blank">'+ unescape(obj['Origin'])+'</>';
            //tb_nLogger.fnAddData([obj['Browser'],obj['Version'],obj['Date'],obj['File'],'<i class="icon-plus-sign" data-num=\''+JSON.stringify(newObj).replace(/\'/g, "\"")+'\'></i>']);
            tb_nLogger.fnAddData([obj['ErrorName'],strOrigin,obj['Count'],obj['Date'],add]);
        }
    }
    $('#urlList_wrapper #page_detail').html('Showing '+((tb_urlList.offset+1))+' of '+tb_urlList.total_num_page()+' pages ');
}


//for filters
function filterResult(res){

    var map = {Browser:'select-browser',Resolution:'select-res',Os:'select-os'}
    var strres1='';
    var strbr1="";
    var stros1='';
    var strres2='';
    var strbr2='';
    var stros2='';

    for(var i in res){

        delete map[i]
        if(i=='Browser'){
            var fil=res[i];
            for(var x in fil){
                strbr1+='<option value="'+(fil[x]).id +'">'+ (fil[x]).browser+'</option>';
                strbr2+='<li rel="'+x+'"><a tabindex="0" class="" style=""><span class="text">'+ (fil[x]).browser +'</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>'
            }
            $('#select-browser').next('.bootstrap-select').find('.dropdown-menu.inner').html(strbr2);
            $('#select-browser').html(strbr1);
        }
        if(i=='Resolution'){
            var fil=res[i];
            for(var x in fil){
                strres1+='<option value="'+(fil[x]).id+'">'+ (fil[x]).resolution+'</option>';
                strres2+='<li rel="'+x+'"><a tabindex="0" class="" style=""><span class="text">'+ (fil[x]).resolution +'</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>'
            }
            $('#select-res').next('.bootstrap-select').find('.dropdown-menu.inner').html(strres2);
            $('#select-res').html(strres1);
        }
        if(i=='Os'){
            var fil=res[i];
            for(var x in fil){
                stros1+='<option value="'+(fil[x]).id +'">'+ (fil[x]).osname+'</option>';
                stros2+='<li rel="'+x+'"><a tabindex="0" class="" style=""><span class="text">'+ (fil[x]).osname +'</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>'
            }
            $('#select-os').next('.bootstrap-select').find('.dropdown-menu.inner').html(stros2);
            $('#select-os').html(stros1);
        }

    }

    for(key in map){

        var fil = abc($('#'+map[key]+' option:selected'));
		stros1 = stros2 = ''
        for(var x=0;x< fil.length;x++){
            stros1+='<option value="'+(fil[x]).id +'">'+ (fil[x]).value+'</option>';
            stros2+='<li rel="'+x+'"><a tabindex="0" class="" style=""><span class="text">'+ (fil[x]).value +'</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>'
        }

        $('#'+map[key]).next('.bootstrap-select').find('.dropdown-menu.inner').html(stros2);
        $('#'+map[key]).html(stros1);
        stros1 = stros2 = ''
    }
}

// JavaScript Document
$(document).ready(function(){

	/* Initializing Page Information */
	$('#urlName').html(Utility.getUrlParam('urlName'))
	
	var startDate = Utility.getUrlParam('startDate');
	var endDate = Utility.getUrlParam('endDate');
	startDate ?$('#startDate').val(startDate):''
	endDate?$('#endDate').val(endDate):''
	
	/* Expanding Breadcrumb */
	
	$('#changeDate').click(function(){	
	Utility.blockUI($(this))  	
		document.location.href = Utility.updateQueryString({startDate:$('#startDate').val(),endDate:$('#endDate').val()})//document.location.href.replace(/startDate.*endDate[^&]*/,'startDate='+$('#startDate').val()+'&endDate='+$('#endDate').val())		
	}) 
	$('li.open:visible .title,.sub-menu li.open:visible').click();     
	
	/** Bind Events on Reload Button */
	$('.grid .tools a.reload').live('click', function (e) {
            var el =  jQuery(this).parents(".grid");		
			var rel = $(e.target).attr('rel');
			if(rel=='urlList')	
				calls[rel].trigger({el:el},{offset : tb_urlList.offset,count:tb_urlList.count,query:tb_urlList.getQuery()});
			if(rel=='heatMapData')	
				calls[rel].trigger({el:$('#loadStatesData')},{callBack:'callback.plotHeatMap'});		
			else
				calls[rel].trigger({el:el});			
    });
	          

})