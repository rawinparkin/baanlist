<?php

use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\FrontendBlogController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\PaymentController;
// Route::get('/', function () {
//     return view('welcome');
// });

// User Frontend All Route 
Route::get('/', [UserController::class, 'Index'])->name('home.index');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


//---------------------User Dashboard------------------------
Route::middleware('auth')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/user/messages', 'UserMessages')->name('user.messages');
        Route::get('/user/profile', 'UserProfile')->name('user.profile');
        Route::post('/user/profile/store', 'UserProfileStore')->name('user.profile.store');
        Route::post('/user/password/update', 'UserPasswordUpdate')->name('user.password.update');
        Route::get('/user/my/listing/', 'MyListing')->name('user.my.listing');
        Route::get('/user/wishlist/', 'GetWishList')->name('user.wishlist');

        Route::get('/user/buy/package', 'PackagePlan')->name('user.package.plan');
        Route::get('/user/payment/history', 'PaymentHistory')->name('user.payment.history');

        Route::post('/store/package/plan', 'StorePackagePlan')->name('store.package.plan');
        Route::get('/user/receipt/{id}', 'UserReceipt')->name('user.receipt');
        Route::get('/user/choose/plan/{id}', 'ChoosePlan')->name('choose.plan');

        //------------ OMISE ---------------
        Route::post('/generate-promptpay', 'generatePromptPay')->name('generate.promptpay');
        Route::get('/check-promptpay-status',  'checkPromptPayStatus');


        //---------- Delete User -------------
        Route::get('/user/delete/user/{id}', 'DeleteUser')->name('delete.user');
    });
    // -------------------Property All Route--------------------
    Route::controller(PropertyController::class)->group(function () {
        Route::get('/user/add/listing', 'AddListing')->middleware('check.credit')->name('add.listing');
        Route::post('/user/gallery/upload', 'UploadGallery');
        Route::get('/user/gallery/list', 'ListGallery');
        Route::delete('/user/gallery/delete/{id}', 'DeletePhoto');
        Route::post('/user/gallery/reorder', 'Reorder');
        Route::post('/user/gallery/set-cover', 'SetCover');
        Route::post('/store/property', 'StoreProperty')->name('store.property');
        Route::get('/get-districts/{province_id}', 'getDistricts');
        Route::get('/get-subdistricts/{district_id}', 'getSubDistricts');
        Route::post('/check-address-server', 'checkAddressServer');
        Route::get('/user/edit/listing/{id}', 'EditListing')->name('edit.listing');
        Route::post('/update/property', 'UpdateProperty')->name('update.property');
        Route::get('/user/delete/property/{id}', 'DeleteProperty')->name('user.delete.property');

        Route::get('/add/thumbnails', 'Addthumbnails')->name('add.thumbnalis');
    });

    // -------------------Payment All Route--------------------
    Route::controller(PaymentController::class)->group(function () {
        //----------- Stripe ---------------
        Route::get('/user/check/out/{id}', 'Checkout')->name('check.out');
        Route::post('/stripe/checkout', 'StripeCheckout')->name('stripe.checkout');
        Route::post('/generate-promptpay-stripe', 'generatePromptPayStripe')->name('generate.promptpay.stripe');
        Route::get('/check-payment-stripe/{payment_intent_id}',  'checkPromptPayStripe');
        Route::post('/finalize-promptpay-payment',  'finalizePromptPayPayment');

        //------------ OMISE ---------------
        Route::post('/charge', 'createCharge')->name('charge');
        Route::post('/bank/charge', 'charge')->name('omise.charge');
        Route::post('/promptpay/charge',  'promptpayCharge')->name('omise.promptpay.charge');
    });
});
//--------End User Dashboard--------------------



/// Admin Group Middleware 
Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/all/user', [AdminController::class, 'AdminAllUser'])->name('admin.all.user');
    Route::get('/admin/edit/user/{id}', [AdminController::class, 'AdminEditUser'])->name('admin.edit.user');
    Route::post('/admin/update/user', [AdminController::class, 'AdminUpdateUser'])->name('admin.update.user');
    Route::post('/admin/update/userpassword', [AdminController::class, 'AdminUpdateUserPassword'])->name('admin.update.user.password');
    Route::get('/admin/delete/user/{id}', [AdminController::class, 'AdminDeleteUser'])->name('admin.delete.user');
    Route::post('/admin/search/user', [AdminController::class, 'AdminSearchUser'])->name('admin.search.user');


    Route::controller(AdminPropertyController::class)->group(function () {

        //----------Property Type-----------
        Route::get('/admin/all/type', 'AdminAllType')->name('admin.all.type');
        Route::get('/admin/add/type', 'AdminAddType')->name('admin.add.type');
        Route::get('/admin/edit/type/{id}', 'AdminEditType')->name('admin.edit.type');
        Route::post('/admin/type/store', 'AdminTypeStore')->name('admin.type.store');
        Route::post('/admin/type/update', 'AdminTypeUpdate')->name('admin.type.update');
        Route::get('/admin/delete/type/{id}', 'AdminDeleteType')->name('admin.delete.type');

        //-----------Property Amenity---------------
        Route::get('/admin/all/amenity', 'AdminAllAmenity')->name('admin.all.amenity');
        Route::get('/admin/add/amenity', 'AdminAddAmenity')->name('admin.add.amenity');
        Route::get('/admin/edit/amenity/{id}', 'AdminEditAmenity')->name('admin.edit.amenity');
        Route::post('/admin/store/amenity', 'AdminStoreAmenity')->name('admin.store.amenity');
        Route::post('/admin/update/amenity', 'AdminUpdateAmenity')->name('admin.update.amenity');
        Route::get('/admin/delete/amenity/{id}', 'AdminDeleteAmenity')->name('admin.delete.amenity');

        //-----------Property---------------
        Route::get('/admin/all/property', 'AdminAllProperty')->name('admin.all.property');
        Route::post('/admin/search/property', 'AdminSearchById')->name('admin.search.by.id');
        Route::get('/admin/edit/property/{id}', 'AdminEditProperty')->name('admin.edit.property');
        Route::post('/admin/update/property', 'AdminUpdateProperty')->name('admin.update.property');
        Route::get('/admin/add/property', 'AdminAddProperty')->name('admin.add.property');
        Route::post('/admin/store/property', 'AdminStoreProperty')->name('admin.store.property');
        Route::get('/admin/delete/property/{id}', 'AdminDeleteProperty')->name('admin.delete.property');
    });


    Route::controller(BlogController::class)->group(function () {
        //-----------Blog Category---------------
        Route::get('/all/blog/category', 'AllBlogCategory')->name('all.blog.category');
        Route::get('/add/blog/category', 'AddBlogCategory')->name('add.blog.category');
        Route::get('/edit/blog/category/{id}', 'EditBlogCategory')->name('edit.blog.category');
        Route::post('/blog/category/store', 'BlogCategoryStore')->name('blog.category.store');
        Route::post('/blog/category/update', 'BlogCategoryUpdate')->name('blog.category.update');
        Route::get('/delete/blog/category/{id}', 'DeleteBlogCategory')->name('delete.blog.category');

        //-----------Blog Post---------------
        Route::get('/all/blog/post', 'AllBlogPost')->name('all.blog.post');
        Route::get('/add/blog/post', 'AddBlogPost')->name('add.blog.post');
        Route::post('/search/blog/id', 'searchBlogById')->name('admin.search.blog.id');
        Route::get('/edit/blog/post/{id}', 'EditBlogPost')->name('edit.blog.post');
        Route::post('/blog/post/store', 'BlogPostStore')->name('blog.post.store');
        Route::post('/blog/post/update', 'BlogPostUpdate')->name('blog.post.update');
        Route::get('/delete/blog/post/{id}', 'DeleteBlogPost')->name('delete.blog.post');
        Route::get('/make/blog/thumbnail', 'MakeBlogThumbnail')->name('make.blog.thumbnail');
    });


    Route::controller(SettingController::class)->group(function () {
        //--------------Site Setting-------------
        Route::get('/site/setting', 'SiteSetting')->name('site.setting');
        Route::post('/update/site/setting', 'UpdateSiteSetting')->name('update.site.setting');

        //--------------Location Setting-------------
        Route::get('/admin/all/province', 'AdminAllProvince')->name('admin.all.province');
        Route::get('/admin/edit/province/{id}', 'AdminEditProvince')->name('admin.edit.province');
        Route::post('/admin/province/update', 'AdminProvinceUpdate')->name('admin.province.update');
        Route::post('/admin/district/update', 'AdminDistrictUpdate')->name('admin.district.update');
        Route::post('/admin/subdistrict/update', 'AdminSubdistrictUpdate')->name('admin.subdistrict.update');
        Route::post('/admin/subdistrict/new', 'AdminSubdistrictNew')->name('admin.subdistrict.new');
        Route::get('/get-district-details/{id}', 'getDistrictDetails');
        Route::get('/get-subdistricts/{district_id}',  'getSubdistricts');
        Route::get('/get-subdistrict-details/{id}', 'getSubdistrictDetails');
        Route::get('/admin/delete/subdistrict/{id}',  'AdminDeleteSubdistrict')->name('admin.delete.subdistrict');


        //----------------SEO Setting--------------
        Route::get('/seo/setting', 'SeoSetting')->name('seo.setting');
        Route::post('/update/seo/setting', 'UpdateSeoSetting')->name('update.seo.setting');

        //----------------STMP Setting--------------
        Route::get('/smtp/setting', 'SmtpSetting')->name('smtp.setting');
        Route::post('/update/smpt/setting', 'UpdateSmtpSetting')->name('update.smpt.setting');

        //-------------Message---------------
        Route::get('/admin/all/message', 'AdminAllMessage')->name('admin.all.message');
        Route::get('/admin/delete/message/{id}', 'DeleteMessageBox')->name('delete.message.box');
        Route::get('/admin/show/message/{id}', 'ShowMessageBox')->name('show.message.box');
    });

    Route::controller(PackageController::class)->group(function () {
        //----------All Package-----------
        Route::get('/admin/all/package', 'AdminAllPackage')->name('admin.all.package');
        Route::get('/admin/edit/package/{id}', 'AdminEditPackage')->name('admin.edit.package');
        Route::post('/admin/package/update', 'AdminPackageUpdate')->name('admin.package.update');
    });

    Route::controller(IncomeController::class)->group(function () {
        //----------All Income-----------
        Route::get('/admin/all/income', 'AdminAllIncome')->name('admin.all.income');
        Route::get('/admin/receipt/{id}', 'AdminReceipt')->name('admin.receipt');
    });
}); //End Admin middleware


// All Frontend Index Route
Route::controller(IndexController::class)->group(function () {
    // Search Route
    Route::get('/search', 'SearchProperty')->name('search.property');



    // Details Route
    Route::get('/property-details/{id}/{slug}', 'PropertyDetails')->name('property.details');
    // Profile Route
    Route::get('/users/{identifier}', 'ShowProfile')->name('show.profile');
    // Package Plan
    Route::get('/package', 'ShowPackage')->name('show.package');
    // Contact 
    Route::get('/contact', 'ShowContact')->name('show.contact');
    Route::post('/store/contact/box', 'StoreContact')->name('store.contact.box');
    // Privacy
    Route::get('/term-of-service', 'ShowTerm')->name('show.term');
    Route::get('/privacy-policy', 'ShowPolicy')->name('show.privacy');
});

//------------------ Blog Frontend ------------------
Route::controller(FrontendBlogController::class)->group(function () {
    Route::get('/blog-details/{id}/{slug}', 'BlogDetails')->name('blog.details');
    Route::get('/blog/cat/list/{id}', 'BlogCatList')->name('blog.category');
    Route::get('/blog', 'BlogList')->name('blog.list');
});




// Wishlist Add Route 
Route::post('/add-to-wishList/{property_id}', [WishlistController::class, 'AddToWishList']);
Route::delete('/user/wishlist/delete/{id}',  [WishlistController::class, 'DestroyWishlist'])->name('user.wishlist.delete');

// Chat Post Request Route 
Route::post('/send-message', [ChatController::class, 'SendMsg'])->name('send.msg');
Route::get('/chat/live', [ChatController::class, 'ChatPage'])->name('chat.page');
Route::get('/inbox-users', [ChatController::class, 'InboxUsers']);
Route::get('/user-all', [ChatController::class, 'GetAllUsers']);
Route::get('/conversation/{user_id}', [ChatController::class, 'getConversation']);
Route::get('/api/auth-id', [ChatController::class, 'getAuthUserInfo']);
Route::delete('/conversation/delete/{user}', [ChatController::class, 'DeleteConversation']);
Route::post('/conversation/read/{userId}', [ChatController::class, 'markAsRead']);



//------------- Social Login ---------------
Route::middleware('web')->group(function () {
    Route::get('login/{provider}', [SocialLoginController::class, 'redirectToProvider'])
        ->name('login.provider');
    Route::get('login/{provider}/callback', [SocialLoginController::class, 'handleProviderCallback']);
});



//-------------might not need these old data---------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user/logout', [UserController::class, 'UserLogout'])->name('user.logout');
});




require __DIR__ . '/auth.php';