<?php

require_once _PATH_lib_.'/dbConnector.c.php';
                                                     
require_once dirname(__FILE__).'/PageVar.c.php';
require_once dirname(__FILE__).'/ListCommon.c.php';  

//파일업로드 필드를 추가해서 자동으로 업로드를 처리한다.
loadLib('upload.c');