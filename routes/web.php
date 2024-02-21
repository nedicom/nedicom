<?php

use App\Http\Controllers\MainpageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\LawyerController;
use App\Http\Controllers\AutocompleteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PostphoneController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UslugiController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ArticlesController as AdminArticleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('', [MainpageController::class, 'main'])->name('Welcome');

Route::get('/my', [MyController::class, 'my'])->middleware('lawyer')->name('my');

Route::post('/message', [MessageController::class, 'send'])->name('message');
Route::post('/messagesent', [MessageController::class, 'sent'])->name('message.sent');
Route::post('/messagegetdata', [MessageController::class, 'getdata'])->name('message.get');

Route::controller(UserController::class)->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/admin/users', 'index')->name('admin.users.list');
        Route::get('/admin/users/{id}/edit', 'edit')->name('admin.users.edit');
        Route::patch('/admin/users/{id}/update', 'update')->name('admin.users.update');
        Route::get('/admin/users/{id}/delete', 'delete')->name('admin.users.delete');
    });
});

Route::controller(AdminArticleController::class)->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/admin/articles', 'index')->name('admin.articles.list');
        Route::get('/admin/articles/{url}/edit', 'edit')->name('admin.articles.edit');
        Route::post('/admin/articles/{url}/update', 'update')->name('admin.articles.update');
        Route::get('/admin/articles/{id}/delete', 'delete')->name('admin.article.delete');
    });
});

Route::post('/phone/send', [PostphoneController::class, 'postphone'])->name('phone/send');

Route::get('/yurist-po-krymenergo', function () {
    return Inertia::render('Welcome', [
    ]);
})->name('krymenergo');


Route::get('/yurist-po-nasledstvu', function () {
    return Inertia::render('Nasledstvo', [
    ]);
})->name('Nasledstvo');

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

Route::get('/lawyers', [LawyerController::class, 'index'])
    ->name('lawyers');
Route::get('/lawyers/{id}', [LawyerController::class, 'lawyer'])
    ->name('lawyer');


    Route::post('search',[AutocompleteController::class,'searchUslugi']);
   

    Route::middleware('auth')->group(function () {
        Route::post('/uslugi/post', [UslugiController::class, 'create'])->name('uslugi.post');
        Route::get('/uslugi/user', [UslugiController::class, 'useruslugi'])->name('uslugi.user');
    });

    Route::controller(UslugiController::class)->group(function () {
        Route::get('/uslugi', 'index')->name('uslugi');
        Route::get('/uslugi/{url}', 'show')->name('uslugi.url');
       // Route::get('/uslugi/user/', 'useruslugi')->name('uslugi.user');
            Route::get('/uslugiadd', 'formadd')->name('uslugi.add');
            Route::post('/uslugi/create', 'create')->name('uslugi.create');
            Route::get('/uslugi/{url}/edit', 'edit')->name('uslugi.edit');
            Route::post('/uslugi/{url}/update', 'update')->name('uslugi.update');          
            Route::get('/uslugi/{url}/delete', 'delete')->name('uslugi.delete');
      });

    Route::get('/policy', function () {
        return Inertia::render('Policy', [
        ]);
    })->name('policy');
      
    Route::controller(ArticleController::class)->group(function () {
        Route::get('/articles', 'index')->name('articles');
        Route::get('/articles/my', 'my')->middleware('lawyer')->name('my.articles');
        Route::get('/articles/{url}', 'articleURL')->name('articles/url');

            Route::get('/articlesadd', 'formadd')->name('articles.add');
            Route::post('/articles/create', 'create')->name('articles/create');
            Route::get('/articles/{url}/edit', 'edit')->name('articles.edit');
            Route::post('/articles/{url}/update', 'update')->name('articles.update');          
            Route::get('/articles/{id}/delete', 'delete')->name('article.delete'); 
      });


    Route::controller(QuestionsController::class)->group(function () {
        Route::get('/questions', 'index')->name('questions');
        Route::get('/questions/my', 'myQuestions')->name('my.questions');
        Route::get('/questions/add', 'questionAdd')->name('questions.add');
        Route::post('/questions/post', 'post')->name('articles/post');
        Route::get('/questions/{url}', 'questionsURL')->name('questions.url');
        Route::get('/question/nonauth', 'questionsNonAuth')->name('questions.nonauth');
        Route::post('/questions/{id}/answer', 'getAIAnswer')->name('questions.AIanswer'); 
        Route::post('/questions/{id}/delete', 'delete')->name('questions.delete'); 
      });

      Route::controller(AiController::class)->group(function () {
        Route::get('/ai', 'ai')->name('ai');
      });

      Route::controller(AnswerController::class)->group(function () {
        Route::get('/my/answers', 'my')->middleware('lawyer')->name('my.answers');
        Route::post('/answerpost', 'post')->name('answer.post');
        Route::post('/aianswerpost', 'aiComment')->name('answer.ia');
      });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profilespec', [ProfileController::class, 'updatespec'])->name('profile.updatespec');
    Route::patch('/deletespec', [ProfileController::class, 'deletespec'])->name('profile.deletespec');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/imagepost', [ImageController::class, 'create'])->name('image.post');
    Route::post('/uslimagepost', [ImageController::class, 'imguslcreate'])->name('uslimage.post');
});

//import
Route::get('import/', [ImportController::class, 'import']);

//import
Route::get('sitemap.xml', [SitemapController::class, 'sitemap']);
Route::get('sitemap/articles.xml', [SitemapController::class, 'articles']);
Route::get('sitemap/lawyers.xml', [SitemapController::class, 'lawyers']);
Route::get('sitemap/uslugi.xml', [SitemapController::class, 'uslugi']);

Route::post('/send/review', [ReviewController::class, 'store'])->name('create.review');

/*Route::get('test', [TestController::class, 'test'])
    ->name('test');*/

require __DIR__.'/auth.php';
