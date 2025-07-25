<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
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
    public function StudentsPerMonth()
    {
        return
            $this->staticsService->StudentsPerMonth();
    }

    public function overviewProjects()
    {
        return $this->staticsService->ProjectsByType();
    }

    public function StudentsLastWeek()
    {
        return $this->staticsService->StudentsLastWeek();
    }

    public function overviewBudget(){
        $data = $this->staticsService->overviewBudget();

        return $data;
    }
}
