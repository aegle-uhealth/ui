<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {
       

    Route::get('/', 'KeyCloak@show');
    Route::get('keycloak/logout', function () {
      Auth::logout();
      $logout_url = "http://snf-699683.vm.okeanos.grnet.gr/auth/realms/AEGLE/protocol/openid-connect/logout?redirect_uri=http://83.212.97.243/aegle/public/index.php/keycloak";
      return redirect($logout_url);
    });    

    Route::get('/analysisresult/getFileDetail','AnalysisResultController@getFileDetail');    
    Route::get('/analysisresult/buildTree','AnalysisResultController@buildTree');
    Route::get('/visualise/buildAddVisualTree','VisualiseController@buildAddVisualTree');
    Route::get('/visualise/getPath','VisualiseController@getPath');

    // Worlflow Routes
    Route::get('/workflows', 'WorkflowController@index');        
    Route::get('/workflow/getWorkflowDetail', 'WorkflowController@getWorkflowDetail');
    Route::post('/addWorkflow','WorkflowController@addWorkflow');
    Route::post('/addTool','WorkflowController@addTool');
    Route::post('/runScript','WorkflowController@runScript');
    Route::post('/workflow/buildModelForm','WorkflowController@buildModelForm');  
    Route::post('/workflow/processAnalytic','WorkflowController@processAnalytic');

    Route::get('/analytictoolbox', 'AnalyticToolboxController@index');

    Route::get('/analytictoolbox/{id}', 'AnalyticToolboxController@getFileDetail');

    Route::get('/case', 'Case@index');

    Route::get('/keycloak', 'KeyCloak@show');

    Route::get('dataset/{id}', 'DatasetController@getFileDetail');

    Route::get('/datasets', 'DatasetController@index');

    Route::get('/case/{status}', 'CaseController@index');

    Route::get('/workbench', 'WorkbenchController@index');

    Route::get('/analysisresult', 'AnalysisResultController@index');

    Route::get('/visualise', 'VisualiseController@index');
});

