<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

/* mileage 관련 */
class UserClass {
	public function __construct($CConnection){
		$this->db = $CConnection;
		if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
		}
	}

	#유저 정보
	public function fnUserLogin($strUserId,$strColumn) {
		#변수 체크
		if (empty($strColumn)){
			return false;
		}
	
		/*유저 정보 조회 */
		$qrySelect = "
			SELECT " . $strColumn ." ,status
			  FROM user_info 
			 WHERE user_id = '" . $strUserId . "' 
		";
		$rstSelect = mysqli_query($this->db, $qrySelect);
		if ($rstSelect == false) {
			return false;
		}

		if (mysqli_num_rows($rstSelect) < 1) {
			return false;
		}
		
		$rgSelect = mysqli_fetch_assoc($rstSelect);

		if ($rgSelect == false) {
			return false;
		}
		
		#탈퇴회원 체크 y = 탈퇴회원, n = 일반회원
		if ($rgSelect['status'] == 'y'){
			return false;
		}

		return $rgSelect;
	}
	
	#유저 정보
	public function fnUserInfo($strColumn, $strUserId) {
		#변수 체크
		if (empty($strColumn)){
			return false;
		}
		if (empty($strUserId)) {
			$strUserId = $this->user_id;
		}
	
		/*유저 정보 조회 */
		$qrySelect = "
			SELECT " . $strColumn ." ,status
			  FROM user_info 
			 WHERE user_id = '" . $strUserId . "' 
		";
		$rstSelect = mysqli_query($this->db, $qrySelect);
		if ($rstSelect == false) {
			return false;
		}

		if (mysqli_num_rows($rstSelect) < 1) {
			return false;
		}
		
		$rgSelect = mysqli_fetch_assoc($rstSelect);

		if ($rgSelect == false) {
			return false;
		}
		
		#탈퇴회원 체크 y = 탈퇴회원, n = 일반회원
		if ($rgSelect['status'] == 'y'){
			return false;
		}

		return $rgSelect;
	}
}


?>