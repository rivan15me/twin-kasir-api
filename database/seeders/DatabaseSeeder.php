<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KasirSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ─────────────────────────────────────────────────────────────
        $users = [
            [
                'username'   => 'admin1',
                'password'   => Hash::make('admin123'),
                'full_name'  => 'Budi Santoso',
                'role'       => 'admin',
                'photo_path' => '',
            ],
            [
                'username'   => 'admin2',
                'password'   => Hash::make('admin456'),
                'full_name'  => 'Siti Rahayu',
                'role'       => 'admin',
                'photo_path' => '',
            ],
            [
                'username'   => 'superadmin',
                'password'   => Hash::make('super123'),
                'full_name'  => 'Owner Twin',
                'role'       => 'superAdmin',
                'photo_path' => '',
            ],
        ];

        foreach ($users as $u) {
            // Cek by username supaya tidak duplikat jika seeder dijalankan ulang
            if (!DB::table('users')->where('username', $u['username'])->exists()) {
                DB::table('users')->insert(array_merge($u, [
                    'name'       => $u['full_name'],
                    'email'      => $u['username'] . '@local.twin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // ── Menu Categories ───────────────────────────────────────────────────
        $categories = [
            ['id' => 'm1', 'name' => 'Twin Photo & Video',       'icon_emoji' => '📸', 'description' => 'Studio foto & video profesional',  'sort_order' => 1],
            ['id' => 'm2', 'name' => 'Sewain Kamera',            'icon_emoji' => '🎥', 'description' => 'Sewa kamera, drone & perlengkapan', 'sort_order' => 2],
            ['id' => 'm3', 'name' => 'Twin Newborn',             'icon_emoji' => '👶', 'description' => 'Foto newborn & maternity',           'sort_order' => 3],
            ['id' => 'm4', 'name' => 'Twice Photobooth',         'icon_emoji' => '🖼️', 'description' => 'Sewa photobooth & props',           'sort_order' => 4],
            ['id' => 'm5', 'name' => 'Twin Decoration',          'icon_emoji' => '🎊', 'description' => 'Dekorasi event & wedding',           'sort_order' => 5],
            ['id' => 'm6', 'name' => 'Twin Wedding Organizer',   'icon_emoji' => '💍', 'description' => 'Paket pernikahan lengkap',            'sort_order' => 6],
        ];

        foreach ($categories as $c) {
            DB::table('menu_categories')->updateOrInsert(
                ['id' => $c['id']],
                array_merge($c, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // ── Products ──────────────────────────────────────────────────────────
        $now = now();
        $products = [
            // m1 — Twin Photo & Video
            ['id'=>'p1',  'menu_id'=>'m1', 'name'=>'Paket Photoshoot Couple + Cetak',   'description'=>'Foto couple + cetak 4R 10 lembar', 'price'=>1700000, 'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>1, 'emoji'=>'👫',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p2',  'menu_id'=>'m1', 'name'=>'Paket Photoshoot Keluarga + Cetak', 'description'=>'Foto keluarga + cetak A4',          'price'=>1800000, 'discount_pct'=>10, 'operational_cost'=>0, 'is_best_seller'=>1, 'emoji'=>'👨‍👩‍👧‍👦',   'image_path'=>'', 'is_active'=>1],
            ['id'=>'p3',  'menu_id'=>'m1', 'name'=>'Paket Photoshoot Model + Cetak',    'description'=>'Foto model + cetak',                'price'=>1200000, 'discount_pct'=>15, 'operational_cost'=>0, 'is_best_seller'=>1, 'emoji'=>'🧍‍♀️',     'image_path'=>'', 'is_active'=>1],
            ['id'=>'p4',  'menu_id'=>'m1', 'name'=>'Adat Sunda',                         'description'=>'Paket foto adat Sunda',             'price'=>675000,  'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'🎎',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p5',  'menu_id'=>'m1', 'name'=>'Adat Jawa',                          'description'=>'Paket foto adat Jawa',              'price'=>500000,  'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'🎏',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p6',  'menu_id'=>'m1', 'name'=>'Make-Up Artist',                     'description'=>'Jasa MUA profesional',              'price'=>850000,  'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>1, 'emoji'=>'💄',       'image_path'=>'', 'is_active'=>1],
            // m2 — Sewain Kamera
            ['id'=>'p7',  'menu_id'=>'m2', 'name'=>'Kamera DSLR Canon EOS',              'description'=>'Sewa/hari',                         'price'=>300000,  'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'📷',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p8',  'menu_id'=>'m2', 'name'=>'Drone DJI Mini',                     'description'=>'Sewa/hari',                         'price'=>500000,  'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>1, 'emoji'=>'🚁',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p9',  'menu_id'=>'m2', 'name'=>'Flash External',                     'description'=>'Sewa/hari',                         'price'=>100000,  'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'⚡',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p10', 'menu_id'=>'m2', 'name'=>'Tripod Profesional',                 'description'=>'Sewa/hari',                         'price'=>75000,   'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'📐',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p11', 'menu_id'=>'m2', 'name'=>'Lensa 50mm',                         'description'=>'Sewa/hari',                         'price'=>150000,  'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'🔭',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p12', 'menu_id'=>'m2', 'name'=>'Ring Light 18 inch',                 'description'=>'Sewa/hari',                         'price'=>80000,   'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'💡',       'image_path'=>'', 'is_active'=>1],
            // m3 — Twin Newborn
            ['id'=>'p13', 'menu_id'=>'m3', 'name'=>'Paket Newborn Basic',                'description'=>'Foto newborn + cetak',              'price'=>800000,  'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'🍼',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p14', 'menu_id'=>'m3', 'name'=>'Paket Newborn Premium',              'description'=>'Foto newborn full set',             'price'=>1500000, 'discount_pct'=>10, 'operational_cost'=>0, 'is_best_seller'=>1, 'emoji'=>'👶',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p15', 'menu_id'=>'m3', 'name'=>'Paket Maternity',                    'description'=>'Foto kehamilan',                    'price'=>1200000, 'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'🤰',       'image_path'=>'', 'is_active'=>1],
            // m4 — Twice Photobooth
            ['id'=>'p16', 'menu_id'=>'m4', 'name'=>'Sewa Photobooth 3 Jam',             'description'=>'3 jam',                             'price'=>600000,  'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'🖼️',      'image_path'=>'', 'is_active'=>1],
            ['id'=>'p17', 'menu_id'=>'m4', 'name'=>'Sewa Photobooth Full Day',           'description'=>'Seharian',                          'price'=>1200000, 'discount_pct'=>10, 'operational_cost'=>0, 'is_best_seller'=>1, 'emoji'=>'📸',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p18', 'menu_id'=>'m4', 'name'=>'Paket Props & Background',           'description'=>'Set props & latar',                 'price'=>200000,  'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'🎭',       'image_path'=>'', 'is_active'=>1],
            // m5 — Twin Decoration
            ['id'=>'p19', 'menu_id'=>'m5', 'name'=>'Dekorasi Ulang Tahun',               'description'=>'Pesta ulang tahun',                 'price'=>1500000, 'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'🎂',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p20', 'menu_id'=>'m5', 'name'=>'Dekorasi Pernikahan',                'description'=>'Premium',                           'price'=>5000000, 'discount_pct'=>5,  'operational_cost'=>0, 'is_best_seller'=>1, 'emoji'=>'💐',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p21', 'menu_id'=>'m5', 'name'=>'Dekorasi Aqiqah',                    'description'=>'Syukuran aqiqah',                   'price'=>800000,  'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'🌙',       'image_path'=>'', 'is_active'=>1],
            // m6 — Twin Wedding Organizer
            ['id'=>'p22', 'menu_id'=>'m6', 'name'=>'Paket WO Silver',                    'description'=>'Paket dasar',                       'price'=>8000000, 'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'💍',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p23', 'menu_id'=>'m6', 'name'=>'Paket WO Gold',                      'description'=>'Paket lengkap',                     'price'=>15000000,'discount_pct'=>5,  'operational_cost'=>0, 'is_best_seller'=>1, 'emoji'=>'👑',       'image_path'=>'', 'is_active'=>1],
            ['id'=>'p24', 'menu_id'=>'m6', 'name'=>'Paket WO Platinum',                  'description'=>'All-in',                            'price'=>25000000,'discount_pct'=>0,  'operational_cost'=>0, 'is_best_seller'=>0, 'emoji'=>'💎',       'image_path'=>'', 'is_active'=>1],
        ];

        foreach ($products as $p) {
            DB::table('products')->updateOrInsert(
                ['id' => $p['id']],
                array_merge($p, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        $this->command->info('✅ KasirSeeder selesai: ' . count($users) . ' users, ' . count($categories) . ' kategori, ' . count($products) . ' produk.');
    }
}
