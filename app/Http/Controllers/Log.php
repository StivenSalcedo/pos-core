<?php

namespace App\Http\Controllers;

use App\Models\Log as ModelsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as FacadesLog;

class Log extends Controller {

    public static function error($message, ?array $data=[], $line=null){

        self::addUser($data);
        self::addClass($data);
        self::addLine($line, $data);

        ModelsLog::create([
            'level' => ModelsLog::ERROR,
            'data' => $data,
            'message' => $message
        ]);
    }

    private static function addUser(&$array){
        if (Auth::check()) {
            $array['user_id'] = auth()->user()->id;
            $array['names'] = auth()->user()->name;
        }
    }

    private static function addClass(&$array){
        $pila = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 3 );
        $x = array_pop( $pila );
        $array['class'] = $x['class'] . '::' . $x['function'] . '()';
    }

    private static function addLine($line, &$data){
        if ($line) $data['line'] = $line ;
    }

    public static function billError($function, ?array $data=[]){
        self::addUser($data);
        FacadesLog::channel('bill')->debug($function, $data);
    }

}
