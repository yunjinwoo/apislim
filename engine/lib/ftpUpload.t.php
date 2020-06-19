<?php

trait FtpUpload {
	private $server_path = 'datasink/boon';
	protected $ftp_login_connect = null;
	protected $ftp_host = "qownsimg.cafe24.com";// ftp host명 
	protected $ftp_id = "qownsimg";				// ftp 아이디
	protected $ftp_pw = "qownsimg!@";			// ftp 비밀번호 
	protected $ftp_port = "21";					// ftp 포트
	
	function setFtpLoginVar($host, $id, $pw, $port){
		$this->ftp_host = $host;
		$this->ftp_id = $id;
		$this->ftp_pw = $pw;
		$this->ftp_port = $port;
	}
	function setFtpPath($path){
		$this->server_path = $path;
		
		$fc = $this->ftp_login();
		ftp_chdir($fc, $this->server_path);
		
		//현재위치 파일 리스트반환
//		$file_list = ftp_nlist($fc, ".");
//		pre($server_path . '/' . $file_path);
//		pre($file_list);
//		var_dump($file_list);
//		
//		
		//일단제외.. 테스트하기 귀찮다;;;
//		if( !ftp_mkdir($fc, $server_path) ){
//			exitJs('폴더생성실패['.$server_path.']','');
//		}
	}
	function ftp_login(){
		if( $this->ftp_login_connect != null ){
			return $this->ftp_login_connect;
		}
		
		$ftp_host = $this->ftp_host;
		$ftp_id = $this->ftp_id;
		$ftp_pw = $this->ftp_pw;
		$ftp_port = $this->ftp_port;

		/* 파일이 저장될 경로입니다. 예를들면 ftp 계정이 test02이고 계정의 디렉토리 구조가 
		  /home/test02/html/thumb_img 일 경우
		  아래와 같이 경로를 잡습니다. */
		if (!($fc = ftp_connect($ftp_host, $ftp_port)))
			die("$ftp_host : $ftp_port - 연결에 실패하였습니다.");


		if (!ftp_login($fc, $ftp_id, $ftp_pw))
			die("$ftp_id - 로그인에 실패하였습니다.");
		
		$this->ftp_login_connect = $fc;
		return $fc;
	}
	
	// ftp 파일 업로드 함수입니다. - $this->ftp_upload($tmp_file, $filename);
	function ftp_upload($up_file_name, $filename) {
		$fc = $this->ftp_login();
		
		$source_file = $up_file_name;
		$destination_file = $filename;

		if (!ftp_put($fc, $destination_file, $source_file, FTP_BINARY)) {
			echo" <script> window.alert ('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');</script>";
			exit;
		}
		
		return '/'.$this->server_path.'/'.$filename;
	}
	
	function ftp_file_delete($file_path){
		$fc = $this->ftp_login();
		return ftp_delete ( $fc , $file_path );
	}

	//	ftp_quit($fc);
}
