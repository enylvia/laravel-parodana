<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\SavingsController;

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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [ 'localize' ] ], function() {

	Route::get('/', [AuthController::class, 'showFormLogin'])->name('login');
	Route::get('login', [AuthController::class, 'showFormLogin'])->name('login');
	Route::post('login', [AuthController::class, 'login']);
	//Route::get('register', [AuthController::class, 'showFormRegister'])->name('register');
	//Route::post('register', [AuthController::class, 'register']);

	Route::group(['middleware' => 'auth'], function () {

		Route::get('home', [HomeController::class, 'index'])->name('home');
		Route::get('totalbox/{branch}', [HomeController::class, 'totalBox'])->name('totalbox');
		Route::get('logout', [AuthController::class, 'logout'])->name('logout');

	});

	//LOAN
	//Route::get('/loan/form', [App\Http\Controllers\LoanController::class, 'form'])->name('loan-form');
	//Route::post('/loan/form/store', [App\Http\Controllers\LoanController::class, 'form_store'])->name('loan-form.store');

	//CUSTOMER
	Route::get('/customer/form', [App\Http\Controllers\CustomerController::class, 'form'])->name('customer-form');
	Route::post('/customer/form/store', [App\Http\Controllers\CustomerController::class, 'form_store'])->name('customer-form.store');
	Route::post('/customer/company/store', [App\Http\Controllers\CustomerController::class, 'company_store'])->name('customer-company.store');
	Route::post('/customer/maritial/store', [App\Http\Controllers\CustomerController::class, 'maritial_store'])->name('customer-maritial.store');
	Route::post('/customer/family/store', [App\Http\Controllers\CustomerController::class, 'family_store'])->name('customer-family.store');

	//CUSTOMER LIST
	Route::get('/customer/list', [App\Http\Controllers\CustomerController::class, 'index'])->name('customer');
	Route::get('/customer/list/edit/{id}', [App\Http\Controllers\CustomerController::class, 'edit'])->name('customer.edit');
	Route::post('/customer/list/update/{id}', [App\Http\Controllers\CustomerController::class, 'update'])->name('customer.update');
	Route::get('/customer/list/view/{id}', [App\Http\Controllers\CustomerController::class, 'view'])->name('customer.view');
	Route::get('/customer/list/delete/{id}', [App\Http\Controllers\CustomerController::class, 'delete'])->name('customer.delete');
	Route::get('/customer/list/print/{id}', [App\Http\Controllers\CustomerController::class, 'print'])->name('customer.print');
	Route::get('/customer/simulation/credit/{id}', [App\Http\Controllers\CustomerController::class, 'simulation'])->name('customer.simulation');
	Route::get('/customer/statement/{id}', [App\Http\Controllers\CustomerController::class, 'statement'])->name('customer.statement');
	Route::get('/customer/list/search', [App\Http\Controllers\CustomerController::class, 'search'])->name('customer.search');

	//SURVEY
	//Route::get('/loan/survey', [App\Http\Controllers\CustomerSurveyController::class, 'survey'])->name('survey');
	Route::get('/customer/survey', [App\Http\Controllers\CustomerSurveyController::class, 'index'])->name('survey');
	Route::get('/customer/survey/plan/{reg_number}', [App\Http\Controllers\CustomerSurveyController::class, 'survey'])->name('survey.plan');
	Route::get('/customer/survey/approve', [App\Http\Controllers\CustomerSurveyController::class, 'approve'])->name('survey.approve');
	Route::post('/survey/plan/update/{id}', [App\Http\Controllers\CustomerSurveyController::class, 'plan_update'])->name('survey.plan.update');
	Route::get('/customer/survey/create/{id}', [App\Http\Controllers\CustomerSurveyController::class, 'create'])->name('survey.create');
	Route::post('/customer/survey/store', [App\Http\Controllers\CustomerSurveyController::class, 'store'])->name('survey.store');
	//Route::post('/customer/submission/store', [App\Http\Controllers\CustomerSubmissionController::class, 'store'])->name('submission.store');

	//APPROVE
	Route::get('/customer/approve', [App\Http\Controllers\CustomerApproveController::class, 'index'])->name('approve');
	Route::post('/customer/approve/store', [App\Http\Controllers\CustomerApproveController::class, 'store'])->name('approve.store');
	Route::get('/customer/view/{id}', [App\Http\Controllers\CustomerApproveController::class, 'view'])->name('approve.view');

	//CONTRACT
	Route::get('/customer/contract', [App\Http\Controllers\CustomerContractController::class, 'contract'])->name('contract');
	Route::get('/customer/contract/create/{id}', [App\Http\Controllers\CustomerContractController::class, 'create'])->name('contract.create');
	Route::post('/customer/contract/store', [App\Http\Controllers\CustomerContractController::class, 'store'])->name('contract.store');
	Route::get('/customer/contract/print/{id}', [App\Http\Controllers\CustomerContractController::class, 'contract_print'])->name('contract.print');
	Route::get('/customer/contract/signature', [App\Http\Controllers\CustomerContractController::class, 'contract_signature'])->name('contract.signature');
	Route::post('/customer/contract/signature/upload', [App\Http\Controllers\CustomerContractController::class, 'signature_upload'])->name('contract.signature.upload');
	Route::get('/customer/contract/list', [App\Http\Controllers\CustomerContractController::class, 'contract_list'])->name('contract.list');
	Route::get('/customer/contract/json', [App\Http\Controllers\CustomerContractController::class, 'cclistJson'])->name('contract.json');
	Route::get('/customer/contract/data', [App\Http\Controllers\CustomerContractController::class, 'getContractData'])->name('contract.data');
	Route::get('/customer/contract/detail/{id}', [App\Http\Controllers\CustomerContractController::class, 'LaporanPeminjaman'])->name('contract.detail');

	//HANDOVER
	Route::get('/customer/handover', [App\Http\Controllers\HandoverController::class, 'index'])->name('handover');
	Route::get('/customer/handover/create/{reg_number}', [App\Http\Controllers\HandoverController::class, 'create'])->name('handover.create');
	Route::post('/customer/handover/store', [App\Http\Controllers\HandoverController::class, 'store'])->name('handover.store');
	Route::get('/customer/handover/edit/{reg_number}', [App\Http\Controllers\HandoverController::class, 'edit'])->name('handover.edit');
	Route::post('/customer/handover/update/{id}', [App\Http\Controllers\HandoverController::class, 'update'])->name('handover.update');
	Route::get('/customer/handover/delete/{id}',[App\Http\Controllers\HandoverController::class, 'delete'])->name('handover.delete');
	Route::get('/customer/handover/print/{id}',[App\Http\Controllers\HandoverController::class, 'print'])->name('handover.print');

	//DOCUMENT
	Route::get('/customer/document', [App\Http\Controllers\CustomerDocumentController::class, 'index'])->name('document');
	Route::post('/customer/document/store', [App\Http\Controllers\CustomerDocumentController::class, 'store'])->name('document.store');
	Route::get('/customer/document/create/{id}', [App\Http\Controllers\CustomerDocumentController::class, 'create'])->name('document.create');
	Route::get('/customer/document/edit/{id}', [App\Http\Controllers\CustomerDocumentController::class, 'edit'])->name('document.edit');
	Route::post('/customer/document/update/{id}', [App\Http\Controllers\CustomerDocumentController::class, 'update'])->name('document.update');
	Route::post('/customer/document/delete/{id}', [App\Http\Controllers\CustomerDocumentController::class, 'destroy'])->name('document.destroy');
	//Route::get('/customer/document/dropzone', [App\Http\Controllers\CustomerDocumentController::class, 'fetch'])->name('dropzone.fetch');

	//RELOAN
	Route::get('/customer/reloan', [App\Http\Controllers\ReLoanController::class, 'stepOne'])->name('reloan.StepOne');
	Route::post('/customer/reloan/storeone', [App\Http\Controllers\ReLoanController::class, 'storeStepOne'])->name('reloan.storeStepOne');
	Route::get('/customer/reloan/steptwo/{id}', [App\Http\Controllers\ReLoanController::class, 'stepTwo'])->name('reloan.StepTwo');
	Route::post('/customer/reloan/storetwo', [App\Http\Controllers\ReLoanController::class, 'storeStepTwo'])->name('reloan.storeStepTwo');
	Route::get('/customer/reloan/stepthree/{id}', [App\Http\Controllers\ReLoanController::class, 'stepThree'])->name('reloan.StepThree');
	Route::post('/customer/reloan/storethree', [App\Http\Controllers\ReLoanController::class, 'storeStepThree'])->name('reloan.storeStepThree');
	Route::get('/customer/reloan/stepfour/{id}', [App\Http\Controllers\ReLoanController::class, 'stepFour'])->name('reloan.StepFour');
	Route::post('/customer/reloan/storefour', [App\Http\Controllers\ReLoanController::class, 'storeStepFour'])->name('reloan.storeStepFour');
	Route::post('/customer/reloan/fetch', [App\Http\Controllers\ReLoanController::class, 'fetch'])->name('reloan.fetch');
	Route::get('/customer/reloan/print/{id}', [App\Http\Controllers\ReLoanController::class, 'print'])->name('reloan.print');

	//BALANCE
	Route::get('/customer/balance', [App\Http\Controllers\BalanceController::class, 'index'])->name('balance');
	//Route::match(['get', 'post'],'/customer/balance', [App\Http\Controllers\BalanceController::class, 'index'])->name('balance');
	Route::get('/customer/balance/create', [App\Http\Controllers\BalanceController::class, 'create'])->name('balance.create');
	Route::post('/customer/balance/store', [App\Http\Controllers\BalanceController::class, 'store'])->name('balance.store');
	Route::get('/customer/balance/load', [App\Http\Controllers\BalanceController::class, 'loaddata'])->name('balance.loaddata');
	Route::get('/customer/balance/history/{id}', [App\Http\Controllers\BalanceController::class, 'history'])->name('balance.history');
	Route::get('/customer/balance/view/{id}', [App\Http\Controllers\BalanceController::class, 'view'])->name('balance.view');
	Route::get('/customer/balance/edit/{id}', [App\Http\Controllers\BalanceController::class, 'edit'])->name('balance.edit');
	Route::post('/customer/balance/update/{id}', [App\Http\Controllers\BalanceController::class, 'update'])->name('balance.update');
	Route::get('/customer/balance/delete/{id}', [App\Http\Controllers\BalanceController::class, 'delete'])->name('balance.delete');
	Route::post('/customer/balance/type',[App\Http\Controllers\BalanceController::class, 'loadtype'])->name('balance.loadtype');
	Route::get('/customer/balance/print/{id}', [App\Http\Controllers\BalanceController::class, 'print'])->name('balance.print');
	Route::get('/customer/balance/journal/{id}', [App\Http\Controllers\BalanceController::class, 'journal'])->name('balance.journal');

	//EMPLOYEE
	Route::get('/employee', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employee');
	Route::get('/employee/create', [App\Http\Controllers\EmployeeController::class, 'create'])->name('employee.create');
	Route::post('/employee/store', [App\Http\Controllers\EmployeeController::class, 'store'])->name('employee.store');
	Route::get('/employee/edit/{id}', [App\Http\Controllers\EmployeeController::class, 'edit'])->name('employee.edit');
	Route::post('/employee/update/{id}', [App\Http\Controllers\EmployeeController::class, 'update'])->name('employee.update');
	Route::get('/employee/delete/{id}', [App\Http\Controllers\EmployeeController::class, 'delete'])->name('employee.delete');
	Route::get('/employee/print/{id}', [App\Http\Controllers\EmployeeController::class, 'print'])->name('employee.print');
	Route::get('/employee/printAll/{id}', [App\Http\Controllers\EmployeeController::class, 'printAll'])->name('employee.printAll');
	Route::get('/employee/profile/{id}', [App\Http\Controllers\EmployeeController::class, 'profile'])->name('employee.profile');
	Route::post('/employee/changepassword', [App\Http\Controllers\EmployeeController::class, 'changePassword'])->name('employee.changePassword');

	//ROLE
	Route::get('/role', [App\Http\Controllers\RoleController::class, 'index'])->name('role');
	Route::get('/role/create', [App\Http\Controllers\RoleController::class, 'create'])->name('role.create');
	Route::post('/role/store', [App\Http\Controllers\RoleController::class, 'store'])->name('role.store');
	Route::get('/role/edit/{id}', [App\Http\Controllers\RoleController::class, 'edit'])->name('role.edit');
	Route::post('/role/update/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('role.update');
	Route::get('/role/delete/{id}', [App\Http\Controllers\RoleController::class, 'delete'])->name('role.delete');

	//PERMISSION
	Route::get('/permission', [App\Http\Controllers\PermissionController::class, 'index'])->name('permission');
	Route::get('/permission/create', [App\Http\Controllers\PermissionController::class, 'create'])->name('permission.create');
	Route::post('/permission/store', [App\Http\Controllers\PermissionController::class, 'store'])->name('permission.store');
	Route::get('/permission/edit/{id}', [App\Http\Controllers\PermissionController::class, 'edit'])->name('permission.edit');
	Route::post('/permission/update/{id}', [App\Http\Controllers\PermissionController::class, 'update'])->name('permission.update');
	Route::get('/permission/delete/{id}', [App\Http\Controllers\PermissionController::class, 'delete'])->name('permission.delete');

	//COMPANY
	Route::get('/company', [App\Http\Controllers\CompaniesController::class, 'index'])->name('company');
	Route::get('/company/create', [App\Http\Controllers\CompaniesController::class, 'create'])->name('company.create');
	Route::post('/company/store', [App\Http\Controllers\CompaniesController::class, 'store'])->name('company.store');
	Route::get('/company/edit/{id}', [App\Http\Controllers\CompaniesController::class, 'edit'])->name('company.edit');
	Route::post('/company/update/{id}', [App\Http\Controllers\CompaniesController::class, 'update'])->name('company.update');
	Route::get('/company/delete/{id}', [App\Http\Controllers\CompaniesController::class, 'delete'])->name('company.delete');

	//ADDRESS
	Route::get('/address/country/{id}', [App\Http\Controllers\AddressController::class, 'country'])->name('country');
	Route::get('/address/provinsi/{id}', [App\Http\Controllers\AddressController::class, 'provinsi'])->name('provinsi');
	Route::get('/address/kabupaten/{id}', [App\Http\Controllers\AddressController::class, 'kabupaten'])->name('kabupaten');
	Route::get('/address/kecamatan/{id}', [App\Http\Controllers\AddressController::class, 'kecamatan'])->name('kecamatan');
	Route::get('/address/kelurahan/{id}', [App\Http\Controllers\AddressController::class, 'kelurahan'])->name('kelurahan');

	//ACCOUNT GROUP
	Route::get('/account', [App\Http\Controllers\AccountGroupController::class, 'index'])->name('account');
	Route::get('/account/create', [App\Http\Controllers\AccountGroupController::class, 'create'])->name('account.create');
	Route::post('/account/store', [App\Http\Controllers\AccountGroupController::class, 'store'])->name('account.store');
	Route::get('/account/edit/{id}', [App\Http\Controllers\AccountGroupController::class, 'edit'])->name('account.edit');
	Route::post('/account/update/{id}', [App\Http\Controllers\AccountGroupController::class, 'update'])->name('account.update');
	Route::get('/account/delete/{id}', [App\Http\Controllers\AccountGroupController::class, 'delete'])->name('account.delete');

	 //MENU MANAGEMENT
	Route::get('/menu/management',[App\Http\Controllers\MenuManagementController::class, 'index'])->name('menu');
	Route::get('/menu/management/create',[App\Http\Controllers\MenuManagementController::class, 'create'])->name('menu.create');
	Route::post('/menu/management/store',[App\Http\Controllers\MenuManagementController::class, 'store'])->name('menu.store');
	Route::post('/menu/management/update/{id}',[App\Http\Controllers\MenuManagementController::class, 'update'])->name('menu.update');
	//Route::get('/menu/management/delete/{id}',[App\Http\Controllers\MenuManagementController::class, 'delete'])->name('menu.delete');
	Route::post('/menu/management/delete',[App\Http\Controllers\MenuManagementController::class, 'delete'])->name('menu.delete');
	//Route::post('menu/management/save', ['as' => 'menu.save', 'uses' => 'App\Http\Controllers\MenuManagementController@save']);
	//Route::post('/menu/management/{id}/toggle-publish/', 'App\Http\Controllers\MenuManagementController@togglePublish')->where('id', '[0-9]+', function(){
		//return view('menu.index');
		//});
	Route::get('/menu/all/{id}/', 'App\Http\Controllers\MenuManagementController@accessMenu')->where('id', '[0-9]+', function(){
		return view('menu.menu');
		});

	//MENU LIST
	Route::get('/menu/list',[App\Http\Controllers\MenuListController::class, 'index'])->name('menulist');
	Route::post('/menu/list/store',[App\Http\Controllers\MenuListController::class, 'store'])->name('menulist.store');
	Route::post('/menu/list/update/{id}',[App\Http\Controllers\MenuListController::class, 'update'])->name('menulist.update');
	Route::get('/menu/list/delete/{id}/{role}/',[App\Http\Controllers\MenuListController::class, 'delete'])->name('menulist.delete');
	Route::post('menu/list/save', ['as' => 'menu.save', 'uses' => 'App\Http\Controllers\MenuListController@save']);
	//Route::post('/menu/list/{id}/toggle-publish/', 'App\Http\Controllers\MenuListController@togglePublish')->where('id', '[0-9]+', function(){
		//return view('menu.list.index');
		//});
	Route::post('/menu/list/{id}/{role}/', 'App\Http\Controllers\MenuListController@accessMenu')->where('id', '[0-9]+', function(){
		return view('menu.list.menu');
		});

	// USER MANAGEMENT
	Route::get('/user/management',[App\Http\Controllers\UserManagementController::class, 'user_role'])->name('userrole');
	Route::post('/user/management/store',[App\Http\Controllers\UserManagementController::class, 'store'])->name('userrole.store');
	Route::post('/user/management/delete',[App\Http\Controllers\UserManagementController::class, 'delete'])->name('userrole.delete');
	Route::post('/user/management/update',[App\Http\Controllers\UserManagementController::class, 'update'])->name('userrole.update');

	//INTEREST RATE
	Route::get('/interest/rate',[App\Http\Controllers\InterestRateController::class, 'index'])->name('interestrate');
	Route::get('/interest/rate/create',[App\Http\Controllers\InterestRateController::class, 'create'])->name('interestrate.create');
	Route::post('/interest/rate/store',[App\Http\Controllers\InterestRateController::class, 'store'])->name('interestrate.store');
	Route::post('/interest/rate/update/{id}',[App\Http\Controllers\InterestRateController::class, 'update'])->name('interestrate.update');
	Route::get('/interest/rate/delete/{id}',[App\Http\Controllers\InterestRateController::class, 'create'])->name('interestrate.delete');

	//TAX
	Route::get('/setting/tax',[App\Http\Controllers\TaxController::class, 'index'])->name('tax');
	Route::get('/setting/tax/create',[App\Http\Controllers\TaxController::class, 'create'])->name('tax.create');
	Route::post('/setting/tax/store',[App\Http\Controllers\TaxController::class, 'store'])->name('tax.store');
	Route::post('/setting/tax/update/{id}',[App\Http\Controllers\TaxController::class, 'update'])->name('tax.update');
	Route::get('/setting/tax/delete/{id}',[App\Http\Controllers\TaxController::class, 'create'])->name('tax.delete');

	//JOURNAL & TRANSACTION
	Route::get('/transaction/history',[App\Http\Controllers\JournalController::class, 'index'])->name('transaction.history');
	Route::get('/transactions/list',[App\Http\Controllers\JournalController::class, 'daftarTransaction'])->name('transaction.list');
	Route::get('/transaction/report',[App\Http\Controllers\JournalController::class, 'laporan_transaksi'])->name('transaction.report');
	Route::get('/transaction/report/detail',[App\Http\Controllers\JournalController::class, 'laporan'])->name('transaction.report.detail');

	Route::get('/transaction/report/running/index',[App\Http\Controllers\JournalController::class,'running_index'])->name('transaction.running.index');
	Route::get('/transaction/report/running/transactions',[App\Http\Controllers\JournalController::class,'running_report'])->name('transaction.running');
	Route::get('/transaction/report/not-running',[App\Http\Controllers\JournalController::class,'getAllTransactionTakTertagih'])->name('transaction.not_running');

	Route::get('/transaction/report/new-nasabah/index',[App\Http\Controllers\JournalController::class,'pinjaman_baru'])->name('transaction.pinjaman.baru');
	Route::get('/transaction/report/new-nasabah',[App\Http\Controllers\JournalController::class,'pinjaman_baru_report'])->name('transaction.pinjaman.baru.report');
 


	Route::get('/report/laba-rugi',[App\Http\Controllers\JournalController::class, 'labarugi'])->name('report.laba-rugi');
	Route::get('/report/index-perubahan-modal',[App\Http\Controllers\JournalController::class, 'indexperubahanmodal'])->name('index.perubahan-modal');
	Route::get('/report/index/perubahan-modal',[App\Http\Controllers\JournalController::class, 'perubahanmodal'])->name('report.perubahan-modal');
	Route::get('/report/posisi-keuangan',[App\Http\Controllers\JournalController::class, 'reportPosisi'])->name('report.keuangan');
	Route::get('/report/index-posisi-keuangan',[App\Http\Controllers\JournalController::class, 'posisikeuangan'])->name('index.report.keuangan');

	Route::get('/transaction/baki',[App\Http\Controllers\JournalController::class, 'baki'])->name('transaction.baki');
	Route::get('/transaction/report-baki',[App\Http\Controllers\JournalController::class, 'reportBaki'])->name('transaction.report.baki');
	Route::get('/transaction/insurance-json',[App\Http\Controllers\JournalController::class, 'getallInsurance'])->name('transaction.issurance');
	Route::get('/transaction/report-issurance',[App\Http\Controllers\JournalController::class, 'GetInsurance'])->name('insurance.index');
	Route::get('/transaction/index-issurance',[App\Http\Controllers\JournalController::class, 'insuranceIndex'])->name('insur');
	Route::get('/transaction/buku-hutang', [App\Http\Controllers\JournalController::class, 'bukuHutang'])->name('buku.hutang');
	Route::get('/transaction/get-buku-hutang/{id}', [App\Http\Controllers\JournalController::class, 'getBukuHutang'])->name('get.buku.hutang');
	//LEDGER
	// Route::get('/ledger',[App\Http\Controllers\LedgerController::class, 'index'])->name('ledger');
	Route::get('/ledger',[App\Http\Controllers\LedgerController::class, 'index'])->name('ledger');
	Route::post('/ledger/detail',[App\Http\Controllers\LedgerController::class, 'detail'])->name('ledger.detail');

	//NERACA SALDO
	Route::get('/neraca/saldo',[App\Http\Controllers\NeracaSaldoController::class, 'index'])->name('neracasaldo');
	Route::get('/neraca/index/saldo',[App\Http\Controllers\NeracaSaldoController::class, 'index_saldo'])->name('neraca.index.saldo');
	Route::get('/neraca/saldo/detail',[App\Http\Controllers\NeracaSaldoController::class, 'detail'])->name('neracasaldo.detail');
	Route::get('/neraca/saveToBalanceHistory',[App\Http\Controllers\NeracaSaldoController::class, 'saveToBalanceHistory'])->name('neracasaldo.saveToBalanceHistory');
	Route::get('/neraca/saldo/history',[App\Http\Controllers\NeracaSaldoController::class, 'neracasaldo'])->name('neracasaldo.history');

	//INSTALLMENT
	Route::get('/installment/',[App\Http\Controllers\InstallmentController::class, 'index'])->name('installment');
	Route::get('/installment/create',[App\Http\Controllers\InstallmentController::class, 'create'])->name('installment.create');
	Route::post('/installment/store',[App\Http\Controllers\InstallmentController::class, 'store'])->name('installment.store');
	Route::get('/installment/edit/{loan_number}',[App\Http\Controllers\InstallmentController::class, 'edit'])->name('installment.edit');
	Route::get('/installment/view/{loan_number}',[App\Http\Controllers\InstallmentController::class, 'view'])->name('installment.view');
	Route::post('/installment/update/{id}',[App\Http\Controllers\InstallmentController::class, 'update'])->name('installment.update');
	Route::post('/installment/loan/update/{member_number}',[App\Http\Controllers\InstallmentController::class, 'loan_update'])->name('installment.loan.update');
	Route::get('/installment/create/table/{loan_number}',[App\Http\Controllers\InstallmentController::class, 'table_create'])->name('installment.table');
	Route::get('/installment/create/pay/{loan_number}',[App\Http\Controllers\InstallmentController::class, 'pay_create'])->name('installment.pay');
	Route::post('/installment/full_store/{id}',[App\Http\Controllers\InstallmentController::class, 'full_store'])->name('installment.full_store');
	Route::post('/installment/free_store/{id}',[App\Http\Controllers\InstallmentController::class, 'free_store'])->name('installment.free_store');
    Route::get('/installment/lunas',[App\Http\Controllers\InstallmentController::class, 'lunas'])->name('installment.lunas');
	//Route::post('/installment/free_store',[App\Http\Controllers\InstallmentController::class, 'free_store'])->name('installment.free_store');
	Route::post('/installment/repayment_store/{id}',[App\Http\Controllers\InstallmentController::class, 'repayment_store'])->name('installment.repayment_store');
	Route::get('/installment/print/{id}',[App\Http\Controllers\InstallmentController::class, 'print'])->name('installment.print');
	Route::get('/installment/printAll/{loan_number}',[App\Http\Controllers\InstallmentController::class, 'printAll'])->name('installment.printAll');
	Route::get('/installment/printFull/{id}',[App\Http\Controllers\InstallmentController::class, 'printFull'])->name('installment.printFull');
	Route::get('/installment/printFree/{id}',[App\Http\Controllers\InstallmentController::class, 'printFree'])->name('installment.printFree');
	Route::get('/installment/printRepayment/{id}',[App\Http\Controllers\InstallmentController::class, 'printRepayment'])->name('installment.printRepayment');
	Route::post('/installment/getCicilan/{loanNumber}',[App\Http\Controllers\InstallmentController::class, 'getCicilan'])->name('installment.getCicilan');
	
	Route::get('/installment/flat/{jumlah}/{tenor}/{bunga}',[App\Http\Controllers\InstallmentController::class, 'metode_flat'])->name('installment.flat');
	Route::get('/installment/posting/{id}',[App\Http\Controllers\InstallmentController::class, 'posting'])->name('installment.posting');
	Route::get('/installment/search',[App\Http\Controllers\InstallmentController::class, 'search'])->name('installment.search');
	Route::get('/installment/journal/{id}',[App\Http\Controllers\InstallmentController::class, 'journal'])->name('installment.journal');
	Route::get('/installment/getDetail/{loan_number}',[App\Http\Controllers\InstallmentController::class, 'getDetailsData'])->name('installment.detailsData');
	
	//SAVINGS
	Route::get('/deposit/getSaving/{limit}/{memberNumber}',[App\Http\Controllers\SavingsController::class, 'getTabunganList'])->name('deposit.list');
	Route::get('/deposit/savings/print/{id}',[App\Http\Controllers\SavingsController::class, 'printTabungan'])->name('print.tabungan');
	Route::get('/deposit',[App\Http\Controllers\SavingsController::class, 'index'])->name('deposit');
	Route::get('/depositJson',[App\Http\Controllers\SavingsController::class, 'indexJson'])->name('deposit.json');
	Route::get('/deposit/create',[App\Http\Controllers\SavingsController::class, 'create'])->name('deposit.create');
	Route::post('/deposit/store',[App\Http\Controllers\SavingsController::class, 'store'])->name('deposit.store');
	Route::get('/deposit/edit/{id}',[App\Http\Controllers\SavingsController::class, 'edit'])->name('deposit.edit');
	Route::post('/deposit/update/{id}',[App\Http\Controllers\SavingsController::class, 'update'])->name('deposit.update');
	Route::get('/deposit/delete/{id}',[App\Http\Controllers\SavingsController::class, 'delete'])->name('deposit.delete');
	Route::get('/deposit/search',[App\Http\Controllers\SavingsController::class, 'search'])->name('deposit.search');
	Route::get('/deposit/journal/{id}',[App\Http\Controllers\SavingsController::class, 'journal'])->name('deposit.journal');
	Route::post('/deposit/saldo/update/{id}',[App\Http\Controllers\SavingsController::class, 'saldo'])->name('deposit.saldo');
	Route::get('/deposit/card/{id}',[App\Http\Controllers\SavingsController::class, 'card'])->name('deposit.card');
	Route::get('/deposit/view/{id}',[App\Http\Controllers\SavingsController::class, 'view'])->name('deposit.view');
	Route::get('/deposit/mutation/{id}',[App\Http\Controllers\SavingsController::class, 'mutation'])->name('deposit.mutation');

	//WITHDRAWAL
	Route::get('/withdrawal/',[App\Http\Controllers\WithdrawalController::class, 'index'])->name('withdrawal');
	Route::get('/withdrawal/create',[App\Http\Controllers\WithdrawalController::class, 'create'])->name('withdrawal.create');
	Route::post('/withdrawal/store',[App\Http\Controllers\WithdrawalController::class, 'store'])->name('withdrawal.store');
	Route::get('/withdrawal/edit/{id}',[App\Http\Controllers\WithdrawalController::class, 'edit'])->name('withdrawal.edit');
	Route::post('/withdrawal/update/{id}',[App\Http\Controllers\WithdrawalController::class, 'update'])->name('withdrawal.update');
	Route::get('/withdrawal/delete/{id}',[App\Http\Controllers\WithdrawalController::class, 'delete'])->name('withdrawal.delete');
	Route::get('/withdrawal/search',[App\Http\Controllers\WithdrawalController::class, 'search'])->name('withdrawal.search');
	Route::get('/withdrawal/posting/{id}',[App\Http\Controllers\WithdrawalController::class, 'posting'])->name('withdrawal.posting');

	//RECEIPT
	Route::get('/receipt/',[App\Http\Controllers\ReceiptController::class, 'index'])->name('receipt');
	Route::get('/receipt/create',[App\Http\Controllers\ReceiptController::class, 'create'])->name('receipt.create');
	Route::post('/receipt/store',[App\Http\Controllers\ReceiptController::class, 'store'])->name('receipt.store');
	Route::get('/receipt/edit/{id}',[App\Http\Controllers\ReceiptController::class, 'edit'])->name('receipt.edit');
	Route::post('/receipt/update/{id}',[App\Http\Controllers\ReceiptController::class, 'update'])->name('receipt.update');
	Route::get('/receipt/delete/{id}',[App\Http\Controllers\ReceiptController::class, 'delete'])->name('receipt.delete');
	Route::get('/receipt/print/{id}',[App\Http\Controllers\ReceiptController::class, 'print'])->name('receipt.print');
	Route::get('/receipt/posting/{id}',[App\Http\Controllers\ReceiptController::class, 'posting'])->name('receipt.posting');
	Route::get('/receipt/journal/{id}',[App\Http\Controllers\ReceiptController::class, 'journal'])->name('receipt.journal');

	//PAYMENT
	Route::get('/transaction/payment', [App\Http\Controllers\PaymentController::class, 'index'])->name('payment');
	Route::get('/transaction/payment/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');
	Route::post('/transaction/payment/store', [App\Http\Controllers\PaymentController::class, 'store'])->name('payment.store');
	Route::get('/transaction/payment/edit/{id}', [App\Http\Controllers\PaymentController::class, 'edit'])->name('payment.edit');
	Route::post('/transaction/payment/update/{id}', [App\Http\Controllers\PaymentController::class, 'update'])->name('payment.update');
	Route::get('/transaction/payment/delete/{id}', [App\Http\Controllers\PaymentController::class, 'delete'])->name('payment.delete');
	Route::get('/transaction/payment/print/{id}', [App\Http\Controllers\PaymentController::class, 'print'])->name('payment.print');
	Route::post('/transaction/payment/loadcustomer', [App\Http\Controllers\PaymentController::class, 'loadcustomer'])->name('payment.loadcustomer');
	Route::post('/transaction/payment/loadpayment', [App\Http\Controllers\PaymentController::class, 'loadpayment'])->name('payment.loadpayment');
	Route::get('/transaction/payment/autoComplete', [App\Http\Controllers\PaymentController::class, 'autoComplete'])->name('payment.autoComplete');
	Route::get('/transaction/payment/journal/{id}', [App\Http\Controllers\PaymentController::class, 'journal'])->name('payment.journal');

	//PURCHASE
	Route::get('/transaction/purchase', [App\Http\Controllers\PurchaseController::class, 'index'])->name('purchase');
	Route::get('/transaction/purchase/create', [App\Http\Controllers\PurchaseController::class, 'create'])->name('purchase.create');
	Route::post('/transaction/purchase/store', [App\Http\Controllers\PurchaseController::class, 'store'])->name('purchase.store');
	Route::get('/transaction/purchase/edit/{id}', [App\Http\Controllers\PurchaseController::class, 'edit'])->name('purchase.edit');
	Route::post('/transaction/purchase/update/{id}', [App\Http\Controllers\PurchaseController::class, 'update'])->name('purchase.update');
	Route::get('/transaction/purchase/delete/{id}', [App\Http\Controllers\PurchaseController::class, 'delete'])->name('purchase.delete');
	Route::get('/transaction/purchase/print/{id}', [App\Http\Controllers\PurchaseController::class, 'print'])->name('purchase.print');

	//OPERATIONAL
	Route::get('/operational', [App\Http\Controllers\OperationalController::class, 'index'])->name('operational');
	Route::post('/operational/findtransactionbyname', [App\Http\Controllers\OperationalController::class, 'findTransactionName'])->name('operational.findtransactionbyname');
	Route::get('/operational/json', [App\Http\Controllers\OperationalController::class, 'operationalJson'])->name('operational.json');
	Route::post('/operational/store', [App\Http\Controllers\OperationalController::class, 'store'])->name('operational.store');
	Route::get('/operational/approve/{id}', [App\Http\Controllers\OperationalController::class, 'approve'])->name('operational.approve');
	Route::get('/operational/delete/{id}', [App\Http\Controllers\OperationalController::class, 'delete'])->name('operational.delete');

	// Journal
	Route::get('/journals', [App\Http\Controllers\JournalController::class, 'buku_besar'])->name('journal');
	Route::get('/journals/{id}', [App\Http\Controllers\InstallmentController::class, 'journal_installment'])->name('journal.installment');

	//TRANSACTION TYPE
	Route::get('/transaction/type', [App\Http\Controllers\TransactionTypeController::class, 'index'])->name('transactiontype');
	Route::get('/transaction/type/create', [App\Http\Controllers\TransactionTypeController::class, 'create'])->name('transactiontype.create');
	Route::post('/transaction/type/store', [App\Http\Controllers\TransactionTypeController::class, 'store'])->name('transactiontype.store');
	Route::get('/transaction/type/edit/{id}', [App\Http\Controllers\TransactionTypeController::class, 'edit'])->name('transactiontype.edit');
	Route::post('/transaction/type/update/{id}', [App\Http\Controllers\TransactionTypeController::class, 'update'])->name('transactiontype.update');
	Route::get('/transaction/type/delete/{id}', [App\Http\Controllers\TransactionTypeController::class, 'delete'])->name('transactiontype.delete');

	//TEMPO
	Route::get('/transaction/tempo/',[App\Http\Controllers\TempoController::class, 'index'])->name('tempo');
	Route::get('/transaction/tempo/create',[App\Http\Controllers\TempoController::class, 'create'])->name('tempo.create');
	Route::post('/transaction/tempo/store',[App\Http\Controllers\TempoController::class, 'store'])->name('tempo.store');
	Route::get('/transaction/tempo/kesepakatan', [App\Http\Controllers\TempoController::class, 'kesepakatan'])->name('tempo.kesepakatan');
	Route::get('/transaction/tempo/berjalan', [App\Http\Controllers\TempoController::class, 'berjalan'])->name('tempo.berjalan');
	Route::get('/transaction/tempo/reject/{id}', [App\Http\Controllers\TempoController::class, 'reject'])->name('tempo.reject');
	Route::get('/transaction/tempo/confirm/{id}', [App\Http\Controllers\TempoController::class, 'update'])->name('tempo.update');
	Route::get('/transaction/tempo/customer',[App\Http\Controllers\TempoController::class, 'customer'])->name('tempo.customer');

	//SETTING
	Route::get('/setting/application', [App\Http\Controllers\SettingController::class, 'index'])->name('application');
	Route::post('/setting/application/store', [App\Http\Controllers\SettingController::class, 'store'])->name('application.store');

	//REPORTS
	Route::get('/report/member',[App\Http\Controllers\ReportController::class, 'view_member'])->name('rptMember.view');
	Route::post('/report/member/print',[App\Http\Controllers\ReportController::class, 'print_member'])->name('rptMember.print');
	Route::get('/report/installment',[App\Http\Controllers\ReportController::class, 'installment'])->name('rptInstallment.view');
	Route::get('/report/installment/member/{id}',[App\Http\Controllers\ReportController::class, 'getInstallment'])->name('rptInstallment.getInstallment');
	Route::get('/report/cashflow',[App\Http\Controllers\ReportController::class, 'view_cashflow'])->name('rptCashFlow.view');
	Route::get('/report/cashflow/print/{id}',[App\Http\Controllers\ReportController::class, 'print_cashflow'])->name('rptCashFlow.print');
	Route::get('/report/installment/print/{id}',[App\Http\Controllers\ReportController::class, 'printInstallment'])->name('printInstallment.printInstallment');
	Route::post('/report/installment/printPdf',[App\Http\Controllers\ReportController::class, 'printPdf'])->name('printInstallment.printPdf');
	Route::get('/report/neraca',[App\Http\Controllers\ReportController::class, 'view_neraca'])->name('rptneraca.view');
	Route::get('/report/neraca/print/{waktu}',[App\Http\Controllers\ReportController::class, 'print_neraca'])->name('rptneraca.print');
	Route::get('/report/profitloss',[App\Http\Controllers\ReportController::class, 'profitloss'])->name('rptProfitLoss');
	Route::get('/report/profitloss/print/{waktu}',[App\Http\Controllers\ReportController::class, 'print_profitloss'])->name('rptProfitLoss.print');
	Route::get('/report/capital/change',[App\Http\Controllers\ReportController::class, 'capital_change'])->name('rptCapitalChange');
	Route::get('/report/capital/change/print/{waktu}',[App\Http\Controllers\ReportController::class, 'print_capital_change'])->name('rptProfitLoss.print');
	Route::get('/report/history/transaction',[App\Http\Controllers\ReportController::class, 'view_history_transaction'])->name('rptHistoryTransaction.view');
	Route::get('/report/history/transaction/print/{daterange}',[App\Http\Controllers\ReportController::class, 'print_history_transaction'])->name('rptHistoryTransaction.print');

	//MAILBOX
	Route::get('/mailbox', [App\Http\Controllers\MailBoxController::class, 'inbox'])->name('mailbox');
	Route::get('/mailbox/read/{id}', [App\Http\Controllers\MailBoxController::class, 'read'])->name('mailbox.read');
	Route::get('/mailbox/compose', [App\Http\Controllers\MailBoxController::class, 'read'])->name('mailbox.compose');
	Route::delete('/mailbox/{id}', [App\Http\Controllers\MailBoxController::class, 'delete']);
	Route::delete('/mailbox/DeleteAll', [App\Http\Controllers\MailBoxController::class, 'deleteAll']);

	Route::get('/import', [App\Http\Controllers\ImportController::class, 'index'])->name('import.index');
	Route::post('/import', [App\Http\Controllers\ImportController::class, 'submit'])->name('import.submit');

	//SIMULATION CREDIT
	///Route::get('/simulation/credit',[App\Http\Controllers\SimulationController::class, 'simulation'])->name('simulation');
	Route::match(['get', 'post'],'/simulation/credit', 'App\Http\Controllers\SimulationController@simulation', function () {
            return view('simulation.simulation');
    });

	// FILEMANAGER
    Route::group(['middleware' => 'web', 'auth'], function () {
        Route::get('filemanager/show', function () {
            return View::make('plugins/filemanager');
        });
        Route::get('flemanager/upload', function () {
            return View::make('plugins/filemanager');
        });
    });

});
