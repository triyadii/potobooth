<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotoboothController extends Controller
{
    /**
     * Display the photobooth application interface.
     */
    public function index()
    {
        // Define photobooth themes and configurations to pass to the view if needed
        $themes = [
            [
                'id' => 'retro',
                'name' => 'Retro Vintage',
                'description' => 'Nuansa klasik, hangat, dan bernostalgia',
                'bgColor' => '#f4ebe1',
                'textColor' => '#4a3b32',
                'borderColor' => '#d5c3b1',
                'font' => "'Playfair Display', serif",
            ],
            [
                'id' => 'pastel',
                'name' => 'Cute Pastel',
                'description' => 'Warna-warni lembut yang manis dan menggemaskan',
                'bgColor' => '#ffe5ec',
                'textColor' => '#6c5ce7',
                'borderColor' => '#ffb3c6',
                'font' => "'Fredoka', sans-serif",
            ],
            [
                'id' => 'cyberpunk',
                'name' => 'Cyberpunk Neon',
                'description' => 'Sentuhan futuristik dengan pendaran cahaya neon',
                'bgColor' => '#0d0e15',
                'textColor' => '#00f0ff',
                'borderColor' => '#ff007f',
                'font' => "'Orbitron', sans-serif",
            ],
            [
                'id' => 'classic',
                'name' => 'Elegant Classic',
                'description' => 'Sederhana, berkelas, dengan estetika hitam & putih',
                'bgColor' => '#ffffff',
                'textColor' => '#1a1a1a',
                'borderColor' => '#1a1a1a',
                'font' => "'Cinzel', serif",
            ],
            [
                'id' => 'party',
                'name' => 'Party Sparkle',
                'description' => 'Penuh energi dengan nuansa pesta yang meriah',
                'bgColor' => '#121212',
                'textColor' => '#ffd700',
                'borderColor' => '#ffd700',
                'font' => "'Outfit', sans-serif",
            ]
        ];

        // Retrieve custom themes stored in the Session (Database-less persistence)
        $customThemes = session()->get('custom_themes', []);
        $themes = array_merge($themes, array_values($customThemes));

        $layouts = [
            [
                'id' => '1x1',
                'frames' => 1,
                'name' => 'Single Frame',
                'aspectRatio' => '4:3',
            ],
            [
                'id' => '1x2',
                'frames' => 2,
                'name' => 'Double Frames',
                'aspectRatio' => '4:3',
            ],
            [
                'id' => '1x3',
                'frames' => 3,
                'name' => 'Triple Strip',
                'aspectRatio' => '4:3',
            ],
            [
                'id' => '2x2',
                'frames' => 4,
                'name' => 'Quad Grid',
                'aspectRatio' => '4:3',
            ],
        ];

        return view('photobooth', compact('themes', 'layouts'));
    }

    /**
     * Save a custom theme configuration dynamically to the session.
     */
    public function saveTheme(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:30',
            'bgColor' => 'required|string',
            'borderColor' => 'required|string',
            'textColor' => 'required|string',
            'borderWidth' => 'required|integer|min:4|max:32',
            'fontFamily' => 'required|string',
            'overlayType' => 'required|string',
            'bgImage' => 'nullable|string',
            'imageType' => 'nullable|string',
        ]);

        $id = 'custom_' . uniqid();
        
        $newTheme = [
            'id' => $id,
            'name' => $request->input('name'),
            'description' => 'Tema Kustom yang Disimpan',
            'bgColor' => $request->input('bgColor'),
            'textColor' => $request->input('textColor'),
            'borderColor' => $request->input('borderColor'),
            'font' => $request->input('fontFamily'),
            'borderWidth' => (int) $request->input('borderWidth'),
            'overlayType' => $request->input('overlayType'),
            'bgImage' => $request->input('bgImage'),
            'imageType' => $request->input('imageType', 'overlay'),
            'isCustom' => true
        ];

        $customThemes = session()->get('custom_themes', []);
        $customThemes[$id] = $newTheme;
        session()->put('custom_themes', $customThemes);

        return response()->json([
            'success' => true,
            'message' => 'Tema berhasil disimpan!',
            'theme' => $newTheme
        ]);
    }

    /**
     * Delete a custom theme from the session.
     */
    public function deleteTheme($id)
    {
        $customThemes = session()->get('custom_themes', []);
        
        if (isset($customThemes[$id])) {
            unset($customThemes[$id]);
            session()->put('custom_themes', $customThemes);
            return response()->json(['success' => true, 'message' => 'Tema berhasil dihapus!']);
        }

        return response()->json(['success' => false, 'message' => 'Tema tidak ditemukan.'], 404);
    }
}
