<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\LeaderController;
use App\Http\Controllers\InvestmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ComissionController;


Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);


Route::middleware(['auth'])->group(function () {
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    
    // dashboard routes
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

    // users, role permission Module
    Route::prefix('/users')->middleware('permission:view users')->group(function () {
        Route::get('/',[UserController::class,'index'])->name('users');
        Route::post('/deleteSelected',[UserController::class,'deleteSelected'])->name('user.deleteSelected');
        Route::post('/',[UserController::class,'store'])->name('user-save');
        Route::get('/delete/{id}',[UserController::class,'destroy'])->name('user-delete');
        Route::get('/edit/{id?}', [UserController::class, 'show'])->name('user-edit');
        
        Route::get('/calculate/', [UserController::class, 'calculate'])->name('calculate');
        Route::get('/showchild/', [UserController::class, 'showchild'])->name('showchild');
    });

    // permissions
    Route::prefix('/permissions')->middleware('permission:view permissions')->group(function () {
        Route::get('/',[PermissionController::class,'index'])->name('permissions');
        Route::post('/deleteSelected',[PermissionController::class,'deleteSelected'])->name('permission.deleteSelected')->middleware('permission:delete permission');
        Route::post('/',[PermissionController::class,'store'])->name('permission-save')->middleware('permission:add permission');
        Route::get('/delete/{id}',[PermissionController::class,'destroy'])->name('permission-delete')->middleware('permission:delete permission');
        Route::get('/edit/{id?}', [PermissionController::class, 'show'])->name('permission-edit')->middleware('permission:edit permission');
    });


    // role
    Route::prefix('/roles')->middleware('permission:view roles')->group(function () {
        Route::get('/',[RoleController::class,'index'])->name('roles');
        Route::post('/deleteSelected',[RoleController::class,'deleteSelected'])->name('role.deleteSelected')->middleware('permission:delete role');
        Route::post('/',[RoleController::class,'store'])->name('role-save')->middleware('permission:add role');
        Route::get('/delete/{id}',[RoleController::class,'destroy'])->name('role-delete')->middleware('permission:delete role');
        Route::get('/edit/{id?}', [RoleController::class, 'show'])->name('role-edit')->middleware('permission:edit role');
        Route::post('/permission',[RoleController::class,'permissionUpdate'])->name('update-role-permission')->middleware('permission:assign role-permissions');
        Route::get('/permission/{id}',[RoleController::class,'permissionEdit'])->name('edit-role-permission')->middleware('permission:assign role-permissions');
    });


    // designation
    Route::prefix('/designation')->middleware('permission:view designations')->group(function () {
        Route::get('/',[DesignationController::class,'index'])->name('designation');
        Route::post('/deleteSelected',[DesignationController::class,'deleteSelected'])->name('designation.deleteSelected')->middleware('permission:delete designation');
        Route::post('/',[DesignationController::class,'store'])->name('designation-save')->middleware('permission:add designation');
        Route::get('/delete/{id}',[DesignationController::class,'destroy'])->name('designation-delete')->middleware('permission:delete designation');
        Route::get('/edit/{id?}', [DesignationController::class, 'show'])->name('designation-edit')->middleware('permission:edit designation');
    });

    
    
    // Staff Module
    Route::prefix('/staff')->middleware('permission:view staff')->group(function () {
        Route::get('/',[StaffController::class,'index'])->name('staff');
        Route::post('/deleteSelected',[StaffController::class,'deleteSelected'])->name('staff.deleteSelected')->middleware('permission:delete staff');
        Route::get('/delete/{id}',[StaffController::class,'destroy'])->name('staff-delete')->middleware('permission:delete staff');
        Route::get('/create',[StaffController::class,'create'])->name('add-staff-form')->middleware('permission:add staff');
        Route::post('/store',[StaffController::class,'store'])->name('add-staff')->middleware('permission:add staff');
        Route::get('/edit/{id}',[StaffController::class,'edit'])->name('edit-staff-form')->middleware('permission:edit staff');
        Route::post('/update',[StaffController::class,'update'])->name('update-staff')->middleware('permission:edit staff');
        Route::get('/show/{id}',[StaffController::class,'show'])->name('view-staff');
    });

    Route::prefix('/leader')->middleware('permission:View Leader')->group(function () {
        Route::get('/',[LeaderController::class,'index'])->name('leader');
        Route::post('/deleteSelected',[LeaderController::class,'deleteSelected'])->name('leader.deleteSelected')->middleware('permission:Delete Leader');
        Route::get('/delete/{id}',[LeaderController::class,'destroy'])->name('leader-delete')->middleware('permission:Delete Leader');
        Route::get('/create',[LeaderController::class,'create'])->name('add-leader-form')->middleware('permission:Add Leader');
        Route::post('/store',[LeaderController::class,'store'])->name('add-leader')->middleware('permission:Add Leader');
        Route::get('/edit/{id}',[LeaderController::class,'edit'])->name('edit-leader-form')->middleware('permission:Edit Leader');
        Route::post('/update',[LeaderController::class,'update'])->name('update-leader')->middleware('permission:Edit Leader');
        Route::get('/show/{id}',[LeaderController::class,'show'])->name('view-leader');
    });

    Route::prefix('/investment')->middleware('permission:view investment')->group(function () {
        Route::get('/',[InvestmentController::class,'index'])->name('investment');
        Route::post('/deleteSelected',[InvestmentController::class,'deleteSelected'])->name('investment.deleteSelected')->middleware('permission:delete investment');
        Route::get('/delete/{id}',[InvestmentController::class,'destroy'])->name('investment-delete')->middleware('permission:delete investment');
        Route::get('/create',[InvestmentController::class,'create'])->name('add-investment-form')->middleware('permission:add investment');
        Route::post('/store',[InvestmentController::class,'store'])->name('add-investment')->middleware('permission:add investment');
    
        Route::get('/edit/{id?}', [InvestmentController::class, 'show'])->name('edit-investment-form')->middleware('permission:edit investment');;

        Route::post('/update',[InvestmentController::class,'update'])->name('update-investment')->middleware('permission:edit investment');
        Route::get('/show/{id}',[InvestmentController::class,'show'])->name('view-investment');
    });

    Route::prefix('/comission')->middleware('permission:view comission')->group(function () {
        Route::get('/',[ComissionController::class,'index'])->name('comission');
        Route::get('/userwisetotal',[ComissionController::class,'userwisetotal'])->name('userwisetotal');
        
    });

    // Quiz Module
    Route::prefix('/quiz')->middleware('permission:view quizzes')->group(function () {
        Route::get('/',[QuizController::class,'index'])->name('quizzes');
        Route::post('/deleteSelected',[QuizController::class,'deleteSelected'])->name('quiz.deleteSelected')->middleware('permission:delete quiz');
        Route::get('/delete/{id}',[QuizController::class,'destroy'])->name('quiz-delete')->middleware('permission:delete quiz');
        Route::get('/create',[QuizController::class,'create'])->name('add-quiz-form')->middleware('permission:add quiz');
        Route::post('/store',[QuizController::class,'store'])->name('add-quiz')->middleware('permission:add quiz');
        Route::get('/edit/{id}',[QuizController::class,'edit'])->name('edit-quiz-form')->middleware('permission:edit quiz');
        Route::post('/update',[QuizController::class,'update'])->name('update-quiz')->middleware('permission:edit quiz');
        Route::get('/show/{id}',[QuizController::class,'show'])->name('view-quiz');
    });

});
