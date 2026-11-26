<x-layout.panel :title="$layanan->exists ? __('admin.services.edit_title') : __('admin.services.create_title')">
    <div class="panel-head">
        <div>
            <p class="panel-crumb">{{ __('admin.services.form_kicker') }}</p>
            <h1>{{ $layanan->exists ? __('admin.services.edit_title') : __('admin.services.create_title') }}</h1>
        </div>

        <a class="back-link" href="{{ route('admin.services.index') }}">
            <x-icon name="arrow-left" />
            {{ __('common.back') }}
        </a>
    </div>

    <div class="form-layout">
        <div class="form-sheet">
            <form
                method="POST"
                action="{{ $layanan->exists ? route('admin.services.update', ['layanan' => $layanan]) : route('admin.services.store') }}"
            >
                @csrf
                @if ($layanan->exists)
                    @method('PUT')
                @endif

                <div class="grid2">
                    <x-form.field :label="__('admin.services.name')" for="nama" :full="true">
                        <input
                            class="in"
                            id="nama"
                            type="text"
                            name="nama"
                            value="{{ old('nama', $layanan->nama) }}"
                            required
                        >
                    </x-form.field>

                    <x-form.field :label="__('admin.services.prefix')" :hint="__('admin.services.prefix_hint')" for="prefix">
                        <input
                            class="in prefix-input"
                            id="prefix"
                            type="text"
                            name="prefix"
                            maxlength="1"
                            value="{{ old('prefix', $layanan->prefix) }}"
                            required
                        >
                    </x-form.field>

                    <x-form.field :label="__('admin.services.headers.status')" for="aktif">
                        <select class="in" id="aktif" name="aktif" required>
                            <option value="1" @selected((string) old('aktif', $layanan->aktif ?? true) === '1')>{{ __('common.active') }}</option>
                            <option value="0" @selected((string) old('aktif', $layanan->aktif ?? true) === '0')>{{ __('common.inactive') }}</option>
                        </select>
                    </x-form.field>
                </div>

                <div class="form-actions">
                    <x-ui.button type="submit" variant="accent">{{ __('common.save') }}</x-ui.button>
                    <x-ui.button :href="route('admin.services.index')" variant="outline">{{ __('common.cancel') }}</x-ui.button>
                </div>
            </form>
        </div>

        <aside class="form-aside">
            <p>{{ __('admin.services.form_lead') }}</p>
        </aside>
    </div>
</x-layout.panel>
