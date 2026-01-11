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
use App\Http\Controllers\CityController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\LentaController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\PensionController;
use App\Http\Controllers\YandexController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\LawyerDashboardController;

use App\Http\Middleware\owner;

use App\Http\Controllers\Admin\ArticlesController as AdminArticleController;
use App\Http\Controllers\Admin\UslugiController as AdminUslugiController;
use App\Http\Controllers\Admin\UserController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('', [MainpageController::class, 'main'])->name('Welcome')->middleware('track.utm');

Route::get('/my', [MyController::class, 'my'])->middleware('lawyer')->name('my');

Route::resource('pension', PensionController::class)->middleware('track.utm');

Route::get('/messages', [MessageController::class, 'messages'])->name('messages');
Route::post('/message', [MessageController::class, 'send'])->name('message');
Route::post('/cookie', [MessageController::class, 'cookie'])->name('cookie');
Route::post('/messagesent', [MessageController::class, 'sent'])->name('message.sent');
Route::post('/messagegetdata', [MessageController::class, 'getdata'])->name('message.get');

Route::get('/message/{lawyer_id}', [MessageController::class, 'lawyer_mess'])->name('lawyer.message');
Route::post('/msglwrsend', [MessageController::class, 'lawyer_send'])->name('lawyer.message.send');
Route::post('/msglwrsent', [MessageController::class, 'lawyer_sent'])->name('lawyer.message.sent');
Route::post('/msglwrdata', [MessageController::class, 'lawyer_get'])->name('lawyer.message.get');

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

Route::controller(AdminUslugiController::class)->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/admin/uslugi', 'index')->name('admin.uslugi.list');
        Route::get('/admin/uslugi/{id}/delete', 'delete')->name('admin.uslugi.delete');
    });
});

Route::post('/phone/send', [PostphoneController::class, 'postphone'])->name('phone/send');

Route::get('/yurist-po-krymenergo', function () {
    return Inertia::render('Welcome', []);
})->name('krymenergo');

Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('dashboard');
});

Route::post('search', [AutocompleteController::class, 'searchUslugi']);

Route::middleware('auth')->group(function () {
    Route::post('/uslugi/post', [UslugiController::class, 'create'])->name('uslugi.post');
    Route::get('/uslugi/user', [UslugiController::class, 'useruslugi'])->name('uslugi.user');
});

Route::middleware(['admin'])->group(function () {
    Route::get('/api/search-author', [UslugiController::class, 'searchAuthor'])->name('api.search-author');
    Route::post('/change-author', [UslugiController::class, 'changeAuthor'])->name('api.change-author');
});

Route::controller(ArticleController::class)->group(function () {
    Route::middleware('track.utm')->group(function () {
        Route::get('/articles/{url}', 'articleURL')->name('articles/url');
    });
    Route::get('/generator', 'articleGeneration')->name('article.generator');
    Route::post('/articles/generate', 'generate')->name('article.generate');
    Route::get('/articles/my', 'my')->middleware('lawyer')->name('my.articles');
    Route::get('/articlesadd', 'formadd')->name('articles.add');
    Route::post('/articles/create', 'create')->name('articles/create');
    Route::post('/articles/draft', 'draft')->name('articles/draft');
    Route::post('/articles/image', 'image')->name('articles.image');
    Route::get('/articles/{url}/edit', 'edit')->middleware(owner::class)->name('articles.edit');
    Route::post('/articles/{url}/update', 'update')->name('articles.update');
    Route::post('/articles/{url}/autoupdate', 'autoupdate')->name('articles.autoupdate');
    Route::get('/articles/{id}/delete', 'delete')->name('article.delete');
});

Route::controller(UslugiController::class)->group(function () {
    Route::middleware('track.utm')->group(function () {
        Route::get('/uslugi', 'index')->name('uslugi');
        Route::get('/uslugi/{url}', 'show')->name('uslugi.url');
        Route::get('/uslugi/{city}/{main_usluga}', 'showOfferByMain')->name('offer.main');
        Route::get('/uslugi/{city}/{main_usluga}/{second_usluga}', 'showOfferBysecond')->name('offer.second');
        Route::get('/uslugi/{city}/{main_usluga}/{second_usluga}/{url}', 'showcanonical')->name('uslugi.canonical.url');
    });
    Route::get('/uslugiadd', 'formadd')->name('uslugi.add')->middleware('auth');
    Route::post('/uslugi/create', 'create')->name('uslugi.create');
    Route::get('/usluga/{url}/edit', 'edit')->name('uslugi.edit')->middleware('auth');
    Route::post('/usluga/{url}/update', 'update')->name('uslugi.update');
});

Route::controller(QuestionsController::class)->group(function () {
    Route::middleware('track.utm')->group(function () {
        Route::get('/questions/add', 'questionAdd')->name('questions.add');
        Route::get('/questions/{url}', 'questionsURL')->name('questions.url');        
    });

    Route::get('/questions/similar/{url}', 'similar')->name('questions.similar');
    Route::get('/question/nonauth', 'questionsNonAuth')->name('questions.nonauth');
    Route::get('/questions/my', 'myQuestions')->name('my.questions');
    Route::post('/questions/post', 'post')->name('questions.post');
    Route::post('/questions/update', 'update')->name('questions.update');
    Route::post('/questions/{id}/setusl', 'setUsl')->name('questions.setusl');
    Route::post('/questions/{id}/getlawyer', 'getLawyer')->name('questions.getlawyer');
    Route::post('/questions/{id}/answer', 'getAIAnswer')->name('questions.AIanswer');
    Route::post('/questions/{id}/delete', 'delete')->name('questions.delete');
});

Route::controller(AnswerController::class)->group(function () {
    Route::get('/my/answers', 'my')->middleware('lawyer')->name('my.answers');
    Route::post('/answerpost', 'post')->name('answer.post');
    Route::post('/answer/{id}/delete', 'AnswerDelete')->name('answer.delete');
    Route::post('/aiarticlecommentpost', 'ArticleComment')->name('article.comment.post');
    Route::post('/aiarticlecomment/{id}/delete', 'ArticleCommentDelete')->name('article.comment.delete');
    Route::post('/aianswerpost', 'aiComment')->name('answer.ia');
});

Route::controller(LentaController::class)->group(function () {
    Route::middleware('track.utm')->group(function () {
        Route::get('/liked', 'liked')->name('lenta.liked');
        Route::get('/bookmarked', 'bookmarked')->name('lenta.bookmarked');
        Route::get('/popular', 'popular')->name('lenta.popular');
        Route::get('/new', 'new')->name('lenta.new');
        Route::get('/articles', 'articles')->name('lenta.articles');
        Route::get('/questions', 'questions')->name('lenta.questions');
    });
});

Route::get('/lawyers', [LawyerController::class, 'index'])
    ->name('lawyers')
    ->middleware('track.utm');

Route::get('/lawyers/{id}', [LawyerController::class, 'lawyer'])
    ->name('lawyer')
    ->middleware('track.utm');

Route::get('/clientdashboard', [ClientDashboardController::class, 'dashboard'])->name('clientdashboard')->middleware('track.utm');
Route::get('/lawyerdashboard', [LawyerDashboardController::class, 'dashboard'])->name('lawyerdashboard');

Route::controller(CityController::class)->group(function () {
    //Route::get('/offers/{city}', 'showCities')->name('offers.city');
    //Route::get('/offers/{city}/{main_usluga}/{second_usluga}/{url}', 'showOffer')->name('offer.show');
    //Route::get('/uslugi/{main_usluga}/{second_usluga}/{city}', 'showCityFromUslugi')->name('show.city'); //del
    Route::post('/setcity', 'setCity')->name('set.city');
    Route::get('/getcities', 'getCities')->name('get.cities');
});


/*
Route::controller(OffersController::class)->group(function () {
    Route::get('/offeradd', 'formadd')->name('offer.add');
    Route::post('/offer/create', 'create')->name('offer.create');
    Route::get('/offers/', 'all')->name('offers.all');
});
*/

Route::get('/policy', function () {
    return Inertia::render('Policy', ['auth' => Auth::user()]);
})->name('policy');

Route::get('/cookie', function () {
    return Inertia::render('Cookie', ['auth' => Auth::user()]);
})->name('cookie.page');

Route::get('/personal', function () {
    return Inertia::render('Personal', ['auth' => Auth::user()]);
})->name('personal');


Route::controller(AiController::class)->group(function () {
    Route::get('/ai', 'ai')->name('ai');
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
    Route::post('/uslimagesquare', [ImageController::class, 'square'])->name('square.post');
});


Route::post('/social', [SocialController::class, 'reaction'])->name('reaction');

//aboutcompany
Route::get('contacts/', function () {
    return Inertia::render('About', ['auth' => Auth::user()]);
})->name('contacts');

//offer
Route::get('oferta', function () {
    return Inertia::render('Oferta', ['auth' => Auth::user()]);
})->name('oferta');

//success page
Route::get('success', function () {
    return Inertia::render('Success', ['auth' => Auth::user()]);
})->name('success');

//import
Route::get('import/', [ImportController::class, 'import']);

//sitemap
Route::get('sitemap.xml', [SitemapController::class, 'sitemap']);
Route::get('sitemap/articles.xml', [SitemapController::class, 'articles']);
Route::get('sitemap/lawyers.xml', [SitemapController::class, 'lawyers']);
Route::get('sitemap/questions.xml', [SitemapController::class, 'questions']);

Route::get('sitemap/uslugi-city.xml', [SitemapController::class, 'uslugi_city']);
Route::get('sitemap/uslugi-city-main.xml', [SitemapController::class, 'uslugi_city_main']);
Route::get('sitemap/uslugi-city-main-second.xml', [SitemapController::class, 'uslugi_city_main_second']);
Route::get('sitemap/uslugi-city-main-second-canonical.xml', [SitemapController::class, 'uslugi_city_main_second_canonical']);



//yandex feed
Route::controller(FeedController::class)->group(function () {
    Route::get('feed/simferopol.yml', 'simferopol')->name('feed.simferopol');
    Route::get('feed/moscow.yml', 'moscow')->name('feed.moscow');
    Route::get('feed/old.yml', 'old')->name('old');
});

Route::post('/send/review', [ReviewController::class, 'store'])->name('create.review');

Route::post('/yandexoauth', [YandexController::class, 'yandexoauth'])->name('yandexoauth');

Route::view('/suggest/token', 'suggest-token');

require __DIR__ . '/auth.php';

Route::fallback(function (): void {
    abort(404);
});
