<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function toggle(Request $request)
    {
        $theme = $request->input('theme', 'light');

        // Validate theme
        if (!in_array($theme, ['light', 'dark'])) {
            $theme = 'light';
        }

        // Store in session
        session(['theme' => $theme]);

        return response()->json(['success' => true, 'theme' => $theme]);
    }
}
