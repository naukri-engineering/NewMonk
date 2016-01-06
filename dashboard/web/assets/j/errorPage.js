// JavaScript Document
/** Offset is dependent on count and search change, so always get it after getting the later params */


$(document).ready(function(){	

	$('#filterSearch').click(function(){
		getFilter();
		Tables.tb_urlList.reset_pagein();
		param.pageNumber=Tables.tb_urlList.offset+1;
		param.quant=Tables.tb_urlList.count;
		calls.loadStatesData.trigger({el:$('#nLoggerTable_wrapper')},param)
		filterCalls();
	
	});	

	// sending file ID to next page	
	$('#nLoggerTable .inner-table .all-file').live('click',function(e){
		e.preventDefault();
		var fileName=$(this).data('file');
		var newurl=Utility.updateQueryString({fileId:fileName});
		window.open("pageList.php"+newurl,"_blank");
	});

	// link to show all results		
	$('#all-result').click(function(){
		location.reload();
	});
	
	// dropdown to change number of results
	$('[name=nLoggerTable_length]').change(function(){
		Tables.tb_urlList.count = parseInt($(this).val())
		Tables.tb_urlList.reset_pagein();
		param.pageNumber=Tables.tb_urlList.offset+1;
		param.quant=Tables.tb_urlList.count;
		if($('div.cus_search input').val()!=""){
		query=$('div.cus_search input').val()||"";
		param.query=query;
		}
		getFilter();
		calls.loadStatesData.trigger({el:$('#nLoggerTable_wrapper')},param);	
	})

	$('[name=pageListTable_length]').change(function(){
		Tables.tb_pageList.count = parseInt($(this).val())
		Tables.tb_pageList.reset_pagein();
		param2.pageNumber=Tables.tb_pageList.offset+1;
		param2.quant=Tables.tb_pageList.count;
		
		calls.pageListData.trigger({el:$('#pageListTable_wrapper')},param2);	
	})

	// adding pagination div
	$("div.cust_pagination").html("<span id=\"page_detail\"></span><button id=\"page_prev\" value=\"prev\">Prev</button><button id=\"page_next\" value=\"next\">Next</button>");	
	/** Event Binding Start*/
	
	// next button  on nlogger table
	$('#nLoggerTable_wrapper #page_next').click(function(){
		var total_num_pages = Tables.tb_urlList.total_num_page();		
		/** When page requested is less than total number of pages */
		//var next_page_index = Tables.tb_urlList.offset/Tables.tb_urlList.count+1;
		next_page_index = Tables.tb_urlList.offset+1;
		if(next_page_index <total_num_pages){
			Tables.tb_urlList.offset+= 1;
			var obj = {pageNumber : Tables.tb_urlList.offset,quant:Tables.tb_urlList.count};
			param.pageNumber=Tables.tb_urlList.offset+1;
			param.quant=Tables.tb_urlList.count;
			calls.loadStatesData.trigger({el:$('#nLoggerTable_wrapper')},param);	
			
		}
	})
	
	// prev button on nlogger
	$('#nLoggerTable_wrapper #page_prev').click(function(){
		/** When page requested is greater than or equal to 0 */
		//Tables.tb_pageList.offset=param2.pageNumber;
		var prev_page_index = Tables.tb_urlList.offset;
		if(prev_page_index>0){	
		Tables.tb_urlList.offset= prev_page_index;
		param.pageNumber=prev_page_index;
		Tables.tb_urlList.offset=prev_page_index-1
		calls.loadStatesData.trigger({el:$('#nLoggerTable_wrapper')},param);	
		}
	})
	
	//next button on pagelist table
	$('#pageListTable_wrapper #page_next').click(function(){
		var total_num_pages2 = Tables.tb_pageList.total_num_page();		
		/** When page requested is less than total number of pages */
		//var next_page_index = Tables.tb_urlList.offset/Tables.tb_urlList.count+1;
		next_page_index2 = Tables.tb_pageList.offset+1;
		if(next_page_index2 <total_num_pages2){
			Tables.tb_pageList.offset+= 1;
			param2.pageNumber=Tables.tb_pageList.offset+1;
			param2.quant=Tables.tb_pageList.count;
			calls.pageListData.trigger({el:$('#pageListTable_wrapper')},param2);	
		}
	})
	
	// prev button on pagelist table
	$('#pageListTable_wrapper #page_prev').click(function(){
		/** When page requested is greater than or equal to 0 */
		var prev_page_index2 = Tables.tb_pageList.offset;
		if(prev_page_index2>0){	
		Tables.tb_pageList.offset= prev_page_index2;
		param2.pageNumber=prev_page_index2;
		Tables.tb_pageList.offset=prev_page_index2-1
		calls.pageListData.trigger({el:$('#pageListTable_wrapper')},param2);	
		}
	})
	

	var strSearch='<div class="cus_search pull-right"><input type="text" placeholder="Press enter to search"> <button type="submit" class="btn btn-success" id="btn-search"><span class="icon icon-search"></span></button><h6 style="margin:0">Enter more than 3 Characters</h6></div>';
	$(strSearch).insertAfter($('#test2'));
	
	$('.selectpicker').selectpicker();

	$("div.cus_search input").keypress(function(ev){
		if(ev.which==13){
			var query = Tables.tb_urlList.setQuery($(this).val());
			getFilter();
			Tables.tb_urlList.reset_pagein();
			param.pageNumber=Tables.tb_urlList.offset+1;
			param.quant=Tables.tb_urlList.count;
			param.query=query;
		calls.loadStatesData.trigger({el:$('#nLoggerTable_wrapper')},param);	
		filterCalls();
		}
	})	
	
	$("#btn-search").click(function(){
		var query = Tables.tb_urlList.setQuery($(this).val());
			getFilter();
			Tables.tb_urlList.reset_pagein();
			param.pageNumber=Tables.tb_urlList.offset+1;
			param.quant=Tables.tb_urlList.count;
			param.query=query;
		calls.loadStatesData.trigger({el:$('#nLoggerTable_wrapper')},param);	
		filterCalls();
		});


	$('#changeDate').click(function(){	  
	
	var sDate=	$('#startDate').datepicker("getDate").getTime();
	var eDate=$('#endDate').datepicker("getDate").getTime();
	
	var one_day=1000*60*60*24;
	var days_diff= Math.round((eDate-sDate)/one_day); 

	//allow user to get data only for a range of max seven days
	if(days_diff<7){
		document.location.href = Utility.updateQueryString({startDate:$('#startDate').val(),endDate:$('#endDate').val()})	
	}
	else{
	var errorStr='<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Sorry!</strong> The date range cannot be more than 7 days. </div>';
	
		$("#error-msg").html(errorStr);
		}
	}) ;

	$('li.open:visible .title,.sub-menu li.open:visible').click();     
	
	/** Bind Events on Reload Button */
	$('.grid .tools a.reload').live('click', function (e) {
		$('div.cus_search input').val("");
		quant=$('[name=nLoggerTable_length]').val();
		param={startDate:startDate,endDate:endDate,appId:appId,pageNumber:1,quant:quant,groupBy:'None',cb:'Tables.resourceTimeTable'}
		calls.loadStatesData.trigger({el:$('#nLoggerTable_wrapper')},param)
		filterCalls();
	});
	          
})

function getFilter(){
	var s1,s2,s3,s4,s5,s6;
	
		if($('#select-file option:selected').length!= 0){
			
	 var a1 = $('#select-file option:selected');
		s1=encodeURIComponent(a1[0].text);

				if(a1.length>1){
		for(var i=1;i< a1.length;i++)
        {  s1+="&file[]="+encodeURIComponent(a1[i].text);}
				}
				param['file[]']=s1;
		}
		
		if($('#select-error option:selected').length!=0){
		var a2 = $('#select-error option:selected');
		s2=encodeURIComponent(a2[0].text);
				if(a2.length>1){

		for(var i=1;i< a2.length;i++)
        {  s2+="&error_msg[]="+encodeURIComponent(a2[i].text);}
		
				}
				param['error_msg[]']=s2;
		}
		
		if($('#select-url option:selected').length!=0){
		var a3 = $('#select-url option:selected');
		s3=encodeURIComponent(a3[0].text);
		if(a3.length>1){
		for(var i=1;i< a3.length;i++)
        {  s3+="&url_tag[]="+a3[i].innerHTML;}
		}
				param['url_tag[]']=s3;

		}
		
		if($('#select-os option:selected').length!= 0){
			
	 var a4 = $('#select-os option:selected');
		s4=a4[0].value;

				if(a4.length>1){
		for(var i=1;i< a4.length;i++)
        {  s4+="&os_id[]="+a4[i].value;}
				}
				param['os_id[]']=s4;
		}
		
		if($('#select-browser option:selected').length!= 0){
			
	 var a5 = $('#select-browser option:selected');
		s5=a5[0].value;

				if(a5.length>1){
		for(var i=1;i< a5.length;i++)
        {  s5+="&browser_id[]="+a5[i].value;}
				}
				param['browser_id[]']=s5;
		}
		
		if($('#select-res option:selected').length!= 0){
	 var a6 = $('#select-res option:selected');

		s6=a6[0].value;

				if(a6.length>1){
		for(var i=1;i< a6.length;i++)
        {
			s6+="&screen_reso_id[]="+a6[i].value;}
				}
				param['screen_reso_id[]']=s6;
		}
		
	}

function filterCalls(){
	calls.filterDataFile.trigger({el:null},param);
	calls.filterDataOS.trigger({el:null},param);
	calls.filterDataScreen.trigger({el:null},param);
	calls.filterDataError.trigger({el:null},param);
	calls.filterDataURL.trigger({el:null},param);
	calls.filterDataBrowser.trigger({el:null},param);
	}
// JavaScript Document