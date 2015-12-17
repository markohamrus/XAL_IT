<?php

/* 
this class is used to check the comm-ports that are active and have not been used in Windows computer
author: usman didi khamdani
author's email: usmankhamdani@gmail.com
author's phone: +6287883919293
*/ 

class windows_comm_port {

	function comm_list() {

		$comm = shell_exec('mode');

		if(substr_count($comm,'COM')<1) {
			$comm_list[0] = 'None';
		} else {

			$conn = explode(' ',$comm);
			$count = count($conn);
			for($i=0;$i<$count;$i++) {
				if(substr_count($conn[$i],'COM')<1) {
					$comm_list[$i] = '';
				} else {
					$comm_list[$i] = str_replace(':','',substr($conn[$i],0,5)).'-';
				}
			}

		}

		$comm = implode('',$comm_list);
		$comm = trim($comm);
		$comm = trim(str_replace('-',' ',$comm));
		$comm_list = explode(' ',$comm);

		return $comm_list;
	}

	function baud_list() {

		$baud_list = array(
			'2400',
			'4800',
			'9600',
			'19200',
			'38400',
			'57600',
			'115200',
			'230400',
		);

		return $baud_list;
	}

}

?>