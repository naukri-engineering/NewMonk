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
/** Offset is dependent on count and search change, so always get it after getting the later params */
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

$(document).ready(function(){

	var oTable = $('#urlList').dataTable({
		"sDom": '<"H"<"cus_search pull-right">l>t<"F"<"cust_pagination fr">>',
		"aoColumns": [
			{ "sTitle": "Name","sClass": "center"},
			{ "sTitle": "Average Loadtime (sec)","sClass": "center"},
			{ "sTitle": "Pageview","sClass": "center"},
			{ "sTitle": "UrlIds"}
		],
		aoColumnDefs: [
			{
                "mRender": function ( data, type, row,_obj,_url ) {
					_obj = {
						'urlName':data,
						'urlId': row[3],
					}
					_url = Utility.updateQueryString(_obj)
					return '<a target="_blank" href="boomDashboardSummary.php'+_url+'">'+data.replace(/([^\s]{100})/g,'$1\n')+'</a>';
                },
                "aTargets": [ 0 ]
            },
			{ "bVisible": false,  "aTargets": [ 3 ] }
		],
		 "aaSorting": [[ 2, "desc" ]]
	 });


	/*Initializing length dropdown*/
	$('[name=urlList_length]').change(function(){
		tb_urlList.count = parseInt($(this).val())
		tb_urlList.reset_pagein();
		var obj = {offset : tb_urlList.offset,count:tb_urlList.count,query:tb_urlList.getQuery()};
		calls.urlList.trigger({el:$('#urlListGrid')},obj);
	})


	/** Pagination */
	$("div.cust_pagination").html("<span id=\"page_detail\"></span><button id=\"page_prev\" value=\"prev\">Prev</button><button id=\"page_next\" value=\"next\">Next</button>");

	/** Event Binding Start*/
	$('#page_next').click(function(){

		var total_num_pages = tb_urlList.total_num_page();
		/** When page requested is less than total number of pages */
		var next_page_index = tb_urlList.offset/tb_urlList.count+1;
		if(next_page_index <total_num_pages){
			tb_urlList.offset+= tb_urlList.count;
			var obj = {offset : tb_urlList.offset,count:tb_urlList.count,query:tb_urlList.getQuery()};
			calls.urlList.trigger({el:$('#urlListGrid')},obj);
		}
	})
	$('#page_prev').click(function(){

		/** When page requested is greater than or equal to 0 */
		var prev_page_index = tb_urlList.offset/tb_urlList.count-1;
		if(prev_page_index>-1){
//			var query = tb_urlList.query();
			tb_urlList.offset-= tb_urlList.count;
			var obj = {offset : tb_urlList.offset,count:tb_urlList.count,query:tb_urlList.getQuery()};
			calls.urlList.trigger({el:$('#urlListGrid')},obj);
		}
	})
	/**Event Binding End */

	/*Custom Search*/
	$("div.cus_search").html("<input type=\"text\" style=\"float:right\" placeholder=\"Press enter to search\" /><h6 style=\"margin:0\" >Enter more than 3 Characters</h6>");
	$("div.cus_search input").keypress(function(ev){
		if(ev.which==13){
			var query = tb_urlList.setQuery($(this).val());
			var obj = {offset : tb_urlList.offset,count:tb_urlList.count,query:query};
			calls.urlList.trigger({el:$('#urlListGrid')},obj);
		}
	})
	 //UrlList Call
	calls.init({name:'urlList',url:serviceBaseUrl+'/boomGetUrls.php',responseVarName:'urlList',callback:callback.fillUrlTable}).trigger({el:$('#urlListGrid')},{offset : tb_urlList.offset,count:tb_urlList.count,query:tb_urlList.getQuery()});


})


callback = (function(){
	var fillUrlTable  = function(data){

		tb_urlList.totalUrls = data['totalUrls'];

		var oTable = $('#urlList').dataTable();
		oTable.fnClearTable()

		var _data =  data['urls'];
		var loadTime  = null

		for(key in _data){
			if(_data.hasOwnProperty(key))
			{
					var obj = _data[key];
					loadTime = Utility.milliToSec(obj['loadTime']);
					oTable.fnAddData([obj['url'],loadTime,obj['pageViews'],obj['urlId']]);
			}
		}
		$('#page_detail').html('Showing '+((tb_urlList.offset/tb_urlList.count)+1)+' of '+tb_urlList.total_num_page()+' pages ');
	}

	return {
		fillUrlTable:fillUrlTable
	}
}())



