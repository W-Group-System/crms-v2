<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerComplaintController;
use App\Http\Controllers\CustomerFeedbackController;
use App\Http\Controllers\SampleRequestController;
use App\Http\Controllers\ActivityController;

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
    Route::get('/home','HomeController@index');

    // change password
    Route::get('my_account','HomeController@myAccount')->name('my_account');
    Route::get('change_password','HomeController@changePassword')->name('change_password');
    Route::post('change_password','HomeController@updatePassword')->name('update_password');

    // Company
    Route::get('/company', 'CompanyController@index')->name('company.index');
    Route::post('/sample_form', 'CompanyController@store')->name('store');
    Route::get('/edit_company/{id}', 'CompanyController@edit')->name('edit');
    Route::post('update_company/{id}', 'CompanyController@update')->name('update_company');
    Route::get('delete/{id}', 'CompanyController@delete')->name('delete');

    // User
    Route::get('/user', 'UserController@index')->name('user.index');
    Route::post('/new_user', 'UserController@store')->name('user.store');
    Route::get('/edit_user/{id}', 'UserController@edit')->name('edit_user');
    Route::post('update_user/{id}', 'UserController@update')->name('update_user');

    // Role
    Route::get('/role', 'RoleController@index')->name('role.index');
    Route::post('/new_role', 'RoleController@store')->name('role.store');
    Route::get('/edit_role/{id}', 'RoleController@edit')->name('edit_role');
    Route::post('update_role/{id}', 'RoleController@update')->name('update_role');
    Route::get('delete_role/{id}', 'RoleController@delete')->name('delete_role');

    // Department
    Route::get('/department', 'DepartmentController@index')->name('department.index');
    Route::post('/new_department', 'DepartmentController@store')->name('department.store');
    Route::get('/edit_department/{id}', 'DepartmentController@edit')->name('edit_department');
    Route::post('update_department/{id}', 'DepartmentController@update')->name('update_department');
    Route::get('delete_department/{id}', 'DepartmentController@delete')->name('delete_department');

    # Product
    Route::get('/current_products', 'ProductController@current')->name('product.current');
    Route::get('/new_products', 'ProductController@new')->name('product.new');
    Route::get('view_product/{id}', 'ProductController@view')->name('product.view');
    Route::post('update_raw_materials/{id}', 'ProductController@updateRawMaterials');
    Route::get('/edit_product/{id}', 'ProductController@edit')->name('edit_product');
    Route::post('update_product/{id}', 'ProductController@update')->name('update_product');
    Route::post('delete_product', 'ProductController@delete')->name('delete_product');

    # Product Specification
    Route::post('add_specification', 'ProductController@specification');
    Route::post('edit_specification/{id}', 'ProductController@editSpecification');
    # Product Files
    Route::post('add_files', 'ProductController@addFiles');
    Route::post('edit_files/{id}', 'ProductController@editFiles');

    # Draft Products
    Route::get('/draft_products', 'ProductController@draft')->name('product.draft');
    Route::post('/add_to_new_products', 'ProductController@addToNewProducts');

    # Current Products
    Route::post('/new_product', 'ProductController@store')->name('product.store');
    Route::post('/add_to_current_products', 'ProductController@addToCurrentProducts');

    # Archived Products
    Route::get('/archived_products', 'ProductController@archived')->name('product.archived');
    Route::post('/add_to_draft_products', 'ProductController@addToDraftProducts');
    Route::post('/add_to_archive_products', 'ProductController@addToArchiveProducts');

    

    // Client
    Route::get('/client', 'ClientController@index')->name('client.index');
    Route::get('/client_prospect', 'ClientController@prospect')->name('client.prospect');
    Route::get('/client_archived', 'ClientController@archived')->name('client.archived');
    Route::get('client/create', 'ClientController@create');    
    Route::post('/new_client', 'ClientController@store')->name('client.store');
    Route::get('edit_client/{id}', 'ClientController@edit')->name('client.edit');
    Route::post('update_client/{id}', 'ClientController@update')->name('update_client');
    Route::get('view_client/{id}', 'ClientController@view')->name('client.view');
    Route::get('/regions', 'ClientController@getRegions');
    Route::get('/areas', 'ClientController@getAreas');

    // Customer Requirement
    Route::get('/customer_requirement', 'CustomerRequirementController@index')->name('customer_requirement.index'); 
    Route::post('/new_customer_requirement', 'CustomerRequirementController@store')->name('customer_requirement.store'); 

    // Product Evaluation
    Route::get('/product_evaluation', 'ProductEvaluationController@index')->name('product_evaluation.index');

    // Sample Request 
    Route::get('/sample_request', 'SampleRequestController@index')->name('sample_request.index');
    Route::post('/new_sample_request', 'SampleRequestController@store')->name('sample_request.store');
    Route::get('samplerequest/view/{id}', 'SampleRequestController@view');
    Route::post('sample_request/edit/{id}', 'SampleRequestController@update');

    // Route::get('samplerequest/edit/{id}', 'SampleRequestController@edit');
    Route::post('addSrfSupplementary', 'SampleRequestController@addSupplementary');



    Route::get('sample_contacts-by-client-f/{clientId}', [SampleRequestController::class, 'getSampleContactsByClientF']);
    Route::get('sample_get-last-increment-f/{year}/{clientCode}', [SampleRequestController::class, 'getSampleLastIncrementF']);
    
    // Price Monitoring 
    Route::get('/price_monitoring', 'PriceMonitoringController@index')->name('price_monitoring.index');

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

    // Nature of Request
    Route::get('/nature_request', 'NatureRequestController@index')->name('nature_request.index');
    Route::post('/new_nature_request', 'NatureRequestController@store')->name('nature_request.store');
    Route::get('/edit_nature_request/{id}', 'NatureRequestController@edit')->name('edit_nature_request');    
    Route::post('/update_nature_request/{id}', 'NatureRequestController@update')->name('update_nature_request');
    Route::get('delete_nature_request/{id}', 'NatureRequestController@delete')->name('delete_nature_request');

    // Project Name
    Route::get('/project_name', 'ProjectNameController@index')->name('project_name.index');    
    Route::post('/new_project_name', 'ProjectNameController@store')->name('project_name.store'); 
    Route::get('/edit_project_name/{id}', 'ProjectNameController@edit')->name('edit_project_name');    
    Route::post('/update_project_name/{id}', 'ProjectNameController@update')->name('update_project_name');
    Route::get('delete_project_name/{id}', 'ProjectNameController@delete')->name('delete_project_name');

    // CRR Priority
    Route::get('/crr_priority', 'CrrPriorityController@index')->name('crr_priority.index');
    Route::post('/new_crr_priority', 'CrrPriorityController@store')->name('crr_priority.store');
    Route::get('/edit_crr_priority/{id}', 'CrrPriorityController@edit')->name('edit_crr_priority');
    Route::post('/update_crr_priority/{id}', 'CrrPriorityController@update')->name('update_crr_priority');
    Route::get('delete_crr_priority/{id}', 'CrrPriorityController@delete')->name('delete_crr_priority');

    // Issue Category
    Route::get('/issue_category', 'IssueCategoryController@index')->name('issue_category.index');
    Route::post('/new_issue_category', 'IssueCategoryController@store')->name('issue_category.store');    
    Route::get('/edit_issue_category/{id}', 'IssueCategoryController@edit')->name('edit_issue_category');
    Route::post('update_issue_category/{id}', 'IssueCategoryController@update')->name('update_issue_category');
    Route::get('delete_issue_category/{id}', 'IssueCategoryController@delete')->name('delete_issue_category');

    // Concerned Department
    Route::get('/concern_department', 'ConcernDepartmentController@index')->name('concern_department.index');
    Route::post('/new_concern_department', 'ConcernDepartmentController@store')->name('concern_department.store'); 
    Route::get('/edit_concern_department/{id}', 'ConcernDepartmentController@edit')->name('edit_concern_department');     
    Route::post('update_concern_department/{id}', 'ConcernDepartmentController@update')->name('update_concern_department');
    Route::get('delete_concern_department/{id}', 'ConcernDepartmentController@delete')->name('delete_concern_department');

    // Activities
    Route::get('/activities', 'ActivityController@index')->name('activities.index');
    Route::post('/new_activity', 'ActivityController@store')->name('activity.store'); 
    Route::get('/get-contacts/{clientId}', [ActivityController::class, 'getContacts']); 
    Route::get('/edit_activity/{id}', 'ActivityController@edit')->name('edit_activity');    
    Route::post('/update_activity/{id}', 'ActivityController@update')->name('update_activity');
    Route::get('get_contacts/{clientId}', [ActivityController::class, 'getContactsByClient']);
    Route::get('view_activity/{id}', 'ActivityController@view')->name('activity.view');
    Route::delete('delete_activity/{id}', 'ActivityController@close')->name('delete_activity');
    Route::post('open_activity/{id}', 'ActivityController@open')->name('open_activity');

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
    // Route::get('/get_raw_materials_products', 'RawMaterialController@getRawMaterialsProducts');

    // Base Price
    Route::get('/base_price', 'BasePriceController@index')->name('base_price.index');
    Route::get('/new_base_price', 'BasePriceController@newBasePriceIndex')->name('base_price.index');
    Route::post('/newBasePrice', 'BasePriceController@store');
    Route::post('/editAllNewBasePrice', 'BasePriceController@updateBasePrices');
    Route::post('editNewBase/{id}', 'BasePriceController@updateBasePrice');
    Route::post('approveNewBasePrice/{id}', 'BasePriceController@editApproved');

    // Price Request Fixed Cost
    Route::get('/fixed_cost', 'PriceFixedCostController@index')->name('fixed_cost.index');
    Route::post('/new_fixed_cost', 'PriceFixedCostController@store')->name('fixed_cost.store');
    Route::get('/edit_fixed_cost/{id}', 'PriceFixedCostController@edit')->name('edit_fixed_cost');    
    Route::post('update_fixed_cost/{id}', 'PriceFixedCostController@update')->name('update_fixed_cost');
    Route::get('delete_fixed_cost/{id}', 'PriceFixedCostController@delete')->name('delete_fixed_cost');

    // Region
    Route::get('/region', 'RegionController@index')->name('region.index');
    Route::post('/new_region', 'RegionController@store')->name('region.store');
    Route::get('/edit_region/{id}', 'RegionController@edit')->name('edit_region');
    Route::post('update_region/{id}', 'RegionController@update')->name('update_region');
    Route::get('delete_region/{id}', 'RegionController@delete')->name('delete_region');

    // Country
    Route::get('/country', 'CountryController@index')->name('country.index');
    Route::post('/new_country', 'CountryController@store')->name('country.store');
    Route::get('/edit_country/{id}', 'CountryController@edit')->name('edit_country');
    Route::post('update_country/{id}', 'CountryController@update')->name('update_country');
    Route::get('delete_country/{id}', 'CountryController@delete')->name('delete_country');

    // Area
    Route::get('/area', 'AreaController@index')->name('area.index');
    Route::post('/new_area', 'AreaController@store')->name('area.store');       
    Route::get('/edit_area/{id}', 'AreaController@edit')->name('edit_area');
    Route::post('update_area/{id}', 'AreaController@update')->name('update_area');
    Route::get('delete_area/{id}', 'AreaController@delete')->name('delete_area');

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
    Route::get('delete_price_currency/{id}', 'PriceCurrencyController@delete')->name('delete_price_currency');

    // Currency Exchange
    Route::get('/currency_exchange', 'CurrencyExchangeController@index')->name('currency_exchange.index');
    Route::post('/new_currency_exchange', 'CurrencyExchangeController@store')->name('currency_exchange.store');
    Route::get('/edit_currency_exchange/{id}', 'CurrencyExchangeController@edit')->name('edit_currency_exchange');
    Route::post('/update_currency_exchange/{id}', 'CurrencyExchangeController@update')->name('update_currency_exchange');
    Route::get('delete_currency_exchange/{id}', 'CurrencyExchangeController@delete')->name('delete_currency_exchange');

    // Payment Terms
    Route::get('/payment_terms', 'PaymentTermsController@index')->name('payment_terms.index');
    Route::post('/new_payment_terms', 'PaymentTermsController@store')->name('payment_terms.store');
    Route::get('/edit_payment_terms/{id}', 'PaymentTermsController@edit')->name('edit_payment_terms');
    Route::post('/update_payment_terms/{id}', 'PaymentTermsController@update')->name('update_payment_terms');
    Route::get('delete_payment_terms/{id}', 'PaymentTermsController@delete')->name('delete_payment_terms');

    // Request GAE
    Route::get('/request_gae', 'RequestGAEController@index')->name('request_gae.index');
    Route::post('/new_request_gae', 'RequestGAEController@store')->name('request_gae.store');
    Route::get('/edit_request_gae/{id}', 'RequestGAEController@edit')->name('edit_request_gae');
    Route::post('/update_request_gae/{id}', 'RequestGAEController@update')->name('update_request_gae');
    Route::get('delete_request_gae/{Id}', 'RequestGAEController@delete')->name('delete_request_gae');
});

