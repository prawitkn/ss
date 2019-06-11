<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::match(['get','post'],'/','AdminController@dashboard');

Auth::routes();


Route::match(['get','post'],'/admin','AdminController@login');

Route::match(['get','post'],'/index','AdminController@index');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function(){
	Route::get('/admin/dashboard','AdminController@dashboard');
	Route::get('/admin/settings','AdminController@settings');
	Route::get('/admin/check-pwd','AdminController@chkPassword');
	Route::match(['get','post'], '/admin/update-pwd','AdminController@updatePassword');
	Route::match(['get','post'], '/admin/index','AdminController@index');

	// Users
	Route::get('/admin/users/view-users','UserController@viewUsers');
	// Route::match(['get','post'], '/admin/users/add-user','UserController@addUser');
	Route::get('/admin/users/add-user', function () {
	    return view('auth.register');
	});
	Route::match(['get','post'], '/admin/users/edit-user/{id}','UserController@editUser');
	Route::match(['get','post'], '/admin/users/delete-user/{id}','UserController@deleteUser');


	//Department
	Route::get('/admin/view-departments','DepartmentController@viewDepartments');
	Route::match(['get','post'], '/admin/add-department','DepartmentController@addDepartment');
	Route::match(['get','post'], '/admin/edit-department/{id}','DepartmentController@editDepartment');
	Route::match(['get','post'], '/admin/delete-department/{id}','DepartmentController@deleteDepartment');
	Route::get('/admin/set-active-department/{id}/{status}','DepartmentController@setActive');

	//Section
	Route::get('/admin/view-sections','SectionController@viewSections');
	Route::match(['get','post'], '/admin/add-section','SectionController@addSection');
	Route::match(['get','post'], '/admin/edit-section/{id}','SectionController@editSection');
	Route::match(['get','post'], '/admin/delete-section/{id}','SectionController@deleteSection');
	Route::get('/admin/set-active-section/{id}/{status}','SectionController@setActive');

	//Position Rank
	Route::get('/admin/view-position_ranks','PositionRankController@viewPositionRanks');
	Route::match(['get','post'], '/admin/add-position_rank','PositionRankController@addPositionRank');
	Route::match(['get','post'], '/admin/edit-position_rank/{id}','PositionRankController@editPositionRank');
	Route::match(['get','post'], '/admin/delete-position_rank/{id}','PositionRankController@deletePositionRank');	
	Route::get('/admin/set-active-position_rank/{id}/{status}','PositionRankController@setActive');
	Route::post('/admin/edit-position_ranks','PositionRankController@editPositionRanks');

	//Position
	Route::get('/admin/view-positions','PositionController@viewPositions');
	Route::match(['get','post'], '/admin/add-position','PositionController@addPosition');
	Route::match(['get','post'], '/admin/edit-position/{id}','PositionController@editPosition');
	Route::match(['get','post'], '/admin/delete-position/{id}','PositionController@deletePosition');

	// Person / employee  
	Route::get('/employees/view-employees','EmployeeController@viewEmployees');
	Route::match(['get','post'], '/employees/add-employee','EmployeeController@addEmployee');
	Route::match(['get','post'], '/employees/edit-employee/{id}','EmployeeController@editEmployee')->name('editEmployee');
	Route::match(['get','post'], '/employees/delete-employee/{id}','EmployeeController@deleteEmployee');
	Route::get('/employees/view-evaluators','EmployeeController@viewEvaluators');
	Route::match(['get','post'], '/employees/list-employees','EmployeeController@listEmployees');
	Route::match(['get','post'], '/employees/list-evaluators','EmployeeController@listEvaluators');
	Route::post('/employees/edit-evaluators','EmployeeController@editEvaluators');
	Route::get('/employees/set-active-employee/{id}/{status}','EmployeeController@setActiveEmployee');
	Route::match(['get','post'], '/employees/filter-list-employee','EmployeeController@listEmployeesByFilter');

	//Category
	Route::match(['get','post'], '/admin/add-category','CategoryController@addCategory');
	Route::match(['get','post'], '/admin/edit-category/{id}','CategoryController@editCategory');
	Route::match(['get','post'], '/admin/delete-category/{id}','CategoryController@deleteCategory');
	Route::get('/admin/view-categories','CategoryController@viewCategories');

	//Person
	Route::match(['get','post'], '/admin/add-person','PersonController@addPerson');

	//Terms
	Route::get('/terms/view-terms','TermController@viewTerms');
	Route::match(['get','post'], '/terms/add-term','TermController@addTerm');
	Route::match(['get','post'], '/terms/edit-term/{id}','TermController@editTerm');
	Route::match(['get','post'], '/terms/delete-term/{id}','TermController@deleteTerm');
	Route::match(['get','post'], '/terms/create-new-header/{id}','TermController@createEvaluateHeader');
	Route::match(['get','post'], '/terms/create-new-data','TermController@createNewEvaluateData');
	Route::match(['get','post'], '/terms/create-copy-data/{id}','TermController@createCopyEvaluateData');
	Route::match(['get','post'], '/terms/set-current/{id}','TermController@setCurrent');
	Route::match(['get','post'], '/terms/close-term/{id}','TermController@closeTerm');


	// Grading Groups
	Route::get('/gradingGroups/view-gradingGroups','GradingGroupController@viewGradingGroups');
	Route::match(['get','post'], '/gradingGroups/add-gradingGroup','GradingGroupController@addGradingGroup');
	Route::match(['get','post'], '/gradingGroups/edit-gradingGroup/{id}','GradingGroupController@editGradingGroup');
	Route::match(['get','post'], '/gradingGroups/delete-gradingGroup/{id}','GradingGroupController@deleteGradingGroup');
	Route::get('/gradingGroups/set-active/{id}/{status}','GradingGroupController@setActive');
	Route::post('/gradingGroups/edit-gradingGroups','GradingGroupController@editGradingGroups');


	// Topic Groups
	Route::get('/topicGroups/view-topicGroups','TopicGroupController@viewTopicGroups');
	Route::match(['get','post'], '/topicGroups/add-topicGroup','TopicGroupController@addTopicGroup');
	Route::match(['get','post'], '/topicGroups/edit-topicGroup/{id}','TopicGroupController@editTopicGroup');
	Route::match(['get','post'], '/topicGroups/delete-topicGroup/{id}','TopicGroupController@deleteTopicGroup');
	Route::get('/topicGroups/set-active-topicGroups/{id}/{status}','TopicGroupController@setActive');

	// Topic
	Route::get('/topics/view-topics','TopicController@viewTopics')->name('viewTopicsSearch');
	Route::match(['get','post'], '/topics/add-topic','TopicController@addTopic');
	Route::match(['get','post'], '/topics/edit-topic/{id}','TopicController@editTopic');
	Route::match(['get','post'], '/topics/delete-topic/{id}','TopicController@deleteTopic');

	// Topic By One
	Route::get('/topics/view-topics-by-one','TopicController@viewTopicsByOne')->name('viewTopicsByOneSearch');
	Route::match(['get','post'], '/topics/add-topic-by-one','TopicController@addTopicByOne');
	Route::match(['get','post'], '/topics/edit-topic-by-one/{id}','TopicController@editTopicByOne');
	Route::match(['get','post'], '/topics/delete-topic-by-one/{id}','TopicController@deleteTopicByOne');

	// Topic Apply
	Route::post('/topics/apply-topics-to-employees','TopicController@applyTopicsToEmployees')->name('applyTopicsToEmployees');
	Route::post('/topics/save-topics-to-employees','TopicController@saveTopicsToEmployees')->name('saveTopicsToEmployees');
	Route::post('/topics/view-topics-to-employees','TopicController@viewTopicsToEmployees')->name('viewTopicsToEmployees');

	// Evaluate 
	Route::get('/evaluates/view-evaluates','EvaluateController@viewEvaluate');
	Route::get('/evaluates/edit-evaluate/{id}/{topic_group_id}/{evaluator_id}','EvaluateController@editEvaluate');
	Route::post('/evaluates/save-evaluate','EvaluateController@saveEvaluate');
	Route::get('/evaluates/view-evaluate/{id}/{topic_group_id}','EvaluateController@viewEvaluate');
	Route::get('/evaluates/view-evaluate/{id}/{topic_group_id}/{evaluator_id}','EvaluateController@viewEvaluate');
	Route::match(['get','post'], '/evaluates/confirm-evaluate/{id}','EvaluateController@confirmEvaluate');	
	Route::match(['get','post'], '/evaluates/reject-evaluate/{id}/{evaluator_id}','EvaluateController@rejectEvaluate');
	
	Route::get('/evaluates/edit-evaluate-timeAttendance/{id}/{topic_group_id}/{evaluator_id}','EvaluateController@editEvaluateTimeAttendance');
	Route::post('/evaluates/save-evaluate-timeAttendance','EvaluateController@saveEvaluateTimeAttendance');

	Route::get('/evaluates/edit-evaluate-comments/{id}/{topic_group_id}/{evaluator_id}','EvaluateController@editEvaluateComments');
	Route::post('/evaluates/save-evaluate-comments','EvaluateController@saveEvaluateComments');

	// Grading
	Route::match(['get','post'], '/grading','GradingController@index');
	Route::match(['get','post'], '/grading/grading/{grading_group_id}','GradingController@grading');

	// Time Attendances  
	Route::get('/timeAttendances/view-timeAttendances','TimeAttendanceController@viewTimeAttendances');

	Route::match(['get','post'], '/timeAttendances/add-leave','TimeAttendanceController@addLeave');
	Route::match(['get','post'], '/timeAttendances/use-leave','TimeAttendanceController@useLeave');

	Route::match(['get','post'], '/timeAttendances/add-absence','TimeAttendanceController@addAbsence');
	Route::match(['get','post'], '/timeAttendances/use-absence','TimeAttendanceController@useAbsence');

	Route::match(['get','post'], '/timeAttendances/add-late','TimeAttendanceController@addLate');
	Route::match(['get','post'], '/timeAttendances/use-late','TimeAttendanceController@useLate');

	Route::match(['get','post'], '/timeAttendances/add-warning','TimeAttendanceController@addWarning');
	Route::match(['get','post'], '/timeAttendances/use-warning','TimeAttendanceController@useWarning');
});

Route::get('/admin', 'AdminController@login');

Route::get('/logout', 'AdminController@logout');





Route::get('/userGroup', 'UserGroupController@index');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
