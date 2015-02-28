// JavaScript Document
//Place the plugin within an anonymous function to avoid outside problems
(function($){

	//Attatch the new method		   
	jQuery.fn.extend({

		//Plugin Name
		drag_plugin: function(options) {
			
			
				var defaults = {
                speed: 5,
                otherThing: 'hello',
				direction: 'x'
            	};
			
					// This is the easiest way to have default options.
				var settings = $.extend(defaults,options);
				
				
				
				
				var selector = this;
				var bouncer =0;
				var moved = false;
				
				this.on("mousedown",function(e){
				
				//console.log("START--------------: "+e.pageX);
				
				if(settings.direction=="x"){
					var startx = e.pageX;
				}else{
					var startx = e.pageY;
				}
						
				selector.on("mousemove",function(e){
				//console.log("moving "+e.pageY);
					moved=true;
					if(settings.direction=="x"){
						var newx = startx - e.pageX;
					}else{
						var newx = startx - e.pageY;
					}
						
							//if newx is postive then movement to left, if negative then movement to right
						if(newx>0){
							if(newx>settings.speed){
							newx=-settings.speed; 
							//limit the speed of drag
							}else{
								//dont drag faster if slow original
								newx=-newx;
							}
						}else if(newx<0){
							//get the value as postive number
							var tx = newx*-1;
							if(tx>settings.speed){
							newx=settings.speed;
							}else{
								newx=tx;
							}
						}
						//console.log("new x-------------: "+newx);
							
						if(settings.direction=="x"){
							var scrollx = selector.scrollLeft();
							//minus a minus number equals a postive
							//so if startx is 500 and e.pagex is 800 then we work out newx as -300 but to increase scrollleft we make the speed a minus number and minus it from scrollx
							// so 500 - 800 = -300, if speed is 30 then make is -30, then 500 - -30 = 530 so we move correct way, this is needed to help both ways of movement
							selector.scrollLeft(scrollx-newx);		
						}else{
							var scrollx = selector.scrollTop();
							selector.scrollTop(scrollx-newx);		
						}	
							
							
							
						
						//set the startx to the current x so there is no offset on change of direction
						if(settings.direction=="x"){
							startx=e.pageX;	
						}else{
							startx=e.pageY;		
						}
						
						bouncer = newx;
						});
				});
			
						
					this.on("mouseup",function(e){
					//console.log("up "+e.pageX);
					selector.off("mousemove");
					
					if(moved==true){
					/*TESTER STUFF -----------------*/
					var mytime = setTimeout(function(){myStopFunction()}, 1000);
					var myVar = setInterval(function(){myTimer()}, 10);
					
					function myTimer() {
						var movement = 2;
						if(bouncer<0){
							movement = -2;
						}
						
						
						if(settings.direction=="x"){
							var xt = selector.scrollLeft();
							selector.scrollLeft(xt-bouncer);	
						}else{
							var xt = selector.scrollTop();
							selector.scrollTop(xt-bouncer);			
						}
						
						
						
						
						moved=false;
						if(bouncer<0){
							bouncer=bouncer*-1;
							bouncer=bouncer-0.5;
							bouncer=bouncer*-1;
						}else if(bouncer>0){
							bouncer=bouncer-0.5;
						}
						if(bouncer==0){
						}
					}
					
					function myStopFunction() {
						clearInterval(myVar);
					}
					/*TESTER STUFF -----------------*/
					}
					
					});
			
					this.on("mouseleave",function(e){
					//console.log("up "+e.pageX);
					//alert("left");
					selector.off("mousemove");
					});
					
					
				
				
		 /*
				// Greenify the collection based on the settings variable.
				return this.css({
					color: settings.color,
					backgroundColor: settings.backgroundColor
				});
			*/
		}

	});
})(jQuery);