<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\SchoolClass;
use App\Models\Category;
use App\Models\News;
use App\Models\File;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
  public function setDatabase(Request $request)
  {
    $request->validate([
      'database' => 'required|string|in:jo,sa,eg,ps'
    ]);


    $request->session()->put('database', $request->input('database'));


    return redirect()->route('home');
  }

  /**
   * Get the current database connection
   */
  private function getDatabaseConnection()
  {
    $database = session('database', 'jo');
    Log::info('Current Database Connection:', ['database' => $database]);
    return $database;
  }

  /**
   *
   */
  public function index(Request $request)
  {
      // Disable output buffering
      if (ob_get_level()) {
          ob_end_clean();
      }

      // Get current database connection from session
      $database = session('database', config('database.default'));

      // Get the current date
      $currentDate = Carbon::now();
      $currentMonth = $currentDate->month;
      $currentYear = $currentDate->year;

      // Get the first day of the month
      $firstDay = Carbon::createFromDate($currentYear, $currentMonth, 1);
      $daysInMonth = $currentDate->daysInMonth;

      // Create calendar array
      $calendar = [];

      // Add days from previous month to align with correct day of week
      $previousMonth = Carbon::createFromDate($currentYear, $currentMonth, 1)->subMonth();
      $daysInPreviousMonth = $previousMonth->daysInMonth;
      $firstDayOfWeek = $firstDay->dayOfWeek;

      // Add previous month's days
      for ($i = $firstDayOfWeek - 1; $i >= 0; $i--) {
          $day = $daysInPreviousMonth - $i;
          $date = $previousMonth->format('Y-m-') . sprintf('%02d', $day);
          $calendar[$date] = [];
      }

      // Add current month's days with events
      for ($day = 1; $day <= $daysInMonth; $day++) {
          $date = $currentDate->format('Y-m-') . sprintf('%02d', $day);

          // Get events for this day from the current database connection
          $events = Event::on($database)
              ->whereDate('event_date', $date)
              ->get()
              ->map(function($event) {
                  return [
                      'id' => $event->id,
                      'title' => $event->title,
                      'description' => $event->description,
                      'date' => $event->event_date,
                  ];
              });

          $calendar[$date] = $events;
      }

      // Add next month's days to complete the calendar grid
      $lastDayOfWeek = Carbon::createFromDate($currentYear, $currentMonth, $daysInMonth)->dayOfWeek;
      $daysToAdd = 6 - $lastDayOfWeek;
      $nextMonth = Carbon::createFromDate($currentYear, $currentMonth, 1)->addMonth();

      for ($day = 1; $day <= $daysToAdd; $day++) {
          $date = $nextMonth->format('Y-m-') . sprintf('%02d', $day);
          $calendar[$date] = [];
      }

      // Get classes for the filter
      $classes = SchoolClass::on($database)->get();

      // Get categories from the current database
      $categories = Category::on($database)
          ->orderBy('id')
          ->get();

      // Get news with categories
      $news = News::on($database)
          ->with('category')
          ->orderBy('created_at', 'desc')
          ->get();

      // Get articles for recent activity section
      $articles = collect(); // Initialize as empty collection
      try {
          // Try to get articles if the model exists
          if (class_exists('\App\Models\Article')) {
              $articles = \App\Models\Article::on($database)
                  ->orderBy('created_at', 'desc')
                  ->limit(10)
                  ->get();
          }
      } catch (\Exception $e) {
          Log::info('Articles model not available or error fetching articles: ' . $e->getMessage());
      }

      // Prepare view data
      $viewData = [
          'classes' => $classes,
          'calendar' => $calendar,
          'categories' => $categories,
          'news' => $news,
          'articles' => $articles,
          'currentMonth' => $currentMonth,
          'currentYear' => $currentYear,
          'database' => $database,
          'icons' => $this->getIcons()
      ];

      // Add user data if authenticated
      if (Auth::check()) {
          $viewData['user'] = Auth::user();
      }

      // Return response without compression
      return response()
          ->view('content.frontend.home', $viewData)
          ->header('Content-Encoding', 'none');

  }

  /**
   * Get icons array for different grade levels
   */
  private function getIcons()
  {
      return [
          '1' => 'ti ti-number-1',
          '2' => 'ti ti-number-2',
          '3' => 'ti ti-number-3',
          '4' => 'ti ti-number-4',
          '5' => 'ti ti-number-5',
          '6' => 'ti ti-number-6',
          '7' => 'ti ti-number-7',
          '8' => 'ti ti-number-8',
          '9' => 'ti ti-number-9',
          '10' => 'ti ti-number-0',
          '11' => 'ti ti-number-1',
          '12' => 'ti ti-number-2',
          'default' => 'ti ti-school'
      ];
  }

  public function about()
  {
    return view('about');
  }

  public function contact()
  {
    return view('contact');
  }

  /**
   * Get calendar events for a specific month and year
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  /**
   * Get real-time statistics for user progress
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getStatistics()
  {
      $database = $this->getDatabaseConnection();
      
      // Get user's statistics based on actual database data
      $stats = [
          'daily_activity' => [
              'hours' => rand(2, 6), // Random hours for now
              'percentage' => rand(50, 90),
          ],
          'weekly_goal' => [
              'percentage' => 0,
              'completed' => 0,
              'total' => 0,
          ],
          'exam_days_left' => rand(5, 30),
          'achievement_points' => 0,
          'file_availability' => [
              'total_classes' => 0,
              'classes_with_files' => 0,
              'percentage' => 0
          ]
      ];

      // Get actual statistics from database
      $user = Auth::user();
      
      // Get statistics for classes and files
      $classes = SchoolClass::on($database)->get();
      $stats['file_availability']['total_classes'] = $classes->count();
      
      $classesWithFiles = [];
      $totalFiles = 0;
      
      foreach ($classes as $class) {
          $fileCount = File::on($database)
              ->whereHas('article', function($query) use ($class) {
                  $query->where('grade_level', $class->id);
              })
              ->count();
          
          if ($fileCount > 0) {
              $classesWithFiles[] = [
                  'class_name' => $class->grade_name,
                  'file_count' => $fileCount
              ];
              $totalFiles += $fileCount;
          }
      }
      
      $stats['file_availability']['classes_with_files'] = count($classesWithFiles);
      $stats['file_availability']['percentage'] = (count($classesWithFiles) / $classes->count() * 100) ?? 0;
      $stats['file_availability']['total_files'] = $totalFiles;
      $stats['file_availability']['classes_details'] = $classesWithFiles;
      
      // Get completed articles vs total articles
      $totalArticles = Article::on($database)->count();
      // TODO: Implement user progress tracking system
      // For now, use placeholder values
      $completedArticles = rand(0, min($totalArticles, 15));
      
      // Get completed files vs total files
      $totalFiles = File::on($database)->count();
      // TODO: Implement user progress tracking system
      // For now, use placeholder values
      $completedFiles = rand(0, min($totalFiles, 20));
      
      // Update statistics with real data
      $stats['weekly_goal']['percentage'] = ($completedArticles / $totalArticles * 100) ?? 0;
      $stats['weekly_goal']['completed'] = $completedArticles;
      $stats['weekly_goal']['total'] = $totalArticles;
      
      $stats['achievement_points'] = $completedFiles * 100; // 100 points per completed file
      
      return response()->json($stats);
  }

  public function getCalendarEvents(Request $request)
  {
    try {
        $month = $request->input('month');
        $year = $request->input('year');

        // Get current database connection from session
        $database = session('database', config('database.default'));

        // Create date objects for the first and last day of the month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Get events for the specified month
        $events = Event::on($database)
            ->whereBetween('event_date', [$startDate, $endDate])
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'date' => $event->event_date,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $events
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch calendar events'
        ], 500);
    }
  }
}
