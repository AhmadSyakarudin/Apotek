<?php
// request mengambil hasil input dari yang kita input
// auth::attempt buat b=mengecek data di database
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// merupakan data dummy -> data palsu
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        User::create([
            'name' => "Admin",
            'email' => "admin@gmail.com",
            'password' => bcrypt("admin123"),
            'role' => "admin"
        ]);

        User::create([
            'name' => "Cashier",
            'email' => "cashier@gmail.com",
            'password' => bcrypt("cashier123"),
            'role' => "cashier"
        ]);

    }
}
