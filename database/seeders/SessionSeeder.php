<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sessions')->insert([
            [
                'id' => '5Ect9N2E68Z6KUDFe8VNSlNCiTIxsjApYoNXg5vI',
                'user_id' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'payload' => 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoibmdkNEVnOE1Ec1lUUzRndHIyYWJSelZINFM2N3hXdFBrdzhWcGJhayI7fQ==',
                'last_activity' => 1773309714,
            ],
            [
                'id' => 'elu0rGAr3QYJfHHPXMXgOJThEmsG2lWb8CyY2aVG',
                'user_id' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'payload' => 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZmJ5RmY5OXFIUDgwQ3VjN1RGM2RYR0F3b2pUc3NMd1hQcG4yeFdHaCI7fQ==',
                'last_activity' => 1773483050,
            ],
            [
                'id' => 'g5WCfjOc2TY8HZHxJfr9ulMyxUwvOn2Q0OfWZEnx',
                'user_id' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'payload' => 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWm1LTWNwWWsxd01KWTE3UGJHUm8xSHFMbGZKaWs5enRnY1E3ZlRTZSI7fQ==',
                'last_activity' => 1773944869,
            ],
            [
                'id' => 'LiQ1VXpUCOi1l0Xfdid76aykGy10vcy8ZBg4GH8y',
                'user_id' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'payload' => 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoidlFXM1dvSGhyNlptR3lCZ2ZrSDl5cFJvT3ZWZVVLT0dyT3BObFo0MSI7fQ==',
                'last_activity' => 1773398574,
            ],
        ]);
    }
}
