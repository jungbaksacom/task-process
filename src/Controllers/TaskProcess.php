<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use DB;
use Debugbar;

class TaskProcess extends Controller
{
    public function Run(Request $request)
    {
        Debugbar::disable();
        $kind       = $request->input('kind', 'default'); // 고유id
        return self::_Run($kind);
    }
    
    public function Check(Request $request)
    {
        Debugbar::disable();
        $kind       = $request->input('kind', 'default'); // 고유id
        return self::_Check($kind);
    }
    
    public function Complete($kind = "default")
    {
        Debugbar::disable();
        return self::_Update($kind, 'N');
    }
    
    public function LoadJS(Request $request)
    {
        Debugbar::disable();
        header( 'Content-type: application/javascript' );
        $kind       = $request->input('kind', 'default'); // 고유id
        $html = <<<EOD
<!--// 저작권 정박사닷컴 www.jungbaksa.com 2019. 04. 01 ~ //-->
function TaskCheck() {
    progressTimer = setInterval(function () {
		$.get("/Task/Check?kind={$kind}", function(data) {
			if(data=='1') {
                clearInterval(progressTimer);
                _TaskCallBack();
            }
		});
    }, 3000);
}
EOD;
        return $html;
    }
    
    public static function _Run($kind = "default") // 시작시 DB flag 저장
    {
        
    	DB::connection('gcoop_task')->table("task_check")
    	   ->where([
    	       ['sid', session()->getId()],
    	       ['kind', $kind]
    	   ])->delete();
	    DB::connection('gcoop_task')->table("task_check")
    	   ->insert([
    	       'sid' => session()->getId(),
    	       'kind' => $kind,
    	       'flag' => 'Y',
    	       's_date' => date('YmdHis')
    	   ]);
    	return "1";
    }
    
    public static function _Check($kind = "default") // 상태값 검사
    {
        
    	$info = DB::connection('gcoop_task')->table("task_check")
    	   ->where([
    	       ['sid', session()->getId()],
    	       ['kind', $kind]
    	   ])->first();
    	   
	    if(!$info) {
	        return "0";                  // 없음
	    }
	    if($info->flag=='Y') return "2"; // 진행중
	    else                 return "1"; // 완료
    }
    
    public static function _Update($kind = "default", $flag = 'N')
    {
        
	    DB::connection('gcoop_task')->table("task_check")
    	   ->where([
    	       ['sid', session()->getId()],
    	       ['kind', $kind]
    	   ])
	       ->update([
    	       'flag' => $flag,
    	       'e_date' => date('YmdHis')
    	   ]);
    	return "1";
    }
    
}
