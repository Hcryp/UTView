<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manpower;
use Illuminate\Support\Facades\DB;

class DashCtrl extends Controller
{
    public function index()
    {
        $manpowers = Manpower::where('status', 'ACTIVE')->get();

        $satuiData = $manpowers->where('site', 'SATUI');
        $satuiManhours = $satuiData->sum('manhours');

        $satuiCounts = [
            'ut'      => $satuiData->where('category', 'KARYAWAN')->count(),
            'ojt'     => $satuiData->filter(fn($i) => str_contains($i->category, 'MAGANG') || str_contains($i->company, 'UT SCHOOL'))->count(),
            'partner' => $satuiData->filter(fn($i) => str_contains($i->category, 'KONTRAKTOR'))->count(),
        ];

        $batuData = $manpowers->where('site', 'BATULICIN');
        $batuCounts = [
            'ut'      => $batuData->where('category', 'KARYAWAN')->count(),
            'ojt'     => $batuData->filter(fn($i) => str_contains($i->category, 'MAGANG'))->count(),
            'partner' => $batuData->filter(fn($i) => str_contains($i->category, 'KONTRAKTOR'))->count(),
        ];

        $data = [
            'manhours' => [
                'satui' => [
                    'label' => 'Satui (Realtime)',
                    'value' => number_format($satuiManhours)
                ],
                'batu' => [
                    'label' => 'Batulicin (Realtime)',
                    'value' => number_format($batuData->sum('manhours'))
                ]
            ],
            'accidents' => [
                'years' => ['2021', '2022', '2023', '2024', '2025'],
                'satui' => [1, 1, 1, 0, 0],
                'batu'  => [2, 1, 4, 1, 1]
            ],
            'mcu' => [
                'fit' => 93, 'temp_unfit' => 5, 'unfit_note' => 2
            ],
            'manpower' => [
                'satui' => $satuiCounts,
                'batu'  => $batuCounts,
                'total' => $manpowers->count()
            ],
            'energy' => [
                'months'      => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                'electricity' => [150, 160, 155, 170, 165, 180, 175, 190, 185, 195],
                'fuel'        => [12, 14, 13, 15, 14, 16, 15, 17, 16, 18],
                'water'       => [80, 85, 82, 90, 88, 95, 92, 98, 96, 100]
            ]
        ];

        return view('dash.index', compact('data'));
    }
}