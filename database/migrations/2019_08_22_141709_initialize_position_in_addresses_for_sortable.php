<?php

use Illuminate\Database\Migrations\Migration;

class InitializePositionInAddressesForSortable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $position = 1;
        \App\Address::orderBy('created_at', 'desc')
            ->chunk(10, function ($addresses) use (&$position) {
                foreach ($addresses as $address) {
                    $address->update(['position' => $position]);
                    $position++;
                }
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Address::query()->update(['position' => 0]);
    }
}
