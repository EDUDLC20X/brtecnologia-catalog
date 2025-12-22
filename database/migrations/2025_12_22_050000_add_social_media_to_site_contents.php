<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Add social media links to site_contents
     */
    public function up()
    {
        $socialContents = [
            [
                'key' => 'contact.social_facebook',
                'section' => 'contact',
                'label' => 'Facebook URL',
                'type' => 'text',
                'value' => null,
                'default_value' => 'https://facebook.com',
                'help_text' => 'Enlace a la página de Facebook (dejar vacío para ocultar)',
                'order' => 60,
            ],
            [
                'key' => 'contact.social_instagram',
                'section' => 'contact',
                'label' => 'Instagram URL',
                'type' => 'text',
                'value' => null,
                'default_value' => 'https://instagram.com',
                'help_text' => 'Enlace al perfil de Instagram (dejar vacío para ocultar)',
                'order' => 61,
            ],
            [
                'key' => 'contact.social_twitter',
                'section' => 'contact',
                'label' => 'Twitter/X URL',
                'type' => 'text',
                'value' => null,
                'default_value' => 'https://twitter.com',
                'help_text' => 'Enlace al perfil de Twitter/X (dejar vacío para ocultar)',
                'order' => 62,
            ],
        ];

        foreach ($socialContents as $content) {
            $exists = DB::table('site_contents')->where('key', $content['key'])->exists();
            if (!$exists) {
                DB::table('site_contents')->insert(array_merge($content, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::table('site_contents')->whereIn('key', [
            'contact.social_facebook',
            'contact.social_instagram',
            'contact.social_twitter',
        ])->delete();
    }
};
