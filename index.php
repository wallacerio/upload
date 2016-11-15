<!-- 


  Upload
  ======
  Send file to server, using the mode of input file html and file of other server
  
  Developed by Wallace Rio <wallrio@gmail.com>
  Last Update: 15/11/2016


-->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Upload - Send file to server</title>
	<link rel="stylesheet" href="examples/css/responsible.css">
	<style type="text/css">
	*{
		font-family: arial;
	}
	body{
		background: #eee;
	}
	a{
		color: blue;
		text-decoration: underline;
		cursor: pointer;
	}
	pre{
		white-space: pre-wrap;
	}
	.content{
		max-width: 1024px;
		margin: auto;
		}
	.area{
		
		background: #fff;
		margin: auto;
		padding: 50px;
		border: 1px solid #ccc;
		box-shadow: 0px 0px 5px -2px #000;
	}.area hr{
		border:0px;
		padding-top: 10px;
		padding-bottom: 10px;
		margin-bottom: 20px;
		border-bottom: 1px solid #ccc;
	}
	label{
		display: table-cell;
		font-weight: bold;
		color: #444;
	}
	

		.uploadProgress{
			display: table;
			width: 100%;
			height: 32px;
			border:1px solid blue;
			position: relative;
		}.uploadProgress span{
			display: table;
			height: 100%;
			background:blue;
			position: relative;
		}.uploadProgress span label{
			color: #fff;
			font-weight: bold;
			display: table-cell;
			vertical-align: middle;
			text-align: center;
			white-space: nowrap;
		}



		table{

		}table tr{
		}table tr td{
			padding: 5px;
			border-bottom: 1px solid #ccc;
			padding-bottom: 16px;
		}
	</style>
	<script type="text/javascript" src="upload.js"></script>

	
</head>
<body>

	<h1>Upload </h1>
	<p>Send file to server, using the mode of input file html and file of other server</p>	
	<p>Version: 1.0 - Last Modified: 15/11/2016</p>
	<p>Developed by Wallace Rio &lt;wallrio@gmail.com></p>
	<hr>

	<h2>How use:</h2>
	<p>Inser the script on document</p>
	<pre>
		&lt;script type="text/javascript" src="upload.js">&lt;/script>
	</pre>

	<h4>Download the javascript</h4>
	<a href="upload.js">Download the script</a>

	<h4>Download the file upload.php on your server:</h4>
	
	<p>This file is responsible for process of upload</p>

	<a href="upload.php.zip" download>upload.php</a>

	<hr>

	

	<!-- Example 1 ================================ -->
	

	<div class="content">

		<h2>Example 1:</h2>
		<p>Enviar um arquivo de um servidor remoto para o servidor local, usando o mesmo nome de arquivo.</p>

		<div class="area">
			<p>Click on Start to run the upload</p>			
			
			<div class="row">
				<div class="column">
					<a onclick="ex1_start();">Start </a> 
				</div>
				<div class="column">
					
					<div id="progress1" class="uploadProgress">
						<span style="width:0%"><label></label></span>
					</div>

				</div>
			</div>

			
			
			<hr>
<pre>
	&lt;script>
		upload.send({			
			file:'http://domain.com/file-remote.zip',
			server:'http://your-server/upload/upload.php',
			target:'directory-on-server-local/file.zip',		
			progress:function(val){
				// the value of val is porcentage of upload				
			},
			complete:function(){
				alert('ex1: upload complete');
			}
		});
	&lt;/script>
</pre>
	

	<script type="text/javascript">

	function ex1_start(){
		upload.send({	
			id:'ex1',		
			file:'http://localhost/cidades.zip',		
			server:'http://localhost/plugins/upload/upload.php',						
			target:'',		
		
			progress:function(percent,loaded,total,name,countVal){								
				document.querySelector('#progress1 > span').style.width = percent + "%";
				document.querySelector('#progress1 > span > label').innerHTML = percent+'('+loaded+'/'+total + ') % '+' - '+name+' - '+countVal;
			},
			complete:function(){
				alert("file downloaded");
			}
		});
	}


	</script>
	

		</div>
	</div>















<!-- Example 2 ================================ -->
	


<div class="content">

		<h2>Example 2:</h2>
		<p>Enviar um arquivo de um servidor remoto para o servidor local.</p>

		<div class="area">
			<p>Click on Start to run the upload</p>			
			
			<div class="row">
				<div class="column">
					<a onclick="ex2_start();">Start </a> 
				</div>
				<div class="column">
					
					<div id="progress2" class="uploadProgress">
						<span style="width:0%"><label></label></span>
					</div>

				</div>
			</div>

			
			
			<hr>
<pre>
	&lt;script>
		upload.send({	
			id:'upload1',
			file:'http://domain.com/file-remote.zip',
			server:'http://your-server/upload/upload.php',
			target:'directory-on-server-local/file.zip',		
			progress:function(val){
				// the value of val is porcentage of upload				
			},
			complete:function(){
				alert('ex2: upload complete');
			}
		});
	&lt;/script>
</pre>
	

	<script type="text/javascript">

	function ex2_start(){
		upload.send({
			id:'up1',
			file:'http://webfocosaopaulo.com.br/lp/cidades.zip',
			server:'http://localhost/plugins/upload/upload.php',
			target:'test.zip',			
			progress:function(percent,loaded,total,name,countVal){							
				document.querySelector('#progress2 > span').style.width = percent + "%";
				document.querySelector('#progress2 > span > label').innerHTML = percent+'('+loaded+'/'+total + ') % '+' - '+name+' - '+countVal;
			},
			complete:function(response){
				alert(JSON.stringify(response));
			}
		});
	}


	</script>
	

		</div>
	</div>		
















<!-- Example 3 ================================ -->
	

<div class="content">

		<h2>Example 3:</h2>
		<p></p>

		<div class="area">
			<p>Click on Start to run the upload</p>			
			
			<div class="row">
				<div class="column">
					<input type="file" name='files2[]' multiple="multiple">
					<a onclick="ex3_start();" >Start </a> 
				</div>
				<div class="column">
					
					<div id="progress3" class="uploadProgress">
						<span style="width:0%"><label></label></span>
					</div>

				</div>
			</div>

			
			
			<hr>
<pre>

</pre>
	

	<script type="text/javascript">

	function ex3_start(){
		upload.send({
			id:'up3',
			file:"[name='files2[]']",
			server:'http://localhost/plugins/upload/upload.php',
			target:'',		
			progress:function(percent,loaded,total,name,countVal){				
				document.querySelector('#progress3 > span').style.width = percent + "%";
				document.querySelector('#progress3 > span > label').innerHTML = percent+'('+loaded+'/'+total + ') % '+' - '+name+' - '+countVal;
			},
			complete:function(response){
				alert(JSON.stringify(response));
			}
		});
	}


	</script>
	

		</div>
	</div>		




<!-- Example 4 ================================ -->
	

<div class="content">

		<h2>Example 4:</h2>
		<p></p>

		<div class="area">
			<p>Click on Start to run the upload</p>			
			
			<div class="row">
				<div class="column">
					<input type="file" name='files4[]' multiple="multiple">
					<a onclick="ex4_start();" >Start </a> 
				</div>
				<div class="column">
					
					<div id="progress4" class="uploadProgress">
						<span style="width:0%"><label></label></span>
					</div>

					<div id="listfiles_ex4">
						#
					</div>
				</div>
			</div>

			
			
			<hr>
<pre>

</pre>
	

	<script type="text/javascript">

	function ex4_start(){
		upload.send({
			id:'up4',
			file:"[name='files4[]']",
			server:'http://localhost/plugins/upload/upload.php',
			target:'up4/',		
			before:function(){
				
				var files = document.querySelector("[name='files4[]']").files;
		

				var html = '';
				html += '<ul>';

				for (var i = 0; i < files.length; i++) {

					var id = 'idfile_'+files[i].name;

					html += '<li data-id="'+id+'">';
					html += '<span>'+files[i].name+'</span> - <span data-rel="progress"></span>';
					html += '</li>';

	
				};

				
				html += '</ul>';

				document.querySelector('#listfiles_ex4').innerHTML = html;
			},
			progress:function(percent,loaded,total,name,countVal){	
				
				var id = 'idfile_'+name;					
				
				if(document.querySelector("[data-id='"+id+"']"))
				document.querySelector("[data-id='"+id+"'] [data-rel='progress']").innerHTML = percent + "%";

				document.querySelector('#progress4 > span').style.width = percent + "%";
				document.querySelector('#progress4 > span > label').innerHTML = percent+'('+loaded+'/'+total + ') % '+' - '+name+' - '+countVal;
			},
			complete:function(){
				var files = document.querySelector("[name='files4[]']").files;
				// alert(files.length);

				/*for (var i = 0; i < files.length; i++) {
					alert(JSON.stringify(files[i]));
				};*/
			}
		});
	}


	</script>
	

		</div>
	</div>		









	<hr>



	<div class="content">

		<h2>API:</h2>
		<p></p>

		<div class="area">
			<table>
				<tr>
					<td>id</td>
					<td>optional</td>
					<td>Use for identify the upload</td>
				</tr>
				<tr>
					<td>file</td>
					<td>required</td>
					<td>URL valid of file remote for upload to server local

					<br>

					<strong>example:</strong><br>
						file:"[name='files[]']",						<br>
						target:"http://domain-remote/file-remote.zip"

					</td>
				</tr>
				<tr>
					<td>server</td>
					<td>required</td>
					<td>URL valid of file on local server to upload process</td>
				</tr>
				<tr>
					<td>target</td>
					<td >required</td>
					<td>path to save the file remote on server local<br><br>

					<strong>example:</strong><br>
						target:'../',						<br>
						target:'/home/wallrio/files/www/plugins/pair/'

					</td>
				</tr>
				<tr>
					<td>delay</td>
					<td>optional</td>
					<td>define refresh show bar in the browser of user if not support 'EventSource', (padrão 500ms) 
					<br>

					<strong>example:</strong><br>
						delay:2000,						<br>
						target:100

					</td>
				</tr>
				<tr>
					<td>progress</td>
					<td>optional</td>
					<td>function to monitoring the process of upload</td>
				</tr>
				<tr>
					<td>complete</td>
					<td>optional</td>
					<td>function to execute on complete upload </td>
				</tr>
			</table>
		</div>

	</div>
</body>
</html>