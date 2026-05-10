<?php

use App\Http\Controllers\Api\AbsenceRequestController;
use App\Http\Controllers\Api\AbsenceTypeController;
use App\Http\Controllers\Api\ApprovalController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\DayController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\IssueTypeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\TimeLogController;
use App\Http\Controllers\Api\TimeLogIssueController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserShiftController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware('multiauth')->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('checkrole:admin,owner,manager,hr');
    Route::patch('/change-password', [AuthenticatedSessionController::class, 'changePassword']);

    // Empresas
    Route::get('companies', [CompanyController::class, 'index'])->middleware('checkrole:admin,owner');
    Route::post('companies', [CompanyController::class, 'store'])->middleware('checkrole:admin');
    Route::get('companies/{company}', [CompanyController::class, 'show'])->middleware('checkrole:admin,owner');
    Route::put('companies/{company}', [CompanyController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::patch('companies/{company}', [CompanyController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::delete('companies/{company}', [CompanyController::class, 'destroy'])->middleware('checkrole:admin');

    Route::post('companies/{company}/holidays/attach', [CompanyController::class, 'attachHoliday'])->middleware('checkrole:admin,owner');
    Route::post('companies/{company}/holidays/detach', [CompanyController::class, 'detachHoliday'])->middleware('checkrole:admin,owner');

    // Departamentos
    Route::get('departments', [DepartmentController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::post('departments', [DepartmentController::class, 'store'])->middleware('checkrole:admin,owner');
    Route::get('departments/{department}', [DepartmentController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::put('departments/{department}', [DepartmentController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::patch('departments/{department}', [DepartmentController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->middleware('checkrole:admin,owner');

    // Usuarios
    Route::get('users', [UserController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr');
    Route::post('users', [UserController::class, 'store'])->middleware('checkrole:admin,owner,manager,hr');
    Route::get('users/{user}', [UserController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('checkrole:admin,owner,hr');
    Route::patch('users/{user}', [UserController::class, 'update'])->middleware('checkrole:admin,owner,hr');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('checkrole:admin,owner');

    // Roles
    Route::get('roles', [RoleController::class, 'index'])->middleware('checkrole:admin');
    Route::post('roles', [RoleController::class, 'store'])->middleware('checkrole:admin');
    Route::get('roles/{role}', [RoleController::class, 'show'])->middleware('checkrole:admin');
    Route::put('roles/{role}', [RoleController::class, 'update'])->middleware('checkrole:admin');
    Route::patch('roles/{role}', [RoleController::class, 'update'])->middleware('checkrole:admin');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->middleware('checkrole:admin');

    // Fichajes
    Route::get('time-logs', [TimeLogController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::post('time-logs', [TimeLogController::class, 'store'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::get('time-logs/{timeLog}', [TimeLogController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::put('time-logs/{timeLog}', [TimeLogController::class, 'update'])->middleware('checkrole:admin,owner,manager,hr');
    Route::patch('time-logs/{timeLog}', [TimeLogController::class, 'update'])->middleware('checkrole:admin,owner,manager,hr');
    Route::delete('time-logs/{timeLog}', [TimeLogController::class, 'destroy'])->middleware('checkrole:admin,owner');

    // Incidencias de fichaje
    Route::get('time-log-issues', [TimeLogIssueController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::post('time-log-issues', [TimeLogIssueController::class, 'store'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::get('time-log-issues/{timeLogIssue}', [TimeLogIssueController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::put('time-log-issues/{timeLogIssue}', [TimeLogIssueController::class, 'update'])->middleware('checkrole:admin,owner,manager,hr');
    Route::patch('time-log-issues/{timeLogIssue}', [TimeLogIssueController::class, 'update'])->middleware('checkrole:admin,owner,manager,hr');
    Route::delete('time-log-issues/{timeLogIssue}', [TimeLogIssueController::class, 'destroy'])->middleware('checkrole:admin,owner');

    // Tipos de incidencia
    Route::get('issue-types', [IssueTypeController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::post('issue-types', [IssueTypeController::class, 'store'])->middleware('checkrole:admin,owner');
    Route::get('issue-types/{issueType}', [IssueTypeController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::put('issue-types/{issueType}', [IssueTypeController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::patch('issue-types/{issueType}', [IssueTypeController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::delete('issue-types/{issueType}', [IssueTypeController::class, 'destroy'])->middleware('checkrole:admin,owner');

    // Tipos de ausencia
    Route::get('absence-types', [AbsenceTypeController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::post('absence-types', [AbsenceTypeController::class, 'store'])->middleware('checkrole:admin,owner');
    Route::get('absence-types/{absenceType}', [AbsenceTypeController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::put('absence-types/{absenceType}', [AbsenceTypeController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::patch('absence-types/{absenceType}', [AbsenceTypeController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::delete('absence-types/{absenceType}', [AbsenceTypeController::class, 'destroy'])->middleware('checkrole:admin,owner');

    // Solicitudes de ausencia
    Route::get('absence-requests', [AbsenceRequestController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::post('absence-requests', [AbsenceRequestController::class, 'store'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::get('absence-requests/{absenceRequest}', [AbsenceRequestController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::put('absence-requests/{absenceRequest}', [AbsenceRequestController::class, 'update'])->middleware('checkrole:admin,owner,manager,hr');
    Route::patch('absence-requests/{absenceRequest}', [AbsenceRequestController::class, 'update'])->middleware('checkrole:admin,owner,manager,hr');
    Route::delete('absence-requests/{absenceRequest}', [AbsenceRequestController::class, 'destroy'])->middleware('checkrole:admin,owner,manager,hr');

    // Aprobaciones
    Route::get('approvals', [ApprovalController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr');
    Route::post('approvals', [ApprovalController::class, 'store'])->middleware('checkrole:admin,owner,manager,hr');
    Route::get('approvals/{approval}', [ApprovalController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr');
    Route::put('approvals/{approval}', [ApprovalController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::patch('approvals/{approval}', [ApprovalController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::delete('approvals/{approval}', [ApprovalController::class, 'destroy'])->middleware('checkrole:admin,owner');

    // Días de la semana
    Route::get('days', [DayController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::post('days', [DayController::class, 'store'])->middleware('checkrole:admin');
    Route::get('days/{day}', [DayController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::put('days/{day}', [DayController::class, 'update'])->middleware('checkrole:admin');
    Route::patch('days/{day}', [DayController::class, 'update'])->middleware('checkrole:admin');
    Route::delete('days/{day}', [DayController::class, 'destroy'])->middleware('checkrole:admin');

    // Turnos
    Route::get('shifts', [ShiftController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::post('shifts', [ShiftController::class, 'store'])->middleware('checkrole:admin,owner,manager');
    Route::get('shifts/{shift}', [ShiftController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::put('shifts/{shift}', [ShiftController::class, 'update'])->middleware('checkrole:admin,owner,manager');
    Route::patch('shifts/{shift}', [ShiftController::class, 'update'])->middleware('checkrole:admin,owner,manager');
    Route::delete('shifts/{shift}', [ShiftController::class, 'destroy'])->middleware('checkrole:admin,owner,manager');

    // Turnos de usuarios
    Route::get('user-shifts', [UserShiftController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::post('user-shifts', [UserShiftController::class, 'store'])->middleware('checkrole:admin,owner,manager');
    Route::get('user-shifts/{userShift}', [UserShiftController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::put('user-shifts/{userShift}', [UserShiftController::class, 'update'])->middleware('checkrole:admin,owner,manager');
    Route::patch('user-shifts/{userShift}', [UserShiftController::class, 'update'])->middleware('checkrole:admin,owner,manager');
    Route::delete('user-shifts/{userShift}', [UserShiftController::class, 'destroy'])->middleware('checkrole:admin,owner,manager');

    // Festivos
    Route::get('holidays', [HolidayController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::post('holidays', [HolidayController::class, 'store'])->middleware('checkrole:admin,owner');
    Route::get('holidays/{holiday}', [HolidayController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::put('holidays/{holiday}', [HolidayController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::patch('holidays/{holiday}', [HolidayController::class, 'update'])->middleware('checkrole:admin,owner');
    Route::delete('holidays/{holiday}', [HolidayController::class, 'destroy'])->middleware('checkrole:admin,owner');

    // Notificaciones
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::get('notifications', [NotificationController::class, 'index'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::post('notifications', [NotificationController::class, 'store'])->middleware('checkrole:admin,owner,hr');
    Route::get('notifications/{notification}', [NotificationController::class, 'show'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->middleware('checkrole:admin,owner,manager,hr,employee');
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->middleware('checkrole:admin,owner,manager,hr,employee');

});
