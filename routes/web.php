<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Home\HomeSliderController;
use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\PortfolioController;
use App\Http\Controllers\Home\BlogCategoryController;
use App\Http\Controllers\Home\BlogController;
use App\Http\Controllers\Home\FooterController;
use App\Http\Controllers\Home\ContactController;

Route::get('/', function () {
    return view('frontend.index');
});


// Admin All routes
Route::controller(AdminController::class)->group(function(){
    Route::get('/admin/logout', 'destroy')->name('admin.logout');
    Route::get('/admin/profile', 'Profile')->name('admin.profile');
    Route::get('/edit/profile', 'EditProfile')->name('edit.profile');
    Route::post('/store/profile', 'StoreProfile')->name('store.profile');
    Route::get('/change/password', 'ChangePassword')->name('change.password');
    Route::post('/update/password', 'updatePassword')->name('update.password');
});


Route::controller(HomeSliderController::class)->group(function(){
    Route::get('/home/slide', 'HomeSlider')->name('home.slide');
    Route::post('/update/slide', 'UpdateSlider')->name('update.slide');

});


Route::controller(AboutController::class)->group(function(){
    Route::get('/about/page', 'AboutPage')->name('about.page');
    Route::post('/update/about', 'updateAbout')->name('update.about');
    Route::get('/about', 'HomeAbout')->name('home.about');
    Route::get('/about/multi/image', 'aboutMultiImage')->name('about.multi.image');
    Route::post('/store/multi/image', 'storeMultiImage')->name('store.multi.image');
    Route::get('/all/multi/image', 'allMultiImage')->name('all.multi.image');
    Route::get('/edit/multi/image/{id}', 'editMultiImage')->name('edit.multi.image');
    Route::post('/update/multi/image/', 'updateMultiImage')->name('update.multi.image');
    // Route::get('/delete/multi/image/{id}', 'deleteMultiImage')->name('delete.multi.image');
});

Route::delete('/delete/multi/image/{id}', [AboutController::class, 'deleteMultiImage'])->name('delete.multi.image');


// Portfolio All routes
Route::controller(PortfolioController::class)->group(function(){
    Route::get('/all/portfolio', 'AllPortfolio')->name('all.portfolio');
    Route::get('/add/portfolio', 'addPortfolio')->name('add.portfolio');
    Route::post('/store/portfolio', 'storePortfolio')->name('store.portfolio');
    Route::get('/portfolio/details/{id}', 'HomePortfolioDetails')->name('home.portfolio.details');

    Route::get('/edit/portfolio/{id}', 'editPortfolio')->name('edit.portfolio');
    Route::post('/update/portfolio', 'updatePortfolio')->name('update.portfolio');

    // Route::get('/delete/portfolio/{id}', 'deletePortfolio')->name('delete.portfolio');

    Route::delete('/delete/portfolio/{id}', [PortfolioController::class, 'deletePortfolio'])->name('delete.portfolio');

});

Route::controller(BlogCategoryController::class)->group(function(){
    Route::get('/all/blog/category', 'allBlogCategory')->name('all.blog.category');
    Route::get('/add/blog/category', 'addBlogCategory')->name('add.blog.category');
    Route::post('/store/blog/category', 'storeBlogCategory')->name('store.blog.category');

    Route::get('/edit/blog/category/{id}', 'editBlogCategory')->name('edit.blog.category');
    Route::post('/update/blog/category', 'updateBlogCategory')->name('update.blog.category');
    Route::delete('/delete/blog/category/{id}', 'deleteBlogCategory')->name('delete.blog.category');

});


Route::controller(BlogController::class)->group(function(){
    Route::get('/all/blog', 'allBlog')->name('all.blog');
    Route::get('/add/blog', 'addBlog')->name('add.blog');
    Route::post('/store/blog', 'storeBlog')->name('store.blog');
    Route::get('/edit/blog/{id}', 'editBlog')->name('edit.blog');
    Route::post('/update/blog', 'updateBlog')->name('update.blog');
    Route::delete('/delete/blog/{id}', 'deleteBlog')->name('delete.blog');
    Route::get('/blog/details/{id}', 'HomeBlogDetails')->name('home.blog.details');
    Route::get('/category/blog/{id}', 'CategoryBlog')->name('category.blog');
    
    Route::get('/blog', 'homeBlog')->name('home.blog');

});

Route::controller(FooterController::class)->group(function(){
    Route::get('/footer/setup', 'footerSetup')->name('footer.setup');
    Route::post('/footer/update', 'footerUpdate')->name('footer.update');
    Route::get('/footer', 'HomeFooter')->name('home.footer');

});

Route::controller(ContactController::class)->group(function(){
    Route::get('/contact', 'contactMe')->name('contact.me');
    Route::post('/store/message', 'storeMessage')->name('store.message');
    Route::get('/contact/message', 'contactMessage')->name('contact.message');

Route::delete('/delete/contact/message/{id}', 'deleteContactmessage')->name('delete.contact.message');


});


Route::get('/dashboard', function () {
    // return view('dashboard');
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
