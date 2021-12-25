<?php

namespace App\Services;

use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;

class ExamScheduleService implements Contracts\ExamScheduleServiceContract
{
    private ExamScheduleRepositoryContract $examScheduleRepository;
    private TeacherRepositoryContract $teacherRepository;

    /**
     * @param ExamScheduleRepositoryContract $examScheduleRepository
     * @param TeacherRepositoryContract      $teacherRepository
     */
    public function __construct (ExamScheduleRepositoryContract $examScheduleRepository,
                                 TeacherRepositoryContract      $teacherRepository)
    {
        $this->examScheduleRepository = $examScheduleRepository;
        $this->teacherRepository      = $teacherRepository;
    }

    public function getTeacherExamSchedules ($id_teacher) : array
    {
        $exam_schedules = $this->examScheduleRepository->findAllByIdTeacher($id_teacher);
        return $this->_formatResponse1($exam_schedules);
    }

    public function getDepartmentExamSchedules ($id_department, $term, $study_sessions) : array
    {
        $id_teachers    = $this->teacherRepository->findByIdDepartment($id_department);
        $exam_schedules = $this->examScheduleRepository->findAllByIdTeachers($id_teachers);
        return $this->_formatResponse2($exam_schedules);
    }

    private function _formatResponse1 ($exam_schedules) : array
    {
        $response = [];
        foreach ($exam_schedules as $exam_schedule)
        {
            $position          = intval($exam_schedule['position']) - 1;
            $id_module_class   = $exam_schedule['id_module_class'];
            $module_class_name = 'Thi môn ' . $exam_schedule['name'];

            if (!isset($response[$id_module_class]))
            {
                $response[$id_module_class]         = $exam_schedule;
                $response[$id_module_class]['name'] = $module_class_name;
                unset($response[$id_module_class]['teacher_name']);
                unset($response[$id_module_class]['position']);
                unset($response[$id_module_class]['pivot']);
            }
            $response[$id_module_class]['teachers'][$position] = $exam_schedule['teacher_name'];

            ksort($response[$id_module_class]['teachers']);
        }

        return array_values($response);
    }

    private function _formatResponse2 ($data) : array
    {
        $response = [];
        foreach ($data as $teacher)
        {
            foreach ($teacher['exam_schedules'] as $exam_schedule)
            {
                $position          = intval($exam_schedule['pivot']['position']) - 1;
                $id_module_class   = $exam_schedule['id_module_class'];
                $module_class_name = 'Thi môn ' . $exam_schedule['module_class']['name'];

                if (!isset($response[$id_module_class]))
                {
                    $response[$id_module_class]                        = $exam_schedule;
                    $response[$id_module_class]['name']                = $module_class_name;
                    $response[$id_module_class]['teachers'][$position] = $teacher['name'];
                    unset($response[$id_module_class]['pivot']);
                    unset($response[$id_module_class]['module_class']);
                }
                else
                {
                    $response[$id_module_class]['teachers'][$position] = $teacher['name'];
                }

                ksort($response[$id_module_class]['teachers']);
            }
        }

        return array_values($response);
    }

    public function updateExamSchedule ($data)
    {
        $this->examScheduleRepository->update($data);
    }
}