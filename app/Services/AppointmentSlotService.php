<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleOverride;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class AppointmentSlotService
{
    /**
     * Retorna un arreglo de slots disponibles para una fecha dada.
     * Cada slot: ['doctor_id' => int, 'doctor_name' => string, 'time' => 'HH:MM']
     */
    public function getAvailableSlotsForDate(\DateTimeInterface $date): array
    {
        $dayOfWeek = (int) CarbonImmutable::instance($date)->dayOfWeek; // 0..6

        // Turnos base activos para ese día
        $baseShifts = DoctorSchedule::with('doctor')
            ->where('active', true)
            ->where('day_of_week', $dayOfWeek)
            ->get();

        // Overrides de ese día
        $overrides = DoctorScheduleOverride::with(['doctor', 'replacementDoctor'])
            ->whereDate('date', $date)
            ->get();

        // Construimos bloques de trabajo finales (doctor_id, start, end, slot_minutes)
        $finalBlocks = [];

        foreach ($baseShifts as $shift) {
            $doctorId = $shift->doctor_id;
            $doctorName = optional($shift->doctor)->name ?? 'Médico';
            $start = CarbonImmutable::instance($date)->setTimeFromTimeString($shift->start_time);
            $end   = CarbonImmutable::instance($date)->setTimeFromTimeString($shift->end_time);

            // Aplicar overrides relevantes
            foreach ($overrides as $ov) {

                // Apagar turno completo del doctor
                if ($ov->status === 'off' && $ov->doctor_id === $doctorId && !$ov->start_time && !$ov->end_time) {
                    // anula este bloque
                    $start = $end;
                }

                // Reemplazo del doctor base
                if ($ov->status === 'replaced' && $ov->doctor_id === $doctorId && $ov->replacement_doctor_id) {
                    $doctorId = $ov->replacement_doctor_id;
                    $doctorName = optional($ov->replacementDoctor)->name ?? $doctorName;
                }

                // Turno extra
                if ($ov->status === 'extra' && $ov->replacement_doctor_id) {
                    $extraStart = $ov->start_time
                        ? CarbonImmutable::instance($date)->setTimeFromTimeString($ov->start_time)
                        : $start;
                    $extraEnd = $ov->end_time
                        ? CarbonImmutable::instance($date)->setTimeFromTimeString($ov->end_time)
                        : $end;

                    $finalBlocks[] = [
                        'doctor_id' => $ov->replacement_doctor_id,
                        'doctor_name' => optional($ov->replacementDoctor)->name ?? 'Médico',
                        'start' => $extraStart,
                        'end'   => $extraEnd,
                        'slot_minutes' => $shift->slot_minutes,
                    ];
                }
            }

            if ($start->lt($end)) {
                $finalBlocks[] = [
                    'doctor_id' => $doctorId,
                    'doctor_name' => $doctorName,
                    'start' => $start,
                    'end'   => $end,
                    'slot_minutes' => $shift->slot_minutes,
                ];
            }
        }

        // Citas ya tomadas por doctor en esa fecha
        $takenByDoctor = Appointment::whereDate('date', $date)
            ->get(['doctor_id', 'time'])
            ->groupBy('doctor_id')
            ->map(fn(Collection $c) => $c->pluck('time')->all());

        $available = [];
        foreach ($finalBlocks as $block) {
            $cursor = $block['start'];
            while ($cursor->lt($block['end'])) {
                $slotTime = $cursor->format('H:i:s');
                $isTaken = in_array($slotTime, $takenByDoctor->get($block['doctor_id'], []), true);

                if (!$isTaken) {
                    $available[] = [
                        'doctor_id'   => $block['doctor_id'],
                        'doctor_name' => $block['doctor_name'],
                        'time'        => substr($slotTime, 0, 5), // HH:MM
                    ];
                }
                $cursor = $cursor->addMinutes($block['slot_minutes']);
            }
        }

        // Orden por hora asc
        usort($available, fn($a, $b) => strcmp($a['time'], $b['time']));

        return $available;
    }
}
