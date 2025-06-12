<?php

namespace App\Http\Controllers;

use App\Services\User\StaticsService;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    protected StaticsService $staticsService;
    public function __construct( StaticsService $staticsService)
    {

        $this->staticsService = $staticsService;
    }
    public function overview()
    {
        return $this->staticsService->overview();
    }
    public function overviewUsers()
    {
        return
            $this->staticsService->StudentsPerMonth();
    }

    public function overviewProjects()
    {
        return $this->staticsService->ProjectsByType();
    }
}
