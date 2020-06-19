<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db_table
 *
 * @author jinwoo
 */
class db_table extends SaveCommon {
	function insertDefault(){
		$this->insertCommon($this->db());
	}
	function updateDefault(){
		$this->updateCommon($this->db());
	}
	function deleteDefault(){
		$this->deleteCommon($this->db());
	}
}
