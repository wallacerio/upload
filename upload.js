/**
 * Upload
 * ------
 * Send file to server, using the mode of input file html and file of other server
 * 
 * Developed by Wallace Rio <wallrio@gmail.com>
 * Last Update: 15/11/2016
 * 
 */

(function(){


	var Functions = {	

		ajax:function(options){

		var url = options['url'] || null;
		var success = options['success'] || null;
		var progress = options['progress'] || null;
		var data = options['data'] || null;
		var type = options['type'] || 'post';

		var xhr = (function(){
			try{return new XMLHttpRequest();}catch(e){}try{return new ActiveXObject("Msxml3.XMLHTTP");}catch(e){}try{return new ActiveXObject("Msxml2.XMLHTTP.6.0");}catch(e){}try{return new ActiveXObject("Msxml2.XMLHTTP.3.0");}catch(e){}try{return new ActiveXObject("Msxml2.XMLHTTP");}catch(e){}try{return new ActiveXObject("Microsoft.XMLHTTP");}catch(e){}return null;
		})();

		
		xhr.open(type, url, true);

		xhr.upload.onprogress = function (e) {

		    if (e.lengthComputable) {			    	
		    	progress(e.loaded,e.total);			 
		    }
		}
		xhr.upload.onloadstart = function (e) {			    
		    progress(0,e.total);
		}
		xhr.upload.onloadend = function (e) {			  
		    progress(e.loaded,e.total);
		}
	

		xhr.onreadystatechange = function () {

			if(xhr.readyState > 3)					
				if(success)
					success(xhr.responseText);				
		};

		
		var dataForm = new FormData();					
		for (key in data) {
	        if (data.hasOwnProperty(key)){	
	        	if(data[key])
	        		dataForm.append(key,data[key]);
	        	else
	        		dataForm.append(key,data);
	        }
	    }
	    xhr.send(dataForm);
	
				
	},
	


	listEvent:Object(),
		


		sendModeInput:function(options){

			var server = options['server'] || null;
			var id = options['id'] || null;
			var file = options['file'] || null;
			
			if(options['target'] !== null)
				var target = options['target'];
			else
				var target = null;

			if(target == '') target = ' ';

			var progress = options['progress'] || null;
			var complete = options['complete'] || null;
			var delay = options['delay'] || 500;

			var fileInput = document.querySelector(file).files;

			

			if(window.FormData){
			// if(1==2){
		
				var initUp = (function(fileInput,numUp){

					var file_lastModified = fileInput[numUp].lastModified;					
					var file_lastModifiedDate = fileInput[numUp].lastModifiedDate;
					var file_name = fileInput[numUp].name;
					var file_type = fileInput[numUp].type;
					var file_size = fileInput[numUp].size;
					var count = (numUp+1)+"/"+fileInput.length;
					
			
					


					Functions.ajax({
						url:server,
						data:{'target':target,'data':fileInput[numUp]},				
						
						progress:function(loaded,total){
							
							if(loaded == 0 || total == 0){
								var percent = 0;						
							}else{
								var percent = parseInt(100 * loaded / total);						
							}

							if(progress){
								progress(percent,loaded,total,file_name,count);
							}
							if( percent == 100 && (numUp+1) == fileInput.length){						
								 return false;
								 
							}else{
															 						
								if(percent == 100 && (numUp+1) != fileInput.length ){
								
									return false;	
								}
							}
						},
						success:function(response){	
							
							

							if(  (numUp+1) == fileInput.length){
								if(complete)
								  complete();
							}else{
								initUp(fileInput,numUp+1);	
							}
								
								
						},
					});

				});

				initUp(fileInput,0);

				
				return false;

			}else{

				
				var count = Functions.listEvent.length;

				if(document.getElementById("upload_iframe_myFile-"+id) == undefined){


					var iframe = document.createElement("iframe");
					iframe.name = 'upload_iframe_myFile-'+id;
					iframe.id = 'upload_iframe_myFile-'+id;
					
		    	
					iframe.setAttribute("src","javascript:false;");
					
			
					var form = document.createElement("form");
					form.id="upload_iframe_form-"+id;
					form.setAttribute("target", "upload_iframe_myFile-"+id);
					form.setAttribute("action", "upload.php");
					form.setAttribute("method", "post");
					form.setAttribute("enctype", "multipart/form-data");
					form.setAttribute("encoding", "multipart/form-data");
				
					var fileInput_el = document.querySelector(file);

					if (fileInput_el.nextSibling) {
					  fileInput_el.parentNode.insertBefore(form, fileInput_el.nextSibling);
					  fileInput_el.parentNode.insertBefore(iframe, fileInput_el.nextSibling);
					}
					else {
					  fileInput_el.parentNode.appendChild(form);
					  fileInput_el.parentNode.appendChild(iframe);
					}

					

					var inputMode = document.createElement("input");
					inputMode.type="hidden";
					inputMode.name="mode";
					inputMode.value="iframe";					
					form.appendChild(inputMode);

					var inputTarget = document.createElement("input");
					inputTarget.type="hidden";
					inputTarget.name="target";
					inputTarget.value=target;					
					form.appendChild(inputTarget);
				  	form.appendChild(fileInput_el);


					
				}else{
					var form = document.querySelector("#upload_iframe_form-"+id);
					var iframe = document.querySelector("#upload_iframe_myFile-"+id);
					
				}
				

				iframeIdmyFile = document.getElementById("upload_iframe_myFile-"+id);

				iframe.style.visibility='hidden';
				iframe.style.position='absolute';
				form.style.display='inline-table';
		 					
				Functions.iframeInterval(iframe,id,progress,complete,delay,fileInput);

				form.submit();
			}


		},
		timeIntId:Object(),
		iframeInterval:function(iframe,id,progress,complete,delay,fileInput){
			
			
			Functions.timeIntId[id] = setInterval(function(){

			 		if ( iframe.contentDocument ){
			 		 	iFrameBody = iframe.contentDocument.getElementsByTagName('body')[0];
			 		}else if ( iframe.contentWindow ){
					     iFrameBody = iframe.contentWindow.document.getElementsByTagName('body')[0];
					}		
						
			 		var responseArray = iFrameBody.textContent.split("|");
			 		var responseUnit = responseArray[responseArray.length-2];
			 		var responseUnitArray = responseUnit.split('::');
			 		var nameVal = responseUnitArray[0];
			 		var progressVal = responseUnitArray[1];
			 		var countVal = responseUnitArray[2];

			 		var progressValUnit = progressVal.split('/');
			 		var progressLoaded = progressValUnit[0];
			 		var progressTotal = progressValUnit[1];


			 		var countValArray = countVal.split('/');
			 		var countValInit = countValArray[0];
			 		var countValFinit = countValArray[1];
			 	
			 		if(progressLoaded == 0 || progressTotal == 0){
								var percent = 0;						
							}else{
								var percent = parseInt(100 * progressLoaded / progressTotal);						
							}

			 		if(progress)
			 			progress(percent,progressLoaded,progressTotal,nameVal,countVal);

			 	
			 		if(percent == 100 && countValInit == countValFinit){			 		

			 			iframe.parentNode.removeChild(iframe);


			 			iframe.src="";
			 			clearInterval(Functions.timeIntId[id]);
			 			
			 			if(complete)
		            		complete();		 			

		            	
			 		}

			 		

			 	},delay);
		},

		send:function(options){

			var id = options['id'] || null;
			var file = options['file'] || null;
			var server = options['server'] || null;

			if(options['target'] !== null)
				var target = options['target'];
			else
				var target = null;
				

			var delay = options['delay'] || 1000;
			var progress = options['progress'] || null;
			var complete = options['complete'] || null;
			var before = options['before'] || null;
			
			var fileInput = null;
			
			if(before)
				before();

			if(file.indexOf('//') != -1){

			}else{

				if(document.querySelector(file)){				
					fileInput = document.querySelector(file);
					return Functions.sendModeInput(options);
				}
			}

			
			if(target == null){
				target = file.split('/');
				target = target[target.length-1];
			}else{

				var bars = target.split('/');
				bars = bars[bars.length-1];

				var lastBar = bars.lastIndexOf('.');

				if(lastBar == -1){
					var targetAdj = file.split('/');
					targetAdj = targetAdj[targetAdj.length-1];
					target =target+''+ targetAdj;		
				}else{

				}			
			}

			var count = Functions.listEvent.length;

			Functions.listEvent[id]=Object();			
			Functions.listEvent[id]['file'] = file;
			Functions.listEvent[id]['target'] = target;
			Functions.listEvent[id]['status'] = 'start';


			

			if (!window.EventSource) {						  
			  var evtSource = new EventSource(server+'?mode=eventsource&file='+file+'&target='+target);
			  Functions.listEvent[id]['handle'] = evtSource;			

			  Functions.listEvent[id]['handle'].addEventListener("progress", function (e) {
		            var obj = JSON.parse(e.data);
		            
		            Functions.listEvent[count]['status'] = 'onprogress';

		          	var nameVal = file;
		          	var countVal = '1/1';

		            var progressVal = obj.progress;
		            		            
		            var progressValUnit = progressVal.split('/');
			 		var progressLoaded = progressValUnit[0];
			 		var progressTotal = progressValUnit[1];

			 		

		            if(progressLoaded == 0 || progressTotal == 0){
						var percent = 0;						
					}else{
						var percent = parseInt(100 * progressLoaded / progressTotal);						
					}


					

		            if(progress)		         
		            	progress(percent,progressLoaded,progressTotal,nameVal,countVal);   
		           

		            if(  percent == '100'){
		            	Functions.listEvent[id]['handle'].close();
		            	Functions.listEvent[count]['status'] = 'finish';		         	       
		                if(complete)
		            		complete();

		            	
		            }
		        }, false);

			  	Functions.listEvent[id]['handle'].addEventListener('message', function(e) {
				  console.log(e);
				}, false);

				Functions.listEvent[id]['handle'].addEventListener('open', function(e) {				  
				  console.log(e);
				}, false);

				Functions.listEvent[id]['handle'].addEventListener('error', function(e) {
					
				  if (e.readyState == EventSource.CLOSED) {
				    console.log(e);
				  }
				}, false);



			} else {
							
			 	var iframe = document.createElement('iframe');			 	
			 	iframe.id="iframeUpload-"+id;
			 	iframe.className="iframeUpload";
			 	iframe.src=server+"?mode=iframeremote&file="+file+"&target="+target;			 	
			 	iframe.style.display = 'none';
			 	document.body.appendChild(iframe);
			 	Functions.iframeInterval(iframe,id,progress,complete,delay,fileInput);
			 	
			}

		}
	}

	window.upload = Functions;
})();


