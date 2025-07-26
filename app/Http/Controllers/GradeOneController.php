<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Article;
use App\Models\File;
use App\Models\User;
use Exception;

class GradeOneController extends Controller
{
  public function setDatabase(Request $request)
  {
    $request->validate([
      'database' => 'required|string|in:jo,sa,eg,ps'
    ]);


    $request->session()->put('database', $request->input('database'));

    return redirect()->back();
  }

  private function getConnection(Request $request)
  {

    return $request->session()->get('database', 'jo');
  }

  public function index(Request $request)
  {
    $database = $this->getConnection($request);


    $lesson = SchoolClass::on($database)->get();
    $classes = SchoolClass::on($database)->get();

    return view('content.frontend.lesson.index', compact('lesson', 'classes'));
  }

  public function show(Request $request, $database, $id)
  {
    $database = $this->getConnection($request);
    $class = SchoolClass::on($database)->findOrFail($id);
    $lesson = SchoolClass::on($database)->findOrFail($id);

    return view('content.frontend.lesson.show', compact('lesson','class', 'database'));
  }



  public function showSubject(Request $request, $database, $id)
{
  $database = $this->getConnection($request);
  $database = $request->session()->get('database', 'jo');

  // تحميل المادة مع العلاقات المطلوبة
  $subject = Subject::on($database)
      ->with([
          'articles' => function($query) {
              $query->with('files');
          },
          'schoolClass'
      ])
      ->findOrFail($id);
      
  $gradeLevel = $subject->grade_level;
  
  // جلب الفصول الدراسية المرتبطة بالصف الدراسي
  $semesters = Semester::on($database)
      ->where('grade_level', $gradeLevel)
      ->orderBy('semester_name')
      ->get();

  return view('content.frontend.subject.show', compact('subject', 'semesters', 'database'));
}

  public function subjectArticles(Request $request, $database, Subject $subject, Semester $semester, $category)
{
$database = $this->getConnection($request);

// تحديد عدد العناصر لكل صفحة (افتراضي 20، يمكن تغييره)
$perPage = $request->get('per_page', 20);
$perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;

// بناء الاستعلام الأساسي
$query = Article::on($database)
    ->where('subject_id', $subject->id)
    ->where('semester_id', $semester->id)
    ->whereHas('files', function ($query) use ($category) {
        $query->where('file_category', $category);
    })
    ->with(['files' => function ($query) use ($category) {
        $query->where('file_category', $category);
    }]);

// إضافة البحث إذا كان موجود
if ($request->has('search') && !empty($request->search)) {
    $searchTerm = $request->search;
    $query->where(function($q) use ($searchTerm) {
        $q->where('title', 'like', "%{$searchTerm}%")
          ->orWhere('content', 'like', "%{$searchTerm}%");
    });
}

// ترتيب النتائج
$sortBy = $request->get('sort', 'created_at');
$sortOrder = $request->get('order', 'desc');

if (in_array($sortBy, ['created_at', 'title', 'visit_count'])) {
    $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
} else {
    $query->orderBy('created_at', 'desc');
}

// تنفيذ الاستعلام مع pagination
$articles = $query->paginate($perPage)->appends($request->query());

// التأكد من تحميل grade_name من القاعدة الفرعية
$subject->setConnection($database);
$grade_level = $subject->schoolClass->grade_name;

// إضافة متغيرات إضافية للعرض
$totalCount = $articles->total();
$currentPage = $articles->currentPage();
$lastPage = $articles->lastPage();
$from = $articles->firstItem();
$to = $articles->lastItem();

return view('content.frontend.articles.index', compact(
    'articles', 'subject', 'semester', 'category', 'grade_level', 'database',
    'totalCount', 'currentPage', 'lastPage', 'from', 'to', 'perPage'
));

}


  public function showArticle(Request $request, $database, $id)
  {

    $database = $request->input('database', session('database', 'jo'));


    $article = Article::on($database)->with([
      'files:id,article_id,file_path,file_name,file_size,mime_type,file_type',
      'subject',
      'semester',
      'schoolClass',
      'keywords',
      'comments' => function ($query) {
          $query->with(['user', 'reactions']);
      }
  ])->findOrFail($id);


    // Safe access to files with null checks
    $file = null;
    try {
        $file = $article->files()->first();
    } catch (Exception $e) {
        $file = null;
    }
    $category = $file ? ($file->file_category ?? 'articles') : 'articles';

    // Safe access to relationships with null checks
    $subject = $article->subject ?? null;
    $semester = $article->semester ?? null;
    
    // Safe access to grade_level with multiple fallbacks
    $grade_level = 'غير محدد';
    
    // Try to get grade_level from article directly first
    if (!empty($article->grade_level)) {
        $grade_level = $article->grade_level;
    }
    // Then try from schoolClass relationship
    elseif ($article->schoolClass && !empty($article->schoolClass->grade_name)) {
        $grade_level = $article->schoolClass->grade_name;
    }
    // Finally try from subject's schoolClass
    elseif ($subject && $subject->schoolClass && !empty($subject->schoolClass->grade_name)) {
        $grade_level = $subject->schoolClass->grade_name;
    }

    $article->increment('visit_count');

    // Safe access to author with null check
    $author = null;
    try {
        $author = User::on('jo')->find($article->author_id);
    } catch (Exception $e) {
        $author = null;
    }

    // Safe access to keywords with null check
    $keywords = $article->keywords ?? collect();
    
    $contentWithKeywords = $this->replaceKeywordsWithLinks($article->content ?? '', $keywords);
    $article->content = $this->createInternalLinks($article->content ?? '', $keywords);

    // Ensure all variables have safe defaults
    $subject = $subject ?? (object)['subject_name' => 'غير محدد'];
    $semester = $semester ?? (object)['semester_name' => 'غير محدد'];
    $author = $author ?? (object)['name' => 'غير معروف'];
    $database = $database ?? session('database', 'jo');

    return view('content.frontend.articles.show', compact('article', 'subject', 'semester', 'grade_level', 'category', 'database', 'contentWithKeywords', 'author'));
  }


  private function createInternalLinks($content, $keywords)
  {

    $keywordsArray = $keywords->pluck('keyword')->toArray();

    foreach ($keywordsArray as $keyword) {
      $keyword = trim($keyword);
      $database = $database ?? session('database', 'jo');
      $url = route('keywords.indexByKeyword', ['database' => $database, 'keywords' => $keyword]);
      $content = str_replace($keyword, '<a href="' . $url . '">' . $keyword . '</a>', $content);
    }

    return $content;
  }

  private function replaceKeywordsWithLinks($content, $keywords)
  {
    foreach ($keywords as $keyword) {
      $keywordText = $keyword->keyword;

      // جلب قاعدة البيانات بشكل صحيح من المتغير أو الجلسة
      $database = $database ?? session('database', 'jo');

      // تمرير معلمة database بالإضافة إلى keyword
      $keywordLink = route('keywords.indexByKeyword', ['database' => $database, 'keywords' => $keywordText]);

      // استبدال الكلمة الدلالية بالرابط الخاص بها
      $content = preg_replace('/\b' . preg_quote($keywordText, '/') . '\b/', '<a href="' . $keywordLink . '">' . $keywordText . '</a>', $content);
    }


    return $content;
  }



  public function downloadFile(Request $request, $id)
  {
    $database = $this->getConnection($request);


    $file = File::on($database)->findOrFail($id);


    $file->increment('download_count');


    $filePath = storage_path('app/public/' . $file->file_path);
    if (file_exists($filePath)) {
      return response()->download($filePath);
    }

    return redirect()->back()->with('error', 'File not found.');
  }
}
