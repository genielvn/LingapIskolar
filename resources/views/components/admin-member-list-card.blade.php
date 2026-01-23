<div class="w-full max-w-sm" x-data="{ show: false, confirmRevoke: false }">
    <div
        class="flex w-full max-w-sm flex-row items-center gap-4 rounded-2xl bg-white p-6 shadow-sm transition-all hover:shadow-md"
        @click="show = true"
    >
        <img
            src="{{ $member["img_link"] }}"
            class="h-24 w-24 shrink-0 rounded-full border-4 border-white shadow-lg ring-1 ring-zinc-200"
        />
        <div class="min-w-0">
            <p
                class="truncate text-xl font-bold text-zinc-800"
                title="{{ $member["name"] }}"
            >
                {{ $member["name"] }}
            </p>
            <p
                class="truncate text-sm font-medium text-zinc-500"
                title="{{ $member["email"] }}"
            >
                {{ $member["email"] }}
            </p>
        </div>
    </div>

    {{-- Details Modal --}}
    <template x-teleport="body">
        <div
            x-show="show"
            x-transition.opacity
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
        >
            <div
                class="flex flex-col gap-4 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm"
            >
                <div>
                    <x-page-header>
                        <x-slot:header>
                            <div>
                                <h1
                                    class="text-3xl font-black tracking-tight text-zinc-900 uppercase"
                                >
                                    Details
                                </h1>
                            </div>
                        </x-slot>
                        <x-slot:side></x-slot>
                    </x-page-header>
                </div>

                <div class="flex items-center gap-4">
                    <img
                        src="{{ $member["img_link"] }}"
                        class="h-24 w-24 shrink-0 rounded-full border-4 border-white shadow-lg ring-1 ring-zinc-200"
                    />
                    <div class="min-w-0">
                        <p
                            class="truncate text-xl font-bold text-zinc-800"
                            title="{{ $member["name"] }}"
                        >
                            {{ $member["name"] }}
                        </p>
                        <p
                            class="truncate text-sm font-medium text-zinc-500"
                            title="{{ $member["email"] }}"
                        >
                            {{ $member["email"] }}
                        </p>
                    </div>
                </div>

                <div
                    class="mt-4 flex w-full flex-col items-center justify-center gap-4 md:flex-row"
                >
                    <x-button :variant="'secondary'" @click="show = false">
                        Close
                    </x-button>
                    <x-button @click="confirmRevoke = true; show = false">
                        Revoke
                    </x-button>
                </div>
            </div>
        </div>
    </template>

    {{-- Confirmation Modal --}}
    <template x-teleport="body">
        <div
            x-show="confirmRevoke"
            x-transition.opacity
            class="fixed inset-0 z-[110] flex items-center justify-center bg-black/40 backdrop-blur-sm"
        >
            <div
                class="flex w-full max-w-md flex-col gap-6 rounded-2xl border border-zinc-200 bg-white p-6 shadow-lg"
            >
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                        <i class="bi bi-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-zinc-900">Confirm Revoke</h2>
                        <p class="text-sm text-zinc-500">This action cannot be undone</p>
                    </div>
                </div>

                <p class="text-zinc-700">
                    Are you sure you want to revoke 
                    <span class="font-semibold text-zinc-900">{{ $member["title"] }}</span> 
                    role from 
                    <span class="font-semibold text-zinc-900">{{ $member["name"] }}</span>?
                </p>

                <form method="POST" action="/{{ strtolower($member['title']) === 'support manager' ? 'manager' : 'agent' }}/revoke">
                    @csrf
                    @method("PUT")
                    <input type="hidden" value="{{ $member['email'] }}" name="email" id="email" />

                    <div class="flex w-full flex-col gap-3 md:flex-row">
                        <x-button 
                            :variant="'secondary'" 
                            @click="confirmRevoke = false"
                            type="button"
                            class="flex-1"
                        >
                            Cancel
                        </x-button>
                        <x-button 
                            type="submit"
                            class="flex-1 bg-red-600 hover:bg-red-700"
                        >
                            Yes, Revoke Role
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>