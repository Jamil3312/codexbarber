<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Barbershop;
use Illuminate\Support\Facades\Session;

class PublicSaaSController extends Controller
{
    public function globalLanding()
    {
        return view('superadmin.landing');
    }

    public function tenantLanding($slug)
    {
        $barbershop = Barbershop::where('slug', $slug)->firstOrFail();
        
        // Guardar datos en sesión para persistencia de marca tras logout y registros
        Session::put('tenant_barbershop_id', $barbershop->id);
        Session::put('tenant_slug', $barbershop->slug);
        Session::put('tenant_color', $barbershop->primary_color);
        
        return view('welcome', compact('barbershop'));
    }
}
