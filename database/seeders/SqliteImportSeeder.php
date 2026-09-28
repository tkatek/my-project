<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;
use PDO;

class SqliteImportSeeder extends Seeder
{
    public function run(): void
    {
        if (Package::withTrashed()->exists() || Booking::exists()) {
            $this->command->info('Skipped: MySQL already contains packages or bookings.');

            return;
        }

        $pdo = new PDO('sqlite:'.base_path('your_database'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        foreach ($pdo->query('SELECT * FROM users') as $row) {
            $user = User::firstOrNew(['email' => $row['email']]);
            $user->name = $row['name'];
            $user->password = $row['password'];
            $user->role = 'owner';
            $user->email_verified_at = $row['email_verified_at'];
            $user->remember_token = $row['remember_token'];
            $user->created_at = $row['created_at'];
            $user->updated_at = $row['updated_at'];
            $user->save();
        }

        $idMap = [];
        foreach ($pdo->query('SELECT * FROM packages') as $row) {
            $package = new Package([
                'title' => $row['title'],
                'category' => $row['category'],
                'description' => $row['description'],
                'duration' => $row['duration'],
                'price' => $row['price'],
                'image' => $row['image'],
                'status' => $row['status'],
            ]);
            $package->created_at = $row['created_at'];
            $package->updated_at = $row['updated_at'];
            $package->save();
            $idMap[$row['id']] = $package->id;
        }

        foreach ($pdo->query('SELECT * FROM bookings') as $row) {
            $newPackageId = $idMap[$row['package_id']] ?? null;
            $package = $newPackageId ? Package::find($newPackageId) : null;
            $packageName = $row['package_name'] ?: $package?->title;

            $booking = new Booking([
                'customer_name' => $row['customer_name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'package_id' => $newPackageId,
                'package_name' => $packageName,
                'visit_date' => $row['visit_date'],
                'guests' => $row['guests'],
                'notes' => $row['notes'],
                'status' => $row['status'],
            ]);
            $booking->unit_price = $package?->price;
            $booking->created_at = $row['created_at'];
            $booking->updated_at = $row['updated_at'];
            $booking->save();
        }

        $this->command->info('Imported: '.User::count().' users, '.Package::count().' packages, '.Booking::count().' bookings.');
    }
}
