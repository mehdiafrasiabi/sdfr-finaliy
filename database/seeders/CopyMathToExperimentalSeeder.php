<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Copies all curriculum content from Math field (cc_field_id=1) to Experimental field (cc_field_id=2).
 *
 * What it does:
 *  - cc_subjects: copies subjects from math->experimental (with grade mapping)
 *  - cc_chapters: copies chapters for those subjects
 *  - cc_topics: (if table exists) copies topics for those chapters
 *
 * Safe to run multiple times (tries to avoid duplicates by reusing existing rows).
 */
class CopyMathToExperimentalSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $MATH_FIELD_ID = 1;
            $EXP_FIELD_ID  = 2;

            // Map: math cc_grade_id => experimental cc_grade_id (same grade_number, same education_level)
            $gradeMap = [
            1 => 2,
            5 => 6,
            9 => 10
        ];

            if (empty($gradeMap)) {
                $this->command?->warn('No grade mapping found (math -> experimental). Seeder did nothing.');
                return;
            }

            // 1) Copy subjects
            $subjectIdMap = []; // old_subject_id => new_subject_id

            $mathSubjects = DB::table('cc_subjects')
                ->where('cc_field_id', $MATH_FIELD_ID)
                ->get();

            foreach ($mathSubjects as $s) {
                $oldGradeId = (int) $s->cc_grade_id;

                if (!isset($gradeMap[$oldGradeId])) {
                    continue; // no matching grade in experimental
                }

                $newGradeId = (int) $gradeMap[$oldGradeId];

                // Logical uniqueness key: (cc_grade_id, cc_field_id, name)
                $existing = DB::table('cc_subjects')
                    ->where('cc_grade_id', $newGradeId)
                    ->where('cc_field_id', $EXP_FIELD_ID)
                    ->where('name', $s->name)
                    ->first();

                if ($existing) {
                    $newSubjectId = (int) $existing->id;

                    // Optionally keep other fields in sync:
                    DB::table('cc_subjects')->where('id', $newSubjectId)->update([
                        'type'       => $s->type,
                        'order'      => $s->order,
                        'updated_at' => now(),
                    ]);
                } else {
                    $newSubjectId = (int) DB::table('cc_subjects')->insertGetId([
                        'cc_grade_id' => $newGradeId,
                        'cc_field_id' => $EXP_FIELD_ID,
                        'name'        => $s->name,
                        'type'        => $s->type,
                        'order'       => $s->order,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }

                $subjectIdMap[(int)$s->id] = $newSubjectId;
            }

            // 2) Copy chapters
            $chapterIdMap = []; // old_chapter_id => new_chapter_id

            foreach ($subjectIdMap as $oldSubjectId => $newSubjectId) {
                $chapters = DB::table('cc_chapters')
                    ->where('cc_subject_id', $oldSubjectId)
                    ->get();

                foreach ($chapters as $ch) {
                    // Logical uniqueness: (cc_subject_id, name)
                    $existing = DB::table('cc_chapters')
                        ->where('cc_subject_id', $newSubjectId)
                        ->where('name', $ch->name)
                        ->first();

                    if ($existing) {
                        $newChapterId = (int) $existing->id;

                        DB::table('cc_chapters')->where('id', $newChapterId)->update([
                            'order'      => $ch->order,
                            'is_active'  => $ch->is_active,
                            'updated_at' => now(),
                        ]);
                    } else {
                        $newChapterId = (int) DB::table('cc_chapters')->insertGetId([
                            'cc_subject_id' => $newSubjectId,
                            'name'          => $ch->name,
                            'order'         => $ch->order,
                            'is_active'     => $ch->is_active,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ]);
                    }

                    $chapterIdMap[(int)$ch->id] = $newChapterId;
                }
            }

            // 3) Copy topics (if exists)
            if (Schema::hasTable('cc_topics')) {
                foreach ($chapterIdMap as $oldChapterId => $newChapterId) {
                    $topics = DB::table('cc_topics')
                        ->where('cc_chapter_id', $oldChapterId)
                        ->get();

                    foreach ($topics as $t) {
                        // Logical uniqueness: (cc_chapter_id, name)
                        $existing = DB::table('cc_topics')
                            ->where('cc_chapter_id', $newChapterId)
                            ->where('name', $t->name)
                            ->first();

                        if ($existing) {
                            DB::table('cc_topics')->where('id', $existing->id)->update([
                                'order'      => $t->order,
                                'is_active'  => $t->is_active,
                                'updated_at' => now(),
                            ]);
                        } else {
                            DB::table('cc_topics')->insert([
                                'cc_chapter_id' => $newChapterId,
                                'name'          => $t->name,
                                'order'         => $t->order,
                                'is_active'     => $t->is_active,
                                'created_at'    => now(),
                                'updated_at'    => now(),
                            ]);
                        }
                    }
                }
            }

            $this->command?->info('CopyMathToExperimentalSeeder finished successfully.');
        });
    }
}
