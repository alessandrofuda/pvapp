<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class DashboardController extends Controller
{
    public function dashboard() : RedirectResponse
    {
        try{
            if(Auth::check()){
                if(request()->user()->hasRole('admin')){
                    return redirect()->route('admin_dashboard');

                }elseif(request()->user()->hasRole('operator')){
                    return redirect()->route('operator_dashboard');

                }elseif (request()->user()->hasRole('user')) {
                    return redirect()->route('user_dashboard');

                }else{
                    return redirect()->route('user_dashboard');
                }
            }


        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
    }
}
