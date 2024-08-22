<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerComplaintController;
use App\Http\Controllers\CustomerFeedbackController;
use App\Http\Controllers\SampleRequestController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PriceMonitoringController;

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
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index');
    Route::get('/dashboard','DashboardController@index');

    // change password
    Route::get('my_account','HomeController@myAccount')->name('my_account');
    Route::get('change_password','HomeController@changePassword')->name('change_password');
    Route::post('change_password','HomeController@updatePassword')->name('update_password');

    // Company
    Route::get('/company', 'CompanyController@index')->name('company.index');
    Route::post('/add_company', 'CompanyController@store')->name('store');
    Route::get('/edit_company/{id}', 'CompanyController@edit')->name('edit');
    Route::post('update_company/{id}', 'CompanyController@update')->name('update_company');
    Route::get('delete/{id}', 'CompanyController@delete')->name('delete');
    Route::post('activate_company/{id}', 'CompanyController@activate');
    Route::post('deactivate_company/{id}', 'CompanyController@deactivate');

    // User
    Route::get('/user', 'UserController@index')->name('user.index');
    Route::post('/new_user', 'UserController@store');
    Route::get('/edit_user/{id}', 'UserController@edit');
    Route::post('update_user/{id}', 'UserController@update');
    Route::post('user_change_password/{id}', 'UserController@userChangePassword');
    Route::get('export_user', 'UserController@exportUser');

    // Role
    Route::get('/role', 'RoleController@index')->name('role.index');
    Route::post('/new_role', 'RoleController@store')->name('role.store');
    // Route::get('/edit_role/{id}', 'RoleController@edit')->name('edit_role');
    Route::post('update_role/{id}', 'RoleController@update')->name('update_role');
    Route::post('delete_role', 'RoleController@delete')->name('delete_role');
    Route::get('module_access/{id}', 'RoleController@moduleAccess');
    Route::post('add_module_access', 'RoleController@addModuleAccess');
    Route::get('edit_role/{id}', 'RoleController@editRole');
    Route::post('activate/{id}', 'RoleController@activate');
    Route::post('deactivate/{id}', 'RoleController@deactivate');

    // Department
    Route::get('/department', 'DepartmentController@index')->name('department.index');
    Route::post('/new_department', 'DepartmentController@store')->name('department.store');
    Route::get('/edit_department/{id}', 'DepartmentController@edit')->name('edit_department');
    Route::post('update_department/{id}', 'DepartmentController@update')->name('update_department');
    Route::post('activate_department/{id}', 'DepartmentController@active');
    Route::post('deactivate_department/{id}', 'DepartmentController@deactive');
    Route::get('delete_department/{id}', 'DepartmentController@delete')->name('delete_department');
    Route::get('department_export', 'DepartmentController@exportDepartment');

    # Product
    Route::get('/current_products', 'ProductController@current')->name('product.current');
    Route::get('/new_products', 'ProductController@new')->name('product.new');
    Route::get('view_product/{id}', 'ProductController@view')->name('product.view');
    Route::post('update_raw_materials/{id}', 'ProductController@updateRawMaterials');
    Route::get('/edit_product/{id}', 'ProductController@edit')->name('edit_product');
    Route::post('update_product/{id}', 'ProductController@update')->name('update_product');
    Route::post('delete_product', 'ProductController@delete')->name('delete_product');
    Route::get('products', 'ProductController@salesProduct');

    # Export Products
    Route::get('/export_current_products', 'ProductController@exportCurrentProducts');
    Route::get('/export_archive_products', 'ProductController@exportArchiveProducts');
    Route::get('/export_new_products', 'ProductController@exportNewProducts');
    Route::get('/draft_new_products', 'ProductController@exportDraftProducts');

    # Export clients
    Route::get('/export_current_client', 'ClientController@exportCurrentClient');
    Route::get('/export_prospect_client', 'ClientController@exportProspectClient');
    Route::get('/export_archived_client', 'ClientController@exportArchivedClient');

    # Product Specification
    Route::post('add_specification', 'ProductController@specification');
    Route::post('edit_specification/{id}', 'ProductController@editSpecification');
    Route::post('delete_specification/{id}', 'ProductController@deleteSpecification');
    Route::post('update_all_product_specification', 'ProductController@updateAllProductSpecification');

    # Product Files
    Route::post('add_product_files', 'ProductController@addFiles');
    Route::post('edit_files/{id}', 'ProductController@editFiles');
    Route::post('update_all_files', 'ProductController@updateAllFiles');
    Route::post('delete_product_files/{id}', 'ProductController@deleteProductFiles');

    # Product DS
    Route::post('add_product_ds', 'ProductController@productDs');
    Route::post('edit_product_ds/{id}', 'ProductController@updatePds');
    Route::get('view_details/{id}', 'ProductController@viewPdsDetails');
    Route::post('delete_pds/{id}', 'ProductController@deletePds');

    # Draft Products
    Route::get('/draft_products', 'ProductController@draft')->name('product.draft');
    Route::get('view_draft_product/{id}', 'ProductController@viewDraft');
    Route::post('/add_to_new_products', 'ProductController@addToNewProducts');

    # New Products
    Route::get('view_new_product/{id}', 'ProductController@viewNew');
    Route::post('/new_product', 'ProductController@store')->name('product.store');
    Route::post('/add_to_current_products', 'ProductController@addToCurrentProducts');

    # Archived Products
    Route::get('/archived_products', 'ProductController@archived')->name('product.archived');
    Route::get('view_archive_products/{id}', 'ProductController@viewArchived');
    Route::post('/add_to_draft_products', 'ProductController@addToDraftProducts');
    Route::post('/add_to_archive_products', 'ProductController@addToArchiveProducts');

    // Client
    Route::get('/client', 'ClientController@index')->name('client.index');
    Route::get('/client_prospect', 'ClientController@prospect')->name('client.prospect');
    Route::get('/client_archived', 'ClientController@archived')->name('client.archived');
    Route::get('client/create', 'ClientController@create');    
    Route::post('/new_client', 'ClientController@store')->name('client.store');
    Route::get('edit_client/{id}', 'ClientController@edit')->name('client.edit');
    Route::post('client/update/{id}', 'ClientController@update');
    Route::get('view_client/{id}', 'ClientController@view')->name('client.view');
    Route::get('/regions', 'ClientController@getRegions');
    Route::get('/areas', 'ClientController@getAreas');
    Route::post('delete_client/{id}', 'ClientController@delete');
    Route::post('activate_client/{id}', 'ClientController@activateClient');
    Route::post('prospect_client/{id}', 'ClientController@prospectClient');
    Route::post('archived_client/{id}', 'ClientController@archivedClient');

    # Contact Client
    Route::post('new_contact', 'ContactController@newContact');
    Route::post('/edit_contact/{id}','ContactController@editContact')->name('edit_contact');
    Route::post('delete_contact/{id}', 'ContactController@delete');

    # File Client
    Route::post('add_files', 'ClientController@addFiles');
    Route::put('/edit_file/{id}', 'ClientController@editFile')->name('edit_file');
    Route::post('delete_file/{id}', 'ClientController@deleteFile');
    
    // Account Targeting

    Route::get('/account_targeting', 'AccountTargetingController@index');
    // Customer Requirement
    Route::get('/customer_requirement', 'CustomerRequirementController@index')->name('customer_requirement.index'); 
    Route::post('new_customer_requirement', 'CustomerRequirementController@store')->name('customer_requirement.store'); 
    Route::post('update_customer_requirement/{id}', 'CustomerRequirementController@update');
    Route::post('update_crr/{id}', 'CustomerRequirementController@updateCrr');
    Route::post('/delete_crr/{id}', 'CustomerRequirementController@delete');
    Route::get('view_customer_requirement/{id}', 'CustomerRequirementController@view')->name('viewCrr');
    Route::get('customer_requirement_export', 'CustomerRequirementController@export');
    Route::post('close_remarks/{id}', 'CustomerRequirementController@closeRemarks');
    Route::post('cancel_remarks/{id}', 'CustomerRequirementController@cancelRemarks');
    Route::post('accept_crr/{id}', 'CustomerRequirementController@acceptCrr');
    Route::post('open_status/{id}', 'CustomerRequirementController@openStatus');
    Route::post('rnd_received/{id}', 'CustomerRequirementController@rndReceived');
    Route::post('start_crr/{id}', 'CustomerRequirementController@startCrr');
    Route::post('pause_crr/{id}', 'CustomerRequirementController@pauseCrr');
    Route::post('submit_crr/{id}', 'CustomerRequirementController@submitCrr');
    Route::post('submit_final_crr/{id}', 'CustomerRequirementController@submitFinalCrr');
    Route::post('complete_crr/{id}', 'CustomerRequirementController@completeCrr');
    Route::post('refresh_user_approvers', 'CustomerRequirementController@refreshUserApprover');
    Route::post('return_to_sales/{id}', 'CustomerRequirementController@returnToSales');
    Route::post('return_to_rnd/{id}', 'CustomerRequirementController@returnToRnd');
    Route::post('sales_accepted/{id}', 'CustomerRequirementController@salesAccepted');

    # Crr Supplementary Details
    Route::post('add_supplementary', 'CustomerRequirementController@addSupplementary');
    Route::post('update_supplementary/{id}', 'CustomerRequirementController@updateSupplementary');
    Route::post('delete_supplementary/{id}', 'CustomerRequirementController@deleteSupplementary');

    # Crr Personnel
    Route::post('add_personnel', 'CustomerRequirementController@addPersonnel');
    Route::post('update_personnel/{id}', 'CustomerRequirementController@updatePersonnel');
    Route::post('delete_personnel/{id}', 'CustomerRequirementController@deletePersonnel');

    # Crr File
    Route::post('add_crr_file', 'CustomerRequirementController@addCrrFile');
    Route::post('update_crr_file/{id}', 'CustomerRequirementController@updateCrrFile');
    Route::post('delete_crr_file/{id}', 'CustomerRequirementController@deleteCrrFile');

    // Product Evaluation
    Route::get('/product_evaluation', 'ProductEvaluationController@index')->name('product_evaluation.index');

    // Request Product Evaluation
    Route::get('/request_product_evaluation', 'RequestProductEvaluationController@index')->name('product_evaluation.index');
    Route::post('new_product_evaluation', 'RequestProductEvaluationController@store')->name('product_evaluation.store'); 
    Route::post('product_evaluation/edit/{id}', 'RequestProductEvaluationController@update');
    Route::get('product_evaluation/view/{id}', 'RequestProductEvaluationController@view');
    Route::delete('request_evaluation/{id}', 'RequestProductEvaluationController@destroy');

    Route::post('addRpeSupplementary', 'RequestProductEvaluationController@addSupplementary');
    Route::post('UpdateRpeSupplementary/{id}', 'RequestProductEvaluationController@editSupplementary');
    Route::delete('requestEvaluation/view/supp-delete/{id}', 'RequestProductEvaluationController@deleteRpeDetails');

    Route::post('assignRpePersonnel', 'RequestProductEvaluationController@assignPersonnel');
    Route::post('UpdateAssignedRpePersonnel/{id}', 'RequestProductEvaluationController@editPersonnel');
    Route::delete('requestEvaluation/view/personnel-delete/{id}', 'RequestProductEvaluationController@deleteSrfPersonnel');

    Route::post('rpe_new_activity', 'RequestProductEvaluationController@RpeActivityStore'); 
    Route::post('rpe_edit_activity/{id}', 'RequestProductEvaluationController@RpeActivityUpdate');
    Route::delete('requestEvaluation/view/activity-delete/{id}', 'RequestProductEvaluationController@deleteActivity');

    Route::post('rpeFiles', 'RequestProductEvaluationController@uploadFile');
    Route::post('updateRpeFile/{id}', 'RequestProductEvaluationController@editFile');
    Route::delete('requestEvaluation/view/file-delete/{id}', 'RequestProductEvaluationController@deleteFile');

    Route::post('CancelRpe/{id}', 'RequestProductEvaluationController@CancelRpe');
    Route::post('CloseRpe/{id}', 'RequestProductEvaluationController@CloseRpe');

    // Sample Request 
    Route::get('/sample_request', 'SampleRequestController@index')->name('sample_request.index');
    Route::post('/new_sample_request', 'SampleRequestController@store')->name('sample_request.store');
    Route::get('samplerequest/view/{id}', 'SampleRequestController@view');
    Route::post('sample_request/edit/{id}', 'SampleRequestController@update');

    // Route::get('samplerequest/edit/{id}', 'SampleRequestController@edit');
    Route::post('addSrfSupplementary', 'SampleRequestController@addSupplementary');
    Route::post('UpdateSupplementary/{id}', 'SampleRequestController@editSupplementary');
    Route::delete('samplerequest/view/supp-delete/{id}', 'SampleRequestController@deleteSrfDetails');

    Route::post('assignSrfPersonnel', 'SampleRequestController@assignPersonnel');
    Route::post('UpdateAssignedPersonnel/{id}', 'SampleRequestController@editPersonnel');
    Route::delete('samplerequest/view/personnel-delete/{id}', 'SampleRequestController@deleteSrfPersonnel');
    
    Route::post('srfFiles', 'SampleRequestController@uploadFile');
    Route::post('updateFile/{id}', 'SampleRequestController@editFile');
    Route::delete('samplerequest/view/file-delete/{id}', 'SampleRequestController@deleteFile');

    Route::post('srfRawMaterial', 'SampleRequestController@addRawMaterial');
    Route::post('UpdateRawMaterial/edit/{id}', 'SampleRequestController@editRawMaterial');
    Route::delete('samplerequest/view/material-delete/{id}', 'SampleRequestController@deleteSrfMaterial');

    Route::delete('samplerequest/view/activity-delete/{id}', 'SampleRequestController@deleteSrfActivity');

    Route::post('ApproveSrf/{id}', 'SampleRequestController@approveSrfSales');
    Route::post('ReceiveSrf/{id}', 'SampleRequestController@receiveSrf');
    Route::post('StartSrf/{id}', 'SampleRequestController@StartSrf');
    Route::post('PauseSrf/{id}', 'SampleRequestController@PauseSrf');
    Route::post('RndUpdate/{id}', 'SampleRequestController@RndUpdate');
    
    Route::get('sample_contacts-by-client-f/{clientId}', [SampleRequestController::class, 'getSampleContactsByClientF']);
    Route::get('sample_get-last-increment-f/{year}/{clientCode}', [SampleRequestController::class, 'getSampleLastIncrementF']);
    
    Route::delete('delete-srf-product/{id}', 'SampleRequestController@deleteSrfProduct');

    // Price Monitoring 
    Route::get('/price_monitoring', 'PriceMonitoringController@index')->name('price_monitoring.index');
    Route::get('/client-details/{id}', 'PriceMonitoringController@getClientDetails');
    Route::post('/price_monitoring', 'PriceMonitoringController@store');
    Route::post('price_monitoring/edit/{id}', 'PriceMonitoringController@update');
    Route::delete('delete_price_request/{id}', 'PriceMonitoringController@delete');
    Route::get('price_monitoring/view/{id}', 'PriceMonitoringController@view');

    Route::post('prfFiles', 'PriceMonitoringController@uploadFile');
    Route::post('updatePrfFile/{id}', 'PriceMonitoringController@editFile');
    Route::delete('price_monitorings/view/file-delete/{id}', 'PriceMonitoringController@deleteFile');
    Route::post('updatePrfFile/{id}', 'PriceMonitoringController@editFile');

    // Price Monitoring Local Sales
    Route::get('/price_monitoring_ls', 'PriceMonitoringController@indexLocal')->name('price_monitoring.index');
    Route::get('client-contact/{clientId}', 'PriceMonitoringController@getPrfContacts');
    Route::get('/getGaeCost/{id}', 'PriceMonitoringController@getGaeDetails');
    Route::get('product-rmc/{id}', 'PriceMonitoringController@getProductRmc');
    Route::get('/get-payment-term/{clientId}', 'PriceMonitoringController@getClientDetailsL');
    Route::post('/local_price_monitoring', 'PriceMonitoringController@storeLocalSalePre');
    Route::get('price_monitoring_local/view/{id}', 'PriceMonitoringController@localview');
    Route::delete('delete-product/{id}', 'PriceMonitoringController@deleteProduct');
    Route::post('price_monitoring_local/edit/{id}', 'PriceMonitoringController@LocalSalesUpdate');
    Route::post('ClosePrf/{id}', 'PriceMonitoringController@ClosePrf');
    Route::post('prf_new_activity', 'PriceMonitoringController@PrfActivityStore'); 
    Route::post('prf_edit_activity/{id}', 'PriceMonitoringController@PrfActivityUpdate');
    Route::delete('price_monitorings/view/activity-delete/{id}', 'PriceMonitoringController@deleteActivity');
    

    // Customer Complaint 
    Route::get('/customer_complaint', 'CustomerComplaintController@index')->name('customer_complaint.index');
    Route::post('/new_customer_complaint', 'CustomerComplaintController@store')->name('customer_complaint.store');
    Route::get('customer_complaint/{id}/edit', 'CustomerComplaintController@edit');
    Route::put('customer_complaint/{id}', 'CustomerComplaintController@update');
    Route::get('/customer-complaint/view/{id}', 'CustomerComplaintController@view')->name('customer_complaint.view');
    Route::delete('delete_complaint/{id}', 'CustomerComplaintController@destroy')->name('customer_complaint.destroy');

    Route::get('contacts-by-client/{clientId}', [CustomerComplaintController::class, 'getContactsByClient']);
    Route::get('get-last-increment/{year}/{clientCode}', [CustomerComplaintController::class, 'getLastIncrement']);
    
    // Customer Feedback 
    Route::get('/customer_feedback', 'CustomerFeedbackController@index')->name('customer_feedback.index');
    Route::post('/new_customer_feedback', 'CustomerFeedbackController@store')->name('customer_feedback.store');
    Route::get('customer_feedback/{id}/edit', 'CustomerFeedbackController@edit');
    Route::put('customer_feedback/{id}', 'CustomerFeedbackController@update');
    Route::get('/customer-feedback/view/{id}', 'CustomerFeedbackController@view')->name('customer-feedback.view');
    Route::delete('delete_feedback/{id}', 'CustomerFeedbackController@destroy')->name('customer-feedback.destroy');

    Route::get('contacts-by-client-f/{clientId}', [CustomerFeedbackController::class, 'getContactsByClientF']);
    Route::get('get-last-increment-f/{year}/{clientCode}', [CustomerFeedbackController::class, 'getLastIncrementF']);

    // Categorization
    Route::get('/categorization', 'CategorizationController@index')->name('categorizations.index');    
    Route::post('/new_categorization', 'CategorizationController@store')->name('categorizations.store'); 
    Route::get('/edit_project_name/{id}', 'ProjectNameController@edit')->name('edit_project_name');    
    Route::post('/update_categorization/{id}', 'CategorizationController@update');
    Route::delete('delete_categorization/{id}', 'CategorizationController@delete');

    // Nature of Request
    Route::get('/nature_request', 'NatureRequestController@index')->name('nature_request.index');
    Route::post('/new_nature_request', 'NatureRequestController@store')->name('nature_request.store');
    Route::get('/edit_nature_request/{id}', 'NatureRequestController@edit')->name('edit_nature_request');    
    Route::post('/update_nature_request/{id}', 'NatureRequestController@update')->name('update_nature_request');
    Route::delete('delete_nature_request/{id}', 'NatureRequestController@delete')->name('delete_nature_request');

    // Project Name
    Route::get('/project_name', 'ProjectNameController@index')->name('project_name.index');    
    Route::post('/new_project_name', 'ProjectNameController@store')->name('project_name.store'); 
    Route::get('/edit_project_name/{id}', 'ProjectNameController@edit')->name('edit_project_name');    
    Route::post('/update_project_name/{id}', 'ProjectNameController@update')->name('update_project_name');
    Route::delete('delete_project_name/{id}', 'ProjectNameController@delete')->name('delete_project_name');

    // CRR Priority
    Route::get('/crr_priority', 'CrrPriorityController@index')->name('crr_priority.index');
    Route::post('/new_crr_priority', 'CrrPriorityController@store')->name('crr_priority.store');
    Route::get('/edit_crr_priority/{id}', 'CrrPriorityController@edit')->name('edit_crr_priority');
    Route::post('/update_crr_priority/{id}', 'CrrPriorityController@update')->name('update_crr_priority');
    Route::delete('delete_crr_priority/{id}', 'CrrPriorityController@delete')->name('delete_crr_priority');

    // Issue Category
    Route::get('/issue_category', 'IssueCategoryController@index')->name('issue_category.index');
    Route::post('/new_issue_category', 'IssueCategoryController@store')->name('issue_category.store');    
    Route::get('/edit_issue_category/{id}', 'IssueCategoryController@edit')->name('edit_issue_category');
    Route::post('update_issue_category/{id}', 'IssueCategoryController@update')->name('update_issue_category');
    Route::get('delete_issue_category/{id}', 'IssueCategoryController@delete')->name('delete_issue_category');
    Route::get('/export-issue-category', 'IssueCategoryController@exportIssueCategory')->name('export_issue_category');

    // Concerned Department
    Route::get('/concern_department', 'ConcernDepartmentController@index')->name('concern_department.index');
    Route::post('/new_concern_department', 'ConcernDepartmentController@store')->name('concern_department.store'); 
    Route::get('/edit_concern_department/{id}', 'ConcernDepartmentController@edit')->name('edit_concern_department');     
    Route::post('update_concern_department/{id}', 'ConcernDepartmentController@update')->name('update_concern_department');
    Route::get('delete_concern_department/{id}', 'ConcernDepartmentController@delete')->name('delete_concern_department');
    Route::get('/export-concerned-department', 'ConcernDepartmentController@exportConcernedDepartment')->name('export_concerned_department');

    // Activities
    Route::get('/activities', 'ActivityController@index')->name('activities.index');
    Route::post('/new_activity', 'ActivityController@store')->name('activity.store'); 
    // Route::get('/get-contacts/{clientId}', [ActivityController::class, 'getContacts']); 
    // Route::get('/edit_activity/{id}', 'ActivityController@edit')->name('edit_activity');    
    Route::post('/update_activity/{id}', 'ActivityController@update')->name('update_activity');
    // Route::get('get_contacts/{clientId}', [ActivityController::class, 'getContactsByClient']);
    Route::post('delete_activity/{id}', 'ActivityController@delete');
    Route::get('view_activity/{id}', 'ActivityController@view')->name('activity.view');
    Route::post('close_activity', 'ActivityController@close')->name('delete_activity');
    Route::post('open_activity', 'ActivityController@open')->name('open_activity');
    Route::post('refresh_client_contact', 'ActivityController@refreshClientContact');
    Route::post('edit_client_contact', 'ActivityController@editClientContact');

    // Product Applications
    Route::get('/product_applications', 'ProductApplicationController@index')->name('product_applications.index');
    Route::post('/new_product_applications', 'ProductApplicationController@store')->name('product_applications.store');
    // Route::get('/edit_product_applications/{id}', 'ProductApplicationController@edit')->name('edit_product_applications');
    Route::post('update_product_applications/{id}', 'ProductApplicationController@update')->name('update_product_applications');
    Route::post('delete_product_applications', 'ProductApplicationController@delete')->name('delete_product_applications');

    // Product Subcategories
    Route::get('/product_subcategories', 'ProductSubcategoriesController@index')->name('product_subcategories.index');
    Route::post('/new_product_subcategories', 'ProductSubcategoriesController@store')->name('product_subcategories.store');
    Route::get('/edit_product_subcategories/{id}', 'ProductSubcategoriesController@edit')->name('edit_product_subcategories');
    Route::post('update_product_subcategories/{id}', 'ProductSubcategoriesController@update')->name('update_product_subcategories');
    Route::post('delete_product_subcategories', 'ProductSubcategoriesController@delete')->name('delete_product_subcategories');

    // Raw Material
    Route::get('/raw_material', 'RawMaterialController@index')->name('raw_material.index');
    Route::post('/add_raw_material', 'RawMaterialController@add');
    Route::post('/deactivate_raw_material', 'RawMaterialController@deactivate');
    Route::post('/activate_raw_material', 'RawMaterialController@activate');
    Route::get('/view_raw_materials/{id}', 'RawMaterialController@viewRawMaterials');

    // Base Price
    Route::get('/base_price', 'BasePriceController@index')->name('base_price.index');
    Route::get('/new_base_price', 'BasePriceController@newBasePriceIndex')->name('base_price.index');
    Route::post('/newBasePrice', 'BasePriceController@store');
    Route::post('/editAllNewBasePrice', 'BasePriceController@updateBasePrices');
    Route::post('editNewBase/{id}', 'BasePriceController@updateBasePrice');
    Route::post('approveNewBasePrice/{id}', 'BasePriceController@editApproved');
    Route::delete('base-price/{id}', 'BasePriceController@destroy');

    // Price Request Fixed Cost
    Route::get('/fixed_cost', 'PriceFixedCostController@index')->name('fixed_cost.index');
    Route::get('/edit_fixed_cost', 'PriceFixedCostController@edit');
    Route::post('/new_fixed_cost', 'PriceFixedCostController@store')->name('fixed_cost.store');
    Route::post('update_fixed_cost/{id}', 'PriceFixedCostController@update')->name('update_fixed_cost');
    Route::post('delete_fixed_cost/{id}', 'PriceFixedCostController@delete')->name('delete_fixed_cost');

    // Region
    Route::get('/region', 'RegionController@index')->name('region.index');
    Route::post('/new_region', 'RegionController@store')->name('region.store');
    Route::get('/edit_region/{id}', 'RegionController@edit')->name('edit_region');
    Route::post('update_region/{id}', 'RegionController@update')->name('update_region');
    Route::post('delete_region/{id}', 'RegionController@delete')->name('delete_region');

    // Country
    Route::get('/country', 'CountryController@index')->name('country.index');
    Route::post('/new_country', 'CountryController@store')->name('country.store');
    Route::get('/edit_country/{id}', 'CountryController@edit')->name('edit_country');
    Route::post('update_country/{id}', 'CountryController@update')->name('update_country');
    Route::post('delete_country/{id}', 'CountryController@delete')->name('delete_country');

    // Area
    Route::get('/area', 'AreaController@index')->name('area.index');
    Route::post('/new_area', 'AreaController@store')->name('area.store');       
    Route::get('/edit_area/{id}', 'AreaController@edit')->name('edit_area');
    Route::post('update_area/{id}', 'AreaController@update')->name('update_area');
    Route::post('delete_area/{id}', 'AreaController@delete')->name('delete_area');

    // Business Type
    Route::get('/business_type', 'BusinessTypeController@index')->name('business_type.index');
    Route::post('/new_business_type', 'BusinessTypeController@store')->name('business_type.store');
    Route::get('/edit_business_type/{id}', 'BusinessTypeController@edit')->name('edit_business_type');
    Route::post('/update_business_type/{id}', 'BusinessTypeController@update')->name('update_business_type');
    Route::get('delete_business_type/{id}', 'BusinessTypeController@delete')->name('delete_business_type');

    // Industry
    Route::get('/industry', 'IndustryController@index')->name('industry.index');
    Route::post('/new_industry', 'IndustryController@store')->name('industry.store');
    Route::get('/edit_industry/{id}', 'IndustryController@edit')->name('edit_industry');
    Route::post('/update_industry/{id}', 'IndustryController@update')->name('update_industry');
    Route::get('delete_industry/{id}', 'IndustryController@delete')->name('delete_industry');

    // Price Currencies
    Route::get('/price_currency', 'PriceCurrencyController@index')->name('price_currency.index');
    Route::post('/new_price_currency', 'PriceCurrencyController@store')->name('price_currency.store');
    Route::get('/edit_price_currency/{id}', 'PriceCurrencyController@edit')->name('edit_price_currency');
    Route::post('/update_price_currency/{id}', 'PriceCurrencyController@update')->name('update_price_currency');
    Route::delete('delete_price_currency/{id}', 'PriceCurrencyController@delete')->name('delete_price_currency');

    // Export Price Currency 
    Route::get('/export_price_currencies', 'PriceCurrencyController@exportPriceCurrency');

    // Currency Exchange
    Route::get('/currency_exchange', 'CurrencyExchangeController@index')->name('currency_exchange.index');
    Route::get('edit_currency_exchange', 'CurrencyExchangeController@edit');
    Route::post('/new_currency_exchange', 'CurrencyExchangeController@store')->name('currency_exchange.store');
    Route::post('/update_currency_exchange/{id}', 'CurrencyExchangeController@update')->name('update_currency_exchange');
    Route::post('delete_currency_exchange/{id}', 'CurrencyExchangeController@delete')->name('delete_currency_exchange');

    // Payment Terms
    Route::get('/payment_terms', 'PaymentTermsController@index')->name('payment_terms.index');
    Route::post('/new_payment_terms', 'PaymentTermsController@store')->name('payment_terms.store');
    Route::get('/edit_payment_terms/{id}', 'PaymentTermsController@edit')->name('edit_payment_terms');
    Route::post('/update_payment_terms/{id}', 'PaymentTermsController@update')->name('update_payment_terms');
    Route::get('delete_payment_terms/{id}', 'PaymentTermsController@delete')->name('delete_payment_terms');

    // Request GAE
    Route::get('/request_gae', 'RequestGAEController@index')->name('request_gae.index');
    Route::get('/edit_request_gae', 'RequestGAEController@edit');
    Route::post('/new_request_gae', 'RequestGAEController@store')->name('request_gae.store');
    Route::post('/update_request_gae/{id}', 'RequestGAEController@update')->name('update_request_gae');
    Route::post('delete_request_gae/{Id}', 'RequestGAEController@delete')->name('delete_request_gae');

    # Reports
    Route::get('/price_request', 'ReportsController@price_summary')->name('reports.price_request');
    Route::get('/export-price-request', 'ReportsController@exportPriceRequest')->name('export_price_request');
    Route::get('/transaction_activity', 'ReportsController@transaction_summary')->name('reports.transaction_activity');
});

