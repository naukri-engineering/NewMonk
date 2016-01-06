/* Table initialisation */
$(document).ready(function() {
    var responsiveHelper = undefined;
    var breakpointDefinition = {
        tablet: 1024,
        phone : 480
    };    
	
	
	$('#quick-access .btn-cancel').click( function() {
		$("#quick-access").css("bottom","-300px");
		$("#test2").css("bottom","0px").removeClass("up").addClass("dwn");
    });
	
	
   $("#nLoggerTable_wrapper div.toolbar").html('<div class="table-tools-actions"><button class="btn btn-primary filter-down" id="test2">Filter&nbsp;<i class="icon icon-arrow-up"></i></button></div>	');
   
    $("#pageListTable_wrapper div.toolbar").html('<div class="table-tools-actions"></div>	'); 
   
	$('#test2').on( "click",function() {
		if($(this).hasClass('filter-down')){
		$(this).removeClass("filter-down").addClass("filter-up").css("bottom",$(".admin-bar-inner").height()+30);
		$(this).find('i').removeClass("icon-arrow-up").addClass("icon-arrow-down");
		$("#quick-access").css("bottom","0px");
		}
		else{
		$("#quick-access").css("bottom","-300px");
		$("#test2").css("bottom","0px").removeClass("filter-up").addClass("filter-down");
		$("#test2").find("i").removeClass("icon-arrow-down").addClass("icon-arrow-up");

			}

    });
	
	
	
	$('#nLoggerTable_wrapper .dataTables_filter input').addClass("input-medium ");
    $('#nLoggerTable_wrapper .dataTables_length select').addClass("select2-wrapper span12"); 
	
	
	$(".select2-wrapper").select2({minimumResultsForSearch: -1});

});

	var tb_nLogger = $('#nLoggerTable').dataTable({        
		"sDom": "<'H'<'row-fluid'<'span12'l <'toolbar '>>r>>t<'F'<'row-fluid'<'cust_pagination fr'>>>", // MIS
       	"oLanguage": {
		"sLengthMenu": "_MENU_ ",
		"sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
		},
		"aoColumns": [
			{ "sTitle": "Error Name","sWidth" : "15%"},
			{ "sTitle": "Origin","sWidth" : "55%"},			
			{ "sTitle": "Count","sWidth" : "10%"},
			{ "sTitle": "Date","sWidth" : "15%"},
			{"sTitle": " ","sWidth" : "5%"}
		],
		"aaSorting": [[ 1, "desc" ]]
    });
	
	var tb_pageListTable=$('#pageListTable').dataTable({
		"sDom": "<'H'<'row-fluid'<'span12'l <'toolbar '>>r>>t<'F'<'row-fluid'<'cust_pagination fr'>>>", // MIS
       	"oLanguage": {
		"sLengthMenu": "_MENU_ ",
		"sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
		},
		"aoColumns": [
			{ "sTitle": "File URL","sWidth" : "100%"},
			
		],
		"aaSorting": [[ 1, "desc" ]]
	});
	
	//Function Tables 
Tables = (function(){
		
	function fnFormatDetails ( tb_nLogger, nTr,index)
	{
		var oData = tb_urlList.hiddenColumnArr[index];
		var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" class="inner-table nLogger">';
		if(oData.File=='na'){
    	sOut += '<tr><td>File</td><td>:&nbsp'+unescape(oData.Origin)+'<a class="all-file" data-file="'+oData.url_id +'" target="_blank" href="#">&nbsp&nbsp&nbsp&nbsp&nbspView All</a></td></tr>';
	}
	else{
		sOut += '<tr><td>File</td><td>:&nbsp'+unescape(oData.File)+'<a class="all-file" data-file="'+oData.url_id +'" target="_blank" href="#">&nbsp&nbsp&nbsp&nbsp&nbspView All</a></td></tr>';
	
		}
		if(oData.Line!=-1){
    	sOut += '<tr><td>Line Number </td><td>:&nbsp'+oData.Line+'</td></tr>';
		}	
		if(oData.Browser!='na'){
	    sOut += '<tr><td>Browser </td><td>:&nbsp'+oData.Browser+'</td></tr>';
		}
		if(oData.os!='na'){
		sOut += '<tr><td>OS </td><td>:&nbsp'+oData.os+'</td></tr>';
		}
		if(oData.Catch!='na'){
		sOut += '<tr><td>Catch</td><td>:&nbsp'+oData.Catch+'</td></tr>';
		}
		if(oData.screen_resolution!='na'){
		sOut += '<tr><td>Screen Resolution</td><td>:&nbsp'+oData.screen_resolution+'</td></tr>';
		}
		if(oData.Version!=-1){
		sOut += '<tr><td>Browser Version</td><td>:&nbsp'+oData.Version+'</td></tr>';
		}
    	sOut += '</table>';
    return sOut;	
	}
	
    $('#nLoggerTable tbody td i').live('click', function () {
        var nTr = $(this).parents('tr')[0];
		var index=$(this).data('num');
	
        if ( tb_nLogger.fnIsOpen(nTr) )
        {
            /* This row is already open - close it */
			$(this).removeClass("icon-minus-sign").addClass("icon-plus-sign");
            tb_nLogger.fnClose( nTr );
        }
        else
        {
			  /* Open this row */
            $(this).removeClass("icon-plus-sign").addClass("icon-minus-sign");
            tb_nLogger.fnOpen( nTr, fnFormatDetails( tb_nLogger, nTr,index), 'details' );
        }
    });
	
var totalResults,totalResults2;	
	
function resourceTimeTable(data){
	totalResults=data.meta;
	tb_nLogger.fnClearTable();
	tb_urlList.hiddenColumnArr = [];
	x=data.data;
		for(key in x){
		if(x.hasOwnProperty(key)){
			var obj = x[key]
			var newObj={'os':obj['os'],'Origin':obj['Origin'],'Line':obj['Line'],'Browser':obj['Browser'],'File':obj['File'],'Catch':obj['Catch'],'screen_resolution':obj['screen_resolution'],'Version':obj['Version'],'url_id':obj['url_id']};
			tb_urlList.hiddenColumnArr.push(newObj);
			var add = '<i class="icon-plus-sign" data-num='+(tb_urlList.hiddenColumnArr.length-1)+'></i>';
			var strOrigin='<a href="'+ obj['Origin']+'" target="_blank">'+ unescape(obj['Origin'])+'</>';
				//tb_nLogger.fnAddData([obj['Browser'],obj['Version'],obj['Date'],obj['File'],'<i class="icon-plus-sign" data-num=\''+JSON.stringify(newObj).replace(/\'/g, "\"")+'\'></i>']);
			tb_nLogger.fnAddData([obj['ErrorName'],strOrigin,obj['Count'],obj['Date'],add]);
			}
		}

	$('#nLoggerTable_wrapper #page_detail').html('Showing '+((tb_urlList.offset+1))+' of '+tb_urlList.total_num_page()+' pages ');
	}
	
	
//for filters
function filterResult(data){
var res=data.dd;
var strfile1='';var strerror1=""; var strurl1='';var strfile2='';var strerror2='';var strurl2='';
var strres1='';var strbr1=""; var stros1='';var strres2='';var strbr2='';var stros2='';

for(var i in res){
	
	if(i=='file'){
		var fil=res[i];
		for(var x in fil){

			var url=unescape(fil[x]);
			strfile1+='<option>'+ url+'</option>';
			strfile2+='<li rel="'+x+'"><a tabindex="0" class="" style=""><span class="text">'+ url+'</span><i class="glyphicon glyphicon-ok icon-ok 			check-mark"></i></a></li>'
				}	
		$('#select-file').next('.bootstrap-select').find('.dropdown-menu.inner').html(strfile2);
		$('#select-file').html(strfile1);
					
			}
	
	if(i=='error_msg'){
		var fil=res[i];
		for(var x in fil){
			if(fil[x]!=null){
			strerror1+='<option>'+ fil[x]+'</option>';
			strerror2+='<li rel="'+x+'"><a tabindex="0" class="" style=""><span class="text">'+ fil[x]+'</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>'
					}
				}
	$('#select-error').next('.bootstrap-select').find('.dropdown-menu.inner').html(strerror2);
	$('#select-error').html(strerror1);
			}
	if(i=='url_tag'){
		var fil=res[i];
		for(var x in fil){		
			if(fil[x]!=null){
			strurl1+='<option>'+ fil[x]+'</option>';
			strurl2+='<li rel="'+x+'"><a tabindex="0" class="" style=""><span class="text">'+ fil[x]+'</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>'
					}
				}
	$('#select-url').next('.bootstrap-select').find('.dropdown-menu.inner').html(strurl2);
	$('#select-url').html(strurl1);			
			}
	if(i=='browser_id'){
		var fil=res[i];
		for(var x in fil){			
		strbr1+='<option value="'+(fil[x]).browser_id +'">'+ (fil[x]).browser_name+'</option>';
		strbr2+='<li rel="'+x+'"><a tabindex="0" class="" style=""><span class="text">'+ (fil[x]).browser_name +'</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>'
				}
	$('#select-browser').next('.bootstrap-select').find('.dropdown-menu.inner').html(strbr2);
	$('#select-browser').html(strbr1);
			}
	if(i=='screen_reso_id'){
		var fil=res[i];
		for(var x in fil){				
		strres1+='<option value="'+(fil[x]).screen_reso_id+'">'+ (fil[x]).screen_resolution+'</option>';
		strres2+='<li rel="'+x+'"><a tabindex="0" class="" style=""><span class="text">'+ (fil[x]).screen_resolution +'</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>'
				}
	$('#select-res').next('.bootstrap-select').find('.dropdown-menu.inner').html(strres2);			
	$('#select-res').html(strres1);			
			}
	if(i=='os_id'){
		var fil=res[i];
		for(var x in fil){				
		stros1+='<option value="'+(fil[x]).os_id +'">'+ (fil[x]).os_name+'</option>';
		stros2+='<li rel="'+x+'"><a tabindex="0" class="" style=""><span class="text">'+ (fil[x]).os_name +'</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>'
				}
	$('#select-os').next('.bootstrap-select').find('.dropdown-menu.inner').html(stros2);
	$('#select-os').html(stros1);		
			}
		}
}
	
	
	
//for page list table
function pageListData(data){
	totalResults2=data.meta;
	tb_pageListTable.fnClearTable();
	x2=data.data;
		for(key in x2){
		if(x2.hasOwnProperty(key)){
			var strFile='<a href="'+ x2[key]+'" target="_blank">'+ unescape(x2[key])+'</>';
				tb_pageListTable.fnAddData([strFile]);
			}	
		}
		$('#pageListTable_wrapper #page_detail').html('Showing '+((tb_pageList.offset+1))+' of '+tb_pageList.total_num_page()+' pages ');
	}	

tb_urlList = {
	reset_pagein : function(){
		this.offset=0;
		},
	setQuery : function(val){
		this.reset_pagein();
		this.query = val;
		return this.query;
		},
	totalUrls:function(){
		return totalResults;
		},
	getQuery : function(){
		return this.query || '';
		}, 
	offset:0, // Row Number
	count:10, //Number of Rows any time
	total_num_page:function(){
		return Math.ceil(this.totalUrls()/this.count)||1
		},
	hiddenColumnArr : []
		}
		

tb_pageList = {
	reset_pagein : function(){
		this.offset=0;
		},
	setQuery : function(val){
		this.reset_pagein();
		this.query = val;
		return this.query;
		},
	totalUrls:function(){
		return totalResults2;
		},
	
	offset:0, // Row Number
	count:10, //Number of Rows any time
	total_num_page:function(){
		return Math.ceil(this.totalUrls()/this.count)||1
		},
	}		


	return {
		resourceTimeTable:resourceTimeTable,
		tb_urlList:tb_urlList,
		tb_pageList:tb_pageList,
		pageListData:pageListData,
		filterResult:filterResult
		}

}())// JavaScript Document
//Cool ios7 switch - Beta version
//Done using pure Javascript
$(document).ready(function(){
//for sidebar
	var x = [
    	{
        	class: "icon-rocket",
        	title: "Performance",
			url:"boomDashboardUrls.php",
			submenu:[{class: "icon-bar-chart",title: "URL Summary",url:"boomDashboardSummary.php"}					
					]
    	},
    	{
        	class: "icon-bug",
        	title: "Errors",
			url: errorBaseUrl + "/errors/report/#/dashboard/NewMonk-Error:-Browser?_g=(time:(from:now-7d,mode:quick,to:now))&_a=(query:(query_string:(analyze_wildcard:!t,lowercase_expanded_terms:!f,query:'appId:"+ Utility.getUrlParam('appId') +"')))",
    		target : "_blank"
    	},
        {
        	class: "li_fire",
        	title: "HeatMap",
			url:"heatMapUrlList.php"
    	}
	];
	sidebar.init(x);

  //Date Pickers

 	var sDate = Utility.getUrlParam('startDate')? new Date(Utility.getUrlParam('startDate')):new Date();
 	var eDate = Utility.getUrlParam('endDate')? new Date(Utility.getUrlParam('endDate')):new Date();

	$('#startDate').datepicker({
		format:'yyyy-mm-dd',
	 	autoclose: true,
	 	todayHighlight: true
   	}).datepicker('setDate',sDate);

	$('#endDate').datepicker({
		format:'yyyy-mm-dd',
	 	autoclose: true,
	 	todayHighlight: true
   	}).datepicker('setDate',sDate);


	if(location.pathname=='/newmonk/boomDashboardSummary.php' || location.pathname=='/newmonk/boomDashboardMap.php'){
		$('#main-menu ul > li').eq(0).find('.sub-menu').show();
		$('#main-menu ul > li').eq(0).find('a > .arrow').addClass('open')

	}

	//for boomerang submenu
	$('#main-menu ul > li').eq(0).find('.sub-menu a').click(function(e){
  		this.href+=document.location.search;
 	});
});



var sidebar=(function(){

	var init=function(obj){
	var str='<ul>';
	for (var i in obj){


		str+='<li>';
			//+'<a href="'+(window.serviceBaseUrl||window.serviceBaseUrl)+'/'+obj[i].url+'?sideBarIndex='+i +'">';
			if(obj[i].title == "Errors"){
				str+='<a href="'+obj[i].url+'"'+ (obj[i].target ? 'target='+ obj[i].target : '')+'>';

			}
			else{
				str+='<a href="'+obj[i].url+'?sideBarIndex='+i+'"'+ (obj[i].target ? 'target='+ obj[i].target : '')+'>';
			}

		if(obj[i].submenu){
			str+='<i class="'+ obj[i].class+'"></i><span class="title">'+ obj[i].title+'</span><span class="arrow"></span></a>'
				+'<ul class="sub-menu">'
			for(var x in obj[i].submenu){
          		str+='<li> <a href="'+obj[i].submenu[x].url+'"><i class="'+obj[i].submenu[x].class+'"></i>'+obj[i].submenu[x].title+'</a> </li>';
       			}
				str+='</ul>';
			}
		else{
			str+='<i class="'+ obj[i].class+'"></i><span class="title">'+ obj[i].title+'</span></a>'
			}

		  	str+='</li>';
		}
		str+='</ul>';
	$("#main-menu").html(str);
		}
		return {init:init};

	}());





$(document).ready(function() {		
	calculateHeight();
	$(".remove-widget").click(function() {
		
		$(this).parent().parent().parent().addClass('animated fadeOut');
		$(this).parent().parent().parent().attr('id', 'id_a');

		setTimeout( function(){			
			$('#id_a').remove();	
		 },400);	
	return false;
	});
	
	$(".create-folder").click(function() {
		$('.folder-input').show();
		return false;
	});
	
	$(".folder-name").keypress(function (e) {
        if(e.which == 13) {
			 $('.folder-input').hide();
			 $( '<li><a href="#"><div class="status-icon green"></div>'+  $(this).val() +'</a> </li>' ).insertBefore( ".folder-input" );
			 $(this).val('');
		}
    });
	
	$("#menu-collapse").click(function() {	
		if($('.page-sidebar').hasClass('mini')){
			$('.page-sidebar').removeClass('mini');
			$('.page-content').removeClass('condensed-layout');
			$('.footer-widget').show();
		}
		else{
			$('.page-sidebar').addClass('mini');
			$('.page-content').addClass('condensed-layout');
			$('.footer-widget').hide();
			calculateHeight();
		}
	});

	$(".inside").children('input').blur(function(){
		$(this).parent().children('.add-on').removeClass('input-focus');		
	})
	
	$(".inside").children('input').focus(function(){
		$(this).parent().children('.add-on').addClass('input-focus');		
	})	

	$(".bootstrap-tagsinput input").blur(function(){
		$(this).parent().removeClass('input-focus');
	})
	
	$(".bootstrap-tagsinput input").focus(function(){
		$(this).parent().addClass('input-focus');		
	})	
//***********************************CHAT POPUP*****************************
	 $('.chat-menu-toggle').sidr({
		name:'sidr',
		side: 'right',
		complete:function(){		 
		}
	});
	$(".simple-chat-popup").click(function(){
		$(this).addClass('hide');
		$('#chat-message-count').addClass('hide');	
	});

	setTimeout( function(){
		$('#chat-message-count').removeClass('hide');	
		$('#chat-message-count').addClass('animated bounceIn');
		$('.simple-chat-popup').removeClass('hide');			
		$('.simple-chat-popup').addClass('animated fadeIn');		
	},5000);	
	setTimeout( function(){
		$('.simple-chat-popup').addClass('hide');			
		$('.simple-chat-popup').removeClass('animated fadeIn');		
		$('.simple-chat-popup').addClass('animated fadeOut');		
	},8000);
	
	
//**********************************MAIN MENU********************************
	jQuery('.page-sidebar li > a > .arrow').live('click', function (e) {
            e.stopImmediatePropagation();
			e.preventDefault();
		    var parent = $(this).parent().parent().parent();
            parent.children('li.open').children('a').children('.arrow').removeClass('open');
            parent.children('li.open').children('.sub-menu').slideUp(200);
            parent.children('li.open').removeClass('open');

            var sub = jQuery(this).parent().next();
            if (sub.is(":visible")) {
                jQuery('.arrow', jQuery(this)).removeClass("open");
                jQuery(this).parent().removeClass("open");
                sub.slideUp(200, function () {
                    handleSidenarAndContentHeight();
                });
            } else {
                jQuery('.arrow', jQuery(this).parent()).addClass("open");
				var parent = jQuery(this).parent().parent();
                parent.addClass("open");

                sub.slideDown(200, function () {
                    handleSidenarAndContentHeight();

                });
				
            }
			
            e.preventDefault();
        });
		/** Side Bar update */
		
		
		 $('.grid .tools a.remove').live('click', function () {
            var removable = jQuery(this).parents(".grid");
            if (removable.next().hasClass('grid') || removable.prev().hasClass('grid')) {
                jQuery(this).parents(".grid").remove();
            } else {
                jQuery(this).parents(".grid").parent().remove();
            }
        });
		
		$('.grid .tools .collapse, .grid .tools .expand').live('click', function () {
            var el = jQuery(this).parents(".grid").children(".grid-body");
            if (jQuery(this).hasClass("collapse")) {
                jQuery(this).removeClass("collapse").addClass("expand");
                el.slideUp(200);
            } else {
                jQuery(this).removeClass("expand").addClass("collapse");
                el.slideDown(200);
            }
        });		
		
		$('.user-info .collapse').live('click', function () {
            jQuery(this).parents(".user-info ").slideToggle();
		});   
		
		var handleSidenarAndContentHeight = function () {
        var content = $('.page-content');
        var sidebar = $('.page-sidebar');

        if (!content.attr("data-height")) {
            content.attr("data-height", content.height());
        }

        if (sidebar.height() > content.height()) {
            content.css("min-height", sidebar.height() + 20);
        } else {
            content.css("min-height", content.attr("data-height"));
        }
    }
	
	$('.accordion').on('show', function (e) {
         $(e.target).prev('.accordion-heading').find('.accordion-toggle').removeClass('collapsed');
    });

    $('.accordion').on('hide', function (e) {
        $(this).find('.accordion-toggle').not($(e.target)).addClass('collapsed');
    });
	
	//**** BEGIN Layout Readjust  ****//
	//Break points for each exits and entering
	$(window).setBreakpoints({
		distinct: true, 
		breakpoints: [
			320,
			480,
			768,
			1024
		] 
	});   
	
	//Break point entry 
	$(window).bind('enterBreakpoint320',function() {	
		$('#main-menu-toggle-wrapper').show();		
		$('#portrait-chat-toggler').show();	
		$('#header_inbox_bar').hide();	
		$('#main-menu').removeClass('mini');		   
		$('.page-content').removeClass('condensed');
		rebuildSider();
	});	
	
	$(window).bind('enterBreakpoint480',function() {
		$('#main-menu-toggle-wrapper').show();		
		$('.header-seperation').show();		
		$('#portrait-chat-toggler').show();				
		$('#header_inbox_bar').hide();	
		//Incase if condensed layout is applied
		$('#main-menu').removeClass('mini');		   
		$('.page-content').removeClass('condensed');			
		rebuildSider();
	});
	
	$(window).bind('enterBreakpoint768',function() {		
		$('#main-menu-toggle-wrapper').show();		
		$('#portrait-chat-toggler').show();	
		
		$('#header_inbox_bar').hide();	
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {			
			$('#main-menu').removeClass('mini');	
			$('.page-content').removeClass('condensed');	
			rebuildSider();
		}	
	});

	$(window).bind('exitBreakpoint320',function() {	
		$('#main-menu-toggle-wrapper').hide();		
		$('#portrait-chat-toggler').hide();	
		$('#header_inbox_bar').show();			
		closeAndRestSider();		
	});	
	
	$(window).bind('exitBreakpoint480',function() {
		$('#main-menu-toggle-wrapper').hide();		
		$('#portrait-chat-toggler').hide();	
		$('#header_inbox_bar').show();			
		closeAndRestSider();	
	});
	
	$(window).bind('exitBreakpoint768',function() {
		$('#main-menu-toggle-wrapper').hide();		
		$('#portrait-chat-toggler').hide();	
		$('#header_inbox_bar').show();			
		closeAndRestSider();
	});

	//Common Function calls
	function closeAndRestSider(){
	  if($('#main-menu').attr('data-inner-menu')=='1'){
		$('#main-menu').addClass("mini");	
		$.sidr('close', 'main-menu');
		$.sidr('close', 'sidr');		
		$('#main-menu').removeClass("sidr");	
		$('#main-menu').removeClass("left");	
	  }
	  else{
		$.sidr('close', 'main-menu');
		$.sidr('close', 'sidr');		
		$('#main-menu').removeClass("sidr");	
		$('#main-menu').removeClass("left");	
	}
	
	}
	function rebuildSider(){
		$('#main-menu-toggle').sidr({		
				name : 'main-menu',
				side: 'left'
		});
	}
	
	$('#layout-condensed-toggle').click(function(){
	 if($('#main-menu').attr('data-inner-menu')=='1'){
		//Do nothing
		$('#logo-center').css('display','inline-block');

	 }
	 else{
	  if($('#main-menu').hasClass('mini')){
		$('#main-menu').removeClass('mini');
		$('.page-content').removeClass('condensed');		
		$('.scrollup').removeClass('to-edge');	
		$('.header-seperation').show();
		$('.footer-widget').show();
		$('#logo-center').hide();
	  }	
	  else{
		$('#main-menu').addClass('mini');
		$('.page-content').addClass('condensed');		
		$('.scrollup').addClass('to-edge');	
		$('.header-seperation').hide();
		$('.footer-widget').hide();	  
		$('#logo-center').css('display','inline-block');
	  }
	 }
	});
	//**** END Layout Readjust  ****//
	
	$('.scroller').each(function () {
        $(this).slimScroll({
                size: '7px',
                color: '#a1b2bd',
                height: $(this).attr("data-height"),
                alwaysVisible: ($(this).attr("data-always-visible") == "1" ? true : false),
                railVisible: ($(this).attr("data-rail-visible") == "1" ? true : false),
                disableFadeOut: true
        });
    });
	$('.dropdown-toggle').click(function () {
		$("img").trigger("unveil");
	});
   
	if ($.fn.sparkline) {
		$('.sparklines').sparkline('html', { enableTagOptions: true });
	}

	 $('table th .checkall').on('click', function () {
			if($(this).is(':checked')){
				$(this).closest('table').find(':checkbox').attr('checked', true);
				$(this).closest('table').find('tr').addClass('row_selected');
				//$(this).parent().parent().parent().toggleClass('row_selected');	
			}
			else{
				$(this).closest('table').find(':checkbox').attr('checked', false);
				$(this).closest('table').find('tr').removeClass('row_selected');
			}
    });

	$('.animate-number').each(function(){
		 $(this).animateNumbers($(this).attr("data-value"), true, parseInt($(this).attr("data-animation-duration")));	
	})
	$('.animate-progress-bar').each(function(){
		 $(this).css('width', $(this).attr("data-percentage"));
		
	})
	
	
	
	$('.controller .remove').click(function () {
		$(this).parent().parent().parent().parent().addClass('animated fadeOut');
		$(this).parent().parent().parent().parent().attr('id', 'id_remove_temp_id');
		 setTimeout( function(){			
			$('#id_remove_temp_id').remove();	
		 },400);
	});
        if (!jQuery().sortable) {
            return;
        }
        $(".sortable").sortable({
            connectWith: '.sortable',
            iframeFix: false,
            items: 'div.grid',
            opacity: 0.8,
            helper: 'original',
            revert: true,
            forceHelperSize: true,
            placeholder: 'sortable-box-placeholder round-all',
            forcePlaceholderSize: true,
            tolerance: 'pointer'
        });

	// wrapper function to  block element(indicate loading)
    function blockUI(el) {		
            $(el).block({
                message: '<div class="loading-animator"></div>',
                css: {
                    border: 'none',
                    padding: '2px',
                    backgroundColor: 'none'
                },
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.3,
                    cursor: 'wait'
                }
            });
     }
	 
     // wrapper function to  un-block element(finish loading)
     function unblockUI(el) {
            $(el).unblock();
    }
	
	$(window).resize(function() {
			calculateHeight();
	});
	
	$(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });
	
	$('.scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 700);
		return false;
    });
	
	 $("img").unveil();
});

function calculateHeight(){
		var contentHeight=parseInt($('.page-content').height());
		if(911 > contentHeight){	
		}	
}	
	
// JavaScript Document
var proto_calls = {
			url : '',
			responseVarName:'',
			callback:function(data){},
			/** obj : Element to show load on
				updateQs : New query string params to be merged
			 */
			trigger : function(obj,updateQs){
					var url = this.url;
					!this.responseVarName?$.ajaxSetup({cache:true}):'';
					updateQs = updateQs || {}
					if(this.responseVarName)
						updateQs.responseVarName = this.responseVarName;
						
					/** 2nd param is the query string which will be updated, default is document.location.search */

					var qs = Utility.updateQueryString(updateQs,(url.match(/\?.*/)||[])[0]);
					url=url.replace(/\?.*/,'');
					url+= qs;
					var _this = this;
					if(!this.responseVarName){
						$.getScript(url,function(){Utility.unblockUI(obj.el||'');})
					}else{
						$.getScript(url,function(){Utility.unblockUI(obj.el||'');_this.callback(window[_this.responseVarName])})
					}

					Utility.blockUI(obj.el||'');
				}	
			}
			

var calls = (function(){
	
	var callsObj = function(obj){
		for(key in obj){			
			this[key] = obj[key];
		}
	}
	callsObj.prototype =  proto_calls;
		
	var init = 	function(obj){		
		return (calls[obj.name] = new callsObj(obj));		
	}	
	return {init:init};
	
}())

// JavaScript Document
/**
	This file holds various utility functions like :-
	
	getUrlParam : Takes name of the param as value and return the value otherwise null.		
	getTotal : Return sum of an Array. For string array a concatinated string is returned. Otherwise 0.
	getMedian : 
	getAverage : Updated to support weighted average
	
	Need to include jQueryblockui.js
	blockUI : 
	unblockUI : 
	
 
 */

Utility = {
	getMedian : function(_arr){
		var arr = _arr.slice(0);			
			if(arr.length){
				arr.sort( function(a,b) {return a - b;} );		 
				window.median = arr;
				var half = Math.floor(arr.length/2);
			 
				if(arr.length % 2)
					return arr[half];
				else
					return (parseFloat(arr[half-1]) + parseFloat(arr[half])) / 2.0;		
			}
			else
				return 0;
		},
	getAverage : function(arr,wArr){
		
		var sum = Utility.getTotal(arr,wArr);
		if(wArr && wArr.length==arr.length){
			var wSum = Utility.getTotal(wArr);
			return sum/wSum;
		}
		console.log(sum,arr.length) 
		return sum/(arr.length||1);
	},
	getTotal : function(_arr,wArr){
		var sum=0;
		if(wArr && wArr.length==_arr.length)
		{
			for(var i=0;i<_arr.length;i++){
				sum+=_arr[i]*wArr[i];
			}			
		}else
		{
			for(var i=0;i<_arr.length;i++){
					sum+=_arr[i]
			}
		}
		return sum;
	},
	getUrlParam : function(param){
			var arr =  document.location.search.slice(1).split(/&|=/);
			var i = $.inArray(param,arr);
			return  (i>-1)?decodeURIComponent(arr[i+1]).replace(/\+/g,' '):null;
	},
	/** 2nd param is the query string which will be updated, default is document.location.search */
	updateQueryString : function(obj,url){
		var search = url || document.location.search;
		for(key in obj){
				var nameValue = key+'='+obj[key];

				var regex = new RegExp(key+'[^&]*')

				if(search.match(regex)){
					search = search.replace(regex,nameValue)
				}
				else{					

					var temp = (search?'&':'?')+nameValue;
					search+= temp;
				}
		}
		return search;
	},
	toFixed : function(num,typeNum){
		return !typeNum?num.toFixed(2):(Math.round(num*Math.pow(10,2))/Math.pow(10,2));		
	},
	milliToSec : function(ms,typeNum){
		ms = parseFloat(ms||0)
		num = ms/1000;
		return this.toFixed(num,typeNum)||(!typeNum?'0':0);
	},	
	blockUI : function(el) {		
			if(jQuery.blockUI){
				$(el).block({
					message: '<div class="loading-animator"></div>',
					css: {
						border: 'none',
						padding: '2px',
						backgroundColor: 'none'
					},
					overlayCSS: {
						backgroundColor: '#fff',
						opacity: 0.3,
						cursor: 'wait'
					}
				});
			}
	 },	 
	 unblockUI : function(el) {
		 if(jQuery.unblockUI)
			$(el).unblock();
	}	
}

var breadcrumb = (function(){
	var holder = {};

	$(document).ready(function(){	
		holder.appName = $('ul.breadcrumb li a').eq(0);
		holder.dashboard = $('ul.breadcrumb li a').eq(1);
		holder.urlList = $('ul.breadcrumb li a').eq(2);
		holder.sideBar = $('ul.breadcrumb li a').eq(3);
		
		for(key in holder){
			var anchor = holder[key][0]
			anchor?(anchor.href+=document.location.search):'';		
		}
		var sideBarIndex = Utility.getUrlParam('sideBarIndex');
		$('.page-sidebar>ul:first>li>a.main-link').eq(sideBarIndex).addClass('open');
		
		
		$('.page-sidebar>ul:first>li>a').live('click',function(){
			var domain=Utility.getUrlParam('domain');	
			var appName = Utility.getUrlParam('appName');
			var appId=Utility.getUrlParam('appId');
			var startDate=Utility.getUrlParam('startDate');
			var endDate=Utility.getUrlParam('endDate');
			this.href=this.href+'&appName='+appName+'&domain='+domain+'&appId='+appId+'&startDate='+startDate+'&endDate='+endDate;
		});
		
		
					
	});		
}())
// JavaScript Document

$(document).ready(function() {



    $('#domain').change(function() {

        callback.appData($(this).val());

    })

    $('form[name=appSelect]').submit(function() {
        $('#appName').attr('value', $('#source option:selected').html());
    })

    var startDate = Utility.getUrlParam('startDate');
    var endDate = Utility.getUrlParam('endDate');
    startDate ? $('#startDate').val(startDate) : ''
    endDate ? $('#endDate').val(endDate) : ''
});

calls.init({
    name: 'appData',
    url: serviceBaseUrl + '/newMonkGetApps.php?callback=callback.getAppData'
}).trigger({
    el: $('#selectAppLayer')
});
/** Hold callback related Function */
var prefillAppId = Utility.getUrlParam('appId');
var model = {
    apps: {},
    domains: {}
}
callback = {
    getAppData: function(data) {
        model.apps = data.apps;
        model.domains = data.domains;

        callback.domainData();
    },
    appData: function(domainIndex) {
        $('#source').empty();
        var apps = model.apps[domainIndex];
        for (appIndex in apps) {
            var app = apps[appIndex];
            $('#source').append($('<option value="' + app.id + '">' + app.name + '</option>'));
        }
        if (prefillAppId) {
            $('#source').val(prefillAppId);
            prefillAppId = "";
        } else {
            $('#source')[0].selectedIndex = 0;
        }


    },
    domainData: function(data) {
        var domains = model.domains;
        $('#domain').empty();
        for (domainIndex in domains) {
            var domain = domains[domainIndex];
            $('#domain').append('<option value="' + domain.id + '">' + domain.name + '</option>')
        }
        var domainId = Utility.getUrlParam('domainId');
        if (domainId) {
            $('#domain').val(Utility.getUrlParam('domainId'));
        } else
            $('#domain')[0].selectedIndex = 0;
        $('#domain').change();
    }
}
