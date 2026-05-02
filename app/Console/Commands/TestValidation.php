<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

#[Signature('app:test-validation')]
#[Description('Test URL validation for video URLs')]
class TestValidation extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing URL validation for video URLs');

        // Test YouTube embed URL
        $data1 = ['video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'];
        // Test with new validation rules
        $rules = ['video_url' => 'nullable|string|max:500'];
        $validator1 = Validator::make($data1, $rules);

        if ($validator1->fails()) {
            $this->error('YouTube embed URL validation failed: ' . $validator1->errors()->first('video_url'));
        } else {
            $this->info('YouTube embed URL validation passed');
        }

        // Test regular YouTube URL
        $data2 = ['video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'];
        $validator2 = Validator::make($data2, $rules);

        if ($validator2->fails()) {
            $this->error('Regular YouTube URL validation failed: ' . $validator2->errors()->first('video_url'));
        } else {
            $this->info('Regular YouTube URL validation passed');
        }

        // Test local video path with manual validation
        $data3 = ['video_url' => 'videos/sample.mp4'];
        $validator3 = Validator::make($data3, $rules);

        // Manual regex check
        $pattern = '/^(https?:\/\/|videos\/)/';
        $isValid = preg_match($pattern, $data3['video_url']);

        if ($validator3->fails()) {
            $this->error('Local video path validation failed: ' . $validator3->errors()->first('video_url'));
        } else {
            $this->info('Local video path validation passed');
        }

        $this->info('Manual regex check for local path: ' . ($isValid ? 'Valid' : 'Invalid'));
    }
}
