<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;

abstract class Controller
{
    use ApiResponse; // Pasang di sini agar semua controller mewarisinya
}
