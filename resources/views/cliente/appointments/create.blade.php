<x-app-layout>
    {{-- ===========================
        Header
    ============================ --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Agendar Nueva Cita Médica
        </h2>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

            {{-- Flash success --}}
            @if (session('success'))
                <div class="mb-4 rounded border border-green-300 bg-green-50 text-green-800 px-4 py-2">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="mb-4 rounded border border-red-300 bg-red-50 text-red-800 px-4 py-2">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ===========================
                Formulario
            ============================ --}}
            <form action="{{ route('cliente.appointments.store') }}" method="POST" id="appointment-form" novalidate>
                @csrf

                {{-- ¿Para quién es la cita? --}}
                <fieldset class="mb-6">
                    <legend class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        ¿Para quién es la cita? <span class="text-red-500">*</span>
                    </legend>

                    <div class="grid sm:grid-cols-3 gap-3">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="for" value="me" class="text-blue-600"
                                {{ old('for', 'me') === 'me' ? 'checked' : '' }}>
                            <span>Para mí</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="for" value="dependent_existing" class="text-blue-600"
                                {{ old('for') === 'dependent_existing' ? 'checked' : '' }}>
                            <span>Dependiente existente</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="for" value="dependent_new" class="text-blue-600"
                                {{ old('for') === 'dependent_new' ? 'checked' : '' }}>
                            <span>Nuevo dependiente</span>
                        </label>
                    </div>
                    @error('for')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- Dependiente existente (select) --}}
                <div id="box-dependent-existing" class="mb-6 hidden">
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Selecciona dependiente
                    </label>
                    <select name="patient_id" id="patient_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Selecciona --</option>
                        @foreach ($dependents as $dep)
                            <option value="{{ $dep->id }}" @selected(old('patient_id') == $dep->id)>
                                {{ $dep->name }} {{ $dep->lastname }}
                                @if ($dep->birthdate)
                                    ({{ \Carbon\Carbon::parse($dep->birthdate)->age }} años)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nuevo dependiente (campos) --}}
                <div id="box-dependent-new" class="mb-6 hidden">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nombres <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="dep_name" value="{{ old('dep_name') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                            @error('dep_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Apellidos
                            </label>
                            <input type="text" name="dep_lastname" value="{{ old('dep_lastname') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                            @error('dep_lastname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Fecha de nacimiento <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="dep_birthdate" value="{{ old('dep_birthdate') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                            @error('dep_birthdate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                DPI (opcional)
                            </label>
                            <input type="text" name="dep_dpi" value="{{ old('dep_dpi') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                            @error('dep_dpi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Teléfono
                            </label>
                            <input type="text" name="dep_phone" value="{{ old('dep_phone') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                            @error('dep_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Email
                            </label>
                            <input type="email" name="dep_email" value="{{ old('dep_email') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                            @error('dep_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                {{-- FECHA --}}
                <div class="mb-5">
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Fecha <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" id="date" value="{{ old('date') }}"
                        min="{{ \Carbon\Carbon::today()->toDateString() }}" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Selecciona el día de tu cita.</p>
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- MÉDICOS DISPONIBLES PARA LA FECHA (informativo) --}}
                <div id="doctorsOfDayWrapper" class="mb-5 hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Médicos disponibles ese día
                    </label>
                    <ul id="doctorsOfDay" class="list-disc pl-5 text-sm text-gray-700 dark:text-gray-300 space-y-1">
                    </ul>
                </div>

                {{-- HORA (AJAX) --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between">
                        <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Hora disponible <span class="text-red-500">*</span>
                        </label>
                        <small id="slotsStatus" class="text-xs text-gray-500"></small>
                    </div>

                    {{-- Select sin name: el valor real se envía con inputs ocultos --}}
                    <select id="time"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500"
                        required disabled>
                        <option value="">-- Selecciona una fecha primero --</option>
                    </select>

                    {{-- Inputs ocultos que envía el formulario --}}
                    <input type="hidden" name="time" id="time_hidden" value="{{ old('time') }}">
                    <input type="hidden" name="doctor_id" id="doctor_id_hidden" value="{{ old('doctor_id') }}">

                    {{-- Mensaje con el médico asignado al elegir hora --}}
                    <p id="selectedDoctorInfo" class="mt-2 text-sm text-gray-600 dark:text-gray-300 hidden"></p>

                    @error('time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('doctor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- MOTIVO / SÍNTOMAS --}}
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Motivo o Síntomas
                    </label>
                    <textarea name="notes" id="notes" rows="4"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Describe brevemente los síntomas o motivo de la consulta...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ACCIONES --}}
                <div class="flex justify-end gap-2">
                    <a href="{{ route('cliente.appointments.index') }}"
                        class="px-4 py-2 rounded bg-gray-500 text-white hover:bg-gray-600 transition">
                        Cancelar
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        Guardar Cita
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===========================
        JS: radios, dependientes y slots
    ============================ --}}
    <script>
        (function() {
            // Radios dependientes
            const forRadios = document.querySelectorAll('input[name="for"]');
            const boxDepOld = document.getElementById('box-dependent-existing');
            const boxDepNew = document.getElementById('box-dependent-new');

            function refreshForSections() {
                const val = document.querySelector('input[name="for"]:checked')?.value;
                boxDepOld.classList.add('hidden');
                boxDepNew.classList.add('hidden');
                if (val === 'dependent_existing') boxDepOld.classList.remove('hidden');
                if (val === 'dependent_new') boxDepNew.classList.remove('hidden');
            }
            forRadios.forEach(r => r.addEventListener('change', refreshForSections));
            refreshForSections();

            // Slots / UI refs
            const dateInput = document.getElementById('date');
            const timeSelect = document.getElementById('time');
            const timeHidden = document.getElementById('time_hidden');
            const doctorHidden = document.getElementById('doctor_id_hidden');
            const submitBtn = document.getElementById('submitBtn');
            const slotsStatus = document.getElementById('slotsStatus');

            const doctorsOfDayWrapper = document.getElementById('doctorsOfDayWrapper');
            const doctorsOfDay = document.getElementById('doctorsOfDay');
            const selectedDoctorInfo = document.getElementById('selectedDoctorInfo');

            function resetSlotsUI(message) {
                timeSelect.innerHTML = `<option value="">${message || '-- Selecciona una fecha primero --'}</option>`;
                timeSelect.disabled = true;
                timeHidden.value = '';
                doctorHidden.value = '';
                submitBtn.disabled = true;
                slotsStatus.textContent = '';

                doctorsOfDayWrapper.classList.add('hidden');
                doctorsOfDay.innerHTML = '';
                selectedDoctorInfo.classList.add('hidden');
                selectedDoctorInfo.textContent = '';
            }

            // Cargar slots cuando cambie la fecha
            dateInput.addEventListener('change', async () => {
                const date = dateInput.value;
                if (!date) {
                    resetSlotsUI('-- Selecciona una fecha primero --');
                    return;
                }

                slotsStatus.textContent = 'Cargando horarios...';
                timeSelect.disabled = true;
                timeSelect.innerHTML = '<option value="">Cargando horarios...</option>';
                timeHidden.value = '';
                doctorHidden.value = '';
                submitBtn.disabled = true;

                try {
                    const url = new URL('{{ route('cliente.appointments.slots') }}', window.location
                        .origin);
                    url.searchParams.set('date', date);

                    const res = await fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!res.ok) throw new Error('Error de red');

                    const data = await res.json();

                    if (!data.success || !Array.isArray(data.slots) || data.slots.length === 0) {
                        resetSlotsUI('No hay horarios disponibles para esta fecha');
                        slotsStatus.textContent = 'Sin disponibilidad';
                        return;
                    }

                    // Poblar select con {time, doctor_id, doctor_name}
                    timeSelect.innerHTML = '<option value="">-- Selecciona una hora --</option>';
                    data.slots.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = JSON.stringify({
                            time: s.time,
                            doctor_id: s.doctor_id,
                            doctor_name: s.doctor_name
                        });
                        opt.textContent = `${s.time} — Dr/a. ${s.doctor_name}`;
                        timeSelect.appendChild(opt);
                    });
                    timeSelect.disabled = false;
                    slotsStatus.textContent = `${data.slots.length} horario(s) disponible(s)`;

                    // Listar médicos disponibles del día (si el endpoint los envía)
                    if (Array.isArray(data.doctors) && data.doctors.length > 0) {
                        doctorsOfDay.innerHTML = '';
                        data.doctors.forEach(d => {
                            const li = document.createElement('li');
                            li.textContent = `Dr/a. ${d.name}`;
                            doctorsOfDay.appendChild(li);
                        });
                        doctorsOfDayWrapper.classList.remove('hidden');
                    } else {
                        doctorsOfDayWrapper.classList.add('hidden');
                        doctorsOfDay.innerHTML = '';
                    }

                    selectedDoctorInfo.classList.add('hidden');
                    selectedDoctorInfo.textContent = '';

                } catch (e) {
                    console.error(e);
                    resetSlotsUI('Error al cargar horarios');
                    slotsStatus.textContent = 'Error';
                }
            });

            // Al seleccionar una hora, fijar doctor y hora (inputs ocultos) y habilitar submit
            timeSelect.addEventListener('change', () => {
                if (!timeSelect.value) {
                    timeHidden.value = '';
                    doctorHidden.value = '';
                    submitBtn.disabled = true;
                    selectedDoctorInfo.classList.add('hidden');
                    selectedDoctorInfo.textContent = '';
                    return;
                }

                try {
                    const parsed = JSON.parse(timeSelect.value);
                    timeHidden.value = parsed.time;
                    doctorHidden.value = parsed.doctor_id;
                    submitBtn.disabled = false;

                    selectedDoctorInfo.textContent =
                        `Te atenderá Dr/a. ${parsed.doctor_name} a las ${parsed.time} hrs.`;
                    selectedDoctorInfo.classList.remove('hidden');
                } catch (e) {
                    timeHidden.value = '';
                    doctorHidden.value = '';
                    submitBtn.disabled = true;
                    selectedDoctorInfo.classList.add('hidden');
                    selectedDoctorInfo.textContent = '';
                }
            });

            // Restaurar selección si hubo validación previa
            @if (old('date'))
                (async function restoreOld() {
                    dateInput.dispatchEvent(new Event('change'));
                    setTimeout(() => {
                        const oldTime = @json(old('time'));
                        const oldDoc = @json(old('doctor_id'));
                        if (oldTime && oldDoc) {
                            const options = Array.from(timeSelect.options);
                            const match = options.find(opt => {
                                try {
                                    const val = JSON.parse(opt.value);
                                    return val.time === oldTime && String(val.doctor_id) ===
                                        String(oldDoc);
                                } catch (_) {
                                    return false;
                                }
                            });
                            if (match) {
                                timeSelect.value = match.value;
                                timeSelect.dispatchEvent(new Event('change'));
                            }
                        }
                    }, 600);
                })();
            @endif
        })();
    </script>
</x-app-layout>
