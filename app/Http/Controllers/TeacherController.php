<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\ExamScheduleResource;
use App\Http\Resources\FixedScheduleResource;
use App\Services\Contracts\TeacherServiceContract;
use App\Services\Contracts\ScheduleServiceContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TeacherController extends Controller
{
    private TeacherServiceContract $teacherService;

    /**
     * @param TeacherServiceContract $teacherService
     */
    public function __construct (TeacherServiceContract $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function getSchedulesByDate (Request $request, $id_teacher)
    {
        $schedules = $this->teacherService->getSchedulesByDate(auth()->user()->id_user,
                                                               $request->start,
                                                               $request->end);
        return ScheduleResource::collection($schedules)->all();
    }

    public function getExamSchedulesByDate (Request $request, $id_teacher)
    {
        $exam_schedules = $this->teacherService->getExamSchedulesByDate(auth()->user()->id_user,
                                                                        $request->start,
                                                                        $request->end);
        return ExamScheduleResource::collection($exam_schedules)->all();
    }

    public function getFixedSchedulesByStatus (Request $request) : AnonymousResourceCollection
    {
        $fixed_schedules = $this->teacherService->getFixedSchedulesByStatus(auth()->user()->id_user,
                                                                            $request->status);
        return FixedScheduleResource::collection($fixed_schedules);
    }

    public function getModuleClassesByStudySessions (Request $request, $id_teacher)
    {
        return response($this->teacherService->getModuleClassesByStudySessions(auth()->user()->id_user,
                                                                               $request->term,
                                                                               $request->ss));
    }
}
