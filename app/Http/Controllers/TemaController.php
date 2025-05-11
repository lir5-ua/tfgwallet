<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class TemaController extends Controller{
public function toggle(Request $request)
{
$modoOscuro = session('modo_oscuro', false);
session(['modo_oscuro' => !$modoOscuro]);

return back();
}
}
