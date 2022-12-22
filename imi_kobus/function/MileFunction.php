<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
/* mileage 관련 */
class UserMile {
	public function __construct($CConnection){
		$this->db = $CConnection;
		$this->user_id = $_SESSION['user_id'];
	}

	public function fnSetUserInfo ($nUserNum, $strUserName) {
		if (empty($nUserNum) || empty($strUserName)) {
			return false;
		}

		$this->user_num = $nUserNum;
		$this->user_name = $strUserName;

		return true;
	}

	#보유중인 마일리지 조회 함수
	public function fnGetUserMile($strCode) {

		#변수 체크
		if (empty($this->user_id)) {
			return false;
		}
		# DB에서 장부코드 조회
		$qryCode = "
			SELECT CONCAT(type1,type2,type3,type4) AS code 
			  FROM account_code_info;
		";
		$rstCode = mysqli_query($this->db, $qryCode);
		if ($rstCode == false) {
			return false;
		}

		if (mysqli_num_rows($rstCode) < 1) {
			return false;
		}

		$rgAccountCode = array();
		while($rgCode = mysqli_fetch_assoc($rstCode)){
				$rgAccountCode[] =$rgCode['code'];
		}
		
		$qry = "
			SELECT sum(own_mile) AS own_mile
			  FROM user_mileage
			WHERE user_num = " . $this->user_num . "
		";

		if (!empty($strCode)) {
			if (in_array($strCode, $rgAccountCode) == false) {
				return false;
			}

			# 장부 코드가 들어있으면 코드에 맞는 금액 조회
			$qry = "
				SELECT own_mile
				  FROM user_mileage
				WHERE user_num = " . $this->user_num . "
				  AND account_code = '" . $strCode ."'
			";
		}

		$rst = mysqli_query($this->db, $qry);
		if ($rst == false) {
			return false;
		}
		
		$rg = mysqli_fetch_assoc($rst);
		if ($rg == false) {
			return false;
		}

		$rgMile = $rg['own_mile'];
		
		if ($rgMile == null) {
			$rgMile = 0;
		}
		
		return $rgMile;
	}

	/* 마일리지 증가 감소 클래스 */
	# 마일리지 증가 함수
	public function fnChargeUpdate($bCheck, $nMileCharge, $strAccountCode) {

		if (empty($bCheck) || empty($nMileCharge) || empty($strAccountCode)) {
			return true;
		}
		$qry = self::fnChangeUpdate($bCheck, $nMileCharge, $strAccountCode);
		if ($qry == false) {
			return false;
		}
		
		return true;
	}
	
	# 마일리지 삭감 함수
	public function fnWithdrawUpdate($bCheck,$nMileWithdraw, $strAccountCode) {

		if (empty($bCheck) || empty($nMileChange) || empty($strAccountCode)) {
			return true;
		}

		$qry = self::fnChangeUpdate($bCheck, $nMileWithdraw, $strAccountCode);
		if ($qry == false) {
			return false;
		}
		
		return true;
	}
	
	#차감 변동 쿼리문
	public function fnChangeUpdate($bCheck, $nMileChange, $strAccountCode) {
	
		if (empty($bCheck) || empty($nMileChange) || empty($strAccountCode)) {
			return false;
		}

		$strCheckPM = '+';

		#충전일 때
		if ($bCheck == 'charge') {
			
			#데이터 있으면 update 없으면 insert
			$qryChange ="
				insert into user_mileage SET
					own_mile =		own_mile " . $strCheckPM . " " . $nMileChange. ",
					change_day =	NOW(),
					account_code =	'" . $strAccountCode . "',
					user_num =		" . $this->user_num . "
				 ON DUPLICATE KEY UPDATE
				  own_mile =	own_mile " . $strCheckPM . " " . $nMileChange. ",
				  change_day =  NOW()
			";
			$rstChange = mysqli_query($this->db, $qryChange);
			if ($rstChange == false) {
				return false;
			}
	
			if(mysqli_affected_rows($this->db) < 1){
				return false;
			}
		}
		
		#충전이 아닐 때
		if ($bCheck !== 'charge') {

			$strCode = '';

			#코드가 비어있을 때 총액
			$nAllAmount = self::fnGetUserMile($strCode);
			if ($nAllAmount === false) {
				return false;
			}

			#총 금액이 0보다 작으면 false
			if ($nAllAmount < 0) {
				return false;
			}	

			#장부 총 금액보다 차감 금액이 크면 false
			if ($nAllAmount < $nMileChange) {
				return false;
			}

			# 제일 먼저 적립된 순서대로 조회
			$qryAccCode = "
				SELECT mileage.account_code, mileage.own_mile, accum.reg_day 
				  FROM user_mileage AS mileage 
				 INNER JOIN accum_mile AS accum on mileage.user_num = accum.user_num AND accum.account_code = mileage.account_code
				  WHERE mileage.own_mile > 0
				  AND accum.user_num = " . $this->user_num . "
				  GROUP BY mileage.account_code 
				  ORDER BY accum.reg_day ASC
			";
			$rstAccCode = mysqli_query($this->db, $qryAccCode);
			if ($rstAccCode == false) {
				return false;
			}

			if (mysqli_num_rows($rstAccCode) < 1) {
				return false;
			}
			
			/* 변수 초기화 */
			$strAccCode = array();	#장부 코드명 받는 배열
			$nOwnMile = array();	#장부 코드별 금액 받는 배열

			while($rgAccCode = mysqli_fetch_assoc($rstAccCode)){
				if ($rgAccCode == false) {
					return false;
				}

				$strAccCode[] = $rgAccCode['account_code'];
				$nOwnMile[] = $rgAccCode['own_mile'];
			}


			#장부 코드 배열 개수
			$nAccCount = count($strAccCode);
			
			#첫 금액이 차감 금액보다 크면 첫번째 값
			if($nOwnMile[0] > $nMileChange ){
				$nAccCount = 1;
			}
			$bBreak = '';
						
			#제일 작은 금액부터 차감되도록.. -> 장부코드 개수만큼 for문
			for ($i = 0; $i < $nAccCount; $i++) {

				$qryWithdLock = "
					SELECT own_mile
					  FROM user_mileage
					 WHERE user_num = " . $this->user_num . "
					   AND account_code = '" . $strAccCode[$i] . "'
					  FOR UPDATE
				";
				#차감되는 행 lock
				$rstWithdLock = mysqli_query($this->db, $qryWithdLock);
				
				if ($rstWithdLock == false) {
					return false;
				}

				if (mysqli_num_rows($rstWithdLock) < 1) {
					return false;
				}

	
				#i가 0일 때만 첫 금액에서 원금 차감
				$nCheck = $nOwnMile[0]-$nMileChange;

				#i가 0이 아닐 때 nMileage가 0보다 작으면 *-1
				if ($i > 0) {
					if($nMileage < 0){
						$nMileage *= -1; 
					}

					$nCheck = $nOwnMile[$i]-$nMileage;
				}
			
				#계산한 금액이 0보다 작으면 0입력시키고 다음 장부코드 계산 진행 
				if ($nCheck < 0) {
					$nMileage = 0;
				} else {
					#0보다 크거나 같으면 nCheck 값이 nMileage값
					$nMileage = $nCheck;
				}

				#update 쿼리문
				$qryChange = "
					UPDATE user_mileage SET
						own_mile =		" . $nMileage . ",
						change_day =	NOW(),
						user_num =		" . $this->user_num . "
					  WHERE user_num = " . $this->user_num . "
						AND account_code = '" . $strAccCode[$i] . "'
				";
				$rstChange = mysqli_query($this->db, $qryChange);
				if ($rstChange == false) {
					return false;
				}

				if (mysqli_affected_rows($this->db) < 1) {
					
					return false;
				}

				$nMileage = $nCheck;
				
				#nMileage가 양수이면 break
				if($nMileage > 0 ){
					break;
				}		

			}		
			
		}
		return true;
	}

	/*마일리지 적립내역*/
	public function fnAccumMile($bCheck, $strAccountCode, $nAccumMile, $strTradeCode, $nAllAmount, $strTradeType) {

		if (empty($bCheck) || empty($strAccountCode) || empty($nAccumMile) || empty($strTradeCode)) {
			return false;
		}

		$All = '';
		# user_mileage 업데이트 전 총 금액이 0보다 작으면 false
		if ($nAllAmount < 0) {
			return false;
		}

		$nCheckAmount = self::fnGetUserMile($All);
		if ($nCheckAmount === false) {
			return false;
		}
	

		# 변동 후 마일리지 값이 0보다 작으면 false
		if ($nCheckAmount < 0) {
			return false;
		}
		
		/** charge일 때 accum_mile에 등록하기 전 값 확인 **/
		# 충전일 때 변동 후 값 - 변동 전 값 == 변동 전 값이 아니면 false반환
		$nAmountCheck = $nCheckAmount - $nAllAmount;
		if ($nAmountCheck < 0) {
			$nAmountCheck *= -1;
		}

		if ($nAmountCheck != $nAccumMile) {
			return false;
		}
			
		#충전되는 프로세스이면 입력된 금액 그대로
		$nAfterMile = $nAccumMile;


		if($bCheck == 'charge'){

			#충전이면 시퀀스 생성값 가져오기..
			$qryAccumMile = "
				INSERT INTO accum_mile SET
					user_num = " . $this->user_num . ",
					account_code = '" . $strAccountCode . "',
					accum_mile = " . $nAccumMile . ",
					after_mile = " . $nAccumMile . ",
					reg_day = now()
			";

			$rstAccumMile = mysqli_query($this->db, $qryAccumMile);
			if ($rstAccumMile == false) {
				return false;
			}

			if (mysqli_affected_rows($this->db) < 1) {
				return false;
			}		
			$nAccumAI = mysqli_insert_id($this->db);


			$qryInsertAccumLog = "
				INSERT INTO accum_mile_log SET
					accum_mile_seq = " . $nAccumAI . ",
					user_num = " . $this->user_num . ",
					account_code = '" . $strAccountCode . "',
					trade_code = '" . $strTradeCode . "',
					accum_mile = " . $nAccumMile . ",
					trade_type = '" . $strTradeType . "',
					reg_day = NOW()
			";
			$rstInsertAccumLog = mysqli_query($this->db, $qryInsertAccumLog);
			if ($rstInsertAccumLog == false) {
				return false;
			}

			if (mysqli_affected_rows($this->db) < 1) {
				return false;
			}
		}


		#충전이 아니면 원래 있던 시퀀스 가져오기..
		if ($bCheck !== 'charge') {

			/** charge가 아닐 때 accum_mile에 등록하기 전 값 확인 **/
			# 출금일 때 변동 전 값 - 변동 후 값이 변동금액이 아니면 false반환
			$nAmountCheck = $nAllAmount - $nCheckAmount ;
			if ($nAmountCheck != $nAccumMile) {
				return false;
			}
		
			if ($nAllAmount < $nAccumMile) {
				return false;
			}
				
			#보유중이던 금액 가져오기
			$qrySelectAccumMile = "
				SELECT seq, after_mile, accum_mile
				  FROM accum_mile
				 WHERE user_num =		" . $this->user_num . "
				   AND after_mile > 0
				  ORDER BY reg_day ASC
			";
			$rstSelectAccumMile = mysqli_query($this->db, $qrySelectAccumMile);
			if ($rstSelectAccumMile == false) {
				return false;
			}

			if (mysqli_num_rows($rstSelectAccumMile) < 1) {
				return false;
			}

			$rgAfterM = array();
			$rgAfterS = array();
			$rgAccumM = array();
			while($rgAfterMile = mysqli_fetch_assoc($rstSelectAccumMile)){
				if ($rgAfterMile == false) {
					return false;
				}
				$rgAfterM[] = $rgAfterMile['after_mile'];
				$rgAfterS[] = $rgAfterMile['seq'];
				$rgAccumM[] = $rgAfterMile['accum_mile'];
			}

			$nAfterCount = count($rgAfterM);
			
			if ($rgAfterM[0] > $nAccumMile) {
				$nAfterCount = 1;
			}

			for ($i = 0; $i < $nAfterCount; $i++) {

				if ($i == 0) {
					$nCheck = $rgAfterM[0]-$nAccumMile;
					$nLogMile = $nAccumMile;
				} else {
					#i가 0이 아닐 때 nMileage가 0보다 작으면 *-1
					if($nMileage < 0){
						$nMileage *= -1; 
					}
					$nCheck = $rgAfterM[$i]-$nMileage;			
					$nLogMile = $nMileage;
				}
				
				#계산한 금액이 0보다 작으면 0입력시키고 다음 장부코드 계산 진행 
				if ($nCheck < 0) {
					$nMileage = 0;
					$nLogMile = $rgAfterM[$i];
				} else {
					#0보다 크거나 같으면 nCheck 값이 nMileage값
					$nMileage = $nCheck;
				}

				
				# 변경 전 accum_mile 마일리지 조회
				$qryAccumBeforeAmount = "
					SELECT after_mile
					  FROM accum_mile
					 WHERE seq =	  " . $rgAfterS[$i] . "
					   AND user_num = " . $this->user_num . "
				";
				$rstAccumBeforeAmount = mysqli_query($this->db, $qryAccumBeforeAmount);
				if ($rstAccumBeforeAmount == false) {
					return false;
				}
				
				if (mysqli_num_rows($rstAccumBeforeAmount) < 1) {
					return false;
				}

				$rgAccumBeforeAmount = mysqli_fetch_assoc($rstAccumBeforeAmount);
				if ($rgAccumBeforeAmount === false) {
					return false;
				}

				#업데이트 할 행에 락
				$qryAccumMileLock = "
					SELECT seq 
					  FROM accum_mile
					 WHERE seq =	  " . $rgAfterS[$i] . "
					   AND user_num = " . $this->user_num . "
				";
				$rstAccumMileLock = mysqli_query($this->db, $qryAccumMileLock);
				if ($rstAccumMileLock == false) {
					return false;
				}

				if (mysqli_num_rows($rstAccumMileLock) < 1) {
					return false;
				}

				$qryAccumZero = "
					UPDATE accum_mile SET
						after_mile =	" . $nMileage . ",
						change_day =	now()
					  WHERE seq =		" . $rgAfterS[$i] . "
						AND user_num =	" . $this->user_num . "
				";
				$rstAccumZero = mysqli_query($this->db, $qryAccumZero);
				if ($rstAccumZero == false) {
					return false;
				}

				if (mysqli_affected_rows($this->db) < 1) {
					return false;
				}
				
				# 변경 후 accum_mile 마일리지 조회
				$qryAccumAfterAmount = "
					SELECT after_mile
					  FROM accum_mile
					 WHERE seq =	  " . $rgAfterS[$i] . "
					   AND user_num = " . $this->user_num . "
				";
				$rstAccumAfterAmount = mysqli_query($this->db, $qryAccumAfterAmount);
				if ($rstAccumAfterAmount == false) {
					return false;
				}
				
				if (mysqli_num_rows($rstAccumAfterAmount) < 1) {
					return false;
				}

				$rgAccumAfterAmount = mysqli_fetch_assoc($rstAccumAfterAmount);
				if ($rgAccumAfterAmount === false) {
					return false;
				}

				/** accum_mile_log에 등록하기 전 값 확인 **/
				# accum_mile에서 출금되기 전 시퀀스 마일리지값 - 시퀀스마다 차감될 auum_mile_log 마일리지 값
				$nAccumCheckMile = $rgAccumBeforeAmount['after_mile'] - $nLogMile;

				# 차감되고 나서의 값과 nAccumCheckMile 값이 같지 않으면 false
				if ($rgAccumAfterAmount['after_mile'] != $nAccumCheckMile) {
					return false;
				}
				
				# 위에서 값 체크하고 nLogMile 값 입력
				$qryInsertAccumLog = "
					INSERT INTO accum_mile_log SET
						accum_mile_seq = " . $rgAfterS[$i] . ",
						user_num = " . $this->user_num . ",
						account_code = '" . $strAccountCode . "',
						trade_code = '" . $strTradeCode . "',
						accum_mile = " . $nLogMile . ",
						trade_type = '" . $strTradeType . "',
						reg_day = NOW()
				";
				$rstInsertAccumLog = mysqli_query($this->db, $qryInsertAccumLog);
				if ($rstInsertAccumLog == false) {
					return false;
				}

				if (mysqli_affected_rows($this->db) < 1) {
					return false;
				}

				$nMileage = $nCheck;

				#nMileage가 양수이면 break
				if($nMileage > 0 ){
					break;
				}
			}
		}
		return true;
	}

	/* 마일리지 change내역 */
	public function fnChangeList($bCheck, $nChangeMile, $strAccountCode, $strTradeCode) {
			
		#변수 체크
		if (empty($bCheck) || empty($nChangeMile) || empty($strAccountCode) || empty($strTradeCode)) {
			return false;
		}
		
		# 프로세스에서 충전한 거래번호로 변동된 마일리지 총액 조회
		$qryAccumMileLog = "
			SELECT sum(accum_mile) AS accum_mile
			  FROM accum_mile_log
			 WHERE Trade_code = '" . $strTradeCode . "'
		";
		$rstAccumMileLog = mysqli_query($this->db, $qryAccumMileLog);
		if ($rstAccumMileLog == false) {
			return false;
		}

		if (mysqli_num_rows($rstAccumMileLog) < 1) {
			return false;
		}

		$rgAccumMileLog = mysqli_fetch_assoc($rstAccumMileLog);
		if ($rgAccumMileLog === false) {
			return false;
		}

		/* 금액 일치 체크 */
		# accum_mile_log에서 방금 전에 변동 된 값이 변경값과 같지 않으면 false
		if ($rgAccumMileLog['accum_mile'] % $nChangeMile !== 0) {	
			return false;
		}

		#변동내역에 있던 이전 금액 조회		
		$qryBeforeMile = "
			SELECT after_mile
			  FROM user_mile_change_list
			 WHERE user_num = " . $this->user_num ."
			   ORDER BY seq DESC
		";
		$rstBeforeMile = mysqli_query($this->db, $qryBeforeMile);
		if ($rstBeforeMile == false) {
			return false;
		}
	

		if (mysqli_num_rows($rstBeforeMile) < 1) {
			#조회되는 금액 없으면 보유중이던 금액은 0원 
			$nBeforeMile = 0;
		} else {
			#조회 금액 있으면 이전 금액은 전에 보유중이던 금액
			$rgBeforeMile = mysqli_fetch_assoc($rstBeforeMile);
				if ($rgBeforeMile['after_mile'] === false) {
					return false;
				}

			$nBeforeMile = $rgBeforeMile['after_mile'];
		}

		if ($nBeforeMile === false) {
			return false;
		}
		
		$nAfterMile = $nBeforeMile + $nChangeMile;

		# 완료 후 금액
		if ($bCheck !== 'charge') {
			#출금액이 -값이면 실패
			$nAfterMile = $nBeforeMile - $nChangeMile;
			if($nAfterMile < 0){
				return false;
			}
		}

		#데이터 입력
		$qryChangeList = "
			INSERT INTO user_mile_change_list SET
				user_num =		" . $this->user_num . ",
				account_code =	'" . $strAccountCode . "',
				trade_code =	'" . $strTradeCode . "',
				before_mile =	" . $nBeforeMile . ",
				change_mile =	" . $nChangeMile . ",
				after_mile =	" . $nAfterMile . ",
				chagne_day =	NOW()
		";
		$rstChangeList = mysqli_query($this->db, $qryChangeList);
		if ($rstChangeList == false) {
			return false;
		}

		if (mysqli_affected_rows($this->db) < 1) {
			return false;
		}

		return true;
	}

	# 장부
	public function fnAccountBook($bCheck, $strTradeCode, $strAccountCode, $strPaymentType, $nChangeMile, $nCommission, $strUserId, $strTradeType) {
	
		if (empty($bCheck) || empty($strTradeCode) || empty($strAccountCode) || empty($strPaymentType) || empty($nChangeMile) || is_null($nCommission)) {
			return false;
		}

		if (empty($strUserId)) {
			$strUserId = $this->user_id;
		}

		# user_mile_change_list에서 거래번호로 변동 마일리지 값 조회
		$qryChangeList = "
			select before_mile, change_mile, after_mile
			from user_mile_change_list
			where trade_code = '" . $strTradeCode . "'
		";	
		$rstChangeList = mysqli_query($this->db, $qryChangeList);
		if ($rstChangeList == false) {
			return false;
		}
	
		if (mysqli_num_rows($rstChangeList) < 1) {
			return false;
		}
	
		$rgChangeList = mysqli_fetch_assoc($rstChangeList);
		if ($rgChangeList == false) {
			return false;
		}

		#적립 테이블에서 정보 가져오기..
		$qryAccSelect = "
			SELECT before_mile, change_mile, after_mile
			  FROM user_mile_change_list
			 WHERE user_num = '" . $this->user_num . "'
			  ORDER BY seq DESC LIMIT 1
		";
		$rstAccSelect = mysqli_query($this->db, $qryAccSelect);
		if ($rstAccSelect == false) {
			return false;
		}

		$rgAfterMile = mysqli_fetch_assoc($rstAccSelect);
		if ($rgAfterMile == false) {
			return false;
		}
	
		#금액 입력
		$qryAccountBook = "
			INSERT INTO account_book SET
				trade_code = '" . $strTradeCode . "',
				user_num = " . $this->user_num . ",
				user_id = '" . $strUserId . "',
				user_name = '" . $this->user_name . "',
				account_code = '" . $strAccountCode . "',	
				trade_type = '" . $strTradeType . "',
				payment_type =  '" . $strPaymentType . "',
				before_mile = " . $rgAfterMile['before_mile'] . ",
				change_mile = " . $rgAfterMile['change_mile']. ",
				after_mile = " . $rgAfterMile['after_mile'] . ",
				commission = " . $nCommission . ",
				reg_day = now()
		";
		$rstAccountBook = mysqli_query($this->db, $qryAccountBook);
		if ($rstAccountBook == false) {
			return false;
		}

		if(mysqli_affected_rows($this->db) < 1){
			return false;
		}
	
		return true;
	}
}

?>

