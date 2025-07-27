<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for Articles table (skip if already exists)
        if (Schema::hasTable('articles')) {
            try {
                Schema::table('articles', function (Blueprint $table) {
                    // Index for published articles
                    $table->index(['status', 'created_at'], 'idx_articles_status_created');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            
            try {
                Schema::table('articles', function (Blueprint $table) {
                    // Index for grade level and subject
                    $table->index(['grade_level', 'subject_id'], 'idx_articles_grade_subject');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            
            try {
                Schema::table('articles', function (Blueprint $table) {
                    // Index for visit count (for popular articles)
                    $table->index(['status', 'visit_count'], 'idx_articles_status_visits');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            
            try {
                Schema::table('articles', function (Blueprint $table) {
                    // Index for author
                    $table->index('author_id', 'idx_articles_author');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // Add indexes for News table
        if (Schema::hasTable('news')) {
            try {
                Schema::table('news', function (Blueprint $table) {
                    $table->index(['is_active', 'created_at'], 'idx_news_active_created');
                    $table->index('category_id', 'idx_news_category');
                    $table->index('is_featured', 'idx_news_featured');
                    $table->index('views', 'idx_news_views');
                });
            } catch (\Exception $e) {
                // Indexes already exist, skip
            }
        }

        // Add indexes for Events table
        if (Schema::hasTable('events')) {
            try {
                Schema::table('events', function (Blueprint $table) {
                    $table->index('event_date', 'idx_events_date');
                    $table->index(['event_date', 'created_at'], 'idx_events_date_created');
                });
            } catch (\Exception $e) {
                // Indexes already exist, skip
            }
        }

        // Add indexes for Files table
        if (Schema::hasTable('files')) {
            try {
                Schema::table('files', function (Blueprint $table) {
                    $table->index('article_id', 'idx_files_article');
                    $table->index('file_type', 'idx_files_type');
                });
            } catch (\Exception $e) {
                // Indexes already exist, skip
            }
        }

        // Add indexes for Comments table (if exists)
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                // Index for polymorphic relationship
                $table->index(['commentable_type', 'commentable_id'], 'idx_comments_commentable');
                
                // Index for database field
                $table->index('database', 'idx_comments_database');
                
                // Index for user
                $table->index('user_id', 'idx_comments_user');
            });
        }

        // Add indexes for School Classes table
        if (Schema::hasTable('school_classes')) {
            try {
                Schema::table('school_classes', function (Blueprint $table) {
                    $table->index('grade_level', 'idx_school_classes_grade');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // Add indexes for Subjects table
        if (Schema::hasTable('subjects')) {
            try {
                Schema::table('subjects', function (Blueprint $table) {
                    $table->index('subject_name', 'idx_subjects_name');
                    $table->index('grade_level', 'idx_subjects_grade');
                });
            } catch (\Exception $e) {
                // Indexes already exist, skip
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes for Articles table
        if (Schema::hasTable('articles')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropIndex('idx_articles_status_created');
                $table->dropIndex('idx_articles_grade_subject');
                $table->dropIndex('idx_articles_status_visits');
                $table->dropIndex('idx_articles_author');
                $table->dropIndex('idx_articles_status_grade_created');
            });
        }

        // Drop indexes for News table
        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropIndex('idx_news_active_created');
                $table->dropIndex('idx_news_category');
                $table->dropIndex('idx_news_featured');
                $table->dropIndex('idx_news_views');
            });
        }

        // Drop indexes for Events table
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropIndex('idx_events_date');
                $table->dropIndex('idx_events_date_created');
            });
        }

        // Drop indexes for Files table
        if (Schema::hasTable('files')) {
            Schema::table('files', function (Blueprint $table) {
                $table->dropIndex('idx_files_article');
                $table->dropIndex('idx_files_type');
            });
        }

        // Drop indexes for Comments table (if exists)
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropIndex('idx_comments_commentable');
                $table->dropIndex('idx_comments_database');
                $table->dropIndex('idx_comments_user');
            });
        }

        // Drop indexes for School Classes table
        if (Schema::hasTable('school_classes')) {
            Schema::table('school_classes', function (Blueprint $table) {
                $table->dropIndex('idx_school_classes_grade');
            });
        }

        // Drop indexes for Subjects table
        if (Schema::hasTable('subjects')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->dropIndex('idx_subjects_name');
                $table->dropIndex('idx_subjects_grade');
            });
        }
    }
};
