<?php

namespace App\Http\Controllers;

use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Lantai yang butuh approval admin + minimal H+2 (harus sinkron dengan ReservationController).
     */
    private const LANTAI_APPROVAL = '19';
    private const MIN_HARI_LANTAI_APPROVAL = 2;

    public function index()
    {
        $rooms = Room::with('photos')
            ->orderBy('lantai')
            ->orderBy('nama')
            ->get();

        // Kelompokkan ruangan per lantai untuk ditampilkan sebagai tab.
        $roomsByLantai = $rooms->groupBy('lantai')->sortKeys();

        return view('lecturer.reservation', [
            'roomsByLantai' => $roomsByLantai,
            'lantaiApproval' => self::LANTAI_APPROVAL,
            'minHariApproval' => self::MIN_HARI_LANTAI_APPROVAL,
        ]);
    }

    public function show($id)
    {
        $room = Room::with('photos')->findOrFail($id);

        return view('lecturer.reservation-detail', compact('room'));
    }
}
