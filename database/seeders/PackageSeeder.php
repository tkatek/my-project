<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'title' => 'Sunset Camel Ride & Mint Tea',
                'category' => 'adventure',
                'description' => 'Leave the rush behind for a gentle camel ride across the Agafay landscape. Pause for photographs as the light softens, then settle into camp for a traditional mint tea.',
                'duration' => '2 hours',
                'price' => 350,
                'image' => 'https://images.pexels.com/photos/36579390/pexels-photo-36579390.jpeg',
                'status' => 'active',
            ],
            [
                'title' => 'Quad Trails & Desert Horizons',
                'category' => 'adventure',
                'description' => 'See another side of Agafay on a guided quad adventure. Start with an introduction to the equipment before following your guide across open tracks.',
                'duration' => '3 hours',
                'price' => 550,
                'image' => 'https://images.pexels.com/photos/36579388/pexels-photo-36579388.jpeg',
                'status' => 'active',
            ],
            [
                'title' => 'Dinner Beneath the Desert Sky',
                'category' => 'food',
                'description' => 'Trade the city lights for an evening at a desert camp. Arrive with time to enjoy the views, gather around the table for a Moroccan dinner.',
                'duration' => '4 hours',
                'price' => 650,
                'image' => 'https://images.pexels.com/photos/25447708/pexels-photo-25447708.jpeg',
                'status' => 'active',
            ],
            [
                'title' => 'The Complete Agafay Evening',
                'category' => 'adventure',
                'description' => 'Make a whole afternoon of it. Combine a guided quad ride with a slower camel experience, pause for the sunset, and end the day over dinner at camp.',
                'duration' => '5 hours',
                'price' => 990,
                'image' => 'https://images.pexels.com/photos/24193958/pexels-photo-24193958.jpeg',
                'status' => 'active',
            ],
            [
                'title' => 'Poolside Pause & Moroccan Lunch',
                'category' => 'relax',
                'description' => 'Give your itinerary a little breathing room. Spend a leisurely day at a desert camp with pool access, time to unwind, and a Moroccan lunch.',
                'duration' => 'Day access',
                'price' => 750,
                'image' => 'https://images.pexels.com/photos/15257132/pexels-photo-15257132.png',
                'status' => 'active',
            ],
            [
                'title' => 'Overnight Camp Under Canvas',
                'category' => 'relax',
                'description' => 'Let your desert day turn into an overnight escape. Spend the evening at camp, enjoy dinner, and wake up to breakfast with a view.',
                'duration' => '1 night',
                'price' => 1490,
                'image' => 'https://images.pexels.com/photos/15258810/pexels-photo-15258810.png',
                'status' => 'active',
            ],
            [
                'title' => 'Stargazing & Fireside Tea',
                'category' => 'relax',
                'description' => 'Enjoy the quieter side of camp after dusk. Settle in with a glass of tea and take time to look up at the night sky.',
                'duration' => '3 hours',
                'price' => 450,
                'image' => 'https://images.pexels.com/photos/15257995/pexels-photo-15257995.png',
                'status' => 'active',
            ],
            [
                'title' => 'Desert Paths & The Art of Tea',
                'category' => 'adventure',
                'description' => 'Get to know the landscape one step at a time on a guided walk. Follow a route suited to your group, stop for photographs, and end with mint tea.',
                'duration' => '2 hours',
                'price' => 290,
                'image' => 'https://images.pexels.com/photos/35910043/pexels-photo-35910043.jpeg',
                'status' => 'active',
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
