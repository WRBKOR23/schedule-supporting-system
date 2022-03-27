<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\ModuleClassResource;
use App\Http\Resources\ExamScheduleResource;
use App\Http\Resources\FixedScheduleResource;
use App\Services\Contracts\TeacherServiceContract;
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

    public function get ($id_teacher) : TeacherResource
    {
        return new TeacherResource($this->teacherService->getById($id_teacher));
    }

    public function getSchedules (Request $request, $id_teacher) : AnonymousResourceCollection
    {
        Gate::authorize('get-teacher-schedule');
        $schedules = $this->teacherService->getSchedules($id_teacher, $request->all());
        return ScheduleResource::collection($schedules);
    }

    public function getExamSchedules (Request $request, $id_teacher) : AnonymousResourceCollection
    {
        Gate::authorize('get-teacher-exam-schedule');
        $exam_schedules = $this->teacherService->getExamSchedules(auth()->user()->accountable_id,
                                                                  $request->all());
        return ExamScheduleResource::collection($exam_schedules);
    }

    public function getFixedSchedules (Request $request) : AnonymousResourceCollection
    {
        Gate::authorize('get-teacher-fixed-schedule');
        $fixed_schedules = $this->teacherService->getFixedSchedules(auth()->user()->accountable_id,
                                                                    $request->all());
        return FixedScheduleResource::collection($fixed_schedules);
    }

    public function getModuleClassesByStudySessions (Request $request,
                                                             $id_teacher) : AnonymousResourceCollection
    {
        $response = $this->teacherService->getModuleClasses(auth()->user()->accountable_id,
                                                            $request->all());
        return ModuleClassResource::collection($response);
    }
}
