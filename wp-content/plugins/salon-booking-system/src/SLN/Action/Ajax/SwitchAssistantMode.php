<?php

class SLN_Action_Ajax_SwitchAssistantMode extends SLN_Action_Ajax_Abstract{
	public function execute()
	{
		$value = $_REQUEST['_assistants_mode'];
		$update = update_user_meta(get_current_user_id(), '_assistants_mode', $value);
		return wp_send_json( ['update'=>$update ,'value' => $value] );
	}
}