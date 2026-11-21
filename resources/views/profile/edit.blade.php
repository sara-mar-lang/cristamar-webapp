<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mi Perfil de Usuario</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-white shadow sm:rounded-xl border">
                <h3 class="text-lg font-bold text-gray-900">Información del Perfil</h3>
                <p class="mt-1 text-sm text-gray-600">Actualiza tu nombre y correo electrónico registrado en CRISTAMAR.</p>

                <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5 max-w-xl">
                    @csrf
                    @method('patch')
                    <div>
                        <x-input-label for="name" value="Nombre completo" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label for="email" value="Correo electrónico" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold px-5 py-2.5 rounded-lg text-xs uppercase tracking-wider">Guardar Cambios</button>
                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-emerald-600 font-semibold">✓ Perfil actualizado correctamente.</p>
                        @endif
                    </div>
                </form>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow sm:rounded-xl border">
                <h3 class="text-lg font-bold text-gray-900">Actualizar Contraseña</h3>
                <p class="mt-1 text-sm text-gray-600">Asegúrate de usar una contraseña segura de al menos 8 caracteres.</p>

                <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5 max-w-xl">
                    @csrf
                    @method('put')
                    <div>
                        <x-input-label for="current_password" value="Contraseña actual" />
                        <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="password" value="Nueva contraseña" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="password_confirmation" value="Confirmar nueva contraseña" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold px-5 py-2.5 rounded-lg text-xs uppercase tracking-wider">Actualizar Contraseña</button>
                        @if (session('status') === 'password-updated')
                            <p class="text-sm text-emerald-600 font-semibold">✓ Contraseña actualizada.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
