<?php

namespace App\Http\Controllers;

use App\Models\Class_model;
use App\Models\Materi;
use App\Models\quiz;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $total = [
        'total_kelas' => 0,
        'total_materi' => 0,
        'total_quiz' => 0,
        'total_user' => 0
    ];

    public function getTotalData()
    {
        $this->total = [
            'total_kelas' => Class_model::count(),
            'total_materi' => Materi::count(),
            'total_quiz' => quiz::count(),
            'total_user' => User::count()
        ];

        return response()->json([
            'data' => $this->total
        ]);
    }
}
