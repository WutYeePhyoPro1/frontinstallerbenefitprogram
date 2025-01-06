<?php
namespace App\Http\Controllers;

use App;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App as FacadesApp;

class LocalizationController extends Controller
{
    public function index($locale)
    {
        $locale = $locale == 'en' ? 'mm' : 'en';
        FacadesApp::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}
