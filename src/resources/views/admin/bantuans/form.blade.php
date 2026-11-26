<x-layout.panel :title="$bantuan->exists ? __('admin.assistance.edit_title') : __('admin.assistance.create_title')">
    <div class="panel-head">
        <div>
            <p class="panel-crumb">{{ __('admin.assistance.form_kicker') }}</p>
            <h1>{{ $bantuan->exists ? __('admin.assistance.edit_title') : __('admin.assistance.create_title') }}</h1>
        </div>

        <a class="back-link" href="{{ route('admin.assistance.index') }}">
            <x-icon name="arrow-left" />
            {{ __('common.back') }}
        </a>
    </div>

    <div class="form-layout">
        <div class="form-sheet">
            <form
                method="POST"
                action="{{ $bantuan->exists ? route('admin.assistance.update', ['bantuan' => $bantuan]) : route('admin.assistance.store') }}"
            >
                @csrf
                @if ($bantuan->exists)
                    @method('PUT')
                @endif

                <div class="grid2">
                    <x-form.field :label="__('admin.assistance.name')" for="nama" :full="true">
                        <input
                            class="in"
                            id="nama"
                            type="text"
                            name="nama"
                            value="{{ old('nama', $bantuan->nama) }}"
                            required
                        >
                    </x-form.field>

                    <x-form.field :label="__('admin.assistance.code')" :hint="__('admin.assistance.code_hint')" for="kode">
                        <input
                            class="in"
                            id="kode"
                            type="text"
                            name="kode"
                            value="{{ old('kode', $bantuan->kode) }}"
                            required
                        >
                    </x-form.field>

                    <x-form.field :label="__('admin.assistance.headers.status')" for="aktif">
                        <select class="in" id="aktif" name="aktif" required>
                            <option value="1" @selected((string) old('aktif', $bantuan->aktif ?? true) === '1')>{{ __('common.active') }}</option>
                            <option value="0" @selected((string) old('aktif', $bantuan->aktif ?? true) === '0')>{{ __('common.inactive') }}</option>
                        </select>
                    </x-form.field>
                </div>

                <div class="form-actions">
                    <x-ui.button type="submit" variant="accent">{{ __('common.save') }}</x-ui.button>
                    <x-ui.button :href="route('admin.assistance.index')" variant="outline">{{ __('common.cancel') }}</x-ui.button>
                </div>
            </form>
        </div>

        <aside class="form-aside">
            <p>{{ __('admin.assistance.form_lead') }}</p>
        </aside>
    </div>
</x-layout.panel>
