@php
    $dipilih = array_map('intval', (array) old('layanan', $layananDipilih));
@endphp

<x-layout.panel :title="$loket->exists ? __('admin.counters.edit_title') : __('admin.counters.create_title')">
    <div class="panel-head">
        <div>
            <p class="panel-crumb">{{ __('admin.counters.form_kicker') }}</p>
            <h1>{{ $loket->exists ? __('admin.counters.edit_title') : __('admin.counters.create_title') }}</h1>
        </div>

        <a class="back-link" href="{{ route('admin.counters.index') }}">
            <x-icon name="arrow-left" />
            {{ __('common.back') }}
        </a>
    </div>

    <div class="form-layout">
        <div class="form-sheet">
            <form
                method="POST"
                action="{{ $loket->exists ? route('admin.counters.update', ['loket' => $loket]) : route('admin.counters.store') }}"
            >
                @csrf
                @if ($loket->exists)
                    @method('PUT')
                @endif

                <div class="grid2">
                    <x-form.field :label="__('admin.counters.name')" for="nama" :full="true">
                        <input
                            class="in"
                            id="nama"
                            type="text"
                            name="nama"
                            value="{{ old('nama', $loket->nama) }}"
                            required
                        >
                    </x-form.field>

                    <x-form.field :label="__('admin.counters.headers.status')" for="aktif">
                        <select class="in" id="aktif" name="aktif" required>
                            <option value="1" @selected((string) old('aktif', $loket->aktif ?? true) === '1')>{{ __('common.active') }}</option>
                            <option value="0" @selected((string) old('aktif', $loket->aktif ?? true) === '0')>{{ __('common.inactive') }}</option>
                        </select>
                    </x-form.field>
                </div>

                <fieldset class="field field-block">
                    <legend class="lab">{{ __('admin.counters.served_services') }}</legend>

                    @if ($layananOptions === [])
                        <p class="form-note">
                            {{ __('admin.counters.no_services') }}
                            <a href="{{ route('admin.services.create') }}">{{ __('admin.counters.add_service_first') }}</a>.
                        </p>
                    @else
                        <div class="opts">
                            @foreach ($layananOptions as $id => $nama)
                                <x-form.option
                                    type="checkbox"
                                    name="layanan[]"
                                    :value="$id"
                                    :checked="in_array($id, $dipilih, true)"
                                >{{ $nama }}</x-form.option>
                            @endforeach
                        </div>
                    @endif

                    @error('layanan')
                        <span class="err" role="alert">{{ $message }}</span>
                    @enderror
                </fieldset>

                <div class="form-actions">
                    <x-ui.button type="submit" variant="accent">{{ __('common.save') }}</x-ui.button>
                    <x-ui.button :href="route('admin.counters.index')" variant="outline">{{ __('common.cancel') }}</x-ui.button>
                </div>
            </form>
        </div>

        <aside class="form-aside">
            <p>{{ __('admin.counters.form_lead') }}</p>
        </aside>
    </div>
</x-layout.panel>
