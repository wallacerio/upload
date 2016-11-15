<?php

/**
 * Upload
 * ------
 * Send file to server, using the mode of input file html and file of other server
 * 
 * Developed by Wallace Rio <wallrio@gmail.com>
 * Last Update: 15/11/2016
 * 
 */

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');


class Upload{
	public static $currentPath = null;
	public static $mode = null;
	public static $target = null;
  public static $fileHandle = null;
	public static $file = null;

	
	/**
	 * grava o arquivo localmente
	 * @param  [type] $fileDataArray array contendo informações do arquivo, semelhante a variavel $_FILE
	 * @param  [type] $key           informa a ordem do arquivo atual
	 * 
	 */
	public static function writeDirect($fileDataArray,$key){
		
		$filesList = self::$fileHandle;

		$files_name = $fileDataArray['name'];
        $files_type = $fileDataArray['type'];
        $files_error = $fileDataArray['error'];
        $files_size = $fileDataArray['size'];
        $files_tmpname = $fileDataArray['tmp_name'];

        if(substr(self::$currentPath, strlen(self::$currentPath)-1) == '/')
				self::$currentPath = substr(self::$currentPath, 0,strlen(self::$currentPath)-1);
						
         $destination = self::$currentPath.'/'.$files_name.'';
      	

          $remote = fopen($files_tmpname, 'r');
          $local = fopen($destination, 'w');

          $read_bytes = 0;
          while(!feof($remote)) {
            $buffer = fread($remote, 1024);
            fwrite($local, $buffer);
            $read_bytes += 1024; 
            $progress = $read_bytes .'/'. $files_size;

			if(self::$mode == 'eventsource'){
				echo "event: progress\n";
				echo 'data: {"progress": "' . $progress . '"}';
				echo "\n\n";
			}else if(self::$mode == 'iframeremote'){
				echo  $files_tmpname.'::'.$progress.'::'.($key+1).'/'.count($filesList).'|';
			}
            
            
              ob_flush();
              flush();
          }
          fclose($remote);
          fclose($local);
	}

	/**
	 * ajusta o array dos arquivos recebidos via $_FILE
	 * 	 
	 */
	public static function writeIframe(){
		$fileDataArray = self::$fileHandle;
		
		foreach ($fileDataArray as $key => $value) {

      	if($key == 'name'){  
      		$index = 0;
      		foreach ($value as $key2 => $value2) {
      			$filesList[$index]['name'] = $value2;
      			$index++;
      		}      		      		
      	}

      	if($key == 'size'){  
      		$index = 0;
      		foreach ($value as $key2 => $value2) {
      			$filesList[$index]['size'] = $value2;
      			$index++;
      		}      		      		
      	}

      	if($key == 'tmp_name'){  
      		$index = 0;
      		foreach ($value as $key2 => $value2) {
      			$filesList[$index]['tmp_name'] = $value2;
      			$index++;
      		}      		      		
      	}
      	
      	if($key == 'error'){  
      		$index = 0;
      		foreach ($value as $key2 => $value2) {
      			$filesList[$index]['error'] = $value2;
      			$index++;
      		}      		      		
      	}

      	if($key == 'size'){  
      		$index = 0;
      		foreach ($value as $key2 => $value2) {
      			$filesList[$index]['size'] = $value2;
      			$index++;
      		}      		      		
      	}

      
      }

      self::$fileHandle = $filesList;
      foreach ($filesList as $key => $value) {
	      self::writeDirect($value,$key);
	  }
	}


  /**
   * captura o tamanho do arquivo remoto via CURL
   * @param  [type] $ch [description]
   * @return [type]     tamanho do arquivo
   */
  public static function remotefileSize($url) {$ch = curl_init($url); curl_setopt($ch, CURLOPT_NOBODY, 1); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0); curl_setopt($ch, CURLOPT_HEADER, 0); curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); curl_setopt($ch, CURLOPT_MAXREDIRS, 3); curl_exec($ch); $filesize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD); curl_close($ch); if ($filesize) return $filesize; }

  /**
   * ajusta o array do arquivo antes de escreve-lo localmente
   *    
   */
  public static function writeIframeRemote(){
    $fileDataArray = array(
        'name'=>self::$target,
        'type'=>null,
        'error'=>null,
        'tmp_name'=>self::$fileHandle,
        'size'=>self::remotefileSize(self::$fileHandle),
      );
     
     
    		self::writeDirect($fileDataArray);		
    	
     
  }

  	/**
  	 * define o modo de gravação
  	 * @param  [type] $fileDataArray [description]
  	 * @return [type]                [description]
  	 */
	public static function write($fileDataArray){
   
		if(self::$mode == 'iframe'){
			self::writeIframe();
		}else if(self::$mode == 'iframeremote' || self::$mode == 'eventsource'){      
			self::writeIframeRemote();
		}else{
			self::writeDirect(self::$fileHandle);
		}
		
	}

	/**
	 * captura a lista de arquivos para serem gravados
	 * 	 
	 */
	public static function getFiles(){
	    if(self::$mode == 'iframeremote' || self::$mode == 'eventsource'){     
	      self::$fileHandle = self::$file;
	      return true;
	    }

		foreach ($_FILES as $key => $value) {
	      	$fil = $key;
	      }
	     self::$fileHandle = $_FILES[$fil];
	}

	/**
	 * verifica a raquisição http para gravação dos arquivos
	 * 	 
	 */
	public static function getRequestData(){

		self::$mode = isset($_POST['mode'])?$_POST['mode']:null;
		self::$target = isset($_POST['target'])?$_POST['target']:null;

		self::$mode = isset($_GET['mode'])?$_GET['mode']:self::$mode;
		self::$target = isset($_GET['target'])?$_GET['target']:self::$target;
		self::$file = isset($_GET['file'])?$_GET['file']:null;

		$currentPath = getcwd();

		if(self::$target != null){
			
			if(self::$target == ' ')
				self::$target = '';

			if(self::$mode == 'iframeremote' || self::$mode == 'eventsource'){
				$currentPath = $currentPath.'/';
			}else{
				$currentPath = $currentPath.'/'.self::$target;
			}
		}


		self::$currentPath = $currentPath;
	}
}


Upload::getRequestData();
Upload::getFiles();
Upload::write();
