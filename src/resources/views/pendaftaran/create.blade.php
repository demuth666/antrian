<x-layout.app
    :title="__('registration.title')"
    :description="__('registration.description')"
    :skip-text="__('common.skip_to_form')"
    font-set="public"
    body-class="landing"
>
    <x-site-header
        :links="[
            ['label' => __('common.home'), 'href' => route('home')],
            ['label' => __('landing.nav.check'), 'href' => route('check-queue')],
        ]"
    />

    <x-antrian.banner />

    <main id="isi" class="wrap register">
        <header class="register-intro">
            <div class="register-intro-head">
                <x-ui.kicker>{{ __('registration.kicker') }}</x-ui.kicker>
                <h1>{{ __('registration.heading') }}</h1>
            </div>

            <div class="register-intro-aside">
                <p class="lead">{{ __('registration.lead') }}</p>
                <p class="register-meta">{{ __('registration.meta') }}</p>
            </div>
        </header>

        <form
            id="form-pendaftaran"
            class="register-form"
            method="POST"
            action="{{ route('register.store') }}"
            novalidate
            data-msg-incomplete="{{ __('registration.js.incomplete') }}"
            data-msg-processing="{{ __('registration.js.processing') }}"
        >
            @csrf

            <div class="register-sheet">
                <div class="register-sheet-head">
                    <span>{{ __('registration.form_label') }}</span>
                    <span>{{ __('registration.form_parts') }}</span>
                    <span class="register-stamp" aria-hidden="true">{{ __('registration.stamp') }}</span>
                </div>

                <x-form.step number="01" :title="__('registration.category.title')" :description="__('registration.category.description')" heading-id="l-kat">
                    <div class="opts opts--two" role="radiogroup" aria-labelledby="l-kat">
                        @foreach ($kategoriOptions as $value => $label)
                            <x-form.option
                                name="kategori"
                                :value="$value"
                                :checked="old('kategori', 'disabilitas') === $value"
                            >{{ $label }}</x-form.option>
                        @endforeach
                    </div>

                    <x-form.field
                        id="jenis-row"
                        class="field-gap"
                        :label="__('registration.category.disability_label')"
                        for="jenis"
                        error-key="jenis_disabilitas"
                        :hidden="old('kategori', 'disabilitas') !== 'disabilitas'"
                    >
                        <select class="in" id="jenis" name="jenis_disabilitas">
                            <option value="">{{ __('registration.category.disability_placeholder') }}</option>
                            @foreach ($jenisDisabilitasOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('jenis_disabilitas') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </x-form.field>
                </x-form.step>

                <x-form.step number="02" :title="__('registration.identity.title')" :description="__('registration.identity.description')" heading-id="l-diri">
                    <div class="grid2">
                        <x-form.field :label="__('registration.identity.name')" for="nama" :full="true">
                            <input class="in" id="nama" type="text" name="nama" autocomplete="name" value="{{ old('nama') }}" required>
                        </x-form.field>

                        <x-form.field :label="__('registration.identity.nik')" :hint="__('registration.identity.nik_hint')" for="nik">
                            <input class="in" id="nik" type="text" name="nik" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" value="{{ old('nik') }}" required>
                        </x-form.field>

                        <x-form.field :label="__('registration.identity.phone')" :hint="__('registration.identity.phone_hint')" for="hp">
                            <input class="in" id="hp" type="tel" name="hp" autocomplete="tel" inputmode="tel" value="{{ old('hp') }}" required>
                        </x-form.field>

                        <x-form.field :label="__('registration.identity.birth_date')" for="tanggal_lahir">
                            <input class="in" id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                        </x-form.field>

                        <x-form.field :label="__('registration.identity.gender')" for="jenis_kelamin">
                            <select class="in" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">{{ __('registration.identity.gender_placeholder') }}</option>
                                @foreach ($jenisKelaminOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('jenis_kelamin') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </x-form.field>

                        <x-form.field :label="__('registration.identity.address')" for="alamat" :full="true">
                            <textarea class="in" id="alamat" name="alamat" autocomplete="street-address" placeholder="{{ __('registration.identity.address_placeholder') }}" required>{{ old('alamat') }}</textarea>
                        </x-form.field>
                    </div>
                </x-form.step>

                <x-form.step number="03" :title="__('registration.service.title')" :description="__('registration.service.description')" heading-id="l-layanan">
                    <x-form.field :label="__('registration.service.label')" for="layanan" error-key="layanan_id">
                        <select class="in" id="layanan" name="layanan_id" required>
                            <option value="">{{ __('registration.service.placeholder') }}</option>
                            @foreach ($layananOptions as $value => $label)
                                <option value="{{ $value }}" @selected((string) old('layanan_id') === (string) $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </x-form.field>
                </x-form.step>

                <x-form.step number="04" :title="__('registration.assistance.title')" :description="__('registration.assistance.description')" heading-id="l-bantuan">
                    <div class="opts opts--three" role="group" aria-labelledby="l-bantuan">
                        @foreach ($bantuanOptions as $value => $label)
                            <x-form.option
                                type="checkbox"
                                name="bantuan[]"
                                :value="$value"
                                :checked="in_array((string) $value, (array) old('bantuan', []), true)"
                            >{{ $label }}</x-form.option>
                        @endforeach
                    </div>

                    @if ($errors->has('bantuan') || $errors->has('bantuan.*'))
                        <span class="err field-gap" role="alert">{{ $errors->first('bantuan') ?: $errors->first('bantuan.*') }}</span>
                    @endif

                    <x-form.field class="field-gap" :label="__('registration.assistance.companion')" :hint="__('registration.assistance.companion_hint')" for="pend" error-key="nama_pendamping">
                        <input class="in" id="pend" type="text" name="nama_pendamping" value="{{ old('nama_pendamping') }}">
                    </x-form.field>
                </x-form.step>

                <div class="register-submit">
                    <div class="register-submit-action">
                        <x-ui.button type="submit" variant="accent">{{ __('registration.submit') }}</x-ui.button>
                        <span class="err" id="err" role="alert">{{ $errors->first() }}</span>
                    </div>

                    <div class="register-submit-note">
                        <span>{{ __('registration.submit_note_label') }}</span>
                        <strong>{{ __('registration.submit_note_value') }}</strong>
                    </div>
                </div>
            </div>
        </form>

        <p class="register-footnote">
            {{ __('registration.registered') }} <a href="{{ route('check-queue') }}">{{ __('registration.check_queue') }}</a>
        </p>
    </main>

    <x-site-footer />
</x-layout.app>
