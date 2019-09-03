<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = User::model()->findByAttributes(array('loginname' => $this->username));
		if($user == null){
			return false;
		}else if( $user->password != md5($this->password) || $user->is_deleted == 1){
			return false;
		}else{
			$this->setState('userid', $user->id);
			$this->setState('loginname', $user->loginname);
			$this->setState('nickname', $user->nickname);
			$this->setState('unid', $user->unid);
			$this->setState('invit_code',$user->invit_code);
			$user->last_login_at = time();
			$user->last_login_ip = Yii::app()->request->userHostAddress;
			$this->errorCode = 0;
			return true;
		}
	}
}
