<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::with(
            'serviceTypes',
            'customers.companyVehicles',
        )->latest()->get();

        foreach ($companies as $company) {

            foreach ($company->customers as $customer) {

                if ($customer->companyVehicles->isEmpty()) {
                    continue;
                }

                $times = ['08:00'];
                for ($i = 0; $i < 16; $i++) {
                    $times[] = Carbon::parse($times[$i])->addMinutes(15)->format('H:i');
                }

                $serviceVariant = $company->serviceTypes->random()->variants->random()->toArray();
                $scheduledDate = now()->addDays(rand(-1, 3))->format('Y-m-d');
                $startsAt = Carbon::parse($scheduledDate . ' ' . $times[rand(0, 16)])->toDateTimeLocalString();
                $endsAt = Carbon::parse($startsAt)->addMinutes($serviceVariant['duration'])->toDateTimeLocalString();
                $booking = [
                    'company_id' => $company->id,
                    'customer_id' => $customer->id,
                    'company_vehicle_id' => $customer->companyVehicles->random()->id,
                    'service_variant_id' => $serviceVariant['id'],
                    'scheduled_date' => $scheduledDate,
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'price' => $serviceVariant['price'],
                    'status' => Arr::random(['pending', 'confirmed']),
                    'notes' => fake()->randomElement([
                        null,
                        fake()->sentence(),
                    ]),
                ];

                Booking::create($booking);
            }
        }
    }
}
