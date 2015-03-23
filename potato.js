// JavaScript Document
var step_shows_arr = [];
var static_last_week;
var tv_json;

$(document).ready(function(e) {
	//get_latest_eps();
	get_shows_list();
	refresh_shows_step();
	//assign clicks to div
	set_up_page_events();
	check_login();
	set_up_schedule();
	//alert(window.innerWidth);
});


function set_up_page_events(){
	$("#menu_icon").click(function(){
		console.log("animate login");
		
		if($("#menu_icon").css("left")=="0px"){
			console.log("in");
		$("#menu_icon").animate({
			left:'180px'},{
				duration:1500,
				step:function(now,fx){
				  $(this).css('-webkit-transform','rotate('+now+'deg)'); 
				  $(this).css('-moz-transform','rotate('+now+'deg)');
				  $(this).css('transform','rotate('+now+'deg)');
			}},1500,function(){
				//callback
				console.log("finished");
			});
		$("#menu").animate({
			left:'0px'
			},1500,function(){
				//callback
				console.log("finished");
			});
		}else{
			console.log("out");
			$("#menu_icon").animate({
			left:'0px'},{
				duration:1500,
				step:function(now,fx){
				  $(this).css('-webkit-transform','rotate('+now+'deg)'); 
				  $(this).css('-moz-transform','rotate('+now+'deg)');
				  $(this).css('transform','rotate('+now+'deg)');
			}},1500,function(){
				//callback
				console.log("finished");
			});
		$("#menu").animate({
			left:'-180px'
			},1500,function(){
				//callback
				console.log("finished");
			});
		}
		
	});
	
	
	//direction either x or y
	$("#shows").drag_plugin({
			speed:25,
			direction:'y'
			});
			
	$("#new_eps").drag_plugin({
			speed:25,
			direction:'y'
			});
			
	$("#history_season_tab_hold").drag_plugin({
			speed:25,
			direction:'x'
			});

		$("#history_season_hold").drag_plugin({
			speed:30,
			direction:'x'
			
			});
			$("#scheduled_shows").drag_plugin({
			speed:25,
			direction:'y'
			});
			
			
			
	 $( "#admin_login" ).dialog({
      autoOpen: false,
      show: {
        effect: "clip",
        duration: 1000
      },
      hide: {
        effect: "clip",
        duration: 1000
      },
	  modal:true,
	 title: "Admin Login",
	 minWidth: 200,
	 minHeight: 220,
	 close: function( event, ui ) {
		 $("#username").css("border-color", "rgb(204,204,204)");
		 $("#password").css("border-color", "rgb(204,204,204)");
		 },
	  buttons: [ { text: "Login", 
	  		click: function() { 
				attempt_login();
				
			}}]
    });		
	$("#password").keypress(function(event){
		if(event.which == 13){
			//pressed enter so attempt loging
			attempt_login();
		}
		});
	
	 $( "#admin_logout" ).dialog({
      autoOpen: false,
      show: {
        effect: "clip",
        duration: 1000
      },
      hide: {
        effect: "clip",
        duration: 1000
      },
	  modal:true,
	 title: "Admin Logout",
	 minWidth: 100,
	 minHeight: 100,
	  buttons: {
        "Logout": function() {
          $.ajax({url:"logout.php",success:function(){
			  $("#menu").empty();
				$("#menu").html('<button onClick="login()"><img src="imgs/admin_login_icon.png" width="16" height="20" /> Login</button>'+
								'<p>Login to be able to add new shows and delete current shows</p>');
								
		  }});
		  $(this).dialog("close");
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });	
			
	
	 $( "#add_show_dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "clip",
        duration: 1000
      },
      hide: {
        effect: "clip",
        duration: 1000
      },
	  modal:true,
	 title: "Add New Tv Show",
	 minWidth: 600,
	 minHeight: 400,
	 close: function( event, ui ) {
		 $("#name").css("border-color", "rgb(204,204,204)");
		 $("#ep_url").css("border-color", "rgb(204,204,204)");
		 $("#error_new_show").text('');
		 },
		 buttons: {
        "Add": function() {
          var name = document.getElementById("name").value;
				var ep_url = document.getElementById("ep_url").value;
				var tvrage_id = $("#tvrage_id").val();
				
				if(name === "" || ep_url === "" || tvrage_id ===""){
					$('#mydia').parent().effect("shake", {times: 4}, 1100);
					if(name ===""){
						document.getElementById("name").style.borderColor="red";
					}
					if(ep_url ===""){
						document.getElementById("ep_url").style.borderColor="red";
					}
					if(tvrage_id === ""){
						$("#error_new_show").text('Error adding to database. Please ensure a new show is selected from the list below');
					}
				}else{
					//$( this ).dialog( "close" ); 
					insertshow(name,ep_url,tvrage_id);
				}
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
	
	$("#name").keypress(function(event){
		if(event.which==13){
			//pressed enter so search
			tvrageapi('showname',this.value);
		}
	});
	
	 $( "#del_show_dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "clip",
        duration: 1000
      },
      hide: {
        effect: "clip",
        duration: 1000
      },
	  modal:true,
	 title: "Delete Show",
	 minWidth: 500,
	 minHeight: 700,
	 close: function( event, ui ) {
		$("#confirm_delete").text('');
		 },
	  buttons: {
        "Delete": function() {
			if(!$("#del_id").val()==""){
					var id = $("#del_id").val(); 	
				$.post( "deleteshow.php", $( "#del_form" ).serialize(), function(data){
						//update list, confirm deletion		
							$("#confirm_delete").text('Show Deleted');
							update_delete_shows_list();
							//update list of shows on side
							get_shows_list();
					});
					
		
			}else{
				$("#confirm_delete").text('Select Show To Be Deleted');
			}
        },
        "Close": function() {
          $( this ).dialog( "close" );
        }
      }
    });	
	
	$("#check_ended").dialog({
		 autoOpen: false,
      show: {
        effect: "clip",
        duration: 1000
      },
      hide: {
        effect: "clip",
        duration: 1000
      },
	  modal:true,
	 title: "Search For Ended TV Shows",
	 minWidth: 400,
	 minHeight: 500,
	 close: function( event, ui ) {
		$("#check_ended_text").html('');
		 },
	  buttons: {
        "Search": function() {
			$("#check_ended_text").html('searching for ended shows.')
        },
        "Close": function() {
          $( this ).dialog( "close" );
        }
      }
    });	
	
	$('#schedule_text, #schedule_pull').click(function(){
		
		var sch_state = $('#schedule').css('left');
		
		
		if (sch_state == "0px"){
			$('#schedule').animate({
			left:-560
			},1500);
		}else{
			$('#schedule').animate({
			left:0
			},1500);
		}		
		});
	
	
	
}

function attempt_login(){
	var username = $("#username").val();
	var password = $("#password").val();
					
					
	if(username === "" || password === ""){
			$('#admin_login').parent().effect("shake", {times: 2}, 1200);
			if(username ===""){
				$("#username").css('border-color','red');
			}
			if(password ===""){
				$("#password").css('border-color','red');
			}
	}else{
			//ajax for password
			$.post( "login.php", $( "#adminform" ).serialize(), function(data){
				
				console.log(data);
				if(data=="true"){
					//logged in
					$("#admin_login").dialog( "close" ); 
					//show logout button and add new show and delete show
					set_up_admin_controls();			
				}else{
					//error
					$('#admin_login').parent().effect("shake", {times: 2}, 1200);
				}
			});
	}
	
}

function set_up_schedule(){
	var d = new Date();
	var today = d.getDay();
	$("#sch_"+today).addClass("linetext_under bg_white");
	get_schedule(today);
}
function switch_day(id){
	$(".sch_day").removeClass("bg_white");
	$("#"+id).addClass("bg_white");
	var day = id.substr(4,5);
	get_schedule(day);
}
function get_schedule(day){
	var text_day = "";
	day = parseInt(day);
	switch(day){
		case 0:
		text_day = "Sunday";
		break;
		case 1:
		text_day = "Monday";
		break;
		case 2:
		text_day = "Tuesday";
		break;
		case 3:
		text_day = "Wednesday";
		break;
		case 4:
		text_day = "Thursday";
		break;
		case 5:
		text_day = "Friday";
		break;
		case 6:
		text_day = "Saturday";
		break;
	}
	
	$.ajax({url:"get_schedule.php?day="+text_day,success:function(result){
		$("#scheduled_shows").empty();
      $("#scheduled_shows").append(result);
    }});
	
}

function get_shows_list(){
	$.ajax({url:"list_tv_shows.php",success:function(result){
		$("#list_of_shows").empty();
      $("#list_of_shows").append(result);
    }});
}

function clicked_list_item(id){
	console.log("the id is: "+id);
	//get history of show.
}
function get_hint(q){
	$.ajax({url:"gethint.php?q="+q,success:function(result){
		$("#list_of_shows").empty();
      $("#list_of_shows").append(result);
    }});
}
function get_latest_eps(){
	//alert("set up latest eps search");
	var last_week = seven_days_ago();
	last_week=last_week[1]+last_week[0]+last_week[2];
	console.log(last_week);
	$.ajax({url:"get_latest_eps.php?last_week="+last_week,success:function(result){
		$("#new_eps").empty();
      $("#new_eps").append(result);
    }});
}
function seven_days_ago(){
	var months = new Array(12);
	months[0] = "01";
	months[1] = "02";
	months[2] = "03";
	months[3] = "04";
	months[4] = "05";
	months[5] = "06";
	months[6] = "07";
	months[7] = "08";
	months[8] = "09";
	months[9] = "10";
	months[10] = "11";
	months[11] = "12";
	var now = new Date();
	now.setDate(now.getDate()-7);
	var searchdate = new Array(2);
	searchdate[0] = months[now.getMonth()];
	searchdate[1] = now.getFullYear();
	searchdate[2] = now.getDate();
	if(searchdate[2]<10){
		searchdate[2]="0"+searchdate[2];
	}
	return searchdate;
}
function get_history(id){
	console.log("clicked id: "+id);
	$("#history_season_hold").html('Loading Data');
	$("#history_tabs").html('');
	$.ajax({
            type: "GET",
            url: "tvrageapi_history.php?value="+id,
            cache: true,
            dataType: "xml",
            success: function(xml) {
				$("#history_season_hold").html('');
				
				var d = new Date();
				var da = d.getDate();
			    var yea = d.getFullYear();
				var mo = d.getMonth()+1;
				mo = (mo<10) ? "0"+mo : mo;
				da = (da<10) ? "0"+da: da;
				var mydate = yea+""+mo+""+da;
				
				var time = -1;
				var store_latest_ep = "none found";
				var store_season = "none found";
				
				var showname = $(xml).find('name').text();
				showname=showname.replace(/ /g,"+");
				var default_img = $(xml).find('image').text();

				//output season num 01 not 1;
				var season_count=1;
				
				 $(xml).find('Season').each(function(){
					
					 var season_num = $(this).attr('no');
					 console.log("---"+season_num+"---");
					 
					 var tv_count=1;
					 
					 var new_season_num = season_num;
					 if(new_season_num<10){
						new_season_num = "0"+new_season_num;
					 }
					 
					 //create the tab for season
					$('<div>', {id:"tv_history_tab_"+season_num } ).appendTo("#history_tabs");
					$("#tv_history_tab_"+season_num).addClass('history_season_tab');
					$("#tv_history_tab_"+season_num).html('Season '+season_num);	
					
					//create click event for tab
					$("#tv_history_tab_"+season_num).on("click", function(){
						//alert(season_num);
						$(".history_season").css("display","none");
						$(".history_season_tab").css("background","#069");
						
						$(this).css("background","orange");
						$("#tv_history_"+season_num).css("display","block");
						
					});				 
					
					//create the holding box for eps
					$('<div>', {id:"tv_history_"+season_num } ).appendTo("#history_season_hold");
					$("#tv_history_"+season_num).addClass('history_season');
					//$("#tv_history_"+season_num).html('Season '+season_num);	
					
					$(this).find('episode').each(function(){
						var epnum = $(this).find('seasonnum').text(),
						title = $(this).find('title').text(),
						img = $(this).find('screencap').text(),
						airdate = $(this).find('airdate').text();
						//2014-10-27
						var air_year = airdate.substr(0,4);
						var air_mon = airdate.substr(5,2);
						var air_day = airdate.substr(8,2);
						
						var uk_date = air_day+"/"+air_mon+"/"+air_year;
						
						console.log(epnum+" - "+title+" - "+airdate);
						
						//create new div for ep and add to season 
						$('<div>', {id:"trse"+epnum+"A"+season_num} ).appendTo("#tv_history_"+season_num);
						$("#trse"+epnum+"A"+season_num).addClass('history_ep_box');
						
						var google_title = title;
						if(title.length>22){
							title = title.substring(0,22)+"...";
						}
						if(!img){
							img = default_img;
						}
						
						var search_title = google_title.replace(/ /g,"+");
						
						var text_link = "<a target='_blank' href=https://www.google.co.uk/search?q=" + showname + "+s"+new_season_num+"e"+epnum+"+720p+torrent+"+search_title+"&ie=UTF-8&safe=off>"+epnum+" - "+title+"</a>"; 
						
						$("#trse"+epnum+"A"+season_num).html("<div class='titlehold'>"+text_link+"</div><img src='"+img+"' height='100' width='150' draggable='false' />\r\r Aired - "+uk_date);
						tv_count=epnum;
						
						
						var ep_airdate = airdate.replace(/-/g,"");
						mydate=parseInt(mydate);
						ep_airdate = parseInt(ep_airdate);
						var ep_time = mydate-ep_airdate;
						
						//time not set
						if(time==-1){
							time=ep_time;
							//store id of ep
							store_latest_ep = "trse"+epnum+"A"+season_num;
							store_season = season_num;
						}
						
						if(ep_time>=0){
							if(ep_time<time){
								time=ep_time;
								//store id of ep
								store_latest_ep = "trse"+epnum+"A"+season_num;
								store_season = season_num;
							}
						}
												
						 
					 });
					
					tv_count=tv_count*180;
					 $("#tv_history_"+season_num).css('width',tv_count+'px');
					 
				season_count=season_num;
					 
				 });
				 season_count=season_count*140;
				 //alert(season_count);
				$("#history_tabs").css("width",season_count+"px");
				
				//console.log("NEWEST EP IS: "+store_latest_ep+" in SEASON: "+store_season);
				//set the correct season to show on click, and color the tab to hightlight it as open and show the latest ep in diff bg color.
				
				$("#tv_history_"+store_season).css("display","block");
				$("#"+store_latest_ep).css('background-color','orange');
				$("#tv_history_tab_"+store_season).css('background-color','orange');
				
				var lefty = $("#tv_history_tab_"+store_season).position().left;
				$("#history_season_tab_hold").scrollLeft(lefty);
				var lefty = $("#"+store_latest_ep).position().left;
				$("#history_season_hold").scrollLeft(lefty);
				
			}
	});
	
	//console.log("fetch wiki link");
	$.ajax({url:"get_link.php?showid="+id,success:function(result){
		//$("#wiki_link").empty();
      $("#wiki_link").html(result);
	  $("#wiki").css('display','block');
	 // console.log(result);
    }});
	
	
	//IF USING THE SCHEDULE BOX CLOSE IT ON CLICK
	var sch_state = $('#schedule').css('left');
		if (sch_state == "0px"){
			$('#schedule').animate({
			left:-560
			},1500);
		}		
		
	
	
	
}

function login(){
	console.log("log in process");
	$( "#admin_login" ).dialog( "open" );
}
function logout(){
	console.log("logout process");
	$( "#admin_logout" ).dialog( "open" );
}
	
	
function check_login(){
	$.ajax({url:"check_logged_in.php",success:function(result){
		//console.log(result);
      if(result=="true"){
		  set_up_admin_controls();
	  }
    }});
}

function set_up_admin_controls(){
	$("#menu").empty();
	$("#menu").html('<button onClick="add_show()"><img src="imgs/greenlight.png" width="20" height="20" /> Add Show</button>'+
					'<button onClick="delete_show()"><img src="imgs/trash.png" width="20" height="20" /> Delete Show</button>'+
					'<button onClick="refresh_shows()"><img src="imgs/refresh.png" width="20" height="20" /> Refresh</button>'+
					'<button onClick="check_ended()"><img src="imgs/end.png" width="20" height="20" /> Check Ended</button>'+
					'<button onClick="logout()"><img src="imgs/exit.png" width="20" height="20" /> Logout</button>');
}
	

function add_show(){
	console.log("start adding show");
	$("#add_show_dialog").dialog("open");
}
	
function tvrageapi(option,value){
	$("#resultstv").empty();
	$("#resultstv").html('<center><img src="imgs/loader.gif" height="190" width="190" /><br/>Loading TV Shows</center>');
	$.ajax({
	  url: "tvrageapi.php?value="+value,
	  cache: false
	})
	  .done(function( html ) {
		$("#resultstv").empty();
		$("#resultstv").html(html);
	  });
	
}	
	
function insertshow(name,ep_url,tvrageid){
	$.post( "insert.php", $( "#add_show_cloud" ).serialize(), function(data){
						if(data=="added"){
							//logged in
							$("#error_new_show").text('Added Show! - '+$("#name").val());
							
							$("#name").val("");
							$("#ep_url").val("");
							$("#resultstv").empty();
						 		
							
								get_shows_list();				
							
						}else{
							//error inserting data
							$("#error_new_show").text('Error adding to database. Please ensure a new show is selected from the list below');
							$('#add_show_dialog').parent().effect("shake", {times: 2}, 1200);
						}
						
					});
}
	
	function getid(){
		console.log("clicked");
	}
	
function getshowid(id,name,thi){
	name = decodeURIComponent(name).replace(/\+/g," ");
//	alert("id:"+id+" name:"+name);
	$("#name").val(name);
	$("#tvrage_id").val(id);
	//set href of search
	name = name.replace(/ /g,"+");
	$("#wiki_search_link").attr("href","https://www.google.co.uk/search?q=wiki+episodes+"+name);
	$(".tvrage_list").css("background-color",'#ccc');
	$(thi).parent().css('background-color', '#39C');
	
}
	
	
function delete_show(){
	$("#del_show_dialog").dialog("open");
	update_delete_shows_list();	
}
	
function update_delete_shows_list(){
	$.ajax({url:"delete_shows_list.php", success: function(data){
		console.log("updating");
		$("#delresultstv").empty();
		$("#delresultstv").html(data);
		
	}});
}
function get_del_id(id,thi){
	$("#del_id").val(id);
	console.log($("#del_id").val());
	$(".tvrage_list2").css("background-color",'#ccc');
	$(thi).parent().css('background-color', '#39C');
}
	
	
function refresh_shows(){
	$.ajax({url:"refresh_list.php",success: function(){
		console.log("refreshed timestamp");
		//now call epsearch
		$("#new_eps").empty();
		$("#new_eps").html('<center><img src="imgs/loader.gif" height="317" width="317" /><br/>Loading TV Shows</center>')
		refresh_shows_step();
	}});
}

/*function refresh_shows_yesterday(){
	var d = new Date();
	d = d.getDay();
	d--;
	var text_day = "";
	switch(d){
		case -1:
		text_day = "Saturday";
		break;
		case 0:
		text_day = "Sunday";
		break;
		case 1:
		text_day = "Monday";
		break;
		case 2:
		text_day = "Tuesday";
		break;
		case 3:
		text_day = "Wednesday";
		break;
		case 4:
		text_day = "Thursday";
		break;
		case 5:
		text_day = "Friday";
		break;
		case 6:
		text_day = "Saturday";
		break;
	}	
	var last_week = seven_days_ago();
	last_week=last_week[1]+last_week[0]+last_week[2];
	
	//set timestamp to old
	$.ajax({url:"refresh_list.php",success: function(){
		console.log("refreshed timestamp");
		$("#new_eps").empty();
		$("#new_eps").html('<center><img src="imgs/loader.gif" height="317" width="317" /><br/>Loading TV Shows</center>')
		
		get_yesterday(text_day,last_week);
	}});
	

}*/

/*function get_yesterday(day,last_week){

	$.ajax({url:"get_latest_eps_yesterday.php?day="+day+"&last_week="+last_week,success: function(data){
		$("#new_eps").empty();
      $("#new_eps").append(data);
		
	}});
		
}*/



function refresh_shows_step(){
	//get the list of shows, which are still airing
	//store them in a gobal array
	//call function which ajaxs x num shows a time
	//and on success it recalls itself 
	console.log("steping");
	var last_week = seven_days_ago();
	static_last_week=last_week[1]+last_week[0]+last_week[2];
	
	$("#new_eps").empty();
	$("#new_eps").html('<center><img src="imgs/loader.gif" height="317" width="317" /><br/>Loading TV Shows</center>');
	
	$.ajax({
    url: "list_tv_shows_step.php",
    dataType:"json",
    success: function(data) {
		console.log("data is: "+data);
		if(data!=0){
		step_shows_arr = data;
 		//console.log(step_shows_arr[4]);  
		
		//start from zero
		step_search(0);
		}else{
			//get cache shows
			$.ajax({url:"cache_shows.php",success: function(data){
				$("#new_eps").empty();
				$("#new_eps").append(data);
			}});
		}
   }});
	
}

function step_search(startpos){
	console.log("starting from: "+startpos);
	if(startpos<=step_shows_arr.length){
		
		var s1 = step_shows_arr[startpos];
		var s2 = step_shows_arr[startpos+1];
		var s3 = step_shows_arr[startpos+2]; 
		
		console.log("sending: "+s1+" : "+s2+" : "+s3); 
		
		 s1==undefined ? s1="undefined" : "";   
		 s2==undefined ? s2="undefined" : "";   
		 s3==undefined ? s3="undefined" : "";   
		
		//s3="undefined";
		//s2="undefined"; 
		
		//javascript check if array is oversixed
		$.ajax({url:"step_search.php?last_week="+static_last_week+"&s1="+s1+"&s2="+s2+"&s3="+s3}).done(function(data){
			//append data to results tv
			//could re-order as well
			$("#new_eps").empty();
      		$("#new_eps").append(data);
			
			var temp_startpos = startpos+3;
			//excedded the array lenght
			s1=="undefined" ? temp_startpos-- : "";
			s2=="undefined" ? temp_startpos-- : "";
			s3=="undefined" ? temp_startpos-- : "";
			
			var percentage = (100 / step_shows_arr.length) * (temp_startpos);
//percentage = parseInt(percentage);
percentage =percentage.toFixed(2);

			var content_step ="<progress value='"+temp_startpos+"' max='"+step_shows_arr.length+"'></progress> Step Search: "+(temp_startpos)+"/"+step_shows_arr.length+" "+percentage +"%";
			
			if(percentage==100){
				content_step = "Search Complete!";
			}
			$("#step_notify").html(content_step);
			console.log("just got: "+startpos);
			step_search(startpos+3);
		});
	}else{
		//retrieved all shows so update timestamp
		$.ajax({url:"updatetimestamp.php",success: function(){
			console.log("updated timestamp");
		}});
	}
}

function check_ended(){
	$("#check_ended").dialog("open");
	
	$.getJSON("list_tv_shows_array.php", function(result){
		tv_json = result;
		//console.log("arr len "+tv_json.length);
		//console.log("data 0: "+tv_json[0].id+" data 1: "+tv_json[0].name);
		
		$("#check_ended_gif").html('<center><img src="imgs/loader.gif" height="150" width="150" /><br/>Searching TV Shows</center>');
		check_ended_search(0);
       /* 
	   	$.each(tv_json, function(i, show){
           console.log(show.id + ":"+ show.name);
        });
		*/
    });
	// some sort of step search to find ended . find_ended.php
}

function check_ended_search(startpos){
	console.log("starting from: "+startpos);
	if(startpos<=tv_json.length){
		
		var s1 = (tv_json[startpos]) ? tv_json[startpos].id : "undefined";
		var s2 = (tv_json[startpos+1]) ? tv_json[startpos+1].id : "undefined";
		var s3 = (tv_json[startpos+2]) ? tv_json[startpos+2].id : "undefined";
		

		console.log("sending: "+s1+" : "+s2+" : "+s3); 
		
		 s1==undefined ? s1="undefined" : "";   
		 s2==undefined ? s2="undefined" : "";   
		 s3==undefined ? s3="undefined" : "";   
		
		//s3="undefined";
		//s2="undefined"; 
		
		$.ajax({url:"find_ended.php?s1="+s1+"&s2="+s2+"&s3="+s3,cache:false}).done(function(data){
			
			console.log("back");
			$("#check_ended_gif").empty();
			$("#check_ended_text").append(data);
		
			
			var temp_startpos = startpos+3;
			//excedded the array lenght
			s1=="undefined" ? temp_startpos-- : "";
			s2=="undefined" ? temp_startpos-- : "";
			s3=="undefined" ? temp_startpos-- : "";
			
			var percentage = (100 / tv_json.length) * (temp_startpos);
//percentage = parseInt(percentage);
percentage =percentage.toFixed(2);

			var content_step ="<progress value='"+temp_startpos+"' max='"+tv_json.length+"'></progress> Step Search: "+(temp_startpos)+"/"+tv_json.length+" "+percentage +"%";
			
			if(percentage==100){
				content_step = "Search Complete!";
			}
			$("#check_ended_notify").html(content_step);
			console.log("just got: "+startpos);
			check_ended_search(startpos+3);
		});
	}
}
	
	
	
	
