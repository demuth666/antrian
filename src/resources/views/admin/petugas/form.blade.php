<x-layout.panel :title="$petugas->exists ? __('admin.officers.edit_title') : __('admin.officers.create_title')">
    <div class="panel-head">
        <div>
            <p class="panel-crumb">{{ __('admin.officers.form_kicker') }}</p>
            <h1>{{ $petugas->exists ? __('admin.officers.edit_heading') : __('admin.officers.create_heading') }}</h1>
        </div>

        <a class="back-link" href="{{ route('admin.officers.index') }}">
            <x-icon name="arrow-left" />
            {{ __('common.back') }}
        </a>
    </div>

    <div class="form-layout">
        <div class="form-sheet">
            <form
                method="POST"
                action="{{ $petugas->exists ? route('admin.officers.update', ['petugas' => $petugas]) : route('admin.officers.store') }}"
            >
                @csrf
                @if ($petugas->exists)
                    @method('PUT')
                @endif

                <div class="grid2">
                    <x-form.field :label="__('admin.officers.name')" for="name">
                        <input
                            class="in"
                            id="name"
                            type="text"
                            name="name"
                            autocomplete="name"
                            value="{{ old('name', $petugas->name) }}"
                            required
                        >
                    </x-form.field>

                    <x-form.field :label="__('admin.officers.email')" for="email">
                        <input
                            class="in"
                            id="email"
                            type="email"
                            name="email"
                            autocomplete="username"
                            value="{{ old('email', $petugas->email) }}"
                            required
                        >
                    </x-form.field>

                    <x-form.field :label="__('admin.officers.counter')" for="loket_id">
                        <select class="in" id="loket_id" name="loket_id">
                            <option value="">{{ __('admin.officers.counter_placeholder') }}</option>
                            @foreach ($loketOptions as $id => $nama)
                                <option value="{{ $id }}" @selected((string) old('loket_id', $petugas->loket_id) === (string) $id)>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </x-form.field>

                    <x-form.field :label="__('admin.officers.headers.status')" for="aktif">
                        <select class="in" id="aktif" name="aktif" required>
                            <option value="1" @selected((string) old('aktif', $petugas->aktif ?? true) === '1')>{{ __('common.active') }}</option>
                            <option value="0" @selected((string) old('aktif', $petugas->aktif ?? true) === '0')>{{ __('common.inactive') }}</option>
                        </select>
                    </x-form.field>

                    <x-form.field
                        :label="__('admin.officers.password')"
                        :hint="$petugas->exists ? __('admin.officers.password_hint_edit') : __('admin.officers.password_hint_create')"
                        for="password"
                        :full="true"
                    >
                        <input
                            class="in"
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="new-password"
                            @required(! $petugas->exists)
                        >
                    </x-form.field>
                </div>

                <div class="form-actions">
                    <x-ui.button type="submit" variant="accent">{{ __('common.save') }}</x-ui.button>
                    <x-ui.button :href="route('admin.officers.index')" variant="outline">{{ __('common.cancel') }}</x-ui.button>
                </div>
            </form>
        </div>

        <aside class="form-aside">
            <p>{{ __('admin.officers.form_lead') }}</p>
        </aside>
    </div>
</x-layout.panel>
