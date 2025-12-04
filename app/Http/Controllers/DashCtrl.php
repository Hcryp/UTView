<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashCtrl extends Controller {
    public function index() {
        // Data exactly matching the reference requirements
        $data = [
            'manhours' => [
                'satui' => [
                    'label' => 'Satui (17 Apr – 30 Oct 2025)',
                    'value' => '934,368'
                ],
                'batu' => [
                    'label' => 'Batulicin (1 Jan – 30 Oct 2025)',
                    'value' => '327,988'
                ]
            ],
            'accidents' => [
                'years' => ['2021', '2022', '2023', '2024', '2025'],
                'satui' => [1, 1, 1, 0, 0],
                'batu' =>  [2, 1, 4, 1, 1]
            ],
            'mcu' => [
                'fit' => 93,
                'temp_unfit' => 5,
                'unfit_note' => 2
            ],
            'manpower' => [
                'satui' => [
                    'partner' => 353,
                    'ut' => 98,
                    'ojt' => 66
                ],
                'batu' => [
                    'partner' => 115,
                    'ut' => 27,
                    'ojt' => 38
                ],
                'total' => 687 // Display label
            ],
            // Simulated month-by-month values for Energy/Emission to match visual density of reference
            'energy' => [
                'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                'electricity' => [150, 160, 155, 170, 165, 180, 175, 190, 185, 195], // KWh Trend
                'fuel' => [12, 14, 13, 15, 14, 16, 15, 17, 16, 18], // Liters (x1000)
                'water' => [80, 85, 82, 90, 88, 95, 92, 98, 96, 100] // m3
            ]
        ];

        return view('dash.index', compact('data'));
    }
}