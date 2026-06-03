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

    public function tenantManifest($slug)
    {
        $barbershop = Barbershop::where('slug', $slug)->firstOrFail();
        
        $themeColor = $barbershop->primary_color ?: '#eab308';
        if ($themeColor === 'yellow-500') $themeColor = '#eab308';

        $letter = strtoupper(substr($barbershop->name, 0, 2));
        $svgColor = str_replace('#', '%23', $themeColor);
        $svgIcon = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><rect width='512' height='512' fill='%23030712'/><text x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-size='200' font-family='sans-serif' font-weight='bold' fill='{$svgColor}'>{$letter}</text></svg>";

        return response()->json([
            'name' => $barbershop->name,
            'short_name' => $barbershop->name,
            'start_url' => "/b/" . $barbershop->slug,
            'display' => 'standalone',
            'background_color' => '#030712',
            'theme_color' => $themeColor,
            'description' => "Reservas para {$barbershop->name}",
            'icons' => [
                [
                    'src' => $svgIcon,
                    'sizes' => '192x192 512x512',
                    'type' => 'image/svg+xml',
                    'purpose' => 'any maskable'
                ]
            ]
        ]);
    }
}
