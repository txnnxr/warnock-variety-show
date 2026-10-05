<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Show descriptions used to be stored HTML-escaped. They're now stored as
     * plain text and escaped when displayed, so decode the existing ones.
     */
    public function up(): void
    {
        DB::table('shows')->orderBy('id')->each(function ($show) {
            $decoded = htmlspecialchars_decode((string) $show->description, ENT_QUOTES);

            if ($decoded !== $show->description) {
                DB::table('shows')->where('id', $show->id)->update(['description' => $decoded]);
            }
        });
    }

    public function down(): void
    {
        DB::table('shows')->orderBy('id')->each(function ($show) {
            DB::table('shows')->where('id', $show->id)->update(['description' => htmlspecialchars((string) $show->description)]);
        });
    }
};
