<?php



use Illuminate\Support\Carbon;

use App\Http\Controllers\Reports;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GeneralSetting;

use App\Http\Controllers\MainController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\FloorController;

use App\Http\Controllers\RoomsController;

use App\Http\Controllers\BranchController;

use App\Http\Controllers\BookingController;

use App\Http\Controllers\RoomTypeController;

use App\Http\Controllers\PettycashController;

use App\Http\Controllers\flexincomeController;

use App\Http\Controllers\RoomStatusController;

use App\Http\Controllers\ImportExportController;

use App\Http\Controllers\ReportsBookingCom;


// Route::get('/', function () {

//     return view('welcome');

// });



// Route::get('/mail_test',function(){



//     Mail::send([],[], function($message) {



//                 $message->to('dev6@fajri.com', 'Test Mail')

//                         ->subject('Test Mail')

//                         ->setBody('Hi, This method working fine');



//         });



//         return 'Mail has been sent';



// });



// Route::get('/test',function(){

    

//     $year = date('Y');

//     $jan = $year.'-01-01';

//     $feb = $year.'-02-01';

//     $mar = $year.'-03-01';

//     $apr = $year.'-04-01';

//     $may = $year.'-05-01';

//     $jun = $year.'-06-01';

//     $jul = $year.'-07-01';

//     $aug = $year.'-08-01';

//     $sep = $year.'-09-01';

//     $oct = $year.'-10-01';

//     $nov = $year.'-11-01';

//     $dec = $year.'-12-01';

        

// });


Route::get('/', [MainController::class, 'login'])->name('login');

Route::get('/register', [MainController::class, 'register'])->name('register');

Route::get('/forgotpassword', [MainController::class, 'forgotpassword'])->name('forgotpassword');

Route::post('/signup', [MainController::class, 'signup'])->name('signup');

Route::post('/auth_check', [MainController::class, 'auth_check'])->name('auth_check');



Route::get('/logout', function(){

    if(session()->has('user_id')) {

        session()->forget('user_id');

        session()->forget('role_id');

        session()->forget('br_code');

        session()->forget('br_code1');

        session()->forget('received_amount');

        return redirect()->route('login')->with('logout', 'Successfully Logout');

    }

    
})->name('logout');



//Route::get('/application', [MainController::class, 'application'])->middleware('MemberAuth')->name('application');



// Route::view('/print_receipt', 'print.print_receipt')->name('print_receipt');

// Route::get('/receipt_pdf', [BookingController::class, 'receipt_pdf'])->name('receipt_pdf');

// Route::view('/checkout_invoice', 'invoices.invoice_checkout')->name('checkout_invoice');



// Route::view('/testPrint', 'print.testPrint');

// Route::get('/test', function(){



//     $result = DB::select("SELECT id, brCode, STR_TO_DATE(created_at,'%Y-%m-%d') as date, description, creditAmount, debitAmount,

//         cast((@Balance := @Balance + creditAmount - debitAmount) as DECIMAL(16,2)) as Balance

//         FROM payment_receipt

//         JOIN (SELECT @Balance :=0) as tmp

//         WHERE brCode = 1002 AND description = 'Partial Amount'

//         ORDER BY date asc, id asc");



//        return array($result);



// });







Route::group(['middleware'=>['MemberAuth']], function(){



    Route::get('/application', [MainController::class, 'application'])->name('application');

    Route::get('/profile', [MainController::class, 'profile'])->name('profile');    

    Route::post('/profile_update', [MainController::class, 'profile_update'])->name('profile_update');    

    Route::post('/reset_password', [MainController::class, 'reset_password'])->name('reset_password');    

    Route::post('/profile_pic', [MainController::class, 'profile_pic'])->name('profile_pic');    



    //shift control for receptionist

    Route::get('/temporary', [MainController::class, 'temporary'])->name('temporary');

    Route::get('/shift_change/{id}', [MainController::class, 'shift_change'])->name('shift_change');

    Route::get('/shift_closed/{id}',function($id){



        $data = DB::table('usertrackere')->insert([



            'loginDate' => date('Y-m-d H:i:s'),

            'loginTime' => '00',

            'logoutTime' => date('H:i:s'),

            'userId' => $id,

            'status' => 1,



        ]);



        if($data) {



            if(session()->has('user_id')) {

                session()->forget('user_id');

                session()->forget('role_id');

                session()->forget('br_code');

                session()->forget('br_code1');

                session()->forget('received_amount');

                return redirect()->route('login')->with('logout', 'Successfully Logout');

            }



        }



    });

    

    //imports and exports

    Route::post('/import', [ImportExportController::class, 'rooms_import'])->name('import');



    // Manage Booking aditional routes

    // Route::get('/available/{id}/{brcode}', [BookingController::class, 'available'])->name('available');

    Route::post('/available', [BookingController::class, 'available'])->name('available');



    Route::get('/reservation', [BookingController::class, 'reservation'])->name('reservation');

    // Route::get('/occupied/{id}/{brcode}', [BookingController::class, 'occupied'])->name('occupied');

    Route::get('/occupied/{id}', [BookingController::class, 'occupied'])->name('occupied');



    // Route::get('/unclean/{id}', [BookingController::class, 'unclean'])->name('unclean');

    // Route::get('/cleantoAvailable/{room_no}/{br_code}', [BookingController::class, 'cleantoAvailable'])->name('cleantoAvailable');

    Route::get('/cleantoAvailable/{id}', [BookingController::class, 'cleantoAvailable'])->name('cleantoAvailable');



    Route::post('/ratecontroll', [BookingController::class, 'ratecontroll'])->name('ratecontroll');

    Route::get('/checkout/{id}', [BookingController::class, 'checkout'])->name('checkout');

    Route::get('/receipt_pdf/{id}', [BookingController::class, 'receipt_pdf'])->name('receipt_pdf');

    Route::get('/invoice_pdf/{id}', [BookingController::class, 'invoice_pdf'])->name('invoice_pdf');

    Route::get('/payment_receipt_pdf/{id}', [BookingController::class, 'payment_receipt_pdf'])->name('payment_receipt_pdf');

    Route::get('/all_bookings', [BookingController::class, 'allbookings'])->name('all_bookings');

    Route::post('/booking_cancel', [BookingController::class, 'booking_cancel'])->name('booking_cancel');

    Route::post('/customerInfo', [BookingController::class, 'customerInfo'])->name('customerInfo');

    Route::get('/booking_details', [BookingController::class, 'booking_details'])->name('booking_details');

    Route::match(['get','post'], '/filter_booking', [BookingController::class, 'filter_booking'])->name('filter_booking');



    Route::get('/recent_bookings', [BookingController::class, 'recent_bookings'])->name('recent_bookings'); //code added on 06-07-2022

    Route::post('/roomTransfer',[BookingController::class, 'roomTransfer'])->name('booking.roomTransfer'); //code added on 15-02-2023



    //Booking.com routes----------------------------------------------------------------------------------@23-02-2023

    Route::get('/booking_com',[BookingController::class, 'booking_com'])->name('booking_com.booking_com'); //code added on 15-02-2023

    Route::match(['get','post'], '/filter_booking_com', [BookingController::class, 'filter_booking_com'])->name('filter_booking_com');

    Route::get('/statement',[BookingController::class, 'statement'])->name('booking_com.statement');

    Route::post('/payout',[BookingController::class, 'payout'])->name('booking_com.payout');

    Route::get('/receivable',[BookingController::class, 'receivable'])->name('booking_com.receivable');

    Route::match(['get','post'], '/filter_statement', [BookingController::class, 'filter_statement'])->name('filter_statement');

    Route::match(['get','post'], '/filter_receivable', [BookingController::class, 'filter_receivable'])->name('filter_receivable');

    Route::get('/statement_xlsx',[BookingController::class, 'statement_csv'])->name('booking_com.statement_csv');

    Route::get('/receivableFilter',[BookingController::class, 'receivableFilter'])->name('booking_com.receivableFilter');

    //Reprint Routes

    Route::get('/reprint_cash_receipt/{id}', [BookingController::class, 'reprint_cash_receipt'])->name('reprint_cash_receipt');

    Route::get('/reprint_receipt', [BookingController::class, 'reprint_receipt'])->name('reprint_receipt');

    Route::get('/invoice_reprint/{id}', [BookingController::class, 'invoice_reprint'])->name('invoice_reprint');



    Route::get('contract/{id}/{bookid}',function(){

        return view('contract.contract');

    });

    Route::get('contract-pdf/{id}/{bookid}',function(){

        

        $pdf = PDF::loadView('contract.contract-pdf');

        $pdf->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download(date('d-m-Y').time().'.pdf');

    });    

    Route::get('/partial_amount', [BookingController::class, 'partial_amount'])->name('partial_amount');

    

    // General Setting Controls

    Route::get('/general_setting', [GeneralSetting::class, 'general_setting'])->name('general_setting');
    
    Route::post('vatSetting', [GeneralSetting::class, 'vatSetting'])->name('vatSetting');    

    Route::post('time_duration', [GeneralSetting::class, 'time_duration'])->name('time_duration');  

    Route::post('add_access', [GeneralSetting::class, 'add_access'])->name('add_access');  

    Route::post('assign_access', [GeneralSetting::class, 'assign_access'])->name('assign_access');  

    Route::get('role_delete/{id}', [GeneralSetting::class, 'role_delete'])->name('role_delete');  

    Route::post('role_add', [GeneralSetting::class, 'role_add'])->name('role_add');  

    Route::get('levelType_delete/{id}', [GeneralSetting::class, 'levelType_delete'])->name('levelType_delete');

    // add new code by tayyab @ 06-08-2023
    Route::get('/user_tracking', [GeneralSetting::class, 'user_tracking'])->name('user_tracking');    
    Route::get('/user_tracking_filter', [GeneralSetting::class, 'user_tracking_filter'])->name('user_tracking_filter');    

    // Additioinal Charges added for the booking.com @ 24-02-2025

    Route::post('additionalCharges', [GeneralSetting::class, 'additionalCharges'])->name('additionalCharges'); 

    Route::post('cancelTimeDuration', [GeneralSetting::class, 'cancelTimeDuration'])->name('cancelTimeDuration'); 

    Route::get('new_room_type', [GeneralSetting::class, 'newRoomType'])->name('new_room_type');

    // Reports
    Route::get('/reports', [Reports::class, 'index'])->name('reports');
    Route::post('/ledger', [Reports::class, 'ledger'])->name('ledger');
    Route::get('/export_xlsx/{id}/{fdate}/{tdate}', [Reports::class, 'cash_export'])->name('export_xlsx');
    Route::get('/cash_mail/{id}/{fdate}/{tdate}/{brname}', [Reports::class, 'cash_report_mail'])->name('cash_mail');
    Route::get('/daily_reports', [Reports::class, 'daily_report'])->name('daily_reports');
    Route::post('/daily_report_ledger', [Reports::class, 'daily_report_ledger'])->name('daily_report_ledger');
    Route::get('/daily_report_export/{id}/{fdate}', [Reports::class, 'daily_report_export'])->name('daily_report_export');
    Route::get('/daily_report_mail/{id}/{fdate}/{brname}', [Reports::class, 'daily_report_mail'])->name('daily_report_mail');

    // Collection Reports 22-03-2023-------------------------------------------------------------------------
    Route::get('/collection_reports', [Reports::class, 'collection_reports'])->name('collection_reports');
    Route::post('/collection_ledger', [Reports::class, 'collection_ledger'])->name('collection_ledger');
    Route::get('/collection_export/{id}/{fdate}/{tdate}', [Reports::class, 'collection_export'])->name('collection_export');
    Route::get('/collection_mail/{id}/{fdate}/{tdate}/{brname}', [Reports::class, 'collection_mail'])->name('collection_mail');

    // Booking.com Reports 22-03-2023------------------------------------------------------------------------
    Route::group(['as'=>'bookingComReports.','prefix'=>'bookingComReports'],function(){
        Route::get('/', [ReportsBookingCom::class, 'index'])->name('bookingComReports');
        Route::post('/ledger', [ReportsBookingCom::class, 'ledger'])->name('ledger');
        Route::get('/export_xlsx/{id}/{fdate}/{tdate}', [ReportsBookingCom::class, 'cash_export'])->name('export_xlsx');
        Route::get('/cash_mail/{id}/{fdate}/{tdate}/{brname}', [ReportsBookingCom::class, 'cash_report_mail'])->name('cash_mail');
        Route::get('/daily_reports', [ReportsBookingCom::class, 'daily_report'])->name('daily_reports');
        Route::post('/daily_report_ledger', [ReportsBookingCom::class, 'daily_report_ledger'])->name('daily_report_ledger');
        Route::get('/daily_report_export/{id}/{fdate}', [ReportsBookingCom::class, 'daily_report_export'])->name('daily_report_export');
        Route::get('/daily_report_mail/{id}/{fdate}/{brname}', [ReportsBookingCom::class, 'daily_report_mail'])->name('daily_report_mail');
    });
    

    // handle Petty Cash 08-05-2022

    Route::get('/petty_cash', [PettycashController::class, 'index'])->name('petty_cash');
    Route::post('/store', [PettycashController::class, 'store'])->name('store');
    Route::post('/pettyCashIn', [PettycashController::class, 'pettyCashIn'])->name('pettyCashIn');
    Route::get('/cashDetails/{id}', [PettycashController::class, 'cashDetails'])->name('cashDetails');
    Route::get('/pettyCashedit/{id}', [PettycashController::class, 'pettyCashedit'])->name('pettyCashedit');    
    Route::post('/update_pettycash', [PettycashController::class, 'update_pettycash'])->name('update_pettycash');                
    Route::get('/deleteCash/{id}', [PettycashController::class, 'deleteCash'])->name('deleteCash');                
    Route::get('/pettyCashrpt', [PettycashController::class, 'pettyCashrpt'])->name('pettyCashrpt');
    Route::post('/pettyCashprint', [PettycashController::class, 'pettyCashprint'])->name('pettyCashprint'); 
    Route::post('/pettyCashUpdate', [PettycashController::class, 'pettyCashUpdate'])->name('pettyCashUpdate');


    // handle flexincome 15-05-2022

    Route::get('/flexincome', [flexincomeController::class, 'index'])->name('flexincome');

    Route::post('/store_income', [flexincomeController::class, 'store'])->name('store_income');

    Route::get('/incomeDetails/{id}', [flexincomeController::class, 'income_details'])->name('incomeDetails');   

    Route::get('/flexincomerpt', [flexincomeController::class, 'flexincomerpt'])->name('flexincomerpt');

    Route::post('/flexIncomeprint', [flexincomeController::class, 'flexIncomeprint'])->name('flexIncomeprint');



    Route::get('/status_check/{id}', [RoomsController::class, 'status_check'])->name('status_check');



       // Manage Users

    Route::resource('/manage_users', UserController::class);

    // Manage Branches

    Route::resource('/manage_branches', BranchController::class);

    // Manage Room Status

    Route::resource('/room_status', RoomStatusController::class);

    // Manage Room Types

    Route::get('/room_rates', [RoomTypeController::class, 'room_rates'])->named('room_rates');

    Route::post('/addType', [RoomTypeController::class, 'addType'])->named('addType');

    Route::post('/rates_update', [RoomTypeController::class, 'rates_update'])->named('rates_update');


    Route::resource('/room_types', RoomTypeController::class);

    // Manage Rooms

    // Route::post('/import', [RoomsController::class, 'import'])->name('import');

    Route::resource('/manage_rooms', RoomsController::class);

    // Manage Floors

    Route::resource('/manage_floors', FloorController::class);

    // Manage Booking

    Route::resource('/manage_booking', BookingController::class);

});















