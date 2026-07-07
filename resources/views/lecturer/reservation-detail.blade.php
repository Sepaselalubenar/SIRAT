@extends('layouts.app')

@section('content')

<form action="/reservation/store" method="POST">

    @csrf

    <input type="hidden"
           name="room_id"
           value="{{ $room->id }}">

    <div class="mt-5">

        <label>Tanggal</label>

        <input
            type="date"
            name="tanggal"
            class="border rounded w-full p-2">

    </div>

    <div class="mt-4">

        <label>Jam Mulai</label>

        <input
            type="time"
            name="jam_mulai"
            class="border rounded w-full p-2">

    </div>

    <div class="mt-4">

        <label>Jam Selesai</label>

        <input
            type="time"
            name="jam_selesai"
            class="border rounded w-full p-2">

    </div>

    <div class="mt-4">

        <label>Keperluan</label>

        <textarea
            name="keperluan"
            class="border rounded w-full p-2"></textarea>

    </div>

    <button
        class="bg-blue-600 text-white px-8 py-3 rounded-xl mt-6">

        Reservasi Sekarang

    </button>

</form>

@endsection